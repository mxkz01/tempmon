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

// Prints HTML Header for Web App
function html_header(){
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="refresh" content="300">
		<title>Temperature Monitor</title>
		<!-- Bootstrap -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->
	</head>
	<body role="document">
		<!-- Fixed navbar -->
		<div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
			<div class="container">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="navbar-brand" href="#">Temperature Monitor</a>
				</div>
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li <?=echoActiveClassIfRequestMatches("index")?>><a href="index.php">Dashboard</a></li>
					</ul>
				</div><!--/.nav-collapse -->
			</div>
		</div>
		<div class="container" role="main">
		</br>
<?php
}

// Prints HTML Footer for Web App
function html_footer(){
?>
		</div>
		<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
		<script src="js/jquery-1.11.0.min.js"></script>
		<script src="js/highcharts.js"></script>
		<!-- Include all compiled plugins (below), or include individual files as needed -->
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>
<?php
}

// Prints the dashboard panels from cache
function dashboard_panels_cache(){
$contents = file_get_contents("dashboard_panels.txt");
echo $contents;
}

// Prints the dashboard panels
function dashboard_panels(){
$con = open_db();
?>
            <div class="row">
<?php
	$result = mysqli_query($con,"SELECT * FROM sensors");
	while($row = mysqli_fetch_array($result)){
		// Grab the readings from the database
		$unit = "";
		$latestTemp = latest_temp($row['sensor_id']);
		$latestTempTimestamp = latest_temp_timestamp($row['sensor_id']);
		$latestTempTimestampReadable = date('d/m/Y H:i', $latestTempTimestamp);
		$averageTemp1H = average_temp($row['sensor_id'],60);
		$averageTemp24H = average_temp($row['sensor_id'],1440);
		$averageTemp1W = average_temp($row['sensor_id'],10080);
		$minTemp1H = min_temp($row['sensor_id'],60);
		$minTemp24H = min_temp($row['sensor_id'],1440);
		$minTemp1W = min_temp($row['sensor_id'],10080);
		$maxTemp1H = max_temp($row['sensor_id'],60);
		$maxTemp24H = max_temp($row['sensor_id'],1440);
		$maxTemp1W = max_temp($row['sensor_id'],10080);
		$outBoundsLatest = is_temp_out_of_bounds($latestTemp,$row['sensor_id']);
		$minForSensor = get_min_for_sensor($row['sensor_id']);
		$maxForSensor = get_max_for_sensor($row['sensor_id']);
		// Compare the readings against min and max for the sensor and change the colours accordingly
		if ($latestTemp < $minForSensor || $latestTemp > $maxForSensor){
			$panelType = "danger";
			$latestTempColor = "red";
		}
		else{
			$panelType = "success";
			$latestTempColor = "green";
		}
		if ($averageTemp1H < $minForSensor || $averageTemp1H > $maxForSensor){
			$averageTemp1HColor = "red";
		}
		else{
			$averageTemp1HColor = "green";
		}
		if ($averageTemp24H < $minForSensor || $averageTemp24H > $maxForSensor){
			$averageTemp24HColor = "red";
		}
		else{
			$averageTemp24HColor = "green";
		}
		if ($averageTemp1W < $minForSensor || $averageTemp1W > $maxForSensor){
			$averageTemp1WColor = "red";
		}
		else{
			$averageTemp1WColor = "green";
		}
		if ($minTemp1H < $minForSensor || $minTemp1H > $maxForSensor){
			$minTemp1HColor = "red";
		}
		else{
			$minTemp1HColor = "green";
		}
		if ($minTemp24H < $minForSensor || $minTemp24H > $maxForSensor){
			$minTemp24HColor = "red";
		}
		else{
			$minTemp24HColor = "green";
		}
		if ($minTemp1W < $minForSensor || $minTemp1W > $maxForSensor){
			$minTemp1WColor = "red";
		}
		else{
			$minTemp1WColor = "green";
		}
		if ($maxTemp1H < $minForSensor || $maxTemp1H > $maxForSensor){
			$maxTemp1HColor = "red";
		}
		else{
			$maxTemp1HColor = "green";
		}
		if ($maxTemp24H < $minForSensor || $maxTemp24H > $maxForSensor){
			$maxTemp24HColor = "red";
		}
		else{
			$maxTemp24HColor = "green";
		}
		if ($maxTemp1W < $minForSensor || $maxTemp1W > $maxForSensor){
			$maxTemp1WColor = "red";
		}
		else{
			$maxTemp1WColor = "green";
		}
		// If the latest reading was updated within 15 minutes
		if ($latestTempTimestamp >= (time() - 900)){
			$latestTempTimestampColor = "green";
		}
		// If the latest reading was updated more than 15 minutes ago
		if ($latestTempTimestamp < (time() - 900)){
			$panelType = "warning";
			$latestTempTimestampColor = "orange";
		}
		// If the latest reading was updated more than 60 minutes ago
		if ($latestTempTimestamp < (time() - 3600)){
			$panelType = "danger";
			$latestTempTimestampColor = "red";
		}
		// Set unit to the correct measurement
		if ($row['sensor_unit'] == "degrees_celsius")
			$unit = "&degC";
		if ($row['sensor_unit'] == "decibels")
			$unit = " dB";
		if ($row['sensor_unit'] == "amps")
			$unit = " A";
		if ($row['sensor_unit'] == "volts")
			$unit = " V";
		// Generate the panel and contents
		echo "				<div class='col-sm-4'>\n";
		echo "                  <div class='panel panel-$panelType'>\n";
		echo "                      <div class='panel-heading'>\n";
		echo "                          <h3 class='panel-title'>" . $row['sensor_name'] . "</h3>\n";
		echo "                      </div>\n";
		echo "                      <div class='panel-body'>\n";
		echo "                          <p style='font-size: 300%; text-align:center; color:$latestTempColor;'>$latestTemp$unit</p>\n";
		echo "                          <p style='text-align:center;'><b>updated</b> <font style='color:$latestTempTimestampColor;'>$latestTempTimestampReadable</font></p>\n";
		echo "                      </div>\n";
		echo "                      <ul class='list-group'>\n";
		echo "                          <li class='list-group-item'><b>1hr</b> <font style='color: $averageTemp1HColor';><span class='glyphicon glyphicon-resize-vertical'></span> $averageTemp1H$unit </font><font style='color: $minTemp1HColor';><span class='glyphicon glyphicon-arrow-down'></span> $minTemp1H$unit </font><font style='color: $maxTemp1HColor';><span class='glyphicon glyphicon-arrow-up'></span> $maxTemp1H$unit</font></li>\n";
		echo "                          <li class='list-group-item'><b>24hr</b> <font style='color: $averageTemp24HColor';><span class='glyphicon glyphicon-resize-vertical'></span> $averageTemp24H$unit </font><font style='color: $minTemp24HColor';><span class='glyphicon glyphicon-arrow-down'></span> $minTemp24H$unit </font><font style='color: $maxTemp24HColor';><span class='glyphicon glyphicon-arrow-up'></span> $maxTemp24H$unit</font></li>\n";
		echo "                          <li class='list-group-item'><b>1wk</b> <font style='color: $averageTemp1WColor';><span class='glyphicon glyphicon-resize-vertical'></span> $averageTemp1W$unit </font><font style='color: $minTemp1WColor';><span class='glyphicon glyphicon-arrow-down'></span> $minTemp1W$unit </font><font style='color: $maxTemp1WColor';><span class='glyphicon glyphicon-arrow-up'></span> $maxTemp1W$unit</font></li>\n";
		echo "                      </ul>\n";
		echo "                  </div>\n";
		echo "				</div>\n";
	}
   ?>
            </div>
<?php
	close_db($con);
}

