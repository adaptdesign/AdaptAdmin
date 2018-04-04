<?php

class _login_main extends _login_global {


  //=========================================
  public function __construct(){
		parent::__construct();


  }
  //=========================================
  public function page_construct() {
    $this->page_header();

    $this->page_content();

    $this->page_footer();
  }
  public function page_header() {
    ?>
    <!DOCTYPE html>
    <html lang="en-US" prefix="og: http://ogp.me/ns#" itemscope="itemscope" itemtype="http://schema.org/WebPage">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <title>AdaptAdmin</title>
      <link rel="icon" type="image/x-icon" href="<?=URL_PATH?>dir_img/favicon.ico">
      <link rel="profile" href="https://gmpg.org/xfn/11">

      <meta name="description" content="Keep your personal or business life organized with AdaptAdmin. We offer a wide array of options from task, client, document, chat, calendar and timeclick managment to name a few. Create/join unlimited groups and start sharing."/>
      <link rel="canonical" href="https://adaptadmin.com/" />
      <meta property="og:locale" content="en_US" />
      <meta property="og:type" content="website" />
      <meta property="og:title" content="AdaptAdmin" />
      <meta property="og:description" content="Keep your personal or business life organized with AdaptAdmin. We offer a wide array of options from task, client, document, chat, calendar and timeclick managment to name a few. Create/join unlimited groups and start sharing." />
      <meta property="og:url" content="https://adaptadmin.com/" />
      <meta property="og:site_name" content="AdaptAdmin" />
      <meta name="twitter:card" content="summary" />
      <meta name="twitter:description" content="Keep your personal or business life organized with AdaptAdmin. We offer a wide array of options from task, client, document, chat, calendar and timeclick managment to name a few. Create/join unlimited groups and start sharing." />
      <meta name="twitter:title" content="AdaptAdmin" />
      <script type='application/ld+json'>{"@context":"http:\/\/schema.org","@type":"WebSite","@id":"#website","url":"https:\/\/adaptadmin.com\/","name":"AdaptAdmin","potentialAction":{"@type":"SearchAction","target":"https:\/\/adaptadmin.com\/?s={search_term_string}","query-input":"required name=search_term_string"}}</script>
      <script type='application/ld+json'>{"@context":"http:\/\/schema.org","@type":"Organization","url":"https:\/\/adaptadmin.com\/","@id":"#organization","name":"AdaptAdmin","logo":"https:\/\/adaptadmin.com\/AdaptAdmin.png"}</script>
      <noscript>
        <meta http-equiv="refresh" content="0;url=https://adaptadmin.com/nojs.html"/>
      </noscript>

      <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,700,800" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
      <link href="https://fonts.googleapis.com/css?family=NTR" rel="stylesheet">

      <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>bootstrap-3.3.7/BootstrapXL.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>xeditable/css/bootstrap-editable.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>awesome-checkbox/awesome-bootstrap-checkbox.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>select2/css/select2.min.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>crop/jquery.Jcrop.css" />
      <link rel="stylesheet" href="<?=URL_PLUGINS?>colorpicker/spectrum.css" />
      <link rel="stylesheet" href="<?=URL_PLUGINS?>noty-3.1.2/lib/noty.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>animate/animate.min.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>gridstack/gridstack.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>gridstack/gridstack-extra.css">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>date-range-picker/daterangepicker.min.css?v=<?=uniqid()?>">
      <link rel="stylesheet" href="<?=URL_PLUGINS?>OverlayScrollbars-master/jquery.overlayScrollbars.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.css">
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

      <link rel="stylesheet" href="<?=URL_PATH?>dir_css/main.css?v=<?=uniqid()?>">
      <link rel="stylesheet" href="<?=URL_PATH?>dir_css/preset.css?v=<?=uniqid()?>">

      <? if(!empty($this->member_data['group_theme'])) {
        if (file_exists(DOC_ROOT."/dir_css/".$this->member_data['group_theme'].".css")) {
          ?><link rel='stylesheet' href='<?=URL_PATH?>dir_css/<?=$this->member_data['group_theme']?>.css?v=<?=uniqid()?>'><?
        }
      }
      ?>

      <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
      <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.1/moment.min.js"></script>

      <script src="<?=URL_PLUGINS?>noty-3.1.2/lib/noty.js"></script>
      <script src="<?=URL_PLUGINS?>tinymce/tinymce.min.js"></script>
      <script src="<?=URL_PLUGINS?>initial/initial.min.js"></script>
      <script src="<?=URL_PLUGINS?>select2/js/select2.min.js"></script>
      <script src="<?=URL_PLUGINS?>crop/jquery.Jcrop.js"></script>
      <script src="<?=URL_PLUGINS?>xeditable/js/bootstrap-editable.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js"></script>
      <script src="<?=URL_PLUGINS?>autosize/autosize.min.js"></script>
      <script src="<?=URL_PLUGINS?>colorpicker/spectrum.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/3.5.0/lodash.min.js"></script>
      <script src="<?=URL_PLUGINS?>gridstack/gridstack.js"></script>
      <script src="<?=URL_PLUGINS?>gridstack/gridstack.jQueryUI.js"></script>
      <script src="<?=URL_PLUGINS?>date-range-picker/jquery.daterangepicker.min.js"></script>
      <script src="<?=URL_PLUGINS?>OverlayScrollbars-master/jquery.overlayScrollbars.min.js"></script>
      <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.6.2/fullcalendar.min.js"></script>
      <script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyBOi_ScgaGjvSytJ1W5Rr8kIs4Zm36rsOw"></script>

    </head>
    <body id="top">
      <div class="page-loader"><div class="loader">Loading...</div></div>
      <a href="#top" id="back-top"><i class="fa fa-arrow-up"></i></a>

      <? if (isset($_SESSION['alertmessage'])){ ?>
        <script>new Noty({ text: '<i class="fa fa-bell m-r-xs"></i><?=$_SESSION["alertmessage"]?>' }).show();</script>
        <? unset($_SESSION['alertmessage']); ?>
      <? } ?>

      <div class="modal fade" id="main_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal_error_window">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div id="main_modal_content" class="modal-content">

          </div>
        </div>
      </div>
      <div class="modal fade" id="doc_editor" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
          <div class="modal_error_window">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          </div>
          <div id="doc_editor_content" class="modal-content">

          </div>
        </div>
      </div>
    <?
  }
  //=========================================
  public function page_content() {
    ?>
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Righteous" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=NTR" rel="stylesheet">
    <!--/ =============================================================== -->
    <!--/ == Forgot password modal ====================================== -->
    <!--/ =============================================================== -->
    <div class="modal fade" id="forgot_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">

          <form class="form-inline pageform" link="<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("a_forgot_password")?>">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h3 class="modal-title" id="myModalLabel">
                Forgot password
              </h3>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  Enter the e-mail address associated with your account below. Shortly after clicking submit you should recieve an e-mail with password recovery instructions. Be sure to check your junk mail folder if the e-mail does not show up in your inbox.
                  <br><br><br><br>
                  <label for="email">E-mail address</label>
                  <input type="text" class="form-control" name="email" autocomplete="off" placeholder="E-mail address" style="width: 100%;">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </form>

        </div>
      </div>
    </div>
    <!--/ =============================================================== -->

    <!--/ =============================================================== -->
    <!--/ == Update password modal ====================================== -->
    <!--/ =============================================================== -->
    <?
    if (isset($_GET['action'])) {
    	switch (strtolower($_GET['action'])) {
    		case 'forgotpassword':
    		  $linkemail = $_GET['email'];
    		  $linkauthcode = $_GET['authcode'];

    		  $stmt = $this->pdo->prepare("SELECT * FROM members WHERE email=:email AND authcode=:authcode LIMIT 1");
          $stmt->execute(array(':email' => $linkemail, ':authcode' => $linkauthcode));
          if ($stmt->rowCount() == 1) {
    			  ?>
            <div class="modal fade" id="password_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">

                  <form id="updateform" link="<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("a_update_password")?>">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h3 class="modal-title" id="myModalLabel">
                        Update password
                      </h3>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-12">
                          Please create and confirm your new password below.
                          <div class="m-b-md m-t-md" id="upd_error_output"></div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class="form-group has-feedback">
                            <input class="form-control" placeholder="Password" required  name="password" type="password"  autocomplete="off" />
                            <i class="glyphicon glyphicon-lock form-control-feedback"></i>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group has-feedback">
                            <input class="form-control" placeholder="Confirm password" required  name="confirmPassword" type="password"  autocomplete="off" />
                            <i class="glyphicon glyphicon-lock form-control-feedback"></i>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <input type="hidden" name="email" value="<? echo "$linkemail"; ?> ">
                      <input type="hidden" name="authcode" value="<? echo "$linkauthcode"; ?> ">
                      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </form>

                </div>
              </div>
            </div>
    			  <?
    			} else {
    			  echo "Error: Credentials do not match";
    			}
    		break;
    	}
    }
    ?>
    <!--/ =============================================================== -->

    <style>
    body {
      background: #f1f1f6;
    }
    .logo-name {
      color: #e6e6e6;
      font-size: 125px;
      font-weight: 800;
      letter-spacing: -10px;
      margin-bottom: 0;
    }
    .middle-box {
      width: 300px;
      max-width: 400px;
      z-index: 100;
      margin: 0 auto;
      padding-top: 40px;
    }
    .nav-tabs.nav-justified > li > a {
      border-radius: 2px 2px 0 0;
    }
    .tab-content {
      padding: 10px;
      border: 1px solid #FFF;
      border-top: none;
      background: #fff;
    }

    .registerform .captchaInput {
      border: 1px solid #ffb900;
    }
    .registerform .input-group-addon {
      border: 0px solid #ffb900 !important;
      background: #fffce3;
      padding: 4px 10px;
    }

    .footer_base {
    	font-size: 10px;
    	text-transform: uppercase;
    	position: relative;
    	display: block;
    	width: 100%;
    	padding: 12px 0;
      font-weight: 400;
      border-top: 1px solid #ccc;
    	background: #f8f8f9;
    }

    @-webkit-keyframes titleline {
    	0% {
    		opacity: 0;
    	}
    	100% {
    		opacity: 1;
    	}
    }

    @keyframes titleline {
    	0% {
    		opacity: 0;
    	}
    	100% {
    		opacity: 1;
    	}
    }
    .login_page {
      font-weight: 300;
    }
    .login_page .container {
      max-width: 1170px;
    }
    .navbar-custom {
      position: fixed;
      left: 0;
      right: 0;
      -webkit-border-radius: 0px;
    	-moz-border-radius: 0px;
    	border-radius: 0px;
    	z-index: 1000;
    	margin-bottom: 0;
    	-webkit-transition: all 1.5s ease-in-out;
    	-moz-transition: all 1.5s ease-in-out;
    	transition: all 1.5s ease-in-out;

      border: 0px solid transparent;
    	background: #FFF;
    	-webkit-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    	-moz-box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    	box-shadow: 0 1px 4px rgba(0, 0, 0, 0.2);
    }
    .navbar-custom.aligntop {
      top: 0;
    }
    .navbar-custom.aligntop .navbar-brand {
      opacity: 1;
    }
    .navbar-custom .navbar-brand {
    	position: relative;
    	font-family: 'Righteous', cursive;
      text-transform: uppercase;
      font-size: 20px;
      font-weight: 400;
      letter-spacing: 4px;
      margin: 0;
      cursor: pointer;
    	-webkit-transition: all 1.5s ease-in-out;
    	-moz-transition: all 1.5s ease-in-out;
    	transition: all 1.5s ease-in-out;
      color: #777;
      opacity: 1;
    }
    .navbar-custom .navbar-brand fra {
    	display: inline-block;
    }
    .navbar-custom .navbar-brand:after {
    	content: attr(data-content);
    	position: absolute;
    	left: 15px;
    	top: 11px;
    	display: inline-block;
      transform: scaleY(-1);
      transform-origin: bottom;
      background: linear-gradient(to bottom, rgba(0, 0, 0, 0) 30%, rgba(0, 0, 0, 0.1) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    .navbar-custom .navbar-brand .blink_line {
    	display: inline-block;
    	content: " ";
    	width: 10px;
    	height: 2px;
    	border-bottom: 2px solid #25cbf5;
    	-webkit-animation: titleline 0.8s infinite linear;
    	   -moz-animation: titleline 0.8s infinite linear;
    		 ms-animation: titleline 0.8s infinite linear;
    		  o-animation: titleline 0.8s infinite linear;
    			animation: titleline 0.8s infinite linear;
    }
    .navbar-custom .navbar-brand:hover {
      /*color: #FFF;*/
    }
    .navbar-custom li a {
    	position: relative;
    	color: #777;
      text-transform: uppercase;
      font-size: 11px;
      font-weight: 400;
    }
    .navbar-custom li a:hover, .navbar-custom li a:focus, .navbar-custom li a:active {
      background: none !important;
      color: #111;
    }
    .navbar-custom li a:hover::after {
    	font-family: "FontAwesome";
    	font-size: 20px;
    	color: #222;

      position: absolute;
    	bottom: 0;
    	left: 0;
    	right: 0;
    	display: inline-block;
    	content: " ";
    	height: 2px;
    	border-bottom: 2px solid #25cbf5;
    	-webkit-animation: titleline 0.8s linear;
    	   -moz-animation: titleline 0.8s linear;
    		 ms-animation: titleline 0.8s linear;
    		  o-animation: titleline 0.8s linear;
    			animation: titleline 0.8s linear;
    }
    .login_page .pagehead {
      color: #FFF;
      padding-top: 200px;
      background: #00C9FF;  /* fallback for old browsers */
      background: -webkit-linear-gradient(to left, #92FE9D, #00C9FF);  /* Chrome 10-25, Safari 5.1-6 */
      background: linear-gradient(to left, #92FE9D, #00C9FF); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
    }
    .login_page .pagehead > .container > .row > div {
      margin-bottom: 150px;
    }
    .login_page .pagehead h2 {
      font-size: 40px;
      font-family: NTR;
      font-weight: 600;
      text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
    }
    .login_page .pagehead .text-block {
      font-size: 15px;
      max-width: 410px;
      text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.3);
    }
    .login_page .pagehead .pagehead_form {
      color: #666;
    }
    .login_page .pagehead .pagehead_form > ul {
      margin-bottom: 0px;
    }
    .login_page .pagehead .pagehead_form .form-control {
      border: 0px solid #9c9c9c;
      border-bottom: 1px solid #ddd;
    }
    .login_page .pagehead .nav-tabs.nav-justified>li>a:focus, .login_page .pagehead .nav-tabs.nav-justified>li>a:hover {
      background: none;
      border: 1px solid transparent;
      border-bottom: 1px solid #FFF;
    }
    .login_page .pagehead .nav-tabs.nav-justified>li>a {
      border-bottom: 1px solid #FFF;
    }
    .login_page .pagehead .nav-tabs.nav-justified>.active>a, .login_page .pagehead .nav-tabs.nav-justified>.active>a:focus, .login_page .pagehead .nav-tabs.nav-justified>.active>a:hover {
      border: 1px solid #FFF;
      border-bottom: 1px solid transparent;
      color: #444;
      background: rgba(255,255,255,1);
      -webkit-border-radius: 4px 4px 0px 0px;
    	-moz-border-radius: 4px 4px 0px 0px;
    	border-radius: 4px 4px 0px 0px;
    }
    .login_page .pagehead .tab-content {
      background: rgba(255,255,255,1);
      -webkit-border-radius: 0px 0px 4px 4px;
    	-moz-border-radius: 0px 0px 4px 4px;
    	border-radius: 0px 0px 4px 4px;
    }
    .login_page .btn-primary {
      background-color: #25cbf5;
      border-color: transparent;
      color: #FFFFFF;
      box-shadow: none;
      border-radius: 4px;
      text-transform: uppercase;
      font-size: 13px;
      font-weight: 500;
      padding: 8px;
      letter-spacing: 1px;
    }

    .iconbox {
    	font-size: 12px;
    	background: #fff;
    	border-bottom: 1px solid #d4d4d4;
    	text-align: center;
    	padding: 30px 20px;
    	margin: 0 0 20px;
    	cursor: pointer;
    	-webkit-border-radius: 3px;
    	-moz-border-radius: 3px;
    	-o-border-radius: 3px;
    	border-radius: 3px;
    	-webkit-transition: all .2s ease-in-out;
    	-moz-transition: all .2s ease-in-out;
    	transition: all .2s ease-in-out;
    }
    .iconbox:hover {
    	color: #FFF;
    	background: #25CBF5;
    }
    .iconbox:hover .iconbox-icon {
    	color: #FFF;
    }

    .iconbox-icon {
    	margin: 0 0 15px;
    	font-size: 32px;
    	color: #777;
    	-webkit-transition: all .2s ease-in-out;
    	-moz-transition: all .2s ease-in-out;
    	transition: all .2s ease-in-out;
    }

    .iconbox-title {
    	font-size: 12px;
      font-weight: 500;
    	margin: 0 0 10px;
    	padding: 0;
    	text-transform: uppercase;
    }
    .fw_pic_left {
      position: relative;
      background: #FFF;
    }
    .fw_pic_left > div:first-child {
    	position: absolute;
    	background: url(dir_img/collaborate1.jpg) scroll center no-repeat;
    	background-size: cover;
    	height: 100%;
    }
    .fw_pic_left > div:last-child {
    	margin-top: 50px;
    	background: rgba(255,255,255,0.9);
    	padding: 0px 45px 10px;
    }

    .iconbox_left {
    	position: relative;
    	margin-bottom: 30px;
    	font-size: 12px;
    }
    .iconbox_left_text:after {
    	clear: both;
    	display: table;
    	content: " ";
    }
    .iconbox_left:hover .iconbox_left_icon {
    	-webkit-animation: swing 0.8s linear;
    	   -moz-animation: swing 0.8s linear;
    		 ms-animation: swing 0.8s linear;
    		  o-animation: swing 0.8s linear;
    			animation: swing 0.8s linear;
    	color: #25CBF5;
    }
    .iconbox_left .iconbox_left_icon {
    	position: absolute;
    	top: 0;
    	left: 0;
    	font-size: 32px;
    	line-height: 1;
    	-webkit-transition: all .2s ease-in-out;
    -moz-transition: all .2s ease-in-out;
    transition: all .2s ease-in-out;
    }
    .iconbox_left .iconbox_left_text {
    	padding-left: 50px;
    }
    .ready_divider {
    	padding: 30px 0;
    	font-size: 12px;
    	font-family: 'Open Sans', serif;
    }
    .ready_divider h2 {
    	font-size: 17px;
    	color: #FFF;
    	margin: 0 0 5px 0;
    }
    .ready_divider h2 {
    	font-size: 17px;
    	color: #FFF;
    	margin: 0 0 5px 0;
    }
    .ready_divider .btn {
    	font-size: 13px;
    	color: #fff;
    	background-color: #25cbf5;
    	border-color: #25cbf5;
    	padding: 9px 12px;
    	text-transform: uppercase;
    }
    .icon-bar {
      background: #777;
    }
    .page_title {
      text-transform: uppercase;
      color: #777;
      margin: 0px;
      font-family: NTR;
      font-size: 35px;
    }
    </style>
    <!--/ == Body Start ===================================================== -->
    <div class="login_page" id="home">

      <nav class="navbar navbar-custom navbar-transparent navbar-light">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <h1 data-content='ADAPTDESIGN' class="navbar-brand scroll_to" href="#home">
              <fra>A</fra>dapt<fra>A</fra>dmin<div class="blink_line"></div>
            </h1>
          </div>
          <ul class="collapse navbar-collapse navbar-ex1-collapse nav navbar-nav navbar-right">
            <li><a class="scroll_to" href="#home">Home</a></li>
            <li><a class="scroll_to" href="#about">About</a></li>
            <li><a class="scroll_to" href="#features">Features</a></li>
            <li><a class="scroll_to" href="#login">Log In</a></li>
          </ul>
        </div>
      </nav>

      <div class="pagehead" id="login">
        <div class="container">
          <div class="row">
            <div class="col-md-8">
              <h2>Adaptive Group Managment</h2>
              <div class="text-block">
                Keep your personal or business life organized with AdaptAdmin. We offer a wide array of options from task, client, document, chat, calendar and timeclick managment to name a few. Create/join unlimited groups and start sharing.<br><br>
                <a href="#about" class="btn btn-primary scroll_to" style="background: transparent;border: 2px solid #FFF;padding: 10px 20px;text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.2);font-weight: 400;border-radius: 10px;">Learn More</a>
              </div>
            </div>
            <div class="col-md-4">
              <div class="pagehead_form">

                <ul class="nav nav-tabs nav-justified">
                  <li class="active"><a data-toggle="tab" href="#logintab"><i class="fa fa-sign-in"></i> Log In</a></li>
                  <li><a data-toggle="tab" href="#registertab"><i class="fa fa-user-circle"></i> Register</a></li>
                </ul>

                <div class="tab-content">
                  <div class="m-b-md" id="log_error_output"></div>
                  <div id="logintab" class="tab-pane fade in active">
                    <form class="pageform" link="<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("p_login")?>">
              				<div class="m-b-15" id="ldog_error_output"></div>

                      <div class="form-group has-feedback">
                        <input class="form-control" placeholder="Email" required name="singleEmail" autocomplete="off" />
                        <i class="glyphicon glyphicon-envelope form-control-feedback"></i>
                      </div>
                      <div class="form-group has-feedback m-b-10">
                        <input class="form-control" placeholder="Password" required  name="singlePassword" type="password"  autocomplete="off" />
                        <i class="glyphicon glyphicon-lock form-control-feedback"></i>
                      </div>
                      <div class="text-right f-w-500"><a data-toggle="modal" data-target="#forgot_modal"><small>Forgot password?</small></a></div>
              				<button type="submit" class="btn btn-block btn-primary small m-t-15">Login</button>
              			</form>

                  </div>
                  <div id="registertab" class="tab-pane fade">
                    <form id="form_redirect_regform" class="registerform" enctype="multipart/form-data" method="POST" link="<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("p_add_member")?>">
              				<div id="after_register">
              					<div class="m-b-md" id="reg_error_output"></div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class="form-group has-feedback" style="margin-bottom:2px;">
                              <input class="form-control" type="text" name="firstname" autocomplete="off" placeholder="First Name" />
                              <i class="glyphicon glyphicon-user form-control-feedback"></i>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class="form-group has-feedback" style="margin-bottom:2px;">
                              <input class="form-control" type="text" name="lastname" autocomplete="off" placeholder="Last Name" />
                              <i class="glyphicon glyphicon-user form-control-feedback"></i>
                            </div>
                          </div>
                        </div>

              					<div class="form-group has-feedback" style="margin-bottom:2px;">
                          <input class="form-control" type="email" name="email" autocomplete="off" placeholder="E-mail" />
                          <i class="glyphicon glyphicon-envelope form-control-feedback"></i>
                        </div>

                        <div class="form-group has-feedback" style="margin-bottom:20px;">
                          <input class="form-control" type="password" name="password" autocomplete="off" placeholder="Password" />
                          <i class="glyphicon glyphicon-lock form-control-feedback"></i>
                        </div>

                        <input type="text" class="form-control captchaInput" name="captcha" style="margin-bottom:2px;" placeholder="Enter the numbers shown below">
                        <div class="input-group-addon"><img src="<?=URL_PATH?>captcha.php"></div>
                        <div class="clearfix"></div>
                        <div class="m-t-15 pull-left">
                          <div class="checkbox checkbox-success checkbox-inline">
                            <input type="hidden" name="terms" value="1">
                            <input id="tms" name="terms" value="2" type="checkbox">
                            <label for="tms" class="f-s-11 f-w-400" style="letter-spacing:0px">I agree with the <a href="<?=URL_PATH?>termsandconditions.html" target="_blank">Terms and Conditions?</a></label>
                          </div>
                        </div>
              					<button type="submit" class="btn btn-block btn-primary small m-t-15">Register</button>
              				</div>
              			</form>
                  </div>
                </div>

              </div>
            </div>
          </div>
        </div>
      </div>

      <div style="background: #FFF;padding:100px 0px;" id="about">
        <div class="container text-center">
          <h2 class="page_title text-center" style="margin-bottom: 30px;">What Is AdaptAdmin?</h2>
          We offer custom management systems to help you organize and run your bussiness. Our systems are interconnected with your e-commerce site to help you sell your products online and easily manage them. The first step in starting a new project is the planning stage. During the planning stage we will work with you to find out exactly what you are looking to achieve from the project and what will be involved in reaching those goals. We will also discuss pricing, hosting options (including domain name setup), estimated project completion time, security preferences and project management options.

        </div>
      </div>


      <div class="row fw_pic_left" id="features">
        <div class="col-xs-12 col-sm-6">

        </div>
        <div class="col-xs-11 col-xs-offset-1 col-sm-6 col-sm-offset-6">
          <h2 class="page_title">Key Features</h2>
          <p class="m-b-sm" style="border-bottom: 1px solid #eee;padding-bottom:20px;">
            A short list of features we offer with AdaptAdmin.
          </p>
          <div class="row">
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-world-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Group Managment</h2>
                  <p>
                    Start by creating your own group whether that be your business or other organization. Stay interconnected with group members including employees and clients. Organize, share, plan and display your ideas, memos, and events.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-monitor-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Personal Groups</h2>
                  <p>
                    AdaptAdmin is meant to be used to organize and collaborate in you personal life as well. For example a family group could be created to share your favorite meal recipies, plan trips, display photos, share documents and find out your loved ones' holiday gift wish lists.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-locked-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Office Managment</h2>
                  <p>
                    AdaptAdmin is a great free alternative to manage your business. We offer group chat, scheduleing, timeclock, client/patient managment, calendar events, document sharing, invoicing and much more.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-photos-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Plugins/Layouts</h2>
                  <p>
                    Each group created in the AdaptAdmin system can be customized to fit your needs by installing/removing plugins and configuring the group layout. We also offer pre-made layouts to save the trouble of setting everything up.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-gear-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Always Improving</h2>
                  <p>
                    AdaptAdmin is evergrowing with constant updates and additions to plugins, layouts, security, spam prevention and every other aspect to make everything easier and safer for you.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-pie-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Backup/Restore</h2>
                  <p>
                    Data loss is never a fun situation, expecially with buisness related data. AdaptAdmin has options for manually backing up your data any time you would like.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-chatboxes-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">The Future</h2>
                  <p>
                    We are constantly working to add more functionality to AdaptAdmin, our plans for the future include education, shopping, marketplace, web analitics, revenue managment, local events and much more.
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="iconbox_left">
                <div class="iconbox_left_icon">
                  <i class="ion-ios-cart-outline"></i>
                </div>
                <div class="iconbox_left_text">
                  <h2 class="iconbox-title">Developers</h2>
                  <p>
                    For all those web designers out there AdaptAdmin comes with website integration to display client/patient registration forms, appointment scheduling, product catalog/shopping or load the entire AdaptAdmin system directly on your own website. These options will be released soon.
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="footer_base">
        <div class="container">
          <span class="pull-right">
            <a href="<?=URL_PATH?>termsandconditions.html" target="_blank">Privacy</a> | <a href="<?=URL_PATH?>termsandconditions.html" target="_blank">Terms</a>
          </span>
          © <a href="<?=URL_PATH?>">ADAPTADMIN.COM</a> 2015. All Rights Reserved.
        </div>
      </div>

    </div>

    <script type="text/javascript">
    $(document).ready(function() {

      $(document).on("click", ".scroll_to", function(e) {
        e.preventDefault();
    		$("html, body").animate({ scrollTop: $($(this).attr("href")).offset().top }, 1000);
    	});

      $(document).on("mouseenter", "fra", function(e) {
    		$(this).addClass("animated swing");
    		$(this).delay(900).queue(function(nxt) {
    			$(this).removeClass('animated swing');
    			nxt();
    		});
    	});
      //=====================================================================
      $(document).on("submit", "#form_redirect_regform", function(e) {
    		e.preventDefault();
    		$('.loader').fadeIn();
    		$('.page-loader').fadeIn();
    		var me = $(this);
    		var formData = new FormData($(this)[0]);
    		var $inputs = $(this).find("input, select, button, textarea");
    		$inputs.prop("disabled", true);
    		$(this).unbind('submit');
    		var link = $(this).attr('link');
    		var returnlink = $(this).attr('return_link');
    		if (me.data('requestRunning')) { return; }
    		me.data('requestRunning', true);
    		$.ajax({
    			type: "POST",
    			url: link,
    			cache: false,
    			contentType: false,
    			processData: false,
    			data: formData,
    			success: function (msg) {
    				result = msg;
    				result = unescape(result);
    				result = result.split("|");
    				outcome = result[0];
    				note = result[1];
    				if(outcome=='true') {
              if(window.location.hostname == "adaptdesign.us") {
                window.location.replace(window.location.protocol+"//"+window.location.hostname+"/testadmin");
              } else {
                window.location.replace(window.location.protocol+"//"+window.location.hostname);
              }
    					//$('.modal').modal('hide');
    					//$('#after_register').html("<div class='alert alert-success'>"+note+"</div>");
    				} else {
    				  new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+msg }).show();
    					//$.notify({ message: msg },{ type: 'bad', z_index: 2200, delay: 0 });
    					$('#reg_error_output').html(msg);
    				}
    				me.data('requestRunning', false);
    				$('.loader').fadeOut();
    				$('.page-loader').delay(350).fadeOut('slow');
    				$inputs.prop("disabled", false);
    			}
    		});
    		//$inputs.prop("disabled", false);
    		return false;
    	});
    	//=====================================================================

      $('#password_modal').modal('show');

      // =====================================================================
      // =================================== Main form submit
      // =====================================================================
      $(document).on("submit", ".pageform", function(e) {
    		e.preventDefault();
    		$(this).unbind('submit');
          var link = $(this).attr('link');
          var return_link = $(this).attr('return_link');
          var $form = $(this);
          var $inputs = $form.find("input, select, button, textarea");
          var serializedData = $form.serialize();
        $inputs.prop("disabled", true);
    		$.ajax({
    			type: "POST",
    			url: link,
    			data: serializedData,
    			success: function (msg) {
    				result = msg;
    				result = unescape(result);
    				result = result.split("|");
    				outcome = result[0];
    				note = result[1];
    				if(outcome=='true') {
    				  $('#log_error_output').html("");
    				  new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+note }).show();
              $('#forgot_modal').modal('hide');
    				} else if (outcome=='login') {
    				  $('#log_error_output').html("");
              if(window.location.hostname == "adaptdesign.us") {
                window.location.replace(window.location.protocol+"//"+window.location.hostname+"/testadmin");
              } else {
                window.location.replace(window.location.protocol+"//"+window.location.hostname);
              }
            } else {
              msg = msg.split("false|").join("");
              new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+msg }).show();
              $('#log_error_output').html(msg);
            }
    			}
    		});
    		$inputs.prop("disabled", false);
    	});

