<?php

$querySelA = $db->query("SELECT * FROM tbl_settings");
$fetchResA = mysql_fetch_assoc($querySelA);
define('SITE_NM', $fetchResA['siteName']);
define('FROM_NM', SITE_NM);
define('FROM_EMAIL', 'no-reply@ncryptedprojects.com');
define('ADMIN_NM', 'Upodot');
define('ADMIN_EMAIL', 'no-reply@ncryptedprojects.com');
define('REGARDS', SITE_NM);

/* For SMTP */
define('SMTP_HOST', 'mail.ncryptedprojects.com');
define('SMTP_PORT', '587');
define('SMTP_USER', 'no-reply@ncryptedprojects.com');
define('SMTP_PASSWORD', '3}z&@V1z48][');

/* for client */
define("SITE_INC", SITE_URL . "includes/");
define("SITE_SLD", SITE_INC . "slider/");
define("SITE_UPD", SITE_URL . "upload/");
define("SITE_UPD_PRO", SITE_URL . "upload/profile/");
define("SITE_MOD", SITE_URL . "modules/");
define("SITE_MOD_REG", SITE_MOD . "registration/");
define("SITE_JS", SITE_INC . "javascript/");
define("SITE_IMG", SITE_URL . "themes/images/");
define("SITE_CSS", SITE_URL . "themes/css/");
define("SITE_TMP", SITE_UPD . "temp/");

define("DIR_INC", DIR_URL . "includes/");
define("DIR_FUN", DIR_URL . "includes/functions/");
define("DIR_MOD", DIR_URL . "modules/");
define("DIR_THEME", DIR_URL . "themes/");
define("DIR_TMPL", DIR_URL . "templates/");
define("DIR_IMG", DIR_URL . "themes/images/");
define("DIR_UPD", DIR_URL . "upload/");
define("DIR_TEMP", DIR_UPD . "temp/");

/* for admin */
define("SITE_ADMIN_URL", SITE_URL . "admin/");
define("SITE_ADM_CSS", SITE_ADMIN_URL . "themes/css/");
define("SITE_ADM_IMG", SITE_ADMIN_URL . "themes/images/");
define("SITE_ADM_INC", SITE_ADMIN_URL . "includes/");
define("SITE_ADM_MOD", SITE_ADMIN_URL . "modules/");
define("SITE_ADM_JS", SITE_ADMIN_URL . "includes/javascript/");
define("SITE_ADM_UPD", SITE_ADMIN_URL . "upload/");

define("DIR_ADMIN_URL", DIR_URL . "admin/");
define("DIR_ADMIN_THEME", DIR_ADMIN_URL . "themes/");
define("DIR_ADMIN_TMPL", DIR_ADMIN_URL . "templates/");
define("DIR_ADM_INC", DIR_ADMIN_URL . "includes/");
define("DIR_ADM_MOD", DIR_ADMIN_URL . "modules/");

define('LIMIT', 10);
define('ADMIN_LIMIT', 5);
define('SLEEP', 1);
define('STRING_LIMIT', 200); //characters
define('MEND_SIGN', '<font color="#FF0000">*</font>');
/*define('RNF', '<div class="RNF">No results found.</div>');*/
define('NMRF', '<div class="NMRF">No more results found.</div>');
//define('SUPER_ADMIN_EMAIL','ajay.makwana@ncrypted.com');
?>