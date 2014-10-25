<?php
	include("functions.inc.php");
	if (isset($_POST['queue']) && isset($_POST['station_code']) && isset($_POST['station_ip'])){
		$result = submit_queue($_POST['station_code'],$_POST['station_ip'],$_POST['queue']);
		echo $result . "\n";
	}
	else {
		echo "FAIL\n";
	}

?>
