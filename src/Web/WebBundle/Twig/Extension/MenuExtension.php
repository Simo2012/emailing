<?php
namespace Web\WebBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\TwigBundle\Loader\FilesystemLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\Yaml\Yaml;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * Display a menu based on app/config/menu.yml configuration
 *
 * <pre>
 * Philippe 24/04/13 Creation
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package admin
 */
class MenuExtension extends \Twig_Extension
{
    /**
     *
     * @var object
     */
    protected $loader;
    /**
     * The active controller
     * @var object
     */
    protected $controller;
    /**
     * The active route
     * @var string
     */
    protected $activeRoute;
    /**
     * Min menu level to display
     * @var int
     */
    protected $minLevel;
    /**
     * Max menu level to display
     * @var int
     */
    protected $maxLevel;
    /**
     * Display only active branches
     * @var boolean
     */
    protected $onlyActive;
    /**
     * Resource translator
     * @var Symfony\Bundle\FrameworkBundle\Translation\Translator
     */
    protected $translator;
    /**
     * Resource security.context
     * @var Symfony\Component\Security\Core\SecurityContext
     */
    protected $securityContext;
    /**
     * Current environment
     * @var string
     */
    protected $environment;
    /**
     * Current router
     * @var Router
     */
    protected $router;
    protected $menus;

    /**
     * Constructor, dependency injection
     *
     * @param FilesystemLoader $poLoader
     * @param string           $psRootDir App directory
     */
    public function __construct(
        FilesystemLoader $poLoader,
        TranslatorInterface $poTranslator,
        SecurityContext $poContext,
        $psEnvironment,
        Router $poRouter,
        $paMenus
    ) {
        $this->loader = $poLoader;
        $this->translator = $poTranslator;
        $this->securityContext = $poContext;
        $this->environment = $psEnvironment;
        $this->router = $poRouter;
        $this->menus = $paMenus;
    } // __construct

