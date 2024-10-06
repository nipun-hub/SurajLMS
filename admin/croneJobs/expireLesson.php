<?php

// database connection
try {
    include('../sql/conn.php');
    include('../sql/function.php');
} catch (EXception $e) {
    echo "Connection Error";
}

$today = GetToday("ymd", '-');
$sql = "UPDATE recaccess SET Status = 'finished' WHERE ExpDate IS NOT NULL AND ExpDate < ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $today);
$stmt->execute();

$stmt->close();
$conn->close();
