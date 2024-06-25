<!-- Page header starts -->
<div class="page-header">

    <div class="toggle-sidebar" id="toggle-sidebar"><i class="bi bi-list"></i></div>

    <!-- Breadcrumb start -->
    <ol class="breadcrumb d-md-flex d-none">
        <li class="breadcrumb-item">
            <i class="bi bi-house"></i>
            <a href="index.html">Home</a>
        </li>
        <li class="breadcrumb-item breadcrumb-active" aria-current="page"><?php echo $_SESSION['active'] == 'dashbord' ? 'Dashbord' : ($_SESSION['active'] == 'dashbord' ? "Dashbord" : ($_SESSION['active'] == 'atendent' ? 'Atendent' : ($_SESSION['active'] == 'notification' ? 'Notification' : ($_SESSION['active'] == 'lessonManage' ? 'Lesson Management' : ($_SESSION['active'] == 'addsnippet' ? 'Snippet Management' : ($_SESSION['active'] == 'peaperManagement' ? 'Peaper Management' : ($_SESSION['active'] == 'userManagement' ? 'User Management' : ($_SESSION['active'] == 'profile' ? 'Profile' : ($_SESSION['active'] == 'contact' ? 'Contact Us' : ($_SESSION['active'] == 'massage' ? 'Massage' : null)))))))))); ?></li>
    </ol>
    <!-- Breadcrumb end -->

    <!-- Header actions ccontainer start -->
    <div class="header-actions-container">

        <!-- Leads start -->
        <a href="notification.php" class="leads d-none d-xl-flex">
            <div class="lead-details">You have <span class="count"> 0 </span> new notification </div>
            <span class="lead-icon">
                <i class="bi bi-bell-fill animate__animated animate__swing animate__infinite infinite"></i>
                <b class="dot animate__animated animate__heartBeat animate__infinite"></b>
            </span>
        </a>
        <!-- Leads end -->

        <!-- Header actions start -->
        <ul class="header-actions">
            <?php
            $sql = "SELECT image FROM adminuser WHERE AdminId = $UserId";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $reusalt = $stmt->get_result();
            $row = $reusalt->fetch_assoc();
            $image = empty($row['image']) ? "../admin/assets/img/admin/admin.jpg" : "../admin/assets/img/admin/" . $row['image'];
            ?>
            <li class="dropdown">
                <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                    <span class="user-name d-none d-md-block"><?php echo $UserName; ?></span>
                    <span class="avatar">
                        <img loading="lazy" src="<?php echo $image ?>" alt="Admin">
                        <span class="status online"></span>
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userSettings">
                    <div class="header-profile-actions">
                        <a href="profile.php">Profile</a>
                        <a href="profile.php">Settings</a>
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