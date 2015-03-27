<?php
namespace Web\WebBundle\Model\Recommendation;

use Web\WebBundle\Entity\Recommendation;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Web\WebBundle\Model\Tracking\TrackingChain;

/**
 * Envoi des emails de recommandation
 *
 * <pre>
 * Julien 18/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
class EmailSender
{
    /**
     * Modèle de génération d'url de tracking
     *
     * @var AbstractTracking $tracking
     */
    private $tracking;

    private $manager = null;
    private $twig = null;
    private $mailer = null;
    private $translator = null;
    private $locales = array();

    /**
     * Constructeur, injection des dépendances
     *
     * @param EntityManager $poManager
     * @param TwigEngine $poTemplating
     * @param \Swift_Mailer $poMailer
     * @param Translator $poTranslator
     * @param TrackingChain $poTrackingChain
     * @param $paLocales
     */
    public function __construct(
        EntityManager $poManager,
        TwigEngine $poTemplating,
        \Swift_Mailer $poMailer,
        Translator $poTranslator,
        TrackingChain $poTrackingChain,
        $paLocales
    ) {
        $this->manager    = $poManager;
        $this->twig       = $poTemplating;
        $this->mailer     = $poMailer;
        $this->translator = $poTranslator;
        $this->tracking   = $poTrackingChain;
        $this->locales    = $paLocales;
    } // __construct

    /**
     * Envoi l'email de recommandation
     *
     * @param Recommendation $poRecommendation
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function send(Recommendation $poRecommendation)
    {
        // ==== Initialisations ====
        $loOffer    = $poRecommendation->getOffer();
        $loUser     = $poRecommendation->getUser();
        $loContacts = $loUser->getContacts();

        $lsLocale = $this->locales[$loUser->getCountry()];
        if (empty($lsLocale)) {
            $lsLocale = 'en_US';
        }

        // ==== Création de l'email ====
        $liCount = 0;
        foreach ($loContacts as $loContact) {
            // ---- Vérification du statut abonné du contact ---- 
            $lbSubscriber = $loContact->getSubscriber();
            $lbDirectUnsubscribe = $loContact->getDirectUnsubscribe();
            if ($lbSubscriber && !$lbDirectUnsubscribe) {
                // ---- Création du lien de tracking ----
                $loTracking = $this->tracking->getModel($loOffer->getPlatform());
                $loTracking->setWithEmail(true);
                $loTracking->setContact($loContact);
                $lsClickTagUrl = $loTracking->getClickTagUrl($poRecommendation);
                $lsOpenTagUrl = $loTracking->getOpenTagUrl($poRecommendation);
                // ---- Création de l'email ----       
                $laArgs = array(
                    '_locale' => $lsLocale,
                    'offer' => $loOffer,
                    'user' => $loUser,
                    'contact' => $loContact,
                    'clickTagUrl' => $lsClickTagUrl,
                    'openTagUrl' => $lsOpenTagUrl
                );
                $lsSubject = $this->translator
                                  ->trans('admin.rubizz.email.recommendation.subject',
                                          array(
                                              '%firstname%' => $loUser->getFirstname()
                                            ),
                                          'messages',
                                          $lsLocale
                                          );

                $loEmail = \Swift_Message::newInstance()
                    ->setSubject($lsSubject)
                    ->setFrom(array($loUser->getEmail() => $loUser->getFirstname().' '.$loUser->getLastname()))
                    ->setTo($loContact->getEmail())
                    ->setBody(
                        $this->twig->render(
                            "WebWebBundle:Recommendation:email/email_{$lsLocale}.html.twig",
                            $laArgs
                        ),
                        'text/html'
                    );

                // ==== Mise en queue de l'email ====
                $this->mailer->send($loEmail);

                // ==== Enregistrement de la recommandation au contact ====
                if (!$poRecommendation->getContact()->contains($loContact)) {
                    $poRecommendation->addContact($loContact);
                }

                // ==== Mise à jour du status "à envoyer" de la recommandation ====
                $poRecommendation->setToSend(false);

                // ==== Flush ====
                if ($liCount % 20 == 0) {
                    $this->manager->flush();
                }
            }
        }
        $this->manager->flush();
    } // sendEmail
}
