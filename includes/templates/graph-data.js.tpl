<?= $callback; ?>([
  [<?php echo join($data_temp, ','); ?>],
  [<?php echo join($data_outside_temp, ','); ?>],
  [<?php echo join($data_setpoint, ','); ?>],
  [<?php echo join($data_humidity, ','); ?>],
  [<?php echo join($data_outside_humidity, ','); ?>],
  [<?php echo join($data_cooling, ','); ?>],
  [<?php echo join($data_heating, ','); ?>],
  [<?php echo join($data_battery_level, ','); ?>]
]);