// Show the current page as the active class for the navbar
function echoActiveClassIfRequestMatches($requestUri){
    $current_file_name = basename($_SERVER['REQUEST_URI'], ".php");
    if ($current_file_name == $requestUri)
        echo 'class="active"';
    // Set the index as active by default
    if ($current_file_name == "" && $requestUri == "index")
        echo 'class="active"';
}

// Checks to see if a given station exists in the database
function station_exists($station_code){
	$con = open_db();
	$ans = false;
	$sql = "SELECT station_code FROM stations";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['station_code'] == $station_code)
			$ans = true;
	}
	close_db($con);
	return $ans;
}

// Returns the Station ID
function get_station_id($station_code){
	$con = open_db();
	$ans = -1;
	$sql = "SELECT station_id, station_code FROM stations";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['station_code'] == $station_code)
			$ans = $row['station_id'];
	}
	close_db($con);
	return $ans;
}

// Adds a new station to the database
function station_add($station_code,$station_ip){
	$con = open_db();
	$station_code2 = mysqli_real_escape_string($con,$station_code);
	$station_ip2 = mysqli_real_escape_string($con,$station_ip);
	$rightnow = time();
	$sql="INSERT INTO stations (station_code, station_ip, station_seen) VALUES ('$station_code2', '$station_ip2', '$rightnow')";
	echo $sql . "\n";
	if (!mysqli_query($con,$sql)){
		die('Error: ' . mysqli_error($con));
	}
	close_db($con);
}

