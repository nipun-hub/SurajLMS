<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'massage'; ?>

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

    <style>
        .chats a {
            color: rgb(0, 110, 255) !important;
        }

        .long-text1 {
            /* margin-right: 8px; */
            /* vertical-align: middle; */
            /* display: inline-block; */
            max-width: 90%;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis
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
                <div class="content-wrapper ">


                    <!-- Row start -->
                    <div class="row">

                        <div class="col-4">
                            <div class="bg-light row border border-2">
                                <div class="col-12">
                                    <div class="search-container my-2">
                                        <div class="input-group w-100">
                                            <input type="text" class="form-control" style="background-color: #dae0e9;" placeholder="Type massage" onkeyup="searchChat(this.value)">
                                            <button class="btn" type="button">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="activity-container m-0 p-0">
                                        <div class="vh-80 overflow-y-auto hide-scroll userBody"></div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-8 bg-light">
                            <div class="pt-2 row">

                                <div class="col-12">
                                    <div class=" overflow-hidden">
                                        <div class="timeline-activity userChat">

                                            <!-- no chat fisplay gif  -->
                                            <div class="col-12">
                                                <div class="mt-1 mx-2">
                                                    <div class="card-body vh60  overflow-y-scroll">
                                                        <ul class="chats">

                                                            <div class="item-center" style="height: 65vh;">
                                                                <div class="text-center">
                                                                    <img src="assets\img\site use/chatFound.gif" alt="" srcset="" width="80%" style="align-item: center;">
                                                                    <p class="tect-center text-dark">Selected chat not found! <br>pleace select the student after send and receime massages </p>
                                                                </div>
                                                            </div>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>

                        </div>
                        <!-- Content wrapper end -->

                        <!-- app footer -->
                        <?php //include('include/footer.php'); 
                        ?>

                    </div>
                    <!-- Content wrapper scroll end -->

                </div>
                <!-- *************
				************ Main container end *************
			************* -->

            </div>


            <!-- alert include -->
            <?php //include('include/alert.php'); ?>

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
            <!-- <script src="assets/js/validate.js"></script> -->
            <script src="chat/javascript/chat.js"></script>

</body>

</html>