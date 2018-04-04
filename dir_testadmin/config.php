<?php
error_reporting(-1); // reports all errors
ini_set("display_errors", "1"); // shows all errors
ini_set("log_errors", 1);
ini_set("error_log", "php-error.log");




session_start();

require_once('../adaptadmin/dir_plugins/stripe-5.6.0/init.php');
$stripe = array(
  "secret_key"      => "",
  "publishable_key" => ""
);
\Stripe\Stripe::setApiKey($stripe['secret_key']);

define("URL_PATH", "https://".$_SERVER['HTTP_HOST']."/testadmin/");
define("URL_ROOT", realpath(__DIR__ . '/../public_html'."/testadmin"));
define("DOC_ROOT", realpath(__DIR__));

define("URL_PLUGINS", "https://adaptadmin.com/dir_plugins/");
define("DOC_UPLOADS", DOC_ROOT . '/dir_uploads');

define("db_dbname", "");
define("db_username", "");
define("db_password", "");

setlocale(LC_MONETARY, 'en_US.UTF-8');
define("SALT1", "");
//define("time_zone", "0");
define("db_host", "localhost");


require_once(DOC_ROOT."/functions.php");
require_all(DOC_ROOT."/_dir_inc");
require_all(DOC_ROOT."/dir_system"); // "_*.php"

$init = new _global();
if(!empty($init->plugins)) { foreach($init->plugins as $row) { require_all(DOC_ROOT."/dir_addons/".$row['title']); } }
if(!empty($init->member_data['time_zone'])) { date_default_timezone_set(timezone_name_from_abbr("", $init->member_data['time_zone']*60, false)); }

require_once(DOC_ROOT."/database.php");
?>
