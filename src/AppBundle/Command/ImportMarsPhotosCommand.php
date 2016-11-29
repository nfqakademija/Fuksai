<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.13
 * Time: 20.30
 */

namespace AppBundle\Command;

use AppBundle\Entity\RoverPhoto;
use Faker\Provider\DateTime;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Class ImportMarsRoverPhotosCommand
 * Package AppBundle/Command
 */
class ImportMarsPhotosCommand extends ContainerAwareCommand
{
    /**
    * {@inheritdoc}
    */
    protected function configure()
    {
        $this
            ->setName('app:import:mars-photos')
            ->setDescription('Import mars rover photos');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Starting Mars Photo Import');

        $api_key = $this->getContainer()->getParameter('nasa_api_key');

        $date = date_create(date('Y-m-d'));
        date_sub($date, date_interval_create_from_date_string('3 days'));
        $date = date_format($date, 'Y-m-d');
        $data = $this->getData(
            'https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.$date
            .'&api_key='.$api_key
        );
        foreach ($data['photos'] as $element) {
            $image = new RoverPhoto();

            $image->setDate($date);
            $image->setCamera($element['camera']['name']);
            $image->setImgSrc($element['img_src']);
            $image->setRover($element['rover']['name']);

            $this->save($image);
        }

        $output->writeln('Import successful!');
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }

    /**
     * @param RoverPhoto $photo
     */
    private function save(RoverPhoto $photo)
    {
        $em = $this->getEntityManager();
        $em->persist($photo);
        $em->flush();
    }

    /**
     * @return mixed
     */
    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
