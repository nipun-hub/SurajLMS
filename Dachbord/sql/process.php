<?php

// database connection
try {
    include('conn.php');
} catch (EXception $e) {
    echo "Connection Error";
}

if (isset($_SESSION['login'])) {
    $UserId = $_SESSION['login'];
} else {
    $UserId = 'null';
    echo "error loading";
}

try {
    include('function.php');
} catch (Exception $e) {
    echo " error loading";
}

try {

    // grt group id
    if (isset($_POST['gettype'])) {
        if (isset(explode("-", $_SESSION['clz'])[2])) {
            echo explode("-", $_SESSION['clz'])[2];
        } else {
            echo "undefind";
        }
    }

    // grt type
    if (isset($_POST['getGid'])) {
        if (isset(explode("-", $_SESSION['clz'])[3])) {
            echo explode("-", $_SESSION['clz'])[3];
        } else {
            echo "undefind";
        }
    }

    if (isset($_POST['checkGid'])) {
        if (isset(explode("-", $_SESSION['clz'])[3])) {
            echo explode("-", $_SESSION['clz'])[2];
        } else {
            echo "normal";
        }
    }

    // get class name
    if (isset($_POST['getactiveClass'])) {
        if (isset($_SESSION['clz'])) {
            $monthName = isset($_POST['month']) ? substr($_POST['month'], 4) : null;
            $activeClass = explode("-", $_SESSION['clz'])[1];
            $sql = "SELECT * FROM class WHERE ClassId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $activeClass);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $classHeader = $row['year'] . "-" . $row['ClassName'] . " in " . $row['InstiName'];
                $data = array(
                    "status" => "done",
                    "month" => GetMonthName($monthName),
                    "type" => $row['Type'],
                    "price" => $row['Price'],
                    "classHeader" => $classHeader,
                );
            } else {
                $data = array(
                    "status" => "undefind",
                );
            }
        } else {
            $data = array(
                "status" => "undefind",
            );
        }
        $dataEncode =  json_encode($data);
        header('Content-Type: application/json');
        echo $dataEncode;
    }

    // login insti start

    if (isset($_POST['ClassLogin'])) {
        $classId = $_POST['id'];

        // check corret regcode
        $sql = "SELECT class.InstiName , class.ClassId FROM class WHERE ClassId = '$classId'";
        $stmt = $conn->prepare($sql);
        // $stmt->bind_param("s", $regcode);
        $stmt->execute();
        $rusalt = $stmt->get_result();
        if ($row = $rusalt->fetch_assoc()) {
            $_SESSION['clz'] = $row['InstiName'] . "-" . $row['ClassId'] . "-" . "lesson";
            echo "success";
        } else {
            echo "invalid";
        }
    }

    // login insti end

    // register insti user start

    if (isset($_POST['insti_register'])) {
        try {
            $instiName = $_POST['instiName'];
            $instiId = $_POST['instiId'];
            if (isset($_FILES['file'])) {
                $fileTmpName = $_FILES['file']['tmp_name'];

                $sql = "SELECT UserId FROM user WHERE InstiId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $instiId);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows < 1) {
                    try {
                        $fileName = str_replace("/", "", $instiId) . "_" . $UserId . ".jpg";
                        $targetFile = "../user_images/instiRegImg/" . $fileName;

                        $conn->begin_transaction();
                        $sql = "UPDATE userdata SET InstiName = ? , InstiId = ? , InstiPic = ? WHERE UserId = ? ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssss", $instiName, $instiId, $fileName, $UserId);
                        $stmt->execute();

                        $sql = "UPDATE user SET InstiId = ? , InstiName = ? WHERE UserId = ? ";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sss", $instiId, $instiName, $UserId);
                        $stmt->execute();
                        $conn->commit();

                        move_uploaded_file($fileTmpName, $targetFile);
                        echo "success";
                    } catch (Exception $e) {
                        $conn->rollback();
                        echo 'upload Error';
                    }
                } else {
                    echo "alredy register";
                }
            } else {
                echo "load Error";
            }
        } catch (Exception $e) {
            echo "load Error";
        }
        $stmt->close();
    }

    // register insti user end

    // ####################################### Lesson.php start ##############

    // update PageControles start
    if (isset($_POST['updatePageControles'])) {
        try {
            $lesson = explode('-', $_SESSION['clz'])[2] == 'lesson' ? 'checked' : '';
            $month = explode('-', $_SESSION['clz'])[2] == 'month' ? 'checked' : '';
            // $_SESSION['clz'] = explode('-', $_SESSION['clz'])[0]."-".explode('-', $_SESSION['clz'])[1]."-".explode('-', $_SESSION['clz'])[2];

            $htmlContent = "
            <div class='sub-nav'>
		    	<div class='sub-nav-body' id='sub-nav-body'>
		    		<div class='radio-btn'>
		    			<input type='radio' name='sub_nav' onchange='changeContent(`lesson`)' id='sub_nav-1' value='lessons' {$lesson}>
		    			<input type='radio' name='sub_nav' onchange='changeContent(`month`)' id='sub_nav-2' value='month' {$month}>
		    			<div class='ul'>
		    				<label class='text-overflow' for='sub_nav-1'>Lesson</label>
		    				<label class='text-overflow' for='sub_nav-2'>Month</label>
		    			</div>
		    		</div>
		    	</div>
		    </div>";
        } catch (Exception $e) {
            $htmlContent = "Undefined Content";
        }
        echo $htmlContent;
    }
    // update PageControles end

    // Page contrallers ui change start
    if (isset($_POST['changePageControles'])) {
        try {
            mysqli_set_charset($conn, "utf8mb4");
            $subType = $_POST['changePageControles'];
            if ($subType == 'lesevent') {
                $data = $_POST['data'];
                $activeClaId = explode("-", $_SESSION['clz'])[1];
                $id = explode("-", $_SESSION['clz'])[3];
                if (explode("-", $_SESSION['clz'])[2] == 'month') {
                    mysqli_set_charset($conn, "utf8mb4");
                    $sql = "SELECT LesName FROM lesson WHERE  LesId = '$data' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                        $Title = $row['LesName'];
                        $STitle = substr($id, 0, 4) . " " . GetMonthName(substr($id, 4));
                    } else {
                        $Title = '';
                        $STitle = '';
                    }
                } else {
                    mysqli_set_charset($conn, "utf8mb4");
                    $sql = "SELECT grouplist.MGName,lesson.LesName FROM lesson,grouplist WHERE grouplist.GId = '$id' and lesson.LesId = '$data' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                        $Title = $row['LesName'];
                        $STitle = $row['MGName'];
                    } else {
                        $Title = '';
                        $STitle = '';
                    }
                }
                $htmlContent = "
                <div class='sub-nav'>
                    <div class='sub-nav-body' id='sub-nav-body'>
                        <div class='radio-btn'>
                            <div class='ul'>
                                <label onclick='backtoclass({$id},2)' class='bg-red text-white d-flex' for='sub_nav-1'>{$STitle}<i class=' bi bi-caret-right'></i> </label>
                                <label class='ul-dict' for='sub_nav-1'>{$Title}</label>
                            </div>
                        </div>
		            </div>
		        </div>";
            } else {
                $type = isset(explode("-", $_SESSION['clz'])[2]) ? explode("-", $_SESSION['clz'])[2] : null;
                $data = $_POST['data'];
                $activeClaId = explode("-", $_SESSION['clz'])[1];
                // $lesson = explode('-', $_SESSION['clz'])[2] == 'lesson' ? 'checked' : '';
                // $month = explode('-', $_SESSION['clz'])[2] == 'month' ? 'checked' : '';
                if ($type == 'lesson') {
                    $Gid = ($data == '' || $data == null || $data == 'null') ? explode("-", $_SESSION['clz'])[3] : $data;
                    $_SESSION['clz'] = $_SESSION['clz'] . "-" . $Gid;

                    $sql = "SELECT grouplist.MGName,class.ClassName FROM grouplist JOIN class ON ClassId = '$activeClaId' WHERE grouplist.GId = '$Gid' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                        $MGName = $row['MGName'];
                        $ClassName = $row['ClassName'];
                    } else {
                        $MGName = '';
                        $ClassName = '';
                    }
                    $htmlContent = "
                <div class='sub-nav'>
                    <div class='sub-nav-body' id='sub-nav-body'>
                        <div class='radio-btn'>
                            <div class='ul'>
                                <label onclick='backtoclass({$activeClaId})' class='bg-red text-white d-flex' for='sub_nav-1'>{$ClassName}<i class=' bi bi-caret-right'></i> </label>
                                <label class='ul-dict' for='sub_nav-1'>{$MGName}</label>
                            </div>
                        </div>
		            </div>
		        </div>";
                } elseif ($type == 'month') {
                    $data = isset($_POST['data']) || $_POST['data'] != "undefined" ? $_POST['data'] : explode("-", $_SESSION['clz'])[3];
                    $_SESSION['clz'] = $_SESSION['clz'] . "-" . $data;
                    $data = explode("-", $_SESSION['clz'])[3];
                    $sql = "SELECT class.ClassName FROM class WHERE ClassId = '$activeClaId' ";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
                        $ClassName = $row['ClassName'];
                    } else {
                        $ClassName = '';
                    }
                    $Month = substr($data, 0, 4) . " " . GetMonthName(substr($data, 4));
                    $htmlContent = "
                <div class='sub-nav'>
                    <div class='sub-nav-body' id='sub-nav-body'>
                        <div class='radio-btn'>
                            <div class='ul'>
                                <label onclick='backtoclass({$activeClaId})' class='bg-red text-white d-flex' for='sub_nav-1'>{$ClassName}<i class=' bi bi-caret-right'></i> </label>
                                <label class='ul-dict' for='sub_nav-1'>{$Month}</label>
                            </div>
                        </div>
		            </div>
		        </div>";
                }
            }
        } catch (Exception $e) {
            $htmlContent = "Undefind Content";
        }
        echo $htmlContent;
    }
    // Page contrallers ui change end

    // Update Payment Status start
    if (isset($_POST['updatePaymentStatus'])) {
        try {
            $activeClaId = explode("-", $_SESSION['clz'])[1];
            $data = $_POST['updatePaymentStatus'];
            // recoding Request
            // $RrActive = "<span class='alert alert-success'>Active</span>";
            // $RrDeactive = "<span class='alert alert-danger'>Desabled</span>";
            // $RrBtnACtive = "<p class='btn btn-info p-0 px-5 text-center'>Add + </p>";
            // $RrBtnDeaCtive = "<p>Recoding Request id desabled</p>";
            // payment
            $thisMonth =  GetToday('ym');
            $thisMonth = $data == 'undefined' ? GetToday('ym') : $data;
            $today =  GetToday('ymd');
            $payActive = "<span>Payment Successfull</span>";
            $payDeactive = "<p class='btn btn-info p-0 px-5' onclick = 'nthj(5,{$thisMonth})'>Pay Now</p>";
            $payPending = "<span>Please wait the until approved</span>";
            $payindiBtnActive = "<span class='alert alert-success'>Active</span>";
            $payindiDeactive = "<span class='alert alert-danger'>Deactive</span>";
            $payindiBtnPending = "<span class='alert alert-warning'>Pending</span>";
            $PayImageActive = "<img height='40' width='30' src='assets/img/site use/pay.png' >";
            $PayImageDisline = "<img height='40' width='30' src='assets/img/site use/unpay.png' >";

            $sql = "SELECT PayId,Status  FROM payment WHERE `UserId` = ? and `ClassId` = ? and `Month` = ? ";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $UserId, $activeClaId, $thisMonth);
            $stmt->execute();
            $result = $stmt->get_result();
            $paymrntStatus = "";
            if ($result->num_rows > 0) {
                if ($row = $result->fetch_assoc()) {
                    $paymrntStatus = $row['Status'];
                    if ($paymrntStatus == 'active') {
                        // $Rr = $RrActive;
                        // $RrBtn = $RrBtnACtive;
                        $PayStatus = $payActive;
                        $payBtn = $payindiBtnActive;
                        $PayImg = $PayImageActive;
                    } elseif ($paymrntStatus == 'pending') {
                        // $RrBtn = $RrBtnDeaCtive;
                        // $Rr = $RrDeactive;
                        $PayStatus = $payPending;
                        $payBtn = $payindiBtnPending;
                        $PayImg = $PayImageDisline;
                    } elseif ($paymrntStatus == 'disline') {
                        // $Rr = $RrDeactive;
                        // $RrBtn = $RrBtnDeaCtive;
                        $PayStatus = $payDeactive;
                        $payBtn = $payindiDeactive;
                        $PayImg = $PayImageDisline;
                    }
                }
            } else {
                // $Rr = $RrDeactive;
                // $RrBtn = $RrBtnDeaCtive;
                $PayStatus = $payDeactive;
                $payBtn = $payindiDeactive;
                $PayImg = $PayImageDisline;
            }
            // $Rr = "";
            // $RrBtn = "";

            // check in progress class and viwe it
            if (true) {
                $sql = "SELECT * FROM class WHERE Conducting = 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $className = "{$row['year']} {$row['ClassName']} {$row['InstiName']}";
                    $decodeDict = json_decode($row['Dict']);
                    $youtubeLink = empty($decodeDict[3]) ? null : $decodeDict[3];
                    $zoomlink = empty($decodeDict[4]) ? null : $decodeDict[4];
                    $dict = empty($decodeDict[5]) ? $className : $decodeDict[5];
                    if ($paymrntStatus == "active") {
                        $zoombtn = "<a href='{$zoomlink}'><span class='btn btn-success'>Join</span></a>";
                        $youBtn = "<a href='{$youtubeLink}'><span class='btn btn-success'>Join</span></a>";
                    } else {
                        $zoombtn = "";
                        $youBtn = "";
                    }

                    $classindiphy = empty($youtubeLink) && empty($zoomlink) ? "
                    <div class='col-12 cardHeaderMain'>
		    	        	<div class=' cardHeaderContent w-100 one item-center'>
		    	        		<img class='text-red' height='50' width='50' src='assets/img/site use/classphy.png' alt=''>
		    	        		<div class='headerContentBody w-100'>
		    	        			<span class='alert alert-success w-100 d-block w-auto'>Now Class in progress</span>
		    	        			<span>{$className}</span>
		    	        		</div>
                                <img class='text-red' height='40' width='40' src='assets/img/site use/pending.gif' alt=''>
		    	        	</div>
		    	        </div>" : null;

                    $classindiyou = !empty($youtubeLink) ? "
                        <div class='col-12 cardHeaderMain'>
		    	        	<div class=' cardHeaderContent w-100 one item-center'>
		    	        		<img class='text-red' height='50' width='50' src='assets/img/site use/youtube.png' alt=''>
		    	        		<div class='headerContentBody w-100'>
		    	        			<span class='alert alert-success w-100 d-block w-auto'>Now Class in progress</span>
		    	        			<span>{$className}</span>
		    	        		</div>
                                <img class='text-red' height='40' width='40' src='assets/img/site use/pending.gif' alt=''>
		    	        		{$youBtn}
		    	        	</div>
		    	        </div>" : null;

                    $classindizoom =  !empty($zoomlink) ? "
                        <div class='col-12 cardHeaderMain item-center'>
		    	        	<div class=' cardHeaderContent w-100 one item-center'>
                                <img class='text-red' height='50' width='50' src='assets/img/site use/zoom.png' alt=''>
                                <div class='headerContentBody w-100'>
                                    <span class='alert alert-success w-100 d-block w-auto'>Now Class in progress</span>
                                    {$PayStatus}
                                </div>
                                <img class='text-red' height='40' width='40' src='assets/img/site use/pending.gif' alt=''>
		    	        		{$zoombtn}
		    	        	</div>
		    	        </div>" : null;
                } else {
                    $classindiphy = null;
                    $classindizoom = null;
                    $classindiyou = null;
                }
            }

            $htmlContent = " 
            <div class='col-12 mainGroupOptions'>
                <div class='card'>
                    <div class='card-body cardHeder row'>
		    	        <div class='col-12 cardHeaderMain item-center'>
		    	        	<div class=' cardHeaderContent w-100 two item-center'>
		    	        		{$PayImg}
		    	        		<div class='headerContentBody w-100'>
		    	        			<h4><span class='fs-6'>Account status</span></h4>
		    	        			{$PayStatus}
		    	        		</div>
		    	        		{$payBtn}
		    	        	</div>
		                </div>
                        {$classindiyou}
                        {$classindizoom}
                        {$classindiphy}
                    </div>
                </div>
            </div>";
        } catch (Exception $e) {
            $htmlContent = "Undefined Content";
        }
        echo $htmlContent;
    }
    // Update Payment Status end

    // get grouplist start

    if (isset($_POST['updatemainCardContent'])) {
        try {
            mysqli_set_charset($conn, "utf8mb4");
            $maintype = $_POST['updatemainCardContent'] == 'null' || $_POST['updatemainCardContent'] == null ? explode("-", $_SESSION['clz'])[2] : $_POST['updatemainCardContent'];
            $insti = explode("-", $_SESSION['clz'])[0];
            $activeClaId = explode("-", $_SESSION['clz'])[1];
            // $maintype = explode("-", $_SESSION['clz'])[2];
            $_SESSION['clz'] = $insti . "-" . $activeClaId . "-" . $maintype;

            // alredy dehind variyable
            $htmlContent = "";
            $lock = "lock bi bi-lock";
            $unlock = "bi bi-unlock";
            $complete = "<span class='green'><i class='bi bi-check-circle'></i></i>&nbsp;Complete</span>";
            $active = "<span class='green'><i class='bi bi-check-circle'></i></i>&nbsp;Active</span>";
            $nonecomplete = "<span class='blue'>&nbsp;Incomplete</span>";
            $notply = "<span class='red'><i class='bi bi-lock'></i>&nbsp;NotPay</span>";
            $Restricted = "<span class='red'><i class='bi bi-lock'></i>&nbsp;Restricted</span>";
            $pending = "<span class='orange'><i class='bi bi-lock'></i>&nbsp;Pending</span>";
            $success = "<span class='green'><i class='bi bi-unlock'></i>&nbsp;Access available</span>";
            $lockbtn = "<i class='fs-6 bi bi-lock me-2'></i>Restricted";
            $unlockbtn = "Asign";

            if (true) {
                mysqli_set_charset($conn, "utf8mb4");
                // $type = $_POST['type'];
                // $type =  ($type == 'undefined') ? (explode("-", $_SESSION['clz'])[2] == 'lesson' ? 'clickGroup' : 'clickMonth') : $type;
                // $getData = $_POST['data'];
                // $activeClaId = explode("-", $_SESSION['clz'])[1];
                // $Insti =  explode("-", $_SESSION['clz'])[0];
                // $data = isset(explode("-", $_SESSION['clz'])[3]) ? explode("-", $_SESSION['clz'])[3] : ($getData == 'null' || $getData == null ? null : $getData);

                // $htmlContentBodyFirst = ""; //paymentindi($conn, $UserId);

                // lessson data
                $video = "<i class='bi bi-camera-video text-success'></i>";
                $note  = "<i class='bi bi-file-earmark-text text-success'></i>";
                $quiz = "<i class='bi bi-check2-circle text-success'></i>";
                $classwork = "<i class='bi bi-person-video3 text-success'></i>";
                $upload = "<i class='bi bi-cloud-upload text-success'></i>";
                $upcomming = "<i class='bi bi-clock-history text-success'></i>";

                $paymentnotpay = "";
                $paymentPending = "<span class='alert alert-warning p-0 px-2'>Payment Pending&nbsp;<i class='bi bi-lock'></i></span>";
                $Compleate = "<span class='alert alert-success p-0 px-2'>Complete&nbsp;<i class='bi bi-check2-circle'></i></span>";
                $NonCompleate = "<span class='alert alert-info p-o px-2'>None complete&nbsp;</span>";
                $upcommingindi = "<span class='alert alert-danger p-0 px-2' >Upcomming&nbsp;<i class='bi bi-clock-history'></i></i></span>";

                $htmlFullContent = "";
                $htmlContentHeader = "
                    `<div class='col-12 mainGroupOptions'>
                    `<div class='card'>";
                $htmlContentFooter = "
                    </div>
                    </div>";

                $lessonHeader = "
                    <div class='card-body m-0 p-0'>
		            	<div class='table-card'>";
                $allLessonContent = "";

                if (true) {
                    // $GidNew = "%[{$data}]%";
                    $activeClaId_upd = "%[{$activeClaId}]%";
                    $status = "active";
                    $pending = "pending";

                    $sql1 = "SELECT recaccess.*,lesson.*,activity.ActId FROM recaccess,lesson LEFT JOIN activity ON UserId = ? and activity.OtherId = lesson.LesId and activity.Status != ?  WHERE recaccess.ClassId LIKE ? and recaccess.Status = ? and lesson.Status = ? and recaccess.LesId = lesson.LesId ORDER BY recaccess.InsDate DESC LIMIT 3";
                    $stmt = $conn->prepare($sql1);
                    $stmt->bind_param("issss", $UserId, $pending, $activeClaId_upd, $status, $status);
                    $stmt->execute();
                    $result0 = $stmt->get_result();
                    if ($result0->num_rows > 0) {
                        $lessonOne = "
                                    <div class='table-card-head'>
		                	            &nbsp;
		                	            <p>Latest Lessons</p>
		                            </div>";
                        $contentRow = "";
                        while ($row0 = $result0->fetch_assoc()) {
                            $unwatch = "<span onclick='markUnCompleate({$row0['LesId']})' data-bs-toggle='tooltip' data-bs-placement='top' title='Mark as none complete'><i class='fs-6 bi bi-eye-slash'></i></span>";
                            $CompleateStatus = $row0['ActId'] != null ? $Compleate : $NonCompleate;
                            $CompleateStatus = $row0['Type'] == 'note'  ? "" : $CompleateStatus;
                            $eye = $row0['ActId'] != null && ($row0['Type'] == 'video' || $row0['Type'] == 'quiz') ? $unwatch : "";
                            $lesMonth = $row0['Month'];
                            $lessonName = $row0['LesName'];

                            $paymentnotpay = "<span class='alert alert-danger p-0 px-2' onclick='nthj(5,`{$lesMonth}`)'>Not paid&nbsp;<i class='bi bi-lock'></i></span>";
                            $click =  "onclick='lesEvent({$row0['LesId']},`{$row0['Type']}`)'";

                            $sql = "SELECT * FROM payment WHERE UserId = ? and ClassId = ? and Month = ? ";
                            $stmt = $conn->prepare($sql);
                            $stmt->bind_param("sss", $UserId, $activeClaId, $lesMonth);
                            $stmt->execute();
                            $result1 = $stmt->get_result();
                            if ($result1->num_rows > 0 && $row1 = $result1->fetch_assoc()) {
                                if ($row1['Status'] == 'active') {
                                    $StatusIndi = $CompleateStatus;
                                    $onclickEvent = $click;
                                } elseif ($row1['Status'] == 'pending') {
                                    $StatusIndi = $paymentPending;
                                    $onclickEvent = "";
                                } else {
                                    $StatusIndi = $paymentnotpay;
                                    $onclickEvent = "";
                                }
                            } else {
                                $StatusIndi = $paymentnotpay;
                                $onclickEvent = "";
                            }
                            if ($row0['Type'] == 'video') {
                                $lesindi = $video;
                            } elseif ($row0['Type'] == 'note') {
                                $lesindi = $note;
                            } elseif ($row0['Type'] == 'quiz') {
                                $lesindi = $quiz;
                            } elseif ($row0['Type'] == 'upload') {
                                $lesindi = $upload;
                            } elseif ($row0['Type'] == 'upcomming') {
                                $lesindi = $upcomming;
                            } else {
                                $lesindi = $classwork;
                            }

                            $contentRow .= "
                                <div class='table-card-main d-flex align-items-center'>
			                	    <div class='ms-3' style='color: #a9aaae;'><i class='fs-5 bi bi-arrow-return-right'></i></div>
			                	    <div class='table-card-row ms-1 justify-content-between w-100'>
			                		    <div class='d-flex flex-grow-1' {$onclickEvent}>
                                            {$lesindi}
                                            &nbsp;&nbsp;
			                		        <p>{$lessonName}</p>
                                        </div>
                                        <div class='item-center'>
                                            {$eye}
			                		        {$StatusIndi}
                                        </div>
                                    </div>
                                </div>";
                        }
                        $allLessonContent .=  $lessonHeader . $lessonOne . $contentRow . $htmlContentFooter;
                    } else {
                        $contentRow = "<div class='table-card-head'><p>Undefind Content !</p></div>";
                        $allLessonContent .=  $lessonHeader . $contentRow . $htmlContentFooter;
                    }
                }
                $htmlFullContent = $htmlContentHeader . $allLessonContent . $htmlContentFooter;
            }

            if ($maintype == 'lesson') {
                $sql = "SELECT * FROM grouplist WHERE HideFrom Not LIKE '%$insti%' and HideFrom NOT LIKE '%All%' and HideFrom Not LIKE '%[$activeClaId]%' OR HideFrom IS NULL ORDER BY Status ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $status = $row['Status'];
                    $MGName = $row['MGName'];
                    $Gid = $row['GId'];
                    $image = $row['MGImage'];
                    if ($status == 'active') {
                        $type = 1;
                        $indigate = $unlock;
                        $action = $success;
                        $btn = $unlockbtn;
                    } else {
                        $type = 0;
                        $indigate = $lock;
                        $action = $Restricted;
                        $btn = $lockbtn;
                    }

                    $htmlContent .= "
                    <div class='col-xxl-2 col-sm-4 col-md-3 col-6 position-relative mainGroup'>
                        <div class='main-card h-auto' style='--index:{$i}'>
                            <div class='main-sub-card'>
                                <i class='{$indigate} position-absolute top-0 start-100 pe-5 pt-5 translate-middle'></i>" .
                        ($image != null ? "<img class='main-card-img' src='assets/img/site use/group/{$image}'>" : "<div class='StylingText01'><div class='top'>{$MGName}</div><div class='bottom' aria-hidden='true'>{$MGName}</div></div>") .
                        "<div class='main-card-details w-100 mt-2'>
                                    {$action}
                                    <div class='name'>{$MGName}</div>
                                </div>
                                <div class='main-card-footer mt-2 h-auto'>
                                    <button onclick='mainCardAction({$Gid},{$type})' class='btn btn-info py-1 px-3'>{$btn}</button>
                                </div>
                            </div>
                        </div>
                    </div>";
                    $i++;
                }
            } elseif ($maintype == 'month') {

                $sql = "SELECT rec.Month , pay.Status FROM recaccess rec LEFT JOIN payment pay ON pay.UserId = $UserId and pay.Month = rec.Month WHERE rec.ClassId LIKE '%[$activeClaId]%' and rec.status = 'active' GROUP BY rec.Month ORDER BY rec.Month DESC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $result = $stmt->get_result();
                $i = 0;
                while ($row = $result->fetch_assoc()) {
                    $status = $row['Status'];
                    $Month = $row['Month'];
                    $year = substr($Month, 0, 4);

                    $MonthName = GetMonthName(substr($Month, 4));

                    if ($status == 'active') {
                        $type = 1;
                        $indigate = $unlock;
                        $action = $active;
                        $btn = $unlockbtn;
                    } elseif ($status == 'pending') {
                        $type = 0;
                        $indigate = $lock;
                        $action = $pending;
                        $btn = $lockbtn;
                    } else {
                        $type = 0;
                        $indigate = $lock;
                        $action = $Restricted;
                        $btn = $lockbtn;
                    }

                    $htmlContent .= "
                <div class='col-xxl-2 col-sm-4 col-md-3 col-6 position-relative mainGroup'>
                    <div class='main-card h-auto' style='--index:{$i}'>
                        <div class='main-sub-card'>
                                <i class='{$indigate} position-absolute top-0 start-100 pe-5 pt-5 translate-middle'></i>
                                <div class='StylingText01'>
									<div class='subtitle'>{$year}</div>
									<div class='top'>{$MonthName}</div>
									<div class='bottom' aria-hidden='true'>{$MonthName}</div>
								</div>
                            <div class='main-card-details w-100 mt-2'>
                                <div class='d-flex justify-content-between mb-2'>
                                    {$action}
                                    <!--<div class='d-flex'>
                                        <div class='text-dark'><span>20%</span></div>
                                        <div class='circular'>
                                            <input type='hidden' value='100'>
                                            <div class='circular-progress flex-end'>
                                            </div>
                                        </div>
                                    </div>-->
                                </div>
                                <div class='name text-center'>{$year} {$MonthName}</div>
                            </div>
                            <div class='main-card-footer mt-2 h-auto'>
                                <button onclick='mainCardAction({$Month},`{$maintype}`)' class='btn btn-info py-1 px-3'>{$btn}</button>
                            </div>
                        </div>
                    </div>
                </div>";
                    $i++;
                }
            } else {
                $htmlContent = "Undefind Content";
            }
        } catch (Exception $e) {
            $htmlContent = "Undefind Content" . $e;
        }
        echo $htmlFullContent . $htmlContent;
    }

    // get grouplist end


    // lesson list header start

    // if (isset($_POST['backtoclass'])) {

    //     $classList =  getUserClassData($conn, $_SESSION['login'], $_SESSION['clz']);
    //     $htmlContent = "";
    //     $htmlContent .= "<div class='radio-btn'>";

    //     $i = 0;
    //     foreach ($classList as $key => $value) {
    //         if ($key == explode("-", $_SESSION['clz'])[1]) {
    //             $lastvalue = "checked";
    //         } else {
    //             $lastvalue = "";
    //         }
    //         $htmlContent .= "<input type='radio' name='sub_nav' onchange='changeContent({$key})' id='sub_nav-{$i}' value='{$key}' {$lastvalue}>";
    //         $i++;
    //     }
    //     $htmlContent .= "<div class='ul'>";
    //     $i = 0;
    //     foreach ($classList as $key => $value) {
    //         $htmlContent .= "<label class='text-overflow' for='sub_nav-{$i}'>{$value}</label>";
    //         $i++;
    //     }
    //     $htmlContent .= "
    //         </div>
    //     </div>";
    //     echo $htmlContent;
    // }

    // lesson list header end

    // lesson list body start

    if (isset($_POST['changemainCardContent'])) {  // click gropup change main content
        try {
            mysqli_set_charset($conn, "utf8mb4");
            $type = $_POST['type'];
            $type =  ($type == 'undefined') ? (explode("-", $_SESSION['clz'])[2] == 'lesson' ? 'clickGroup' : 'clickMonth') : $type;
            $getData = $_POST['data'];
            $activeClaId = explode("-", $_SESSION['clz'])[1];
            $Insti =  explode("-", $_SESSION['clz'])[0];
            $data = isset(explode("-", $_SESSION['clz'])[3]) ? explode("-", $_SESSION['clz'])[3] : ($getData == 'null' || $getData == null ? null : $getData);

            // $htmlContentBodyFirst = ""; //paymentindi($conn, $UserId);

            // lessson data
            $video = "<i class='bi bi-camera-video text-success'></i>";
            $note  = "<i class='bi bi-file-earmark-text text-success'></i>";
            $quiz = "<i class='bi bi-check2-circle text-success'></i>";
            $classwork = "<i class='bi bi-person-video3 text-success'></i>";
            $upload = "<i class='bi bi-cloud-upload text-success'></i>";
            $upcomming = "<i class='bi bi-clock-history text-success'></i>";

            $paymentnotpay = "";
            $paymentPending = "<span class='alert alert-warning p-0 px-2'>Payment Pending&nbsp;<i class='bi bi-lock'></i></span>";
            $Compleate = "<span class='alert alert-success p-0 px-2'>Complete&nbsp;<i class='bi bi-check2-circle'></i></span>";
            $NonCompleate = "<span class='alert alert-info p-o px-2'>None complete&nbsp;</span>";
            $upcommingindi = "<span class='alert alert-danger p-0 px-2' >Upcomming&nbsp;<i class='bi bi-clock-history'></i></i></span>";

            $htmlFullContent = "";
            $htmlContentHeader = "
            <div class='col-12 mainGroupOptions'>
            <div class='card'>";
            $htmlContentFooter = "
            </div>
            </div>";

            $lessonHeader = "
            <div class='card-body m-0 p-0'>
		    	<div class='table-card'>";
            $allLessonContent = "";

            if ($type == 'clickGroup') {
                $GidNew = "%[{$data}]%";
                $activeClaId_upd = "%[{$activeClaId}]%";
                $status = "active";
                $sql = "SELECT recaccess.Month,recaccess.week FROM recaccess,lesson WHERE recaccess.ClassId LIKE ? and recaccess.GId LIKE ? and recaccess.Status = ? and recaccess.LesId = lesson.LesId GROUP BY recaccess.Month,recaccess.week ORDER BY recaccess.InsDate DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $activeClaId_upd, $GidNew, $status);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $pending = "pending";
                        $Month = $row['Month'];
                        $week = $row['week'];

                        $MonthFomat = DateTime::createFromFormat("Ym", $Month)->format("Y-m");
                        $MonthFomat = explode("-", $MonthFomat);
                        $MonthName = GetMonthName($MonthFomat[1]);

                        $sql1 = "SELECT recaccess.*,lesson.*,activity.ActId FROM recaccess,lesson LEFT JOIN activity ON UserId = ? and activity.OtherId = lesson.LesId and activity.Status != ? WHERE recaccess.ClassId LIKE ? and recaccess.GId LIKE ? and recaccess.Month = ? and recaccess.week = ? and recaccess.Status = ? and lesson.Status = ? and recaccess.LesId = lesson.LesId ORDER BY recaccess.InsDate DESC";
                        $stmt = $conn->prepare($sql1);
                        $stmt->bind_param("isssssss", $UserId, $pending, $activeClaId_upd, $GidNew, $Month, $week, $status, $status);
                        $stmt->execute();
                        $result0 = $stmt->get_result();
                        if ($result0->num_rows > 0) {
                            $numofrow = $result0->num_rows;
                            $viweCompleate = 0;
                            // get compleate lesson count 
                            if (true) {
                                $sql = "SELECT COUNT(activity.ActId)+(SELECT COUNT(lesson.LesId) FROM lesson,recaccess WHERE recaccess.ClassId LIKE ? and recaccess.GId LIKE ? and recaccess.Month = ? and recaccess.week = ? and recaccess.Status = ? and recaccess.LesId = lesson.LesId and lesson.Type = 'note' and lesson.Status = ?) AS countOfCompleate FROM activity,recaccess,lesson WHERE activity.UserId = ? and recaccess.ClassId LIKE ? and recaccess.GId LIKE ? and recaccess.Month = ? and recaccess.week = ? and recaccess.Status = ? and activity.OtherId = recaccess.LesId and activity.Status != ? and recaccess.LesId = lesson.LesId and lesson.Type != 'note' and lesson.Status = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssssssssssssss", $activeClaId_upd, $GidNew, $Month, $week, $status, $status, $UserId, $activeClaId_upd, $GidNew, $Month, $week, $status, $pending, $status);
                                $stmt->execute();
                                $reusaltactivity = $stmt->get_result();
                                if ($reusaltactivity->num_rows > 0 && $rowactivity = $reusaltactivity->fetch_assoc()) {
                                    $viweCompleate = $rowactivity['countOfCompleate'];
                                }
                            }
                            // les compleate status
                            $viwepresentage = (100 / $numofrow) * $viweCompleate;
                            $viwepresentage = number_format($viwepresentage, 0);
                            $lessonOne = "
                            <div class='table-card-head'>
		                	    <div class='circular'>
                                    <input type='hidden' value='{$viwepresentage}'>
                                    <div class='circular-progress'>
                                         <!-- <div class='value-circular'>0%</div> -->
                                    </div>
		                	    </div>
		                	    &nbsp;
		                	    <p>{$viwepresentage}%&nbsp;&nbsp;{$MonthName} {$MonthFomat[0]} ( {$row['week']} )</p>
		                    </div>";
                            $contentRow = "";
                            while ($row0 = $result0->fetch_assoc()) {
                                $unwatch = "<span onclick='markUnCompleate({$row0['LesId']})' data-bs-toggle='tooltip' data-bs-placement='top' title='Mark as none complete'><i class='fs-6 bi bi-eye-slash'></i></span>";
                                $CompleateStatus = $row0['ActId'] != null ? $Compleate : $NonCompleate;
                                $CompleateStatus = $row0['Type'] == 'note'  ? "" : $CompleateStatus;
                                $eye = $row0['ActId'] != null && ($row0['Type'] == 'video' || $row0['Type'] == 'quiz') ? $unwatch : "";
                                $lesMonth = $row0['Month'];
                                $lessonName = $row0['LesName'];

                                $paymentnotpay = "<span class='alert alert-danger p-0 px-2' onclick='nthj(5,`{$lesMonth}`)'>Not paid&nbsp;<i class='bi bi-lock'></i></span>";
                                $click =  "onclick='lesEvent({$row0['LesId']},`{$row0['Type']}`)'";

                                $sql = "SELECT * FROM payment WHERE UserId = ? and ClassId = ? and Month = ? ";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("sss", $UserId, $activeClaId, $lesMonth);
                                $stmt->execute();
                                $result1 = $stmt->get_result();
                                if ($result1->num_rows > 0 && $row1 = $result1->fetch_assoc()) {
                                    if ($row1['Status'] == 'active') {
                                        $StatusIndi = $CompleateStatus;
                                        $onclickEvent = $click;
                                    } elseif ($row1['Status'] == 'pending') {
                                        $StatusIndi = $paymentPending;
                                        $onclickEvent = "";
                                    } else {
                                        $StatusIndi = $paymentnotpay;
                                        $onclickEvent = "";
                                    }
                                } else {
                                    $StatusIndi = $paymentnotpay;
                                    $onclickEvent = "";
                                }
                                if ($row0['Type'] == 'video') {
                                    $lesindi = $video;
                                } elseif ($row0['Type'] == 'note') {
                                    $lesindi = $note;
                                } elseif ($row0['Type'] == 'quiz') {
                                    $lesindi = $quiz;
                                } elseif ($row0['Type'] == 'upload') {
                                    $lesindi = $upload;
                                } elseif ($row0['Type'] == 'upcomming') {
                                    $lesindi = $upcomming;
                                } else {
                                    $lesindi = $classwork;
                                }

                                $contentRow .= "
                                <div class='table-card-main d-flex align-items-center'>
			                	    <div class='ms-3' style='color: #a9aaae;'><i class='fs-5 bi bi-arrow-return-right'></i></div>
			                	    <div class='table-card-row ms-1 justify-content-between w-100'>
			                		    <div class='d-flex flex-grow-1' {$onclickEvent}>
                                            {$lesindi}
                                            &nbsp;&nbsp;
			                		        <p>{$lessonName}</p>
                                        </div>
                                        <div class='item-center'>
                                            {$eye}
			                		        {$StatusIndi}
                                        </div>
                                    </div>
                                </div>";
                            }
                            $allLessonContent .=  $lessonHeader . $lessonOne . $contentRow . $htmlContentFooter;
                        }
                    }
                } else {
                    $contentRow = "<div class='table-card-head'><p>Undefind Content !</p></div>";
                    $allLessonContent .=  $lessonHeader . $contentRow . $htmlContentFooter;
                }
            } elseif ($type == 'clickMonth') {
                $GidNew = "%[{$data}]%";
                $activeClaId_upd = "%[{$activeClaId}]%";
                $status = "active";
                $sql = "SELECT GId FROM recaccess WHERE ClassId LIKE ? and Month LIKE ? and Status = ? GROUP BY GId ORDER BY InsDate DESC";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sss", $activeClaId_upd, $data, $status);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    $GidList = [];
                    while ($row = $result->fetch_assoc()) {
                        $recGId = explode("][", substr($row['GId'], 1, -1));
                        foreach ($recGId as $val) {
                            in_array($val, $GidList) ? null : array_push($GidList, $val);
                        }
                    }
                    foreach ($GidList as $GId) {
                        $pending = "pending";
                        $GidNew = "%[{$GId}]%";
                        $sql1 = "SELECT rac.*,les.*,act.ActId FROM recaccess rac,lesson les LEFT JOIN activity act ON act.UserId = ? and les.LesId = act.OtherId and act.Status != ? WHERE rac.ClassId LIKE ? and rac.GId LIKE ? and rac.Month = ? and rac.Status = ? and rac.LesId = les.LesId and les.Status = ?  ORDER BY rac.InsDate DESC";
                        $stmt = $conn->prepare($sql1);
                        $stmt->bind_param("issssss", $UserId, $pending, $activeClaId_upd, $GidNew, $data, $status, $status);
                        $stmt->execute();
                        $result0 = $stmt->get_result();
                        if ($result0->num_rows > 0) {
                            $sql2 = "SELECT MGName FROM grouplist WHERE GId = '$GId'";
                            $stmt = $conn->prepare($sql2);
                            $stmt->execute();
                            $reusaltMG = $stmt->get_result();
                            $rowMG = $reusaltMG->fetch_array();
                            $MGName = $rowMG['MGName'];

                            $numofrow = $result0->num_rows;
                            // $CompleateStatus = $NonCompleate;
                            // les compleate status
                            if (true) {
                                $sql = "SELECT COUNT(activity.ActId)+(SELECT COUNT(lesson.LesId) FROM lesson,recaccess WHERE recaccess.ClassId LIKE ? and recaccess.GId LIKE ? and recaccess.Month = ? and recaccess.Status = ? and recaccess.LesId = lesson.LesId and lesson.Type = 'note' and lesson.Status = ?) AS countOfCompleate FROM activity,recaccess,lesson WHERE activity.UserId = ? and recaccess.ClassId LIKE ? and recaccess.GId LIKE ? and recaccess.Month = ? and recaccess.Status = ? and activity.OtherId = recaccess.LesId and activity.Status != ? and recaccess.LesId = lesson.LesId and lesson.Type != 'note' and lesson.Status = ?";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ssssssssssss", $activeClaId_upd, $GidNew, $data, $status, $status, $UserId, $activeClaId_upd, $GidNew, $data, $status, $pending, $status);
                                $stmt->execute();
                                $reusaltactivity = $stmt->get_result();
                                if ($reusaltactivity->num_rows > 0 && $rowactivity = $reusaltactivity->fetch_assoc()) {
                                    $viweCompleate = $rowactivity['countOfCompleate'];
                                }
                            }
                            $viwepresentage = (100 / $numofrow) * $viweCompleate;
                            $viwepresentage = number_format($viwepresentage, 0);
                            $lessonOne = "
                            <div class='table-card-head'>
		                	    <div class='circular'>
		                	    	<input type='hidden' value='{$viwepresentage}'>
		                	    	<div class='circular-progress'>
                                        <!-- <div class='value-circular'>0%</div> -->
		                	    	</div>
                                </div>
                                &nbsp;
                                <p>{$viwepresentage}%&nbsp;&nbsp;{$MGName}</p>
                            </div>";
                            $contentRow = "";
                            while ($row0 = $result0->fetch_assoc()) {
                                $lesMonth = $row0['Month'];
                                $lessonName = $row0['LesName'];
                                $unwatch = "<span onclick='markUnCompleate({$row0['LesId']})' data-bs-toggle='tooltip' data-bs-placement='top' title='Mark as none complete'><i class='fs-6 bi bi-eye-slash'></i></span>";

                                $CompleateStatus = $row0['ActId'] != null ? $Compleate : $NonCompleate;
                                $CompleateStatus = $row0['Type'] == 'note'  ? "" : $CompleateStatus;
                                $eye = $row0['ActId'] != null && ($row0['Type'] == 'video' || $row0['Type'] == 'quiz') ? $unwatch : "";

                                $paymentnotpay = "<span class='alert alert-danger p-0 px-2' onclick='nthj(5,`{$lesMonth}`)'>Not paid&nbsp;<i class='bi bi-lock'></i></span>";
                                $click = "onclick='lesEvent(`{$row0['LesId']}`,`{$row0['Type']}`)'";

                                $sql = "SELECT * FROM payment WHERE UserId = ? and Month = ? ";
                                $stmt = $conn->prepare($sql);
                                $stmt->bind_param("ss", $UserId, $lesMonth);
                                $stmt->execute();
                                $result1 = $stmt->get_result();
                                if ($result1->num_rows > 0 && $row1 = $result1->fetch_assoc()) {
                                    if ($row1['Status'] == 'active') {
                                        $StatusIndi = $CompleateStatus;
                                        $onclickEvent = $click;
                                    } elseif ($row1['Status'] == 'pending') {
                                        $StatusIndi = $paymentPending;
                                        $onclickEvent = "";
                                    } else {
                                        $StatusIndi = $paymentnotpay;
                                        $onclickEvent = "";
                                    }
                                } else {
                                    $StatusIndi = $paymentnotpay;
                                    $onclickEvent = "";
                                }
                                if ($row0['Type'] == 'video') {
                                    $lesindi = $video;
                                } elseif ($row0['Type'] == 'note') {
                                    $lesindi = $note;
                                } elseif ($row0['Type'] == 'quiz') {
                                    $lesindi = $quiz;
                                } elseif ($row0['Type'] == 'upload') {
                                    $lesindi = $upload;
                                } elseif ($row0['Type'] == 'upcomming') {
                                    $lesindi = $upcomming;
                                } else {
                                    $lesindi = $classwork;
                                }

                                $contentRow .= "
                            <div class='table-card-main d-flex align-items-center'>
			                	<div class='ms-3' style='color: #a9aaae;'><i class='fs-5 bi bi-arrow-return-right'></i></div>
			                	<div class='table-card-row ms-1 justify-content-between w-100'>
                                <div class='d-flex flex-grow-1' {$onclickEvent}>
                                {$lesindi}
                                &nbsp;&nbsp;
                                <p>{$lessonName}</p>
                                </div>
                                <div class='item-center'>
                                {$eye}
                                {$StatusIndi}
                                </div>
			                	</div>
			                </div>";
                            }
                            $allLessonContent .=  $lessonHeader . $lessonOne . $contentRow . $htmlContentFooter;
                        }
                    }
                }
            } else {
                $contentRow = "<div class='table-card-head'><p>Undefind Content !</p></div>";
                $allLessonContent .=  $lessonHeader . $contentRow . $htmlContentFooter;
            }
            $htmlFullContent = $htmlContentHeader . $allLessonContent . $htmlContentFooter;
        } catch (Exception $e) {
            $htmlFullContent = "Undefind Content !";
        }

        echo $htmlFullContent;
    }

    // lesson list body end

    // payment physuical start

    if (isset($_POST['PaymetPhy'])) {
        try {
            $paymentClass = explode("-", $_SESSION['clz'])[1];
            $type = "active";
            // ($_POST['month'] == null || $_POST['month'] == 'null') ? $Month = GetToday('ym') : $Month = $_POST['month'];
            if (!isset($_POST['month']) || $_POST['month'] == 'null' || $_POST['month'] == null) {
                $Month = GetToday('ym');
            } else {
                $Month = $_POST['month'];
            }
            $today = GetToday('ymd');
            $fileTemp = $_FILES['image']['tmp_name'];
            $fileName = $UserId . "_" . GetToday("ymdhis") . ".jpg";
            $targetFile = "../user_images/payment/";
            // if (validateuser($conn, $UserId, "instiid", $UserId)) {
            $sql = "SELECT PayId FROM payment WHERE UserId = ? and ClassId = ? and Month = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $UserId, $ClassId, $Month);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows == 0) {
                $sql = "INSERT INTO payment(UserId,ClassId,Type,Month,Slip,InsDate) VALUES(?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $UserId, $paymentClass, $type, $Month, $fileName, $today);
                $stmt->execute();
                move_uploaded_file($fileTemp, $targetFile . $fileName);
                echo "success";
            } else {
                echo "error";
            }
        } catch (Exception $e) {
            echo "error" . $e;
        }
    }

    // payment physuical end

    // payment online start
    if (isset($_POST['PaymetOnl'])) {
        $classid = explode("-", $_SESSION['clz'])[1];
        $paymethod = $_POST['paymethod'];
        $price = $_POST['price'];
        $numwha = $_POST['numwha'];
        $num01 = $_POST['num01'];
        $num02 = $_POST['num02'];
        $address = $_POST['address'];
        $distric = $_POST['distric'];
        $city = $_POST['city'];
        $dict = $_POST['dict'];
        $Month = $_POST['payMonth'];
        $fileTemp = $_FILES['slip']['tmp_name'];
        $fileName = $UserId . "_" . GetToday("ymdhis") . ".jpg";
        $targetFile = "../user_images/payment/";
        // $thisMonth = GetToday('ym');
        $today = GetToday('ymd');

        $sql = "SELECT UserName FROM user WHERE UserId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $UserId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0 && $row = $result->fetch_assoc()) {
            $UserName = $row['UserName'];
        }
        $stmt->close();

        try {
            $sql = "SELECT PayId FROM payment WHERE UserId = ? and ClassId = ? and Month = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $UserId, $classid, $Month);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows < 1) {

                $conn->begin_transaction();
                $sql = "INSERT INTO payment(UserId, Name, ClassId, Price, Type, Month, Slip, InsDate) VALUES(?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $UserId, $UserName, $classid, $price, $paymethod, $Month, $fileName, $today);
                $stmt->execute();
                $inserted_id = $stmt->insert_id;
                move_uploaded_file($fileTemp, $targetFile . $fileName);

                $sql = "INSERT INTO paydata(PayId, Address, Tel1, Tel2, TelW, Distric, City, Dict) VALUES(?,?,?,?,?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssss", $inserted_id, $address, $num01, $num02, $numwha, $distric, $city, $dict);
                $stmt->execute();
                $conn->commit();
                echo "success";
            } else {
                echo "alredy add payment";
            }
        } catch (Exception $e) {
            $conn->rollback();
            echo "error" . $e;
        }
    }
    // payment online end

    // lesson model load 
    if (isset($_POST['loadLessonModel'])) {
        $type = $_POST['type'];
        if ($type == 'video') {
            $lessonId = $_POST['data'];
            $sql = "SELECT LesName,Time FROM lesson WHERE LesId = '$lessonId'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $LesName = $row['LesName'];
            } else {
                $LesName = "";
            }
            $respons = "
            <div class='modal-header'>
                    <h5 class='modal-title' id='lessonModelLabel'>{$LesName}</h5>
                    <button type='button' class='btn-close' data-bs-dismiss='modal' onclick='clodeLesModel()'><b><i class='bi bi-x-lg'></i></b></button>
                </div>
				<div class='modal-body row'>
                    <div class='col-12 position-relative mainGroup animiZoom m-0 p-0' style='--index:1;'>
                        <div class='container-fluid px-4'>
                            <div class='row g-4'>
                                <div class='col-sm-12 col-xl-12' >
                                    <div class='bg-secondary text-center rounded p-4'>
                                        <div class='container_player show-controls'>
                                            <div class='wrapper'>
                                                <div class='video-timeline'>
                                                    <div class='progress-area mx-4'>
                                                        <span>00:00</span>
                                                        <div class='progress-bar'></div>
                                                    </div>
                                                </div>
                                                <ul class='video-controls'>
                                                    <li class='options left'>
                                                        <button id='mute' class='volume'><i class='fa-solid fa-volume-high'></i></button>
                                                        <input id='volume-bar' type='range' min='0' max='100' step='any'>
                                                        <div class='video-timer'>
                                                            <p class='current-time'>00:00</p>
                                                            <p>&nbsp;/&nbsp;</p>
                                                            <p class='video-duration'>00:00</p>
                                                        </div>
                                                    </li>
                                                    <li class='options center'>
                                                        <button class='skip-backward'><i class='fas fa-backward'></i></button>
                                                        <button id='play-pause' class='play-pause'><i class='fas fa-play'></i></button>
                                                        <button class='skip-forward'><i class='fas fa-forward'></i></button>
                                                    </li>
                                                    <li class='options right'>
                                                        <div class='playback-content'>
                                                            <button class='playback-speed'><span class='material-symbols-outlined'>
                                                            slow_motion_video
                                                            </span></button>
                                                            <ul class='speed-options'>
                                                                <li data-speed='2.0'>2x</li>
                                                                <li data-speed='1.5'>1.5x</li>
                                                                <li data-speed='1.0' class='active'>Normal</li>
                                                                <li data-speed='0.75'>0.75x</li>
                                                                <li data-speed='0.5'>0.5x</li>
                                                            </ul>
                                                        </div>
                                                        <div class='quality-content' hidden>
                                                            <button class='playback-quality'><span class='fa fa-sliders'></span></button>
                                                            <ul class='quality-options'>
                                                                <li data-speed='highres'>Higher</li>
                                                                <li data-speed='hd1080'>1080p</li>
                                                                <li data-speed='hd720'>720p</li>
                                                                <li data-speed='large'>480p</li>
                                                                <li data-speed='medium' class='active'>360p</li>
                                                                <li data-speed='small'>240p</li>
                                                            </ul>
                                                        </div>
                                                        <button class='pic-in-pic'><span class='material-icons'>picture_in_picture_alt</span></button>
                                                        <button class='fullscreen'><i class='fa-solid fa-expand'></i></button>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div style='position: absolute;' id='player'></div>
                                            <video style='position:relative; width:100%;height: 100%; opacity: 0;'></video>
                                            <!-- <div style='position:relative; width:100%;height: 100%;'>k</div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>";
        } elseif ($type == 'quiz') {
            $lessonId = $_POST['data'];
            $sql = "SELECT LesName,Time FROM lesson WHERE LesId = '$lessonId'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $LesName = $row['LesName'];
                $LesTime = $row['Time'];
            }
            $respons = "
            <div class='modal-header'>
                <h5 class='modal-title' id='lessonModelLabel'>Upload Peaper ( peaper Name )</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' onclick='clodeLesModel()'><b><i class='bi bi-x-lg'></i></b></button>
            </div>
            <div class='modal-body row'>
                <div class='mainGroupOptions'>
                    <div class='card item-center m-1 p-2'>
                        <div class='quiz_content col-xxl-6 col-sm-10 col-md-8 col-12'>
                            <!-- start Quiz button -->
                            <div class='start_btn'><button>Start Now</button></div>
                            <!-- Info Box -->
                            <div class='info_box w-100'>
                                <div class='info-title'><span>Some Rules of this Quiz</span></div>
                                <div class='info-list'>
                                    <div class='info'>1. You will have only <span>{$LesTime} seconds</span> per each question.</div>
                                    <div class='info'>2. Once you select your answer, it can't be undone.</div>
                                    <div class='info'>3. You can't select any option once time goes off.</div>
                                    <div class='info'>4. You can't exit from the Quiz while you're playing.</div>
                                    <div class='info'>5. You'll get points on the basis of your correct answers.</div>
                                </div>
                                <div class='buttons'>
                                    <button class='quit'>Exit Quiz</button>
                                    <button class='restart'>Continue</button>
                                </div>
                            </div>
                            <!-- Quiz Box -->
                            <div class='quiz_box w-100'>
                                <header>
                                    <div class='title'>{$LesName}</div>
                                    <div class='timer'>
                                        <div class='time_left_txt'>Time Left</div>
                                        <div class='timer_sec'>--:--</div>
                                    </div>
                                    <div class='time_line'></div>
                                </header>
                                <div class='section'>
                                    <div class='que_text'>
                                        <!-- Here I've inserted question from JavaScript -->
                                    </div>
                                    <div class='option_list'>
                                        <!-- Here I've inserted options from JavaScript -->
                                    </div>
                                </div>
                                <!-- footer of Quiz Box -->
                                <footer>
                                    <div class='total_que'>
                                        <!-- Here I've inserted Question Count Number from JavaScript -->
                                    </div>
                                    <button class='next_btn'>Next Que</button>
                                </footer>
                            </div>
                            <!-- Result Box -->
                            <div class='result_box w-100'>
                                <div class='icon'>
                                    <i class='fas fa-crown' animate__animated animate__swing animate__infinite infinite></i>
                                </div>
                                <div class='complete_text'>You've completed the Quiz!</div>
                                <div class='score_text text-center d-block'>
                                    <!-- Here I've inserted Score Result from JavaScript -->
                                </div>
                                <div class='buttons'>
                                    <button class='restart'>Replay Quiz</button>
                                    <button class='quit'>Quit Quiz</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>";
        } else {
            $respons = "undefind Content";
        }
        echo $respons;
    }

    // get video player start
    // if (isset($_POST['LessonData'])) {
    //     try {
    //         $type = $_POST['type'];
    //         if ($type == 'video') {
    //             $htmlContent = "
    //             <div class='col-12 position-relative mainGroup animiZoom' style='--index:1;'>
    //                 <div class='container-fluid pt-4 px-4'>
    //                     <div class='row g-4'>
    //                         <div class='col-sm-12 col-xl-12' >
    //                             <div class='bg-secondary text-center rounded p-4'>
    //                                 <div class='container_player show-controls'>
    //                                     <div class='wrapper'>
    //                                         <div class='video-timeline'>
    //                                             <div class='progress-area mx-4'>
    //                                                 <span>00:00</span>
    //                                                 <div class='progress-bar'></div>
    //                                             </div>
    //                                         </div>
    //                                         <ul class='video-controls'>
    //                                             <li class='options left'>
    //                                                 <button id='mute' class='volume'><i class='fa-solid fa-volume-high'></i></button>
    //                                                 <input id='volume-bar' type='range' min='0' max='100' step='any'>
    //                                                 <div class='video-timer'>
    //                                                     <p class='current-time'>00:00</p>
    //                                                     <p>&nbsp;/&nbsp;</p>
    //                                                     <p class='video-duration'>00:00</p>
    //                                                 </div>
    //                                             </li>
    //                                             <li class='options center'>
    //                                                 <button class='skip-backward'><i class='fas fa-backward'></i></button>
    //                                                 <button id='play-pause' class='play-pause'><i class='fas fa-play'></i></button>
    //                                                 <button class='skip-forward'><i class='fas fa-forward'></i></button>
    //                                             </li>
    //                                             <li class='options right'>
    //                                                 <div class='playback-content'>
    //                                                     <button class='playback-speed'><span class='material-symbols-outlined'>
    //                                                     slow_motion_video
    //                                                     </span></button>
    //                                                     <ul class='speed-options'>
    //                                                         <li data-speed='2.0'>2x</li>
    //                                                         <li data-speed='1.5'>1.5x</li>
    //                                                         <li data-speed='1.0' class='active'>Normal</li>
    //                                                         <li data-speed='0.75'>0.75x</li>
    //                                                         <li data-speed='0.5'>0.5x</li>
    //                                                     </ul>
    //                                                 </div>
    //                                                 <div class='quality-content' hidden>
    //                                                     <button class='playback-quality'><span class='fa fa-sliders'></span></button>
    //                                                     <ul class='quality-options'>
    //                                                         <li data-speed='highres'>Higher</li>
    //                                                         <li data-speed='hd1080'>1080p</li>
    //                                                         <li data-speed='hd720'>720p</li>
    //                                                         <li data-speed='large'>480p</li>
    //                                                         <li data-speed='medium' class='active'>360p</li>
    //                                                         <li data-speed='small'>240p</li>
    //                                                     </ul>
    //                                                 </div>
    //                                                 <button class='pic-in-pic'><span class='material-icons'>picture_in_picture_alt</span></button>
    //                                                 <button class='fullscreen'><i class='fa-solid fa-expand'></i></button>
    //                                             </li>
    //                                         </ul>
    //                                     </div>
    //                                     <div style='position: absolute;' id='player'></div>
    //                                     <video style='position:relative; width:100%;height: 100%; opacity: 0;'></video>
    //                                     <!-- <div style='position:relative; width:100%;height: 100%;'>k</div> -->
    //                                 </div>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>";
    //         } elseif ($type == 'quiz') {
    //             $lessonId = $_POST['LessonData'];
    //             $sql = "SELECT LesName,Time FROM lesson WHERE LesId = '$lessonId'";
    //             $stmt = $conn->prepare($sql);
    //             $stmt->execute();
    //             $reusalt = $stmt->get_result();
    //             if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
    //                 $LesName = $row['LesName'];
    //                 $LesTime = $row['Time'];
    //             }
    //             $htmlContent = "
    //             <div class='mainGroupOptions'>
    //                 <div class='card item-center m-1 p-2'>
    //                     <div class='quiz_content col-xxl-6 col-sm-10 col-md-8 col-12'>
    //                         <!-- start Quiz button -->
    //                         <div class='start_btn'><button>Start Now</button></div>
    //                         <!-- Info Box -->
    //                         <div class='info_box w-100'>
    //                             <div class='info-title'><span>Some Rules of this Quiz</span></div>
    //                             <div class='info-list'>
    //                                 <div class='info'>1. You will have only <span>{$LesTime} seconds</span> per each question.</div>
    //                                 <div class='info'>2. Once you select your answer, it can't be undone.</div>
    //                                 <div class='info'>3. You can't select any option once time goes off.</div>
    //                                 <div class='info'>4. You can't exit from the Quiz while you're playing.</div>
    //                                 <div class='info'>5. You'll get points on the basis of your correct answers.</div>
    //                             </div>
    //                             <div class='buttons'>
    //                                 <button class='quit'>Exit Quiz</button>
    //                                 <button class='restart'>Continue</button>
    //                             </div>
    //                         </div>
    //                         <!-- Quiz Box -->
    //                         <div class='quiz_box w-100'>
    //                             <header>
    //                                 <div class='title'>{$LesName}</div>
    //                                 <div class='timer'>
    //                                     <div class='time_left_txt'>Time Left</div>
    //                                     <div class='timer_sec'>--:--</div>
    //                                 </div>
    //                                 <div class='time_line'></div>
    //                             </header>
    //                             <div class='section'>
    //                                 <div class='que_text'>
    //                                     <!-- Here I've inserted question from JavaScript -->
    //                                 </div>
    //                                 <div class='option_list'>
    //                                     <!-- Here I've inserted options from JavaScript -->
    //                                 </div>
    //                             </div>
    //                             <!-- footer of Quiz Box -->
    //                             <footer>
    //                                 <div class='total_que'>
    //                                     <!-- Here I've inserted Question Count Number from JavaScript -->
    //                                 </div>
    //                                 <button class='next_btn'>Next Que</button>
    //                             </footer>
    //                         </div>
    //                         <!-- Result Box -->
    //                         <div class='result_box w-100'>
    //                             <div class='icon'>
    //                                 <i class='fas fa-crown' animate__animated animate__swing animate__infinite infinite></i>
    //                             </div>
    //                             <div class='complete_text'>You've completed the Quiz!</div>
    //                             <div class='score_text text-center d-block'>
    //                                 <!-- Here I've inserted Score Result from JavaScript -->
    //                             </div>
    //                             <div class='buttons'>
    //                                 <button class='restart'>Replay Quiz</button>
    //                                 <button class='quit'>Quit Quiz</button>
    //                             </div>
    //                         </div>
    //                     </div>
    //                 </div>
    //             </div>";
    //         } else {
    //             $htmlContent = "undefind";
    //         }
    //     } catch (Exception $e) {
    //         $htmlContent = "undefind";
    //     }
    //     echo $htmlContent;
    // }
    // get video player end

    // get video link in video id start
    if (isset($_POST['lesRespons'])) {
        try {
            $lesId = $_POST['value'];
            $type = $_POST['type'];
            if ($type == 'video') {
                $sql = "SELECT Link FROM lesson WHERE LesId = '$lesId'";
                $stmt = $conn->prepare($sql);
                // $stmt->bind_param("s", $lesId);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $respons =  $row['Link'];
                } else {
                    $respons = "undefind";
                }
            } elseif ($type == 'quiz') {
                $list = [];
                $fileParth = "../assets/js/quiz/quction.js";
                mysqli_set_charset($conn, "utf8mb4");
                $sql = "SELECT * FROM quction WHERE LesId = '$lesId' and Status = 'active' ORDER BY Number ASC";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $number = $row['Number'];
                    $quiction = $row['quction'];
                    $ans = $row['Ans'];
                    $option_1 = $row['Opt1'];
                    $option_2 = $row['Opt2'];
                    $option_3 = $row['Opt3'];
                    $option_4 = $row['Opt4'];
                    $Dict = $row['Dict'];
                    $Type = $row['Type'];

                    $data =  [
                        "numb" => $number,
                        "question" => $quiction,
                        "answer" => $ans,
                        "options" => [
                            $option_1,
                            $option_2,
                            $option_3,
                            $option_4,
                        ],
                        "Dict" => $Dict,
                        "Type" => $Type
                    ];

                    array_push($list, $data);
                }

                $JsonList = json_encode($list);
                $final = "var questions = " . $JsonList;

                // update quizList
                $WriteFile = fopen($fileParth, 'w');
                if ($WriteFile) {
                    fwrite($WriteFile, $final);
                    fclose($WriteFile);
                    $respons = 'success';
                } else {
                    $respons = 'undefind';
                }
            } else {
                $respons = 'undefind';
            }
        } catch (Exception $e) {
            $respons = 'undefind';
        }
        echo $respons;
    }
    // get video link in video id end

    // add point and activity start 
    if (isset($_POST['manageActivity'])) {
        $type = $_POST['type'];
        $LessonId = $_POST['data'];
        if ($type == 'votchRecoding') {
            try {
                $sql = "SELECT ActId,Status FROM activity WHERE UserId = ? and OtherId = ? and Type = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iis", $UserId, $LessonId, $type);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $status = "active";
                    $sql = "UPDATE activity SET Status = ? WHERE UserId =  ? and OtherId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sii", $status, $UserId, $LessonId);
                    $respons = $stmt->execute() ? "update as compleate" : "update error";
                } else {
                    $conn->begin_transaction();

                    $point = 10;
                    $status = "active";
                    $sql = "INSERT INTO activity(UserId,OtherId,Type,Status,Point) VALUES(?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iisss", $UserId, $LessonId, $type, $status, $point);
                    $stmt->execute();

                    $sql = "UPDATE user SET Point = (SELECT Point FROM user WHERE UserId = ?)+10 WHERE UserId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ii", $UserId, $UserId);
                    $stmt->execute();

                    $conn->commit();

                    $respons = "insert";
                }
            } catch (Exception $e) {
                $conn->rollback();
                $respons = "error";
            }
        } elseif ($type == 'viwePdf') {
            $respons = $type;
        } elseif ($type == 'compleateQuiz') {
            $quixSummary = explode("-", $_POST['quixSummary']);
            $marks = $quixSummary[0] / $quixSummary[1] * 100;
            $point = $quixSummary[0];
            $dict = "Total Question - {$quixSummary[0]} => Compleate Quection - {$quixSummary[1]}";
            $status = "active";
            try {
                $sql = "SELECT ActId,Status FROM activity WHERE UserId = ? and OtherId = ? and Type = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("iis", $UserId, $LessonId, $type);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $status = "active";
                    $sql = "UPDATE activity SET Status = ? WHERE UserId =  ? and OtherId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sii", $status, $UserId, $LessonId);
                    $respons = $stmt->execute() ? "update as compleate" : "update error";
                    // $respons = "alredy add point";
                } else {
                    $conn->begin_transaction();

                    $sql = "INSERT INTO activity(UserId,OtherId,Type,Point,Marks,Dict,Status) VALUES(?,?,?,?,?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iisssss", $UserId, $LessonId, $type, $point, $marks, $dict, $status);
                    $stmt->execute();

                    $sql = "UPDATE user SET Point = (SELECT Point FROM user WHERE UserId = ?)+? WHERE UserId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("iii", $UserId, $point, $UserId);
                    $stmt->execute();

                    $conn->commit();
                    $respons = "insert";
                }
            } catch (Exception $e) {
                $conn->rollback();
                $respons = "error - " . $e;
            }
        } elseif ($type == 'uploadFile') {
            $fileTmpName = $_FILES['zipFile']['tmp_name'];
            $fileName = $UserId . "_" . $LessonId . ".zip";
            $targetFile = "../user_images/uploadFiles/";
            $status = "timeOut";

            $sql = "SELECT activity.*,recaccess.Status AS recStatus FROM recaccess LEFT JOIN activity ON activity.UserId = ? and activity.OtherId = ? and activity.type= ? WHERE recaccess.LesId = ? and recaccess.Status != ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $UserId, $LessonId, $type, $LessonId, $status);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                if ($row['ActId'] != null && $row['recStatus'] != "timeOut") {
                    // upload zip file 
                    move_uploaded_file($fileTmpName, $targetFile . $fileName);
                    $respons = "success";
                } else {
                    $status = "uploaded";
                    try {
                        $conn->begin_transaction();

                        $sql = "INSERT INTO  activity(UserId,OtherId,Type,Status) VALUES(?,?,?,?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("ssss", $UserId, $LessonId, $type, $status);
                        $stmt->execute();
                        $stmt->close();

                        $sql = "INSERT INTO uploadpeaper(UserId,LesId,Url) VALUES(?,?,?)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("sss", $UserId, $LessonId, $fileName);
                        $stmt->execute();
                        $stmt->close();

                        $conn->commit();

                        // upload zip file 
                        move_uploaded_file($fileTmpName, $targetFile . $fileName);

                        $respons = "success";
                    } catch (Exception $e) {
                        $conn->rollback();
                        $respons = "error " . $e;
                    } finally {
                        // $respons = "fine";
                    }
                }
            } else {
                $respons = "this is time  out";
            }
        } else {
            $respons = "not";
        }
        echo $respons;
    }
    // add point and activity end 

    // mark as unwatch start
    if (isset($_POST['markUnCompleate'])) {
        $status = "pending";
        $data = $_POST['data'];
        $sql = "UPDATE activity SET Status = ? WHERE UserId =  ? and OtherId = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sii", $status, $UserId, $data);
        $respons = $stmt->execute() ? "success" : "error";
        echo $respons;
    }
    // mark as unwatch end

    // profile sections start ********************
    if (isset($_POST['loadModel'])) {
        $type = $_POST['type'];
        if ($type == 'editInfo') {
            $id = $_POST['id'];
            $sql = "SELECT * FROM user,userdata WHERE user.UserId = ? and userdata.UserId = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $UserId, $UserId);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $stmt->close();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                if (empty($row['Year'])) {
                    $year = "
                    <div class='p-3 col-sm-6 col-12'>
                        <label for='' class='form-label'>Select the exam year</label>
                        <select name='year' class='form-select'>
                            <option value='' selected>Select the exam year</option>
                            <option value='2024'>2024</option>
                            <option value='2025'>2025</option>
                            <option value='2026'>2026</option>
                        </select>
                    </div>";
                } else {
                    $year = "
                    <div class='p-3 col-sm-6 col-12'>
                        <label for='' class='form-label'>Select the exam year</label>
                        <select name='year' class='form-select'>
                            <option value='{$row['Year']}'>{$row['Year']}</option>
                        </select>
                    </div>";
                }
                $respons = "
                <div class='modal-header'>
					<h5 class='modal-title' id='editInfoModelLabel'>Edit user Information</h5>
					<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
				</div>
				<div class='modal-body' >
                <p class='text-dark'>Student Information</p>
			    <div class='table-responsive d-none d-lg-block'>
			    	<table class='table table-bordered m-0'>
			    		<thead>
			    			<tr>
			    				<th>User Name</th>
			    				<th>Emali</th>
			    				<th>Register Code</th>
			    				<th>Insti Id</th>
			    				<th>Institute</th>
			    			</tr>
			    		</thead>
			    		<tbody>
			    			<tr>
			    				<td>{$row['UserName']}</td>
			    				<td>{$row['Email']}</td>
			    				<td>{$row['RegCode']}</td>
			    				<td>{$row['InstiId']}</td>
			    				<td>{$row['InstiName']}</td>
			    			</tr>
			    		</tbody>
			    	</table>
			    </div>
			    <div class='table-responsive d-block d-lg-none'>
			    	<table class='table table-bordered m-0'>
			    		<tbody>
			    			<tr>
			    				<td><b>Name</b></td>
			    				<td>{$row['UserName']}</td>
			    			</tr>
			    			<tr>
			    				<td><b>Email</b></td>
			    				<td>{$row['Email']}</td>
			    			</tr>
			    			<tr>
			    				<td><b>Register Code</b></td>
			    				<td>{$row['RegCode']}</td>
			    			</tr>
			    			<tr>
			    				<td><b>Institute Id</b></td>
			    				<td>{$row['InstiId']}</td>
			    			</tr>
			    			<tr>
			    				<td><b>Institute</b></td>
			    				<td>{$row['InstiName']}</td>
			    			</tr>
			    		</tbody>
			    	</table>
			    </div>
			    <div id='Formclear'>
			    	<div class='row m-1 editInfor mt-4 pt-0'>
			    		<p class='text-dark mb-2'>Change your information and after save changes.</p>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 pt-0'>
			    				<label for='' class='form-label'>Enter First Name</label>
			    				<input value='{$row['Fname']}' type='text' name='fname' class='form-control' placeholder='Enter First Name'>
			    			</div>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 pt-0'>
			    				<label for='' class='form-label'>Enter Last Name</label>
			    				<input value='{$row['Lname']}' type='text' name='lname' class='form-control' placeholder='Enter Last Name'>
			    			</div>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 '>
			    				<label for='' class='form-label'>Enter Nic Number</label>
			    				<input value='{$row['Nic']}' type='number' name='nicNum' class='form-control ' placeholder='Enter Nic Number'>
			    			</div>
			    		</div>
			    		<div class='col-6 '>
			    			<label class='form-label'>Nic Picture</label>
			    			<input name='nic_pic' type='file' name='nicPic' class='form-control' accept='.jpeg, .jpg, .png, .tiff'>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 '>
			    				<label for='' class='form-label'>Enter Mobile Number</label>
			    				<input value='{$row['MobNum']}' type='number' name='num01' class='form-control ' placeholder='Enter Mobile Number'>
			    			</div>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 '>
			    				<label for='' class='form-label'>Enter Whatsapp Number</label>
			    				<input value='{$row['WhaNum']}' type='number' name='num02' class='form-control ' placeholder='Enter Whatsapp Number'>
			    			</div>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2'>
			    				<!-- <label class='d-block d-sm-none form-label' for='dob'>Select the birthday</label> -->
			    				<label for='' class='form-label'>Select the birthday</label>
			    				<input value='{$row['Dob']}' type='date' id='dob' name='dob' class='form-control ' placeholder='Select the birthday'>
			    			</div>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 '>
			    				<label for='' class='form-label'>Enter School Name</label>
			    				<input value='{$row['SchName']}' type='text' name='schyName' class='form-control ' placeholder='Enter School Name'>
			    			</div>
			    		</div>
                        {$year}
			    		<div class='p-3 col-sm-6 col-12'>
			    			<label for='' class='form-label'>Select subject stream</label>
			    			<select name='streem' class='form-select'>
			    				<option value='' " . ($row['Streem'] == '' ? "selected" : "") . ">Select subject stream</option>
			    				<option value='Maths' " . ($row['Streem'] == 'Maths' ? "selected" : "") . ">Maths</option>
			    				<option value='Bio' " . ($row['Streem'] == 'Bio' ? "selected" : "") . ">Bio</option>
			    				<option value='Tec' " . ($row['Streem'] == 'Tec' ? "selected" : "") . ">Tec</option>
			    				<option value='Art' " . ($row['Streem'] == 'Art' ? "selected" : "") . ">Art</option>
			    				<option value='Commers' " . ($row['Streem'] == 'Commers' ? "selected" : "") . ">Commerce</option>
			    			</select>
			    		</div>
			    		<div class='p-3 col-sm-6 col-12'>
			    			<label for='' class='form-label'>Select Shy</label>
			    			<select name='shy' class='form-select'>
			    				<option value='' " . ($row['Shy'] == '' ? "selected" : "") . ">Select Shy</option>
			    				<option value='1' " . ($row['Shy'] == '1' ? "selected" : "") . ">1'st shy</option>
			    				<option value='2' " . ($row['Shy'] == '2' ? "selected" : "") . ">2'nd shy</option>
			    				<option value='3' " . ($row['Shy'] == '3' ? "selected" : "") . ">3'rd shy</option>
			    			</select>
			    		</div>
			    		<div class='p-3 col-sm-6 col-12'>
			    			<label for='' class='form-label'>Select Medium</label>
			    			<select name='medium' class='form-select'>
			    				<option value='' " . ($row['Medium'] == '' ? "selected" : "") . ">Select Medium</option>
			    				<option value='Sinhala' " . ($row['Medium'] == 'Sinhala' ? "selected" : "") . ">Sinhala</option>
			    				<option value='English' " . ($row['Medium'] == 'English' ? "selected" : "") . ">English</option>
			    			</select>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0 pb-3'>
			    			<div class='card-body p-2'>
			    				<label for='' class='form-label'>Enter address</label>
			    				<input value='{$row['Address']}' type='text' name='address' class='form-control ' placeholder='Enter address'>
			    			</div> 
			    		</div>
			    		<div class='mb-3 col-sm-6 col-12'>
			    			<label for='' class='form-label'>Select District</label>
			    			<select name='dictric' class='form-select'>
			    				<option value='' " . ($row['Distric'] == '' ? "selected" : "") . ">Select District</option>
			    				<option value='Ampara' " . ($row['Distric'] == 'Ampara' ? "selected" : "") . ">Ampara</option>
			    				<option value='Anuradhapura' " . ($row['Distric'] == 'Anuradhapura' ? "selected" : "") . ">Anuradhapura</option>
			    				<option value='Badulla' " . ($row['Distric'] == 'Badulla' ? "selected" : "") . ">Badulla</option>
			    				<option value='Batticaloa' " . ($row['Distric'] == 'Batticaloa' ? "selected" : "") . ">Batticaloa</option>
			    				<option value='Colombo' " . ($row['Distric'] == 'Colombo' ? "selected" : "") . ">Colombo</option>
			    				<option value='Galle' " . ($row['Distric'] == 'Galle' ? "selected" : "") . ">Galle</option>
			    				<option value='Gampaha' " . ($row['Distric'] == 'Gampaha' ? "selected" : "") . ">Gampaha</option>
			    				<option value='Hambantota' " . ($row['Distric'] == 'Hambantota' ? "selected" : "") . ">Hambantota</option>
			    				<option value='Jaffna' " . ($row['Distric'] == 'Jaffna' ? "selected" : "") . ">Jaffna</option>
			    				<option value='Kalutara' " . ($row['Distric'] == 'Kalutara' ? "selected" : "") . ">Kalutara</option>
			    				<option value='Kandy' " . ($row['Distric'] == 'Kandy' ? "selected" : "") . ">Kandy</option>
			    				<option value='Kegalle' " . ($row['Distric'] == 'Kegalle' ? "selected" : "") . ">Kegalle</option>
			    				<option value='Kilinochchi' " . ($row['Distric'] == 'Kilinochchi' ? "selected" : "") . ">Kilinochchi</option>
			    				<option value='Kurunegala' " . ($row['Distric'] == 'Kurunegala' ? "selected" : "") . ">Kurunegala</option>
			    				<option value='Mannar' " . ($row['Distric'] == 'Mannar' ? "selected" : "") . ">Mannar</option>
			    				<option value='Matale' " . ($row['Distric'] == 'Matale' ? "selected" : "") . ">Matale</option>
			    				<option value='Matara' " . ($row['Distric'] == 'Matara' ? "selected" : "") . ">Matara</option>
			    				<option value='Moneragala' " . ($row['Distric'] == 'Moneragala' ? "selected" : "") . ">Moneragala</option>
			    				<option value='Mullaitivu' " . ($row['Distric'] == 'Mullaitivu' ? "selected" : "") . ">Mullaitivu</option>
			    				<option value='Nuwara Eliya' " . ($row['Distric'] == 'Nuwara Eliya' ? "selected" : "") . ">Nuwara Eliya</option>
			    				<option value='Polonnaruwa' " . ($row['Distric'] == 'Polonnaruwa' ? "selected" : "") . ">Polonnaruwa</option>
			    				<option value='Puttalam' " . ($row['Distric'] == 'Puttalam' ? "selected" : "") . ">Puttalam</option>
			    				<option value='Ratnapura' " . ($row['Distric'] == 'Ratnapura' ? "selected" : "") . ">Ratnapura</option>
			    				<option value='Trincomalee' " . ($row['Distric'] == 'Trincomalee' ? "selected" : "") . ">Trincomalee</option>
			    				<option value='Vavuniya' " . ($row['Distric'] == 'Vavuniya' ? "selected" : "") . ">Vavuniya</option>
			    			</select>
			    		</div>
			    		<div class='col-sm-6 col-12  m-0'>
			    			<div class='card-body p-2 '>
			    				<label for='' class='form-label'>Enter City</label>
			    				<input value='{$row['City']}' type='text' name='city' class='form-control ' placeholder='Enter City'>
			    			</div>
			    		</div>
			    	</div>
			    </div>
                </div>
				<div class='modal-footer'>
					<button type='button' class='btn btn-dark' data-bs-dismiss='modal'>Close</button>
					<button type='button' class='btn btn-success' Onclick='modelSubmit(`editInfo`)'>Save changes</button>
				</div>
                <div class='my-3 rusaltLog mx-3'>
                    <div class='valid-feedback alert alert-success text-center alert-dismissible fade show py-2'>Successfull add the lesson!</div>
                    <div class='invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2' >Failed add the lesson</div>
                </div>";
            }
        }
        echo $respons;
    }

    // profile model submit section start 
    if (isset($_POST['profileModelSubmit'])) {
        $type = $_POST['type'];
        if ($type == 'editInfo') {
            try {
                // get data 
                $fname = $_POST['fname'];
                $lname = $_POST['lname'];
                $uNameNew = $fname . " " . $lname;
                $nicNum = $_POST['nicNum'];
                $num01 = $_POST['num01'];
                $num02 = $_POST['num02'];
                $dob = $_POST['dob'];
                $schyName = $_POST['schyName'];
                $year = $_POST['year'];
                $streem = $_POST['streem'];
                $shy = $_POST['shy'];
                $medium = $_POST['medium'];
                $address = $_POST['address'];
                $dictric = $_POST['dictric'];
                $city = $_POST['city'];
                $updateSql = empty($nicNum) ? "" : " ,Nic = '$nicNum' ";

                if (true) {
                    try {
                        $sql = "SELECT NicPic FROM userdata WHERE UserId = ?";
                        $stmt = $conn->prepare($sql);
                        $stmt->bind_param("i", $UserId);
                        $stmt->execute();
                        $reusalt = $stmt->get_result();
                        $stmt->close();
                        $row = $reusalt->fetch_assoc();
                    } catch (Exception $e) {
                        null;
                    } finally {
                        $nicPicName = empty($row['NicPic']) || !isset($row['NicPic']) ? "Nic-{$UserId}-" . GetToday('ymd') . ".jpg" : $row['NicPic'];
                    }
                }
                $nicPicTmp_name = isset($_FILES['nicPic']) ? $_FILES['nicPic']['tmp_name'] : null;

                // update table 
                $sql = "SELECT user.UserId FROM user,userdata WHERE user.UserId = ? and userdata.UserId = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ii", $UserId, $UserId);
                $stmt->execute();
                $reusalt = $stmt->get_result();
                $stmt->close();
                if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                    $conn->begin_transaction();

                    $sql = "UPDATE user SET UserName = ? , Year = ? WHERE UserId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sii", $uNameNew, $year, $UserId);
                    $stmt->execute();

                    $sql = "UPDATE userdata SET Fname=? ,Lname=? ,NicPic=? ,MobNum=? ,WhaNum=? ,Dob=? ,SchName=? ,Year=? ,Streem=? ,Shy=? ,Medium=? ,Address=? ,Distric=? ,City=? $updateSql WHERE UserId = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("ssssssssssssssi", $fname, $lname, $nicPicName, $num01, $num02, $dob, $schyName, $year, $streem, $shy, $medium, $address, $dictric, $city, $UserId);
                    $stmt->execute();

                    try {
                        empty($nicPicTmp_name) ? move_uploaded_file($nicPicTmp_name, "../user_images/nic_pic/" . $nicPicName) : null;
                    } catch (Exception $e) {
                        null;
                    }

                    $conn->commit();
                    $respons = "success";
                } else {
                    $respons = "undefind";
                }
            } catch (Exception $e) {
                $conn->rollback();
                $respons = "error " . $e;
            }
        } else {
            $respons = "Undefind";
        }
        echo $respons;
    }
    // profile model submit section end

    // profile sections end **********************


    // same as all functions section start  *************

    if (isset($_POST['updateModal'])) {
        $type = $_POST['type'];
        $modelHead = "
        <div class='modal-header'>
                <h5 class='modal-title' id='verticallyCenteredLabel'>Peaper Rusalt</h5>
                <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'><i class='bi bi-x-lg'></i></button>
            </div>
        <div class='modal-body' id='modelBody'>";
        $modelFooter = "
        </div>";
        if ($type == 'searchbox') {
            $today = GetToday('ymd', '-');
            $status = "finished";
            $sql = "SELECT * FROM peaper WHERE DATE_ADD(finishDate, INTERVAL 8 DAY) >  ?  and Status = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $today, $status);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $ModelMiddle = "
                <div class='row item-center'>
                    <div class='col-8'>
                        <div class='search-container'>
                            <div class='input-group'>
                                <input type='text' class='form-control' id='searchInp2' placeholder='Search index'>
                                <button class='btn' type='button' onclick='search(`peaperReusalt`)'>
                                    <i class='bi bi-search' id='searchInp2Search'></i>
                                    <div class='spinner-border text-red spinner-w1 d-none pe-none' id='searchInp2Snip' role='status'>
							        	<span class='visually-hidden'>Loading...</span>
							        </div>
                                </button>
                            </div>
                        </div>
                        <div class='search_reusalt' id='search_reusalt'></div>
                    </div>
                </div>
                ";
                $respons = $modelHead . $ModelMiddle . $modelFooter;
            }
        } elseif ($type == 'peaperReusalt') {
            $data = $_POST['data'];
            $respons = "";
            $today = GetToday('ymd', '-');
            $status = "finished";
            // $sql = "SELECT * FROM peaper LEFT JOIN marksofpeaper ON peaper.PeaperId = marksofpeaper.PeaperId and unreguser.URGId = marksofpeaper.URGId LEFT JOIN unreguser ON unreguser.CousId = ? OR unreguser.InstiId = ? WHERE DATE_ADD(peaper.finishDate, INTERVAL 8 DAY) >  ?  and peaper.Status = ?";
            $sql = "SELECT * FROM peaper
            LEFT JOIN unreguser ON unreguser.CousId = ?
            LEFT JOIN marksofpeaper ON peaper.PeaperId = marksofpeaper.PeaperId and unreguser.URGId = marksofpeaper.URGId
            WHERE DATE_ADD(peaper.finishDate, INTERVAL 8 DAY) > ? AND peaper.Status = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss",$data, $today, $status);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            while ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                $marks = $row['Marks'];
                $grade = empty($marks) ? "Grade Not Found" : (($marks >  94) ? "A &#8314;" : ($marks > 74 ? "A &#8315;" : ($marks > 69 ? "B &#8314;" : ($marks > 64 ? "B &#8315;" : ($marks > 59  ? "C &#8314;" : ($marks > 54 ? "C &#8315;" : ($marks > 49 ? "S &#8314;" : ($marks > 44 ? "S &#8315;" : "F") ) ) )) ) ) );
                $respons .= empty($row['Name']) ? "<p class='text-info mt-3'> <i class='bi bi-search'>&nbsp;</i> Reusalt Not Found </p>" :  "<p class='text-success mt-3 p-3 border'> Peaper &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :  &nbsp;{$row['peaperName']} <br> Use Name : &nbsp;{$row['Name']} <br> Greade &nbsp;&nbsp;&nbsp;&nbsp; : &nbsp;{$grade} <br> Marks &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: &nbsp;".(empty($row['Marks']) ? "Marks Not Found" : $row['Marks'])." </p>";
            }
        }
        echo $respons;
    }

    // same as all functions section ens  *************
} catch (Exception $e) {
    $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $e);
    fclose($myfile);
    echo "Main Error Please Inform Developer";
}


// $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
// fwrite($myfile, $htmlContent);
// fclose($myfile);

function filrWrite($argument)
{
    $myfile = fopen("newfile.txt", "a") or die("Unable to open file!");
    fwrite($myfile, $argument);
    fclose($myfile);
}
