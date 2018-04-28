<?php
/**
 * The Booking class
 */
class Booking {

  /**
   * @var object
   */
  private $client;

  /**
   * @var integer
   */
  private $id;

  /**
   * @var string
   */
  private $name;

  /**
   * @var string
   */
  private $email;

  /**
   * @var object
   */
  public $timeslot;

  /**
   * @var string
   */
  private $token;

  /**
   * @param object
   */
  public function __construct($client) {
    $this->client = $client;
  }

  /**
   * @param string
   */
  public function setName($name) {
    $this->name = $name;
  }

  /**
   * @param string
   *
   * @return string
   */
  public function getName($lang = false) {
    $result = $this->name;
    if ($lang == 'html') $result = htmlspecialchars($this->name);
    if ($lang == 'sqlite') $result = $this->name;
    return $result;
  }

  /**
   * @param string
   */
  public function setEmail($email) {
    $this->email = strtolower($email);
  }

  /**
   * @param string
   *
   * @return string
   */
  public function getEmail($lang = false) {
    $result = $this->email;
    $email = strtolower($this->email);
    if ($lang == 'html') $result = htmlspecialchars($email);
    if ($lang == 'sqlite') $result = $email;
    return $result;
  }

  /**
   * @param object
   */
  public function setTimeslot($timeslot) {
    $this->timeslot = $timeslot;
  }

  /**
   * @return object
   */
  public function getTimeslot() {
    return $this->timeslot;
  }

  /**
   * @param string
   */
  public function setToken($token = false) {
    if (!$token) $token = bin2hex(random_bytes(70));
    $this->token = $token;
  }

  /**
   * @return string
   */
  public function getToken() {
    return $this->token;
  }

  /**
   * @param integer
   */
  public function setId($id) {
    $this->id = $id;
  }

  /**
   * @return integer
   */
  public function getId() {
    return $this->id;
  }

  /**
   * @return object
   */
  public function make() {
    $response = $this->client->response();
    if (!$this->validate()) {
      $response->setSucces(false);
      $response->setMessage('Insufficient data.');
    }
    else if ($this->exists([
      'day' => $this->timeslot->getDay('sqlite'),
      'beginTime' => $this->timeslot->getBeginTime('sqlite'),
      'endTime' => $this->timeslot->getEndTime('sqlite')
    ])) {
      $response->setSucces(false);
      $response->setMessage('This slot is already booked. Please choose a different day and/or time.');
    }
    else if ($this->exists(['email' => $this->getEmail('sqlite')])) {
      $response->setSucces(false);
      $response->setMessage('It seems you have already made a booking. If you would like to change or cancel your booking, please click on the link in your email.');
    }
    else {
      $this->setToken();
      $this->client->database->insert('bookings', [
        'name' => $this->getName('sqlite'),
        'email' => $this->getEmail('sqlite'),
        'day' => $this->timeslot->getDay('sqlite'),
        'beginTime' => $this->timeslot->getBeginTime('sqlite'),
        'endTime'=> $this->timeslot->getEndTime('sqlite'),
        'token' => $this->getToken()
      ]);
      if (array_filter($this->client->database->error())) {
        $message = 'Thank you, ' . $this->getName('html') . '. A booking was made ';
        $message .= 'on ' . $days[$this->timeslot->getDay('html')] . ' ';
        $message .= 'at ' . substr($this->timeslot->getBeginTime('html'), 0, 2) . 'h';
        $this->id = $this->client->database->id();
        $response = $this->sendMailToAdmin();
        if (!$response->isSucces()) {
          $this->cancel();
        } else {
          $response = $this->sendConfirmationMail();
          $response->setMessage($message);
        }
      } else {
        $response->setSucces(false);
        $response->setMessage('Something went wrong.');
      }
    }
    return $response;
  }

  /**
   * @return object
   */
  public function update() {
    $response = $this->client->response();
    if (!$this->validate(['day', 'beginTime', 'endTime'])) {
      $response->setSucces(false);
      $response->setMessage('Insufficient data.');
    } else if ($this->exists([
      'day' => $this->timeslot->getDay('sqlite'),
      'beginTime' => $this->timeslot->getBeginTime('sqlite'),
      'endTime' => $this->timeslot->getEndTime('sqlite'),
      'id[!]' => $this->id
    ])) {
      $response->setSucces(false);
      $response->setMessage('This slot is already booked. Please choose a different day and/or time.');
    } else {
      $this->client->database->update('bookings', [
        'name' => $this->getName('sqlite'),
        'email' => $this->getEmail('sqlite'),
        'day' => $this->timeslot->getDay('sqlite'),
        'beginTime' => $this->timeslot->getBeginTime('sqlite'),
        'endTime'=> $this->timeslot->getEndTime('sqlite')
      ],[
        'id' => $this->getId()
      ]);
      if (array_filter($this->client->database->error())) {
        $days = getDaysOfTheWeek();
        $message = 'Thank you, ' . $this->getName('html');
        $message .= '. Your booking was updated to ' . $days[$this->timeslot->getDay()] . ' ';
        $message .= 'at ' . substr($this->timeslot->getBeginTime(), 0, 2) . 'h.';
        $response->setSucces(true);
        $response->setMessage($message);
      } else {
        $response->setSucces(false);
        $response->setMessage('Something went wrong.');
      }
    }
    return $response;
  }

