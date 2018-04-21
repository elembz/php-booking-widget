<?php
require_once __DIR__ . '/vendor/autoload.php';

$klein = new \Klein\Klein();

$klein->respond('GET', $app->sitePath . '/slots', function () {
  $app = $GLOBALS['app'];
  echo json_response($app->getSlots());
});

$klein->respond('GET', $app->sitePath . '/slots/[:day]', function ($request) {
  $app = $GLOBALS['app'];
  echo json_response($app->getSlots($request->day));
});


$klein->respond('POST', $app->sitePath . '/booking/create', function ($request) {
  $app = $GLOBALS['app'];

  $timeslot = $app->timeslot();
  $timeslot->setDay($request->day);
  $timeslot->setBeginTime(substr($request->time, 0, 4));
  $timeslot->setEndTime(substr($request->time, 4, 8));

  $booking = $app->booking();
  $booking->setName($request->name);
  $booking->setEmail($request->email);
  $booking->setTimeslot($timeslot);

  $result = $booking->make();

  echo json_response([
    'succes' => $result->isSucces(),
    'message' => $result->getMessage()
  ]);
});

$klein->respond('PUT', $app->sitePath . '/booking/edit', function ($request) {
  $app = $GLOBALS['app'];

  $timeslot = $app->timeslot();
  $timeslot->setDay($request->day);
  $timeslot->setBeginTime(substr($request->time, 0, 4));
  $timeslot->setEndTime(substr($request->time, 4, 8));

  $booking = $app->booking();
  $booking->setId($request->id);
  $booking->setToken($request->token);
  $booking->get();
  $booking->setTimeslot($timeslot);

  $result = $booking->update();

  echo json_response([
    'succes' => $result->isSucces(),
    'message' => $result->getMessage()
  ]);
});

$klein->respond('DELETE', $app->sitePath . '/booking/delete', function ($request) {
  $app = $GLOBALS['app'];
  $booking = $app->booking();
  $booking->setToken($request->token);
  $booking->setId($request->id);
  if ($booking->exists(['id' => $booking->getId(), 'token' => $booking->getToken()])) {
    $result = $booking->cancel();
    echo json_response([
      'succes' => $result->isSucces(),
      'message' => $result->getMessage()
    ]);
  } else {
    echo json_response([
      'succes' => false,
      'message' => 'Booking could not be found'
    ]);
  }

});



$klein->dispatch();
