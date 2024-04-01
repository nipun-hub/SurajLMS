<?php

// ************************ Section with date related stuff ************************

try {
	function GetToday($type, $Separate = '')
	{
		if ($type == 'ym') {
			$contents = "Y{$Separate}m";
			return date($contents);
		}

		if ($type == 'ymd') {
			$contents = "Y{$Separate}m{$Separate}d";
			return date($contents);
		}

		if ($type == 'ymdhis') {
			$contents = "Y{$Separate}m{$Separate}d{$Separate}H{$Separate}i{$Separate}s";
			return date($contents);
		}

		if ($type == 'timeOfDay') {
			// Determine the time of day
			$hour = date('H');
			switch ($hour) {
				case 5 <= $hour && $hour < 12:
					return 'Morning';
					break;
				case 12 <= $hour && $hour < 17:
					return 'Afternoon';
					break;
				case 17 <= $hour && $hour < 21:
					return 'Evening';
					break;
				default:
					return 'Night';
					break;
			}
		}

		// nothig output
		return 'undefind';
	}
} catch (Exception $e) {
	return 'error';
}

try {
	function get_date_ago_time($givenTime, $today_now)
	{
		try {
			// Create DateTime objects for the given time and the current time.
			date_default_timezone_set("Asia/colombo");
			$givenTimeObject = new DateTime($givenTime);
			$currentTimeObject = new DateTime($today_now);

			// Calculate the difference between the two DateTime objects.
			$diff = $givenTimeObject->diff($currentTimeObject);

			if (($diff->h) == 0) {
				if (($diff->i) == 0) {
					$left_time = 'just now';
				} else {
					$left_time = "{$diff->i} minutes";
				}
			} elseif (($diff->d) == 0) {
				$left_time = "{$diff->h} hours";
			} else {
				$left_time = "{$diff->d} days";
			}
		} catch (Exception $e) {
			$left_time = 'undefind';
		}

		return $left_time;
	}
} catch (Exception $e) {
	return "undefind";
}

function GetMonthName($number)
{
	$months = [
		"01" => 'January',
		"02" => 'February',
		"03" => 'March',
		"04" => 'April',
		"05" => 'May',
		"06" => 'June',
		"07" => 'July',
		"08" => 'August',
		"09" => 'September',
		"10" => 'October',
		"11" => 'November',
		"12" => 'December',
	];
	try {
		if($number == null){
			return $months;
		}
		elseif ($number > 0 && $number < 13) {
			return $months[$number];
		} else {
			return "Undefind";
		}
	} catch (Exception $e) {
		return "Undefind";
	}
}

// user gide GetToday function 👇
// GetToday($type,$Separate);
// $type --->> output date type ( ym -> '2021-10' , ymd -> '2024-10-21' , ymdhis -> '2024-10-20-12-55-50' , timeOfDay -> 'Morning,Afternoon,Evening,Night')


// ************************ side bar functions ************************

function GetActive($PageName)
{
	if (isset($_SESSION['active'])) {
		$active = $_SESSION['active'];
		switch ($PageName) {
			case $PageName == $active:
				return 'active-page-link';
				break;

			default:
				return '';
				break;
		}
	} else {
		return '';
	}
}

// user gide for GetActive function
// GetActive($PageName);
// GetActive($PageName) -->> rerurn value = active or empty


// databade finctions

function instiBtn($conn, $val1, $val2 = null)
{
	$sql = "SELECT UserId,InstiName,Status FROM user WHERE UserId = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $val1);
	$stmt->execute();
	$rusalt = $stmt->get_result();
	if ($row = $rusalt->fetch_assoc()) {
		$instiName = $row['InstiName'];
		$status = $row['Status'];
		if ($instiName == null) {
			return 'register';
		} else {
			if ($instiName == $val2 && $status == "active") {
				return 'login';
			} else if ($instiName == $val2 && $status == "pending") {
				return "pending";
			} else {
				return "invalid";
			}
		}
	} else {
		return 'invalid';
	}
}

// function getUserClassData($conn, $val1 = null, $val2 = null)
// {
//     $insti = explode("-", $val2)[0];
//     $status = "active";
//     $accessClz = [];
// 	$sql = "SELECT ClassId,ClassName FROM class WHERE InstiName = ? and Status = ? and Year = (SELECT Year FROM user WHERE UserId = ?)";
// 	$stmt = $conn->prepare($sql);
// 	$stmt->bind_param("sss", $insti ,$status ,$val1);
// 	$stmt->execute();
// 	$rusalt = $stmt->get_result();
// 	while ($row = $rusalt->fetch_assoc()) {
// 	    $ClassId = $row['ClassId'];
// 	    $accessClz[$ClassId] = $row['ClassName'];
// 		// $a = reset($accessClz);

// 	}
// 	$a = array_key_first($accessClz);
// 	if (!isset(explode("-", $val2)[1])) {
// 		$_SESSION['clz'] = $insti . '-' . $a;
// 	}
// 	return $accessClz;
// }

// validate user
function validateuser($conn, $userid, $type, $value)
{
	if ($type == "regcode") {
		$sql = "SELECT RegCode FROM user WHERE UserId = ? and RegCode = ? ";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $userid, $value);
		$stmt->execute();
		$reusalt = $stmt->get_result();
		if ($reusalt->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	} elseif ($type == "instiid") {
		$sql = "SELECT InstiId FROM userdata WHERE UserId = ? and InstiId = ? ";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ss", $userid, $value);
		$stmt->execute();
		$reusalt = $stmt->get_result();
		if ($reusalt->num_rows > 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
