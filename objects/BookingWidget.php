<?php
require_once __DIR__ . '/../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
    * @var string
    */
   public $admin;

   /**
    * @var array
    */
   public $mailServer;

   /**
    * @param string
    * @param string
    * @param array
    */
   function __construct($sitePath, $databaseName, $slots, $admin, $mailServer = false)
   {
     $this->sitePath = $sitePath;
     $this->slots = $slots;
     $this->admin = $admin;

     require_once(__DIR__ . '/../config/database.php');
     require_once(__DIR__ . '/../objects/Timeslot.php');
     require_once(__DIR__ . '/../objects/Booking.php');
     require_once(__DIR__ . '/../objects/Response.php');

     $this->database = setDatabase($databaseName);
     $this->mailServer = $mailServer;

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
     $bookings = $this->booking()->list(['day', 'beginTime','endTime']);
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

   /**
    * @param array
    * @param string
    * @param string
    * @param string
    *
    * @return object
    */
    public function sendEmail($address, $subject, $message) {
      $response = $this->response();
      $mailServer = $this->mailServer;
      $mail = new PHPMailer(true);
      try {
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host = $mailServer['smtp'];
        $mail->SMTPAuth = true;
        $mail->Username = $mailServer['username'];
        $mail->Password = $mailServer['password'];
        $mail->SMTPSecure = $mailServer['secure'];
        $mail->Port = $mailServer['port'];

        $mail->setFrom($mailServer['username'], 'Timeslot Booking Widget');
        $mail->addAddress($address['email'], $address['name']);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $message;

        $mail->send();
        $response->setSucces(true);
        $response->setMessage($message);
      } catch (Exception $e) {
        $response->setSucces(false);
        $response->setMessage($mail->ErrorInfo);
      }
      return $response;
    }
 }
  ?>
