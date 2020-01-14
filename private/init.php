<?php

ob_start();
session_start();

define("DB_SERVER", "localhost"); // Server Name
define("DB_USER", "root"); // Database User
define("DB_PASS", ""); // Database Password
define("DB_NAME", "bd-1971"); // Database Name

define("UPLOADED_FOLDER", "uploads");    // Image/Video Upload Folder
define("UPLOADED_THUMB_FOLDER", "thumb");   // Thumb Image Upload Folder
define("API_PAGINATION", 5);    // 5 items in the api
define("BACKEND_PAGINATION", 16); // 5 items in the admin panel
define("MAX_IMAGE_SIZE", 1.5);    // Maximum Image Size 1 mb(Max Value of server 16mb(To change open .htaccess file))
define("MAX_VIDEO_SIZE", 15);   // Maximum Image Size 1 mb(Max Value of server 16mb(To change open .htaccess file))
define("DATE_FORMAT", "Y-m-d h:i:s");


define("YOUTUBE_VIDEO", 1);
define("VIMEO_VIDEO", 2);
define("UPLOADED_VIDEO", 3);
define("YOUKU_VIDEO", 4);
define("VIDEO_LINK", 5);


define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH));
define("PUBLIC_PATH", PROJECT_PATH . DIRECTORY_SEPARATOR  . 'public');
define("UPLOAD_FOLDER", PUBLIC_PATH . DIRECTORY_SEPARATOR . UPLOADED_FOLDER . DIRECTORY_SEPARATOR);
define("UPLOAD_FOLDER_THUMB", PUBLIC_PATH . DIRECTORY_SEPARATOR . UPLOADED_FOLDER . DIRECTORY_SEPARATOR . UPLOADED_THUMB_FOLDER . DIRECTORY_SEPARATOR);
define("UPLOAD_LINK", getcwd() . DIRECTORY_SEPARATOR . UPLOADED_FOLDER . DIRECTORY_SEPARATOR);

require_once('models/lib/Database.php');
require_once('models/lib/Helper.php');
require_once('models/lib/Session.php');
require_once('models/lib/Response.php');
require_once('models/lib/Errors.php');
require_once('models/lib/Message.php');
require_once('models/lib/Upload.php');
require_once('models/lib/Mailer.php');
require_once('models/lib/Util.php');
require_once('models/lib/Pagination.php');

require_once('models/Admin.php');
require_once('models/Site_Config.php');
require_once('models/Setting.php');
require_once('models/Push_Notification.php');
require_once('models/Admob.php');
require_once('models/User.php');
require_once('models/Category.php');
require_once('models/audio_category.php'); // audio category 
require_once('models/Doc_category.php'); // doc category 
require_once('models/Img_category.php'); // imgcategory 
require_once('models/LiveTV.php');
require_once('models/Smtp_Config.php');
require_once('models/Video.php');
require_once('models/Audio.php'); // audio file include
require_once('models/Document.php'); // doc file include
require_once('models/Image.php'); // image file include
require_once('models/Favourite.php');
require_once('models/Playlist.php');
require_once('models/Review.php');
require_once('models/Check_Video.php');

require_once('vendor/autoload.php');

?>
