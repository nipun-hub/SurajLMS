<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'userManagement'; ?>

<?php include_once('sql/function.php'); ?>

<?php include_once('include/main.php'); ?>

<?php
// if (!isset($adminType[0]) ||  $adminType == "admin" ) {
//     header('location:./');
//     exit;
// }
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
                    <!-- <div class="row item-center"> -->
                    <!-- <div class="col-xl-6 col-sm-12 col-12"> -->
                    <!-- <div class="card"> -->
                    <!-- <div class="card-header"> -->
                    <!-- <div class="invalid-feedback">Not Selected Peaper! Please select peaper</div> -->
                    <!-- <div class="col-xl-12 col-sm-12 col-12"> -->
                    <!-- <div class="mb-3"> -->
                    <!-- <label for="activePeaper" class="form-label">Select the peaper *</label> -->
                    <!-- <Select name="activePeaper" id="activePeaper" class="form-select" onchange="ActivePeaper(this.value)"> -->
                    <!-- <option value="">Select The Peaper</option> -->
                    <!-- <option value="1">2025 Susipwan peaper</option> -->
                    <!-- <option value="2">2026 peaper</option> -->
                    <!-- <option value="3">2027 peaper</option> -->
                    <!-- </Select> -->
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- </div> -->
                    <!-- Row end -->


                    <!-- Row start -->
                    <div class="row my-3 text-center">
                        <div class="col-auto m-2">
                            <button class="btn btn-success w-100" onclick="updateModelContent('regStu')"><i class="bi bi-plus"></i>&nbsp;Register Student</button>
                        </div>
                        <!-- <div class="col-auto m-2">
                            <button class="btn btn-success w-100" onclick="updateModelContent('uploadMarks')" <?php echo ($adminType[0] == 'owner' || $adminType[0] == 'editor') ? null : "disabled" ?> disabled><i class="bi bi-plus"></i>&nbsp;Upload marks Site User</button>
                        </div>
                        <div class="col-auto m-2">
                            <button class="btn btn-success w-100" onclick="updateModelContent('uploadMarkNotReg')" <?php echo ($adminType[0] == 'owner' || $adminType[0] == 'editor') ? null : "disabled" ?> disabled><i class="bi bi-plus"></i>&nbsp;Upload marks other</button>
                        </div> -->
                        <!-- <div class="col-xxl-3 col-md-3 col-sm-6 col-6 mb-3">
                            <button class="btn btn-success w-100" onclick="updateModelContent('winner','insert')"><i class="bi bi-plus"></i>&nbsp;Add Winner</button>
                        </div> -->
                    </div>
                    <!-- Row end -->

                    <!-- Row start -->
                    <div class="row item-center d-none">
                        <div class="sub-nav">
                            <div class="sub-nav-body" id="sub-nav-body1">
                                <div class="radio-btn notifiradio">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-1" value="1" onclick="ShowBody()" checked>
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-2" value="2" onclick="ShowBody()">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-3" value="3" onclick="ShowBody()">
                                    <!-- <input type="radio" name="sub_nav" onchange="" id="sub_nav-4" value="4" onclick="ShowBody()"> -->
                                    <div class="ul">
                                        <label class="text-overflow" for="sub_nav-1">User</label>
                                        <label class="text-overflow" for="sub_nav-2">Ranking</label>
                                        <label class="text-overflow" for="sub_nav-3">User Register</label>
                                        <!-- <label class="text-overflow" for="sub_nav-4">Winner Manage</label> -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Row end -->

                    <!-- Row start -->
                    <div class="row admin-table">
                        <div class="col-12">

                            <!-- snippit management table start -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"></div>
                                    <div class="search-container">

                                        <!-- Search input group start -->
                                        <div class="input-group">
                                            <select id="limitData" class="form-select mx-3" onchange="ShowBody('',this.value)">
                                                <option value="10">10</option>
                                                <option value="20">20</option>
                                                <option value="50">50</option>
                                                <option value="all">all</option>
                                            </select>
                                            <input type="text" class="form-control searchInp" style="background-color: #dae0e9;" placeholder="Search anything" onkeyup="ShowBody(this.value)">
                                            <button class="btn" type="button">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <!-- Search input group end -->

                                    </div>
                                </div>
                                <div class="card-body" id="table-content-change"><!-- change table content-->
                                    <center><img src="assets/img/gif/loding.gif" width="300" alt="" srcset=""></center>
                                </div>
                            </div>
                            <!-- snippit management table end -->

                        </div>
                    </div>
                    <!-- Row end -->

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


    <!-- model Section start -->
    <div class="modal fade" id="modelMain" tabindex="-1" aria-labelledby="modelMainLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content modelMain" id="modelMainContent">

            </div>
        </div>
    </div>
    <div class="modal fade" id="modelMainxl" tabindex="-1" aria-labelledby="modelMainxlLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content modelMainxl bg-light" id="modelMainContentxl">

            </div>
        </div>
    </div>

    <div class="modal fade" id="actiomModel" tabindex="-1" aria-labelledby="actiomModelLabel" aria-hidden="true">
        <div class="modal-dialog" id="actionModelContent"></div>
    </div>

    <!-- model Section end -->


    <!-- alert include -->
    <?php include('include/alert.php'); ?>

    <!-- ************************* Required JavaScript Files ************************** -->


    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/modernizr.js"></script>
    <script src="assets/js/moment.js"></script>

    <!-- Overlay Scroll JS -->
    <script src="assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js"></script>
    <script src="assets/vendor/overlay-scroll/custom-scrollbar.js"></script>

    <!-- Bootstrap Select JS -->
    <script src="assets/vendor/bs-select/bs-select.min.js"></script>
    <script src="assets/vendor/bs-select/bs-select-custom.js"></script>

    <!-- Main Js Required -->
    <script src="assets/js/main.js"></script>

    <!-- alert js -->
    <script src="assets/js/alert.js"></script>
    <script src="assets/js/error.js"></script>
    <script src="assets/js/validate.js"></script>
    <script src="assets/js/nTost.js"></script>

    <script>
        let informationChangeValue = "";

        // updateSelectPeaper();

        // function updateSelectPeaper() {
        //     PeaperData = "peaperManage=" + "&type=updateSelectPeaper";
        //     $.post("sql/process.php", PeaperData, function(response, status) {
        //         $('#activePeaper').html(response);
        //     });
        // }

        // function ActivePeaper(data) {
        //     PeaperData = "peaperManage=" + "&type=selectActivePeaper" + "&data=" + data;
        //     $.post("sql/process.php", PeaperData, function(response, status) {
        //         response == ' success' ? nTost({
        //             type: 'success',
        //             titleText: 'SuccessFully Select Peaper'
        //         }) : nTost({
        //             type: 'error',
        //             titleText: 'Error Select Peaper'
        //         });
        //         updateSelectPeaper();
        //     });
        // }

        function updateModelContent(type, data = null) {
            if (true) {
                formData = data == null ? "loadModelDataStManage=" + "&type=" + type : "loadModelDataStManage=" + "&type=" + type + "&data=" + data;
                $.post("sql/process.php", formData, function(response, status) {
                    if (type == 'viweStuInfo') {
                        $('#modelMainContentxl').html(response);
                        $('#modelMainxl').modal('show');
                    } else {
                        $('#modelMainContent').html(response);
                        $('#modelMain').modal('show');
                    }
                });
            }
            // if (type == 'regStu') {
            //     formData = data == null ? "loadModelDataStManage=" + "&type=" + type : "loadModelDataStManage=" + "&type=" + type + "&data=" + data;
            //     $.post("sql/process.php", formData, function(response, status) {
            //         $('#modelMainContent').html(response);
            //         $('#modelMain').modal('show');
            //     });
            // } else if (type == 'regStuSearch') {
            //     formData = "loadModelDataStManage=" + "&type=" + type + "&data=" + data;
            //     $.post("sql/process.php", formData, function(response, status) {
            //         $('#modelMainContent').html(response);
            //         $('#modelMain').modal('show');
            //     });
            // }
        }

        ShowBody();

        function ShowBody(data = null, limit = null) {
            $('#table-content-change').html("<center><img src='assets/img/gif/loding.gif' width='300' alt='' srcset=''></center>");

            var searchval = document.querySelector('.input-group .searchInp').value;
            data = searchval == "" ? null : searchval;
            var checkedinp = document.querySelector('#sub-nav-body1 input:checked').value;
            var type = checkedinp == 1 ? "userManage" : (checkedinp == 2 ? 'rankingManage' : (checkedinp == 3 ? "regStu" : "undefind"));
            var limitData = document.querySelector('.input-group #limitData').value;
            limitData = limit == null ? limitData : limit;

            formData = data == null ? "changeUserManageTable=" + type + '&limidData=' + limitData : "changeUserManageTable=" + type + "&data=" + data + '&limidData=' + limitData;
            $.post("sql/process.php", formData, function(response, status) {
                $('#table-content-change').html(response);
                checkedinp == 2 ? ShowRankBody() : null;
            });
        }

        function search(type) {
            const value = document.querySelector('#searchInp2').value;
            if (value.length > 2 && validateEmail(value)) {
                document.getElementById('searchInp2Search').classList.add('d-none');
                document.getElementById('searchInp2Snip').classList.remove('d-none');
                updateModelContent('regStuSearch', value);
            } else {
                nTost({
                    type: 'error',
                    titleText: 'Pleace enter valied email'
                });
            }
        }

        function validateEmail(email) {
            const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
            return emailRegex.test(email);
        }

        function add(self, type) {
            formData = "add=" + "&type=" + type + "&data=" + self;
            $.post("sql/process.php", formData, function(response, status) {
                $('#modelMainContent').html(response);
                $('#modelMain').modal('show');
            });
        }

        // function viwe(){
        //     updateModelContent('viweUser');
        // }


        function userAction(action, id = null) {
            let deleteModelContent = `
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSmLabel">Confourm Delete User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="userAction('confourmDelete',${id})"><i class='bi bi-trash text-white fs-6'></i>&nbsp;Delete</button>
                </div>
            </div>`;

            let changeInstiModelContent = `
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSmLabel">Change user institute</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id='changeInforModelBody'>
                    <select onchange="informationChangeValue = this.value" class="form-select" id='instiDta'>
                        
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="userAction('changeInsti',${id})"><i class='bi bi-repeat text-white fs-6'></i>&nbsp;Change</button>
                </div>
            </div>`;

            console.log(action, " is ", id);
            if (action == 'showDeleteAlert') {

                $('#actionModelContent').html(deleteModelContent);
                document.getElementById('actionModelContent').classList.add('modal-sm');
                $('#actiomModel').modal('show');

            } else if (action == 'showChangeInstiAlert') {

                $('#actionModelContent').html(changeInstiModelContent);
                document.getElementById('actionModelContent').classList.add('modal-sm');
                formData = "getinstitute=";
                $.post("sql/process.php", formData, function(response, status) {
                    $('#instiDta').html(response);
                });
                $('#actiomModel').modal('show');

            } else if (action == 'confourmDelete') {

                formData = "userAction=" + "&type=" + action + "&id=" + id;
                $.post("sql/process.php", formData, function(response, status) {
                    console.log(response);
                    ShowBody();
                    response.trim() == "success" ?
                        nTost({
                            type: 'success',
                            titleText: 'Successfully Deleted user'
                        }) :
                        nTost({
                            type: 'error',
                            titleText: 'Error Deleted user'
                        });
                });
                $('#actiomModel').modal('hide');
                $('#modelMainxl').modal('hide');


            } else if (action == 'changeInsti') {
                if (informationChangeValue.length > 2) {
                    formData = "userAction=" + "&type=" + action + "&id=" + id + "&value=" + informationChangeValue;
                    $.post("sql/process.php", formData, function(response, status) {
                        console.log(response)
                        updateModelContent(`viweStuInfo`, id);
                        ShowBody();
                        response.trim() == "success" ?
                            nTost({
                                type: 'success',
                                titleText: 'Successfully change user institute',
                            }) :
                            nTost({
                                type: 'error',
                                titleText: 'Error change user institute',
                            });
                    });
                    $('#actiomModel').modal('hide');
                } else {
                    nTost({
                        type: 'error',
                        titleText: 'Plese Select the institute!',
                    });
                }

            }
        }

        function testClearFunction() {
            formData = "ClearPaymentImage="
            $.post("sql/clearData.php", formData, function(response, status) {
                console.log(response)
            });
        }



        // finished ##############

        function ShowRankBody(data = null, limit = null) {
            $('#rank_Body').html("<center><img src='assets/img/gif/loding.gif' width='300' alt='' srcset=''></center>");
            var searchval = document.querySelector('.input-group .searchInp').value;
            var limitData = document.querySelector('.input-group #limitData').value;
            limitData = limit == null ? limitData : limit;
            data = searchval == "" ? null : searchval;
            var type = document.querySelector('#sub-nav-body2 input:checked').value;
            formData = data == null ? "changeRankingData=" + "&type=" + type + "&limit=" + limitData : "changeRankingData=" + "&type=" + type + "&data=" + data + "&limit=" + limitData;
            $.post("sql/process.php", formData, function(response, status) {
                $('#rank_Body').html(response);
            });
        }

        function getClassList() {
            formData = "getClassList=";
            $.post("sql/process.php", formData, function(response, status) {
                $('#class').html(response);
            });
        }

        function loadGroup() {
            $(document).ready(function() {
                formData = "getGroupList=";
                $.post("sql/process.php", formData, function(response, status) {
                    $('#group').html(response);
                });
            });
        }

        function enable(self, type) {
            formData = "enableWith=" + "&type=" + type + "&data=" + self;
            $.post("sql/process.php", formData, function(response, status) {
                ShowBody();
                nTost({
                    type: 'success',
                    titleText: 'Successfully enable peaper'
                });
            });
        }

        function disable(self, type) {
            formData = "disableWith=" + "&type=" + type + "&data=" + self;
            $.post("sql/process.php", formData, function(response, status) {
                ShowBody();
                nTost({
                    type: 'success',
                    titleText: 'Successfully disable peaper'
                });
            });
        }

        function end(self, type) {
            formData = "endWith=" + "&type=" + type + "&data=" + self;
            $.post("sql/process.php", formData, function(response, status) {
                ShowBody();
                nTost({
                    type: 'success',
                    titleText: 'Successfully end the peaper'
                });
            });
        }

        function update(self, type) {
            document.getElementById(self).querySelector('i').classList.add('rotatr-continuar');
            if (type == 'addmarks') {
                var marksId = self.replace(/\s/g, '');
                var marks = document.getElementById(marksId).value;
                if (marks == null || marks == undefined || marks.length == 0) {
                    document.getElementById(self).querySelector('i').classList.remove('rotatr-continuar');
                    nTost({
                        type: 'error',
                        titleText: 'Plece Enter Marks and Try again!'
                    });
                } else {
                    formData = "updateWith=" + "&type=" + type + 'Update' + "&data=" + self + "&marks=" + marks;
                    $.post("sql/process.php", formData, function(response, status) {
                        document.getElementById(self).querySelector('i').classList.remove('rotatr-continuar');
                        response == ' success' ? nTost({
                            type: 'success',
                            titleText: 'Successfully updated'
                        }) : nTost({
                            type: 'error',
                            titleText: 'Error updated'
                        });
                    });
                }
            } else if (type == 'addmarksNotReg') {
                if (self.split(' ')[0] == 'addNewStu') {
                    var stuData = document.querySelectorAll('#addNewStu');
                    var finaly = true;
                    for (var count = 0; count < stuData.length; count++) {
                        if (stuData[count].value != '') {
                            stuData[count].classList.toggle("is-valid", true);
                            stuData[count].classList.toggle("is-invalid", false);
                        } else {
                            stuData[count].classList.toggle("is-valid", false);
                            stuData[count].classList.toggle("is-invalid", true);
                            finaly = false;
                        }
                    }
                    if (finaly) {
                        const formData = new FormData();
                        formData.append("updateWith", "");
                        formData.append("methord", "insert");
                        formData.append("type", type + "Update");
                        formData.append("data", self);

                        for (let count = 0; count < stuData.length; count++) {
                            formData.append(stuData[count].name, stuData[count].value);
                        }
                        $.ajax({
                            url: 'sql/process.php',
                            type: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                document.getElementById(self).querySelector('i').classList.remove('rotatr-continuar');
                                for (let count = 0; count < stuData.length; count++) {
                                    stuData[count].value = "";
                                    stuData[count].classList.toggle("is-valid", false);
                                }
                                // console.log(response);
                                response == ' success' ? nTost({
                                    type: 'success',
                                    titleText: 'Successfully updated'
                                }) : (response == ' alredy added') ? nTost({
                                    type: 'info',
                                    titleText: 'Alredy add this student'
                                }) : nTost({
                                    type: 'error',
                                    titleText: 'Error updated'
                                });
                            }
                        });
                    }
                } else {
                    var marksId = self.replace(/\s/g, '');
                    var marks = document.getElementById("_" + marksId);
                    if (marks.value != "") {
                        const formData = new FormData();
                        formData.append("updateWith", "");
                        formData.append("methord", "update");
                        formData.append("type", type + "Update");
                        formData.append("data", self);
                        // console.log(self);
                        formData.append("marks", marks.value);
                        // console.log('passed');
                        $.ajax({
                            url: 'sql/process.php',
                            type: 'POST',
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function(response) {
                                document.getElementById(self).querySelector('i').classList.remove('rotatr-continuar');
                                // console.log(response);
                                response == ' success' ? nTost({
                                    type: 'success',
                                    titleText: 'Successfully updated'
                                }) : nTost({
                                    type: 'error',
                                    titleText: 'Error updated'
                                });
                            }
                        });
                    } else {
                        ocument.getElementById(self).querySelector('i').classList.remove('rotatr-continuar');
                        marks.classList.toggle("is-valid", false);
                        marks.classList.toggle("is-invalid", true);
                        nTost({
                            type: 'error',
                            titleText: 'Plece enter marks'
                        });
                    }
                }
            } else {
                // optionaly use 
                // formData = "updateWith=" + "&type=" + type + 'Update' + "&data=" + self;
                // $.post("sql/process.php", formData, function(response, status) {
                //     console.log(response);
                //     response == ' success' ? nTost({
                //         type: 'success',
                //         titleText: 'Successfully updated'
                //     }) : nTost({
                //         type: 'error',
                //         titleText: 'Error updated'
                //     });
                // });
            }
        }



        // finished 2 ***********************************

        // function showImage(src) {
        //     nthj(6, src);
        // }

        // window.onload = function() {
        //     url_data = window.location.search;
        //     if (url_data == '?success_login') {
        //         history.pushState({
        //             page: 'new-page'
        //         }, 'New Page', './');
        //         nthj(3);
        //     } else if (url_data == '?success_register') {
        //         history.pushState({
        //             page: 'new-page'
        //         }, 'New Page', './');
        //         nthj(4);
        //     }
        // };

        // function getinstitute() {
        //     formData = "getinstitute=";
        //     $.post("sql/process.php", formData, function(response, status) {
        //         $('#classinstiopt').html(response);
        //     });
        // }

        // function update(value, type) {
        //     if (type == 'winner') {

        //     }
        //     formData = "lessonUpdateAlert=" + '&data1=' + data1 + '&data2=' + data2;
        //     $.post("sql/process.php", formData, function(response, status) {
        //         $('#updateLessonAlert').html(response);
        //         document.getElementById('clickupdateLesson').click();
        //     });
        // }

        // function del(self, type) {
        //     formData = "deleteWith=" + "&type=" + type + "&data=" + self;
        //     $.post("sql/process.php", formData, function(response, status) {
        //         // console.log(response);
        //         ShowBody();
        //     });
        // }

        // function update(self, type) {
        //     updateModelContent(type + 'Update', self);
        // }

        // function viweMore(id, type) {
        //     var options;
        //     var chart;
        //     formData = "viewMore=" + "&type=lesson" + '&id=' + id;
        //     $.post("sql/process.php", formData, function(response, status) {
        //         $('#mainModalAlert').html(response);
        //         loadaccess();
        //         loadGroup();
        //         loadScript('assets/vendor/bs-select/bs-select-custom.js');
        //         loadScript('assets/vendor/bs-select/bs-select.min.js');
        //         formData = "getChartVariyable=" + "&type=lessonViwes" + '&id=' + id;
        //         $.post("sql/process.php", formData, function(response, status) {
        //             if (status) {
        //                 var options = response.lesSummary1;
        //                 var chart1 = new ApexCharts(
        //                     document.querySelector("#lessonSummary"),
        //                     options
        //                 );
        //                 setTimeout(function() {
        //                     chart1.render();
        //                 }, 500);

        //                 var options = response.lesSummary2;
        //                 var chart2 = new ApexCharts(
        //                     document.querySelector("#lessonSummary2"),
        //                     options
        //                 );
        //                 setTimeout(function() {
        //                     chart2.render();
        //                 }, 500);
        //             }
        //         });
        //         document.getElementById('clickShowModel').click();
        //     });
        // }

        // $(".cansal").click(function(event) {
        //     event.preventDefault();
        //     const forms = document.querySelectorAll('#myForm');
        //     forms.forEach((form) => {
        //         const elements = form.elements;

        //         for (let i = 0; i < elements.length; i++) {
        //             const element = elements[i];

        //             switch (element.type) {
        //                 case 'text':
        //                 case 'password':
        //                 case 'email':
        //                 case 'file':
        //                 case 'number':
        //                     element.value = '';
        //                     break;
        //                 case 'checkbox':
        //                 case 'radio':
        //                     element.checked = false;
        //                     break;
        //                 case 'select':
        //                     element.value = '';
        //                     break;
        //                     // Add more cases for other input types if needed
        //             }
        //         }
        //     });
        // });

        function changeImage() {
            var image_prevew = document.getElementById('image-preview');
            const [file] = imgInp.files
            if (file) {
                image_prevew.src = URL.createObjectURL(file)
            }
        }
    </script>

    <script>

    </script>


</body>

</html>