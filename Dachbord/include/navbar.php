<!-- Page header starts -->
<div class="page-header">

    <div class="toggle-sidebar" id="toggle-sidebar"><i class="bi bi-list"></i></div>

    <!-- Breadcrumb start -->
    <ol class="breadcrumb d-md-flex d-none">
        <li class="breadcrumb-item">
            <i class="bi bi-house"></i>
            <a href="index.php">Dashbord</a>
        </li>
        <li class="breadcrumb-item breadcrumb-active" aria-current="page"><?php echo ucfirst($_SESSION['active']); ?></li>
    </ol>
    <!-- Breadcrumb end -->

    <!-- Header actions ccontainer start -->
    <div class="header-actions-container">

        <?php
        $today = GetToday('ymd', '-');
        $status = "finished";
        $sql = "SELECT * FROM peaper WHERE DATE_ADD(finishDate, INTERVAL 8 DAY) >  ?  and Status = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $today, $status);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
        ?>
            <a href="#" class="leads ms-0">
                <span class="lead-icon" onclick="search('searchbox')">
                    <i class="text-success bi bi-card-text animate__animated animate__swing animate__infinite infinite fs-5"></i>
                    <b class="dot animate__animated animate__heartBeat animate__infinite"></b>
                </span>
            </a>
        <?php } ?>

        <!-- Search container start -->
        <div class="search-container" hidden>

            <!-- Search input group start -->
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search anything">
                <button class="btn" type="button">
                    <i class="bi bi-search"></i>
                </button>
            </div>
            <!-- Search input group end -->

        </div>
        <!-- Search container end -->

        <!-- Leads start -->
        <?php
        $sql = "SELECT COUNT(NotifiId) as notifiCount FROM notification WHERE UserId = $UserId";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $reusalt = $stmt->get_result();
        $notiCount = ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) ? $row['notifiCount'] : null;
        ?>
        <a href="notification.php" class="leads ms-0">
            <div class="lead-details d-none d-xl-flex">You have <span class="count"> <?php echo $notiCount; ?> </span> new notification </div>
            <span class="lead-icon">
                <i class="bi bi-bell-fill animate__animated animate__swing animate__infinite infinite"></i>
                <b class="dot animate__animated animate__heartBeat animate__infinite"></b>
            </span>
        </a>
        <!-- Leads end -->

        <!-- Header actions start -->
        <ul class="header-actions">
            <li class="dropdown">
                <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                    <span class="user-name d-none d-md-block"><?php echo $_SESSION['username']; ?></span>
                    <span class="avatar">
                        <img src="<?php echo $pict; ?>" alt="User profile picture" onerror="imgNotFound()">
                        <span class="status online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
                    <div class="header-profile-actions">
                        <a href="profile.php">Profile</a>
                        <a href="#">Settings</a>
                        <a href="?logout">Logout</a>
                    </div>
                </div>
            </li>
        </ul>
        <!-- Header actions end -->

    </div>
    <!-- Header actions ccontainer end -->

</div>
<!-- Page header ends -->

<!-- *************************************************************************************** -->

<!-- pending peaper rusalt start -->
<?php
$today = GetToday('ymd', '-');
$status = "finished";
$sql = "SELECT * FROM peaper WHERE DATE_ADD(finishDate, INTERVAL 8 DAY) >  ?  and Status = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $today, $status);
$stmt->execute();
$reusalt = $stmt->get_result();
if ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) {
?>
    <div class="page-header m-3">

        <!-- Breadcrumb start -->
        <ol onclick="search('searchbox')" class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#" class="leads ms-0">
                    <span class="lead-icon">
                        <i class="text-success bi bi-card-text animate__animated animate__swing animate__infinite infinite fs-5"></i>
                        <b class="dot animate__animated animate__heartBeat animate__infinite"></b>
                    </span>
                </a>
            </li>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#" class="d-flex"><span class="text-red"><?php echo $row['peaperName']; ?></span>&nbsp;<span class="d-none d-md-block">Peaper results released</span></a>
                </li>
            </ol>
        </ol>
        <!-- Breadcrumb end -->

        <!-- end section start  -->
        <ul class="header-actions">
            <button class="alert alert-success px-3 py-2 rounded-pill d-flex d-none d-sm-flex" onclick="search('searchbox')"><i class="bi bi-search"></i>&nbsp; <span class="d-sm-block d-none">Search reusalt Now</span></button>
            <i onclick="search('searchbox')" class="bi bi-search text-green me-2"></i>
        </ul>
        <!-- end section end -->

    </div>
<?php } ?>
<!-- pending peaper rusalt ends -->