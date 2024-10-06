<?php

if (isset($_REQUEST['logout'])) {
    session_destroy();
    header('location:./');
    exit;
}

// chech logged user ?
if (!isset($_SESSION['adminlogin'])) {
    header('location:login/');
    exit;
} else {
    $UserId = $_SESSION['adminlogin'];
    $sql = "SELECT UName,Access,Type FROM adminuser WHERE AdminId = ? and Status = 'active'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $UserId);
    $stmt->execute();
    $result_data = $stmt->get_result();
    $stmt->close();
    $row = $result_data->fetch_assoc();
    $UserName = $result_data->num_rows > 0 ? $row['UName'] : null;
    $adminAcsess = $result_data->num_rows > 0 ? explode("][", substr($row['Access'], 1, -1)) : null;
    $adminType = $result_data->num_rows > 0 ? explode("-", $row['Type']) : null;
}
if (true) {
    $sql = "SELECT * FROM class WHERE Conducting = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $reusalt = $stmt->get_result();
    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $classId = $row['ClassId'];
        $activeClassName = $row['InstiName'];
        $decodeDict = json_decode($row['Dict'], true);
        $endTime = $decodeDict[2];
        $currentTime = GetToday('hi', ':');
        if ($endTime < $currentTime) {
            $sql = "UPDATE class SET Conducting = 0 , Dict  = NULL WHERE ClassId = '$classId'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
        }
    }
}
