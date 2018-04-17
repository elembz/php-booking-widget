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
   * @param structure     Options: by_day,
   * @return object // fix this
   */
  public function getAvailableSlots($structure) {
    $slots = $this->slots;
    if ( $structure == 'by_day') {
      $slots_by_day = array(
        0 => array(),
        2 => array(),
        3 => array(),
        4 => array(),
        5 => array(),
        6 => array()
      );
      foreach ($slots as $slot) {
        foreach($slot->days as $day) {
          $slotObject = new Timeslot;
          $slotObject->setDay($day);
          $slotObject->setBeginTime($slot->beginTime);
          $slotObject->setEndTime($slot->endTime);
          $slots_by_day[$day][] = $slotObject;
        }
      }
      $slots = $slots_by_day;
    }
    return $slots;
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
