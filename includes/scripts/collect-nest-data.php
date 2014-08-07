<?php
/*
// Nest collector
// Collects nest data into mysql database
// This should be run every 5 minutes
*/

include('../includes/common.php');

date_default_timezone_set($config['local_tz']);

function get_nest_data() {
  $nest = new Nest();
  $info = $nest->getDeviceInfo();
  $data = array('heating'      => ($info->current_state->heat == 1 ? 1 : 0),
		'timestamp'    => $info->network->last_connection,
		'target_temp'  => sprintf("%.02f", (preg_match("/away/", $info->current_state->mode) ? 
						    $info->target->temperature[0] : $info->target->temperature)),
		'current_temp' => sprintf("%.02f", $info->current_state->temperature),
		'humidity'     => $info->current_state->humidity
		);
  return $data;
}

function c_to_f($c) {
  return ($c * 1.8) + 32;
}

?>