<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'addsnippet'; ?>

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
                    <div class="row my-3 text-center">
                        <div class="col-xxl-3 col-md-3 col-sm-6 col-6 mb-3">
                            <button class="btn btn-success w-100" onclick="updateModelContent('insti','insert')" <?php echo ($adminType[0] == 'owner' || $adminType[0]=='editor') ? null : "disabled"?>><i class="bi bi-plus"></i>&nbsp;Add institute</button>
                        </div>
                        <div class="col-xxl-3 col-md-3 col-sm-6 col-6 mb-3">
                            <button class="btn btn-success w-100" onclick="updateModelContent('class','insert')" <?php echo ($adminType[0] == 'owner' || $adminType[0]=='editor') ? null : "disabled"?>><i class="bi bi-plus"></i>&nbsp;Add Class</button>
                        </div>
                        <div class="col-xxl-3 col-md-3 col-sm-6 col-6 mb-3">
                            <button class="btn btn-success w-100" onclick="updateModelContent('group','insert')" <?php echo ($adminType[0] == 'owner' || $adminType[0]=='editor') ? null : "disabled"?>><i class="bi bi-plus"></i>&nbsp;Add Group</button>
                        </div>
                        <div class="col-xxl-3 col-md-3 col-sm-6 col-6 mb-3">
                            <button class="btn btn-success w-100" onclick="updateModelContent('winner','insert')"><i class="bi bi-plus"></i>&nbsp;Add Winner</button>
                        </div>
                    </div>
                    <!-- Row end -->

                    <!-- Row start -->
                    <div class="row item-center">
                        <div class="sub-nav">
                            <div class="sub-nav-body" id="sub-nav-body">
                                <div class="radio-btn notifiradio">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-1" value="1" onclick="ShowBody()" checked>
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-2" value="2" onclick="ShowBody()">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-3" value="3" onclick="ShowBody()">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-4" value="4" onclick="ShowBody()">
                                    <div class="ul">
                                        <label class="text-overflow" for="sub_nav-1">Institute Manage</label>
                                        <label class="text-overflow" for="sub_nav-2">Class Manage</label>
                                        <label class="text-overflow" for="sub_nav-3">Group Manage</label>
                                        <label class="text-overflow" for="sub_nav-4">Winner Manage</label>
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
                                            <input type="text" class="form-control searchInp" style="background-color: #dae0e9;" placeholder="Search anything" onkeyup="ShowBody(this.value)">
                                            <button class="btn" type="button">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <!-- Search input group end -->

                                    </div>
                                </div>
                                <div class="card-body" id="table-content-change"><!-- change table content--></div>
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

    <script>
        function showImage(src) {
            nthj(6, src);
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

        function getinstitute() {
            formData = "getinstitute=";
            $.post("sql/process.php", formData, function(response, status) {
                $('#classinstiopt').html(response);
            });
        }

        function getClassList() {
            formData = "getClassList=";
            $.post("sql/process.php", formData, function(response, status) {
                $('#grouphide').html(response);
            });
        }

        // function update(value, type) {
        //     if (type == 'winner') {

        //     }
        //     formData = "lessonUpdateAlert=" + '&data1=' + data1 + '&data2=' + data2;
        //     $.post("sql/process.php", formData, function(response, status) {
        //         $('#updateLessonAlert').html(response);
        //         document.getElementById('clickupdateLesson').click();
        //     });
        // }

        function updateModelContent(type, method = null) {
            if (type == 'insti') {
                formData = method == 'insert' ? "loadModelDataInsert=" + "&Type=" + type : (method == 'update' ? "loadModelDataUpdate=" + "&Type=" + type : null);
                $.post("sql/process.php", formData, function(response, status) {
                    $('#modelMainContent').html(response);
                    $('#modelMain').modal('show');
                });
            } else if (type == 'class') {
                formData = method == 'insert' ? "loadModelDataInsert=" + "&Type=" + type : (method == 'update' ? "loadModelDataUpdate=" + "&Type=" + type : null);
                $.post("sql/process.php", formData, function(response, status) {
                    $('#modelMainContent').html(response);
                    getinstitute();
                    $('#modelMain').modal('show');
                });
            } else if (type == 'group') {
                formData = method == 'insert' ? "loadModelDataInsert=" + "&Type=" + type : (method == 'update' ? "loadModelDataUpdate=" + "&Type=" + type : null);
                $.post("sql/process.php", formData, function(response, status) {
                    $('#modelMainContent').html(response);
                    getClassList();
                    loadScript('assets/vendor/bs-select/bs-select.min.js');
                    loadScript('assets/vendor/bs-select/bs-select-custom.js');
                    $('#modelMain').modal('show');
                });
            } else if (type == 'winner') {
                formData = method == 'insert' ? "loadModelDataInsert=" + "&Type=" + type : (method == 'update' ? "loadModelDataUpdate=" + "&Type=" + type : null);
                $.post("sql/process.php", formData, function(response, status) {
                    $('#modelMainContent').html(response);
                    $('#modelMain').modal('show');
                });
            }
        }

        ShowBody();

        function ShowBody(data = null) {
            var searchval = document.querySelector('.input-group .searchInp').value;
            data = searchval == "" ? null : searchval;
            var checkedinp = document.querySelector('.sub-nav-body input:checked').value;
            var type = checkedinp == 1 ? "InstiManage" : (checkedinp == 2 ? 'ClassManage' : (checkedinp == 3 ? "GroupManage" : "WinnerManage"));
            formData = data == null ? "changeSnippitManageTable=" + type : "changeSnippitManageTable=" + type + "&data=" + data;
            $.post("sql/process.php", formData, function(response, status) {
                $('#table-content-change').html(response);
            });
        }

        function enable(self, type) {
            formData = "enableWith=" + "&type=" + type + "&data=" + self;
            $.post("sql/process.php", formData, function(response, status) {
                ShowBody();
            });
        }

        function disable(self, type) {
            formData = "disableWith=" + "&type=" + type + "&data=" + self;
            $.post("sql/process.php", formData, function(response, status) {
                ShowBody();
            });
        }

        // function serchinp(value) {
        //     var searchval = document.querySelector('.input-group .searchInp').value;
        //     formData = "UpdateLessonContent=" + searchval;
        //     $.post("sql/process.php", formData, function(response, status) {
        //         $('#table-content-change').html(response);
        //     });
        // }

        $(".cansal").click(function(event) {
            event.preventDefault();
            const forms = document.querySelectorAll('#myForm');
            forms.forEach((form) => {
                const elements = form.elements;

                for (let i = 0; i < elements.length; i++) {
                    const element = elements[i];

                    switch (element.type) {
                        case 'text':
                        case 'password':
                        case 'email':
                        case 'file':
                        case 'number':
                            element.value = '';
                            break;
                        case 'checkbox':
                        case 'radio':
                            element.checked = false;
                            break;
                        case 'select':
                            element.value = '';
                            break;
                            // Add more cases for other input types if needed
                    }
                }
            });
        });

        function changeImage() {
            var image_prevew = document.getElementById('image-preview');
            const [file] = imgInp.files
            if (file) {
                image_prevew.src = URL.createObjectURL(file)
            }
        }
    </script>


</body>

</html>