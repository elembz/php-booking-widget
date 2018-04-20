<?php
$message = false;
function prepareTimeslot($app) {
  $timeslot = $app->timeslot();
  $timeslot->setDay($_POST['day']);
  $timeslot->setBeginTime(substr($_POST['time'], 0, 4));
  $timeslot->setEndTime(substr($_POST['time'], 4, 8));
  return $timeslot;
}

if (isset($_GET['edit']) &&
  isset($_POST['day']) && strlen($_POST['day']) > 0 &&
  isset($_POST['time']) && strlen($_POST['time']) > 0)
  {
    $booking = $app->booking();
    $booking->setId($_GET['id']);
    $booking->setToken($_GET['token']);
    $booking->get();
    $booking->setTimeslot(prepareTimeslot($app));

    $result = $booking->update();
    $message = $result->getMessage();
    $error = !$result->isSucces();

} else if (!isset($_GET['edit']) &&
  isset($_POST['day']) && strlen($_POST['day']) > 0 &&
  isset($_POST['time']) && strlen($_POST['time']) > 0 &&
  isset($_POST['name']) && strlen($_POST['name']) > 0 &&
  isset($_POST['email']) && strlen($_POST['email']) > 0)
  {
    $booking = $app->booking();
    $booking->setName($_POST['name']);
    $booking->setEmail($_POST['email']);
    $booking->setTimeslot(prepareTimeslot($app));

    $result = $booking->make();
    $message = $result->getMessage();
    $error = !$result->isSucces();
} ?>
<?php if ($message): ?>
<article class="message <?php if ($error) echo 'is-danger'; ?>">
  <div class="message-header">
    <p><?php echo !$error ? 'Thank you' : 'Oops'; ?></p>
  </div>
  <div class="message-body">
    <?php echo $message; ?>
  </div>
</article>
<?php endif; ?>
