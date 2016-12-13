<?php

namespace AppBundle\Command;

use AppBundle\Entity\PlanetSchedule;
use AppBundle\ExceptionLib\NoAPIParameterException;
use AppBundle\ExceptionLib\NoApiResponseException;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PlanetPositionCommand
 * @package AppBundle\Command
 */
class PlanetPositionCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:import:planet-position')
            ->setDescription('Gets planet rise and set times');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $calculator = $this->getContainer()->get('app.calculator.rise_set_calculator');

        try {
            $schedule = $calculator->getRiseSet('vilnius');
        } catch (NoApiResponseException $e) {
            $output->writeln($e->getMessage());
            return 1;
        } catch (NoAPIParameterException $e) {
            $output->writeln($e->getMessage());
            return 1;
        }
        $em = $this->getEntityManager();

        $todaySchedule = $em->getRepository('AppBundle:PlanetSchedule')->findBy(['date' => date('Y-m-d')]);
        if (!empty($todaySchedule)) {
            $output->writeln('Schedule for today already exists');
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