    /**
     * Injection of the active controller
     *
     * @param unknown $poController The active controller
     */
    public function setController($poController)
    {
        $this->controller = $poController;
    } // setController

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'menu' => new \Twig_Function_Method($this, 'getMenu', array('is_safe' => array('html'))),
            'menuActiveOption' => new \Twig_Function_Method(
                $this,
                'getMenuActiveOption',
                array('is_safe' => array('html'))
            ),
        );
    } // getFunctions

    /**
     * Display the first active option of the required menu
     *
     * @param string  $psName       Menu name
     * @param int     $piMinLevel   Min menu level
     * @return string The option label
     */
    public function getMenuActiveOption($psName, $piMinLevel = 0)
    {
        $lsSource = $this->getMenu($psName, $piMinLevel, $piMinLevel, true);
        if (preg_match(
            '/\<li\s+class=\'active\'[^\>]*\>\s*\<a\s[^\>]+\>([^\<]+)\<\/a\>\s*\<\/li\>/',
            $lsSource,
            $laMatches
        )) {
            return $laMatches[1];
        } else {
            return '';
        }
    } // getMenuActiveOption

    /**
     * Display the required menu
     *
     * @param string  $psName       Menu name
     * @param int     $piMinLevel   Min menu level
     * @param int     $piMaxLevel   Max menu level
     * @param boolean $pbOnlyActive Display only active menu branches
     * @return string The XHTML source code
     */
    public function getMenu($psName, $piMinLevel = 0, $piMaxLevel = PHP_INT_MAX, $pbOnlyActive = false)
    {
        // ==== Recherche du menu ====
        // $laConfig = $this->getConfig();
        $laMenu = $this->menus[$psName];
        if (!isset($laMenu)) {
            // ---- Un sous menu peut être appelé avec la notation "." ----
            if (strpos($psName, '.') !== false) {
                $laNames = explode('.', $psName);
                if (isset($laConfig[$laNames[0]]) && isset($laConfig[$laNames[0]]['pages'][$laNames[1]])) {
                    $laConfig = $laConfig[$laNames[0]]['pages'][$laNames[1]];
                } else {
                    return '';
                }
            } else {
                return '';
            }
        } else {
            $laConfig = $this->menus[$psName];
        }

        // ==== Initialisation ====
        if (!isset($laConfig['pages'])) {
            return '';
        } else {
            $this->minLevel = $piMinLevel;
            $this->maxLevel = $piMaxLevel;
            $this->activeRoute = $this->controller[0]->getRequest()->attributes->get('_controller');
            if (empty($this->activeRoute)) {
                $this->activeRoute = '*';
            }
            $this->onlyActive = $pbOnlyActive;
            $lbIgnore = false;
            $lsSource = $this->generateMenu($laConfig['pages'], 0, $lbIgnore);
            return $lsSource;
        }
    } // getMenu

    /**
     * Generate a menu level
     *
     * @param array   $paMenu   Definition of the menu level
     * @param int     $piLevel  The level being read
     * @param boolean $pbActive True if the level is active
     * @return string The XHTML source code
     */
    protected function generateMenu(array $paMenu, $piLevel, &$pbActive)
    {
        // ==== Read menu options ====
        if ($this->testLevel($piLevel)) {
            $lsSource = '<ul>';
        } else {
            $lsSource = '';
        }
        $pbActive = false;
        $lbActiveBranch = false;
        foreach ($paMenu as $laSubmenu) {
            // ---- Test de l'environnement ----
            if (!empty($laSubmenu['environment'])) {
                if ($this->environment != $laSubmenu['environment']) {
                    continue;
                }
            }
            // ---- Check role ----
            $lsRole = isset($laSubmenu['role']) ? preg_replace('/\s+/', '', $laSubmenu['role']) : '';
            if (!$this->checkRoles($lsRole)) {
                continue;
            }
            // ---- Define label and route for the option ----
            $lsLabel = isset($laSubmenu['label']) ? $laSubmenu['label'] : '';
            if (!empty($laSubmenu['route'])) {
                if (!isset($laSubmenu['visible']) || $laSubmenu['visible']) {
                    $lsHref = $this->controller[0]->generateUrl($laSubmenu['route'], array(), true);
                    if (isset($laSubmenu['domain_'.$this->environment])) {
                        $lsHref = preg_replace('/[a-z]+.admin/', $laSubmenu['domain'], $lsHref);
                    }
                } else {
                    $lsHref = '#';
                }
                $loRoute = $this->router->getRouteCollection()->get($laSubmenu['route']);
                $lsController = $loRoute->getDefault('_controller');
            } else {
                $lsHref = '#';
                $lsController = '#';
                $laSubmenu['route'] = '';
            }
            // ---- Read the option sub branch ----
            $lbActive = false;
            $lsSubSource = '';
            if (!empty($laSubmenu['pages'])) {
                $lsSubSource .= $this->generateMenu($laSubmenu['pages'], $piLevel + 1, $lbActive);
            }
            $lbActive = $lbActive || ($lsController == $this->activeRoute);
            $pbActive = $pbActive || $lbActive;
            $lbActiveBranch = $lbActiveBranch || $pbActive;
            // ---- Visible ? ----
            if (isset($laSubmenu['visible']) && !$laSubmenu['visible']) {
                continue;
            }
            // ---- Display an option ----
            if ($this->testLevel($piLevel)) {
                if ($lbActive) {
                    if (empty($laSubmenu['class'])) {
                        $laSubmenu['class'] = 'active';
                    } else {
                        if (is_array($laSubmenu['class'])) {
                            $laSubmenu['class'][] = 'active';
                        } else {
                            $laSubmenu['class'] = array($laSubmenu['class'], 'active');
                        }
                    }
                }
                // ---- Sous option grisée ----
                if (($piLevel > 0) && ('#' == $lsHref)) {
                    if (empty($laSubmenu['class'])) {
                        $laSubmenu['class'] = 'NXO_waiting';
                    } else {
                        if (is_array($laSubmenu['class'])) {
                            $laSubmenu['class'][] = 'NXO_waiting';
                        } else {
                            $laSubmenu['class'] = array($laSubmenu['class'], 'NXO_waiting');
                        }
                    }
                }
                $lbHrefBis = in_array('hrefbis', array_keys($laSubmenu));
                $lsSource .= "<li" . $this->getLiOptions($laSubmenu) .
                    "><a " . $this->getAOptions($laSubmenu, $lsHref) . " href='" . ($lbHrefBis ? '#' : $lsHref) . "'>" .
                    $this->translator->trans($lsLabel) . '</a>';
            }
            $lsSource .= $lsSubSource;
            if ($this->testLevel($piLevel)) {
                $lsSource .= '</li>';
            }
        }
        if ($this->testLevel($piLevel)) {
            $lsSource .= '</ul>';
        }
        if ($this->onlyActive) {
            return $lbActiveBranch ? $lsSource : '';
        } else {
            return $lsSource;
        }
    } // generateMenu

    /**
     * Check the roles for a menu option
     *
     * @param string $psRoles The roles
     * @return boolean True if it's correct
     */
    protected function checkRoles($psRoles)
    {
        // ==== Roles with ! operator ====
        if (!empty($psRoles)) {
            if ('!' == $psRoles[0]) {
                $psRoles = substr($psRoles, 1);
                if ($this->securityContext->isGranted($psRoles)) {
                    return false;
                }

                // ==== Roles with | operator ====
            } elseif ('|' == $psRoles[0]) {
                $laRoles = explode(',', substr($psRoles, 1));
                $lbRight = false;
                foreach ($laRoles as $lsRole) {
                    if ($this->securityContext->isGranted($lsRole)) {
                        $lbRight = true;
                    }
                }
                return $lbRight;

                // ==== Standard roles ====
            } else {
                if (!$this->securityContext->isGranted($psRoles)) {
                    return false;
                }
            }
        }
        return true;
    } // checkRoles

    /**
     * Test the actual menu level
     *
     * @param int $piLevel Menu level too test
     * @return boolean True if it's ok
     */
    protected function testLevel($piLevel)
    {
        return ($piLevel >= $this->minLevel) && ($piLevel <= $this->maxLevel);
    } // testLevel

    /**
     * Get the <li> options defined in the menu.yml
     *
     * @param array $paMenu The definition of the option
     */
    protected function getLiOptions(array $paMenu)
    {
        $paMenu = array_diff_key(
            $paMenu,
            array(
                'label' => null, 'route' => null, 'pages' => null, 'role' => null, 'target' => null, 'hrefbis' => null
            )
        );
        $lsOptions = '';
        foreach ($paMenu as $lsName => $lsValue) {
            if (!is_array($lsValue)) {
                $lsOptions .= " {$lsName}='{$lsValue}'";
            } else {
                $lsOptions .= " {$lsName}='" . implode(' ', $lsValue) . "'";
            }
        }
        return $lsOptions;
    } // getLiOptions

    /**
     * Get the <a> options defined in the menu.yml
     *
     * @param array $paMenu The definition of the option
     */
    protected function getAOptions(array $paMenu, $psHref = '#')
    {
        $paMenu = array_intersect_key($paMenu, array('target' => null, 'hrefbis' => null));
        $lsOptions = '';
        foreach ($paMenu as $lsName => $lsValue) {
            if (!is_array($lsValue)) {
                if ('hrefbis' == strtolower($lsName)) {
                    $lsValue = $psHref;
                }
                $lsOptions .= " {$lsName}='{$lsValue}'";
            } else {
                $lsOptions .= " {$lsName}='" . implode(' ', $lsValue) . "'";
            }
        }
        return $lsOptions;
    } // getAOptions

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'menu';
    } // getName
}
