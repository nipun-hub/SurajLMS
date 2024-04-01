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
	$notiCount = ($reusalt->num_rows > 0 && $row = $reusalt->fetch_assoc()) ? $row['notifiCount'] : null ;
    ?>
    <a href="notification.php" class="leads d-none d-xl-flex">
        <div class="lead-details">You have <span class="count"> <?php echo $notiCount; ?> </span> new notification </div>
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
                <span class="user-name d-none d-md-block"><?php echo $_SESSION['username'];?></span>
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