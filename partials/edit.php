<?php
$booking = $app->booking();
$booking->setToken($_GET['token']);
$booking->setId($_GET['id']);
if ($booking->exists(['id' => $booking->getId(), 'token' => $booking->getToken()])):
  $booking->get();
  $requestedDay = $booking->timeslot->getDay('html');
  if (isset($_GET['day'])) $requestedDay = $_GET['day']; ?>
  <h3 class="title">Hello, <?php echo $booking->getName('html'); ?>.</h3>
  <form id="form" action="?edit&id=<?php echo $_GET['id']; ?>&token=<?php echo $_GET['token']; ?>" method="post">
    <div class="field">
      <label for="day" class="label">Day</label>
      <div class="control">
        <div class="select">
          <script>
          function updateDay() {
            var day = document.getElementById('day').value;
            var url = window.location.href;
            if (url.indexOf('?') > -1) {
               url += '&day=' + day;
            } else{
               url += '?param=' + day;
            }
            window.location.href = url;
          }
          </script>
          <select name="day" id="day" onchange="updateDay()">
            <option value="" disabled>Select a day</option>
            <?php foreach ($days as $day => $dayName): ?>
            <option value="<?php echo $day; ?>"<?php if ($requestedDay == $day) echo ' selected';?>>
              <?php echo $dayName; ?>
            </option>
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
            foreach ($app->getSlots($requestedDay) as $slotData): ?>
            <option value="<?php echo $slotData['slot']->beginTime . $slotData['slot']->endTime; ?>"
              <?php if ($booking->timeslot->getBeginTime('html') != $slotData['slot']->beginTime && !$slotData['availability']) echo ' disabled'; ?>
              <?php if ($booking->timeslot->getBeginTime('html') == $slotData['slot']->beginTime) echo ' selected'; ?>>
              <?php echo substr(strval($slotData['slot']->beginTime), 0, 2) . 'h—' . substr(strval($slotData['slot']->endTime), 0, 2) . 'h'; ?>
            </option>
          <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
    <div class="field">
      <div class="control">
        <button class="button is-primary" type="submit">Submit</button>
      </div>
    </div>
  </form>
<?php else: ?>
  <article class="message is-danger">
    <div class="message-header">
      <p>Oops</p>
    </div>
    <div class="message-body">
      <p>Booking could not be found.</p>
    </div>
<?php endif; ?>
