<?php if (isset($_REQUEST['logout'])) {
	session_destroy();
	header('location:./');
	exit;
} ?>

<?php
// chech logged user ?
if (!isset($_SESSION['login'])) {
	header('location:../login');
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
        $pict = $row['picture'];
	} else {
		header('location:../login');
		exit;
	}
}
?>