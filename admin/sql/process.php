<?php

// database connection

use function PHPSTORM_META\type;

try {
    include('conn.php');
} catch (EXception $e) {
    echo "Connection Error";
}

if (isset($_SESSION['adminlogin'])) {
    $UserId = $_SESSION['adminlogin'];
    $sql = "SELECT UName,Access,Type FROM adminuser WHERE AdminId = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $UserId);
    $stmt->execute();
    $result_data = $stmt->get_result();
    $stmt->close();
    $row = $result_data->fetch_assoc();
    $UserName = $row['UName'];
    $adminAcsess = explode("][", substr($row['Access'], 1, -1));
    $adminType = explode("-", $row['Type']);
    $hiddenStatus = $adminType[0] == 'owner' || $adminType[0] == 'editor' ? null : 'hidden';
} else {
    $UserId = 'null';
    echo "error loading";
}

try {
    include('function.php');
} catch (Exception $e) {
    echo " error loading";
}

// get html content section start ****************

// get group list start

if (isset($_POST['getGroupList'])) {
    $status = "active";
    $htmlContent = "";
    $sql = "SELECT MGName FROM grouplist WHERE Status = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $reusalt = $stmt->get_result();
    while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $MGName = $row['MGName'];
        $htmlContent .= "<option>{$MGName}</option>";
    }
    $stmt->close();
    echo $htmlContent;
}

// get group list end

// get class list start

if (isset($_POST['getClassList'])) {
    $status = "active";
    $htmlContent = "<option value = ''>Celect the class</option>";
    $sql = "SELECT * FROM class WHERE Status = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $reusalt = $stmt->get_result();
    while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $ClassName = $row['year'] . "-" . $row['ClassName'] . "-" . $row['InstiName'];
        $htmlContent .= "<option value = '{$row['ClassId']}'>{$ClassName}</option>";
    }
    $htmlContent = $reusalt->num_rows < 1 ? "<option value = ''>Undefined</option>" : $htmlContent;
    $stmt->close();
    echo $htmlContent;
}


if (isset($_POST['getinstitute'])) {
    $status = "active";
    $htmlContent = "";
    $htmlContent .= "<option value=''>Select Institute</option>";
    $sql = "SELECT InstiName FROM insti WHERE Status = ? ";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
    $stmt->execute();
    $reusalt = $stmt->get_result();
    while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $instiName = $row['InstiName'];
        $htmlContent .= "<option value='{$instiName}'>{$instiName}</option>";
    }
    $stmt->close();
    echo $htmlContent;
}


if (isset($_POST['getQiozList'])) {
    $OptionContent = "";
    $OptionContent = "<option = value=''>Select Quiz</option>";
    $sql = "SELECT LesName,LesId FROM lesson WHERE Status = 'active' and Type = 'quiz' ORDER BY InsertDate DESC";
    $stmt = $conn->prepare($sql);
    // $stmt->bind_param("")
    $stmt->execute();
    $reusalt = $stmt->get_result();
    while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $OptionContent .= "<option value='{$row['LesId']}'>{$row['LesName']}</option>";
    }
    $stmt->close();
    echo $OptionContent;
}
// get html content section end *******************

// index page proccess start **********************

// add current class start 
if (isset($_POST['addCurrentClass'])) {
    if ($_POST['addCurrentClass'] == 'del') {
        $sql = "UPDATE class SET Conducting = 0 , Dict = NULL";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $respons = "Done Delete";
    } else {
        try {
            $data = json_decode($_POST['addCurrentClass']);
            $classId = $data[0];

            $conn->begin_transaction();
            $sql = "UPDATE class SET Conducting = 0 , Dict = NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $dataNew = json_encode($data);
            $sql = "UPDATE class SET Conducting = 1 , Dict = ? WHERE ClassId = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $dataNew, $classId);
            $stmt->execute();
            $conn->commit();

            $respons = "success";
        } catch (Exception $e) {
            $respons = "undefined";
        }
    }
    echo $respons;
}
// add current class end

// index page proccess end *******************

// atendent page start ***********************

if (isset($_POST['loadAtendentAlertData'])) {
    try {
        $id = $_POST['data'];
        $month = GetToday('ym');
        $classId = $_SESSION['ClassId'];
        $error = "
            <div class='modal-header'>
                <h5 class='modal-title' id='verticallyCenteredLabel'>Scaned Data (ID : <span id=''>{$id}</scan>)</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body'>
                <h5 class=''><span class='text-dark'>Invalid Id Plece try again!</span></h5>
            </div>
            <div class='modal-footer'>
                <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
            </div>
        ";
        if ($classId != 'undefind') {

            $sql = "SELECT payment.PayId , payment.Status , user.UserId FROM payment,user WHERE ( user.RegCode = ? OR user.InstiId = ? ) && user.UserId = payment.UserId and payment.ClassId = ? and payment.Month = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $id, $id, $classId, $month);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $indigator = $row['Status'] == 'active' ? "<span class='alert alert-success py-1'><i class='bi bi-check-circle'></i>&nbsp;Active</span>" : ($row['Status'] == 'pending' ? "<span class='alert alert-warning py-1'><i class='bi bi-lock'></i>&nbsp;Pending</span>" : "<span class='alert alert-danger py-1'><i class='bi bi-lock'></i>&nbsp;Not Pay</span>");
            } else {
                $indigator = "<span class='alert alert-danger py-1'><i class='bi bi-lock'></i>&nbsp;Not Pay</span>";
            }

            $activityToday = "%" . GetToday('ymd', '-') . "%";
            $sql = "SELECT user.UserId, user.UserName, user.RegCode,user.InstiName as userInsti, class.*,activity.ActId FROM user JOIN class ON class.ClassId = ? LEFT JOIN activity ON activity.UserId = user.UserId and activity.OtherId = ? and InsDate LIKE ? WHERE user.RegCode = ? OR user.InstiId = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $classId, $classId, $activityToday, $id, $id);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $ActId = $row['ActId'];
                $userInstiName = $row['userInsti'];
                $classInstiName = $row['InstiName'];
                if ($userInstiName == $classInstiName && $ActId == null) {
                    $htmlContent = "
                        <div class='modal-header'>
                            <h5 class='modal-title' id='verticallyCenteredLabel'>Scaned Data (ID : <span id=''>{$id}</scan>)</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <h5 class=''>Name : <span class='text-dark'> {$row['UserName']}</span></h5>
                            <h5 class=''>RegCode : <span class='text-dark'> {$row['RegCode']}</span></h5>
                            <h5 class=''>Insti : <span class='text-dark'>{$row['InstiName']}</span></h5>
                            <h5 class=''>Class : <span class='text-dark'> {$row['year']} {$row['ClassName']} </span></h5>
                        </div>
                        <div class='ms-2 mb-3'>
                        {$indigator}
                        </div>
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
                            <button type='button' class='btn btn-success' onclick = 'more({$row['UserId']})'>Viwe More</button>
                            <button type='button' class='btn btn-success d-flex' onclick = 'atendent({$row['UserId']},{$classId})'><span class='proccing_snipper spinner-border spinner-border-sm' role='status' aria-hidden='true'></span>&nbsp;<i class='bi bi-check2-all'></i></button>
                        </div>
                        <div class='my-3 rusaltLog mx-3'>
                            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show py-2'>Successfull add the lesson!</div>
                            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2'>Failed add the lesson</div>
                        </div>
                        ";
                } else {
                    if ($ActId != null) {
                        $content = "
                        <div class='modal-body'>
                            <h5 class='mb-3'><span class='text-dark'>Alredy Marked Atendent</span></h5>
                            {$indigator}
                        </div>
                        ";
                    } else {
                        $content = "
                        <div class='modal-body'>
                            <h5 class=''><span class='text-dark'>Invalid Institue</span></h5>
                            <h5 class=''><span class='text-dark'>In progress institute- {$classInstiName}</span></h5>
                            <h5 class=''><span class='text-dark'>User Register Institute - {$userInstiName}</span></h5>
                        </div>
                        ";
                    }
                    $htmlContent = "
                        <div class='modal-header'>
                            <h5 class='modal-title' id='verticallyCenteredLabel'>Scaned Data (ID : <span id=''>{$id}</scan>)</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        {$content}
                        <div class='modal-footer'>
                            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
                        </div>
                        ";
                }
            } else {
                $htmlContent = $error;
            }
        } else {
            $htmlContent = $error;
        }
    } catch (Exception $e) {
        $htmlContent = $error;
    }
    echo $htmlContent;
}

if (isset($_POST['markAtendent'])) {
    $userId = $_POST['val1'];
    $classId = $_POST['val2'];
    $thisMonth = GetToday('ym');

    $sql = "SELECT PayId,Status FROM payment WHERE UserId = ? and ClassId = ? and Month = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii", $userId, $classId, $thisMonth);
    $stmt->execute();
    $reusalt = $stmt->get_result();
    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $payId = $row['PayId'];
        try {
            $status = "active";
            $type = 'active-clz';

            $conn->begin_transaction();
            if ($row['Status'] != 'active') {
                $sql = "UPDATE payment SET Status = 'active' WHERE PayId = '$payId'";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $stmt->close();
            }

            $point = 10;
            $sql = "INSERT INTO activity(UserId,OtherId,Type,Point) VALUES(?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $userId, $classId, $type, $point);
            $stmt->execute();
            $stmt->close();

            $sql = "UPDATE user SET Point = (SELECT Point FROM user WHERE UserId = '$userId')+10 WHERE UserId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            $htmlContent = 'success';
        } catch (Exception $e) {
            $conn->rollback();
            $htmlContent = "error";
        }
    } else {
        try {
            $status = "active";
            $type = 'active-clz';

            $conn->begin_transaction();
            $sql = "INSERT INTO payment(UserId,ClassId,Type,Month,Status) VALUES(?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $userId, $classId, $type, $thisMonth, $status);
            $stmt->execute();
            $stmt->close();

            $point = 10;
            $sql = "INSERT INTO activity(UserId,OtherId,Type,Point) VALUES(?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $userId, $classId, $type, $point);
            $stmt->execute();
            $stmt->close();

            $sql = "UPDATE user SET Point = (SELECT Point FROM user WHERE UserId = '$userId')+10 WHERE UserId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            $htmlContent = 'success';
        } catch (Exception $e) {
            $conn->rollback();
            $htmlContent = "error";
        }
    }
    echo $htmlContent;
}

// atendent page end ***********************

//  lesson management sestion start ********

if (isset($_POST['UpdateLessonContent'])) {
    $search = $_POST['UpdateLessonContent'];
    $htmlAllContent = "";
    $tableHeaderContent = "
    <div class='table-responsive'>
        <table class='table table-bordered  v-middle'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Dict</th>
                    <th>Type</th>
                    <th>Institutes</th>
                    <th>Insert Date</th>
                    <th>Status</th>
                    <th {$hiddenStatus}>Action</th>
                </tr>
            </thead>
            <tbody>";
    $tableFooter = "          
            </tbody>
        </table>
    </div>";
    $tableBodyContent = "";
    if ($search == '') {
        $sql = "SELECT lesson.*,recaccess.ClassId FROM lesson,recaccess WHERE lesson.LesId = recaccess.LesId";
        $stmt = $conn->prepare($sql);
    } else {
        $search = "%" . $search . "%";
        $sql = "SELECT lesson.*,recaccess.ClassId FROM lesson,recaccess WHERE ( lesson.LesName LIKE ? or lesson.Type LIKE ? ) and lesson.LesId = recaccess.LesId";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $search, $search);
    }
    $stmt->execute();
    $reusalt = $stmt->get_result();
    while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $LesId = $row['LesId'];
        $name = $row['LesName'];
        $dict = empty($row['Dict']) ? "Not Found" : $row['Dict'];
        $type = $row['Type'];
        $InsertDate = substr($row['InsertDate'], 0, 10);
        // $InsertDate = DateTime::createFromFormat('Ymd', $InsertDate)->format('Y-m-d');
        $status = $row['Status'];
        if ($status == 'active') {
            $statusindi = "<span class='text-green td-status'><i class='bi bi-check-circle'></i> Active</span>";
            $actionbtn = "<a onclick='lesAction({$LesId},`disable`)'><i class='bi bi-x-circle text-red'></i></a>";
        } else if ($status == 'desable') {
            $statusindi = "<span class='text-red td-status'><i class='bi bi-x-circle'></i> Desable</span>";
            $actionbtn = "<a onclick='lesAction({$LesId},`active`)'><i class='bi bi-check-circle text-success'></i></a>";
        } else {
            $statusindi = "<span class='text-blue td-status'><i class='bi bi-clock-history'></i> Awaiting</span>";
        }

        $InstiNames = "";
        $access = explode("][", substr($row['ClassId'], 1, -1));
        foreach ($access as $value) {
            $sql = "SELECT * FROM class WHERE ClassId = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $value);
            $stmt->execute();
            $reusalt1 = $stmt->get_result();
            if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                $InstiNames .= "<p>" . $row1['InstiName'] . "</p>";
            }
        }
        $tableBodyContent .= "
        <tr>
            <td>{$name}</td>
            <td>{$dict}</td>
            <td>{$type}</td>
            <td>{$InstiNames}</td>
            <td>{$InsertDate}</td>
            <td>{$statusindi}</td>
            <td class='item-center' {$hiddenStatus}>
                <div class='actions'>
					<a onclick='update(this.id)' id='{$LesId} {$type}'><i class='bi bi-pencil-square text-green'></i></a>
                    <i class='d-none' id='clickShowModel' data-bs-toggle='modal' data-bs-target='#mainModal'></i>
                    {$actionbtn}
					<a onclick='viweMore({$LesId},`{$type}`)'><i class='bi bi-list text-green'></i></a>
				</div>
                <!-- <span class='badge shade-blue min-90'>Processing</span> -->
            </td>
        </tr>";
    }
    $htmlAllContent = $tableHeaderContent . $tableBodyContent . $tableFooter;
    echo $htmlAllContent;
}

if (isset($_POST['AddLessonData'])) {
    try {
        $lestype = $_POST['lestype'];
        $expdate = $_POST['expdate'];
        $lesdict = $_POST['lesdict'];
        $week = $_POST['week'];

        $lesName = ($lestype != 'quiz') ? $_POST['lesname'] : null;
        $leslink = ($_POST['AddLessonData'] == 1) ? $_POST['leslink'] : null;
        $quiz  = ($lestype == 'quiz') ? $_POST['quiz'] : null;


        $LesLink = ($lestype == 'video') ?  $LesLink = get_youtube_video_id($leslink) : (($lestype == 'note') ? $LesLink = get_google_drive__id($leslink) : null);
        $access = explode(",", $_POST['accesslist']);
        $group = explode(",", $_POST['grouplist']);
        $groupNew = "";
        $classNew = "";
        foreach ($group as $value) {
            $sql = "SELECT GId FROM grouplist WHERE MGName =  ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $value);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $groupNew .= "[{$row['GId']}]";
            }
            $stmt->close();
        }

        foreach ($access as $value) {
            $classdata = explode("-", $value);
            $sql = "SELECT ClassId FROM class WHERE `ClassName` =  ? and `InstiName` = ? and `year` = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $classdata[1], $classdata[2], $classdata[0]);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $classNew .= "[{$row['ClassId']}]";
            }
            $stmt->close();
        }
        // $group = json_encode($groupNew);
        // $access = json_encode($classNew);

        $expdate = ($expdate == '' || $expdate == null || $expdate) ? null : str_replace("-", "", $expdate);
        $lesdict = ($lesdict == '' || $lesdict == null) ? null : $lesdict;

        $today = GetToday('ymd');
        $thisMonth = GetToday("ym");
        $status = "active";

        if ($_POST['AddLessonData'] == 1) {
            $sql = "SELECT * FROM lesson WHERE Link = ? and Type = 'video' ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $LesLink);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0) {
                $respons = "Alredy add";
            } else {
                try {
                    $conn->begin_transaction();

                    $sql1 = "INSERT INTO lesson(LesName,Dict,Link,Type,Status) VALUE(?,?,?,?,?)";
                    $stmt = $conn->prepare($sql1);
                    $stmt->bind_param("sssss", $lesName, $lesdict, $LesLink, $lestype, $status);
                    $stmt->execute();
                    $inserted_id = $stmt->insert_id;

                    $sql2 = "INSERT INTO recaccess(LesId,ClassId,GId,Month,week,ExpDate,Status) VALUE(?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($sql2);
                    $stmt->bind_param("sssssss", $inserted_id, $classNew, $groupNew, $thisMonth, $week, $expdate, $status);
                    $stmt->execute();

                    $conn->commit();

                    $respons = "successfull";
                } catch (Exception $e) {
                    $respons = "error";
                    $conn->rollback();
                }
            }
        } elseif ($_POST['AddLessonData'] == 2) {
            if ($lestype == 'quiz') {
                // gatting lesson id 
                $sql = "SELECT * FROM lesson WHERE LesId = ? and Type = 'quiz'";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $quiz);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    // insert or update resaccess
                    try {

                        $sql2 = "INSERT INTO recaccess(LesId,ClassId,GId,Month,week,InsDate,ExpDate,Status) VALUE(?,?,?,?,?,?,?,?)";
                        $stmt = $conn->prepare($sql2);
                        $stmt->bind_param("ssssssss", $quiz, $classNew, $groupNew, $thisMonth, $week, $today, $expdate, $status);
                        $respons = $stmt->execute() ? "successfull" : "error";
                    } catch (Exception $e) {
                        $respons = "error";
                    }
                } else {
                    $respons = "undefind";
                }
            } elseif ($lestype != 'quiz') {
                $sql = "SELECT * FROM lesson WHERE LesName = ? and Type = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $lesName, $lestype);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0) {
                    $respons = "Alredy add";
                } else {
                    try {
                        $conn->begin_transaction();
                        $sql1 = "INSERT INTO lesson(LesName,Dict,Link,Type,InsertDate,Status) VALUE(?,?,?,?,?,?)";
                        $stmt = $conn->prepare($sql1);
                        $stmt->bind_param("ssssss", $lesName, $lesdict, $LesLink, $lestype, $today, $status);
                        $stmt->execute();
                        $inserted_id = $stmt->insert_id;

                        $sql2 = "INSERT INTO recaccess(LesId,ClassId,GId,Month,week,InsDate,ExpDate,Status) VALUE(?,?,?,?,?,?,?,?)";
                        $stmt = $conn->prepare($sql2);
                        $stmt->bind_param("ssssssss", $inserted_id, $classNew, $groupNew, $thisMonth, $week, $today, $expdate, $status);
                        $stmt->execute();
                        $conn->commit();

                        $respons = "successfull";
                    } catch (Exception $e) {
                        $respons = "error";
                        $conn->rollback();
                    }
                }
            }
        } else {
            $respons = "error";
        }
    } catch (Exception $e) {
        $respons = 'error';
    }
    echo $respons;
    // $stmt->close();
}

if (isset($_POST['lessonUpdateAlert'])) {
    try {
        $LesId = $_POST['data1'];
        $type = $_POST['data2'];
        if ($type = 'video' || $type = 'note') {
            $sql = "SELECT * FROM lesson,recaccess WHERE lesson.LesId = ? and lesson.LesId = recaccess.LesId ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $LesId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $LesName = $row['LesName'];
                $Dict = $row['Dict'];
                $Type = $row['Type'];
                $Link = $row['Link'];
                $week = empty($row['week']) ? "Week Not Definnd" : $row['week'];

                $accessNew = "";
                $groupNew = "";
                $access = explode("][", substr($row['ClassId'], 1, -1));
                $group = explode("][", substr($row['GId'], 1, -1));
                foreach ($access as $value) {
                    $sql = "SELECT * FROM class WHERE ClassId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                        $accessNew .= "<p>" . $row1['year'] . " " . $row1['ClassName'] . " " . $row1['InstiName'] . "</p>";
                    }
                }
                foreach ($group as $value) {
                    $sql = "SELECT MGName FROM grouplist WHERE GId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                        $groupNew .= "<p>" . $row1['MGName'] . "</p>";
                    }
                }
                $tableData = "
                <div class='col-12'>
                    <div class='mb-3'>
                        <div class='table-responsive'>
                            <table class='table table-bordered m-0'>
                                <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Desctiption </th>
                                        <th>Week</th>
                                        <th>Type</th>
                                        <th>Access Class</th>
                                        <th>Show Groups</th>
                                        <th>Month</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{$row['LesId']}</td>
                                        <td>{$LesName}</td>
                                        <td>{$Dict}</td>
                                        <td>{$week}</td>
                                        <td>{$Type}</td>
                                        <td>$accessNew</td>
                                        <td>{$groupNew}</td>
                                        <td>{$row['Month']}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>";

                $htmlHeader = "
                <div class='modal-header'>
                    <h5 class='modal-title' id='updateLessonLabel'>Update Lessson <span class='ModelTitle'></span></h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                </div>
                <div class='modal-body'>
                    <div class='row'>
                        <div class='col-sm-12 col-12 add-lesson'>
                            <!-- Card start -->
                            <div class='card'>
                                <div class='card-body'>
                                    <form id='addlesson'>
                                        <!-- Row start -->
                                        <div class='row'>
                ";
                $htmlcontentForm = "
                <form id='Formclear'>
                    <div class='col-xl-4 col-sm-6 col-12'>
                        <div class='mb-3 AddLesSub'>
                            <label for='inputName' class='form-label'>Lesson Name *</label>
                            <input name='lesname' type='text' class='form-control' id='inputName' placeholder='Enter Lesson Name' value='{$LesName}'>
                        </div>
                    </div>
                    <div class='col-xl-4 col-sm-6 col-12'>
                        <div class='mb-3 AddLesSub'>
                            <label for='inputIndustryType' class='form-label'>Select Lesson Type *</label>
                            <select name='lestype' class='form-select' id='inputIndustryType'>
                                <option value=''>Select Type</option>
                                <option value='video'" . ($Type == 'video' ? 'selected' : '') . ">Video</option>
                                <option value='note'" . ($Type == 'note' ? 'selected' : '') . ">Note</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-xl-4 col-sm-6 col-12'>
                        <div class='mb-3 AddLesSub'>
                            <label for='inputEmail' class='form-label'>Lesson Link *</label>
                            <input name='leslink' type='text' class='form-control' id='inputEmail' placeholder='Enter Lesson Link' value='{$Link}'>
                        </div>
                    </div>
                    <div class='col-xl-4 col-sm-12 col-12'>
                        <div class='mb-3 AddLesSub'>
                            <label for='inputweek' class='form-label'>Week of lesson *</label>
                            <select name='week' class='form-select' id='inputweek'>
                                <option value='' " . (empty($week) ? "selected" : null) . ">Select the Week</option>
                                <option value='First week' " . ($week == "First week" ? "selected" : null) . ">First week</option>
                                <option value='Second week' " . ($week == "Second week" ? "selected" : null) . ">Second week</option>
                                <option value='Third Week' " . ($week == "Third Week" ? "selected" : null) . ">Third Week</option>
                                <option value='Fourth week' " . ($week == "Fourth week" ? "selected" : null) . ">Fourth week</option>
                                <option value='Fifth week' " . ($week == "Fifth week" ? "selected" : null) . ">Fifth week</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-xl-4 col-sm-12 col-12'>
                        <div class='mb-3 tags group3'>
                            <label class='form-label d-flex'>Select show in group</label>
                            <select id='group3' class='select-multiple js-states form-control' title='Select Product Category' multiple='multiple'>
                                <option>111111111111111111111111111111111111111111111111</option>
                            </select>
                            <div class='invalid-feedback'>Please Select the Group</div>
                            <div class='valid-feedback'>Done!</div>
                        </div>
                    </div>
                    <div class='col-xl-4 col-sm-12 col-12'>
                        <div class='mb-3 tags access3'>
                            <label class='form-label d-flex'>Select access class</label>
                            <select id='access3' class='select-multiple js-states form-control' title='Select Product Category' multiple='multiple'>
                                <option>111111111111111111111111111111111111111111111111111111</option>
                            </select>
                            <div class='invalid-feedback'>Please Select the access</div>
                            <div class='valid-feedback'>Done!</div>
                        </div>
                    </div>
                    <div class='col-12'>
                        <div class='mb-3 AddLesSub'>
                            <label for='inputMessage' class='form-label'>Desctiption ( optional )</label>
                            <textarea name='lesdict' class='form-control' id='inputMessage' placeholder='Enter Desctiption' rows='3' value='{$Dict}'></textarea>
                        </div>
                    </div>
                </form>";
                $htmlFooter  = "
                                        </div>
                                        <!-- Row end -->
                                        <!-- Form actions footer start -->
                                        <!-- <div class='form-actions-footer'> -->
                                            <!-- <button id='cansal' class='btn btn-light'>Cancel</button> -->
                                            <!-- <button class='btn btn-success' onclick='submitAddLesson()'>Submit</button> -->
                                        <!-- </div> -->
                                        <!-- Form actions footer end -->
                                    </form>
                                </div>
                            </div>
                            <!-- Card end -->
                        </div>
                    </div>
                </div>
                <div class='modal-footer'>
                    <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
                    <button type='button' class='btn btn-success' onclick='updateLessonData({$LesId} , `{$type}`)'>Save changes</button>
                </div>
                <div class='my-3 rusaltLog mx-3'>
                    <div class='valid-feedback alert alert-success text-center alert-dismissible fade show py-2'>Successfull add the lesson!</div>
                    <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2'>Failed add the lesson</div>
                </div>
                ";
                $htmlContent = $htmlHeader . $tableData . $htmlcontentForm . $htmlFooter;
            } else {
                $htmlContent = "<div class='modal-header'>This Lesson Not Found Access</div>";
            }
        }
    } catch (Exception $e) {
        $htmlContent = "undefined";
    }
    echo $htmlContent;
}

