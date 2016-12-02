<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.18
 * Time: 20.42
 */

namespace AppBundle\Command;

use AppBundle\Entity\PlanetSchedule;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlanetPositionCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:import:planet-position')
            ->setDescription('Gets planet rise and set times');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $calculator = $this->getContainer()->get('app.calculator.rise_set_calculator');
        $schedule = $calculator->getRiseSet('vilnius');
        $em = $this->getEntityManager();

        $todaySchedule = $em->getRepository('AppBundle:PlanetSchedule')->findBy(['date' => date('Y-m-d')]);
        if (!is_null($todaySchedule))
        {
            return 0;
        }

        if (!is_null($em->getRepository('AppBundle:PlanetSchedule')->findAll())) {
            $schedules = $em->getRepository('AppBundle:PlanetSchedule')->findAll();

            foreach ($schedules as $planet) {
                $em->remove($planet);
            }
            $em->flush();

            $output->writeln('Table cleared');
        }

        foreach ($schedule as $planet) {
            $em->persist($planet);
            $output->writeln("Planet ". $planet->getObject() ." data imported");
        }
        $em->flush();
        $output->writeln("Data flushed");
    }

    /**
     * @param $url
     * @return mixed
     */
    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
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
