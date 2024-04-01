<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'atendent'; ?>

<?php include_once('sql/function.php'); ?>

<?php include_once('include/main.php'); ?>

<?php
$adminTypenew = empty($adminType[1]) || $adminType[1] == "undefined" ? null : $adminType[1];
if (!($activeClassName == $adminTypenew || $adminType[0] == 'owner')) {
    header('location:./');
    exit;
}
?>

<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <link rel="shortcut icon" href="../assets/images/ict.ico">

    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="assets/vendor/bs-select/bs-select.css" />

    <!-- Title -->
    <title>Surajskumara.lk | Admin</title>

    <?php include('include/header.php'); ?>

    <link rel="stylesheet" href="assets/css/qrscanner.css">

    <script src="https://unpkg.com/html5-qrcode@2.0.9/dist/html5-qrcode.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>

</head>

<body>

    <!-- Loading wrapper -->
    <?php include('include/snipper.php'); ?>

    <!-- Page wrapper start -->
    <div class="page-wrapper">

        <!-- Sidebar wrapper -->
        <?php include_once('include/sidebar.php'); ?>


        <!-- *************
				************ Main container start *************
			************* -->
        <div class="main-container">

            <!-- page header -->
            <?php include('include/navbar.php'); ?>

            <!-- Content wrapper scroll start -->
            <div class="content-wrapper-scroll">

                <!-- Content wrapper start -->
                <div class="content-wrapper">

                    <!-- Row start -->
                    <div class="row item-center">
                        <div class="col-xxl-4 col-sm-8 col-10 ">
                            <?php
                            $sql = "SELECT * FROM class WHERE Conducting = 1 ";
                            $stmt = $conn->prepare($sql);
                            $stmt->execute();
                            $reusalt = $stmt->get_result();
                            if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                                $className = "{$row['year']}  {$row['ClassName']} {$row['InstiName']}";
                            ?>
                                <div class="stats-tile p-2">
                                    <img src="assets/img/site use/acvitating.gif" alt="" width="30">
                                    <!-- <div class="sale-icon-bdr">
								</div> -->
                                    <div class="sale-details ms-3">
                                        <h5 class="alert alert-success p-1 ps-2">Now Class in progress</h5><i onclick="submitCurrentClass('del')" class="bi bi-trash text-dark position-absolute top-0 end-0 m-2"></i>
                                        <h6 class="text-dark"><?php echo $className; ?></h6>
                                        <p class="">Ending with 17.00 pm</p>
                                    </div>
                                </div>
                            <?php
                            } else { ?>
                                <div class="stats-tile p-2" data-bs-toggle="modal" data-bs-target="#setcurrentclass" onclick="addcurrentclassAttribute()">
                                    <img src="assets/img/site use/notfound.jpg" alt="" width="50">
                                    <!-- <div class="sale-icon-bdr">
								</div> -->
                                    <div class="sale-details ms-3 w-100">
                                        <h5 class="alert alert-info p-1 ps-2">Now Class in progress</h5>
                                        <h6 class="text-dark">No class has started yet</h6>
                                        <p class="">Click heer to start a class</p>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <!-- Row end -->


                    <?php
                    $sql = "SELECT ClassId FROM class WHERE Conducting = 1";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $reusalt = $stmt->get_result();
                    if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
                        $classId = $row['ClassId'];
                        $_SESSION['ClassId'] = $classId;
                    ?>
                        <!-- Row start -->
                        <div class="row">
                            <div class="col-sm-12 col-12">
                                <!-- Card start -->
                                <div class="card createclass">
                                    <!-- <div class="card-header">
                                    <div class="card-title">Create the new Group</div>
                                </div> -->
                                    <div class="card-body item-center">
                                        <div class="qrscanner">
                                            <!-- <h1>Scan QR Codes</h1> -->
                                            <div class="section">
                                                <div id="my-qr-reader">
                                                </div>
                                                <!-- Modal 4 -->
                                                <button id="qrresponsalert" type="button" class="btn btn-info d-none" data-bs-toggle="modal" data-bs-target="#verticallyCentered">
                                                    Vertically Centered Modal
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Card end -->

                            </div>
                        </div>
                        <!-- Row end -->
                    <?php } else {
                        $_SESSION['ClassId'] = 'undefind'; ?>
                        <!-- Row start -->
                        <div class="row">
                            <div class="col-12">
                                <div class="stats-tile p-2 item-center">
                                    <img src="assets/img/site use/notfound.jpg" alt="" srcset="" width="500">
                                </div>
                            </div>
                        </div>
                        <!-- Row end -->
                    <?php } ?>


                    <!-- model section start  -->

                    <!-- set class model  -->
                    <div class="modal fade" id="setcurrentclass" tabindex="-1" aria-labelledby="setcurrentclassLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content addcurrentclass">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="setcurrentclassLabel">Set current class</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-12 mb-3 currentClassData">
                                            <label class="form-label d-flex">Select current Class</label>
                                            <select id="className" name="className" class="form-select">
                                                <option> Select the current class</option>
                                            </select>
                                        </div>
                                        <div class="col-6 mb-3 currentClassData">
                                            <label class="form-label d-flex">start time</label>
                                            <input type="time" class="form-control" name="startTime" placeholder="hrs:mins">
                                        </div>
                                        <div class="col-6 mb-3 currentClassData">
                                            <label class="form-label d-flex">End time</label>
                                            <input type="time" class="form-control" name="endTime" placeholder="hrs:mins">
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="submitCurrentClass" onclick="submitCurrentClass()">Set a class</button>
                                    <!-- <button type="button" class="btn btn-success"><i class="bi bi-check2-all"></i></button> -->
                                </div>

                                <div class="my-3 rusaltLog mx-3">
                                    <div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
                                    <div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- scan qr respons data -->
                    <div class="modal fade" id="verticallyCentered" tabindex="-1" aria-labelledby="verticallyCenteredLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content" id="atendentAlert">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="verticallyCenteredLabel">Scaned Data (ID : <span id="scanCode"></scan>)</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <h5 class=""><span class="text-dark">Searching Information!</span></h5>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                    <!-- <button type="button" class="btn btn-success">Viwe More</button> -->
                                    <!-- <button type="button" class="btn btn-success"><i class="bi bi-check2-all"></i></button> -->
                                </div>
                                <div class="my-3 rusaltLog mx-3">
                                    <div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
                                    <div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- model section end  -->



                </div>
                <!-- Content wrapper end -->
                <!-- app footer -->
                <?php include('include/footer.php'); ?>

            </div>
            <!-- Content wrapper scroll end -->

        </div>
        <!-- *************
				************ Main container end *************
			************* -->

    </div>


    <!-- alert include -->
    <?php // include('include/alert.php'); 
    ?>

    <!-- *************
			************ Required JavaScript Files *************
		************* -->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/modernizr.js"></script>
    <script src="assets/js/moment.js"></script>

    <!-- *************
			************ Vendor Js Files *************
		************* -->

    <!-- Overlay Scroll JS -->
    <script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

    <!-- Apex Charts -->
    <!-- <script src="assets/vendor/apex/apexcharts.min.js"></script> -->
    <!-- <script src="assets/vendor/apex/custom/sales/salesGraph.js"></script> -->
    <!-- <script src="assets/vendor/apex/custom/sales/revenueGraph.js"></script> -->
    <!-- <script src="assets/vendor/apex/custom/sales/taskGraph.js"></script> -->

    <!-- Input Mask JS -->
    <!-- <script src="assets/vendor/input-masks/cleave.min.js"></script> -->
    <!-- <script src="assets/vendor/input-masks/cleave-phone.js"></script> -->
    <!-- <script src="assets/vendor/input-masks/cleave-custom.js"></script> -->

    <!-- Bootstrap Select JS -->
    <!-- <script src="assets/vendor/bs-select/bs-select.min.js"></script> -->
    <!-- <script src="assets/vendor/bs-select/bs-select-custom.js"></script> -->


    <!-- ajex -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->

    <!-- Main Js Required -->
    <script src="assets/js/main.js"></script>

    <!-- alert js -->
    <!-- <script src="assets/js/alert.js"></script> -->
    <!-- <script src="assets/js/error.js"></script> -->
    <script src="assets/js/validate.js"></script>
    <script src="assets/js/qrscanner.js"></script>

    <script>
        $(document).ready(function() {
            formData = "getClassList=";
            $.post("sql/process.php", formData, function(response, status) {
                $('#className').html(response);
            });
        });

        function qrRespons(val) {
            // document.getElementById('scanCode').innerHTML = val;
            // var classId = document.getElementById('ClassIdData').value;
            formData = "loadAtendentAlertData=" + "&data=" + val;
            $.post("sql/process.php", formData, function(response, status) {
                $('#atendentAlert').html(response);
                document.getElementById('qrresponsalert').click();
            });
        }
    </script>


</body>

</html>