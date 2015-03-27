<?php
namespace Web\WebBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\Query;
use Doctrine\Common\Persistence\ObjectManager;
/**
 * Envoi des emails de recommandation (publication)
 *
 * <pre>
 * Julien 18/03/2015 Création
 * </pre>
 * @author Julien
 * @version 1.0
 * @package Web
 */
class EmailPublishingCommand extends ContainerAwareCommand
{
    /**
     * Doctrine manager de la base RUBIZZ
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * @var OutputInterface
     */
    protected $output;


    /**
     * Configuration des options du batch
     */
    protected function configure()
    {
        ini_set('mysql.connect_timeout', 300);
        $this->setName('batchs:rubizz:email-publishing')
             ->setDescription('Rubizz\'s emails publishing');
    } // configure

    /**
     * Lancement de l'exécution
     *
     * @param InputInterface $poInput Arguments
     * @param OutputInterface $poOutput Sortie écran
     * @return int|null|void
     */
    protected function execute(InputInterface $poInput, OutputInterface $poOutput)
    {
        // ==== Initialisations ====
        $this->manager = $this->getContainer()->get('doctrine')->getManager();
        $this->output  = $poOutput;

        $this->sendEmails();

        $poOutput->writeln('<comment>Envois des emails</comment>');
        $this->flushQueue();
        $poOutput->writeln('');
        $poOutput->writeln('<comment>Envois terminés</comment>');
    } // execute

    /**
     * Envoi des emails
     */
    protected function sendEmails()
    {
        // ==== Initialisation ====
        $loEmailSender = $this->getContainer()->get('web.web.model.recommendation.email_sender');

        // ==== Lecture des recommandations ====
        $loRecommendations = $this->manager->getRepository('WebWebBundle:Recommendation')
                                           ->getBy(array('type' => 'email', 'toSend' => 1));

        // ==== Envoi des recommandations ====
        foreach ($loRecommendations as $loRecommendation) {
            $loEmailSender->send($loRecommendation);
        }
    } // sendEmails

    /**
     * Flush the swiftmailer queue (spool memory)
     */
    protected function flushQueue()
    {
        $loContainer = $this->getContainer();
        $loMailer = $loContainer->get('mailer');
        $loSpool = $loMailer->getTransport()->getSpool();
        $loTransport = $loContainer->get('swiftmailer.transport.real');

        $loSpool->flushQueue($loTransport);
    } // flushQueue
}