if (isset($_POST['updateLessonData'])) {
    try {
        $lestype = $_POST['lestype'];
        if ($lestype == "video" || $lestype == "note") {
            $lessonId = $_POST['val1'];
            $lestype = $_POST['lestype'];
            $lesdict = $_POST['lesdict'];
            $week = $_POST['week'];
            $lesName = $_POST['lesname'];
            $leslink = $_POST['leslink'];
            $groupNew = "";
            $classNew = "";

            $LesLink = ($lestype == 'video') ?  $LesLink = get_youtube_video_id($leslink) : (($lestype == 'note') ? $LesLink = get_google_drive__id($leslink) : null);

            if (isset($_POST['grouplist'])) {
                foreach ($_POST['grouplist'] as $value) {
                    $sql = "SELECT GId FROM grouplist WHERE MGName =  ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("s", $value);
                    $stmt->execute();
                    $reusalt = $stmt->get_result();
                    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                        $groupNew .= "[{$row['GId']}]";
                    }
                    $stmt->close();
                }
            }

            if (isset($_POST['accesslist'])) {
                foreach ($_POST['accesslist'] as $value) {
                    $classdata = explode("-", $value);
                    $sql = "SELECT ClassId FROM class WHERE `ClassName` =  ? and `InstiName` = ? and `year` = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $classdata[1], $classdata[2], $classdata[0]);
                    $stmt->execute();
                    $reusalt = $stmt->get_result();
                    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                        $classNew .= "[{$row['ClassId']}]";
                    }
                    $stmt->close();
                }
            }

            if (true) {
                $sql = "SELECT rec.ClassId,rec.GId,les.Dict FROM recaccess rec , lesson les WHERE rec.LesId = ? and les.LesId = rec.LesId";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $lessonId);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $classNew = !isset($_POST['accesslist']) ? $row['ClassId'] : $classNew;
                    $groupNew = !isset($_POST['grouplist']) ? $row['GId'] : $groupNew;
                    $lesdict = empty($lesdict) ? $row['Dict'] : $lesdict;
                }
            }

            $sql = "SELECT * FROM lesson WHERE Link = ? and Type = ? and LesId != ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $LesLink, $lestype, $lessonId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0) {
                $respons = "Alredy add";
            } else {
                try {
                    $conn->begin_transaction();
                    $sql1 = "UPDATE lesson SET LesName = ? , Dict = ? , Link = ? , Type = ? WHERE LesId = ?";
                    $stmt = $conn->prepare($sql1);
                    $stmt->bind_param("ssssi", $lesName, $lesdict, $LesLink, $lestype, $lessonId);
                    $stmt->execute();

                    $sql2 = "UPDATE recaccess SET ClassId = ? , GId = ? , week = ? WHERE LesId = ?";
                    $stmt = $conn->prepare($sql2);
                    $stmt->bind_param("sssi", $classNew, $groupNew, $week, $lessonId);
                    $stmt->execute();
                    $conn->commit();

                    $respons = "successfull";
                } catch (Exception $e) {
                    $respons = "error" . $e;
                    $conn->rollback();
                }
            }
        } else {
            $respons = "Undefing input";
        }
    } catch (Exception $e) {
        $respons = "error";
    }
    echo $respons;
}

if (isset($_POST['lesAction'])) {
    try {
        $lesId = $_POST['val1'];
        $lesAction = $_POST['lesAction'] == 'active' ? 'active' : 'desable';

        $sql1 = "UPDATE lesson SET Status = ? WHERE LesId = ?";
        $stmt = $conn->prepare($sql1);
        $stmt->bind_param("si", $lesAction, $lesId);
        $stmt->execute();
        $respons = "success" . $lesId;
    } catch (Exception $e) {
        $respons = "error";
    }
    echo $respons;
}

// add quiz section start
if (isset($_POST['AddQuiz'])) {
    $qName = $_POST['qName'];
    $qTime = $_POST['qTime'];
    $qDict = $_POST['qDict'];
    // $StrQction = json_decode($_POST['Quctions'] , true);
    $Quctions = json_decode($_POST['quctions'], true);
    $LesType = "quiz";
    $Today = GetToday('ymd');
    $status = "active";

    if ($qDict == "" || $qDict == null) {
        $qDict = null;
    }
    // echo $Quctions;
    // foreach($Quctions as $key => $value) {
    // }

    $sql = "SELECT * FROM lesson WHERE LesName = ? and Type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $qName, $LesType);
    $stmt->execute();
    $reusalt = $stmt->get_result();
    if (!$reusalt->num_rows > 0) {
        try {
            $conn->begin_transaction();
            $sql = "INSERT INTO lesson(LesName,Dict,Time,Type,InsertDate,Status) value(?,?,?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $qName, $qDict, $qTime, $LesType, $Today, $status);
            $stmt->execute();
            $inserted_id = $stmt->insert_id;

            foreach ($Quctions as $key => $value) {
                $numb = $Quctions[$key]['numb'];
                $quction = $Quctions[$key]['quction'];
                $dict = $Quctions[$key]['dict'];
                $ans = $Quctions[$key]['ans'];
                $opt1 = $Quctions[$key]['option'][0];
                $opt2 = $Quctions[$key]['option'][1];
                $opt3 = $Quctions[$key]['option'][2];
                $opt4 = $Quctions[$key]['option'][3];

                $sql = "INSERT INTO quction(LesId,Number,quction,Ans,Opt1,Opt2,Opt3,Opt4,Dict,InsDate,Status) value(?,?,?,?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssssssss", $inserted_id, $numb, $quction, $ans, $opt1, $opt2, $opt3, $opt4, $dict, $Today, $status);
                $stmt->execute();
            }
            $conn->commit();
            echo "success";
        } catch (Exception $e) {
            $conn->rollback();
            echo "error" . $e;
        }
    } else {
        echo "alredy added";
    }
}
// add quiz section end

// lesson management sestion end **************


// peaper management sestion start **************
if (isset($_POST['peaperManage'])) {
    $type = $_POST['type'];
    // if ($type == "selectActivePeaper") { 
    // $PeaperName = $_POST['data'];
    // $_SESSION['activePeaper'] = $PeaperName;
    // $respons = "success";
    // } else
    // if ($type == "updateSelectPeaper") {
    //     try {
    //         $respons = "";
    //         $respons .= "<option value=''>Select The Peaper1</option>";
    //         $sql = "SELECT peaperName FROM peaper WHERE Status = 'active' ";
    //         $stmt = $conn->prepare($sql);
    //         $stmt->execute();
    //         $reusalt = $stmt->get_result();
    //         $stmt->close();
    //         while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
    //             $respons .= "<option value='{$row['peaperName']}'" . (isset($_SESSION['activePeaper']) ? ($_SESSION['activePeaper'] == $row['peaperName'] ? "selected" : null) : null) . ">{$row['peaperName']}</option>";
    //         }
    //         $respons = ($reusalt->num_rows > 0) ? $respons : "<option value=''>Peaper Not Found</option>";
    //     } catch (Exception $th) {
    //         $respons = "error";
    //     }
    // } else
    if ($type === "addPeaperData") {
        try {
            $PeaperType = $_POST['PeaperType'];
            $peaperName = $_POST['peaperName'];
            $dict = empty($_POST['dict']) ? null : $_POST['dict'];
            // $group = $_POST['group'];
            // $class = $_POST['class'];

            $access = explode(",", $_POST['class']);
            // $group = explode(",", $_POST['group']);
            // $groupNew = "";
            $classNew = "";
            $groupSql = "";
            $classSql = "";

            // $i = 1;
            // foreach ($group as $value) {
            //     $sql = "SELECT GId FROM grouplist WHERE MGName =  ? ";
            //     $stmt = $conn->prepare($sql);
            //     $stmt->bind_param("s", $value);
            //     $stmt->execute();
            //     $reusalt = $stmt->get_result();
            //     $stmt->close();
            //     if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            //         $groupSql .= $i == 1 ? " AND ( " : "";
            //         $groupSql .= " GId LIKE '%[{$row['GId']}]%' ";
            //         $groupSql .= $i < sizeof($group) ? " OR " : " ) ";
            //         $groupNew .= "[{$row['GId']}]";
            //     }
            //     $i++;
            // }

            $i = 1;
            foreach ($access as $value) {
                $classdata = explode("-", $value);
                $sql = "SELECT ClassId FROM class WHERE `ClassName` =  ? and `InstiName` = ? and `year` = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $classdata[1], $classdata[2], $classdata[0]);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $classSql .= $i == 1 ? " AND ( " : "";
                    $classSql .= " ClassId LIKE '%[{$row['ClassId']}]%' ";
                    $classSql .= $i < sizeof($access) ? " OR " : " ) ";
                    $classNew .= "[{$row['ClassId']}]";
                }
                $i++;
            }

            $conn->begin_transaction();
            $sql = "SELECT PeaperId FROM peaper WHERE peaperName = ? and type = ? " . $classSql . $groupSql;
            // echo $sql;
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $peaperName, $PeaperType);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if (!$reusalt->num_rows > 0) {

                $sql = "INSERT INTO peaper(peaperName,type,ClassId,Dict) VALUES(?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $peaperName, $PeaperType, $classNew, $dict);
                $stmt->execute();

                $conn->commit();
                $respons = "success";
            } else {
                $respons = "Alredy Added";
            }
            // $respons = "success";
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type === 'addNewStudent') {
        try {
            $CousId = $_POST['CousId'];
            $InstiId = $_POST['InstiId'];
            $StName = $_POST['StName'];
            $StAddress = $_POST['StAddress'];
            $StSchool = $_POST['StSchool'];
            $StInsti = $_POST['StInsti'];
            $StYear = $_POST['StYear'];
            $StMobNumber = $_POST['StMobNumber'];
            $StWhaNumber = $_POST['StWhaNumber'];


            $sql = "SELECT * FROM unreguser WHERE CousId = ? and InstiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $CousId, $InstiId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $respons = "Alredy Added";
            } else {

                try {
                    $sql = "INSERT INTO unreguser(CousId,InstiId,Name,Address,School,InstiName,Year,MOBNum,WhaNum) VALUES (?,?,?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssssssss", $CousId, $InstiId, $StName, $StAddress, $StSchool, $StInsti, $StYear, $StMobNumber, $StWhaNumber);
                    $stmt->execute();
                    $reusalt = $stmt->get_result();
                    $respons = "success";
                } catch (\Throwable $th) {
                    $respons = 'error';
                }
            }
        } catch (\Throwable $th) {
            $respons = 'error';
        }
    } else {
        $respons = "not Mtch";
    }
    echo $respons;
}

