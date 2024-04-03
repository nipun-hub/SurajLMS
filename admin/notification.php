<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'notification'; ?>

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

    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="assets/vendor/bs-select/bs-select.css" />

    <!-- Title -->
    <title>Surajskumara.lk | Admin</title>

    <?php include('include/header.php'); ?>

    <link rel="stylesheet" href="assets/css/alert.css">

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
                <div class="content-wrapper ">


                    <!-- Row start -->
                    <div class="row item-center">
                        <div class="sub-nav">
                            <div class="sub-nav-body" id="sub-nav-body">
                                <div class="radio-btn notifiradio">
                                    <input type="radio" name="sub_nav" id="sub_nav-1" value="1" onclick="showBody()" checked>
                                    <input type="radio" name="sub_nav" id="sub_nav-2" value="2" onclick="showBody()">
                                    <?php echo $adminType[0] == "owner" || $adminType[0] == 'developer' ? "<input type='radio' name='sub_nav' id='sub_nav-3' value='3' onclick='showBody()'>" : null; ?>
                                    <!-- <input type="radio" name="sub_nav" onchange="" id="sub_nav-2" value="2" onclick="showBody(2)"> -->
                                    <div class="ul">
                                        <label class="text-overflow" for="sub_nav-1">Payment Request </label>
                                        <label class="text-overflow" for="sub_nav-2">Insti Register Request</label>
                                        <?php echo $adminType[0] == "owner" || $adminType[0] == 'developer' ? "<label class='text-overflow' for='sub_nav-3'>Admin Register Request</label>" : null; ?>
                                        <!-- <label class='text-overflow' for='sub_nav-2'>Lesson Request </label> -->
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Row end -->

                    <!-- Row start -->
                    <div class="row admin-table">
                        <div class="col-12">

                            <!-- notification Request -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"></div>
                                    <div class="search-container">

                                        <!-- Search input group start -->
                                        <div class="input-group">
                                            <input type="text" class="form-control searchInp" style="background-color: #dae0e9;" placeholder="Search anything" onkeyup="showBody(this.value)">
                                            <button class="btn" type="button">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <!-- Search input group end -->

                                    </div>
                                </div>
                                <div class="card-body" id="table-content-change"></div>
                            </div>
                            <!-- notification Request -->


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
    <script src="assets/vendor/apex/apexcharts.min.js"></script>
    <!-- <script src="assets/vendor/apex/custom/sales/salesGraph.js"></script> -->
    <!-- <script src="assets/vendor/apex/custom/sales/revenueGraph.js"></script> -->
    <!-- <script src="assets/vendor/apex/custom/sales/taskGraph.js"></script> -->

    <!-- Input Mask JS -->
    <!-- <script src="assets/vendor/input-masks/cleave.min.js"></script> -->
    <!-- <script src="assets/vendor/input-masks/cleave-phone.js"></script> -->
    <!-- <script src="assets/vendor/input-masks/cleave-custom.js"></script> -->

    <!-- Bootstrap Select JS -->
    <script src="assets/vendor/bs-select/bs-select.min.js"></script>
    <script src="assets/vendor/bs-select/bs-select-custom.js"></script>


    <!-- ajex -->
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> -->

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
        showBody();

        function showBody(data = null) {
            var radinpval = document.querySelector('.radio-btn input[type=radio]:checked').value;
            formData = radinpval == 1 ? (data != null ? "UpdateNotifiTableContent=" + "&type=" + "updatePayment" + "&data=" + data : "UpdateNotifiTableContent=" + "&type=" + "updatePayment") : (radinpval == 2 ? (data != null ? "UpdateNotifiTableContent=" + "&type=" + "updateInstiReg" + "&data=" + data : "UpdateNotifiTableContent=" + "&type=" + "updateInstiReg") : (radinpval == 3 ? (data != null ? "UpdateNotifiTableContent=" + "&type=" + "updateAdminReg" + "&data=" + data : "UpdateNotifiTableContent=" + "&type=" + "updateAdminReg") : null));
            $.post("sql/process.php", formData, function(response, status) {
                $('#table-content-change').html(response);
            });
        }

        function Ignored(val1, val2) {
            PassData = "Ignored=" + "&type=" + val1 + "&id=" + val2;
            $.post("sql/process.php", PassData, function(response, status) {
                console.log(response);
                if (response == ' success Ignored insti' || response == ' success aprued insti') {
                    showBody();
                } else if (response == ' success Ignored payment' || response == ' success aprued payment') {
                    showBody();
                }
            });

        }


        function aprued(type, id) {
            PassData = "aprued=" + "&type=" + type + "&id=" + id;
            $.post("sql/process.php", PassData, function(response, status) {
                console.log(response);
                if (response == ' success Ignored insti' || response == ' success aprued insti') {
                    showBody();
                } else if (response == ' success Ignored payment' || response == ' success aprued payment') {
                    showBody();
                }
            });

            // if (type == 'payment') {
            //     $(document).ready(function() {
            //         formData = "PaymentRequest=" + "&Id=" + id;
            //         $.post("sql/process.php", formData, function(response, status) {
            //             if (response == ' success payment') {
            //                 formData = "updatePayment=";
            //                 $.post("sql/process.php", formData, function(response, status) {
            //                     $('#table-content-change').html(response);
            //                     console.log('updated');
            //                 });
            //             }
            //             // $('#mainCardContent').html(response);
            //         });
            //     });
            // } else if (type == 'active') {

            // } else if (type == 'lesson') {

            // } else if (type == 'register') {

            // } else if (type == 'insti') {

            // }
        }

        // function serchinp() {
        //     var radinpval = document.querySelector('.radio-btn input[type=radio]:checked').value;
        //     var searchval = document.querySelector('.input-group .searchInp').value;
        //     if (radinpval == 1) {
        //         $(document).ready(function() {
        //             formData = "updatePayment=" + searchval;
        //             $.post("sql/process.php", formData, function(response, status) {
        //                 $('#table-content-change').html(response);
        //             });
        //         });
        //     } else if (radinpval == 2) {
        //         console.log(radinpval, searchval);
        //     } else if (radinpval == 3) {
        //         $(document).ready(function() {
        //             formData = "updateInstiReg=" + searchval;
        //             $.post("sql/process.php", formData, function(response, status) {
        //                 $('#table-content-change').html(response);
        //             });
        //         });
        //     }
        // }



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