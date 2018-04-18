<?php

/**
 * The Booking class
 */
class Booking {

  /**
   * @var object
   */
  private $database;

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

  public function __construct() {
    require_once('database.php');
    require_once('timeslot.php');
    $this->database = $database;
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
    $this->database->insert('bookings', [
      'name' => $this->name,
      'email' => $this->email,
      'day' => $this->timeslot->day,
      'beginTime' => $this->timeslot->beginTime,
      'endTime'=> $this->timeslot->endTime
    ]);
    $this->id = $this->database->id();
    return $this->id;
  }
} ?>
