<?php
//
//namespace AppBundle\Feature;
//use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
//use AppBundle\Entity\Video;
//use Doctrine\ORM\EntityManager;
//use Symfony\Bundle\FrameworkBundle\Controller\Controller;
//use Symfony\Bundle\FrameworkBundle\Tests\Controller\ContainerAwareController;
//
///**
// * Class EventCalendar
// * @package AppBundle\Feature
// */
//class EventCalendar
//{
//    /**
//     * @var string
//     */
//    private $month;
//    /**
//     * @var int
//     */
//    private $year;
//
//    protected $em;
//
//    public function __construct(EntityManager $em,$month, $year)
//    {
//        $this->month = $month;
//        $this->year = $year;
//        $this->em = $em;
//    }
//
//    public function DaysInMonth() {
//        $month = $this->month;
//        $year = $this->year;
//        $number = cal_days_in_month(CAL_GREGORIAN, $month, $year);
//        return $number;
//    }
//    public function CalendarData($day) {
//        $data = array();
//        $month = $this->month;
//        $year = $this->year;
//        $newDate = new \DateTime($year.'-'.$month.'-'.$day);
//        $date = $newDate->format('Y-m-d');
//        $data = [
//            'date' => $date,
//            'day' => $day
//        ];
//        return $data;
//
//    }
//
//    public function MonthCalendar() {
////        $days = $this->DaysInMonth();
////        $count = 1;
////        while ($count != $days) {
////            $result = $this->CalendarData($count);
////            $count++;
////        }
////        return $result;
//    }
//
//    public function AddEvents($date) {
//        $em = $this->em;
//        $event = $em->getRepository('AppBundle:Event')->findEvent($date);
//        if ($event == null) {
//            return "test";
//        }
//        return $event;
//    }
//}
