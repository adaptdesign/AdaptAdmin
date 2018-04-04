<?php
require_once('../../dir_testadmin/config.php');

if(!empty($_GET['page'])) { $pipe_page = preg_replace('/[^a-zA-Z0-9-_]/','', decode($_GET['page'])); }
if(!empty($_GET['action'])) { $pipe_action = preg_replace('/[^a-zA-Z0-9-_]/','', decode($_GET['action'])); }
if(!empty($pipe_page) && !empty($pipe_action) && substr($pipe_action, 0, 2) === 'p_') {
  if(class_exists($pipe_page)) {
    $page = new $pipe_page();
    if(method_exists($page, $pipe_action)){
      $page->$pipe_action();
    } else { echo "method not found"; }
  } else { echo "class not found"; }
} elseif(!loggedIn()) {
  if(class_exists("_login_main")) {
    $page = new _login_main();
    if(method_exists($page, "page_construct")){
      $page->page_construct();
    } else { echo "method not found"; }
  } else { echo "class not found"; }
} else {
  if(class_exists("_page_main")) {
    $page = new _page_main();
    if(method_exists($page, "_page_main_construct")){
      $page->_page_main_construct();
    } else { echo "method not found"; }
  } else { echo "class not found"; }
}
?>
