<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.7
 * Time: 18.00
 */

namespace AppBundle\Command;

use AppBundle\Entity\Subscriber;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendNotificationsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:send:notifications')
            ->setDescription('Command sends notifications to all subscribed users');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEntityManager();
        $subscriberList = $em->getRepository('AppBundle:Subscriber')->findAll();
        $mailerService = $this->getContainer()->get('mailer');

        foreach ($subscriberList as $subscriber) {
            /* @var $subscriber Subscriber */
            $mailerService->send($this->createMessage($subscriber));
            $output->writeln('Notification to '.$subscriber->getEmail().' sent');
        }

        $output->writeln('All notifications successfully sent');
    }

    /**
     * @param $user
     * @return \Swift_Mime_MimePart
     */
    private function createMessage(Subscriber $user)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject('News articles')
            ->setFrom('SkyAboveUsTeam@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'emails/notification.html.twig', [
                    'myPath' => 'http://localhost:8000/unsubscribe/user='.$user->getKeyName(),
                ])
            );

        return $message;
    }

    /**
     * @return EntityManager
     */
    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
