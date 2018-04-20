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
     require_once($this->sitePath . '/objects/Response.php');

     $this->database = $database;

     require_once(__DIR__ . '/../helpers.php');
   }

   /**
    * @return Timeslot
    */
   function timeslot() {
     $timeslot = new Timeslot($this);
     return $timeslot;
   }

   /**
    * @return Booking
    */
   function booking() {
     $booking = new Booking($this);
     return $booking;
   }

   /**
    * @return Response
    */
   function response() {
     $response = new Response();
     return $response;
   }

   /**
    * @param string
    *
    * @return array
    */
   public function getSlots($singleDay = false) {
     $result;
     $slots = $this->slots;
     $bookings = $this->booking()->getAll(['day', 'beginTime','endTime']);
     $slotsByDay = array();
     $client = $this;
     foreach ($slots as $slot) {
       foreach($slot['days'] as $day) {
         $availability = array_search( ['day' => $day, 'beginTime' => $slot['beginTime'], 'endTime' => $slot['endTime']], $bookings) === false;
         $slotObject = $client->timeslot();
         $slotObject->setDay($day);
         $slotObject->setBeginTime($slot['beginTime']);
         $slotObject->setEndTime($slot['endTime']);
         $slotsByDay[$day][] = array('slot' => $slotObject, 'availability' => $availability);
       }
     }
     $result = $slotsByDay;
     if ($singleDay !== false) $result = $slotsByDay[$singleDay];
     return $result;
   }
 }
  ?>