if (isset($_POST['loadModelDataPeaper'])) {
    $type = $_POST['type'];
    // if ($type == 'uploadMarks' || $type == 'uploadMarkNotReg' || $type == 'loadModelDataPeaperTable') {
    if ($type == 'uploadMarks' || $type == 'loadModelDataPeaperTable') {
        $modelFooter = "
        <div class='modal-footer pt-3'>
            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
        </div>";
    } else {
        $modelFooter = "
        <div class='modal-footer pt-3'>
            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
            <button type='button' class='btn btn-success' onclick='submitModelPeaper(`{$type}`)'>Finish</button>
        </div>";
    }
    if ($type == 'addPeaper') {
        $PeaperList = "";
        $PeaperList .= "<option value = ''>Select The Lesson</option>";
        $sql = "SELECT LesName FROM lesson WHERE Type = 'upload' and Status != 'disable'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $conn->close();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $PeaperList .= "<option value='{$row['LesName']}'>{$row['LesName']}</option>";
        }
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Add Peaper</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $modelBody = "
        <form class='addpeaper' id='Formclear'>
            <!-- Row start -->
            <div class='row'>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='PeaperType' class='form-label'>Select the peaper Type *</label>
                        <Select name='PeaperType' id='PeaperType' class='form-select  physical online' onchange='peaperType(this.value)'>
                            <option value='' selected>Select The Peaper Type</option>
                            <option value='online'>Only Online Peaper</option>
                            <option value='physical'>Only Physical Peaper</option>
                            <option value='both'>Physical and Online Peaper</option>
                        </Select>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='peaperName' class='form-label'>Select the peaper Name *</label>
                        <Select name='peaperName' id='peaperName' class='form-select online' disabled>
                            {$PeaperList}
                        </Select>
                        <input name='peaperName' type='text' class='form-control physical' id='peaperName' placeholder='Enter Peaper Name' hidden>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3 tags w-100 class'>
                        <label class='form-label d-flex'>Select The Class *</label>
                        <select name='class' id='class' class='is-valid select-multiple js-states form-control w-100' title='Select the class *' multiple='multiple' disabled>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>
                <!--<div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3 tags w-100 group'>
                        <label class='form-label d-flex'>Select The Group*</label>
                        <select name='group' id='group' class='is-valid select-multiple js-states form-control w-100' title='Select Group *' multiple='multiple' disabled>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>-->

                <div class='col-12'>
                    <div class='mb-3'>
                        <label for='inputMessage' class='form-label'>Description</label>
                        <textarea name='dict' class='form-control online physical' id='inputMessage' placeholder='Write Description' rows='3' disabled></textarea>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the peaper!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the peaper</div>
        </div>";
        $modelContent = $modelHead . $modelBody . $modelFooter;
    } elseif ($type == 'uploadMarks') {
        $sql = "SELECT * FROM peaper WHERE Status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusaltMain = $stmt->get_result();
        $stmt->close();
        if ($reusaltMain->num_rows > 0 && $rowMain = $reusaltMain->fetch_assoc()) {
            $modelHead = "
            <div class='modal-header'>
                <h5 class='modal-title' id='modelMainLabel'>Add Peaper - ( {$rowMain['peaperName']} )</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body bg-light'>";
            $modelBody = "
            <!-- Row start -->
            <div class='row'>
                <div class='col-xl-12 col-sm-12 col-12'>
                    <div class='card'>
                        <div class='card-header'>
                            <div class='card-title'></div>
                            <div class='search-container'>
                                <div class='input-group'>" . ($type == 'uploadMarks' ?
                "<input type='text' class='form-control searchInp' style='background-color: #dae0e9;' placeholder='Search anything' onkeyup='updateModelContent(`uploadMarksTableData`,this.value)'>"
                :
                "<input type='text' class='form-control searchInp' style='background-color: #dae0e9;' placeholder='Search anything' onkeyup='updateModelContent(`addNewStudentTableData`,this.value)'>"
            ) . "
                                    <button class='btn' type='button'>
                                        <i class='bi bi-search'></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class='card-body' id='model-table-content-change'><!-- change table content-->
                            <center><img src='assets/img/gif/loding.gif' width='200' alt='' srcset=''></center>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Row end -->
            <div class='my-3 rusaltLog mx-3'>
                <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the peaper!</div>
                <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the peaper</div>
            </div>";
            $modelContent = $modelHead . $modelBody . $modelFooter;
        } else {
            $modelContent = 'not active peaper';
        }
    } elseif ($type == 'addNewStudent') {
        $modelHead = "
            <div class='modal-header'>
                <h5 class='modal-title' id='modelMainLabel'>Add new student</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body bg-light'>";
        $modelBody = "
            <!-- Row start -->
            <div class='row'>
              <form id='Formclear'>
                <div class='col-xl-12 col-sm-12 col-12'>
                    <div class='card'>
                        <div class='card-body' id='model-table-content-change'><!-- change table content-->
                            <div class='row'>
                                <div class='col-xl-6 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='CousId' type='text' class='form-control AddStu' id='' placeholder='Enter Coustom Id'>
                                    </div>
                                </div>
                                <div class='col-xl-6 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='InstiId' type='text' class='form-control AddStu' id='' placeholder='Enter Institute Id'>
                                    </div>
                                </div>
                                <div class='col-xl-12 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='StName' type='text' class='form-control AddStu' id='' placeholder='Enter Student name'>
                                    </div>
                                </div>
                                <div class='col-xl-12 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='StAddress' type='text' class='form-control AddStu' id='' placeholder='Enter address'>
                                    </div>
                                </div>
                                <div class='col-xl-12 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='StSchool' type='text' class='form-control AddStu' id='' placeholder='Enter school name'>
                                    </div>
                                </div>
                                <div class='col-xl-6 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <select class='form-select AddStu' name='StInsti' id='StInsti'>
                                            <option value=''>Select institute name</option>
                                            <option value='Susipwan'>Susipwan</option>
                                            <option value='Sasip'>Sasip</option>
                                            <option value='Ziyowin'>Ziyowin</option>
                                            <option value='Wins'>Wins</option>
                                            <option value='Online'>Online</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-xl-6 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <select class='form-select AddStu' name='StYear' id='StYear'>
                                            <option value=''>Select year</option>
                                            <option value='2024'>2024</option>
                                            <option value='2025'>2025</option>
                                            <option value='2026'>2026</option>
                                            <option value='2027'>2027</option>
                                            <option value='2028'>2028</option>
                                        </select>
                                    </div>
                                </div>
                                <div class='col-xl-6 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='StMobNumber' type='text' class='form-control AddStu' id='' placeholder='Enter mobile number'>
                                    </div>
                                </div>
                                <div class='col-xl-6 col-sm-12 col-12'>
                                    <div class='mb-3'>
                                        <input name='StWhaNumber' type='text' class='form-control AddStu' id='' placeholder='Enter whatsapp number'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
              </form>
            </div>
            <!-- Row end -->
            <div class='my-3 rusaltLog mx-3'>
                <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the peaper!</div>
                <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the peaper</div>
            </div>";
        $modelContent = $modelHead . $modelBody . $modelFooter;
    } elseif ($type == 'loadModelDataPeaperTable') {
        $sql = "SELECT * FROM peaper WHERE Status = 'active'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusaltMain = $stmt->get_result();
        $stmt->close();
        if ($reusaltMain->num_rows > 0 && $rowMain = $reusaltMain->fetch_assoc()) {
            $PeaperId = $rowMain['PeaperId'];
            $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
            $tFirst = "
            <div class='table-responsive'>
                <table class='table table-bordered m-0'>
                    <thead>";
            $tMiddle = "
            </thead>
            <tbody>";
            $tEnd = "
                    </tbody>
                </table>
            </div>";
            $tHead = "
            <tr>
                <th>Student Name</th>
                <th>Instute</th>
                <th>Exam Id</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>";
            $tBody = "";
            $sql_with_data = "SELECT * FROM unreguser WHERE Name LIKE ? or CousId LIKE ? GROUP BY InsertDate,Year,InstiName LIMIT 5";
            $sql_without_data = "SELECT * FROM unreguser GROUP BY Name,Year,InstiName LIMIT 5 ";

            if ($data != null) {
                $stmt = $conn->prepare($sql_with_data);
                $stmt->bind_param("ss", $data, $data);
            } else {
                $stmt = $conn->prepare($sql_without_data);
            }
            $stmt->execute();
            $reusalt = $stmt->get_result();
            while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $URGId = $row['URGId'];
                $marks = '';
                if (true) { // get marks
                    $sql = "SELECT Marks FROM marksofpeaper WHERE URGId = ? and PeaperId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $URGId, $PeaperId);
                    $stmt->execute();
                    $reusaltMarks = $stmt->get_result();
                    $stmt->close();
                    if ($reusaltMarks->num_rows > 0 && $rowMarks = $reusaltMarks->fetch_assoc()) {
                        $marks = $rowMarks['Marks'];
                    }
                }
                $tBody .= "
                <tr>
                    <td>{$row['Name']}</td>
                    <td>{$row['InstiName']} / {$row['Year']}</td>
                    <td>{$row['CousId']}</td>
                    <td><input type='number' name='marks' class='form-input w-100' placeholder='Enter Marks' id='{$row['URGId']}{$rowMain['PeaperId']}' value='{$marks}'></input></td>
                    <td> 
                        <div class='actions item-center'>
                            <a onclick='update(this.id,`addmarks`)' id='{$row['URGId']} {$rowMain['PeaperId']}'><i class='bi bi-arrow-repeat text-green fs-6'></i></a>
                        </div>
                    </td>
                </tr>";
            }
            if (!$reusalt->num_rows > 0) {
                $tBody = "<tr><td colspan='5' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
            }
        }
        $modelContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } elseif ($type == 'loadModelDataPeaperNotRegTable') { // search not register student
        if (false) {
            $sql = "SELECT * FROM peaper WHERE Status = 'active'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusaltMain = $stmt->get_result();
            $stmt->close();
            if ($reusaltMain->num_rows > 0 && $rowMain = $reusaltMain->fetch_assoc()) {
                $PeaperId = $rowMain['PeaperId'];
                $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
                $tFirst = "
            <div class='table-responsive'>
                <table class='table table-bordered m-0'>
                    <thead>";
                $tMiddle = "
            </thead>
            <tbody>";
                $tEnd = "
                    </tbody>
                </table>
            </div>";
                $tHead = "
            <tr>
                <th>Student Name</th>
                <th>Instute</th>
                <th>Exam Id</th>
                <th>Marks</th>
                <th>Action</th>
            </tr>";
                $tBody = "";
                // $sql_with_data = "SELECT * FROM unreguser WHERE Name LIKE ? GROUP BY Name,Year,InstiName LIMIT 5";
                // $sql_without_data = "SELECT * FROM unreguser GROUP BY Name,Year,InstiName LIMIT 5 ";

                // if ($data != null) {
                //     $stmt = $conn->prepare($sql_with_data);
                //     $stmt->bind_param("s", $data);
                // } else {
                //     $stmt = $conn->prepare($sql_without_data);
                // }
                // $stmt->execute();
                // $reusalt = $stmt->get_result();
                // $stmt->close();
                // while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                //     $URGId = $row['URGId'];
                //     $Marks = '';
                //     if (true) { // get marks
                //         $sql = "SELECT Marks FROM marksofpeaper WHERE URGId = ? and PeaperId = ?";
                //         $stmt = $conn->prepare($sql);
                //         $stmt->bind_param("ss", $URGId, $PeaperId);
                //         $stmt->execute();
                //         $reusaltMarks = $stmt->get_result();
                //         $stmt->close();
                //         if ($reusaltMarks->num_rows > 0 && $rowMarks = $reusaltMarks->fetch_assoc()) {
                //             $Marks =  $rowMarks['Marks'];
                //         }
                //     }
                //     $tBody .= "
                //     <tr>
                //         <td>{$row['Name']}</td>
                //         <td>{$row['InstiName']}</td>
                //         <td>{$row['Year']}</td>
                //         <td><input type='number' name='marks' class='form-input w-100' placeholder='Enter Marks' id='_{$row['URGId']}{$PeaperId}' value='{$Marks}'></input></td>
                //         <td> 
                //             <div class='actions item-center'>
                //                 <a onclick='update(this.id,`addmarksNotReg`)' id='{$row['URGId']} {$PeaperId}'><i class='bi bi-arrow-repeat text-green fs-6'></i></a>
                //             </div>
                //         </td>
                //     </tr>";
                // }
                if (!$reusalt->num_rows > 0) {
                    $InstiList = '';
                    $InstiList .= "<option value=''>Select the Institute</option>";
                    if (true) { // get marks
                        $sql = "SELECT InstiName FROM insti";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $reusaltInsti = $stmt->get_result();
                        $stmt->close();
                        while ($reusaltInsti->num_rows > 0 && $rowInsti = $reusaltInsti->fetch_assoc()) {
                            $InstiList .=  "<option value='{$rowInsti['InstiName']}' >{$rowInsti['InstiName']}</option>";
                        }
                    }
                    $tBody .= "<tr><td colspan='5' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
                    $tBody .= "
                <tr>
                <form id='Formclear'>
                    <td><input type='text' name='name' class='form-control w-100' placeholder='Enter Name' id='addNewStu' value=''></input></td>
                    <td><select class='form-select' name='insti' id='addNewStu'>{$InstiList}</select></td>
                    <td>
                        <select class='form-select' name='year' id='addNewStu'>
                            <option value=''>Select year</option>
                            <option value='2024'>2024</option>
                            <option value='2025'>2025</option>
                            <option value='2026'>2026</option>
                            <option value='2027'>2027</option>
                            <option value='2028'>2028</option>
                        </select>
                    </td>
                    <td><input type='number' name='marks' class='form-control w-100' placeholder='Enter Marks' id='addNewStu'></input></td>
                    <td> 
                        <div class='actions item-center'>
                            <a onclick='update(this.id,`addmarksNotReg`)' id='addNewStu {$PeaperId}'><i class='bi bi-arrow-repeat text-green fs-6'></i></a>
                        </div>
                    </td>
                </form>
                </tr>";
                }
            }
            $modelContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
            // WriteFile($modelContent);

        }

        if (true) {
            $PeaperList = "";
            $PeaperList .= "<option value = ''>Select The Lesson</option>";
            $sql = "SELECT LesName FROM lesson WHERE Type = 'upload' and Status != 'disable'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $conn->close();
            while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $PeaperList .= "<option value='{$row['LesName']}'>{$row['LesName']}</option>";
            }
            $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'></h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
            $modelBody = "
        <form class='addpeaper' id='Formclear'>
            <!-- Row start -->
            <div class='row'>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='' class='form-label'>Enter Coustom Id *</label>
                        <input name='' type='text' class='form-control physical' id='' placeholder='Enter Coustom Id'>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='peaperName' class='form-label'>Select the peaper Name *</label>
                        <Select name='peaperName' id='peaperName' class='form-select online' disabled>
                            {$PeaperList}
                        </Select>
                        <input name='peaperName' type='text' class='form-control physical' id='peaperName' placeholder='Enter Peaper Name' hidden>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3 tags w-100 class'>
                        <label class='form-label d-flex'>Select The Class *</label>
                        <select name='class' id='class' class='is-valid select-multiple js-states form-control w-100' title='Select the class *' multiple='multiple' disabled>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3 tags w-100 group'>
                        <label class='form-label d-flex'>Select The Group*</label>
                        <select name='group' id='group' class='is-valid select-multiple js-states form-control w-100' title='Select Group *' multiple='multiple' disabled>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>

                <div class='col-12'>
                    <div class='mb-3'>
                        <label for='inputMessage' class='form-label'>Description</label>
                        <textarea name='dict' class='form-control online physical' id='inputMessage' placeholder='Write Description' rows='3' disabled></textarea>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the peaper!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the peaper</div>
        </div>";
            $modelContent = $modelHead . $modelBody . $modelFooter;
        }
    } elseif ($type == 'loadModelDataviewFinishedPeaper') {
        $data = $_POST['data'];

        $sql = "SELECT SUM(CASE WHEN marksofpeaper.Marks >=75 THEN 1 ELSE 0 END)AS Apass,SUM(CASE WHEN 75 > marksofpeaper.Marks AND marksofpeaper.Marks >=65 THEN 1 ELSE 0 END)AS Bpass,SUM(CASE WHEN 65 > marksofpeaper.Marks AND marksofpeaper.Marks >= 55 THEN 1 ELSE 0 END)AS Cpass,SUM(CASE WHEN 55 > marksofpeaper.Marks and marksofpeaper.Marks >=45 THEN 1 ELSE 0 END)AS Spass,SUM(CASE WHEN 45 > marksofpeaper.Marks THEN 1 ELSE 0 END)AS Fpass,COUNT(marksofpeaper.MOPId) AS doneStu,peaper.* FROM peaper,marksofpeaper WHERE peaper.peaperId = '$data' and peaper.PeaperId = marksofpeaper.PeaperId ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        $row = $reusalt->fetch_assoc();

        // access class
        if ($row['ClassId'] == !null) {
            $ClassNameList = "";
            $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
            foreach ($ClassIdList as $value) {
                $sql = "SELECT * FROM class WHERE ClassId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $value);
                $stmt->execute();
                $reusalt1 = $stmt->get_result();
                if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                    $ClassNameList .= "• {$row1['year']} {$row1['ClassName']} {$row1['InstiName']}" . "<br>";
                }
            }
        } else {
            $ClassNameList = "Not a Class";
        }

        // get group access
        // if ($row['GId'] == !null) {
        //     $groupNameList = "";
        //     $groupIdList = explode("][", substr($row['GId'], 1, -1));
        //     foreach ($groupIdList as $value) {
        //         $sql = "SELECT * FROM grouplist WHERE GId = ? ";
        //         $stmt = $conn->prepare($sql);
        //         $stmt->bind_param("i", $value);
        //         $stmt->execute();
        //         $reusalt1 = $stmt->get_result();
        //         if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
        //             $groupNameList .= "• {$row1['MGName']}" . "<br>";
        //         }
        //     }
        // } else {
        //     $groupNameList = "Not a Group";
        // }

        // totle student count 
        // if ($row['ClassId'] == !null) {
        //     $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
        //     $totleStudent = 0;
        //     foreach ($ClassIdList as $value) {
        //         $sql = "SELECT COUNT(DISTINCT UserId) AS userCount FROM payment WHERE ClassId = ? and Status = 'active'";
        //         $stmt = $conn->prepare($sql);
        //         $stmt->bind_param("i", $value);
        //         $stmt->execute();
        //         $reusalt1 = $stmt->get_result();
        //         if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
        //             $totleStudent = $row1['userCount'];
        //         }
        //     }
        // } else {
        //     $totleStudent = "Not Calculate";
        // }

        // get insti radio 
        if (true) {
            $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
            $radioList = "";
            $radioLable = "";
            $sql = "SELECT InstiName FROM insti ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt1 = $stmt->get_result();
            $i = 2;
            while ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                $radioList .= "<input type='radio' name='sub_nav_rank' id='sub_nav_rank-{$i}' value='{$row1['InstiName']}' onclick='showViewOldPeaperBody()'>";
                $radioLable .= "<label class='text-overflow' for='sub_nav_rank-{$i}'>{$row1['InstiName']} Rank</label>";
                $i++;
            }
        }

        $modelHead = "
        <!-- Row start -->
        <div class='row admin-table'>
            <div class='col-12'>
                <!-- snippit management table start -->
                <div class='card'>
                    <div class='card-header'>
                        <div class='card-title'></div>
                        <div class='search-container'>
                            <!-- Search input group start -->
                            <div class='input-group inputData2'>
                                <select id='limitData' class='form-select mx-3' onchange='showViewOldPeaperBody('',this.value)'>
                                    <option value='10'>10</option>
                                    <option value='20'>20</option>
                                    <option value='50'>50</option>
                                    <option value='all'>all</option>
                                </select>
                                <input type='text' class='form-control searchInp' style='background-color: #dae0e9;' placeholder='Search anything' onkeyup='showViewOldPeaperBody(this.value)'>
                                <button class='btn' type='button'>
                                    <i class='bi bi-search'></i>
                                </button>
                            </div>
                            <!-- Search input group end -->
                        </div>
                    </div>
                    <div class='card-body'><!-- change table content-->
                        <div class='modal-header'>
                            <h5 class='modal-title' id='modelMainLabel'>{$row['peaperName']}</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>";
        $modelFooter = "
                        </div>
                    </div>
                </div>
                <!-- snippit management table end -->
            </div>
        </div>
        <!-- Row end -->";

        $htmlContent = "
            <div class='accordion mb-3' id=''>
                <div class='accordion-item'>
                    <h2 class='accordion-header' id='headingOneLight'>
                        <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOneLight' aria-expanded='true' aria-controls='collapseOneLight'>Peaper Details</button>
                    </h2>
                    <div id='collapseOneLight' class='accordion-collapse collapse' aria-labelledby='headingOneLight' data-bs-parent='#accordionExample2'>
                        <div class='accordion-body'>
                            <strong>{$row['peaperName']}</strong>
                            <div class='row'>
                                <div class='col-5 border border-1 m-2 p-2'>
                                    <span class='text-dark'>Access Class</span><br>
                                    {$ClassNameList}</div>
                            </div>
                            <div class='row'>
                                <span class='col-auto alert alert-success m-2 p-0 px-2'>Compleated : {$row['doneStu']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>A pass : {$row['Apass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>B pass : {$row['Bpass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>C pass : {$row['Cpass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>S pass : {$row['Spass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>F pass : {$row['Fpass']}</span>
                            </div>
                            <div class='row'>
                                    <div id='oldPeaperchart' class='auto-align-graph bg-light align-center'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row item-center'>
                <div class='sub-nav'>
                    <div class='sub-nav-body' id='sub-nav-body2'>
                        <div class='radio-btn notifiradio'>
                            <input type='radio' name='sub_nav_rank' id='sub_nav_rank-1' value='iland' onclick='showViewOldPeaperBody()' checked>
                            {$radioList}
                            <div class='ul'>
                                <label class='text-overflow' for='sub_nav_rank-1'>ILand Rank</label>
                                {$radioLable}
                            </div>
                        </div
                    </div>
                </div>
            </div>
            <div id='Old_peaper_body'><center><img src='assets/img/gif/loding.gif' width='300' alt='' srcset=''></center></div>
            <input type='hidden' id='oldPeaperId' value='{$data}'>
            ";

        $modelContent = $modelHead . $htmlContent . $modelFooter;
    } else {
        $modelContent = 'invalied inputs ' . $type;
    }
    echo $modelContent;
}

if (isset($_POST['changePeaperManageTable'])) {
    $type = $_POST['changePeaperManageTable'];
    $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
    $tFirst = "
    <div class='table-responsive'>
        <table class='table table-bordered m-0'>
            <thead>";
    $tMiddle = "
    </thead>
    <tbody>";
    $tEnd = "
            </tbody>
        </table>
    </div>";
    if ($type == 'PeaperManage') {
        $tHead = "
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Discription</th>
            <th>Aprued Class</th>
            <th>Status</th>
            <th>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM peaper WHERE ( peaperName LIKE ? or type LIKE ? or ClassId LIKE ? or GId LIKE ? or Dict LIKE ? or Status LIKE ? ) AND Status != 'finished' " : "SELECT * FROM peaper WHERE Status != 'finished' ";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("ssssss", $data, $data, $data, $data, $data, $data) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $PeaperId = $row['PeaperId'];
            $action = $row['Status'] == 'active' ? "<a onclick='disable(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-x-circle text-red fs-6'></i></a>" : "<a onclick='enable(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-check-circle text-green fs-6'></i></a>";
            $actionFinished = $row['Status'] == 'active' ? "<a onclick='end(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-explicit text-info fs-6'></i></a>" : "";
            $Status = $row['Status'] == 'active' ? "<span class='text-green fs-6'><i class='bi bi-check-circle'></i></span>" : "<span class='text-red fs-6'><i class='bi bi-x-circle'></i></span>";

            if ($row['ClassId'] == !null) {
                $ClassNameList = "";
                $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
                foreach ($ClassIdList as $value) {
                    $sql = "SELECT * FROM class WHERE ClassId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                        $ClassNameList .= "{$row1['year']} {$row1['ClassName']} {$row1['InstiName']}" . "<br>";
                    }
                }
            } else {
                $ClassNameList = "Not a Class";
            }

            // if ($row['GId'] == !null) {
            //     $groupNameList = "";
            //     $groupIdList = explode("][", substr($row['GId'], 1, -1));
            //     foreach ($groupIdList as $value) {
            //         $sql = "SELECT * FROM grouplist WHERE GId = ? ";
            //         $stmt = $conn->prepare($sql);
            //         $stmt->bind_param("i", $value);
            //         $stmt->execute();
            //         $reusalt1 = $stmt->get_result();
            //         if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
            //             $groupNameList .= "{$row1['MGName']}" . "<br>";
            //         }
            //     }
            // } else {
            //     $groupNameList = "Not a Class";
            // }

            $tBody .= "
            <tr>
                <td>{$row['peaperName']}</td>
                <td>{$row['type']}</td>
                <td>" . (empty($row['Dict']) ? "Not Discription" :  $row['Dict']) . "</td>
                <td>{$ClassNameList}</td>
                <td class='text-center'>{$Status}</td>
                <td>
                    <div class='actions item-center'>
                        {$action}&nbsp;{$actionFinished}
                        &nbsp;
                        <!--<a onclick='update(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-pencil-square text-green fs-6'></i></a>-->
                    </div>
                </td>
            </tr>";
        }
        $htmlContent = ($reusalt->num_rows > 0) ?  $tFirst . $tHead . $tMiddle . $tBody . $tEnd : $tFirst . $tHead . $tMiddle . "<tr><td colspan='7'  class='text-info text-center'><i class='bi bi-search'>&nbsp;</i>Not A Peaper</td></tr>" . $tEnd;
    } elseif ($type == 'rankingManage') {
        $sql = "SELECT SUM(CASE WHEN marksofpeaper.Marks >=75 THEN 1 ELSE 0 END)AS Apass,SUM(CASE WHEN 75 > marksofpeaper.Marks and marksofpeaper.Marks >=65 THEN 1 ELSE 0 END)AS Bpass,SUM(CASE WHEN 65 > marksofpeaper.Marks and marksofpeaper.Marks >=55 THEN 1 ELSE 0 END)AS Cpass,SUM(CASE WHEN 55 > marksofpeaper.Marks and marksofpeaper.Marks >=45 THEN 1 ELSE 0 END)AS Spass,SUM(CASE WHEN 45 > marksofpeaper.Marks THEN 1 ELSE 0 END)AS Fpass,COUNT(marksofpeaper.MOPId) AS doneStu,SUM(CASE WHEN marksofpeaper.UserId IS NOT NULL THEN 1 ELSE 0 END)AS Compsite,SUM(CASE WHEN marksofpeaper.URGId IS NOT NULL THEN 1 ELSE 0 END)AS CompNotReg,peaper.* FROM peaper,marksofpeaper WHERE peaper.Status = 'active' and peaper.PeaperId = marksofpeaper.PeaperId ";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        $row = $reusalt->fetch_assoc();

        // access class
        if ($row['ClassId'] == !null) {
            $ClassNameList = "";
            $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
            foreach ($ClassIdList as $value) {
                $sql = "SELECT * FROM class WHERE ClassId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $value);
                $stmt->execute();
                $reusalt1 = $stmt->get_result();
                if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                    $ClassNameList .= "• {$row1['year']} {$row1['ClassName']} {$row1['InstiName']}" . "<br>";
                }
            }
        } else {
            $ClassNameList = "Not a Class";
        }

        // get group access
        // if ($row['GId'] == !null) {
        //     $groupNameList = "";
        //     $groupIdList = explode("][", substr($row['GId'], 1, -1));
        //     foreach ($groupIdList as $value) {
        //         $sql = "SELECT * FROM grouplist WHERE GId = ? ";
        //         $stmt = $conn->prepare($sql);
        //         $stmt->bind_param("i", $value);
        //         $stmt->execute();
        //         $reusalt1 = $stmt->get_result();
        //         if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
        //             $groupNameList .= "• {$row1['MGName']}" . "<br>";
        //         }
        //     }
        // } else {
        //     $groupNameList = "Not a Group";
        // }

        // totle student count 
        // if ($row['ClassId'] == !null) {
        //     $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
        //     $totleStudent = 0;
        //     foreach ($ClassIdList as $value) {
        //         $sql = "SELECT COUNT(DISTINCT UserId) AS userCount FROM payment WHERE ClassId = ? and Status = 'active'";
        //         $stmt = $conn->prepare($sql);
        //         $stmt->bind_param("i", $value);
        //         $stmt->execute();
        //         $reusalt1 = $stmt->get_result();
        //         if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
        //             $totleStudent = $row1['userCount'];
        //         }
        //     }
        // } else {
        //     $totleStudent = "Not Calculate";
        // }

        // get insti radio 
        if (true) {
            $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
            $radioList = "";
            $radioLable = "";
            $sql = "SELECT InstiName FROM insti ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt1 = $stmt->get_result();
            $i = 2;
            while ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                $radioList .= "<input type='radio' name='sub_nav_rank' id='sub_nav_rank-{$i}' value='{$row1['InstiName']}' onclick='ShowRankBody()'>";
                $radioLable .= "<label class='text-overflow' for='sub_nav_rank-{$i}'>{$row1['InstiName']} Rank</label>";
                $i++;
            }
        }

        $htmlContent = "
            <div class='accordion mb-3' id=''>
                <div class='accordion-item'>
                    <h2 class='accordion-header' id='headingOneLight'>
                        <button class='accordion-button' type='button' data-bs-toggle='collapse' data-bs-target='#collapseOneLight' aria-expanded='true' aria-controls='collapseOneLight'>Peaper Details</button>
                    </h2>
                    <div id='collapseOneLight' class='accordion-collapse collapse' aria-labelledby='headingOneLight' data-bs-parent='#accordionExample2'>
                        <div class='accordion-body'>
                            <strong>{$row['peaperName']}</strong>
                            <div class='row'>
                                <div class='col-5 border border-1 m-2 p-2'>
                                    <span class='text-dark'>Access Class</span><br>
                                    {$ClassNameList}</div>
                            </div>
                            <div class='row'>
                                <span class='col-auto alert alert-success m-2 p-0 px-2'>All Compleated : {$row['doneStu']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>A pass : {$row['Apass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>B pass : {$row['Bpass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>C pass : {$row['Cpass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>S pass : {$row['Spass']}</span>
                                <span class='col-auto alert alert-info m-2 p-0 px-2'>F pass : {$row['Fpass']}</span>
                            </div>
                            <div class='row'>
                                    <div id='activePeaperChart' class='auto-align-graph bg-light align-center'></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class='row item-center'>
                <div class='sub-nav'>
                    <div class='sub-nav-body' id='sub-nav-body2'>
                        <div class='radio-btn notifiradio'>
                            <input type='radio' name='sub_nav_rank' id='sub_nav_rank-1' value='iland' onclick='ShowRankBody()' checked>
                            {$radioList}
                            <div class='ul'>
                                <label class='text-overflow' for='sub_nav_rank-1'>ILand Rank</label>
                                {$radioLable}
                            </div>
                        </div
                    </div>
                </div>
            </div>
            <div id='rank_Body'><center><img src='assets/img/gif/loding.gif' width='300' alt='' srcset=''></center></div>
            ";
    } elseif ($type == "downloadPeaper") {
        $tHead = "
        <tr>
            <th>User Name</th>
            <th>RegCode</th>
            <th>Mobile</th>
            <th>Status</th>
            <th>Action</th>
        </tr>";
        $tBody = "";

        $sql = "SELECT lesson.LesName,user.UserName,user.RegCode,userdata.MobNum,userdata.WhaNum,uploadpeaper.* FROM uploadpeaper LEFT JOIN user ON uploadpeaper.UserId = user.UserId LEFT JOIN lesson ON lesson.LesId = uploadpeaper.LesId LEFT JOIN userdata ON uploadpeaper.UserId = userdata.UserId  WHERE uploadpeaper.Status != 'finished'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $tBody .= "
                <tr>
                    <td>{$row['UserName']}</td>
                    <td>{$row['RegCode']}</td>
                    <td>Mobile : {$row['MobNum']} <br> Whatsapp : {$row['WhaNum']}</td>
                    <td>{$row['Status']}</td>
                    <td>
                        <div class='actions item-center'>
                            <a onclick=''><i class='bi bi-pencil-square text-green fs-6'></i></a>
                            &nbsp;
                            <a onclick=''><i class='bi bi-cloud-arrow-down text-green fs-6'></i></a>
                        </div>
                    </td>
                </tr>";
        } else {
            $tBody .= "<tfoot><tr><td colspan='5' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr></tfoot>";
        }
        $htmlContent =  $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } elseif ($type == 'FinishedPeaper') {
        $tHead = "
        <tr>
            <th>Name</th>
            <th>Type</th>
            <th>Discription</th>
            <th>Aprued Class</th>
            <th>Status</th>
            <th>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM peaper WHERE ( peaperName LIKE ? or type LIKE ? or ClassId LIKE ? or GId LIKE ? or Dict LIKE ? or Status LIKE ? ) AND Status = 'finished' " : "SELECT * FROM peaper WHERE Status = 'finished' ";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("ssssss", $data, $data, $data, $data, $data, $data) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $PeaperId = $row['PeaperId'];
            $action = $row['Status'] == 'active' ? "<a onclick='disable(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-x-circle text-red fs-6'></i></a>" : "<a onclick='enable(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-check-circle text-green fs-6'></i></a>";
            $actionFinished = $row['Status'] == 'active' ? "<a onclick='end(this.id,`peaper`)' id='{$PeaperId}'><i class='bi bi-explicit text-info fs-6'></i></a>" : "";
            $Status = $row['Status'] == 'active' ? "<span class='text-green fs-6'><i class='bi bi-check-circle'></i></span>" : "<span class='text-red fs-6'><i class='bi bi-x-circle'></i></span>";

            if ($row['ClassId'] == !null) {
                $ClassNameList = "";
                $ClassIdList = explode("][", substr($row['ClassId'], 1, -1));
                foreach ($ClassIdList as $value) {
                    $sql = "SELECT * FROM class WHERE ClassId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                        $ClassNameList .= "{$row1['year']} {$row1['ClassName']} {$row1['InstiName']}" . "<br>";
                    }
                }
            } else {
                $ClassNameList = "Not a Class";
            }

            // if ($row['GId'] == !null) {
            //     $groupNameList = "";
            //     $groupIdList = explode("][", substr($row['GId'], 1, -1));
            //     foreach ($groupIdList as $value) {
            //         $sql = "SELECT * FROM grouplist WHERE GId = ? ";
            //         $stmt = $conn->prepare($sql);
            //         $stmt->bind_param("i", $value);
            //         $stmt->execute();
            //         $reusalt1 = $stmt->get_result();
            //         if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
            //             $groupNameList .= "{$row1['MGName']}" . "<br>";
            //         }
            //     }
            // } else {
            //     $groupNameList = "Not a Class";
            // }

            $tBody .= "
            <tr>
                <td>{$row['peaperName']}</td>
                <td>{$row['type']}</td>
                <td>" . (empty($row['Dict']) ? "Not Discription" :  $row['Dict']) . "</td>
                <td>{$ClassNameList}</td>
                <td class='text-center'>{$Status}</td>
                <td>
                    <div class='actions item-center'>
                        {$action}&nbsp;{$actionFinished}
                        &nbsp;
                        <a onclick='updateModelContent(`viewFinishedPeaper`,this.id)' id='{$PeaperId}'><i class='bi bi-list text-green fs-6'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $htmlContent = ($reusalt->num_rows > 0) ?  $tFirst . $tHead . $tMiddle . $tBody . $tEnd : $tFirst . $tHead . $tMiddle . "<tr><td colspan='7'  class='text-info text-center'><i class='bi bi-search'>&nbsp;</i>Not A Peaper</td></tr>" . $tEnd;
    }
    echo $htmlContent;
}

if (isset($_POST['changeRankingData'])) {
    $type = $_POST['type'];
    if (true) {
        $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
        $limit = $_POST['limit'] == 'all' ? "" : " LIMIT " . $_POST['limit'];
        $tFirst = "
            <div class='table-responsive'>
                <table class='table table-bordered m-0'>
                    <thead>";
        $tMiddle = "
            </thead>
            <tbody>";
        $tEnd = "
                    </tbody>
                </table>
            </div>";
        $tHead = "
            <tr>
                <th>Name</th>
                <th>Exam Id</th>
                <th>Institute</th>
                <th>Marks</th>
                <th>Rank</th>
                <th></th>
            </tr>";
        $tBody = "";
        $sql = isset($_POST['data']) ? "SELECT * FROM peaper p INNER JOIN  ( SELECT m.*,un.CousId,un.Name,DENSE_RANK() OVER (ORDER BY m.Marks DESC) AS rank FROM marksofpeaper m INNER JOIN unreguser un ON un.URGId = m.URGId ORDER BY m.Marks ) t ON ( t.Name LIKE ? or t.CousId LIKE ? ) and t.PeaperId = p.PeaperId WHERE p.Status = 'active'  $limit" :
            "SELECT * FROM peaper p INNER JOIN ( SELECT *,DENSE_RANK() OVER (ORDER BY m.Marks DESC) AS rank FROM marksofpeaper m ORDER BY m.Marks) t ON t.PeaperId = p.PeaperId WHERE p.Status = 'active' $limit";
        $stmt = $conn->prepare($sql);
        isset($_POST['data']) ? $stmt->bind_param("ss", $data, $data) : null;
        $stmt->execute();
        $reusaltMain = $stmt->get_result();
        $stmt->close();
        while ($reusaltMain->num_rows > 0 && $rowMain = $reusaltMain->fetch_assoc()) {
            // $PeaperId = $rowMain['PeaperId'];
            $URGId = $rowMain['URGId'];
            $UserId = $rowMain['UserId'];
            if ($UserId != null) {
                $sql = "SELECT UserName,InstiId,InstiName FROM user WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $UserId);
                $stmt->execute();
                $reusaltUserData = $stmt->get_result();
                $stmt->close();
                if ($reusaltUserData->num_rows > 0 && $rowUserData = $reusaltUserData->fetch_assoc()) {
                    $UserName =  $rowUserData['UserName'];
                    $InstiId =  $rowUserData['InstiId'];
                    $InstiName =  $rowUserData['InstiName'];
                }
            } else {
                $sql = "SELECT Name,InstiName,CousId FROM unreguser WHERE URGId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $URGId);
                $stmt->execute();
                $reusaltUserData = $stmt->get_result();
                $stmt->close();
                if ($reusaltUserData->num_rows > 0 && $rowUserData = $reusaltUserData->fetch_assoc()) {
                    $UserName =  $rowUserData['Name'];
                    $InstiId =  $rowUserData['CousId'];
                    $InstiName =  $rowUserData['InstiName'];
                }
            }

            if ($type != 'iland' && $type != $InstiName) {
                continue;
            }

            $tBody .= "
                <tr>
                    <td>{$UserName}</td>
                    <td>{$InstiId}</td>
                    <td>{$InstiName}</td>
                    <td>{$rowMain['Marks']}</td>
                    <td>{$rowMain['rank']}  ( " . ($rowMain['rank'] > 20 ? $rowMain['rank'] + 50 : $rowMain['rank']) . " )</td>
                    <td class='text-center'>" . (($rowMain['Marks'] >= 74) ? "A" : (($rowMain['Marks'] > 64) ? "B"  : (($rowMain['Marks'] > 54) ? "C" : (($rowMain['Marks'] > 34) ? "S" : "F")))) . "</td>
                </tr>";
        }
        if (!$reusaltMain->num_rows > 0) {
            $tBody .= "<tr><td colspan='5' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
        }
        $modelContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    }
    echo $modelContent;
}

if (isset($_POST['changeviewOldPeaperData'])) {
    $type = $_POST['type'];
    $peaperId = $_POST['peaperId'];
    if (true) {
        $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
        $limit = $_POST['limit'] == 'all' ? "" : " LIMIT " . $_POST['limit'];
        $tFirst = "
            <div class='table-responsive'>
                <table class='table table-bordered m-0'>
                    <thead>";
        $tMiddle = "
            </thead>
            <tbody>";
        $tEnd = "
                    </tbody>
                </table>
            </div>";
        $tHead = "
            <tr>
                <th>Name</th>
                <th>Exam Id</th>
                <th>Institute</th>
                <th>Marks</th>
                <th>Rank</th>
                <th>
                    <div class='actions item-center'>
                        <a onclick='prepareFile(`OldPeaperData`,`{$peaperId}`,`{$type}`)' id='{$peaperId}'><i class='bi bi-cloud-arrow-down text-green fs-6'></i></a>
                    </div>
                </th>
            </tr>";

        $limitInsti = empty($type) || $type == 'iland' ? "" : " and InstiName = '{$type}'";

        $tBody = "";
        $sql = isset($_POST['data']) ? "SELECT * FROM peaper p INNER JOIN  ( SELECT m.*,un.CousId,un.Name,DENSE_RANK() OVER (ORDER BY m.Marks DESC) AS rank FROM marksofpeaper m , unreguser un WHERE m.PeaperId = ? and un.URGId = m.URGId $limitInsti ORDER BY m.Marks DESC) t ON ( t.Name LIKE ? or t.CousId LIKE ? ) WHERE p.PeaperId = ? $limit" :
            "SELECT * FROM peaper p INNER JOIN ( SELECT m.*,DENSE_RANK() OVER (ORDER BY m.Marks DESC) AS rank FROM marksofpeaper m , unreguser un  WHERE m.peaperId = '$peaperId' and un.URGId = m.URGId $limitInsti ORDER BY m.Marks DESC) t WHERE p.PeaperId = '$peaperId' $limit";
        $stmt = $conn->prepare($sql);
        isset($_POST['data']) ? $stmt->bind_param("ssss", $peaperId, $data, $data, $peaperId) : null;
        $stmt->execute();
        $reusaltMain = $stmt->get_result();
        $stmt->close();
        while ($reusaltMain->num_rows > 0 && $rowMain = $reusaltMain->fetch_assoc()) {
            // $PeaperId = $rowMain['PeaperId'];
            $URGId = $rowMain['URGId'];
            $UserIdstu = $rowMain['UserId'];
            if ($UserIdstu != null) {
                $sql = "SELECT UserName,InstiId,InstiName FROM user WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $UserIdstu);
                $stmt->execute();
                $reusaltUserData = $stmt->get_result();
                $stmt->close();
                if ($reusaltUserData->num_rows > 0 && $rowUserData = $reusaltUserData->fetch_assoc()) {
                    $UserName =  $rowUserData['UserName'];
                    $InstiId =  $rowUserData['InstiId'];
                    $InstiName =  $rowUserData['InstiName'];
                }
            } else {
                $sql = "SELECT Name,InstiName,CousId FROM unreguser WHERE URGId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $URGId);
                $stmt->execute();
                $reusaltUserData = $stmt->get_result();
                $stmt->close();
                if ($reusaltUserData->num_rows > 0 && $rowUserData = $reusaltUserData->fetch_assoc()) {
                    $UserName =  $rowUserData['Name'];
                    $InstiId =  $rowUserData['CousId'];
                    $InstiName =  $rowUserData['InstiName'];
                }
            }

            if ($type != 'iland' && $type != $InstiName) {
                continue;
            }

            $tBody .= "
                <tr>
                    <td>{$UserName}</td>
                    <td>{$InstiId}</td>
                    <td>{$InstiName}</td>
                    <td>{$rowMain['Marks']}</td>
                    <td>{$rowMain['rank']}  ( " . ($rowMain['rank'] > 20 ? $rowMain['rank'] + 50 : $rowMain['rank']) . " )</td>
                    <td class='text-center'>" . (($rowMain['Marks'] >= 74) ? "A" : (($rowMain['Marks'] > 64) ? "B"  : (($rowMain['Marks'] > 54) ? "C" : (($rowMain['Marks'] > 34) ? "S" : "F")))) . "</td>
                </tr>";
        }
        if (!$reusaltMain->num_rows > 0) {
            $tBody .= "<tr><td colspan='5' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
        }
        $modelContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    }
    echo $modelContent;
}

// peaper management sestion end **************


// add isnippet management start **************

// add insti sestion start
if (isset($_POST['addinstiData'])) {
    try {
        $instiName = $_POST['instiName'];
        $instiPlace = $_POST['instiPlace'];
        $instidict = $_POST['instidict'];
        $instisubdict = $_POST['instisubdict'];
        $timetable = $_POST['timetable'];
        $instiTempName = $_FILES['instipic']['tmp_name'];
        $instipic = $instiName . ".jpg";
        $status = "active";

        if (true) {
            $sql = "SELECT InstiId FROM insti WHERE InstiName = ? and InstiPlace = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $instiName, $instiPlace);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if (!$reusalt->num_rows > 0) {
                $sql1 = "INSERT INTO insti(InstiName, InstiPlace, Dict, SubDict, InstiPic, TimeTable, Status) VALUE(?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql1);
                $stmt->bind_param("sssssss", $instiName, $instiPlace, $instidict, $instisubdict, $instipic, $timetable, $status);
                $stmt->execute();
                $stmt->close();
                $targetFile = "../../Dachbord/assets/img/site use/instiimge/" . $instipic;
                move_uploaded_file($instiTempName, $targetFile);
                $respons =  "success";
            } else {
                $respons =  "alredy added";
            }
        }
    } catch (Exception $e) {
        $respons =  "error";
    }
    echo $respons;
}
// add insti sestion end

// add class section start
if (isset($_POST['addClassData'])) {
    try {
        $className = $_POST['className'];
        $classPrice = $_POST['classPrice'];
        $classInsti = $_POST['classInsti'];
        $classType = $_POST['classType'];
        $year = $_POST['year'];
        $status = "active";
        if (true) {
            $sql = "SELECT ClassId FROM class WHERE ClassName = ? and InstiName = ? and Type = ? and year = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $className, $classInsti, $classType, $year);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if (!$reusalt->num_rows  > 0) {
                $sql1 = "INSERT INTO class(ClassName,InstiName,Type,Year,Price,Status) VALUE(?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql1);
                $stmt->bind_param("ssssss", $className, $classInsti, $classType, $year, $classPrice, $status);
                $stmt->execute();
                $stmt->close();
                $respons = "success";
            } else {
                $respons = "alredy added";
            }
        }
    } catch (Exception $e) {
        $respons = "error";
    }
    echo $respons;
}
// add class section end

