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
      include 'partials/message.php';
      if (isset($_GET['edit']) && isset($_GET['token']) && isset($_GET['id'])) {
        include 'partials/edit.php';
      } else if (isset($_GET['delete']) && isset($_GET['token']) && isset($_GET['id'])) {
        include 'partials/delete.php';
      } else {
          include 'partials/create.php';
      }
      ?>
    </div>
  </section>
</body>
</html>
