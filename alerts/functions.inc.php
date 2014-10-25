<?php

// Open Database
function open_db(){
	$db_host = "localhost";
	$db_name = "tempdb";
	$db_user = "tempdb";
	$db_pass = "P@55word"; // make this more secure
	$con=mysqli_connect($db_host,$db_user,$db_pass,$db_name);
	// Check connection
	if (mysqli_connect_errno()){
		echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		die();
	}
	return $con;
}

// Close Database
function close_db($con){
	if ($con != NULL){
		mysqli_close($con);
	}
}

// Return min allowed temperature for sensor
function get_min_for_sensor($sensor_id){
	$con = open_db();
	$ans = 0;
	$sql = "SELECT * FROM sensors";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = (int)$row['sensor_min'];
	}
	close_db($con);
	return $ans;
}

// Return max allowed temperature for sensor
function get_max_for_sensor($sensor_id){
	$con = open_db();
	$ans = 0;
	$sql = "SELECT * FROM sensors";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = (int)$row['sensor_max'];
	}
	close_db($con);
	return $ans;
}

// Is the current temp out of bounds
function is_temp_out_of_bounds($log_data,$sensor_id){
    if ($log_data > get_max_for_sensor($sensor_id))
        return true;
    if ($log_data < get_min_for_sensor($sensor_id))
        return true;
    return false;
}

// Returns latest temp for sensor
function latest_temp($sensor_id){
	$con = open_db();
	$ans = "NONE";
	$timestamp = 0;
	$sql = "SELECT * FROM log WHERE sensor_id = " . (int)$sensor_id . " ORDER BY log_time DESC LIMIT 1";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			if ((int)$row['log_time'] > $timestamp)
				$timestamp = (int)$row['log_time'];
				$ans = $row['log_data'];			
	}
	close_db($con);
	return $ans;
}

// Returns when the latest temperature was submitted
function latest_temp_timestamp($sensor_id){
	$con = open_db();
	$timestamp = 0;
	$sql = "SELECT * FROM log WHERE sensor_id = " . (int)$sensor_id . " ORDER BY log_time DESC LIMIT 1";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			if ((int)$row['log_time'] > $timestamp)
				$timestamp = (int)$row['log_time'];
	}
	close_db($con);
	return $timestamp;
}

// Returns the average temp for sensor for the last x minutes
function average_temp($sensor_id, $Xminutes){
	$con = open_db();
	$ans = "NONE";
	$sql = "SELECT sensor_id, FORMAT(avg(log_data),1) AS average_temp FROM log WHERE sensor_id = " . (int)$sensor_id . " AND log_time > cast(UNIX_TIMESTAMP(DATE_ADD(now(),INTERVAL - " . (int)$Xminutes . " MINUTE)) as UNSIGNED)";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = $row['average_temp'];			
	}
	close_db($con);
	return $ans;
}