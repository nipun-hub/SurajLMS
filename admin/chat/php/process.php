
<?php
// session_start();

use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard\Expression;

include_once "../../sql/conn.php";

if (true) {
    $outgoing_id = "A" . $_SESSION['adminlogin'];
}

if (true) {

    // $chatHead = "
    // <div class='col-12'>
    //     <div class='mt-1 mx-2'>
    //         <div class='card-body vh60  overflow-y-scroll'>
    //             <ul class='chats  bottom-0 w-100 pt-3'>
    //             </ul>
    //         </div>
    //     </div>
    // </div>";
}

if (isset($_POST['getUser'])) {
    $status = true;
    $data = array();

    try {
        $sql = "SELECT u.UserName,CONCAT(InstiName,' - ',Year) as insti,u.Picture,m.*,(CASE WHEN m.Status = 1 THEN 0 ELSE msc.msgCount END) as msgCount FROM messages m 
                INNER JOIN ( SELECT MAX(date) AS max_date, outgoing_msg_id FROM messages GROUP BY outgoing_msg_id ) max_dates ON m.date = max_dates.max_date AND m.outgoing_msg_id = max_dates.outgoing_msg_id 
                INNER JOIN ( SELECT SUM(CASE WHEN Status = 2 THEN 1 ELSE 0 END) AS msgCount,outgoing_msg_id FROM messages GROUP BY outgoing_msg_id ) msc ON m.outgoing_msg_id = msc.outgoing_msg_id 
                INNER JOIN user u ON m.outgoing_msg_id = u.UserId ORDER BY m.date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $data[] = array($row['outgoing_msg_id'], $row['Picture'], $row['UserName'], $row['insti'], $row['msg'], $row['msgCount']);
        }

        $status = true;
    } catch (Exception $th) {
        $status = false;
    }

    $respons = array(
        "Status" => $status,
        "Data" => $data
    );

    send($respons, 'json');
} elseif (isset($_POST['getChat'])) {

    $status = true;
    $data = "";
    $id = $_POST['id'];

    try {
        $sql = "SELECT messages.*,user.picture FROM messages INNER JOIN user ON user.UserId = messages.outgoing_msg_id OR user.UserId = messages.incoming_msg_id WHERE ( messages.outgoing_msg_id = ? OR messages.incoming_msg_id = ? ) AND messages.Status !=0 ORDER BY messages.date";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $id);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $chatBody = "";
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $time = date("H:i", strtotime($row['date']));
            if ($row['incoming_msg_id'] == 0 || $row['incoming_msg_id'] == $outgoing_id) {
                # this is income massage
                $massage = detectLinks($row['msg']);
                $chatBody .= "
                <li class='chats-left'>
                <div class='chats-avatar'>
                <img src='{$row['picture']}' alt='user' onerror='notImage(this)'>
                </div>
                <div class='chats-text'>{$massage}</div>
                <div class='chats-hour'>{$time}<span class='icon-done_all'></div>
                </li>";
            } else {
                $read = $row['Status'] == 0 ? "<i class='bi bi-check'></i>" : "<i class='bi bi-check-all'></i>";
                $massage = detectLinks($row['msg']);
                $chatBody .= "
                    <li class='chats-right'>
                        <div class='d-flex flex-row-reverse'>
                            <div class='chats-avatar ms-3'>
                                <img src='assets/img/site use/admin.jpg' alt='user' onerror='notImage(this)'>
                            </div>
                            <div class='chats-text'>{$massage}</div>
                        </div>
                        <div class='chats-hour'>{$time}<span class='icon-done_all'></span>{$read}</div>
                    </li>";
            }
        }

        $data = $chatBody;
        $status = true;
        // write($data);
    } catch (Expression $th) {
        $status = false;
    }

    // $respons = array(
    //     "Status" => $status,
    //     "Data" => $data
    // );
    send($data);
} elseif (isset($_POST['updateChat'])) {

    $status = true;
    $data = "";
    $id = $_POST['id'];

    try {
        $sql = "SELECT messages.*,user.picture FROM messages INNER JOIN user ON user.UserId = messages.outgoing_msg_id OR user.UserId = messages.incoming_msg_id WHERE ( messages.outgoing_msg_id = ? AND messages.Status != 0 ) or ( messages.incoming_msg_id = ? )  ORDER BY messages.date";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id, $id);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $chatBody = "";
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $time = date("H:i", strtotime($row['date']));
            if ($row['incoming_msg_id'] == 0 || $row['incoming_msg_id'] == $outgoing_id) {
                # this is income massage
                if ($row['Status'] == 2) {
                    $massageId = $row['msg_id'];
                    $sql = "UPDATE messages SET Status = 1 WHERE msg_id = '$massageId' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                }
                $massage = detectLinks($row['msg']);
                $chatBody .= "
                    <li class='chats-left'>
                        <div class='chats-avatar'>
                            <img src='{$row['picture']}' alt='user' onerror='notImage(this)'>
                        </div>
                        <div class='chats-text'>{$massage}</div>
                        <div class='chats-hour'>{$time}<span class='icon-done_all'></div>
                    </li>";
            } else {
                $read = $row['Status'] != 1 ? "<i class='bi bi-check'></i>" : "<i class='bi bi-check-all'></i>";
                if ($row['Status'] == 0) {
                    $massageId = $row['msg_id'];
                    $sql = "UPDATE messages SET Status = 2 WHERE msg_id = '$massageId' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                }
                $massage = detectLinks($row['msg']);
                $chatBody .= "
                    <li class='chats-right'>
                        <div class='d-flex flex-row-reverse'>
                            <div class='chats-avatar ms-3'>
                                <img src='assets/img/site use/admin.jpg' alt='user' onerror='notImage(this)'>
                            </div>
                            <div class='chats-text'>{$massage}</div>
                        </div>
                        <div class='chats-hour'>{$time}<span class='icon-done_all'></span>{$read}</div>
                    </li>";
            }
        }
        if (!$reusalt->num_rows) {
            $status = false;
            $data = "";
        } else {
            $status = true;
            $data = $chatBody;
        }
    } catch (Expression $th) {
        $status = false;
    }

    $respons = array(
        "Status" => $status,
        "Data" => $data,
    );
    send($respons, 'json');
} elseif (isset($_POST['sendMassage'])) {
    $massage = $_POST['massage'];
    $id = $_POST['id'];

    try {
        $sql = "INSERT INTO messages(incoming_msg_id, outgoing_msg_id, msg) VALUES(?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $id, $outgoing_id, $massage);
        $stmt->execute();
    } catch (\Throwable $th) {
        write($th);
    }
}

function detectLinks($text)
{
    // Regular expression to match URLs (supports various protocols and subdomains)
    $pattern = "/(((http|https|ftp|ftps)\:\/\/)|(www\.))[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\:[0-9]+)?(\/\S*)?/";

    // Find all matches using preg_match_all
    preg_match_all($pattern, $text, $matches);

    // Extract the actual URLs from the matches
    $links = array();
    if (!empty($matches[0])) {
        foreach ($matches[0] as $url) {
            // $links[] = $url;
            $text = str_replace($url, "<a href='{$url}' target='_blank'>{$url}</a>", $text);
        }
    }

    return $text;
}

function send($respons, $type = null)
{
    if ($type == 'json') {
        header('Content-Type: application/json');
        echo json_encode($respons);
    } else {
        echo $respons;
    }
}

function write($data)
{
    // Open the file for writing
    $myfile = fopen("text.txt", 'a') or die("Unable to open file!");

    // Write the data to the file
    fwrite($myfile, $data . "\n");

    // Close the file
    fclose($myfile);
}

?>

