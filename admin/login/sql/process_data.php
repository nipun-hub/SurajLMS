<?php

try {
  include('../../sql/conn.php');
} catch (Exception $e) {
  $rusalt = "main error";
}

if (isset($_POST['admin_register'])) {
  $reguname = $_POST['reguname'];
  $mobnum = $_POST['mobnum'];
  $email = $_POST['email'];
  $pass = $_POST['pass'];

  try {
    $sql = "SELECT * FROM adminuser WHERE Email = ? or MobNum = ? or UName = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $email, $mobnum, $reguname);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $rusalt = "alredy register";
    } else {
      $sql = "INSERT INTO adminuser(UName , Email , Password , MobNum) VALUE(?,?,?,?)";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("ssss", $reguname, $email, $pass, $mobnum);
      if ($stmt->execute()) {
        $rusalt = "Successfull";
      } else {
        $rusalt = "error001";
      }
    }
  } catch (Exception $th) {
    $rusalt = "error002";
    $fp = fopen("myfile.csv", "w");
    fwrite($fp, $th);
    fclose($fp);
  }
} elseif (isset($_POST['admin_login'])) {
  $uname = $_POST['URegCode'];
  $pass = $_POST['URegPass'];

  try {
    $sql = "SELECT * FROM adminuser WHERE ( UName = ? or Email = ? ) and Password = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $uname, $uname, $pass);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      $rusalt = "Successfull";
      $_SESSION['adminlogin'] = $row['AdminId'];
    } else {
      $rusalt = "error";
    }
  } catch (Exception $th) {
    $rusalt = "error03";
  }
} else {
  $rusalt = "not register";
}

// } elseif (isset($_POST['login'])) {

//   $URegCode = $_POST['URegCode'];
//   $URegPass = $_POST['URegPass'];

//   $sql = "SELECT * FROM user WHERE (RegCode = ? and Password= ? ) or ( Email = ? and Password = ? )";
//   $stmt = $conn->prepare($sql);
//   $stmt->bind_param("ssss", $URegCode, $URegPass, $URegCode, $URegPass);
//   $stmt->execute();
//   $result_data = $stmt->get_result();
//   if ($row = $result_data->fetch_assoc()) {
//     $rusalt = "successfyll";
//     $_SESSION['login'] = $row['UserId'];
//   } else {
//     $rusalt = "error05";
//   }

//   // json output array
//   $output = array(
//     'rusalt'    =>  $rusalt,
//   );
//   echo json_encode($output);
// }


// $rusalt = "dfgsdfgd";
// $output = array(
//   'rusalt'    =>  $rusalt,
// );
// echo json_encode($output);

$output = array(
  'rusalt'    =>  $rusalt,
);
echo json_encode($output);
