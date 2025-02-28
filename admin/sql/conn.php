 <?php
  //  database connection
    // $conn=new mysqli("localhost","godadydulaxsuraj","JSo{5#rcV{kA","suraj_ict_lms");
  // $conn = new mysqli("127.0.0.1", "root", "", "ict_lms");

      // Database connection using mysqli with error handling
      $host = "mariadb-container"; // Docker service name
      $username = "db_user";
      $password = "JSo{5#rcV{kA";
      $database = "ict_lms";
    
      // Create connection
      $conn = new mysqli($host, $username, $password, $database);
    
      // Check connection
      if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
      }

mysqli_set_charset($conn, "utf8mb4");

  //  time zoon
  date_default_timezone_set("Asia/colombo");

  //  session start
  session_start();
  ?>
