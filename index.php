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
  <?php
  require_once 'helpers.php';
  $days = getDaysOfTheWeek();
  ?>
  <section class="section">
    <div class="container">
      <?php if (isset($_POST['day']) &&
                isset($_POST['time']) &&
                isset($_POST['name']) &&
                isset($_POST['email'])): ?>
      <article class="message">
        <div class="message-header">
          <p>Thank you</p>
        </div>
        <div class="message-body">
          A booking was made for <?php echo $_POST['name']; ?>
          on <?php echo $days[$_POST['day']]; ?>
          at <?php echo substr($_POST['time'], 0, 2); ?>h.
        </div>
</article>
      <?php endif; ?>
      <form action="" method="post">
        <div class="field">
          <label for="day" class="label">Day</label>
          <div class="control">
            <div class="select">
              <select name="day">
                <option value="" disabled selected>Select day</option>
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
                require_once 'timeslot.php';
                $timeslot = new Timeslot;
                foreach ($timeslot->getAvailableSlots('monday') as $slot): ?>
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
