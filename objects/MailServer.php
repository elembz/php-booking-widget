<?php
/**
 * The MailServer class
 */
 class MailServer
 {
   /**
    * @var string
    */
   private $smtpHost;

   /**
    * @var string
    */
   private $username;

   /**
    * @var string
    */
   private $password;

   /**
    * @var integer
    */
   private $port;

   /**
    * @var string
    */
   private $securityType;

   /**
    * @param string
    */
   public function setSmtpHost($host) {
     $this->host = $host;
   }

   /**
    * @return string
    */
   public function getSmtpHost() {
     return $this->host;
   }

   /**
    * @param string
    */
   public function setUsername($username) {
     $this->username = $username;
   }

   /**
    * @return string
    */
   public function getUsername() {
     return $this->username;
   }

   /**
    * @param string
    */
   public function setPassword($password) {
     $this->password = $password;
   }

   /**
    * @return string
    */
   public function getPassword() {
     return $this->password;
   }


   /**
    * @param integer
    */
   public function setPort($port) {
     $this->port = $port;
   }

   /**
    * @return integer
    */
   public function getPort() {
     return $this->port;
   }

   /**
    * @param string
    */
   public function setSecurityType($securityType) {
     $this->securityType = $securityType;
   }

   /**
    * @return string
    */
   public function getSecurityType() {
     return $this->securityType;
   }
 }
?>
