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
		if ($type == 'hi') {
			$contents = "H{$Separate}i";
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

	// get month name
	function GetMonthName($number)
	{
		try {
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

			if ($number > 0 && $number < 13) {
				return $months[$number];
			} else {
				return "Undefind";
			}
		} catch (Exception $e) {
			return "Undefind";
		}
	}
} catch (Exception $e) {
	return 'error';
}

// user gide GetToday function 👇
// GetToday($type,$Separate);
// $type --->> output date type ( ym -> '2021-10' , ymd -> '2024-10-21' , ymdhis -> '2024-10-20-12-55-50' , timeOfDay -> 'Morning,Afternoon,Evening,Night')


// ************************ side bar functions ************************

function GetActive($PageName, $type = null)
{
	if ($type == null) {
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
	} elseif ($type == 'sub') {
		if (isset($_SESSION['active'])) {
			$active = $_SESSION['active'];
			switch ($PageName) {
				case $PageName == $active:
					return 'current-page';
					break;

				default:
					return 'not-current-page';
					break;
			}
		} else {
			return 'not-current-page';
		}
	}
}

// user gide for GetActive function
// GetActive($PageName);
// GetActive($PageName) -->> rerurn value = active or empty

// get youtube link id
function get_youtube_video_id($url)
{
	// Extract the video ID from the URL.
	try {
		$video_id = $url = str_replace("https://youtube.com/live/", "", $url);
		$video_id = $url = str_replace("https://youtu.be/", "", $url);
		if (stripos($video_id, '?') !== false) {
			$video_id = substr($video_id, 0, strpos($video_id, "?"));
		}
	} catch (Exception $e) {
		$video_id = null;
	}
	return $video_id;
}

// get google drive id
function get_google_drive__id($url)
{
	$url = str_replace("https://drive.google.com/file/d/", "", $url);
	$url = substr($url, 0, strpos($url, "/"));
	return $url;
}


// databade finctions