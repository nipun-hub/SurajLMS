<?php

// database connection
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
    $hiddenStatus = !$adminType[0] == 'owner' || $adminType[0] == 'editor' ? null : 'hidden';
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
            $startTime = $data[1];
            $endTime = $data[2];

            $conn->begin_transaction();
            $sql = "UPDATE class SET Conducting = 0 , Dict = NULL";
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $dataNew = implode("-", $data);
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
        <table class='table v-middle'>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Dict</th>
                    <th>Type</th>
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
        $sql = "SELECT * FROM lesson";
        $stmt = $conn->prepare($sql);
    } else {
        $search = "%" . $search . "%";
        $sql = "SELECT * FROM lesson WHERE LesName LIKE ? or Type LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $search, $search);
    }
    $stmt->execute();
    $reusalt = $stmt->get_result();
    while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        $LesId = $row['LesId'];
        $name = $row['LesName'];
        $dict = $row['Dict'];
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
        $tableBodyContent .= "
        <tr>
            <td>{$name}</td>
            <td>{$dict}</td>
            <td>{$type}</td>
            <td>{$InsertDate}</td>
            <td>{$statusindi}</td>
            <td class='item-center' {$hiddenStatus}>
                <div class='actions'>
					<a onclick='update(this.id)' id='{$LesId} {$type}'><i class='bi bi-pencil-square text-green'></i></a>
                    <i class='d-none' id='clickupdateLesson' data-bs-toggle='modal' data-bs-target='#updateLesson'></i>
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
            $sql = "SELECT * FROM lesson,recaccess WHERE lesson.LesId = ? and recaccess.LesId = ?  ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $LesId, $LesId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $LesName = $row['LesName'];
                $Dict = $row['Dict'];
                $Type = $row['Type'];
                $Link = $row['Link'];

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
            } else {
                $$tableData = "undefind";
            }
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
                <div class='col-xl-6 col-sm-12 col-12'>
                    <div class='mb-3 tags group3'>
                        <label class='form-label d-flex'>Select show in group</label>
                        <select id='group3' class='select-multiple js-states form-control' title='Select Product Category' multiple='multiple'>
                            <option>111111111111111111111111111111111111111111111111</option>
                        </select>
                        <div class='invalid-feedback'>Please Select the Group</div>
                        <div class='valid-feedback'>Done!</div>
                    </div>
                </div>
                <div class='col-xl-6 col-sm-12 col-12'>
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
        }
        $htmlContent = $htmlHeader . $tableData . $htmlcontentForm . $htmlFooter;
    } catch (Exception $e) {
        $htmlContent = "undefined";
    }
    echo $htmlContent;
}

if (isset($_POST['updateLessonData'])) {
    try {
        $lessonId = $_POST['val1'];

        $lestype = $_POST['lestype'];
        $lesdict = $_POST['lesdict'];

        $lesName = ($lestype != 'quiz') ? $_POST['lesname'] : null;
        // $leslink = ($_POST['AddLessonData'] == 1) ? $_POST['leslink'] : null;
        $leslink = $_POST['leslink'];

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

        $sql = "SELECT * FROM lesson WHERE Link = ? and Type = 'video' and LesId != '$lessonId' ";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $LesLink);
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

                $sql2 = "UPDATE recaccess SET ClassId = ? , GId = ? WHERE LesId = ?";
                $stmt = $conn->prepare($sql2);
                $stmt->bind_param("ssi", $classNew, $groupNew, $lessonId);
                $stmt->execute();
                $conn->commit();

                $respons = "successfull";
            } catch (Exception $e) {
                $respons = "error";
                $conn->rollback();
            }
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
        $HideFrom = $_POST['HideList'];
        $fileName = GetToday('ymdhis') . ".jpg";
        if ($HideFrom == "") {
            $hideList = null;
        } else {
            $hideList = "";
            $listinhide = explode(",", $HideFrom);
            foreach ($listinhide as $value) {
                $classdata = explode("-", $value);
                $sql = "SELECT ClassId FROM class WHERE `ClassName` =  ? and `InstiName` = ? and `year` = ? ";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $classdata[1], $classdata[2], $classdata[0]);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $classId = $row['ClassId'];
                    $hideList .= "[{$classId}]";
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
                $sql1 = "INSERT INTO grouplist(MGName,MGImage,HideFrom) VALUE(?,?,?)";
                $stmt = $conn->prepare($sql1);
                $stmt->bind_param("sss", $groupName, $fileName, $hideList);
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

// model html Content data load start 

if (isset($_POST['loadModelDataInsert'])) {
    $type = $_POST['Type'];
    $modelFooter = "
    <div class='modal-footer pt-3'>
        <button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
        <button type='button' class='btn btn-success' onclick='submitModelSnippet(`insert`,`{$type}`)'>Finish</button>
    </div>";
    if ($type == 'insti') {
        $modelHead = "
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the inatitute!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the inatitute</div>
        </div>
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
        </form>";
    } elseif ($type == 'class') {
        $modelHead = "
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the Class!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the Class</div>
        </div>
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
        </form>";
    } elseif ($type == 'group') {
        $modelHead = "
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the group!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the group</div>
        </div>
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
                    <div class='mb-3 tags w-100 grouphideclass'>
                        <label class='form-label d-flex'>Select Hide Class *</label>
                        <select name='hidefrom' id='grouphide' class='is-valid select-multiple js-states form-control w-100' title='Select Product Category' multiple='multiple'>
                            <option>111111111111111111111111111111111111111111111111111</option>
                        </select>
                    </div>
                </div>
            </div>
            <!-- Row end -->
        </form>";
    } elseif ($type == 'winner') {
        $modelHead = "
        <div class='my-3 rusaltLog mx-3'>
            <div class='valid-feedback alert alert-success text-center alert-dismissible fade show'>Successfull add the winning student!</div>
            <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show'>Failed add the winning student</div>
        </div>
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
        </form>";
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
            <th>Hide From</th>
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
            if ($row['HideFrom'] == !null) {
                $HideFrom = "";
                $InstiIdList = explode("][", substr($row['HideFrom'], 1, -1));
                foreach ($InstiIdList as $value) {
                    $sql = "SELECT InstiName FROM insti WHERE InstiId = ? ";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $value);
                    $stmt->execute();
                    $reusalt1 = $stmt->get_result();
                    if ($reusalt1->num_rows > 0 && $row1 = $reusalt1->fetch_assoc()) {
                        $HideFrom .= $row1['InstiName'] . "br";
                    }
                }
            } else {
                $HideFrom = "Not Hide Institute";
            }
            $tBody .= "
            <tr>
                <td><img src='../Dachbord/assets/img/site use/group/{$row['MGImage']}' width='50' onclick='showImage(this.src)' alt='Image not found!'></td>
                <td>{$row['MGName']}</td>
                <td>{$HideFrom}</td>
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
                    &nbsp;
                    <a onclick='update(this.id,`winner`)' id='{$NotifiId}'><i class='bi bi-pencil-square text-green fs-6'></i></a>
                </div>
                </td>
            </tr>";
        }
        $htmlContent = $tFirst . $tHead . $tMiddle . $tBody . $tEnd;
    } else {
    }
    echo $htmlContent;
}

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
    } else {
        $respons = "error";
    }
    echo $respons;
}