// add group section start
if (isset($_POST['AddGroupData'])) {
    try {
        $groupName = $_POST['groupname'];
        $FileTemp = $_FILES['groupimg']['tmp_name'];
        $ShowFrom = $_POST['ShowList'];
        $fileName = GetToday('ymdhis') . ".jpg";
        if ($ShowFrom == "") {
            $showList = null;
        } else {
            $showList = "";
            $listinshow = explode(",", $ShowFrom);
            foreach ($listinshow as $value) {
                $classdata = explode("-", $value);
                $sql = "SELECT ClassId FROM class WHERE `ClassName` =  ? and `InstiName` = ? and `year` = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $classdata[1], $classdata[2], $classdata[0]);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $classId = $row['ClassId'];
                    $showList .= "[{$classId}]";
                }
                $stmt->close();
            }
        }

        if (true) {
            $sql = "SELECT * FROM grouplist WHERE MGName = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $groupName);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if (!$reusalt->num_rows > 0) {
                $targetFile = "../../Dachbord/assets/img/site use/group/" . $fileName;
                $sql1 = "INSERT INTO grouplist(MGName,MGImage,ShowFrom) VALUE(?,?,?)";
                $stmt = $conn->prepare($sql1);
                $stmt->bind_param("sss", $groupName, $fileName, $showList);
                $stmt->execute();
                $stmt->close();
                move_uploaded_file($FileTemp, $targetFile);
                $respons = "success";
            } else {
                $respons = "alredy added";
            }
        }
    } catch (Exception $e) {
        $respons = "error";
    }
    echo $respons;
}
// add group section end

// add winner data start
if (isset($_POST['addwinner'])) {
    try {
        $type = $_POST['addwinner'];
        $expdate = $_POST['expdate'] == "" ? null : $_POST['expdate'];
        $dataList = $_POST['dataList'];
        $FileTemp = $_FILES['winnerImage']['tmp_name'];
        $fileName = GetToday('ymdhis') . ".jpg";
        $fileParth = "../../Dachbord/user_images/winner/";
        if ($type == 'insert') {
            try {
                $type = "winner";
                $status = "active";
                $sql = "INSERT INTO notification(Type,Dict,Image,expDate,Status) VALUES(?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $type, $dataList, $fileName, $expdate, $status);
                if ($stmt->execute()) {
                    move_uploaded_file($FileTemp, $fileParth . $fileName);
                    $respons = "success";
                }
            } catch (Exception $e) {
                $respons = 'error' . $e;
            }
        } elseif ($type == 'update') {
            $respons = "update";
        } else {
            $respons = "undefind";
        }
    } catch (Exception $e) {
        $respons = "undefind";
    }
    echo $respons;
}
// add winner data end

// update insti Data start
if (isset($_POST['updateInsti'])) {
    try {
        $id = $_POST['id'];
        $place = $_POST['place'];
        $dict = $_POST['dict'];
        $subDict = $_POST['subDict'];
        $imageData = isset($_FILES['inatiImage']) ? $_FILES['inatiImage'] : null;

        $sql = "SELECT InstiName FROM insti WHERE InstiId = '$id'";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $row = $reusalt->fetch_assoc();
        $InstiName = $row['InstiName'];
        $picName = $InstiName . ".jpg";

        $sql = isset($_FILES['instiImage']) ? "UPDATE insti SET InstiPlace = ? , Dict = ? , SubDict = ? , InstiPic = ? WHERE InstiId = ?" : "UPDATE insti SET InstiPlace = ? , Dict = ? , SubDict = ? WHERE InstiId = ?";
        $stmt = $conn->prepare($sql);
        isset($_FILES['instiImage']) ?  $stmt->bind_param("sssss", $place, $dict, $subDict, $picName, $id) :  $stmt->bind_param("ssss", $place, $dict, $subDict, $id);
        if ($stmt->execute()) {
            // echo isset($_FILES['inatiImage'])  ?  "uploaded" : "not uploaded";
            isset($_FILES['instiImage'])  ? move_uploaded_file($_FILES['instiImage']['tmp_name'], "../../Dachbord/assets/img/site use/instiimge/" . $picName) : null;
            // echo isset($_FILES['instiImage'])  ?  : null;
        }

        $respons = "success";
    } catch (Exception $e) {
        $respons = "error " . $e;
    }
    echo $respons;
}
// update insti Data end
if (isset($_POST['updateClass'])) {
    try {
        $id = $_POST['id'];
        $className = $_POST['className'];

        $sql = "UPDATE class SET ClassName = ? WHERE ClassId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $className, $id);
        $stmt->execute();

        $respons = "success";
    } catch (Exception $e) {
        $respons = "error";
    }
    echo $respons;
}
// update class data start 

// group update start 
if (isset($_POST['updateGroup'])) {
    try {
        $id = $_POST['id'];
        $groupName = $_POST['groupName'];

        if (true) {
            $sql = "SELECT MGImage,ShowFrom FROM grouplist WHERE GId = '$id'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            $row = $reusalt->fetch_assoc();
            $MGImage = $row['MGImage'];
            $ShowFrom = $row['ShowFrom'];
        }

        if (isset($_POST['ShowList'])) {
            $showList = "";
            $listinshow = explode(",", $_POST['ShowList']);
            foreach ($listinshow as $value) {
                $classdata = explode("-", $value);
                $sql = "SELECT ClassId FROM class WHERE `ClassName` =  ? and `InstiName` = ? and `year` = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $classdata[1], $classdata[2], $classdata[0]);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $classId = $row['ClassId'];
                    $showList .= "[{$classId}]";
                }
                $stmt->close();
            }
        } else {
            $showList = $ShowFrom;
        }

        // update dtabase 
        $sql = "UPDATE grouplist SET MGName = ? , ShowFrom = ? WHERE GId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $groupName, $showList, $id);
        $stmt->execute();

        isset($_FILES['groupImage'])  ? move_uploaded_file($_FILES['groupImage']['tmp_name'], "../../Dachbord/assets/img/site use/group/" . $MGImage) : null;

        $respons = "success";
    } catch (\Throwable $th) {
        $respons = "error";
    }
    echo $respons;
}
// group update end 

// update class data end

// model html Content data load start 

