<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Booking Widget</title>
  <!-- CSS -->
  <link rel="stylesheet" href="assets/css/vendor/bulma.min.css" />
</head>
<body>
  <section class="hero is-primary">
    <div class="hero-body">
      <div class="container">
        <h1 class="title">Booking Widget</h1>
        <h2 class="subtitle">UncoverMac</h2>
      </div>
    </div>
  </section>
  <?php
  require_once 'helpers.php';
  $days = getDaysOfTheWeek();

  require_once 'objects/BookingWidget.php';
  $app = new BookingWidget(__DIR__, 'bookings.db', json_decode(file_get_contents(__DIR__ . '/config/slots.json'), true));
  ?>
  <section class="section">
    <div class="container">
      <?php
      if (isset($_POST['day']) &&
          isset($_POST['time']) &&
          isset($_POST['name']) &&
          isset($_POST['email'])):

        $error = true;
        $message = 'Something went wrong';
        $timeslot = $app->timeslot;
        $timeslot->setDay($_POST['day']);
        $timeslot->setBeginTime(substr($_POST['time'], 0, 4));
        $timeslot->setEndTime(substr($_POST['time'], 4, 8));
        $booking = $app->booking;
        $booking->setName($_POST['name']);
        $booking->setEmail($_POST['email']);
        $booking->setTimeslot($timeslot);

        if ($booking->exists()) {
          $error = true;
          $message = 'It seems you have already made a booking. If you would like to change or cancel your booking, please click on the link in your email.';
        }
        else if ($booking->make() > 0) {
          $error = false;
          $message .= 'A booking was made for ' . $booking->getName();
          $message .= ' on ' . $days[$booking->timeslot->getDay()];
          $message .= ' at ' . substr($booking->timeslot->getBeginTime(), 0, 2);
        }

      ?>
      <article class="message <?php if ($error) echo 'is-danger'; ?>">
        <div class="message-header">
          <p><?php echo !$error ? 'Thank you' : 'Oops'; ?></p>
        </div>
        <div class="message-body">
          <?php echo $message; ?>
        </div>
</article>
      <?php endif; ?>
      <form action="" method="post">
        <div class="field">
          <label for="day" class="label">Day</label>
          <div class="control">
            <div class="select">
              <select name="day">
                <option value="" disabled selected>Select a day</option>
                <?php foreach ($days as $day => $dayName): ?>
                <option value="<?php echo $day; ?>"><?php echo $dayName; ?></option>
                <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <label for="day" class="label">Time</label>
          <div class="control">
            <div class="select">
              <select name="time">
                <option value="" disabled selected>Select time</option>
                <?php
                foreach ($app->getSlots('monday') as $slot): ?>
                <option value="<?php echo $slot->beginTime . $slot->endTime; ?>">
                  <?php echo substr(strval($slot->beginTime), 0, 2) . 'h—' . substr(strval($slot->endTime), 0, 2) . 'h'; ?>
                </option>
              <?php endforeach; ?>
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <label for="name" class="label">Name</label>
          <div class="control">
            <input name="name" class="input" type="text">
          </div>
        </div>
        <div class="field">
          <label for="email" class="label">Email</label>
          <div class="control">
            <input name="email" class="input" type="email">
          </div>
        </div>
        <div class="field">
          <div class="control">
            <button class="button is-primary" type="submit">Submit</button>
          </div>
        </div>
      </form>
    </div>
  </section>
</body>
</html>
