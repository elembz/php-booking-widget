<form id="form" action="" method="post">
  <div class="field">
    <label for="day" class="label">Day</label>
    <div class="control">
      <div class="select">
        <select name="day" onchange="document.getElementById('form').submit()">
          <option value="" disabled<?php if (!isset($_POST['day'])) echo ' selected'; ?>>Select a day</option>
          <?php foreach ($days as $day => $dayName): ?>
          <option value="<?php echo $day; ?>" <?php if (isset($_POST['day']) && $_POST['day'] == $day) echo ' selected'; ?>>
            <?php echo $dayName; ?>
          </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
  </div>
  <?php if (isset($_POST['day'])): ?>
  <div class="field">
    <label for="day" class="label">Time</label>
    <div class="control">
      <div class="select">
        <select name="time">
          <option value="" disabled selected>Select time</option>
          <?php
          foreach ($app->getSlots($_POST['day']) as $slotData): ?>
          <option value="<?php echo $slotData['slot']->beginTime . $slotData['slot']->endTime; ?>" <?php if (!$slotData['availability']) echo ' disabled'; ?>>
            <?php echo substr(strval($slotData['slot']->beginTime), 0, 2) . 'h—' . substr(strval($slotData['slot']->endTime), 0, 2) . 'h'; ?>
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
    <?php endif; ?>
  </div>
</form>
