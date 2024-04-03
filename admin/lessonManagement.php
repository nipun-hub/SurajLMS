<!-- database connection include -->
<?php include('sql/conn.php'); ?>

<!-- navbar_session -->
<?php $_SESSION['active'] = 'lessonManage'; ?>

<?php include_once('sql/function.php'); ?>

<?php include_once('include/main.php'); ?>

<?php
// if (!isset($adminType[0]) ||  $adminType == "admin") {
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
                <div class="content-wrapper ">


                    <!-- Row start -->
                    <!-- <div class="row item-center">
                        <div class="sub-nav">
                            <div class="sub-nav-body" id="sub-nav-body">
                                <div class="radio-btn notifiradio">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-1" value="1" onclick="showBody(1)" checked>
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-2" value="2" onclick="showBody(2)">
                                    <input type="radio" name="sub_nav" onchange="" id="sub_nav-3" value="3" onclick="showBody(3)">
                                    <div class="ul">
                                        <label class="text-overflow" for="sub_nav-1">Payment Request </label>
                                        <label class="text-overflow" for="sub_nav-2">Lesson Request </label>
                                        <label class="text-overflow" for="sub_nav-3">Insti Register Request</label>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div> -->
                    <div class="row my-3 text-center">
                        <div class="col-xxl-3 col-md-4 col-sm-4  col-6 mb-3">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#lessovVidNot" onclick="clickLessons(1)" <?php echo ($adminType[0] == "owner" || $adminType == 'editor') ? null : "disabled"; ?>><i class="bi bi-plus"></i>&nbsp;Add Note & Video</button>
                        </div>
                        <div class="col-xxl-3 col-md-4 col-sm-4  col-6 mb-3">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#lessonClzUpd" onclick="clickLessons(2)" <?php echo ($adminType[0] == "owner" || $adminType == 'editor') ? null : "disabled"; ?>><i class="bi bi-plus"></i>&nbsp;Add ClassWork , File Upload</button>
                        </div>
                        <div class="col-xxl-3 col-md-4 col-sm-4  col-6 mb-3">
                            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addQuizIns" onclick="cickquiz()"><i class="bi bi-plus"></i>&nbsp;Add Quiz</button>
                        </div>
                        <div class="col-xxl-3 col-md-4 col-sm-4  col-6 mb-3">
                            <button class="btn btn-success" disabled><i class="bi bi-plus"></i>&nbsp;Add new Lesson</button>
                        </div>
                    </div>
                    <!-- Row end -->

                    <!-- model section start  -->

                    <!-- model box lesson quiz , classwor and upload note start  -->
                    <div class="modal fade" id="lessovVidNot" tabindex="-1" aria-labelledby="lessovVidNotLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="lessovVidNotLabel">Add Lesson ( Youtube video and Google drive note )</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                                </div>
                                <div class="modal-body add-lesson">

                                    <form id="Formclear">
                                        <!-- Row start -->
                                        <div class="row">
                                            <div class="col-xl-12 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub1">
                                                    <label for="inputIndustryType" class="form-label">Select Lesson Type *</label>
                                                    <select name="lestype" class="form-select" id="inputIndustryType">
                                                        <option value="">Select Type</option>
                                                        <option value="video">Video</option>
                                                        <option value="note">Note</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub1">
                                                    <label for="inputName" class="form-label">Lesson Name *</label>
                                                    <input name="lesname" type="text" class="form-control" id="inputName" placeholder="Enter Lesson Name">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub1">
                                                    <label for="inputEmail" class="form-label">Lesson Link *</label>
                                                    <input name="leslink" type="text" class="form-control" id="inputEmail" placeholder="Enter Lesson Link">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub select-date">
                                                    <div class="form-label float-start">Expire Date&nbsp;&nbsp;
                                                    </div>
                                                    <input name="date-check" class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                    <input name="expdate" type="date" class="form-control datepicker-week-numbers ">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub1">
                                                    <label for="inputweek" class="form-label">Week of lesson *</label>
                                                    <select name="week" class="form-select" id="inputweek">
                                                        <option value="">Select the Week</option>
                                                        <option value="1'st Week">1'st Week</option>
                                                        <option value="2'nd Week">2'nd Week</option>
                                                        <option value="3'rd Week">3'rd Week</option>
                                                        <option value="4'th Week">4'th Week</option>
                                                        <option value="5'th Week">5'th Week</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 tags group w-100">
                                                    <label class="form-label d-flex">Select Show Group *</label>
                                                    <select id="group" class="is-valid select-multiple js-states form-control w-100" title="Select Product Category" multiple="multiple">
                                                        <option>1111111111111111111111111111111111111111111111111111</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please Select the Group</div>
                                                    <div class="valid-feedback">Done!</div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 tags access">
                                                    <label class="form-label d-flex">Give Access *</label>
                                                    <select id="access" class="select-multiple js-states form-control w-100" title="Select Product Category" multiple="multiple">
                                                        <option>1111111111111111111111111111111111111111111111111111</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please Select the access class</div>
                                                    <div class="valid-feedback">Done!</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3 AddLesSub1">
                                                    <label for="inputMessage" class="form-label">Desctiption ( optional )</label>
                                                    <textarea name="lesdict" class="form-control" id="inputMessage" placeholder="Enter Desctiption" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Row end -->
                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="lesson1" onclick="submitAddLesson(1)">Save changes</button>
                                </div>
                                <div class="my-3 rusaltLog mx-3">
                                    <div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
                                    <div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- model box lesson quiz , classwor and upload note end -->

                    <!-- model lesson set quiz  ,  classwork and upload file start-->
                    <div class="modal fade" id="lessonClzUpd" tabindex="-1" aria-labelledby="lessonClzUpdLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="lessonClzUpdLabel">Add the new lesson ( Class Work , file upload and Quiz )</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body add-lesson2">
                                    <form id="Formclear">

                                        <!-- Row start -->
                                        <div class="row">
                                            <div class="col-xl-12 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub2">
                                                    <label for="inputIndustryType" class="form-label">Select Lesson Type *</label>
                                                    <select name="lestype" class="form-select" id="inputIndustryType" onchange="typeChange(2)">
                                                        <option value="quiz">Quiz</option>
                                                        <option value="classwork" selected>ClassWork</option>
                                                        <option value="upload">File Uploade</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub2" id="lesName">
                                                    <label for="inputName" class="form-label">Lesson Name *</label>
                                                    <input name="lesname" type="text" class="form-control" id="inputName" placeholder="Enter Lesson Name">
                                                </div>
                                                <div class="mb-3 quiz tags">
                                                    <label class="form-label d-flex">Select Quiz</label>
                                                    <select class="select-single js-states form-control quizContent" title="Select Product Category" data-live-search="true">
                                                        <option></option>
                                                    </select>
                                                    <div class="invalid-feedback">Please Select the quiz</div>
                                                    <div class="valid-feedback">Done!</div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub2 select-date">
                                                    <div class="form-label float-start">Expire Date&nbsp;&nbsp;
                                                    </div>
                                                    <input name="date-check" class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1">
                                                    <input name="expdate" type="date" class="form-control datepicker-week-numbers ">
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 AddLesSub2">
                                                    <label for="inputweek" class="form-label">Week of lesson *</label>
                                                    <select name="week" class="form-select" id="inputweek">
                                                        <option value="">Select the Week</option>
                                                        <option value="1'st Week">1'st Week</option>
                                                        <option value="2'nd Week">2'nd Week</option>
                                                        <option value="3'rd Week">3'rd Week</option>
                                                        <option value="4'th Week">4'th Week</option>
                                                        <option value="5'th Week">5'th Week</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 tags group2">
                                                    <label class="form-label d-flex">Select show in group</label>
                                                    <select id="group2" class="select-multiple js-states form-control" title="Select Product Category" multiple="multiple">
                                                        <option>111111111111111111111111111111111111111111111111</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please Select the Group</div>
                                                    <div class="valid-feedback">Done!</div>
                                                </div>
                                            </div>
                                            <div class="col-xl-6 col-sm-12 col-12">
                                                <div class="mb-3 tags access2">
                                                    <label class="form-label d-flex">Select access class</label>
                                                    <select id="access2" class="select-multiple js-states form-control" title="Select Product Category" multiple="multiple">
                                                        <option>111111111111111111111111111111111111111111111111111111</option>
                                                    </select>
                                                    <div class="invalid-feedback">Please Select the access</div>
                                                    <div class="valid-feedback">Done!</div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="mb-3 AddLesSub2">
                                                    <label for="inputMessage" class="form-label">Desctiption ( optional )</label>
                                                    <textarea name="lesdict" class="form-control" id="inputMessage" placeholder="Enter Desctiption" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Row end -->

                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" id="cansal2" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" id="lesson2" onclick="submitAddLesson(2)">Submit</button>
                                </div>
                                <div class="my-3 rusaltLog mx-3">
                                    <div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
                                    <div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- model lesson set quiz  ,  classwork and upload file end -->

                    <!-- model add thye quction start -->
                    <div class="modal fade" id="addQuizIns" tabindex="-1" aria-labelledby="addQuizInsLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addQuizInsLabel">Add the quiz</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body ">
                                    <div class="row">
                                        <div class="col-8 add-quiz">

                                            <div class=" card-border">
                                                <form id="finifhQizForm">
                                                    <div class="row">
                                                        <div class="col-xl-6 col-sm-12 col-12">
                                                            <div class="mb-3 finifhQiz">
                                                                <label for="inputName" class="form-label">Quiz Name *</label>
                                                                <input name="qName" type="text" class="form-control" id="inputName" placeholder="Enter Quiz Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-6 col-12">
                                                            <div class="mb-3 finifhQiz">
                                                                <label for="inputName" class="form-label">Time Duration *</label>
                                                                <input name="qTime" type="number" maxlength="2" class="form-control" id="inputName" placeholder="Enter Quiz time duration">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-12 col-sm-12 col-12">
                                                            <div class="mb-3 finifhQiz">
                                                                <label for="inputMessage" class="form-label">Desctiption ( optional )</label>
                                                                <textarea name="qDict" class="form-control" id="inputMessage" placeholder="Enter Desctiption" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Row end -->
                                                </form>
                                            </div>

                                            <div class="card-border">
                                                <form id="Formclear">
                                                    <div class="row">
                                                        <div class="invalid-feedback">Not Maked quction Pleace make quction and try again!</div>
                                                        <div class="col-xl-12 col-sm-12 col-12">
                                                            <div class="mb-3 addQiz">
                                                                <label for="inputName" class="form-label">Quction *</label>
                                                                <input name="qName" type="text" class="form-control" id="inputName" placeholder="Enter Quction">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-12 col-12">
                                                            <div class="mb-3 addQiz">
                                                                <label for="inputName" class="form-label">Option 01 *</label>
                                                                <input name="qOpt1" type="text" class="form-control" id="inputName" placeholder="Enter Option 01">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-12 col-12">
                                                            <div class="mb-3 addQiz">
                                                                <label for="inputName" class="form-label">Option 02 *</label>
                                                                <input name="qOpt2" type="text" class="form-control" id="inputName" placeholder="Enter Option 02">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-12 col-12">
                                                            <div class="mb-3 addQiz">
                                                                <label for="inputName" class="form-label">Option 03 *</label>
                                                                <input name="qOpt3" type="text" class="form-control" id="inputName" placeholder="Enter Option 03">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-12 col-12">
                                                            <div class="mb-3 addQiz">
                                                                <label for="inputName" class="form-label">Option 04 *</label>
                                                                <input name="qOpt4" type="text" class="form-control" id="inputName" placeholder="Enter Option 04">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-sm-12 col-12">
                                                            <div class="mb-3 addQiz addQuizSelect">
                                                                <label for="inputIndustryType" class="form-label">Select Corret Answer *</label> &nbsp;
                                                                <input type="checkbox" name="ansselectcheck" id="" class=" form-check-input">
                                                                <select name="qAns" class="form-select" id="inputIndustryType">
                                                                    <option value="">Select Type</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="mb-3 addQiz">
                                                                <label for="inputMessage" class="form-label">Desctiption ( optional )</label>
                                                                <textarea name="qDict" class="form-control" id="inputMessage" placeholder="Enter Desctiption" rows="3"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="col-12">
                                                            <div class="mb-3 addQiz">
                                                                <input type="checkbox" class="form-check-input" name="FinishedQuiz" id="FinishedQuiz quizcheck">&nbsp;<label class="form-label" for="quizcheck">Finished Make Quiz and Redy To Upload</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Row end -->

                                                    <!-- Form actions footer start -->
                                                    <div class="form-actions-footer">
                                                        <!-- <button id="cansal" class="btn btn-light">Cancel</button> -->
                                                        <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                                        <button class="btn btn-info" id="addQuiz"><i class="bi bi-plus-square"></i> Add</button>
                                                        <button id="quizfinishedBtn" class="btn btn-info quizfinishedBtn">Finished</button>
                                                    </div>
                                                    <!-- Form actions footer end -->

                                                </form>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="row g-1 overflowquiz card-border" id="quisList">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <!-- <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button> -->
                                    <!-- <button type="button" class="btn btn-success" data-bs-dismiss="modal">Save changes</button> -->
                                </div>
                                <div class="my-3 rusaltLog mx-3">
                                    <div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
                                    <div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- model add thye quction end -->

                    <!-- model update lesson start -->
                    <div class="modal fade" id="updateLesson" tabindex="-1" aria-labelledby="updateLessonLabel" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content" id="updateLessonAlert">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="updateLessonLabel">Update Lessson<span class="ModelTitle"></span></h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="col-sm-12 col-12 add-lesson">

                                            <!-- Card start -->
                                            <div class="card">
                                                <div class="card-body">
                                                    <form id="addlesson">

                                                        <!-- Row start -->
                                                        <div class="row">
                                                            <div class="col-12">
                                                                <div class="mb-3">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-bordered m-0">
                                                                            <thead>
                                                                                <tr>
                                                                                    <th>Name</th>
                                                                                    <th>Desctiption </th>
                                                                                    <th>Type</th>
                                                                                    <th>Access Class</th>
                                                                                    <th>Show Groups</th>
                                                                                </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                    <td></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 col-sm-6 col-12">
                                                                <div class="mb-3 AddLesSub">
                                                                    <label for="inputName" class="form-label">Lesson Name *</label>
                                                                    <input name="lesname" type="text" class="form-control" id="inputName" placeholder="Enter Lesson Name">
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 col-sm-6 col-12">
                                                                <div class="mb-3 AddLesSub">
                                                                    <label for="inputIndustryType" class="form-label">Select Lesson Type *</label>
                                                                    <select name="lestype" class="form-select" id="inputIndustryType">
                                                                        <option value="">Select Type</option>
                                                                        <option value="video">Video</option>
                                                                        <option value="note">Note</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-4 col-sm-6 col-12">
                                                                <div class="mb-3 AddLesSub">
                                                                    <label for="inputEmail" class="form-label">Lesson Link *</label>
                                                                    <input name="leslink" type="text" class="form-control" id="inputEmail" placeholder="Enter Lesson Link">
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-6 col-sm-12 col-12">
                                                                <div class="mb-3 tags group3">
                                                                    <label class="form-label d-flex">Select show in group</label>
                                                                    <select id="group3" class="select-multiple js-states form-control" title="Select Product Category" multiple="multiple">
                                                                        <option>111111111111111111111111111111111111111111111111</option>
                                                                    </select>
                                                                    <div class="invalid-feedback">Please Select the Group</div>
                                                                    <div class="valid-feedback">Done!</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-xl-6 col-sm-12 col-12">
                                                                <div class="mb-3 tags access3">
                                                                    <label class="form-label d-flex">Select access class</label>
                                                                    <select id="access3" class="select-multiple js-states form-control" title="Select Product Category" multiple="multiple">
                                                                        <option>111111111111111111111111111111111111111111111111111111</option>
                                                                    </select>
                                                                    <div class="invalid-feedback">Please Select the access</div>
                                                                    <div class="valid-feedback">Done!</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12">
                                                                <div class="mb-3 AddLesSub">
                                                                    <label for="inputMessage" class="form-label">Desctiption ( optional )</label>
                                                                    <textarea name="lesdict" class="form-control" id="inputMessage" placeholder="Enter Desctiption" rows="3"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Row end -->

                                                        <!-- Form actions footer start -->
                                                        <!-- <div class="form-actions-footer"> -->
                                                        <!-- <button id="cansal" class="btn btn-light">Cancel</button> -->
                                                        <!-- <button class="btn btn-success" onclick="submitAddLesson()">Submit</button> -->
                                                        <!-- </div> -->
                                                        <!-- Form actions footer end -->

                                                    </form>

                                                </div>
                                            </div>
                                            <!-- Card end -->

                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-dark" data-bs-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Save changes</button>
                                </div>
                                <div class="my-3 rusaltLog mx-3">
                                    <div class="valid-feedback alert alert-success text-center alert-dismissible fade show py-2">Successfull add the lesson!</div>
                                    <div class="invalid-feedback alert alert-danger text-center alert-dismissible fade show py-2">Failed add the lesson</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- model update lesson end -->

                    <!-- model section end  -->


                    <!-- Row start -->
                    <div class="row admin-table">
                        <div class="col-12">

                            <!-- notification Request -->
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Lessons List</div>
                                    <div class="search-container">

                                        <!-- Search input group start -->
                                        <div class="input-group">
                                            <input type="text" class="form-control searchInp" style="background-color: #dae0e9;" placeholder="Search anything" onkeyup="serchinp()">
                                            <button class="btn" type="button">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                        <!-- Search input group end -->

                                    </div>
                                </div>
                                <div class="card-body" id="table-content-change">
                                </div>
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
    <!-- <script src="assets/vendor/apex/apexcharts.min.js"></script> -->
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
        function clickLessons(val) {
            val == 1 ? clearmodeldata('#lessovVidNot') : clearmodeldata('#lessonClzUpd');
            getlessonAttribute();
            typeChange(2);
        }

        function cickquiz() {
            clearmodeldata('#addQuizIns');
            quizpagePnclick();
            getqiozAttribute();
        }
        // load lessod data start 
        loadLessocContent();

        function loadLessocContent() {
            $(document).ready(function() {
                formData = "UpdateLessonContent=";
                $.post("sql/process.php", formData, function(response, status) {
                    $('#table-content-change').html(response);
                });
            });
        }
        // load lessod data end

        // load group data options start 
        loadGroup();

        function loadGroup() {
            $(document).ready(function() {
                formData = "getGroupList=";
                $.post("sql/process.php", formData, function(response, status) {
                    $('#group').html(response);
                    $('#group2').html(response);
                    $('#group3').html(response);
                });
            });
        }
        // load group data options end

        // load the access class data option start 
        loadaccess();

        function loadaccess() {
            $(document).ready(function() {
                formData = "getClassList=";
                $.post("sql/process.php", formData, function(response, status) {
                    $('#access').html(response);
                    $('#access2').html(response);
                    $('#access3').html(response);
                });
            });
        }
        // load the access class data option end

        // load the quiz data options start 
        $(document).ready(function() {
            formData = "getQiozList=";
            $.post("sql/process.php", formData, function(response, status) {
                console.log(response);
                $('.quizContent').html(response);
            });
        });
        // load the quiz data options end

        //  lesson update data start 
        function update(value) {
            var data1 = value.split(' ')[0];
            var data2 = value.split(' ')[1];
            formData = "lessonUpdateAlert=" + '&data1=' + data1 + '&data2=' + data2;
            $.post("sql/process.php", formData, function(response, status) {
                $('#updateLessonAlert').html(response);
                loadaccess();
                loadGroup();
                loadScript('assets/vendor/bs-select/bs-select-custom.js');
                loadScript('assets/vendor/bs-select/bs-select.min.js');
                document.getElementById('clickupdateLesson').click();
            });
        }
        //  lesson update data end

        // load cearhc respons dataa start 
        function serchinp() {
            var searchval = document.querySelector('.input-group .searchInp').value;
            formData = "UpdateLessonContent=" + searchval;
            $.post("sql/process.php", formData, function(response, status) {
                $('#table-content-change').html(response);
            });
        }
        // load cearhc respons dataa end

        // ***************************************** compleate section ***************************

        // update lesson start
        // function Update(val) {
        //     nthj(7, val);
        // }
        // update lesson end


        /// clickImageRespond.forEach((self) => {
        //     self.addEventListener("click", () => {
        //         nthj(6, self.src);
        //     });
        // });

        // function showBody(value) {
        //     var inptutevent = document.querySelectorAll('.admin-table .card');
        //     var inptutevent = document.querySelectorAll('.admin-table .card');
        //     inptutevent.forEach((self) => {
        //         // self.style.display = "none";
        //     });
        //     if (value == 1) {
        //         // inptutevent[0].style.display = "block";
        //         $(document).ready(function() {
        //             formData = "updatePayment=";
        //             $.post("sql/process.php", formData, function(response, status) {
        //                 $('#table-content-change').html(response);
        //                 console.log(response);
        //             });
        //         });
        //     } else if (value == 2) {

        //     } else if (value == 3) {
        //         $(document).ready(function() {
        //             formData = "updateInstiReg=";
        //             $.post("sql/process.php", formData, function(response, status) {
        //                 $('#table-content-change').html(response);
        //                 console.log(response);
        //             });
        //         });
        //     }
        // }

        // function Ignored(val1, val2) {
        //     PassData = "Ignored=" + "&type=" + val1 + "&id=" + val2;
        //     $.post("sql/process.php", PassData, function(response, status) {
        //         if (response == ' success insti') {
        //             $(document).ready(function() {
        //                 formData = "updateInstiReg=";
        //                 $.post("sql/process.php", formData, function(response, status) {
        //                     $('#table-content-change').html(response);
        //                 });
        //             });
        //         } else if (response == ' success payment') {
        //             $(document).ready(function() {
        //                 formData = "updatePayment=";
        //                 $.post("sql/process.php", formData, function(response, status) {
        //                     $('#table-content-change').html(response);
        //                 });
        //             });
        //         }
        //         console.log(response);
        //     });

        // }


        // function aprued(type, id) {
        //     PassData = "aprued=" + "&type=" + type + "&id=" + id;
        //     $.post("sql/process.php", PassData, function(response, status) {
        //         if (response == ' success insti') {
        //             $(document).ready(function() {
        //                 formData = "updateInstiReg=";
        //                 $.post("sql/process.php", formData, function(response, status) {
        //                     $('#table-content-change').html(response);
        //                 });
        //             });
        //         } else if (response == ' success payment') {
        //             $(document).ready(function() {
        //                 formData = "updatePayment=";
        //                 $.post("sql/process.php", formData, function(response, status) {
        //                     $('#table-content-change').html(response);
        //                 });
        //             });
        //         }
        //         console.log(response);
        //     });
        // }/ var clickImageRespond = document.querySelectorAll('.admin-table .notifiImage');


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
    </script>

</body>

</html>