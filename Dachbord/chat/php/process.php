
<?php
// session_start();
include_once "../../sql/conn.php";

// insert massage 
if (isset($_POST['insertMassage']) && isset($_SESSION['login'])) {

    $outgoing_id = "{$_SESSION['login']}";
    $incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : '0';
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    $type = null;
    if (isset($_POST['type'])) {
        $type = mysqli_real_escape_string($conn, $_POST['type']);
        $url = mysqli_real_escape_string($conn, $_POST['url']);
        if (!empty($message) && $type != null) {
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id,type,url)
                                            VALUES ({$incoming_id}, {$outgoing_id}, '{$type}', '{$url}')") or die();
        }
    } else {
        if (!empty($message) && $type == null) {
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                            VALUES ({$incoming_id}, {$outgoing_id}, '{$message}')") or die();
        }
    }
} elseif (isset($_POST['GetChat'])) {
    $outgoing_id = "{$_SESSION['login']}";
    $incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : '0';
    $respons = "";
    $sql = "SELECT * FROM messages WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id} AND Status = 1) OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id} and Status = 1) ORDER BY msg_id";
    $query = mysqli_query($conn, $sql);
    write('out ' . $outgoing_id);
    write('in ' . $incoming_id);

    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_assoc($query)) {
            // $time = substr($row['date'], 10, 12);
            $time = date("H:i", strtotime($row['date']));
            $msg = detectLinks($row['msg']);
            if ($row['outgoing_msg_id'] === $outgoing_id) {
                $read = $row['Status'] == 1 ? "<i class='bi bi-check-all'></i>" : "<i class='bi bi-check'></i>";
                $respons .=
                    "<div class='chatbox-message-item sent'>
                        <span class='chatbox-message-item-text'>
                            {$msg}
                        </span>
                        <span class='chatbox-message-item-time'>{$time} &nbsp {$read}</span>
                    </div>";
            } else {
                $respons .= "
                <div class='chatbox-message-item received'>
                    <span class='chatbox-message-item-text'>
                        {$msg}
                    </span>
                     <span class='chatbox-message-item-time'>{$time} &nbsp</span>
                </div>";
            }
        }
    }
    echo $respons;
} elseif (isset($_POST['updateChat'])) {
    $outgoing_id = "{$_SESSION['login']}";
    $incoming_id = isset($_POST['incoming_id']) ? mysqli_real_escape_string($conn, $_POST['incoming_id']) : '0';
    $respons = "";
    $sql = "SELECT * FROM messages WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id} AND Status !=1) OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id} AND Status != 1) ORDER BY msg_id";
    $query = mysqli_query($conn, $sql);
    if (mysqli_num_rows($query) > 0) {

        $sql = "SELECT * FROM messages WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id}) OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY date";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0) {
            while ($row = mysqli_fetch_assoc($query)) {
                $time = date("H:i", strtotime($row['date']));
                // check in links 
                $msg = detectLinks($row['msg']);
                if ($row['outgoing_msg_id'] === $outgoing_id) {
                    if ($row['Status'] == 0) {
                        $massageId = $row['msg_id'];
                        $sql = "UPDATE messages SET Status = 2 WHERE msg_id = '$massageId' ";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                    }
                    $read = $row['Status'] == 1 ? "<i class='bi bi-check-all'></i>" : "<i class='bi bi-check'></i>";
                    $respons .=
                        "<div class='chatbox-message-item sent'>
                            <span class='chatbox-message-item-text'>
                                {$msg}
                            </span>
                            <span class='chatbox-message-item-time'>{$time} &nbsp {$read}</span>
                        </div>";
                } else {
                    if ($row['Status'] == 2) {
                        $massageId = $row['msg_id'];
                        $sql = "UPDATE messages SET Status = 1 WHERE msg_id = '$massageId' ";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                    }
                    $respons .= "
                        <div class='chatbox-message-item received'>
                            <span class='chatbox-message-item-text'>
                                {$msg}
                            </span>
                             <span class='chatbox-message-item-time'>{$time} &nbsp</span>
                        </div>";
                }
            }
        }
    }
    echo $respons;
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

    // $link = detectLinks($msg);
    // if (!empty($link)) {
    //     foreach ($link as $a) {
    //         $msg = str_replace($a, "<a href='{$a}'>{$a}</a></li>", $msg);
    //     }
    // }
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

