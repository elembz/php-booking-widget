<?php
if (isset($_POST['day']) && strlen($_POST['day']) > 0 &&
    isset($_POST['time']) && strlen($_POST['time']) > 0 &&
    isset($_POST['name']) && strlen($_POST['name']) > 0 &&
    isset($_POST['email']) && strlen($_POST['email']) > 0):

  $timeslot = $app->timeslot();
  $timeslot->setDay($_POST['day']);
  $timeslot->setBeginTime(substr($_POST['time'], 0, 4));
  $timeslot->setEndTime(substr($_POST['time'], 4, 8));

  $booking = $app->booking();
  $booking->setName($_POST['name']);
  $booking->setEmail($_POST['email']);
  $booking->setTimeslot($timeslot);

  $result = $booking->make();
  $message = $result->getMessage();
  $error = !$result->isSucces();

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
