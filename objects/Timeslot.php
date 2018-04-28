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
  private $day;

  /**
   * @var int
   */
  private $beginTime;

  /**
   * @var int
   */
  private $endTime;

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
    $this->day = intval($day);
  }

  /**
   * @param string
   *
   * @return integer
   */
  public function getDay($lang = false) {
    $result = $this->day;
    $day = intval($this->day);
    if ($lang == 'html') $result = htmlspecialchars($day);
    if ($lang == 'sqlite') $result = $day;
    return $result;
  }

  /**
   * @param string
   */
  public function setBeginTime($time) {
    $this->beginTime = $time;
  }

  /**
   * @param string
   *
   * @return string
   */
  public function getBeginTime($lang = false) {
    $result = strval($this->beginTime);
    if ($lang == 'html') $result = htmlspecialchars($this->beginTime);
    if ($lang == 'sqlite') $result = $this->beginTime;
    return $result;
  }

  /**
   * @param string
   */
  public function setEndTime($time) {
    $this->endTime = $time;
  }

  /**
   * @param string
   *
   * @return string
   */
  public function getEndTime($lang = false) {
    $result = strval($this->endTime);
    if ($lang == 'html') $result = htmlspecialchars($this->endTime);
    if ($lang == 'sqlite') $result = $this->endTime;
    return $result;
  }

  /**
   * @return boolean
   */
  public function exists() {
    $result = false;
    $slots = $this->client->getSlots();
    $day = $this->getDay();
    $beginTime = $this->getBeginTime();
    $endTime = $this->getEndTime();
    foreach($slots as $slotDay) {
      foreach($slotDay as $slot) {
        $slot = $slot['slot'];
        if ($slot['day'] == $day && $slot['beginTime'] == $beginTime && $slot['endTime'] == $endTime) $result = true;
      }
    }
    return $result;
  }
}
?>