if (isset($_POST['loadModelDataInsert'])) {
    $type = $_POST['Type'];
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    if ($type == "instiUpdate" || $type == "classUpdate" || $type == "groupUpdate") {
        $modelFooter = "
        <div class='modal-footer pt-3'>
            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
            <button type='button' class='btn btn-success' onclick='submitModelSnippetUpdate(`{$type}`,{$data})'>Update</button>
        </div>";
    } else {
        $modelFooter = "
        <div class='modal-footer pt-3'>
            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
            <button type='button' class='btn btn-success' onclick='submitModelSnippet(`insert`,`{$type}`)'>Finish</button>
        </div>";
    }
    if ($type == 'insti') {
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Add inatitute</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $modelBody = "
        <form class='addinstiform' id='Formclear'>
            <!-- Row start -->
            <div class='row'>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Institute Name *</label>
                        <input name='instiName' type='text' class='form-control addinsti' id='inputName' placeholder='Enter Institute Name'>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Institute Place *</label>
                        <input name='instiPlace' type='text' class='form-control addinsti' id='inputName' placeholder='Enter Institute Place'>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Desctiption</label>
                        <input name='instidict' type='text' class='form-control addinsti' id='inputName' placeholder='Enter Desctiption'>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Sub Desctiption</label>
                        <input name='instisubdict' type='text' class='form-control addinsti' id='inputName' placeholder='Enter Sub Desctiption'>
                    </div>
                </div>
                <div class='col-12 item-center'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Select Institute Picture *</label>
                        <input name='instipic' type='file' class='form-control addinstiimage' id='inputName' accept='.jpeg, .jpg, .png, .tiff'>
                    </div>
                </div>
                <div class='col-12'>
                    <div class='mb-3'>
                        <label for='inputMessage' class='form-label'>Time Table</label>
                        <textarea name='timetable' class='form-control addinsti' id='inputMessage' placeholder='Time table' rows='3'></textarea>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the inatitute!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the inatitute</div>
        </div>";
    } elseif ($type == 'class') {
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Add the Class</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $modelBody = "
        <form id='Formclear'>
            <!-- Row start -->
            <div class='row'>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Class Name *</label>
                        <input name='className' type='text' class='form-control addclass' id='inputName' placeholder='Enter Institute Name'>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Class Price *</label>
                        <input name='classPrice' type='number' class='form-control addclass' id='inputName' placeholder='Enter class price'>
                    </div>
                </div>
                <div class='col-xl-4 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputIndustryType' class='form-label'>Select Institute *</label>
                        <select id='classinstiopt' name='classInsti' class='form-select addclass' id='inputIndustryType'>
                            <option value=''>Select Type</option>
                        </select>
                    </div>
                </div>
                <div class='col-xl-4 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputIndustryType' class='form-label'>Select Class Type *</label>
                        <select name='classType' class='form-select addclass' id='inputIndustryType'>
                            <option value=''>Select Type</option>
                            <option value='physical'>Physical</option>
                            <option value='online'>Online</option>
                        </select>
                    </div>
                </div>
                <div class='col-xl-4 col-sm-12 col-12'>
                    <div class='mb-3'>
                        <label for='inputIndustryType' class='form-label'>Select Year *</label>
                        <select name='year' class='form-select addclass' id='inputIndustryType'>
                            <option value=''>Select Year</option>
                            <option value='2023' disabled>2023</option>
                            <option value='2024'>2024</option>
                            <option value='2025'>2025</option>
                            <option value='2026'>2026</option>
                            <option value='2027' disabled>2027</option>
                            <option value='2028' disabled>2028</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the Class!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the Class</div>
        </div>";
    } elseif ($type == 'group') {
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Add the group</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $modelBody = "
        <form id='Formclear'>
            <!-- Row start -->
            <div class='row'>
                <div class='col-xl-6 col-sm-6 col-12'>
                    <div class='mb-3'>
                        <label for='inputName' class='form-label'>Group Name *</label>
                        <input name='groupname' type='text' class='form-control addgroup' id='' placeholder='Enter Group Name'>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-6 col-12'>
                    <div class='mb-3 w-100'>
                        <label for='inputName' class='form-label'>Group Image *</label>
                        <input name='groupimg' type='file' class='form-control addgroup' id='' placeholder='Upload Group Image' accept='image/*'>
                    </div>
                </div>
                <div class='col-xl-12 col-sm-6 col-12'>
                    <div class='mb-3 tags w-100 groupshowclass'>
                        <label class='form-label d-flex'>Select Show Class *</label>
                        <select name='Showfrom' id='groupshow' class='is-valid select-multiple js-states form-control w-100' title='Select Product Category' multiple='multiple'>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the group!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the group</div>
        </div>";
    } elseif ($type == 'winner') {
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Add Winning Student</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $modelBody = "
        <form id='Formclear'>
            <div class='row d-flex flex-column item-center' id='stuWinInforBaer'>
                <div class='col-lg-10 col-12'>
                    <div class=''>
                        <div class='card my-3 p-2 w-100 main-info-card'>
                            <div class='main-info-card-head'>
                                <p class='w-100'><input name='peaperName' type='text' class='w-100 border-none is-invalid' placeholder='2025 pass peaper gampaha'></p>
                                <span class='text-success'><i class='bi bi-trophy'></i></span>
                            </div>
                            <div class='main-info-card-body'>
                                <label for='imgInp'>
                                    <img id='image-preview' src='assets/img/site use/icons/logo.png' width='75' height='75' alt=''>
                                </label>
                                <div class='ps-3 w-100'>
                                    <span class='text-red w-100'><input name='place' type='text' class='w-100 border-none text-red' placeholder='1`st Place'></span>
                                    <span class='fs-6 w-100'><input name='winnerName' type='text' class='w-100 border-none' placeholder='W.D Nethuli Nimasha Hettiarachchi - kegalle'></span>
                                </div>
                            </div>
                            <div class='main-info-card-end'>
                                <span class='text-dark w-100'><input name='dict' type='text' class='text-dark w-100 border-none' placeholder='physical peaper &nbsp; - &nbsp;14.00 to 16.00'></span>
                                <span class='text-success'><input name='marks' type='text' class='text-success w-100 border-none' placeholder='90%' style='text-align: end;'></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-lg-6 col-12'>
                    <label for='expdate' class='form-label'>Selelct Expair Date</label>
                    <input type='date' name='expdate' class='form-select' id='expdate'>
                </div>
            </div>
        </form>
        <form runat='server' id='Formclear' class='pb-4'>
            <!-- <input type='file' class='form-control' id='inputGroupFile02'> -->
            <div class='mb-3 d-none'>
                <label class='form-label'>Select Winner Image</label>
                <input name='winnerImage' accept='image/*' type='file' id='imgInp' class='form-control' onchange='changeImage()' />
            </div>
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the winning student!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the winning student</div>
        </div>";
    } elseif ($type == 'instiUpdate') {
        $data = $_POST['data'];
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Update Institute</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $sql = "SELECT * FROM insti WHERE InstiId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        $row = $reusalt->fetch_assoc();
        $image = $row['InstiPic'];
        $subDict = explode("-", $row['SubDict'])[0];
        $modelBody = "
        <form id='Formclear' class='item-center'>
            <div class='col-xxl-6 col-sm-10 col-12'>
                <div class='info-tile'>
                    <center>
                        <label for='imgInp'><img id='image-preview' class='w-auto' src='../Dachbord/assets/img/site use/instiimge/{$image}' alt='insti Image'></label>
                    </center>
                    <div class='info-details' style='height: 125px;'>
                        <span class='green w-50'><input name='place' type='text' class='w-75 bg-transparent border-none' value = '{$row['InstiPlace']}'></span><i class='bi bi-check2-circle'></i>
                        <p class='pt-2'><textarea name = 'dict' class='border-none w-100 h-auto'>{$row['Dict']}</textarea></p>
                    </div>
                    <div class='card__data'>
                        <center>
                            <p>
                                <span class='card__description'><b>
                                        {$row['InstiName']}
                                    </b><br>
                                    <input name='subDict' type='text' class=' border-none ' value = '{$subDict}'>
                                </span>
                            </p>
                            <div class='card_btn'>
                                <p class='btn btn-info mt-3 p-2'><span class='icon'><i class='bi bi-arrow-right-circle'></i> Login</span></p>
                            </div>
                        </center>
                    </div>
                </div>
            </div>
        </form>
        <form runat='server' id='Formclear' class='pb-4'>
            <div class='mb-3 d-none'>
                <label class='form-label'>Select Winner Image</label>
                <input name='instiImage' accept='image/*' type='file' id='imgInp' class='form-control' onchange='changeImage()' />
            </div>
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull Update Institute!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed Update Institute</div>
        </div>";
    } elseif ($type == "classUpdate") {
        $data = $_POST['data'];
        $modelHead = "
            <div class='modal-header'>
                <h5 class='modal-title' id='modelMainLabel'>Update The Class</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
            </div>
            <div class='modal-body bg-light'>";
        $sql = "SELECT * FROM class WHERE ClassId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        $row = $reusalt->fetch_assoc();
        $ClassName = $row['ClassName'];
        $modelBody = "
            <form id='Formclear'>
                <!-- Row start -->
                <div class='row'>
                    <div class='col-xl-12 col-sm-12 col-12'>
                        <div class='mb-3'>
                            <label for='className' class='form-label'>Class Name</label>
                            <input name='className' type='text' class='form-control' value='{$ClassName}' placeholder='Enter Class Name'>
                        </div>
                    </div>
                </div>
                <!-- Row end -->
            </form>
            <div class='my-3 rusaltLog mx-3'>
                <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the group!</div>
                <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the group</div>
            </div>";
    } elseif ($type == "groupUpdate") {
        $modelHead = "
        <div class='modal-header'>
            <h5 class='modal-title' id='modelMainLabel'>Update group</h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body bg-light'>";
        $sql = "SELECT * FROM grouplist WHERE GId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        $row = $reusalt->fetch_assoc();
        // get Show NAme 
        if ($row['ShowFrom'] != null) {
            $ShowName = "";
            try {
                $Showarray = explode("][", substr($row['ShowFrom'], 1, -1));
                foreach ($Showarray as $value) {
                    $sql = "SELECT * FROM class WHERE ClassId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    $stmt->close();
                    $row1 = $reusalt1->fetch_assoc();
                    $ShowName .= "{$row1['year']} {$row1['ClassName']} {$row1['InstiName']}" . "<br>";
                }
            } catch (\Throwable $th) {
                $ShowName = "Undefind";
            }
        } else {
            $ShowName = "Not Show Class";
        }
        $modelBody = "
        <form id='Formclear'>
            <div class='row item-center'>
                <div class='col-12 '>
                    <div class='table-responsive'>
                        <table class='table v-middle'>
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Image</th>
                                    <th>Show From</th>
                                    <th>Status</th>
                                </tr>
                             </thead>
                             <tbody id='table-content-change'>
                                <td>{$row['GId']}</td>
                                <td>{$row['MGName']}</td>
                                <td><img width='50' id='image-preview' class='main-card-img' src='../Dachbord/assets/img/site use/group/{$row['MGImage']}' ></td>
                                <td>{$ShowName}</td>
                                <td>{$row['Status']}</td>
                             </tbody>
                        </table>
                    </div>
                </div>
                <div class='col-xl-4 col-sm-6 col-8 '>
                    <div class='main-card h-auto'>
                        <div class='main-sub-card'>
                            <i class=' position-absolute top-0 start-100 pe-5 pt-5 translate-middle'></i>
                            <label for='imgInp'>
                                <center><img id='image-preview' class='main-card-img' src='../Dachbord/assets/img/site use/group/{$row['MGImage']}' ></center>
                            </label>
                        <div class='main-card-details w-100 mt-2'>
                                <span class='green'><i class='bi bi-unlock'></i>&nbsp;Access available</span>
                                <div class='name'>
                                    <textarea name = 'groupName' class='border-none w-100 h-auto' rows='5'>{$row['MGName']}</textarea>
                                </div>
                            </div>
                            <div class='main-card-footer mt-2 h-auto'>
                                <button class='btn btn-info py-1 px-3'>Acign</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-6 col-12 item-center' >
                    <div class='mb-3 tags w-100 groupshowclass'>
                        <label class='form-label d-flex'>Select Show Class *</label>
                        <select name='showfrom' id='groupshow' class='is-valid select-multiple js-states form-control w-100' title='Select Product Category' multiple='multiple'>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>
            </div>
        </form>
        <form runat='server' id='Formclear' class='pb-4'>
            <!-- <input type='file' class='form-control' id='inputGroupFile02'> -->
            <div class='mb-3 d-none'>
                <label class='form-label'>Select Winner Image</label>
                <input name='winnerImage' accept='image/*' type='file' id='imgInp' class='form-control' onchange='changeImage()' />
            </div>
        </form>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the group!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the group</div>
        </div>";
    }
    $htmlContent = $modelHead . $modelBody . $modelFooter;
    echo $htmlContent;
}

// model html Content data load end

// update snppet checked table content start

if (isset($_POST['changeSnippitManageTable'])) {
    $type = $_POST['changeSnippitManageTable'];
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    $dataNew = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
    $tFirst = "
    <div class='table-responsive'>
        <table class='table table-bordered m-0'>
            <thead>";
    $tMiddle = "
    </thead>
    <tbody>";
    $tEnd = "
            </tbody>
        </table>
    </div>";
    if ($type == 'InstiManage') {
        $tHead = "
        <tr>
            <th></th>
            <th>Name</th>
            <th>Place </th>
            <th>Description</th>
            <th>Sub Description</th>
            <th>Status</th>
            <th {$hiddenStatus}>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM insti WHERE InstiName LIKE ? or InstiPlace LIKE ? or Dict LIKE ? or SubDict LIKE ? or Status LIKE ?" : "SELECT * FROM insti";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("sssss", $dataNew, $dataNew, $dataNew, $dataNew, $dataNew) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $InstiId = $row['InstiId'];
            $action = $row['Status'] == 'active' ? "<a onclick='disable(this.id,`insti`)' id='{$InstiId}'><i class='bi bi-x-circle text-red fs-6'></i></a>" : "<a onclick='enable(this.id,`insti`)' id='{$InstiId}'><i class='bi bi-check-circle text-green fs-6'></i></a>";
            $Status = $row['Status'] == 'active' ? "<span class='text-green fs-6'><i class='bi bi-check-circle'></i></span>" : "<span class='text-red fs-6'><i class='bi bi-x-circle'></i></span>";
            $tBody .= "
            <tr>
                <td><img src='../Dachbord/assets/img/site use/instiimge/{$row['InstiPic']}' width='50' onclick='showImage(this.src)' alt='Image not found!'></td>
                <td>{$row['InstiName']}</td>
                <td>{$row['InstiPlace']}</td>
                <td>{$row['Dict']}</td>
                <td>{$row['SubDict']}</td>
                <td class='text-center'>{$Status}</td>
                <td {$hiddenStatus}>
                    <div class='actions item-center'>
                        {$action}
                        &nbsp;
                        <a onclick='update(this.id,`insti`)' id='{$InstiId}'><i class='bi bi-pencil-square text-green fs-6'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $htmlContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } elseif ($type == "ClassManage") {
        $tHead = "
        <tr>
            <th>Year</th>
            <th>ClassName</th>
            <th>Insti Name</th>
            <th>Type</th>
            <th>Price</th>
            <th>Status</th>
            <th {$hiddenStatus}>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM class WHERE ClassName LIKE ? or InstiName LIKE ? or Type LIKE ? or year LIKE ? or Price LIKE ? or Status LIKE ?" : "SELECT * FROM class";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("ssssss", $dataNew, $dataNew, $dataNew, $dataNew, $dataNew, $dataNew) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $ClassId = $row['ClassId'];
            $action = $row['Status'] == 'active' ? "<a onclick='disable(this.id,`class`)' id='{$ClassId}'><i class='bi bi-x-circle text-red fs-6'></i></a>" : "<a onclick='enable(this.id,`class`)' id='{$ClassId}'><i class='bi bi-check-circle text-green fs-6'></i></a>";
            $Status = $row['Status'] == 'active' ? "<span class='text-green fs-6'><i class='bi bi-check-circle'></i></span>" : "<span class='text-red fs-6'><i class='bi bi-x-circle'></i></span>";
            $tBody .= "
            <tr>
                <td>{$row['year']}</td>
                <td>{$row['ClassName']}</td>
                <td>{$row['InstiName']}</td>
                <td>{$row['Type']}</td>
                <td>{$row['Price']}</td>
                <td class='text-center'>{$Status}</td>
                <td class='text-center' {$hiddenStatus}>
                    <div class='actions item-center'>
                        {$action}
                        &nbsp;
                        <a onclick='update(this.id,`class`)' id='{$ClassId}'><i class='bi bi-pencil-square text-green fs-6'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $htmlContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } elseif ($type == "GroupManage") {
        $tHead = "
        <tr>
            <th></th>
            <th>Group Name</th>
            <th>Show From</th>
            <th>Status</th>
            <th {$hiddenStatus}>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM grouplist WHERE MGName LIKE ? or Status LIKE ?" : "SELECT * FROM grouplist";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("ss", $dataNew, $dataNew) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $Gid = $row['GId'];
            $action = $row['Status'] == 'active' ? "<a onclick='disable(this.id,`group`)' id='{$Gid}'><i class='bi bi-x-circle text-red fs-6'></i></a>" : "<a onclick='enable(this.id,`group`)' id='{$Gid}'><i class='bi bi-check-circle text-green fs-6'></i></a>";
            $Status = $row['Status'] == 'active' ? "<span class='text-green fs-6'><i class='bi bi-check-circle'></i></span>" : "<span class='text-red fs-6'><i class='bi bi-x-circle'></i></span>";
            if ($row['ShowFrom'] == !null) {
                $ShowFrom = "";
                $InstiIdList = explode("][", substr($row['ShowFrom'], 1, -1));
                foreach ($InstiIdList as $value) {
                    $sql = "SELECT * FROM class WHERE ClassId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                        $ShowFrom .= "{$row1['year']} {$row1['ClassName']} {$row1['InstiName']}" . "<br>";
                    }
                }
            } else {
                $ShowFrom = "Not Show Institute";
            }
            $tBody .= "
            <tr>
                <td><img src='../Dachbord/assets/img/site use/group/{$row['MGImage']}' width='50' onclick='showImage(this.src)' alt='Image not found!'></td>
                <td>{$row['MGName']}</td>
                <td>{$ShowFrom}</td>
                <td class='text-center'>{$Status}</td>
                <td class='text-center' {$hiddenStatus}>
                    <div class='actions item-center'>
                        {$action}
                        &nbsp;
                        <a onclick='update(this.id,`group`)' id='{$Gid}'><i class='bi bi-pencil-square text-green fs-6'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $htmlContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } elseif ($type == "WinnerManage") {
        $tHead = "
        <tr>
            <th></th>
            <th>Name</th>
            <th>Peaper </th>
            <th>Place</th>
            <th>Marks</th>
            <th>Discription</th>
            <th>Status</th>
            <th>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM notification WHERE (Dict LIKE ? or Status LIKE ?) and Type = 'winner' " : "SELECT * FROM notification WHERE Type = 'winner'";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("ss", $dataNew, $dataNew) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $NotifiId = $row['NotifiId'];
            $action = $row['Status'] == 'active' ? "<a onclick='disable(this.id,`winner`)' id='{$NotifiId}'><i class='bi bi-x-circle text-red fs-6'></i></a>" : "<a onclick='enable(this.id,`winner`)' id='{$NotifiId}'><i class='bi bi-check-circle text-green fs-6'></i></a>";
            $Status = $row['Status'] == 'active' ? "<span class='text-green fs-6'><i class='bi bi-check-circle'></i></span>" : "<span class='text-red fs-6'><i class='bi bi-x-circle'></i></span>";
            $dataList = json_decode($row['Dict']);
            $tBody .= "
            <tr>
                <td><img src='../Dachbord/user_images/winner/{$row['Image']}' width='50' onclick='showImage(this.src)' alt='Image not found!'></td>
                <td>{$dataList[5]}</td>
                <td>{$dataList[1]}</td>
                <td>{$dataList[3]}</td>
                <td>{$dataList[9]}</td>
                <td>{$dataList[7]}</td>
                <td class='text-center'>{$Status}</td>
                <td>
                <div class='actions item-center'>
                    {$action}
                    <a onclick='del(this.id,`winner`)' id='{$NotifiId}'><i class='bi bi-trash text-red'></i></a>
                    &nbsp;
                    <!--<a onclick='update(this.id,`winner`)' id='{$NotifiId}'><i class='bi bi-pencil-square text-green fs-6'></i></a>-->
                </div>
                </td>
            </tr>";
        }
        $htmlContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } else {
    }
    echo $htmlContent;
}

// snippet management end **************

// notification management start ********

// update admin notification  table section start

