<?php include('../Dachbord/sql/conn.php'); ?>


<?php

// check alredy loged
if (isset($_POST['checkLoged']) && isset($_SESSION['login'])) {
  echo "done";
}

$file_type = "png";
// $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
// $txt = "start";
if (isset($_POST['register'])) {

  // getting pass data
  $fname = $_POST['fname'];
  $lname = $_POST['lname'];
  $email = $_POST['email'];
  $nic = $_POST['nic'];
  $fileTmpName = isset($_FILES['nic_pic']) ? $_FILES['nic_pic']['tmp_name'] : null;
  $instiFileTmpName = isset($_FILES['paySlip']) ? $_FILES['paySlip']['tmp_name'] : null;
  // $NicPic = "images";
  $NumMob = $_POST['NumMob'];
  $NumWha = $_POST['NumWha'];
  $dob = $_POST['dob'];
  $school = $_POST['school'];
  $year = $_POST['year'];
  $streem = $_POST['streem'];
  $shy = $_POST['shy'];
  $medium = $_POST['medium'];
  $address = $_POST['address'];
  $dictric = $_POST['dictric'];
  $city = $_POST['city'];
  $insti = $_POST['insti'];

  $UserName = $fname . " " . $lname;
  $regCode = null;

  try {
    $sql = "SELECT UserId FROM user WHERE Email = ? and RegCode IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
      try {
        $nic = ($nic == "") ? null : $_POST['nic'];
        $insert_id = $row['UserId'];
        $regCode = "ICT" . substr($year, -2) . $insert_id; // make reg code
        $fillCount = 10 - strlen($regCode);
        $regCode = substr($regCode, 0, 5) . str_repeat(0, $fillCount) . substr($regCode, 5);

        $instituteData = explode("-", $insti);
        $instiName = $instituteData[1];
        $instiId = $instituteData[0];

        $fileName = isset($_FILES['nic_pic']) ? "Nic-" . $insert_id . "-" . date("Ymd") . ".jpg" : null;
        $instiPicName = isset($_FILES['paySlip']) ? "instiPic-" . $insert_id . ".jpg" : null;

        $targetFile = "../Dachbord/user_images/nic_pic/" . $fileName;
        $instiTargetFile = "../Dachbord/user_images/instiRegImg/" . $instiPicName;

        $active = "active";

        $conn->begin_transaction();
        $sql = "INSERT INTO `userdata`(`UserId`, `RegCode`, `Fname`, `Lname`, `Email`, `Nic`, `NicPic`, `MobNum`, `WhaNum`, `Dob`, `SchName`, `Year`, `Streem`, `Shy`, `Medium`, `Address`, `Distric`, `City`,`InstiName`,`InstiId`,`InstiPic`,`Status`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssssssssssssss", $insert_id, $regCode, $fname, $lname, $email, $nic, $fileName, $NumMob, $NumWha, $dob, $school, $year, $streem, $shy, $medium, $address, $dictric, $city, $instiName, $regCode, $instiPicName, $active);
        $stmt->execute();

        $sql = "UPDATE user SET RegCode=?, Year=?, InstiName=?, InstiId=?, Status=? WHERE UserId=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $regCode, $year, $instiName, $regCode, $active, $insert_id);
        $stmt->execute();

        // check it location
        if (!file_exists($targetFile)) {
          mkdir($targetFile, 0777, true);
        }

        if (!file_exists($instiTargetFile)) {
          mkdir($instiTargetFile, 0777, true);
        }

        // file upload
        if (isset($_FILES['nic_pic'])) {
          move_uploaded_file($fileTmpName, $targetFile);
        }
        if (isset($_FILES['paySlip'])) {
          move_uploaded_file($instiFileTmpName, $instiTargetFile);
        }
        $conn->commit();
        $_SESSION['login'] = $insert_id;
        $rusalt = "Successfull";
      } catch (Exception $e) {
        $conn->rollback();
        $rusalt = "error001" . $e;
      }
    } else {
      $rusalt = "Alredy register";
    }
  } catch (Exception $e) {
    $rusalt = "error004";
    $file = fopen("myfile.txt", "a"); // Open in write mode (overwrites existing content)
    fwrite($file, $e);
  }

  // json output array
  $output = array(
    'rusalt'    =>  $rusalt,
    'regcode'    =>  $regCode,
  );
  WriteFile(json_encode($output));
  echo json_encode($output);
}

function generateRegCode($year, $id)
{
  $regCode = "ICT" . substr($year, -2) . $id; // make reg code
  $fillCount = 10 - strlen($regCode);
  $regCode = substr($regCode, 0, 5) . str_repeat(0, $fillCount) . substr($regCode, 5);
  return $regCode;
}

function WriteFile($content)
{
  $file = fopen("myfile.txt", "a"); // Open in write mode (overwrites existing content)
  fwrite($file, $content);
  fclose($file);
}
?>