// Updates the station's IP and updated it's last seen timestamp
function station_update($station_code,$station_ip){
	$con = open_db();
	$station_ip2 = mysqli_real_escape_string($con,$station_ip);
	$sql = "SELECT station_code, station_ip FROM stations";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['station_code'] == $station_code){
			if ($row['station_ip'] != $station_ip){
				$sql2 = "UPDATE stations SET station_ip='$station_ip2' WHERE station_code='$station_code'";
				echo $sql2 . "\n";
				if (!mysqli_query($con,$sql2)){
					die('Error: ' . mysqli_error($con));
				}
			}
			$rightnow = time();
			$sql3 = "UPDATE stations SET station_seen=$rightnow WHERE station_code='$station_code'";
			echo $sql3 . "\n";
			if (!mysqli_query($con,$sql3)){
				die('Error: ' . mysqli_error($con));
			}
		}
	}
	close_db($con);
}

// Returns the number of sensors connected to a station
function get_sensor_count_for_station($station_code){
	$con = open_db();
	$ans = 0;
	$sql = "SELECT station_id FROM sensors";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['station_id'] == get_station_id($station_code))
			$ans++;
	}
	close_db($con);
	return $ans;
}

// Returns the sensor ids for the given station as a CSV string
function get_sensor_ids_for_station($station_code){
	$con = open_db();
	$ans = "";
	$sql = "SELECT * FROM sensors WHERE station_id = '" . get_station_id($station_code)."'";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		$ans = $ans . $row['sensor_id'] . ',';
	}
	$ans = rtrim($ans,",");
	close_db($con);
	return $ans;
}

// Return the sensor name
function get_sensor_name($sensor_id){
	$con = open_db();
	$ans = "";
	$sql = "SELECT * FROM sensors";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = $row['sensor_name'];
	}
	close_db($con);
	return $ans;
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

// select log_time, avg(log_data), min(log_data), max(log_data) from log where sensor_id = 1 order by log_time DESC limit 10

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
	$sql = "SELECT sensor_id, FORMAT(avg(cast(log_data as signed)),1) AS average_temp FROM log WHERE sensor_id = " . (int)$sensor_id . " AND log_time > cast(UNIX_TIMESTAMP(DATE_ADD(now(),INTERVAL - " . (int)$Xminutes . " MINUTE)) as UNSIGNED)";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = $row['average_temp'];			
	}
	close_db($con);
	return $ans;
}

