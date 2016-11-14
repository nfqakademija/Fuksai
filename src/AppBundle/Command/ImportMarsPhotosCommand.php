<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.13
 * Time: 20.30
 */

namespace AppBundle\Command;


use AppBundle\Entity\RoverPhoto;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/*
 * Class ImportMarsRoverPhotosCommand
 * Package AppBundle/Command
 */
class ImportMarsPhotosCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:import:mars-photos');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = '2016-11-10';
        $data = $this->getData
            ('https://api.nasa.gov/mars-photos/api/v1/rovers/curiosity/photos?earth_date='.
            $date
            .'&api_key=Mb2wUHphygVlLVqIGgYG5FBcrTcSYrc9Gb1XzG8s');

        foreach ($data['photos'] as $element)
        {
            $image = new RoverPhoto();

            $image->setDate($date);
            $image->setCamera($element['camera']['name']);
            $image->setImgSrc($element['img_src']);
            $image->setRover($element['rover']['name']);

            $this->save($image);
        }
    }

    public function getData($request)
    {
        $json = file_get_contents($request);
        $data = json_decode($json, true);

        return $data;
    }

    private function save(RoverPhoto $photo)
    {
        $em = $this->getEntityManager();
        $em->persist($photo);
        $em->flush();
    }

    private function getEntityManager()
    {
        return $this
            ->getContainer()
            ->get('doctrine')
            ->getManager();
    }
}