if (isset($_POST['UpdateNotifiTableContent'])) {
    $type = $_POST['type'];
    $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
    if ($type == 'updatePayment') {
        $htmlContent = "";
        $htmlContentHeader = "
            <div class='table-responsive'>
                <table class='table v-middle'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reg Code</th>
                            <th>Number</th>
                            <th>images</th>
                            <th>Type</th>
                            <th>Class</th>
                            <th>Month</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id='table-content-change'>";
        $htmlContentFooter = "
                    </tbody>
                </table>
            </div>";
        $status = "pending";
        $accessSql = "";
        if ($adminType[0] == 'admin') {
            foreach ($adminAcsess as $key => $value) {
                $key == 0 ? $accessSql .= " AND (" : null;
                $key > 0 && count($adminAcsess) + 1 > $key ? $accessSql .= " OR " : null;
                $accessSql .= "class.year = '$value'";
            }
            $accessSql .= ")";
        }
        $addsql = $adminType[0] == 'admin' && isset($adminType[1]) ? " AND class.InstiName = '$adminType[1]' $accessSql" : null;
        $sql = $data == null ? "SELECT user.RegCode,user.UserName,class.*,payment.*,userdata.WhaNum,userdata.MobNum FROM payment INNER JOIN user ON payment.UserId = user.UserId INNER JOIN userdata ON userdata.UserId = payment.UserId INNER JOIN class ON payment.ClassId = class.ClassId  WHERE payment.status = ?" . $addsql : "SELECT user.RegCode,user.UserName,class.*,payment.*,userdata.MobNum,userdata.WhaNum FROM payment INNER JOIN user ON payment.UserId = user.UserId INNER JOIN userdata ON userdata.UserId = user.UserId INNER JOIN class ON payment.ClassId = class.ClassId WHERE ( payment.status = ? and payment.UserId = user.UserId and payment.ClassId = class.ClassId ) and (user.RegCode LIKe ? or user.UserName LIKE ? or payment.Name LIKE ? or class.ClassName LIKE ? or class.year LIKE ? or class.InstiName LIKE ?)" . $addsql;
        $stmt = $conn->prepare($sql);
        $data !=  null ? $stmt->bind_param("sssssss", $status, $data, $data, $data, $data, $data, $data) : $stmt->bind_param("s", $status);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $payId = $row['PayId'];
            $image = $row['Slip'];
            $ClassName = $row['year'] . "-" . $row['ClassName'] . "-" . $row['InstiName'];
            $month = substr($row['Month'], 0, 3) . " " . GetMonthName(substr($row['Month'], 4, 5));
            $htmlContent .= "
            <tr>
                <td>{$row['UserName']}</td>
                <td>{$row['RegCode']}</td>
                <td>Mobile : {$row['MobNum']}<br>Whatsapp : {$row['WhaNum']}</td>
                <td>
                    <div class='media-box'>
                        <img src='../Dachbord/user_images/payment/{$image}' class='media-avatar notifiImage' alt='Image not found!' onclick='showImage(this.src)'>
                    </div>
                </td>
                <td>{$row['Type']}</td>
                <td>{$row['year']} {$row['ClassName']} - {$row['InstiName']}</td>
                <td>{$month}</td>
                <td>Rs : {$row['Price']}.00</td>
                <td>
                    <span class='text-blue td-status'><i class='bi bi-clock-history'></i> Pending</span>
                </td>
                <td>
                    <span class=' btn btn-success p-0 px-2' onclick='aprued(`payment`,{$payId})'>Aprued</span>
                    <span class=' btn btn-info p-0 px-2' onclick='Ignored(`payment`,{$payId})' >Ignored</span>
                </td>
            </tr>
            ";
        }
        if (!$reusalt->num_rows > 0) {
            $htmlContent =  "<tr><td colspan='10' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
        }
        $htmlAllContent = $htmlContentHeader . $htmlContent . $htmlContentFooter;
    } elseif ($type == 'updateInstiReg') {
        $htmlContent = "";
        $htmlContentHeader = "
            <div class='table-responsive'>
                <table class='table v-middle'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Reg Code</th>
                            <th>Number</th>
                            <th>Image</th>
                            <th>Insti Id</th>
                            <th>Insti</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id='table-content-change'>";
        $htmlContentFooter = "
                    </tbody>
                </table>
            </div>";
        $status = "pending";
        $accessSql = "";
        if ($adminType[0] == 'admin') {
            foreach ($adminAcsess as $key => $value) {
                $key == 0 ? $accessSql .= " AND (" : null;
                $key > 0 && count($adminAcsess) + 1 > $key ? $accessSql .= " OR " : null;
                $accessSql .= "user.Year = '$value'";
            }
            $accessSql .= ")";
        }
        $addsql = $adminType[0] == 'admin' && isset($adminType[1]) ? " AND user.InstiName = '$adminType[1]' $accessSql" : null;
        $sql = $data != null ? "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status,userdata.MobNum,userdata.WhaNum FROM user,userdata WHERE (user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ) and (user.UserName LIKE ? or user.RegCode LIKE ? or userdata.InstiId LIKE ? or user.InstiName LIKE ? ) $addsql" : "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status,userdata.MobNum,userdata.WhaNum FROM user,userdata WHERE user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId $addsql";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("sssss", $status, $data, $data, $data, $data) : $stmt->bind_param("s", $status);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $InstiId = $row['InstiId'];
            $InstiPic = $row['InstiPic'];
            $htmlContent .= "
            <tr>
                <td>{$row['UserName']}</td>
                <td>{$row['RegCode']}</td>
                <td>Number : {$row['MobNum']}<br>Whatsapp : {$row['WhaNum']}</td>
                <td>
                    <div class='media-box'>
                        <img src='../Dachbord/user_images/instiRegImg/{$InstiPic}' class='media-avatar notifiImage' alt='Image not found!' onclick='showImage(this.src)'>
                    </div>
                </td>
                <td>{$InstiId}</td>
                <td>{$row['InstiName']}</td>
                <td>
                    <!-- <span class='text-green td-status'><i class='bi bi-check-circle'></i> Paid</span> -->
                    <!-- <span class='text-red td-status'><i class='bi bi-x-circle'></i> Failed</span> -->
                    <span class='text-blue td-status'><i class='bi bi-clock-history'></i> {$row['Status']}</span>
                </td>
                <td>
                    <span class=' btn btn-success p-0 px-2' onclick='aprued(`insti`,`{$InstiId}`)'>Aprued</span>
                    <span class=' btn btn-info p-0 px-2' onclick='Ignored(`insti`,`{$InstiId}`)'>Ignored</span>
                    <!-- <span class='badge shade-blue min-90'>Processing</span> -->
                </td>
            </tr>
            ";
        }
        if (!$reusalt->num_rows > 0) {
            $htmlContent =  "<tr><td colspan='8' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
        }
        $htmlAllContent = $htmlContentHeader . $htmlContent . $htmlContentFooter;
    } elseif ($type = "updateAdminReg") {
        $htmlContent = "";
        $htmlContentHeader = "
            <div class='table-responsive'>
                <table class='table v-middle'>
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Access</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id='table-content-change'>";
        $htmlContentFooter = "
                    </tbody>
                </table>
            </div>";
        $status = "pending";
        $sql = $data != null ? "SELECT * FROM adminuser WHERE (UName LIKE ? or Email LIKE ? or MobNum LIKE ? or Access LIKE ? or Type LIKE ?) and Status = ?" : "SELECT * FROM adminuser WHERE Status = ?";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("ssssss", $data, $data, $data, $data, $data, $status) : $stmt->bind_param("s", $status);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $adminId = $row['AdminId'];
            $htmlContent .= "
            <tr>
                <td>{$row['UName']}</td>
                <td>{$row['Email']}</td>
                <td>{$row['MobNum']}</td>
                <td>{$row['Access']}</td>
                <td>{$row['Type']}</td>
                <td>
                    <!-- <span class='text-green td-status'><i class='bi bi-check-circle'></i> Paid</span> -->
                    <!-- <span class='text-red td-status'><i class='bi bi-x-circle'></i> Failed</span> -->
                    <span class='text-blue td-status'><i class='bi bi-clock-history'></i> {$row['Status']}</span>
                </td>
                <td>
                    <span class=' btn btn-success p-0 px-2' onclick='aprued(`regAdmin`,{$adminId})'>Aprued</span>
                    <span class=' btn btn-info p-0 px-2' onclick='Ignored(`regAdmin`,{$adminId})'>Ignored</span>
                    <!-- <span class='badge shade-blue min-90'>Processing</span> -->
                </td>
            </tr>
            ";
        }
        if (!$reusalt->num_rows > 0) {
            $htmlContent =  "<tr><td colspan='7' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
        }
        $htmlAllContent = $htmlContentHeader . $htmlContent . $htmlContentFooter;
    } else {
        $htmlAllContent = "undefind";
    }
    echo $htmlAllContent;
}

// if (isset($_POST['updatePayment'])) {
//     $response = $_POST['updatePayment'];
//     $htmlContent = "";
//     $htmlContentHeader = "
//             <div class='table-responsive'>
//                 <table class='table v-middle'>
//                     <thead>
//                         <tr>
//                             <th>Name</th>
//                             <th>Reg Code</th>
//                             <th>images</th>
//                             <th>Type</th>
//                             <th>Class</th>
//                             <th>Month</th>
//                             <th>Price</th>
//                             <th>Status</th>
//                             <th>Action</th>
//                         </tr>
//                     </thead>
//                     <tbody id='table-content-change'>";
//     $htmlContentFooter = "
//                     </tbody>
//                 </table>
//             </div>";
//     $status = "pending";
//     if ($response == "") {
//         $sql = "SELECT user.RegCode,user.UserName,class.*,payment.* FROM payment,user,class WHERE payment.status = ? and payment.UserId = user.UserId and payment.ClassId = class.ClassId";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("s", $status);
//     } else {
//         $response = "%{$response}%";
//         $sql = "SELECT user.RegCode,user.UserName,class.*,payment.* FROM payment,user,class WHERE ( payment.status = ? and payment.UserId = user.UserId and payment.ClassId = class.ClassId ) and (user.RegCode LIKe ? or payment.Name LIKE ? or class.ClassName LIKE ? or class.year LIKE ? or class.InstiName LIKE ?)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ssssss", $status, $response, $response, $response, $response, $response);
//     }
//     $stmt->execute();
//     $reusalt = $stmt->get_result();
//     while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
//         $payId = $row['PayId'];
//         $regcode = $row['RegCode'];
//         $price = $row['Price'];
//         $name = $row['UserName'];
//         $image = $row['Slip'];
//         $type = $row['Type'];
//         $year = $row['year'];
//         $ClassName = $row['ClassName'];
//         $InstiName = $row['InstiName'];
//         $ClassName = $year . "-" . $ClassName . "-" . $InstiName;
//         $month = substr($row['Month'], 0, 3) . " " . GetMonthName(substr($row['Month'], 4, 5));
//         $htmlContent .= "
//             <tr>
//                 <td>{$name}</td>
//                 <td>{$regcode}</td>
//                 <td>
//                     <div class='media-box'>
//                         <img src='../Dachbord/user_images/payment/{$image}' class='media-avatar notifiImage' alt='Image not found!' onclick='showImage(this.src)'>
//                     </div>
//                 </td>
//                 <td>{$ClassName}</td>
//                 <td>{$type}</td>
//                 <td>{$month}</td>
//                 <td>Rs : {$price}.00</td>
//                 <td>
//                     <!-- <span class='text-green td-status'><i class='bi bi-check-circle'></i> Paid</span> -->
//                     <!-- <span class='text-red td-status'><i class='bi bi-x-circle'></i> Failed</span> -->
//                     <span class='text-blue td-status'><i class='bi bi-clock-history'></i> Awaiting</span>
//                 </td>
//                 <td>
//                     <span class=' btn btn-success p-0 px-2' onclick='aprued(`payment`,{$payId})'>Aprued</span>
//                     <span class=' btn btn-info p-0 px-2' onclick='Ignored(`payment`,{$payId})' >Ignored</span>
//                     <!-- <span class='badge shade-blue min-90'>Processing</span> -->
//                 </td>
//             </tr>
//             ";
//     }
//     echo $htmlContentHeader . $htmlContent . $htmlContentFooter;
// }

// if (isset($_POST['updateInstiReg'])) {
//     $respons = $_POST['updateInstiReg'];
//     $htmlContent = "";
//     $htmlContentHeader = "
//             <div class='table-responsive'>
//                 <table class='table v-middle'>
//                     <thead>
//                         <tr>
//                             <th>Name</th>
//                             <th>Reg Code</th>
//                             <th>Mobile Number</th>
//                             <th>Image</th>
//                             <th>Insti Id</th>
//                             <th>Insti</th>
//                             <th>Status</th>
//                             <th>Action</th>
//                         </tr>
//                     </thead>
//                     <tbody id='table-content-change'>";
//     $htmlContentFooter = "
//                     </tbody>
//                 </table>
//             </div>";
//     $status = "pending";
//     if ($respons == "" || $respons = null) {
//         $sql = "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status,userdata.MobNum,userdata.WhaNum FROM user,userdata JOIN userdata ON userdata.UserId = user.UserId WHERE user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("s", $status);
//     } else {
//         $respons = "%{$respons}%";
//         $sql = "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status,userdata.MobNum,userdata.WhaNum FROM user,userdata JOIN userdata ON userdata.UserId = user.UserId WHERE (user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ) and (user.UserName LIKE ? or user.RegCode LIKE ? or userdata.InstiId LIKE ?)";
//         $stmt = $conn->prepare($sql);
//         $stmt->bind_param("ssss", $status, $respons, $respons, $respons);
//     }
//     // $stmt = $conn->prepare($sql);
//     // $stmt->bind_param("s", $status);
//     $stmt->execute();
//     $reusalt = $stmt->get_result();
//     while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
//         $regcode = $row['RegCode'];
//         $name = $row['UserName'];
//         $InstiName = $row['InstiName'];
//         $InstiId = $row['InstiId'];
//         $InstiPic = $row['InstiPic'];
//         $status1 = $row['Status'];
//         // $type = $row['Type'];
//         // $month = substr($row['Month'], 0, 3) . " " . GetMonthName(substr($row['Month'], 4, 5));
//         $htmlContent .= "
//             <tr>
//                 <td>{$name}</td>
//                 <td>{$regcode}</td>
//                 <td>Mobile : {$row['MobNum']}<br>Whatsapp : {$row['WhaNum']}</td>
//                 <td>
//                     <div class='media-box'>
//                         <img src='../Dachbord/user_images/instiRegImg/{$InstiPic}' class='media-avatar notifiImage' alt='Image not found!' onclick='showImage(this.src)'>
//                     </div>
//                 </td>
//                 <td>{$InstiId}</td>
//                 <td>{$InstiName}</td>
//                 <td>
//                     <!-- <span class='text-green td-status'><i class='bi bi-check-circle'></i> Paid</span> -->
//                     <!-- <span class='text-red td-status'><i class='bi bi-x-circle'></i> Failed</span> -->
//                     <span class='text-blue td-status'><i class='bi bi-clock-history'></i> {$status1}</span>
//                 </td>
//                 <td>
//                     <span class=' btn btn-success p-0 px-2' onclick='aprued(`insti`,`{$InstiId})`'>Aprued</span>
//                     <span class=' btn btn-info p-0 px-2' onclick='Ignored(`insti`,`{$InstiId})`'>Ignored</span>
//                     <!-- <span class='badge shade-blue min-90'>Processing</span> -->
//                 </td>
//             </tr>
//             ";
//     }
//     echo $htmlContentHeader . $htmlContent . $htmlContentFooter;
// }

// update admin notification  table section start

// Ignored start
if (isset($_POST['Ignored'])) {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $today = GetToday('ymdhis');
    if ($type == 'payment') {
        $sql = "SELECT UserId FROM payment WHERE PayId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $StuId = $row['UserId'];
            try {
                $conn->begin_transaction();

                $type = $type . " Ignored";
                $title = "Your Payment Ignored.";
                $Stitle = "Your Payment is Ignored. Place check and try again make a new payment.";
                $sql = "INSERT INTO notification(UserId,OtherId,Type,Title,Dict,Date) VALUES(?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $StuId, $id, $type, $title, $Stitle, $today);
                $stmt->execute();
                $stmt->close();

                $sql = "SELECT Slip FROM payment WHERE PayId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $slip = $row['Slip'];
                    $unlinkParth = "../../Dachbord/user_images/payment/" . $slip;
                    unlink($unlinkParth);
                }

                $sql = "DELETE FROM payment WHERE PayId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $stmt->close();

                $conn->commit();
                echo "success Ignored payment";
            } catch (Exception $e) {
                $conn->rollback();
                echo "error Ignored payment"; // $e->getMessage()
            }
        } else {
            echo "error Ignored payment";
        }
    } elseif ($type == 'insti') {
        $sql = "SELECT UserId FROM user WHERE InstiId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $StuId = $row['UserId'];
            try {
                $conn->begin_transaction();

                $type = $type . " Ignored";
                $title = "Your registation request is Ignored.";
                $Stitle = "Your institute registation request is Ignored. Place check and try again register.";
                $sql = "INSERT INTO notification(UserId,OtherId,Type,Title,Dict,Date) VALUES(?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $StuId, $id, $type, $title, $Stitle, $today);
                $stmt->execute();
                $stmt->close();

                $sql = "SELECT InstiPic FROM userdata WHERE InstiId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $instipic = $row['InstiPic'];
                    $unlinkParth = "../../Dachbord/user_images/instiRegImg/" . $instipic;
                    file_exists($unlinkParth) ? unlink($unlinkParth) : null;
                }
                $null = null;
                $status = 'pending';
                $sql = "UPDATE user,userdata SET user.InstiName = ? , user.InstiId = ? , user.Status = ? , userdata.InstiName = ? , userdata.InstiId = ? , InstiPic = ? WHERE user.UserId = ? and user.UserId = userdata.UserId";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $null, $null, $status, $null, $null, $null, $StuId);
                $stmt->execute();
                $stmt->close();

                $conn->commit();
                echo "success Ignored insti";
            } catch (Exception $e) {
                $conn->rollback();
                echo "error Ignored insti"; // $e->getMessage()
            }
        } else {
            echo "error Ignored insti";
        }
    }
}

if (isset($_POST['aprued'])) {
    $type = $_POST['type'];
    $id = $_POST['id'];
    $today = GetToday('ymdhis');
    if ($type == 'payment') {
        $sql = "SELECT UserId FROM payment WHERE PayId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $stmt->close();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $StuId = $row['UserId'];
            try {
                $conn->begin_transaction();

                $status = 'active';
                $sql = "UPDATE payment SET Status = ? WHERE PayId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $status, $id);
                $stmt->execute();
                $stmt->close();

                $type = $type . " aprued";
                $title = "Your Payment Aprued Successfull.";
                $sql = "INSERT INTO notification(UserId,OtherId,Type,Title,Date) VALUES(?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $StuId, $id, $type, $title, $today);
                $stmt->execute();
                $stmt->close();
                $conn->commit();
                echo "success aprued payment";
            } catch (Exception $e) {
                $conn->rollback();
                echo "error aprued payment"; // $e->getMessage()
            }
        } else {
            echo "error aprued payment";
        }
    } elseif ($type == 'insti') {
        $subquery = "SELECT UserId FROM userdata WHERE InstiId = ?";
        $substmt = $conn->prepare($subquery);
        $substmt->bind_param("s", $id);
        $substmt->execute();
        $subresult = $substmt->get_result();
        $substmt->close();
        if ($subresult->num_rows > 0 && $row = $subresult->fetch_assoc()) {
            $StuId = $row['UserId'];
            try {
                $conn->begin_transaction();

                $status = 'active';
                $sql = "UPDATE user SET Status = ? WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $status, $StuId);
                $stmt->execute();
                $stmt->close();

                $type = $type . " aprued";
                $title = "Your institute registstion Aprued Successfull.";
                $sql = "INSERT INTO notification(UserId,OtherId,Type,Title,Date) VALUES(?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssss", $StuId, $id, $type, $title, $today);
                $stmt->execute();
                $stmt->close();

                $conn->commit();
                echo "success aprued insti";
            } catch (Exception $e) {
                $conn->rollback();
                echo "error aprued insti"; // $e->getMessage()
            }
        } else {
            echo "error aprued insti";
        }
    }
}
// Ignored end

// viewMore start 
if (isset($_POST['viewMore'])) {
    $Type = $_POST['type'];
    $lessonId = $_POST['id'];
    $htmlHeader = "
        <div class='modal-header'>
            <h5 class='modal-title' id='mainModel'>Lesson Details<span class='ModelTitle'></span></h5>
            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
        </div>
        <div class='modal-body'>
            <div class='row'>
                <div class='col-sm-12 col-12 add-lesson'>
                    <!-- Card start -->
                    <div class='card'>
                        <div class='card-body'>
                            <form id='addlesson'>
                                <!-- Row start -->
                                <div class='row item-center'>";
    $htmlFooter  = "
                                </div>
                                <!-- Row end -->
                                <!-- Form actions footer start -->
                                <!-- <div class='form-actions-footer'> -->
                                    <!-- <button id='cansal' class='btn btn-light'>Cancel</button> -->
                                    <!-- <button class='btn btn-success' onclick='submitAddLesson()'>Submit</button> -->
                                <!-- </div> -->
                                <!-- Form actions footer end -->
                            </form>
                        </div>
                    </div>
                    <!-- Card end -->
                </div>
            </div>
        </div>
        <div class='modal-footer'>
            <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
            <button type='button' class='btn btn-success' onclick='updateLessonData({} , `{}`)'>Save changes</button>
        </div>
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show py-2'>Successfull add the lesson!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2'>Failed add the lesson</div>
        </div>";
    if ($Type == "lesson") {

        // lessson icon
        $video = "<i class='bi bi-camera-video text-success'></i>";
        $note  = "<i class='bi bi-file-earmark-text text-success'></i>";
        $quiz = "<i class='bi bi-check2-circle text-success'></i>";
        $classwork = "<i class='bi bi-person-video3 text-success'></i>";
        $upload = "<i class='bi bi-cloud-upload text-success'></i>";
        $upcomming = "<i class='bi bi-clock-history text-success'></i>";

        $sql = "SELECT * FROM lesson,recaccess WHERE lesson.LesId = ? and recaccess.LesId = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $lessonId, $lessonId);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $LesName = $row['LesName'];
            $Dict = $row['Dict'];
            $Month = $row['Month'];
            $Link = $row['Link'];
            $LesId = $row['LesId'];
            $type = $row['Type'];

            if ($type == 'video') {
                $lesindi = $video;
            } elseif ($type == 'note') {
                $lesindi = $note;
            } elseif ($type == 'quiz') {
                $lesindi = $quiz;
            } elseif ($type == 'upload') {
                $lesindi = $upload;
            } elseif ($type == 'upcomming') {
                $lesindi = $upcomming;
            } else {
                $lesindi = $classwork;
            }

            if ($row['Status'] == "active") {
                $statusindi = "<span class='text-green td-status'><i class='fs-6 bi bi-check-circle'></i></span>";
            } else {
                $statusindi = "<span class='text-red td-status'><i class='fs-6 bi bi-x-circle'></i></span>";
            }


            $accessNew = "";
            $groupNew = "";
            $access = explode("][", substr($row['ClassId'], 1, -1));
            $group = explode("][", substr($row['GId'], 1, -1));
            foreach ($access as $value) {
                $sql = "SELECT * FROM class WHERE ClassId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $reusalt1 = $stmt->get_result();
                if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                    $accessNew .= "<p>" . $row1['year'] . " " . $row1['ClassName'] . " " . $row1['InstiName'] . "</p>";
                }
            }
            foreach ($group as $value) {
                $sql = "SELECT MGName FROM grouplist WHERE GId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $value);
                $stmt->execute();
                $reusalt1 = $stmt->get_result();
                if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                    $groupNew .= "<p>" . $row1['MGName'] . "</p>";
                }
            }

            $allShowUser = 0;
            $allRegUsers =  0;
            $actDoneUser = 0;
            $sql = "SELECT ClassId,Month FROM recaccess WHERE recaccess.LesId = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $lessonId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $access = explode("][", substr($row['ClassId'], 1, -1));
                $Month = $row['Month'];

                // get lesson viwes 
                foreach ($access as $value) {
                    $status = "active";
                    $sql = "SELECT SUM(CASE WHEN month = ? and ClassId = ? and Status = ? THEN 1 ELSE 0 END) AS allShowUser, SUM(CASE WHEN ClassId = ? and Status = ? THEN 1 ELSE 0 END) AS allRegUsers FROM payment";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssss", $Month, $value, $status, $value, $status);
                    $stmt->execute();
                    $reusalt = $stmt->get_result();
                    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                        $allShowUser += $row['allShowUser'];
                        $allRegUsers += $row['allRegUsers'];
                    }
                }
                $sql = "SELECT SUM(CASE WHEN OtherId = ? THEN 1 ELSE 0 END)AS doneUser FROM activity";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $lessonId);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $actDoneUser += $row['doneUser'];
                }
                $viweCompleatePrecentage = $allShowUser == 0 ? "Not Registered User" : $actDoneUser / $allShowUser * 100 . "% is Compleated";
                // $viweCompleatePrecentage = $actDoneUser/$allShowUser*100;
            }
            $tableData = "
                <div class='col-12'>
                    <div class='mb-3'>
                        <div class='table-responsive'>
                            <table class='table table-bordered m-0'>
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Desctiption </th>
                                        <th>Access Class</th>
                                        <th>Show Groups</th>
                                        <th>Month</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class='text-center'>{$lesindi}</td>
                                        <td>{$LesId}</td>
                                        <td>{$LesName}</td>
                                        <td>{$Dict}</td>
                                        <td>$accessNew</td>
                                        <td>{$groupNew}</td>
                                        <td>{$Month}</td>
                                        <td class='text-center'>{$statusindi}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>";
            $type != "note" ? $tableData .= "
                <div class='col-auto'>
					<div class='card'>
						<div class='card-header'>
							<div class='card-title'>{$LesName} - Summary</div>
						</div>
						<div class='card-body'>
							<div id='lessonSummary' class='chart-height-xl auto-align-graph'></div>
							<div class='num-stats'>
                                <h6>{$viweCompleatePrecentage}</h6>
							</div>
						</div>
					</div>
				</div>
                <div class='col-auto'>
					<div class='card'>
						<div class='card-header'>
							<div class='card-title'>{$LesName} - Summary</div>
						</div>
						<div class='card-body'>
							<div id='lessonSummary2' class='auto-align-graph'></div>
							<div class='num-stats'>
								<h6 class='text-truncate'>$viweCompleatePrecentage</h6>
							</div>
						</div>
					</div>
				</div>
                " : null;
        } else {
            $tableData = "undefind";
        }
        $htmlContent = $htmlHeader . $tableData . $htmlFooter;
    }
    echo $htmlContent;
}
// viewMore end

