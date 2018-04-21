<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/objects/BookingWidget.php';
$GLOBALS['app'] = new BookingWidget('/timeslot-booking-widget', 'bookings.db', json_decode(file_get_contents(__DIR__ . '/config/slots.json'), true));
$days = getDaysOfTheWeek();
include __DIR__ . '/routes.php';
?>
