<?php

/**
 * The Timeslot Class
 */
class Timeslot
{
  /**
  * @var object
   */
  public $slots;
  /**
   * @var int
   */
  public $day;

  /**
   * @var int
   */
  public $beginTime;

  /**
   * @var int
   */
  public $endTime;

  public function __construct() {
    require_once('helpers.php');
    $this->slots = json_decode(file_get_contents('slots.json'));
  }

  /**
   * @param string
   */
  public function setDay($day) {
    $this->day = $day;
  }

  /**
   * @return string
   */
  public function getDay() {
    return $this->day;
  }

  /**
   * @param string
   */
  public function setBeginTime($time) {
    $this->beginTime = $time;
  }

  /**
   * @return string
   */
  public function getBeginTime() {
    return $this->beginTime;
  }

  /**
   * @param string
   */
  public function setEndTime($time) {
    $this->endTime = $time;
  }

  /**
   * @return string
   */
  public function getEndTime() {
    return $this->endTime;
  }

  /**
   * @param string
   *
   * @return object
   */
  public function getAvailableSlots($singleDay = false) {
    $slots = $this->slots;
    $result;
    $daysOfTheWeek = getDaysOfTheWeek();
    $slotsByDay = new stdClass();
    foreach ($slots as $slot) {
      foreach($slot->days as $day) {
        $slotObject = new Timeslot;
        $slotObject->setDay($day);
        $slotObject->setBeginTime($slot->beginTime);
        $slotObject->setEndTime($slot->endTime);
        $slotsByDay->{$day}[] = $slotObject;
      }
    }
    $result = $slotsByDay;
    if ($singleDay != false) $result = $slotsByDay->{$day};
    return $result;
  }
  /**
   * @return boolean
   */
  public function isAvailable() {
    $result = false;
    $slots = $this->slots;
    $day = $this->getDay();
    $beginTime = $this->getBeginTime();
    $endTime = $this->getEndTime();
    foreach($slots as $slot) {
      if ( array_search( $day, $slot->days ) !== false &&
        $slot->beginTime == $beginTime &&
        $slot->endTime == $endTime ) {
          $result = true;
      }
    }
    return $result;
  }

}
?>
