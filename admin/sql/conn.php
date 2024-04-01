 <?php
  //  database connection
    $conn=new mysqli("localhost","godadydulaxsuraj","JSo{5#rcV{kA","suraj_ict_lms");
  // $conn = new mysqli("127.0.0.1", "root", "", "ict_lms");

mysqli_set_charset($conn, "utf8mb4");

  //  time zoon
  date_default_timezone_set("Asia/colombo");

  //  session start
  session_start();


  ?>
