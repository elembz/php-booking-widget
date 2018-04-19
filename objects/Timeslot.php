<?php

/**
 * The Timeslot Class
 */
class Timeslot
{

  /**
   * @var object
   */
  private $client;

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

  /**
   * @param object
   */
  public function __construct($client) {
    $this->client = $client;
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
   * @return boolean
   */
  public function isAvailable() {
    $result = false;
    $slots = $this->client->slots;
    $bookings = $this->client->booking()->getAll(['beginTime','endTime']);
    $day = $this->getDay();
    $beginTime = $this->getBeginTime();
    $endTime = $this->getEndTime();

    foreach($slots as $slot) {
      if (
        array_search( $day, $slot['days'] ) !== false &&
        array_search( [$beginTime, $endTime], $bookings) == false &&
        $slot->beginTime == $beginTime &&
        $slot->endTime == $endTime ) {
          $result = true;
      }
    }

    return $result;
  }

}
?>
