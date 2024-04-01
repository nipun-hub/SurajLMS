<?php
// db connection include
include('../Dachbord/sql/conn.php');

try {
    // Retrieve JSON from POST body 
    $jsonStr = file_get_contents('php://input');
    $jsonObj = json_decode($jsonStr);

    if (!empty($jsonObj->request_type) && $jsonObj->request_type == 'user_auth') {
        $credential = !empty($jsonObj->credential) ? $jsonObj->credential : '';

        // Decode response payload from JWT token 
        list($header, $payload, $signature) = explode(".", $credential);
        $responsePayload = json_decode(base64_decode($payload));

        if (!empty($responsePayload)) {
            // The user's profile info 
            $oauth_provider = 'google';
            $oauth_uid  = !empty($responsePayload->sub) ? $responsePayload->sub : '';
            $first_name = !empty($responsePayload->given_name) ? $responsePayload->given_name : '';
            $last_name  = !empty($responsePayload->family_name) ? $responsePayload->family_name : '';
            $email      = !empty($responsePayload->email) ? $responsePayload->email : '';
            $picture    = !empty($responsePayload->picture) ? $responsePayload->picture : '';
            $userName = $first_name . " " . $last_name;

            // Check whether the user data already exist in the database 
            $sql = "SELECT UserId FROM user WHERE Email = ? and AuthId = ? and RegCode IS NOT NULL";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $oauth_uid);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                $_SESSION['login'] = $row['UserId'];
                $status = 2;
                $msg = "Account login successfully!";
            } else {
                $sql = "SELECT UserId FROM user WHERE Email = ? and AuthId = ? and RegCode IS NULL";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $email , $oauth_uid);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $status = 3;
                    $msg = "Account alredy loged and not fill details";
                } else {
                    // insert user data
                    $sql = "INSERT INTO user(UserName,Email,AuthId,Picture) VALUE(?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $userName, $email, $oauth_uid, $picture);
                    $stmt->execute();
                    $status = 1;
                    $msg = "Account data inserted successfully!";
                }
            }
            $stmt->close();

            $output = [
                'status' => $status,
                'msg' => $msg,
                'pdata' => $responsePayload
            ];
            echo json_encode($output);
        } else {
            echo json_encode(['error' => 'Account data is not available! 1']);
        }
    } else {
        echo json_encode(['error' => 'Account data is not available! 2']);
    }
} catch (Exception $e) {
    echo json_encode(['error' => 'Account data is not available! 3'.$e]);
}
