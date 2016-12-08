<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.12.7
 * Time: 18.00
 */

namespace AppBundle\Command;

use AppBundle\Entity\Article;
use AppBundle\Entity\Subscriber;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
        $context = $this->getContainer()->get('router')->getContext();
        $context->setHost('localhost:8000');
        $context->setScheme('http');
        $context->setBaseUrl('');

        $em = $this->getEntityManager();

        $today = new \DateTime(date('Y-m-d', strtotime('today')));
        //$today = new \DateTime(date('Y-m-d', strtotime('30 November 2016')));

        $publishedToday = $em->getRepository('AppBundle:Article')->findBy(['publishDate' => $today]);
        if (!empty($publishedToday)) {
            $subscriberList = $em->getRepository('AppBundle:Subscriber')->findAll();
            $mailerService = $this->getContainer()->get('mailer');

            foreach ($subscriberList as $subscriber) {
                /* @var $subscriber Subscriber */
                $mailerService->send($this->createMessage($subscriber, $this->getTitles($publishedToday)));
                $output->writeln('Notification to ' . $subscriber->getEmail() . ' sent');
            }

            $output->writeln('All notifications successfully sent');
        } else {
            $output->writeln('No new articles were published today');
        }
    }

    /**
     * @param $user
     * @return \Swift_Mime_MimePart
     */
    private function createMessage(Subscriber $user, $titleList)
    {
        $baseUrl = $this
            ->getContainer()
            ->get('router')
            ->generate(
                'homepage',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

        $unsubscribeUrl = $this
            ->getContainer()
            ->get('router')
            ->generate(
                'show_unsubscribe',
                ['userKeyName' => $user->getKeyName()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

        $message = \Swift_Message::newInstance()
            ->setSubject('News articles')
            ->setFrom('SkyAboveUsTeam@gmail.com')
            ->setTo($user->getEmail())
            ->setBody(
                $this->getContainer()->get('templating')->render(
                    'emails/notification.html.twig', [
                    'unsubscribePath' => $unsubscribeUrl,
                    'link' => $baseUrl,
                    'titles' => $titleList,
                ])
            );

        return $message;
    }

    private function getTitles($articles)
    {
        $titleList = array();
        foreach ($articles as $article) {
            /* @var $article Article */
            $titleList[] = $article->getTitle();
        }
        return $titleList;
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
