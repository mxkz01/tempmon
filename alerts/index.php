<html>
	<head>
		<title>Alert Monitor</title>
	</head>
	<body>
		<h1>Alert Monitor</h1>
		<table border=1>
			<tr>
				<th>Sensor Name</th>
				<th>15 min Average</th>
				<th>Latest</th>
				<th>Last Updated</th>
				<th>Status</th>
			</tr>
<?php
	include("functions.inc.php");
	
	$con = open_db();

		$result = mysqli_query($con,"SELECT * FROM sensors");
		while($row = mysqli_fetch_array($result)){
			$latestTemp = latest_temp($row['sensor_id']);
			$latestTempTimestamp = latest_temp_timestamp($row['sensor_id']);
			$latestTempTimestampReadable = date('d/m/Y H:i', $latestTempTimestamp);
			$averageTemp15M = average_temp($row['sensor_id'],15);
			$outBoundsAverage = is_temp_out_of_bounds($averageTemp15M,$row['sensor_id']);
			$sensorName = $row['sensor_name'];
			echo "			<tr>";
			echo "				<td>$sensorName</td>\n";
			echo "				<td>$averageTemp15M</td>\n";
			echo "				<td>$latestTemp</td>\n";
			echo "				<td>$latestTempTimestampReadable</td>\n";
			$status = "OK";
			// If the 15 minute average is out of bounds
			if ($outBoundsAverage){
				$status = "FAIL";
			}
			// If the latest reading was updated more than 30 minutes ago
			if ($latestTempTimestamp < (time() - 1800)){
				$status = "FAIL";
			}
			echo "				<td>$status</td>\n";
			echo "			</tr>";
		}
		close_db($con);
?>
		</table>
	</body>
</html>