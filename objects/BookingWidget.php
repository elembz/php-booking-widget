<?php
require_once __DIR__ . '/../vendor/autoload.php';
use Medoo\Medoo;
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
   private $appName;

   /**
    * @var string
    */
   private $siteUrl;

   /**
    * @var string
    */
   private $sitePath;

   /**
    * @var object
    */
   public $database;

   /**
    * @var object
    */
   private $slots;

   /**
    * @var string
    */
   private $adminEmail;

   /**
    * @var object
    */
   private $mailServer;

   /**
    * @var boolean
    */
   private $testMode;

   /**
    * @param string
    * @param string
    * @param array
    */
   function __construct($appName, $siteUrl, $sitePath, $databaseName, $slots, $adminEmail, $mailServer = false, $testMode)
   {
     require_once(__DIR__ . '/../objects/Timeslot.php');
     require_once(__DIR__ . '/../objects/Booking.php');
     require_once(__DIR__ . '/../objects/Response.php');
     require_once(__DIR__ . '/../objects/MailServer.php');
     require_once(__DIR__ . '/../helpers.php');
     $this->appName = $appName;
     $this->siteUrl = $siteUrl;
     $this->sitePath = $sitePath;
     $this->database = new Medoo([ 'database_type' => 'sqlite', 'database_file' => $databaseName ]);
     $this->slots = $slots;
     $this->adminEmail = $adminEmail;
     $this->mailServer = new MailServer;
     $this->mailServer->setSmtpHost($mailServer['smtpHost']);
     $this->mailServer->setUsername($mailServer['username']);
     $this->mailServer->setPassWord($mailServer['password']);
     $this->mailServer->setPort($mailServer['port']);
     $this->mailServer->setSecurityType($mailServer['securityType']);
     $this->testMode = $testMode;
   }

   /**
    * @return Booking
    */
   function booking() {
     $booking = new Booking($this);
     return $booking;
   }

   /**
    * @return Timeslot
    */
   function timeslot() {
     $timeslot = new Timeslot($this);
     return $timeslot;
   }

   /**
    * @return Response
    */
   function response() {
     $response = new Response();
     return $response;
   }

   /**
    * @return string
    */
   public function getAppName() {
     return $this->appName;
   }

   /**
    * @return string
    */
   public function getSiteUrl() {
     return $this->siteUrl;
   }

   /**
    * @return string
    */
   public function getSitePath() {
     return $this->sitePath;
   }

   /**
    * @return object
    */
   public function database() {
     return $this->database;
   }

   /**
    * @return string
    */
   public function getAdminEmail() {
     return $this->adminEmail;
   }

   /**
    * @return boolean
    */
   public function isInTestMode() {
     return $this->testMode['on'];
   }

   /**
    * @return boolean
    */
   public function mailServerIsSet() {
     if ($this->mailServer === false) {
       return false;
     }
     else {
       return true;
     }
   }

   /**
    * @return string
    */
   public function getTestEmail() {
     return $this->testMode['email'];
   }

   /**
    * @param integer
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
         $slotsByDay[$day][] = [
           'slot' => [
             'day' => $slotObject->getDay(),
             'beginTime' => $slotObject->getBeginTime(),
             'endTime' => $slotObject->getEndTime()
           ],
           'availability' => $availability
         ];
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
    *
    * @return object
    */
    public function sendEmail($address, $subject, $message) {
      $response = $this->response();
      if ($this->mailServerIsSet() != false) {
        $app = $this;
        $mail = new PHPMailer(true);
        if ($this->isInTestMode()) $address['email'] = $this->getTestEmail();
        try {
          $mail->SMTPDebug = 2;
          $mail->isSMTP();
          $mail->Host = $app->mailServer->getSmtpHost();
          $mail->SMTPAuth = true;
          $mail->Username = $app->mailServer->getUsername();
          $mail->Password = $app->mailServer->getPassword();
          $mail->SMTPSecure = $app->mailServer->getSecurityType();
          $mail->Port = $app->mailServer->getPort();

          $mail->setFrom($app->mailServer->getUsername(), $app->getAppName());
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
      } else {
        $response->setSucces(true);
        $response->setMessage($message);
      }
      return $response;
    }
 }
  ?>
