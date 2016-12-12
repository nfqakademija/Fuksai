<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use AppBundle\Entity\Picture;

/**
 * Class ImportPictureCommand
 * @package Fuksai\src\AppBundle\Command
 */
class ImportPictureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import:apod')
            ->setDescription('Import astronomy picture of the day.')
            ->setHelp('This command finds and imports astronomy picture of the day in the website.');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newAstronomyPictureData = $this->getAstronomyPictureAlongWithInformation();

        $this->createNewAstronomyPictureOfTheDay($newAstronomyPictureData);

        $output->writeln('The astronomy picture of the day was inserted!');
    }

    /**
     * @return array
     */
    private function getAstronomyPictureAlongWithInformation(): array
    {
        $api_key = $this->getApiKey();

        $astronomyPictureData = $this->getDataFromRequest(
            'https://api.nasa.gov/planetary/apod?api_key='.$api_key
        );

        return $astronomyPictureData;
    }

    /**
     * @return string
     */
    private function getApiKey():string
    {
        return $this->getContainer()->getParameter('nasa_api_key_for_astronomy_picture');
    }

    /**
     * @param string $request
     * @return array
     */
    private function getDataFromRequest(string $request): array
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * @param array $newAstronomyPicture
     */
    private function createNewAstronomyPictureOfTheDay(array $newAstronomyPicture)
    {
        // check if new astronomy picture exists in DB and create one if it does not exist
        if (!$this->checkPictureExistence($newAstronomyPicture)) {
            $newAstronomyPicture = $this->createPicture($newAstronomyPicture);
            $this->insertNewPictureToDB($newAstronomyPicture);
        }
    }

    /**
     * @param array $newPicture
     * @return bool
     */
    private function checkPictureExistence(array $newPicture): bool
    {
        $em = $this->getEntityManager();

        $oldPicture = $em->getRepository('AppBundle:Picture')
            ->findOneBy(
                array(
                    'title' => $newPicture['title'],
                )
            );

        if (!empty($oldPicture)) {
            return true;
        }

        return false;
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

    /**
     * @param array $astronomyPicture
     * @return Picture
     */
    private function createPicture(array $astronomyPicture): Picture
    {
        $newsAstronomyPicture = new Picture();

        $newsAstronomyPicture->setTitle($astronomyPicture['title']);
        $newsAstronomyPicture->setExplanation($astronomyPicture['explanation']);
        $newsAstronomyPicture->setUrl($astronomyPicture['hdurl']);
        $newsAstronomyPicture->setStringDate($astronomyPicture['date']);

        return $newsAstronomyPicture;
    }

    /**
     * @param Picture $newPicture
     */
    private function insertNewPictureToDB(Picture $newPicture)
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        $em->persist($newPicture);
        $em->flush();
    }
}
