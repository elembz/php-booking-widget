<?php
$message = 'Booking could not be found.';
$error = true;
$booking = $app->booking();
$booking->setToken($_GET['token']);
$booking->setId($_GET['id']);
if ($booking->exists(['id' => $booking->getId(), 'token' => $booking->getToken()])) {
  $result = $booking->cancel();
  $message = $result->getMessage();
  $error = !$result->isSucces();
}
if ($message): ?>
<article class="message <?php if ($error) echo 'is-danger'; ?>">
  <div class="message-header">
    <p><?php echo !$error ? 'Thank you' : 'Oops'; ?></p>
  </div>
  <div class="message-body">
    <?php echo $message; ?>
  </div>
</article>
<?php endif; ?>
