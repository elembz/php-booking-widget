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
   * @return string
   */
  public function getName() {
    return $this->name;
  }

  /**
   * @param string
   */
  public function setEmail($email) {
    $this->email = $email;
  }

  /**
   * @return string
   */
  public function getEmail() {
    return $this->email;
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
    $result = 0;
    if (!$this->exists(['day' => $this->timeslot->getDay(), 'beginTime' => $this->timeslot->getBeginTime(), 'endTime' => $this->timeslot->getEndTime()])) {
      $this->client->database->insert('bookings', [
        'name' => $this->getName(),
        'email' => $this->getEmail(),
        'day' => $this->timeslot->getDay(),
        'beginTime' => $this->timeslot->getBeginTime(),
        'endTime'=> $this->timeslot->getEndTime()
      ]);
      $this->id = $this->client->database->id();
      $result = $this->id;
    }
    return $result;

  }

  /**
   * @param array
   *
   * @return boolean
   */
  public function exists($details = false) {
    if (!$details) $details = ['email' => $this->getEmail()];
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
      $this->id = $data['id'];
      $this->name = $data['name'];
      $this->email = $data['email'];
      $this->day = $data['day'];
      $this->beginTime = $data['beginTime'];
      $this->endTime = $data['endTime'];
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
