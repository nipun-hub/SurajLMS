<form action="test.php" method="post">
  <input type="tetx" name="base64_data" id="base64_data">
  <button type="submit">Convert Image</button>
</form>

<?php
// 
// $names = "[2][1][2]";
// $list = [1, 3, 4, 1 , 4 ,1 ,1 ,3];
// $list = [1, 3, 4, 1 , 4 ,1 ,1 ,3];
// $namesNew = substr($names, 1, -1);
// echo $namesNew . "<br>";
// echo "\t";
// array_push($list, 'e');
// 
// $nameList = explode("][", substr($names, 1, -1));
// print_r($nameList);
// print_r($list);
// array_pop($list);
// print_r($list);
// echo in_array(2, $list) ? 'in' : 'not';
// $recGId = explode("][", substr($names, 1, -1));
// $newlist = [];
// foreach ($list as $val) {
// in_array($val, $newlist) ? null : array_push($newlist, $val);
// }
// 
// sort($newlist);
// print_r($newlist);


// Check if base64 data is provided
// if (!isset($_POST['base64_data'])) {
//   echo "Error: No base64 data provided.";
//   exit;
// }

// $hemdata = ``;

// Extract image data and type

// Check if base64 data is provided
if (!isset($_POST['base64_data'])) {
  echo "Error: No base64 data provided.";
  exit;
}

// Extract image data and type
$data = explode(',', $_POST['base64_data']);
$type = explode(';', $data[0])[0];
$type = str_replace('data:image/', '', $type);
$base64_image = base64_decode($data[1]);

// Validate image type (optional for security)
$allowed_types = array('png', 'jpg', 'jpeg');
if (!in_array($type, $allowed_types)) {
  echo "Error: Unsupported image type.";
  exit;
}

// Generate a unique filename
$filename = uniqid() . '.' . $type;

// Save the image file
if (file_put_contents($filename, $base64_image) !== false) {
  // Display the image
  echo "<img src='$filename' alt='Converted Image'>";
} else {
  echo "Error: Failed to save image file.";
}

$folder_path = 'saveimage/'; // Replace with your desired folder path

$filename = $folder_path . uniqid() . '.' . $type;

if (file_put_contents($filename, $data) !== false) {
  echo "Image uploaded successfully to folder: $folder_path"; // Or redirect to success page
} else {
  echo "Error: Failed to save image file.";
}

?>