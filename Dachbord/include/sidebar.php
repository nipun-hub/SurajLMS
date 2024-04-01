<?php
include_once('sql/function.php');
$dashbord = GetActive('dashbord');
$lesson = GetActive('lesson');
$notification = GetActive('notification');
$profile = GetActive('profile');
$contact = GetActive('contact');
?>
<!-- Sidebar wrapper start -->
<nav class="sidebar-wrapper">

<!-- Sidebar brand starts -->
<div class="sidebar-brand">
    <a href="index.php" class="logo">
        <img src="assets/img/site use/icons/logo.png" alt="Admin Dashboards" />
    </a>
</div>
<!-- Sidebar brand starts -->

<!-- Sidebar menu starts -->
<div class="sidebar-menu">
    <div class="sidebarMenuScroll">
        <ul>
            <li class="<?php echo $dashbord; ?>">
                <a href="index.php">
                    <i class="bi bi-house"></i>
                    <span class="menu-text">Dashboards</span>
                </a>
                <!-- <div class="sidebar-submenu"> -->
                    <!-- <ul> -->
                        <!-- <li> -->
                            <!-- <a href="index.php" class="current-page">Analytics</a> -->
                        <!-- </li> -->
                        <!-- <li> -->
                            <!-- <a href="reports.php">Reports</a> -->
                        <!-- </li> -->
                    <!-- </ul> -->
                <!-- </div> -->
            </li>
            <li class="<?php echo $lesson; ?>">
				<a href="lesson.php">
                    <i class="bi bi-camera-video"></i>
					<span class="menu-text">Lesson</span>
				</a>
			</li>

            <!-- tempary -->
            <li class="<?php echo $notification; ?>">
				<a href="notification.php">
                    <i class="bi bi-bell"></i>
					<span class="menu-text">Notification</span>
				</a>
			</li>
            <li class="<?php echo $profile; ?>">
				<a href="profile.php">
                    <i class="bi bi-person-fill"></i>
					<span class="menu-text">Profile</span>
				</a>
			</li>
            <li class="<?php echo $contact; ?>">
				<a href="contact.php">
                    <i class="bi bi-telephone"></i>
					<span class="menu-text">Contact Us</span>
				</a>
			</li>
        </ul>
    </div>
</div>
<!-- Sidebar menu ends -->

</nav>
<!-- Sidebar wrapper end -->