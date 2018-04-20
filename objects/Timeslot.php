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
    $this->day = intval($day);
  }

  /**
   * @param string
   *
   * @return integer
   */
  public function getDay($lang) {
    $result = false;
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
  public function getBeginTime($lang) {
    $result = false;
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
  public function getEndTime($lang) {
    $result = false;
    if ($lang == 'html') $result = htmlspecialchars($this->endTime);
    if ($lang == 'sqlite') $result = $this->endTime;
    return $result;
  }
}
?>
