<?php
require 'vendor/autoload.php';

// Using Medoo namespace
use Medoo\Medoo;

function setDatabase($name) {
  $database = new Medoo([
    'database_type' => 'sqlite',
    'database_file' => $name,
  ]);
  return $database;
}
?>