      $(document).on("submit", "#updateform", function(e) {
        e.preventDefault();
        $(this).unbind('submit');
          var link = $(this).attr('link');
          var return_link = $(this).attr('return_link');
          var $form = $(this);
          var $inputs = $form.find("input, select, button, textarea");
          var serializedData = $form.serialize();
        $inputs.prop("disabled", true);
        $.ajax({
          type: "POST",
          url: link,
          data: serializedData,
          success: function (msg) {
            result = msg;
            result = unescape(result);
            result = result.split("|");
            outcome = result[0];
            note = result[1];
            if(outcome=='true') {
              $('#upd_error_output').html("");
              $('#log_error_output').html(note);
              new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+note }).show();
              $('#password_modal').modal('hide');
            } else {
              msg = msg.split("false|").join("");
              new Noty({ text: '<i class="fa fa-bell m-r-xs"></i>'+msg }).show();
              $('#upd_error_output').html(msg);
            }
          }
        });
        $inputs.prop("disabled", false);
      });


    });
    </script>
    <?
  }
  //=========================================
  public function page_footer() {
    ?>
        <span id="pageLogger"></span>
        <script src="https://adaptadmin.com/api.js?id=9C886B19075F6" type="text/javascript"></script>
        <script src="<?=URL_PATH?>dir_js/reloads.js?v=<?=uniqid()?>"></script>
        <script src="<?=URL_PATH?>dir_js/global.js?v=<?=uniqid()?>"></script>
      </body>
    </html>
    <?
  }
  //=========================================


}
?>
