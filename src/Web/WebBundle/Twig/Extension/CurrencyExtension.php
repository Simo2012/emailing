<?php
namespace Web\WebBundle\Twig\Extension;

/**
 * Affichage des montants en devise
 *
 * <pre>
 * Philippe 24/10/13 Creation
 * </pre>
 * @author Philippe
 * @version 1.0
 * @package Rubizz
 */
class CurrencyExtension extends \Twig_Extension
{
    /**
     * Liste des devises
     * @var array
     */
    private $currencies;


    /**
     * Constructeur, injection des dépendances
     */
    public function __construct(array $paCurrencies)
    {
        $this->currencies = $paCurrencies;
    } // __construct

    /**
     * (non-PHPdoc)
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions()
    {
        return array(
            'currency_format' => new \Twig_Function_Method($this, 'currencyFormat', array('is_safe' => array('html'))),
            'percent_format' => new \Twig_Function_Method($this, 'percentFormat', array('is_safe' => array('html'))),
        );
    } // getFunctions

    /**
     * Affiche un montant dans la devise du pays
     *
     * @param float  $piAmount  Le montant
     * @param string $psCountry Le pays
     */
    public function currencyFormat($piAmount, $psCountry, $psTag = false)
    {
        if (isset($this->currencies[$psCountry])) {
            $laParams = $this->currencies[$psCountry];
        } else {
            $laParams = $this->currencies['fr'];
        }
        if (!empty($psTag)) {
            $lsBefore = "<{$psTag}>";
            $lsAfter = "</{$psTag}>";
        } else {
            $lsBefore = $lsAfter = '';
        }
        $lsResult = number_format($piAmount, 2, $laParams['decimal'], $laParams['thousand']);
        if ('before' == $laParams['position']) {
            $lsResult = $lsBefore . $laParams['symbol'] . $lsAfter . ($laParams['space'] ? ' ' : '') . $lsResult;
        } else {
            $lsResult .= ($laParams['space'] ? ' ' : '') . $lsBefore . $laParams['symbol'] . $lsAfter;
        }
        return $lsResult;
    } // currencyFormat

    /**
     * Affiche un pourcentage en fonction du pays
     *
     * @param float  $piAmount  Le montant
     * @param string $psCountry Le pays
     */
    public function percentFormat($piAmount, $psCountry, $psTag = false)
    {
        if (isset($this->currencies[$psCountry])) {
            $laParams = $this->currencies[$psCountry];
        } else {
            $laParams = $this->currencies['fr'];
        }
        if (!empty($psTag)) {
            $lsBefore = "<{$psTag}>";
            $lsAfter = "</{$psTag}>";
        } else {
            $lsBefore = $lsAfter = '';
        }
        $lsResult = number_format($piAmount, 2, $laParams['decimal'], $laParams['thousand']);
        $lsResult .= ($laParams['space'] ? ' ' : '') . $lsBefore . '%' . $lsAfter;
        return $lsResult;
    } // percentFormat

    /**
     * (non-PHPdoc)
     * @see Twig_ExtensionInterface::getName()
     */
    public function getName()
    {
        return 'currency';
    } // getName
}
