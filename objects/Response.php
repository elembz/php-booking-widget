<?php

/**
 * The Response Class
 */
class Response {

  /**
   * @var string
   */
  private $type;

  /**
   * @var string
   */
  private $message;

  /**
   * @param boolean
   */
  public function setSucces($type) {
    $this->type = $type;
  }

  /**
   * @return boolean
   */
  public function isSucces() {
    return $this->type;
  }

  /**
   * @param string
   */
  public function setMessage($message) {
    $this->message = $message;
  }

  /**
   * @return string
   */
  public function getMessage() {
    return $this->message;
  }
}
