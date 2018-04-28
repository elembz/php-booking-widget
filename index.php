<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/objects/BookingWidget.php';
require_once __DIR__ . '/config/settings.php';

$GLOBALS['app'] = new BookingWidget(
  $settings['name'],
  $settings['url'],
  $settings['path'],
  $settings['database'],
  json_decode(file_get_contents($settings['slots']), true),
  $settings['adminEmail'],
  $settings['mailServer'],
  $settings['testMode']
);

$klein = new \Klein\Klein();

$klein->respond('GET', $app->getSitePath() . '/slots', function () {
  $app = $GLOBALS['app'];
  echo json_response($app->getSlots());
});

$klein->respond('GET', $app->getSitePath() . '/slots/[:day]', function ($request) {
  $app = $GLOBALS['app'];
  echo json_response($app->getSlots($request->day));
});

$klein->respond('GET', $app->getSitePath() . '/booking/[:id]', function ($request) {
  $app = $GLOBALS['app'];

  $booking = $app->booking();
  $booking->setId($request->id);
  $booking->setToken(getBearerToken());

  if ($booking->exists(['id' => $booking->getId(), 'token' => $booking->getToken()])) {
    $result = $booking->get();
    if ($result->isSucces()) {
      echo json_response([
        'succes' => true,
        'message' => [
          'id' => $booking->getId(),
          'name' => $booking->getName(),
          'email' => $booking->getEmail(),
          'day' => $booking->timeslot->getDay(),
          'beginTime' => $booking->timeslot->getBeginTime(),
          'endTime' => $booking->timeslot->getEndTime(),
        ]
      ]);
    }

  } else {
    echo json_response([
      'succes' => false,
      'message' => 'Booking could not be found'
    ]);
  }
});


$klein->respond('POST', $app->getSitePath() . '/booking/create', function ($request) {
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

$klein->respond('PUT', $app->getSitePath() . '/booking/edit', function ($request) {
  $app = $GLOBALS['app'];

  $timeslot = $app->timeslot();
  $timeslot->setDay($request->day);
  $timeslot->setBeginTime(substr($request->time, 0, 4));
  $timeslot->setEndTime(substr($request->time, 4, 8));

  $booking = $app->booking();
  $booking->setId($request->id);
  $booking->setToken(getBearerToken());

  if ($booking->exists(['id' => $booking->getId(), 'token' => $booking->getToken()])) {
    $booking->get();
    $booking->setTimeslot($timeslot);

    $result = $booking->update();
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

$klein->respond('DELETE', $app->getSitePath() . '/booking/delete', function ($request) {
  $app = $GLOBALS['app'];
  $booking = $app->booking();
  $booking->setToken(getBearerToken());
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
?>
