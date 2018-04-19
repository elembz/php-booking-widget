<?php
if (isset($_POST['day']) && strlen($_POST['day']) > 0 &&
    isset($_POST['time']) && strlen($_POST['time']) > 0 &&
    isset($_POST['name']) && strlen($_POST['name']) > 0 &&
    isset($_POST['email']) && strlen($_POST['email']) > 0):

  $error = true;
  $message = 'Something went wrong';

  $timeslot = $app->timeslot();
  $timeslot->setDay($_POST['day']);
  $timeslot->setBeginTime(substr($_POST['time'], 0, 4));
  $timeslot->setEndTime(substr($_POST['time'], 4, 8));

  $booking = $app->booking();
  $booking->setName($_POST['name']);
  $booking->setEmail($_POST['email']);
  $booking->setTimeslot($timeslot);

  if ($booking->exists()) {
    $error = true;
    $message = 'It seems you have already made a booking. If you would like to change or cancel your booking, please click on the link in your email.';
  }
  else if ($booking->make() > 0) {
    $error = false;
    $message = 'A booking was made for ' . $booking->getName();
    $message .= ' on ' . $days[$booking->timeslot->getDay()];
    $message .= ' at ' . substr($booking->timeslot->getBeginTime(), 0, 2) . 'h';
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
