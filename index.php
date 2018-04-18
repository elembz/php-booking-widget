<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>The Maker Store @ Lightspeed</title>
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
  <section class="section">
    <div class="container">
      <div class="columns">
        <?php require_once 'timeslot.php';
        require_once 'helpers.php';
        $timeslot = new Timeslot;
        $available_time_slots = $timeslot->getAvailableSlots('by_day');
        $days = getDaysOfTheWeek();
        ?>
        <?php foreach($days as $day => $dayName) { ?>
        <div class="column content">
          <h3><?php echo $dayName; ?></h3>
          <?php foreach ($available_time_slots->{$day} as $slot) { ?>
            <div class="field">
              <a class="button" href="">
                <?php echo substr(strval($slot->beginTime), 0, 2) . 'h—' . substr(strval($slot->endTime), 0, 2) . 'h'; ?>
              </a>
            </div>
          <?php } ?>
      </div>
      <?php } ?>
    </div>
  </section>
</body>
</html>
