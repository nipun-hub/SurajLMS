<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'profile'; ?>

<?php include_once('sql/function.php'); ?>

<?php include_once('include/main.php'); ?>




<!doctype html>
<html lang="en">

<head>

    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <link rel="shortcut icon" href="../assets/images/ict.ico">

    <!-- Title -->
    <title>Surajskumara.lk | Admin</title>

    <?php include('include/header.php'); ?>

    <link rel="stylesheet" href="assets/css/alert.css">

    <style>
        .select2 {
            width: 100% !important;
        }
    </style>

    <style>
        /* Style for positioning toast */
        .toast {
            z-index: 10100;
        }

        .rotatr-continuar {
            animation: in 2s linear 0s infinite normal;
        }

        @keyframes in {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

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
                    <div class="row">
                        <div class="col-xl-12">
                            <!-- Card start -->
                            <div class="card">
                                <div class="card-body">

                                    <?php
                                    $sql = "SELECT * FROM adminuser WHERE AdminId = '$UserId'";
                                    $stmt = $conn->prepare($sql);
                                    $stmt->execute();
                                    $reusalt = $stmt->get_result();
                                    if ($reusalt->num_rows  > 0 && $row = $reusalt->fetch_assoc()) {
                                        $uName = empty($row['UName']) ? "" : $row['UName'];
                                        $email = empty($row['Email']) ? "" : $row['Email'];
                                        $mobNum = empty($row['MobNum']) ? "" : $row['MobNum'];
                                        $whaNum = empty($row['WhaNum']) ? "" : $row['WhaNum'];
                                        $pict = empty($row['image']) ? "assets/img/site use/admin.jpg" : "assets/img/admin/" . $row['image'];
                                        $access = empty($row['Access']) ? "" : $row['Access'];
                                        $type = empty($row['Type']) ? "Undefind" : $row['Type'];
                                        $showStu = empty($row['ShowStu']) ? "" : $row['ShowStu'];
                                        $ShowAdm = empty($row['ShowAdm']) ? "" : $row['ShowAdm'];
                                        $accessList = explode("][", substr($row['Access'], 1, -1));
                                        $instiImage = (explode("-", $type))[0] == 'admin' ? "../Dachbord/assets/img/site use/instiimge/" . (explode("-", $type))[1] . ".jpg" : "assets/img/site use/admin.jpg";
                                        $accessStr = "";
                                        foreach ($accessList as $key => $value) {
                                            $accessStr .= $value . " ";
                                        }
                                    }
                                    ?>
                                    <!-- Row start -->
                                    <div class="row">
                                        <div class="col-xxl-8 col-xl-7 col-lg-7 col-md-6 col-sm-12 col-12 adminData">
                                            <div class="row">
                                                <div class="col-sm-6 col-12">
                                                    <div class="d-flex flex-row">
                                                        <!-- <img src="assets/img/site use/admin.jpg" class="img-fluid change-img-avatar" alt="Image"> -->
                                                        <label for='imgInp' id="imgInpLab"><img id='image-preview' class='img-fluid change-img-avatar' src='<?php echo $pict; ?>' alt='admin profile'></label>
                                                        <div id="dropzone-sm" class="mb-4 dropzone-dark" onclick="document.getElementById('imgInpLab').click()">
                                                            <form action="/upload" class="dropzone needsclick dz-clickable" id="">

                                                                <div class="dz-message needsclick">
                                                                    <button type="button" class="dz-button">Change Image.</button>
                                                                </div>

                                                            </form>
                                                        </div>

                                                        <!-- <div id="dropzone-sm" class="mb-4 dropzone-dark"> -->
                                                        <form runat='server' id='Formclear'>
                                                            <div class='mb-3 d-none'>
                                                                <input name='profileImage' accept='image/*' type='file' id='imgInp' class='form-control' onchange='changeImage()' />
                                                            </div>
                                                        </form>
                                                        <!-- </div> -->
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-xxl-4 col-sm-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="userName" class="form-label">User Name</label>
                                                        <input type="text" class="form-control" name="userName" id="userName" placeholder="User Name" value="<?php echo $uName; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xxl-4 col-sm-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="email" class="form-label">Email </label>
                                                        <input type="email" name="email" class="form-control" id="email" placeholder="example@gmail.com" value="<?php echo $email; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xxl-4 col-sm-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="mobNo" class="form-label">Mobile Number</label>
                                                        <input type="number" name="mobNo" class="form-control" id="mobNo" placeholder="07********" value="<?php echo $mobNum; ?>">
                                                    </div>
                                                </div>
                                                <div class="col-xxl-4 col-sm-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="whaNo" class="form-label">Whatsapp Number</label>
                                                        <input type="number" name="whaNo" class="form-control" id="whaNo" placeholder="07********" value="<?php echo $whaNum; ?>">
                                                    </div>
                                                </div>
                                                <!-- <div class="col-xxl-4 col-sm-6 col-12">
                                                    <div class="mb-3">
                                                        <label for="enterPassword" class="form-label">Password</label>
                                                        <input type="password" class="form-control" id="enterPassword" placeholder="Enter Password">
                                                    </div>
                                                </div> -->
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-lg-5 col-md-6 col-sm-12 col-12">
                                            <div class="account-settings-block">
                                                <div class="settings-block ">
                                                    <div class="settings-block-title">Access details</div>
                                                    <div class="card-body d-flex row border border-1 m-3">
                                                        <div class="col-auto">
                                                            <label class='form-label'></label>
                                                            <img class="product-added-img" width="100" src="<?php echo $instiImage; ?>" alt="User profile picture">
                                                        </div>
                                                        <div class="col-auto d-flex row">
                                                            <div class="col-12 align-self-center">
                                                                <h5><?php echo $type; ?></h5>
                                                                <p class="text-dark"> Higher Education (Pvt) Ltd - Gampaha</p>
                                                                <p class="text-success"><?php echo $accessStr; ?></p> <!-- <p class="text-success"><i class="bi bi-check-circle"></i> active</p> -->
                                                                <!-- <p class="text-red"><i class="bi bi-x-circle"></i> Not Registered Institute</p> -->
                                                                <!-- <p class="text-warning"><i class="bi bi-clock-history"></i></i> Pending activation</p> -->
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="settings-block">
                                                    <div class="settings-block-title">Other Settings</div>
                                                    <div class="settings-block-body">
                                                        <div class="list-group">

                                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>Show contact student page</div>
                                                                <div class="form-check form-switch">
                                                                    <input onchange="save('showStu',this.checked)" class="form-check-input" type="checkbox" id="showNotifications" <?php echo $showStu==1 ? 'checked' : null; ?>>
                                                                    <label class="form-check-label" for="showNotifications"></label>
                                                                </div>
                                                            </div>

                                                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>Show contact admin page</div>
                                                                <div class="form-check form-switch">
                                                                    <input onchange="save('showAdmin',this.checked)" class="form-check-input" type="checkbox" id="showNotifications" <?php echo $ShowAdm==1 ? 'checked' : null; ?>>
                                                                    <label class="form-check-label" for="showNotifications"></label>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12 col-12">
                                            <hr>
                                            <button class="btn btn-info" onclick="save('adminData')">Save Settings</button>
                                        </div>
                                    </div>
                                    <!-- Row end -->

                                </div>
                            </div>
                            <!-- Card end -->
                        </div>
                    </div>
                    <!-- Row end -->

                    <!-- model section start  -->
                    <div class="modal fade" id="setcurrentclass" tabindex="-1" aria-labelledby="setcurrentclassLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content addcurrentclass">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="setcurrentclassLabel">Set current class</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">

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
    <?php include('include/alert.php'); ?>

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

    <!-- ajex -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->

    <!-- Main Js Required -->
    <script src="assets/js/main.js"></script>

    <!-- alert js -->
    <script src="assets/js/alert.js"></script>
    <script src="assets/js/error.js"></script>
    <script src="assets/js/validate.js"></script>
    <script src="assets/js/nTost.js"></script>

    <script>
        function save(type, value = null) {
            if (type == 'adminData') {
                const InputFeild = document.querySelectorAll('.adminData input , .adminData select');
                // get Data
                let PassData = new FormData();
                PassData.append('save', '');
                PassData.append("type", type);
                InputFeild.forEach(self => {
                    self.name == 'profileImage' ? null : PassData.append(self.name, self.value);
                });
                InputFeild[0].value == '' ? null : PassData.append('profileImage', InputFeild[0].files[0]);
                $.ajax({
                    url: "sql/process.php",
                    type: "POST",
                    data: PassData,
                    processData: false,
                    contentType: false,
                    success: function(response, status) {
                        console.log(response);
                        if (response == ' success') {
                            nTost({
                                type: 'success',
                                titleText: 'SuccessFully update profile'
                            });
                        } else {
                            console.log('error')
                            nTost({
                                type: 'error',
                                titleText: 'somthing wront! pleace try again.'
                            });
                        }
                    }
                });
            } else if(type == 'showStu' || type == 'showAdmin') {
                let PassData = new FormData();
                PassData.append('save','');
                PassData.append('type', type);
                PassData.append('value', value);

                $.ajax({
                    url: "sql/process.php",
                    type: "POST",
                    data: PassData,
                    processData: false,
                    contentType: false,
                    success: function(response, status) {
                        console.log(response);
                        if (response == ' success') {
                            nTost({type: 'success',titleText: 'SuccessFully updated'});
                        } else {
                            console.log('error');
                            nTost({type: 'error',titleText: 'somthing wront! pleace try again.'});
                        }
                    }
                });
            }
        }
    </script>

    <script>
        function changeImage() {
            var image_prevew = document.getElementById('image-preview');
            const [file] = imgInp.files
            if (file) {
                image_prevew.src = URL.createObjectURL(file)
            }
        }

        window.onload = function() {
            url_data = window.location.search;
            if (url_data == '?success_login') {
                history.pushState({
                    page: 'new-page'
                }, 'New Page', './');
                nthj(3);
            } else if (url_data == '?success_register') {
                history.pushState({
                    page: 'new-page'
                }, 'New Page', './');
                nthj(4);
            }
        };
    </script>

</body>

</html>