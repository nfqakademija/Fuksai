<?php
/**
 * Created by PhpStorm.
 * User: shalifar
 * Date: 16.11.18
 * Time: 20.42
 */

namespace AppBundle\Command;


use AppBundle\Entity\PlanetSchedule;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PlanetPositionCommand extends ContainerAwareCommand
{
    public function configure()
    {
        $this
            ->setName('app:planet-position')
            ->setDescription('Gets planet rise and set times');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $calculator = $this->getContainer()->get('app.calculator.rise_set_calculator');
        $schedule = $calculator->getRiseSet('vilnius');

        foreach ($schedule as $planet)
        {

        }
    }

    private function getData($url)
    {
        $json = file_get_contents($url);
        $data = json_decode($json, true);

        return $data;
    }
}
