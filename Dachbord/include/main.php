<?php if (isset($_REQUEST['logout'])) {
	session_destroy();
	header('location:./');
	exit;
} ?>

<?php
// chech logged user ?
if (!isset($_SESSION['login'])) {
	$protocol = $_SERVER['REQUEST_SCHEME'];
	$host = $_SERVER['HTTP_HOST'];
	$path = $_SERVER['REQUEST_URI'];

	$fullUrl = $protocol . '://' . $host . $path;

	// echo $fullUrl;
	header('location:../login/?login&data=' . $fullUrl);
	exit;
} else {
	$UserId = $_SESSION['login'];
	$sql = "SELECT UserName,RegCode,picture FROM user WHERE UserId = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("s", $UserId);
	$stmt->execute();
	$result_data = $stmt->get_result();
	if ($row = $result_data->fetch_assoc()) {
		$_SESSION['username'] = $row['UserName'];
		$_SESSION['regcode'] = $row['RegCode'];
		$pict = isset($row['picture']) ? $row['picture'] : "assets/img/user.jpeg";
	} else {
		header('location:../login');
		exit;
	}
}
?>