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
  public $id;

  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $email;

  /**
   * @var object
   */
  public $timeslot;

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
  public function getName($lang) {
    $result = false;
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
  public function getEmail($lang) {
    $result = false;
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
   * @return string
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
      $this->client->database->insert('bookings', [
        'name' => $this->getName('sqlite'),
        'email' => $this->getEmail('sqlite'),
        'day' => $this->timeslot->getDay('sqlite'),
        'beginTime' => $this->timeslot->getBeginTime('sqlite'),
        'endTime'=> $this->timeslot->getEndTime('sqlite'),
        'token' => bin2hex(random_bytes(70))
      ]);
      $this->id = $this->client->database->id();
      if ($this->id > 0) {
        $days = getDaysOfTheWeek();
        $message = 'A booking was made for ' . $this->getName('html');
        $message .= ' on ' . $days[$this->timeslot->getDay('html')];
        $message .= ' at ' . substr($this->timeslot->getBeginTime('html'), 0, 2) . 'h';
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
   * @return boolean
   */
  public function validate() {
    if (!is_int($this->timeslot->day) || $this->timeslot->day > 6 || $this->timeslot->day < 0) return 'false';
    elseif (
      !is_numeric($this->timeslot->beginTime) ||
      strlen($this->timeslot->beginTime) != 4 ||
      intval($this->timeslot->beginTime) > 2400 ||
      intval($this->timeslot->beginTime) < 0) {
        return false;
      }
    elseif (
      !is_numeric($this->timeslot->endTime) ||
      strlen($this->timeslot->endTime) != 4 ||
      $this->timeslot->endTime > 2400 ||
      $this->timeslot->endTime < 0) {
        return false;
      }
    elseif (strlen($this->name) <= 0) {
      return false;
    }
    elseif (strlen($this->email) <= 0) {
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
   * @param integer
   *
   * @return array
   */
  public function get($id) {
    $data = $this->client->database->get('bookings', [
      'id', 'name','email','day','beginTime','endTime'
    ],[
      'id' => $id
    ]);
    if ($data !== false) {
      $this->id = intval($data['id']);
      $this->name = $data['name'];
      $this->email = $data['email'];
      $this->timeslot->day = intval($data['day']);
      $this->timeslot->beginTime = intval($data['beginTime']);
      $this->timeslot->endTime = intval($data['endTime']);
      return true;
    }
    return $data;
  }

  /**
   * @param integer
   *
   * @return array
   */
  public function getAll($fields = '*') {
    $data = $this->client->database->select('bookings', $fields);
    return $data;
  }
} ?>