  /**
   * @return object
   */
  public function cancel() {
    $response = $this->client->response();
    $this->client->database->delete('bookings', [
      'id' => $this->getId()
    ]);
    if (array_filter($this->client->database->error())) {
      $response->setSucces(true);
      $response->setMessage('Booking was succesfully cancelled.');
    } else {
      $response->setSucces(false);
      $response->setMessage('Something went wrong');
    }
    return $response;
  }

  /**
   * @param array
   *
   * @return boolean
   */
  public function validate($fields = ['day', 'beginTime', 'endTime', 'name', 'email']) {
    $slots = $this->client->getSlots();
    if (!$this->timeslot->exists()) {
      return false;
    }
    elseif (
      array_search('day', $fields) !== false &&
      !is_int($this->timeslot->getDay()) ||
      $this->timeslot->getDay() > 6 ||
      $this->timeslot->getDay() < 0) {
        return false;
      }
    elseif (
      array_search('beginTime', $fields) !== false &&
      !is_numeric($this->timeslot->getBeginTime()) ||
      strlen($this->timeslot->getBeginTime()) != 4 ||
      intval($this->timeslot->getBeginTime()) > 2400 ||
      intval($this->timeslot->getBeginTime()) < 0) {
        return false;
      }
    elseif (
      array_search('endTime', $fields) !== false &&
      !is_numeric($this->timeslot->getEndTime()) ||
      strlen($this->timeslot->getEndTime()) != 4 ||
      $this->timeslot->getEndTime() > 2400 ||
      $this->timeslot->getEndTime() < 0) {
        return false;
      }
    elseif (array_search('name', $fields) !== false && strlen($this->getName()) <= 0) {
      return false;
    }
    elseif (array_search('email', $fields) !== false && strlen($this->getEmail()) <= 0) {
      return false;
    }
    else return true;
  }

  /**
   * @param array
   *
   * @return boolean
   */
  public function exists($details = false) {
    if (!$details) $details = ['email' => $this->getEmail('sqlite')];
    $data = $this->client->database->get('bookings', [
      'name','email','day','beginTime','endTime'
    ], $details);
    $result = $data;
    if ($data !== false) $result = true;
    return $result;
  }

  /**
   * @return object
   */
  public function get() {
    $response = $this->client->response();
    $data = $this->client->database->get('bookings', [
      'id', 'name','email','day','beginTime','endTime'
    ],[
      'id' => $this->id
    ]);
    if (array_filter($this->client->database->error())) {
      $this->setTimeslot($this->client->timeslot());
      $this->setId(intval($data['id']));
      $this->setName($data['name']);
      $this->setEmail($data['email']);
      $this->timeslot->setDay(intval($data['day']));
      $this->timeslot->setBeginTime($data['beginTime']);
      $this->timeslot->setEndTime($data['endTime']);
      $response->setSucces(1);
      $response->setMessage('Use other methods to get specific booking data.');
    } else {
      $response->setSucces(false);
      $response->setMessage('Something went wrong.');
    }
    return $response;
  }

  /**
   * @return object
   */
  public function sendMailToAdmin() {
    $days = getDaysOfTheWeek();
    $address = ['email' => $this->client->getAdminEmail(), 'Bookings'];
    $subject = 'A booking was made';
    $message = 'A booking was made for ' . $this->getName('html');
    $message .= ' on ' . $days[$this->timeslot->getDay('html')];
    $message .= ' at ' . substr($this->timeslot->getBeginTime('html'), 0, 2) . 'h.';
    return $this->client->sendEmail($address, $subject, $message);
  }

  public function sendConfirmationMail() {
    $days = getDaysOfTheWeek();
    $address = ['email' => $this->getEmail(), $this->getName()];
    $subject = 'Booking confirmation';
    $message = '<p>Hello ' . $this->getName('html') . ',</p>';
    $message .= '<p>You have made a booking on ' . $days[$this->timeslot->getDay('html')];
    $message .= ' at ' . substr($this->timeslot->getBeginTime('html'), 0, 2) . 'h.<br />';
    $message .= 'If you would like to edit or cancel your booking,';
    $message .= ' please click <a href=' . $this->client->getSiteUrl() . $this->client->getSitePath() . '/edit?id=' . $this->getId() . '&token=' . $this->getToken() .'>here</a></p>';
    $message .= '<p>Thank you,</p><p>' . $this->client->getAppName() . '</p>';
    return $this->client->sendEmail($address, $subject, $message);
  }

  /**
   * @param integer
   *
   * @return array
   */
  public function list($fields = '*') {
    $data = $this->client->database->select('bookings', $fields);
    return $data;
  }
} ?>