// get lessno Viwes start
// if (isset($_POST['getChartVariyable'])) {
//     $type = $_POST['type'];
//     $LessonId = $_POST['id'];

//     if ($type == "lessonViwes") {
//         $allShowUser = 0;
//         $allRegUsers =  0;
//         $actDoneUser = 0;
//         try {
//             $sql = "SELECT ClassId,Month FROM recaccess WHERE recaccess.LesId = ? ";
//             $stmt = $conn->prepare($sql);
//             $stmt->bind_param("s", $LessonId);
//             $stmt->execute();
//             $reusalt = $stmt->get_result();
//             if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
//                 $access = explode("][", substr($row['ClassId'], 1, -1));
//                 $Month = $row['Month'];

//                 // get lesson viwes 
//                 foreach ($access as $value) {
//                     $status = "active";
//                     $sql = "SELECT SUM(CASE WHEN month = ? and ClassId = ? and Status = ? THEN 1 ELSE 0 END) AS allShowUser, SUM(CASE WHEN ClassId = ? and Status = ? THEN 1 ELSE 0 END) AS allRegUsers FROM payment";
//                     $stmt = $conn->prepare($sql);
//                     $stmt->bind_param("sssss", $Month, $value, $status, $value, $status);
//                     $stmt->execute();
//                     $reusalt = $stmt->get_result();
//                     if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
//                         $allShowUser += $row['allShowUser'];
//                         $allRegUsers += $row['allRegUsers'];
//                     }
//                 }
//                 $sql = "SELECT SUM(CASE WHEN OtherId = ? THEN 1 ELSE 0 END)AS doneUser FROM activity";
//                 $stmt = $conn->prepare($sql);
//                 $stmt->bind_param("i", $LessonId);
//                 $stmt->execute();
//                 $reusalt = $stmt->get_result();
//                 if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
//                     $actDoneUser += $row['doneUser'];
//                 }
//             }
//             $pendingUser = $allShowUser - $actDoneUser;
//             // $pendingUser = $allShowUser - $actDoneUser;
//             // $respons = "{chart: {height: 310,type: 'donut',},labels: ['completed Students', 'All Student'],series: [{$actDoneUser},{$pendingUser}],legend: {position: 'bottom',},dataLabels: {enabled: false},stroke: {width: 8,colors: ['#ffffff'],},colors: ['#30ba55', '#ff0000'],tooltip: {y: {formatter: function (val) {return '$' + val}}},}";
//             $response = array(
//                 "lesSummary1" => array(
//                     "chart" => array(
//                         "height" => 270,
//                         "type" => "donut"
//                     ),
//                     "labels" => array("completed", "Not Compleate"),
//                     "series" => array($actDoneUser, $pendingUser),
//                     "legend" => array("position" => "bottom"), // Corrected the structure
//                     "dataLabels" => array("enabled" => false),
//                     "stroke" => array("width" => 3, "colors" => array("#ffffff")),
//                     "colors" => array("#24e398", "#fc4163"),
//                     "tooltip" => array(
//                         "y" => array("formatter" => "function (val) { return '$' + val; }") // Changed the formatter value
//                     )
//                 ),
//                 "lesSummary2" => array(
//                     'chart' => array(
//                         'height' => 235,
//                         'width' => '75%',
//                         'type' => 'bar',
//                         'toolbar' => array(
//                             'show' => false
//                         )
//                     ),
//                     'plotOptions' => array(
//                         'bar' => array(
//                             'horizontal' => false,
//                             'columnWidth' => '60%',
//                             'borderRadius' => 8
//                         )
//                     ),
//                     'dataLabels' => array(
//                         'enabled' => false
//                     ),
//                     'stroke' => array(
//                         'show' => true,
//                         'width' => 0,
//                         'colors' => array('#435EEF')
//                     ),
//                     'series' => array(
//                         array(
//                             'name' => 'user',
//                             'data' => array($allRegUsers, $allRegUsers - $allShowUser, $allShowUser, $actDoneUser)
//                         )
//                     ),
//                     'legend' => array(
//                         'show' => false
//                     ),
//                     'xaxis' => array(
//                         'categories' => array('All Registered', "Not Pay", 'Show', 'Compleate')
//                     ),
//                     'yaxis' => array(
//                         'show' => false
//                     ),
//                     'fill' => array(
//                         'colors' => array('#4267cd')
//                     ),
//                     'grid' => array(
//                         'show' => false,
//                         'xaxis' => array(
//                             'lines' => array(
//                                 'show' => true
//                             )
//                         ),
//                         'yaxis' => array(
//                             'lines' => array(
//                                 'show' => false
//                             )
//                         ),
//                         'padding' => array(
//                             'top' => 0,
//                             'right' => 0,
//                             'bottom' => -10,
//                             'left' => 0
//                         )
//                     ),
//                     'colors' => array('#ffffff')
//                 )
//             );

//             header('Content-Type: application/json');
//             $response = json_encode($response);
//         } catch (Exception $e) {
//             $respons = "undefind";
//         }
//     }
//     echo $response;
// }
// get lessno Viwes end

// notification management start *********************

//  user management start ***************************
if (isset($_POST['loadModelDataStManage'])) {
    $type = $_POST['type'];
    $data = isset($_POST['data']) ? $_POST['data'] : null;
    if ($type == 'regStu' || $type == "viweStuInfo") {
        $modelFooter = "
        </div>";
    } elseif ($type == 'regStuSearch') {
        $modelFooter = "
            <div class='modal-footer pt-3'>
                <button type='button' class='btn btn-dark' onclick='updateModelContent(`regStu`,`{$data}`)' >Back</button>
            </div>
        </div>";
    } else {
        $modelFooter = "
            <div class='modal-footer pt-3'>
                <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
                <button type='button' class='btn btn-success' id='finishIndi' onclick='submitModelPeaper(`{$type}`)'>
                    <div id='finishText'>Finish</div>
                    <div class='spinner-border text-red spinner-w1 d-none' id='finishSnip' role='status'>
				    	<span class='visually-hidden'>Loading...</span>
				    </div>
                </button>
            </div>
        </div>";
    }
    $modelHead = "
        <div class='modal-header'>
           
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'><i class='bi bi-x-lg'></i></button>
            </div>
        <div class='modal-body' id='modelBody'>";
    if ($type == 'regStu') {
        $modelBody = "
            <form id='Formclear'>
                <div class='search-container'>
                    <!-- Search input group start -->
                    <div class='input-group'>
                        <input type='text' class='form-control searchInp' id='searchInp2' style='background-color: #dae0e9;' placeholder='Search User' value='" . (empty($data) ? "" : "{$data}") . "'>
                        <button class='btn' type='button' onclick='search(`UnRegUser`)'>
                            <i class='bi bi-search' id='searchInp2Search'></i>
                            <div class='spinner-border text-red spinner-w1 d-none pe-none' id='searchInp2Snip' role='status'>
								<span class='visually-hidden'>Loading...</span>
							</div>
                        </button>
                    </div>
                    <!-- Search input group end -->
                </div>
            </form>
            <div class='my-3 rusaltLog mx-3'>
                <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the peaper!</div>
                <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the peaper</div>
            </div>";
    } elseif ($type == 'regStuSearch') {
        $tFirst = "
        <div class='table-responsive'>
            <table class='table table-bordered m-0'>
                <thead>";
        $tMiddle = "
        </thead>
        <tbody>";
        $tEnd = "
                </tbody>
            </table>
        </div>";

        if ($data != null) {
            $tHead = "
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>";
            $tBody = "";
            $sql = "SELECT * FROM user WHERE Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $data);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $UnRegUserId = $row['UserId'];
                $tBody .= "
                <tr>
                    <td>{$row['UserName']}</td>
                    <td>{$row['Email']}</td>
                    <td>" . (empty($row['RegCode']) ? "Not Registered" : "Registered") . "</td>
                    <td>
                        <div class='actions item-center'>
                            " . (empty($row['RegCode']) ? "<a onclick='updateModelContent(`UnRegUserReg`,`{$UnRegUserId}`)' id='{$UnRegUserId}'><i class='bi bi-person-plus text-green fs-6'></i></a> " : "<a><i class='bi bi-check-circle text-green fs-6'></i></a>") . "
                        </div>
                    </td>
                </tr>";
            }
            $modelBody = ($reusalt->num_rows > 0) ?  $tFirst . $tHead . $tMiddle . $tBody . $tEnd : $tFirst . $tHead . $tMiddle . "<tr><td colspan='7'  class='text-info text-center'><i class='bi bi-search'>&nbsp;</i>Rusalt Not Found</td></tr>" . $tEnd;
        } else {
            $modelBody = "";
        }
    } elseif ($type == 'UnRegUserReg') {
        $sql = "SELECT * FROM user WHERE UserId = ? ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $data);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $fname = explode(' ', $row['UserName'])[0];
            $lname = explode(' ', $row['UserName'])[1];
            $modelBody = "
            <form id='Formclear'>
                <!-- Row start -->
                <div class='row'>
                    <div class='col-xl-6 col-sm-12 col-12'>
                        <div class='mb-3'>
                            <label for='fname' class='form-label'>First Name *</label>
                            <input name='fname' type='text' class='form-control regstu' id='fname' placeholder='Enter first Name' value='{$fname}'>
                        </div>
                    </div>
                    <div class='col-xl-6 col-sm-12 col-12'>
                        <div class='mb-3'>
                            <label for='lname' class='form-label'>Last Name *</label>
                            <input name='lname' type='text' class='form-control regstu' id='lname' placeholder='Enter last name' value='{$lname}'>
                        </div>
                    </div>
                    <div class='col-xl-6 col-sm-12 col-12'>
                        <div class='mb-3'>
                            <label for='email' class='form-label'>Email</label>
                            <input name='email' type='text' class='form-control regstu' id='email' placeholder='Enter email' value='{$row['Email']}' disabled>
                        </div>
                    </div>
                    <div class='col-xl-6 col-sm-12 col-12'>
                        <div class='mb-3'>
                            <label for='select year' class='form-label'>Select Year</label>
                            <select name='year' id='year' class='form-select regstu'>
                                <option value='' Selected>Select the year</option>
                                <option value='2024'>2024</option>
                                <option value='2025'>2025</option>
                                <option value='2026'>2026</option>
                                <option value='2027'>2027</option>
                                <option value='2028'>2028</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-6'>
                        <div class='mb-3'>
                            <label for='MobNum' class='form-label'>Mobile Number</label>
                            <input name='MobNum' type='number' class='form-control regstu' id='MobNum' placeholder='Enter Mobile Number'>
                        </div>
                    </div>
                    <div class='col-6'>
                        <div class='mb-3'>
                            <label for='WhaNum' class='form-label'>Whatsapp Number</label>
                            <input name='WhaNum' type='number' class='form-control regstu' id='WhaNum' placeholder='Enter Whatsapp Number'>
                        </div>
                    </div>
                    <div class='col-xl-6 col-sm-12 col-12'>
                        <div class='mb-3'>
                            <label for='select InstiName' class='form-label'>Select Institute</label>
                            <select name='InstiName' id='InstiName' class='form-select regstu'>
                                <option value='' Selected>Select the Institute</option>
                                <option value='Ziyowin'>Ziyowin</option>
                                <option value='Online'>Online</option>
                                <option value='Sasip'>Sasip</option>
                                <option value='Susipwan'>Susipwan</option>
                                <option value='Wins'>Wins</option>
                            </select>
                        </div>
                    </div>
                    <div class='col-6'>
                        <div class='mb-3'>
                            <label for='InstiId' class='form-label'>Institute Id</label>
                            <input name='InstiId' type='text' class='form-control regstu' id='InstiId' placeholder = 'Enter Institute Id'>
                        </div>
                    </div>
                    <input type='hidden' name='id' class='regstu' value='{$data}'>
                </div>
                <!-- Row end -->
            </form>
            <div class='my-3 rusaltLog mx-3'>
                <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the peaper!</div>
                <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the peaper</div>
            </div>";
        } else {
            $modelBody =  'invalied';
        }
    } elseif ($type == "viweStuInfo") {
        if (true) {
            $sql = "SELECT user.Status as userStatus, user.InstiId as InstiIdUser, user.*, insti.* FROM user LEFT JOIN insti ON user.InstiName COLLATE utf8mb4_unicode_ci = insti.InstiName COLLATE utf8mb4_unicode_ci WHERE user.UserId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $data);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $UName = $row['UserName'];
                $RegisterCode = $row['RegCode'];
                $dp = $row['Picture'];
                $InstiName = empty($row['InstiName']) ? "Not Registered Institute" : $row['InstiName'];
                $Point = $row['Point'];
                $InstiId = empty($row['InstiName']) ? "Not Registered Institute" : $row['InstiIdUser'];
                $Email = $row['Email'];
                $SubDict = $row['SubDict'];
                $InstiPic = empty($row['InstiPic']) ? "../Dachbord/assets/img/site use/searcing.gif" : "../Dachbord/assets/img/site use/instiimge/" . $row['InstiPic'];
                $Email = $row['Email'];
                $Place = empty($row['InstiPlace']) ? "" : " ( " . $row['InstiPlace'] . " ) ";
                $StatusIndi = $row['userStatus'] == 'active' ? "<p class='text-success'><i class='bi bi-check-circle'></i> active</p>" : ($row['userStatus'] == 'pending' ? "<p class='text-warning'><i class='bi bi-clock-history'></i> Pending activation</p>" : "<p class='text-red'><i class='bi bi-x-circle'></i> Not Registered Institute</p>");
                $StatusIndi = empty($row['InstiName']) ? "" : $StatusIndi;
                $compPrecentage = 30;
            }
        }

        $profileInfo = "
        <div class='row'>
			<div class='col-auto'>
				<div class='card'>
					<div class='card-body product-added-card m-3 row justify-content-center'>
						<div class='col-auto'>
							<img class='product-added-img' width='100' height='100' src='{$dp}' alt='User profile picture' onerror='imgNotFound()'>
						</div>
						<div class='col-auto row'>
							<div class='col-auto m-2'>
								<h5>{$UName}</h5>
								<P class='text-dark'>{$InstiName}</P>
								<p class='text-success'><i class='bi bi-check-circle'></i> active</p>
							</div>
						</div>
						<!-- <div class='col-auto'><h5>Susipwan</h5></div> -->
					</div>
				</div>
			</div>
			<div class='col-auto'>
			</div>
		</div>";


        $rowHed = "<div class='row'>";
        $rowFotter = "</div>";
        $UserInfo = "<div class='col-xxl-4 col-md-6  col-12'>
        <div class='card'>
            <div class='card-header'>
                <div class='card-title w-100'>User Information
                    <hr class='text-dark'>
                </div>
            </div>
            <div class='card-body '>
                <div class='row border border-1 m-1 p-1 pb-3'>
                    <div class=' col-12'>
                        <div class='product-added-card-body'>
                            <p class='text-dark'>User Name</p>
                            <div>
                                <p class='ms-3'>{$UName}</p>
                            </div>
                            <p class='text-dark'>Basic information</p>
                            <div class='row'>
                                <div class='col-12 row m-1'>
                                    <div class='col-4'>
                                        <p> Register Code </p>
                                    </div>
                                    <div class='col-auto'>
                                        <p> - </p>
                                    </div>
                                    <div class='col-auto'>
                                        <p> {$RegisterCode} </p>
                                    </div>
                                </div>
                                <div class='col-12 row m-1'>
                                    <div class='col-4'>
                                        <p> Insti Id </p>
                                    </div>
                                    <div class='col-auto'>
                                        <p> - </p>
                                    </div>
                                    <div class='col-auto'>
                                        <p> {$InstiId} </p>
                                    </div>
                                </div>
                                <div class='col-12 row m-1'>
                                    <div class='col-4'>
                                        <p> Email </p>
                                    </div>
                                    <div class='col-auto'>
                                        <p> - </p>
                                    </div>
                                    <div class='col-auto'>
                                        <p> {$Email} </p>
                                    </div>
                                </div>
                            </div>
                            <div class='d-flex'>
                                <p class='text-dark pe-5'>Account status</p>
                                <img class='' src='../Dachbord/assets/img/site use/pending.gif' width='25' height='25'>
                            </div>
                            <div class='product-added-actions mt-3 item-center'>
                                <button class='btn btn-light remove-from-cart' onclick='loadModelData('editInfo')' data-bs-toggle='modal' data-bs-target='#editInfoModel'>Change information</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>";
        $InstiInfo = "
    <div class='col-xxl-4 col-md-6 col-12'>
        <div class='card'>
            <div class='card-header'>
                <div class='card-title w-100'>Institute Information
                    <hr class='text-dark'>
                </div>
            </div>
            <div class='card-body d-flex row border border-1 m-3'>
                <div class='col-auto'>
                    <img class='product-added-img' width='100' src='{$InstiPic}' alt='User profile picture'>
                </div>
                <div class='col-auto d-flex row'>
                    <div class='col-12 align-self-center'>
                        <h5>'{$InstiName} {$Place}'</h5>
                        <P class='text-dark'>{$SubDict}</P>
                        {$StatusIndi}
                    </div>
                </div>
            </div>
        </div>
    </div>";
        $ClassInfo = "
    <div class='col-xxl-4 col-md-6  col-12'>
        <div class='card'>
            <div class='card-header'>
                <div class='card-title w-100'>Class Information
                    <hr class='text-dark'>
                </div>
            </div>
            <div class='card-body d-flex row m-1'>";
        if (true) {
            $sql = "SELECT class.* FROM user JOIN class ON user.Year = class.Year and user.InstiName = class.InstiName and class.Status = 'active' WHERE user.UserId = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $data);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $classId = $row['ClassId'];
                $className = $row['ClassName'];
                $year = $row['year'];
                $payStatus = null;

                // get payment data 
                $sql = 'SELECT Status FROM payment WHERE UserId = ? and ClassId = ?';
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ii', $data, $classId);
                $stmt->execute();
                $reusalt2 = $stmt->get_result();
                $stmt->close();
                if ($reusalt2->num_rows > 0 && $row2 = $reusalt2->fetch_assoc()) {
                    $payStatus = $row2['Status'];
                }
                $payIndi = $payStatus == 'active' ? "<p class='text-success'><i class='bi bi-check-circle'></i> active</p>" : ($payStatus == 'pending' ? "<p class='text-red'><i class='bi bi-x-circle'></i> Payment pending . . .</p>" : "<p class='text-red'><i class='bi bi-x-circle'></i> Payment pending . . .</p>");
                $ClassInfo .= "
                    <div class='col-12 d-flex row border border-1 m-1 p-1'>
						<div class='col-4'>
							<div class='StylingText01'>
								<!-- <div class='subtitle'></div> -->
								<div class='top'>{$className}</div>
								<div class='bottom' aria-hidden='true'>{$className}</div>
							</div>
						</div>
						<div class='col-8 align-self-center'>
							<h5>{$year} {$className}</h5>
							{$payIndi}
						</div>
					</div>";
            }
            if (!$reusalt->num_rows > 0) {
                $ClassInfo .= "
                        <div class='text-center text-red'>
					    	<h3>Oops!</h3>
					    	Not Registered Institute
					    </div>
                    ";
            }
        }
        $ClassInfo .= "
            </div>
			<!-- <div class='card-body text-center text-red' hidden> -->
			<!-- </div> -->
		</div>
	</div>";

        $modelBody = $profileInfo . $rowHed . $UserInfo . $InstiInfo . $ClassInfo . $rowFotter;
    }
    $modelContent = $modelHead . $modelBody . $modelFooter;
    echo $modelContent;
}

