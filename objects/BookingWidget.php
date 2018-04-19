<?php

/**
 * The Booking Widget Class
 */
 class BookingWidget
 {

   /**
    * @var string
    */
   public $sitePath;

   /**
    * @var object
    */
   public $database;

   /**
    * @var object
    */
   public $slots;

   /**
    * @var Timeslot
    */
   public $timeslot;

   /**
    * @var Booking
    */
   public $booking;

   /**
    * @param string
    * @param string
    * @param array
    */
   function __construct($sitePath, $databaseName, $slots)
   {
     $this->sitePath = $sitePath;
     $this->slots = $slots;

     require_once($this->sitePath . '/config/database.php');
     require_once($this->sitePath . '/objects/Timeslot.php');
     require_once($this->sitePath . '/objects/Booking.php');

     $this->database = $database;
     $this->timeslot = new Timeslot($this);
     $this->booking = new Booking($this);

     require_once(__DIR__ . '/../helpers.php');
   }

   /**
    * @param string
    *
    * @return array
    */
   public function getSlots($singleDay = false) {
     $slots = $this->slots;
     $result;
     $daysOfTheWeek = getDaysOfTheWeek();
     $slotsByDay = array();
     $slotObject = new Timeslot($this);
     foreach ($slots as $slot) {
       foreach($slot['days'] as $day) {
         $slotObject->setDay($day);
         $slotObject->setBeginTime($slot['beginTime']);
         $slotObject->setEndTime($slot['endTime']);
         $slotsByDay[strtolower($daysOfTheWeek[$day])][] = $slotObject;

       }
     }
     $result = $slotsByDay;
     if ($singleDay !== false) $result = $slotsByDay[$singleDay];
     return $result;
   }
 }
  ?>