// update snppet checked table content end

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
        // if ($response == "") {
        //     $sql = "SELECT user.RegCode,user.UserName,class.*,payment.* FROM payment,user,class WHERE payment.status = ? and payment.UserId = user.UserId and payment.ClassId = class.ClassId";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("s", $status);
        // } else {
        //     $response = "%{$response}%";
        //     $sql = "SELECT user.RegCode,user.UserName,class.*,payment.* FROM payment,user,class WHERE ( payment.status = ? and payment.UserId = user.UserId and payment.ClassId = class.ClassId ) and (user.RegCode LIKe ? or payment.Name LIKE ? or class.ClassName LIKE ? or class.year LIKE ? or class.InstiName LIKE ?)";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("ssssss", $status, $response, $response, $response, $response, $response);
        // }
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $payId = $row['PayId'];
            // $regcode = $row['RegCode'];
            // $price = $row['Price'];
            // $name = $row['UserName'];
            // $type = $row['Type'];
            // $year = $row['year'];
            // $ClassName = $row['ClassName'];
            // $InstiName = $row['InstiName'];
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
        // if ($respons == "" || $respons = null) {
        //     $sql = "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status FROM user,userdata WHERE user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("s", $status);
        // } else {
        //     $respons = "%{$respons}%";
        //     $sql = "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status FROM user,userdata WHERE (user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ) and (user.UserName LIKE ? or user.RegCode LIKE ? or userdata.InstiId LIKE ?)";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("ssss", $status, $respons, $respons, $respons);
        // }
        // $stmt = $conn->prepare($sql);
        // $stmt->bind_param("s", $status);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            // $regcode = $row['RegCode'];
            // $name = $row['UserName'];
            // $InstiName = $row['InstiName'];
            $InstiId = $row['InstiId'];
            $InstiPic = $row['InstiPic'];
            // $status1 = $row['Status'];
            // $type = $row['Type'];
            // $month = substr($row['Month'], 0, 3) . " " . GetMonthName(substr($row['Month'], 4, 5));
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
        // if ($respons == "" || $respons = null) {
        //     $sql = "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status FROM user,userdata WHERE user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("s", $status);
        // } else {
        //     $respons = "%{$respons}%";
        //     $sql = "SELECT user.RegCode,user.UserName,user.InstiName,user.InstiId,userdata.InstiPic,user.Status FROM user,userdata WHERE (user.InstiName IS NOT NULL and user.status = ? and user.UserId = userdata.UserId ) and (user.UserName LIKE ? or user.RegCode LIKE ? or userdata.InstiId LIKE ?)";
        //     $stmt = $conn->prepare($sql);
        //     $stmt->bind_param("ssss", $status, $respons, $respons, $respons);
        // }
        // $stmt = $conn->prepare($sql);
        // $stmt->bind_param("s", $status);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
            $adminId = $row['AdminId'];
            // $status1 = $row['Status'];
            // $type = $row['Type'];
            // $month = substr($row['Month'], 0, 3) . " " . GetMonthName(substr($row['Month'], 4, 5));
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

// notification management start ********




// $conn->close();



// file write 
// $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
// fwrite($myfile, $key);
// fclose($myfile);