if (isset($_POST['changeUserManageTable'])) {
    $type = $_POST['changeUserManageTable'];
    $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
    $limit = $_POST['limidData'] == 'all' ? "" : " LIMIT " . $_POST['limidData'];
    $tFirst = "
    <div class='table-responsive'>
        <table class='table table-bordered m-0'>
            <thead>";
    $tMiddle = "
    </thead>
    <tbody>";
    $tEnd = "
            </tbody>
        </table>
    </div>";
    if ($type == 'userManage') {
        $tHead = "
        <tr>
            <th>Name</th>
            <th>RegCode</th>
            <th>Email</th>
            <th>Institute</th>
            <th>Institute Id</th>
            <th>Status</th>
            <th>Action</th>
        </tr>";
        $tBody = "";
        $sql = $data != null ? "SELECT * FROM user WHERE ( UserNAme LIKE ? or RegCode LIKE ? or Year LIKE ? or InstiName LIKE ? or Email LIKE ? ) AND RegCode IS NOT NULL $limit" : "SELECT * FROM user WHERE RegCode IS NOT NULL $limit";
        $stmt = $conn->prepare($sql);
        $data != null ? $stmt->bind_param("sssss", $data, $data, $data, $data, $data) : null;
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $tBody .= "
            <tr>
                <td>{$row['UserName']}</td>
                <td>{$row['RegCode']}</td>
                <td>{$row['Email']}</td>
                <td>" . (empty($row['InstiName']) ? "Not Registered" : $row['InstiName']) . "</td>
                <td>" . (empty($row['InstiId']) ? "Not Registered" : $row['InstiId']) . "</td>
                <td>{$row['Status']}</td>
                <td>
                    <div class='actions item-center'>
                        &nbsp;
                        <a onclick='updateModelContent(`viweStuInfo`,this.id)' id='{$row['UserId']}'><i class='bi bi-list text-green fs-6'></i></a>
                    </div>
                </td>
            </tr>";
        }
        $htmlContent = ($reusalt->num_rows > 0) ?  $tFirst . $tHead . $tMiddle . $tBody . $tEnd : $tFirst . $tHead . $tMiddle . "<tr><td colspan='7'  class='text-info text-center'><i class='bi bi-search'>&nbsp;</i>Rusalt Not Found</td></tr>" . $tEnd;
    } elseif ($type == 'regStu') {
        $data = isset($_POST['data']) ? "%" . $_POST['data'] . "%" : null;
        $tFirst = "
            <div class='table-responsive'>
                <table class='table table-bordered m-0'>
                    <thead>";
        $tMiddle = "
            </thead>
            <tbody>";
        $tEnd = "
                    </tbody>
                </table>
            </div>";
        $tHead = "
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Status</th>
                <th>Action</th>
            </tr>";
        $tBody = "";
        $sql_with_data = "SELECT * FROM user WHERE ( UserName LIKE ? or Email LIKE ? ) and RegCode IS NULL $limit";
        $sql_without_data = "SELECT * FROM user WHERE RegCode IS NULL $limit";

        if ($data != null) {
            $stmt = $conn->prepare($sql_with_data);
            $stmt->bind_param("ss", $data, $data);
        } else {
            $stmt = $conn->prepare($sql_without_data);
        }
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $tBody .= "
                <tr>
                    <td>{$row['UserName']}</td>
                    <td>{$row['Email']}</td>
                    <td>Not Regisrered</td>
                    <td> 
                        <div class='actions item-center'>
                            <a onclick='update(this.id,`addmarks`)' id='{$row['UserId']} {$row['UserId']}'><i class='bi bi-plus text-green fs-5'></i></a>
                        </div>
                    </td>
                </tr>";
        }
        if (!$reusalt->num_rows > 0) {
            $tBody = "<tr><td colspan='5' class='text-center'><i class='bi bi-search text-info'></i>&nbsp;Rusalt not found</td></tr>";
        }
        // }
        $htmlContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    }
    echo $htmlContent;
}

if (isset($_POST['add'])) {
    $type = $_POST['type'];
    $data = $_POST['type'];
}

if (isset($_POST['studentManage'])) {
    $type = $_POST['type'];
    if ($type == "UnRegUserReg") {
        try {
            $fname = $_POST['fname'];
            $lname = $_POST['lname'];
            $fullName = $_POST['fname'] . " " . $_POST['lname'];
            $email = $_POST['email'];
            $year = $_POST['year'];
            $MobNum = $_POST['MobNum'];
            $WhaNum = $_POST['WhaNum'];
            $InstiName = $_POST['InstiName'];
            $InstiId = $_POST['InstiId'];
            $id = $_POST['id'];



            $sql = "SELECT UserId FROM user WHERE UserId = ? and Email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $id, $email);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $conn->begin_transaction();

                $regCode = "ICT" . substr($year, -2) . $id; // make reg code
                $fillCount = 10 - strlen($regCode);
                $regCode = substr($regCode, 0, 5) . str_repeat(0, $fillCount) . substr($regCode, 5);

                $sql = "UPDATE user SET RegCode = ? , UserName = ? , InstiName = ? , InstiId = ?, Year = ? WHERE UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssii", $regCode, $fullName, $InstiName, $InstiId, $year, $id);
                $stmt->execute();

                $sql = "INSERT INTO userdata(UserId , RegCode , Fname , Lname , Email , MobNum , WhaNum , Year , InstiName , InstiId) values(?,?,?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("isssssssss", $id, $regCode, $fname, $lname, $email, $MobNum, $WhaNum, $year, $InstiName, $InstiId);
                $stmt->execute();

                $respons = "success";
                $conn->commit();
            } else {
                $respons = "somthing wront";
            }
        } catch (\Throwable $th) {
            $respons = "error " . $th;
        }
    }

    echo $respons;
}
//  user management end ******************************

// profile start *************************************
if (isset($_POST['save'])) {
    $type = $_POST['type'];
    if ($type == 'adminData') {
        try {
            $userName = empty($_POST['userName']) ? null : $_POST['userName'];
            $email = empty($_POST['email']) ? null : $_POST['email'];
            $mobNo = empty($_POST['mobNo']) ? null : $_POST['mobNo'];
            $whaNo = empty($_POST['whaNo']) ? null : $_POST['whaNo'];
            $fileTemp = empty($_FILES['profileImage']) ? null : $_FILES['profileImage']['tmp_name'];

            $sql = "UPDATE adminuser SET UName = ? , Email = ? , MobNum = ? , WhaNum = ?  WHERE AdminId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssi", $userName, $email, $mobNo, $whaNo, $UserId);
            $stmt->execute();
            $stmt->close();

            if (!empty($_FILES['profileImage'])) {
                $image = $UserId . ".jpg";
                $sql = "UPDATE adminuser SET image = ? WHERE AdminId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $image, $UserId);
                $stmt->execute();
                $stmt->close();
                move_uploaded_file($fileTemp, "../assets/img/admin/" . $UserId . ".jpg");
            }
            $respons = "success";
        } catch (\Throwable $e) {
            $respons = "error " . $e;
        }
    } elseif ($type == 'showStu' || $type == 'showAdmin') {
        try {
            $value = empty($_POST['value'])  ? 0 : ($_POST['value'] == 'true' ? 1 : 0);

            $sql = $type == 'showStu' ? "UPDATE adminuser SET ShowStu = ? WHERE AdminId = ?" : "UPDATE adminuser SET ShowAdm = ? WHERE AdminId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $value, $UserId);
            $stmt->execute();
            $stmt->close();
            $respons = 'success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } else {
        $respons = 'undefind';
    }
    echo $respons;
}
// profile end ***************************************

// same ad alol site  stast *****************
if (isset($_POST['enableWith'])) {
    $type = $_POST['type'];
    $data = $_POST['data'];
    $status = "active";
    if ($type == 'insti') {
        try {
            $sql = "UPDATE insti SET Status = ? WHERE InstiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'class') {
        try {
            $sql = "UPDATE class SET Status = ? WHERE ClassId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'group') {
        try {
            $sql = "UPDATE grouplist SET Status = ? WHERE GId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'winner') {
        try {
            $sql = "UPDATE notification SET Status = ? WHERE NotifiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'peaper') {
        try {
            $statusDisable = "disable";
            $conn->begin_transaction();
            $sql = "UPDATE peaper SET Status = ? WHERE Status = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $statusDisable, $status);
            $stmt->execute();

            $sql = "UPDATE peaper SET Status = ? WHERE PeaperId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $conn->commit();
            $respons = 'Success';
        } catch (Exception $e) {
            $conn->rollback();
            $respons = "error";
        }
    } else {
        $respons = "error";
    }
    echo $respons;
}

if (isset($_POST['disableWith'])) {
    $type = $_POST['type'];
    $data = $_POST['data'];
    $status = "disable";
    if ($type == 'insti') {
        try {
            $sql = "UPDATE insti SET Status = ? WHERE InstiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'class') {
        try {
            $sql = "UPDATE class SET Status = ? WHERE ClassId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'group') {
        try {
            $sql = "UPDATE grouplist SET Status = ? WHERE GId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'winner') {
        try {
            $sql = "UPDATE notification SET Status = ? WHERE NotifiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } elseif ($type == 'peaper') {
        try {
            $sql = "UPDATE peaper SET Status = ? WHERE PeaperId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("si", $status, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    } else {
        $respons = "error";
    }
    echo $respons;
}

// update snppet checked table content end

// delete item start 
if (isset($_POST['deleteWith'])) {
    $type = $_POST['type'];
    $data = $_POST['data'];
    if ($type == 'winner') {
        try {
            $conn->begin_transaction();

            $sql = "SELECT Image FROM notification WHERE NotifiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $data);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $row = $reusalt->fetch_assoc();
            $url = "../../Dachbord/user_images/winner/" . $row['Image'];

            file_exists($url) ? unlink($url) : null;

            $sql = "DELETE FROM notification WHERE NotifiId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $data);
            $stmt->execute();

            $conn->commit();
            $respons = 'Success';
        } catch (Exception $e) {
            $conn->rollback();
            $respons = "erro1r" . $e;
        }
    } else {
        $respons = "Undefind";
    }
    echo $respons;
}
// delete item end

// update with start 
if (isset($_POST['updateWith'])) {
    $type = $_POST['type'];
    $id = $_POST['data'];
    if ($type == 'addmarksUpdate') {
        try {
            // $userId = explode(" ", $id)[0];
            // $PeaperId = explode(" ", $id)[1];
            // $marks = $_POST['marks'];

            // $conn->begin_transaction();
            // $sql = "SELECT PeaperId FROM marksofpeaper WHERE UserId = ? and PeaperId = ?";
            // $stmt = $conn->prepare($sql);
            // $stmt->bind_param("ii", $userId, $PeaperId);
            // $stmt->execute();
            // $reusalt = $stmt->get_result();
            // $stmt->close();
            // if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            //     $sql = "UPDATE marksofpeaper SET Marks = ? WHERE UserId = ? and PeaperId = ?";
            //     $stmt = $conn->prepare($sql);
            //     $stmt->bind_param("iii", $marks, $userId, $PeaperId);
            //     $stmt->execute();
            // } else {
            //     $sql = "INSERT INTO marksofpeaper (PeaperId, Type, UserId, Marks) SELECT ?, type, ?, ? FROM peaper WHERE PeaperId = ? ";
            //     $stmt = $conn->prepare($sql);
            //     $stmt->bind_param("ssss", $PeaperId, $userId, $marks, $PeaperId);
            //     $stmt->execute();
            // }
            // $respons = "success";
            // $conn->commit();

            $URGId = explode(" ", $id)[0];
            $peaperId = explode(" ", $id)[1];
            $marks = $_POST['marks'];

            $conn->begin_transaction();

            $sql = "SELECT MOPId FROM marksofpeaper WHERE URGId = ? and PeaperId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $URGId, $peaperId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $thisId = $row['MOPId'];
                $sql = "UPDATE marksofpeaper SET Marks = ? WHERE MOPId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $marks, $thisId);
                $stmt->execute();
            } else {
                $sql = "INSERT INTO marksofpeaper(PeaperId,URGId,Type,Marks) SELECT ?,?,type,? FROM peaper WHERE PeaperId = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $peaperId, $URGId, $marks, $peaperId);
                $stmt->execute();
            }
            $respons = "success";
            $conn->commit();
        } catch (Exception $e) {
            $respons = 'error ' . $e;
            $conn->rollback();
        }
    } elseif ($type == 'addmarksNotRegUpdate') {
        if ($_POST['methord'] == 'insert') {
            try {
                $URGId = explode(" ", $id)[0];
                $PeaperId = explode(" ", $id)[1];
                $name = $_POST['name'];
                $insti = $_POST['insti'];
                $year = $_POST['year'];
                $marks = $_POST['marks'];

                $conn->begin_transaction();

                $sql = "SELECT * FROM unreguser WHERE Name = ? and Year = ? and InstiName = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $name, $year, $insti);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $respons = "alredy added";
                } else {
                    $sql = "INSERT INTO unreguser(Name,InstiName,Year) VALUE(?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sss", $name, $insti, $year);
                    $stmt->execute();
                    $inserted_id = $stmt->insert_id;

                    $sql = "INSERT INTO marksofpeaper (PeaperId, Type, URGId, Marks) SELECT ?, type, ?, ? FROM peaper WHERE PeaperId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $PeaperId, $inserted_id, $marks, $PeaperId);
                    $stmt->execute();
                    $respons = "success";
                }
                $conn->commit();
            } catch (Exception $e) {
                $respons = "error";
            }
        } elseif ($_POST['methord'] == 'update') {
            try {
                $URGId = explode(" ", $id)[0];
                $peaperId = explode(" ", $id)[1];
                $marks = $_POST['marks'];

                $conn->begin_transaction();

                $sql = "SELECT MOPId FROM marksofpeaper WHERE URGId = ? and PeaperId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $URGId, $peaperId);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $thisId = $row['MOPId'];
                    $sql = "UPDATE marksofpeaper SET Marks = ? WHERE MOPId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ss", $marks, $thisId);
                    $stmt->execute();
                } else {
                    $sql = "INSERT INTO marksofpeaper(PeaperId,URGId,Type,Marks) SELECT ?,?,type,? FROM peaper WHERE PeaperId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssss", $peaperId, $URGId, $marks, $peaperId);
                    $stmt->execute();
                }
                $respons = "success";
                $conn->commit();
            } catch (Exception $e) {
                $respons = "error ";
            }
        }
    } else {
        $respons = "invalied type";
    }
    echo $respons;
}
// update with end

// end with start 
if (isset($_POST['endWith'])) {
    $type = $_POST['type'];
    $data = $_POST['data'];
    $status = "finished";
    $today = GetToday('ymd', '-');
    if ($type == 'peaper') {
        try {
            $sql = "UPDATE peaper SET Status = ? , finishDate = ? WHERE PeaperId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssi", $status, $today, $data);
            $stmt->execute();
            $respons = 'Success';
        } catch (Exception $e) {
            $respons = "error";
        }
    }
}
// end with end

// chart section start 
if (isset($_POST['getChartVariyable'])) {
    $type = $_POST['type'];
    $id = $_POST['id'];

    if ($type == "lessonViwes") {
        $allShowUser = 0;
        $allRegUsers =  0;
        $actDoneUser = 0;
        try {
            $sql = "SELECT ClassId,Month FROM recaccess WHERE recaccess.LesId = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $access = explode("][", substr($row['ClassId'], 1, -1));
                $Month = $row['Month'];

                // get lesson viwes 
                foreach ($access as $value) {
                    $status = "active";
                    $sql = "SELECT SUM(CASE WHEN month = ? and ClassId = ? and Status = ? THEN 1 ELSE 0 END) AS allShowUser, SUM(CASE WHEN ClassId = ? and Status = ? THEN 1 ELSE 0 END) AS allRegUsers FROM payment";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sssss", $Month, $value, $status, $value, $status);
                    $stmt->execute();
                    $reusalt = $stmt->get_result();
                    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                        $allShowUser += $row['allShowUser'];
                        $allRegUsers += $row['allRegUsers'];
                    }
                }
                $sql = "SELECT SUM(CASE WHEN OtherId = ? THEN 1 ELSE 0 END)AS doneUser FROM activity";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $actDoneUser += $row['doneUser'];
                }
            }
            $pendingUser = $allShowUser - $actDoneUser;
            // $pendingUser = $allShowUser - $actDoneUser;
            // $respons = "{chart: {height: 310,type: 'donut',},labels: ['completed Students', 'All Student'],series: [{$actDoneUser},{$pendingUser}],legend: {position: 'bottom',},dataLabels: {enabled: false},stroke: {width: 8,colors: ['#ffffff'],},colors: ['#30ba55', '#ff0000'],tooltip: {y: {formatter: function (val) {return '$' + val}}},}";
            $response = array(
                "lesSummary1" => array(
                    "chart" => array(
                        "height" => 270,
                        "type" => "donut"
                    ),
                    "labels" => array("completed", "Not Compleate"),
                    "series" => array($actDoneUser, $pendingUser),
                    "legend" => array("position" => "bottom"), // Corrected the structure
                    "dataLabels" => array("enabled" => false),
                    "stroke" => array("width" => 3, "colors" => array("#ffffff")),
                    "colors" => array("#24e398", "#fc4163"),
                    "tooltip" => array(
                        "y" => array("formatter" => "function (val) { return '$' + val; }") // Changed the formatter value
                    )
                ),
                "lesSummary2" => array(
                    'chart' => array(
                        'height' => 235,
                        'width' => '75%',
                        'type' => 'bar',
                        'toolbar' => array(
                            'show' => false
                        )
                    ),
                    'plotOptions' => array(
                        'bar' => array(
                            'horizontal' => false,
                            'columnWidth' => '60%',
                            'borderRadius' => 8
                        )
                    ),
                    'dataLabels' => array(
                        'enabled' => false
                    ),
                    'stroke' => array(
                        'show' => true,
                        'width' => 0,
                        'colors' => array('#435EEF')
                    ),
                    'series' => array(
                        array(
                            'name' => 'user',
                            'data' => array($allRegUsers, $allRegUsers - $allShowUser, $allShowUser, $actDoneUser)
                        )
                    ),
                    'legend' => array(
                        'show' => false
                    ),
                    'xaxis' => array(
                        'categories' => array('All Registered', "Not Pay", 'Show', 'Compleate')
                    ),
                    'yaxis' => array(
                        'show' => false
                    ),
                    'fill' => array(
                        'colors' => array('#4267cd')
                    ),
                    'grid' => array(
                        'show' => false,
                        'xaxis' => array(
                            'lines' => array(
                                'show' => true
                            )
                        ),
                        'yaxis' => array(
                            'lines' => array(
                                'show' => false
                            )
                        ),
                        'padding' => array(
                            'top' => 0,
                            'right' => 0,
                            'bottom' => -10,
                            'left' => 0
                        )
                    ),
                    'colors' => array('#ffffff')
                )
            );

            header('Content-Type: application/json');
            $response = json_encode($response);
        } catch (Exception $e) {
            $respons = "undefind";
        }
    } elseif ($type == 'activePeaperchart') {
        try {
            $sql = "SELECT SUM(CASE WHEN marksofpeaper.Marks >=75 THEN 1 ELSE 0 END)AS Apass,SUM(CASE WHEN 75 > marksofpeaper.Marks and marksofpeaper.Marks >=65 THEN 1 ELSE 0 END)AS Bpass,SUM(CASE WHEN 65 > marksofpeaper.Marks and marksofpeaper.Marks >=55 THEN 1 ELSE 0 END)AS Cpass,SUM(CASE WHEN 55 > marksofpeaper.Marks and marksofpeaper.Marks >=45 THEN 1 ELSE 0 END)AS Spass,SUM(CASE WHEN 45 > marksofpeaper.Marks THEN 1 ELSE 0 END)AS Fpass,peaper.* FROM peaper,marksofpeaper WHERE peaper.Status = 'active' and peaper.PeaperId = marksofpeaper.PeaperId ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            $row = $reusalt->fetch_assoc();

            $response = array(
                "lesSummary1" => array(
                    'chart' => array(
                        'height' => 400,
                        'width' => '50%',
                        'type' => 'bar',
                        'toolbar' => array(
                            'show' => false
                        )
                    ),
                    'plotOptions' => array(
                        'bar' => array(
                            'horizontal' => false,
                            'columnWidth' => '50%',
                            'borderRadius' => 8
                        )
                    ),
                    'dataLabels' => array(
                        'enabled' => false
                    ),
                    'stroke' => array(
                        'show' => true,
                        'width' => 0,
                        'colors' => array('#435EEF')
                    ),
                    'series' => array(
                        array(
                            'name' => 'user',
                            'data' => array($row['Apass'], $row['Bpass'], $row['Cpass'], $row['Spass'], $row['Fpass'])
                        )
                    ),
                    'legend' => array(
                        'show' => false
                    ),
                    'xaxis' => array(
                        'categories' => array('A', "B", 'C', 'S', 'F')
                    ),
                    'yaxis' => array(
                        'show' => false
                    ),
                    'fill' => array(
                        'colors' => array('#4267cd')
                    ),
                    'grid' => array(
                        'show' => false,
                        'xaxis' => array(
                            'lines' => array(
                                'show' => true
                            )
                        ),
                        'yaxis' => array(
                            'lines' => array(
                                'show' => false
                            )
                        ),
                        'padding' => array(
                            'top' => 0,
                            'right' => 0,
                            'bottom' => -10,
                            'left' => 0
                        )
                    ),
                    'colors' => array('#ffffff')
                )
            );

            header('Content-Type: application/json');
            $response = json_encode($response);
        } catch (\Throwable $th) {
            $respons = "undefind";
        }
    } elseif ($type == 'oldPeaperchart') {
        try {
            $sql = "SELECT SUM(CASE WHEN marksofpeaper.Marks >=75 THEN 1 ELSE 0 END)AS Apass,SUM(CASE WHEN 75 > marksofpeaper.Marks and marksofpeaper.Marks >=65 THEN 1 ELSE 0 END)AS Bpass,SUM(CASE WHEN 65 > marksofpeaper.Marks and marksofpeaper.Marks >=55 THEN 1 ELSE 0 END)AS Cpass,SUM(CASE WHEN 55 > marksofpeaper.Marks and marksofpeaper.Marks >=45 THEN 1 ELSE 0 END)AS Spass,SUM(CASE WHEN 45 > marksofpeaper.Marks THEN 1 ELSE 0 END)AS Fpass,peaper.* FROM peaper,marksofpeaper WHERE peaper.PeaperId = '$id' and peaper.PeaperId = marksofpeaper.PeaperId ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            $row = $reusalt->fetch_assoc();

            $response = array(
                "lesSummary2" => array(
                    'chart' => array(
                        'height' => 400,
                        'width' => '50%',
                        'type' => 'bar',
                        'toolbar' => array(
                            'show' => false
                        )
                    ),
                    'plotOptions' => array(
                        'bar' => array(
                            'horizontal' => false,
                            'columnWidth' => '50%',
                            'borderRadius' => 8
                        )
                    ),
                    'dataLabels' => array(
                        'enabled' => false
                    ),
                    'stroke' => array(
                        'show' => true,
                        'width' => 0,
                        'colors' => array('#435EEF')
                    ),
                    'series' => array(
                        array(
                            'name' => 'user',
                            'data' => array($row['Apass'], $row['Bpass'], $row['Cpass'], $row['Spass'], $row['Fpass'])
                        )
                    ),
                    'legend' => array(
                        'show' => false
                    ),
                    'xaxis' => array(
                        'categories' => array('A', "B", 'C', 'S', 'F')
                    ),
                    'yaxis' => array(
                        'show' => false
                    ),
                    'fill' => array(
                        'colors' => array('#4267cd')
                    ),
                    'grid' => array(
                        'show' => false,
                        'xaxis' => array(
                            'lines' => array(
                                'show' => true
                            )
                        ),
                        'yaxis' => array(
                            'lines' => array(
                                'show' => false
                            )
                        ),
                        'padding' => array(
                            'top' => 0,
                            'right' => 0,
                            'bottom' => -10,
                            'left' => 0
                        )
                    ),
                    'colors' => array('#ffffff')
                )
            );

            header('Content-Type: application/json');
            $response = json_encode($response);
        } catch (\Throwable $th) {
            $respons = "undefind";
        }
    }
    echo $response;
}
// chart section end 

// same ad alol site  end *****************
function WriteFile($value)
{
    $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $value);
    fclose($myfile);
}
