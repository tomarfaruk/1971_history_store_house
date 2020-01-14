<?php

$current = basename($_SERVER["SCRIPT_FILENAME"]);
$index = $categories = $videos = $users = $documents = $images = $setting = $audios =  $admob = $site_config = $push_notifications = "";


if($current == "index.php") $index = "active";
else if(($current == "categories.php") ||($current == "category-form.php")) $categories = "active";
else if(($current == "livetvs.php") ||($current == "livetv-form.php")) $livetvs = "active";
else if(($current == "videos.php") ||($current == "video-form.php")) $videos = "active";
else if($current == "users.php") $users = "active";
else if($current == "audios.php") $audios = "active";
else if($current == "documents.php") $documents = "active";
else if($current == "images.php") $images = "active";
else if($current == "setting.php") $setting = "active";
else if($current == "admob.php") $admob = "active";
else if($current == "site-config.php") $site_config = "active";
else if(($current == "push-notifications.php") || $current == "push-notification-form.php") $push_notifications = "active";

?>

<div class="sidebar">
    <ul class="sidebar-list">
        <li class="<?php echo $index; ?>"><a href="index.php"><i class="ion-ios-pie"></i><span>Dashboard</span></a></li>
        <li class="<?php echo $categories; ?>"><a href="categories.php"><i class="ion-social-buffer"></i><span>Video categories</span></a></li>
        <li class="<?php echo $livetvs; ?>"><a href="livetvs.php"><i class="ion-android-laptop"></i><span>LiveTV</span></a></li>
        <li class="<?php echo $videos; ?>"><a href="videos.php"><i class="ion-android-film"></i><span>Videos</span></a></li>
        <li  class="<?php echo $audios; ?>"><a href="audios.php"><i class="ion-android-film"></i><span>Audios </span></a></li>
        <li  class="<?php echo $documents; ?>"><a href="documents.php"><i class="ion-android-film"></i><span>Documents </span></a></li>
        <li  class="<?php echo $images; ?>"><a href="images.php"><i class="ion-android-film"></i><span>Images </span></a></li>
        <li class="<?php echo $users; ?>"><a href="users.php"><i class="ion-person"></i><span>Register Users</span></a></li>
        <li class="<?php echo $admob; ?>"><a href="admob.php"><i class="ion-cash"></i><span>Admob</span></a></li>
        <li class="<?php echo $push_notifications; ?>"><a href="push-notifications.php"><i class="ion-ios-bell"></i><span>Push Notification</span></a></li>
        <li class="<?php echo $site_config; ?>"><a href="site-config.php"><i class="ion-settings"></i><span>Configuration</span></a></li>
        <li class="<?php echo $setting; ?>"><a href="setting.php"><i class="ion-android-settings"></i><span>Setting</span></a></li>
    </ul>
</div><!--sidebar-->