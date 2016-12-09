<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.18
 * Time: 20.42
 */

namespace AppBundle\Command;

use AppBundle\Entity\PlanetSchedule;
use AppBundle\Exception\NoApiResponseException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
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

        try {
            $schedule = $calculator->getRiseSet('vilnius');
        } catch (NoApiResponseException $e) {
            $output->writeln($e);
            return 1;
        }
        $em = $this->getEntityManager();

        $todaySchedule = $em->getRepository('AppBundle:PlanetSchedule')->findBy(['date' => date('Y-m-d')]);
        if (!empty($todaySchedule)) {
            return 0;
        }

        if (!empty($em->getRepository('AppBundle:PlanetSchedule')->findAll())) {
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
        return 0;
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