// Returns the max temp for sensor for the last X minutes
function max_temp($sensor_id, $Xminutes){
	$con = open_db();
	$ans = "NONE";
	$sql = "SELECT sensor_id, FORMAT(max(cast(log_data as signed)),1) AS max_temp FROM log WHERE sensor_id = " . (int)$sensor_id . " AND log_time > cast(UNIX_TIMESTAMP(DATE_ADD(now(),INTERVAL - " . (int)$Xminutes . " MINUTE)) as UNSIGNED)";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = $row['max_temp'];			
	}
	close_db($con);
	return $ans;
}

// Returns the min temp for sensor for the last X minutes
function min_temp($sensor_id, $Xminutes){
	$con = open_db();
	$ans = "NONE";
	$sql = "SELECT sensor_id, FORMAT(min(cast(log_data as signed)),1) AS min_temp FROM log WHERE sensor_id = " . (int)$sensor_id . " AND log_time > cast(UNIX_TIMESTAMP(DATE_ADD(now(),INTERVAL - " . (int)$Xminutes . " MINUTE)) as UNSIGNED)";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result)){
		if ($row['sensor_id'] == $sensor_id)
			$ans = $row['min_temp'];			
	}
	close_db($con);
	return $ans;
}

// Adds a new sensor to the database
function sensor_add($station_code){
	$con = open_db();
	$station_id = get_station_id($station_code);
	$station_id2 = mysqli_real_escape_string($con,$station_id);
	$sql="INSERT INTO sensors (station_id) VALUES ('$station_id2')";
	//echo $sql . "\n";
	if (!mysqli_query($con,$sql)){
		die('Error: ' . mysqli_error($con));
	}
	close_db($con);
}

// Convert date/time from queue to timestamp
function queue_datetime_to_timestamp($datetime){
	$dtarray = explode('_', $datetime);
	$darray = explode('-',$dtarray[0]);
	$tarray = explode(':',$dtarray[1]);
	return (mktime($tarray[0],$tarray[1],0,$darray[1],$darray[0],$darray[2]));
}

// Add sensor log entry to the database
function sensor_log($this_sensor,$data,$datetime){
	$con = open_db();
	$this_sensor2 = mysqli_real_escape_string($con,$this_sensor);
	$data2 = mysqli_real_escape_string($con,$data);
	$timestamp = mysqli_real_escape_string($con,queue_datetime_to_timestamp($datetime));
	$sql="INSERT INTO log (sensor_id, log_data, log_time) VALUES ('$this_sensor2','$data2','$timestamp')";
	//echo $sql . "\n";
	if (!mysqli_query($con,$sql)){
		die('Error: ' . mysqli_error($con));
	}
	close_db($con);
}

// The main procedure for adding the uploaded queue to the database
function submit_queue($station_code,$station_ip,$queue){
	if (isset($station_code) && isset($station_ip) && isset($queue)){
		// If we haven't seen this station before, add it to the database
		if (!station_exists($station_code)){
			station_add($station_code,$station_ip);
		}
		// Update the IP and timestamp for the station
		station_update($station_code,$station_ip);
		
		// Split up the queue into an array
		$lines = explode(PHP_EOL, $queue);
		$counter = 0;
		while ($counter < (count($lines) -1)){
			// Split up the line into an array
			$fields = explode(' ', $lines[$counter]);
			// If there are less sensors in the database than the number of reported sensors, add more sensors to the database
			while ((count($fields) - 1) > get_sensor_count_for_station($station_code)){
				sensor_add($station_code);
			}
			// Go through each sensor reading and add it to the database
			$scounter = 1;
			$existing_sensors = explode(',', get_sensor_ids_for_station($station_code));
			//echo $existing_sensors[0];
			//echo $existing_sensors[1];
			//echo $existing_sensors[2];
			while ($scounter < count($fields)){
				//sensor_log(sensor_id,sensor_data,sensor_datetime)
				sensor_log($existing_sensors[$scounter-1],$fields[$scounter],$fields[0]);
				$scounter++;
			}
			//echo " readings: " . (count($fields) - 1) . " ($fields[1]) sensors: " . get_sensor_count_for_station($station_code);
			$counter++;
		}
		return "SUCCESS";
	}
	return "FAIL";
}
