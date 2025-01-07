<?php
include_once('sql/function.php');
$dashbord = GetActive('dashbord');
$atendent = GetActive('atendent');
$notification = GetActive('notification');
$massage = GetActive('massage');
$contact = GetActive('contact');
$setting = GetActive('setting');
$lessonManage_sub = GetActive('lessonManage', 'sub');
$addsnippet_sub = GetActive('addsnippet', 'sub');
$userManagement_sub = GetActive('userManagement', 'sub');
$peaperManagement_sub = GetActive('peaperManagement', 'sub');
(GetActive('addsnippet') != '' || GetActive('lessonManage') != '') || GetActive('peaperManagement') || GetActive('userManagement') ? $addcontent = "active-page-link" : $addcontent = "";
// (GetActive('updatelesson') != '') ? $updatecontent = "active-page-link" : $updatecontent = "";

?>
<!-- Sidebar wrapper start -->
<nav class="sidebar-wrapper">

    <!-- Sidebar brand starts -->
    <div class="sidebar-brand">
        <a href="index.php" class="logo">
            <img src="assets/images/logo.png" alt="Admin Dashboards" />
        </a>
    </div>
    <!-- Sidebar brand starts -->

    <!-- Sidebar menu starts -->
    <div class="sidebar-menu">
        <div class="sidebarMenuScroll">
            <ul>
                <li class="<?php echo $dashbord; ?>">
                    <a class="sidea" href="index.php">
                        <i class="bi bi-house"></i>
                        <span class="menu-text">Dashboards</span>
                    </a>
                </li>
                <li class="<?php echo $atendent; ?>">
                    <a class="sidea" href="atendent.php">
                        <i class="bi bi-house"></i>
                        <span class="menu-text">Atendent</span>
                    </a>
                </li>
                <!-- <li class="<?php // echo $lesson; 
                                ?>">
				<a href="addLesson.php">
                    <i class="bi bi-camera-video"></i>
					<span class="menu-text">Add Content</span>
				</a>
			</li> -->
                <li class="sidebar-dropdown sidebar-dropdown-arror <?php echo $addcontent; ?>">
                    <a class="sidea" href="#">
                        <i class="bi bi-plus-circle"></i>
                        <span class="menu-text ">Site Management</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="userManagement.php" class="<?php echo $userManagement_sub; ?>">User Management</a>
                            </li>
                            <li>
                                <a href="lessonManagement.php" class="<?php echo $lessonManage_sub; ?>">Lesson Management</a>
                            </li>
                            <li>
                                <a href="peaperManagement.php" class="<?php echo $peaperManagement_sub; ?>">Peaper Management</a>
                            </li>
                            <li>
                                <a href="addsnippet.php" class="<?php echo $addsnippet_sub; ?>">Snippet Management</a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- <li class="sidebar-dropdown sidebar-dropdown-arror <?php //echo $updatecontent; 
                                                                        ?>">
                    <a href="#">
                        <i class="bi bi-plus-circle"></i>
                        <span class="menu-text ">update Content</span>
                    </a>
                    <div class="sidebar-submenu">
                        <ul>
                            <li>
                                <a href="lessonManagement.php" class="<?php //echo $updatelesson_sub; 
                                                                        ?>">Update Lessons</a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <li class="<?php echo $notification; ?>">
                    <a class="sidea" href="notification.php">
                        <i class="bi bi-house"></i>
                        <span class="menu-text">Notofication</span>
                    </a>
                </li>
                <!-- <li class="<?php echo $massage; ?>">
                    <a class="sidea" href="massage.php">
                        <i class="bi bi-chat-text"></i>
                        <span class="menu-text">Massage</span>
                    </a>
                </li> -->
                <li class="<?php echo $contact; ?>">
                    <a class="sidea" href="contact.php">
                        <i class="bi bi-telephone "></i>
                        <span class="menu-text">Contact Us</span>
                    </a>
                </li>
                <li class="<?php echo $setting; ?>">
                    <a class="sidea" href="setting.php">
                        <i class="bi bi-gear "></i>
                        <span class="menu-text">Setting Us</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar menu ends -->

</nav>
<!-- Sidebar wrapper end -->