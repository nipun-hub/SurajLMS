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
//   $guaname = $_POST['guaname'];
//   $guanum = $_POST['guanum'];

  $UserName = $fname ." ". $lname;
  $regCode = null;

    try {
        $sql = "SELECT UserId FROM user WHERE Email = ? and RegCode IS NULL";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
            try{
                $nic = ($nic=="") ? null : $_POST['nic'];
                $insert_id = $row['UserId'];
                $regCode = "ICT" . substr($year, -2) . $insert_id; // make reg code
                $regCode = str_pad($regCode, 10, '0', STR_PAD_RIGHT); // fill 10 all caractoy to '0'
                // $fileName = "Nic-" . $insert_id . "-" . date("Ymd") . ".jpg";
                $fileName = isset($_FILES['nic_pic']) ? "Nic-" . $insert_id . "-" . date("Ymd") . ".jpg" : null;
                $targetFile = "../Dachbord/user_images/nic_pic/" . $fileName;
                
                $conn->begin_transaction();
                $sql = "INSERT INTO `userdata`(`UserId`, `RegCode`, `Fname`, `Lname`, `Email`, `Nic`, `NicPic`, `MobNum`, `WhaNum`, `Dob`, `SchName`, `Year`, `Streem`, `Shy`, `Medium`, `Address`, `Distric`, `City`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssssssssssssss", $insert_id, $regCode, $fname, $lname, $email, $nic, $fileName, $NumMob, $NumWha, $dob, $school, $year, $streem, $shy, $medium, $address, $dictric, $city);
                $stmt->execute();
                
                $sql = "UPDATE user SET RegCode=? , Year=? WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $regCode, $year,$insert_id);
                if ($stmt->execute()) {
                    // file upload
                    (isset($_FILES['nic_pic'])) ? move_uploaded_file($fileTmpName, $targetFile) : $rusalt = "";
                    $_SESSION['login'] = $insert_id;
                    $rusalt = "Successfull";
                }
                $conn->commit();
            } catch (Exception $e){
                $conn->rollback();
                $rusalt = "error001".$e;
            }
        } else {
          $rusalt = "ardins";
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
  echo json_encode($output);
}
?>