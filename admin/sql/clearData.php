<?php

// database connection

try {
    include('conn.php');
    include('function.php');
} catch (EXception $e) {
    echo "Connection Error";
}


// clear Payment Data function 
// if (isset($_POST['ClearPaymentImage'])) {
$today = GetToday('ymd');
$thisMonth = GetToday('ym');
$after_month = date('Ym', strtotime('-2 months', strtotime($thisMonth)));

$sql = "SELECT * FROM `payment` WHERE `Month` < $after_month and FileStatus = 1";
$stmt = $conn->prepare($sql);
$stmt->execute();
$reusalt = $stmt->get_result();
while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
    $unlinkParth = "../../Dachbord/user_images/payment/" . $row['Slip'];
    if (is_file($unlinkParth)) {
        try {
            unlink($unlinkParth);
            $status = "true";
        } catch (\Throwable $th) {
            $status = "false";
        }
    } else {
        $status = "false";
    }

    $PayId = $row['PayId'];
    $sql = "UPDATE payment SET FileStatus = 0 WHERE PayId = $PayId";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->close();

    DeletePaymentFile("Deleted   $status   " . $PayId . "        " . $row['Month'] . "    -     " . $row['Slip']);
}

// }


function DeletePaymentFile($value)
{
    $myfile = fopen("deletePaymentFileLog.txt", "a") or die("Unable to open file!");
    fwrite($myfile, "\n" . $value);
    fclose($myfile);
}
