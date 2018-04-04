<?php
/*
 * METHOD LIST FOR _page_main
 *
 * __construct
 * _page_main_construct
 * _setmenuwidgets
 * _page_main_content
 * _page_main_header
 * _page_main_topnavigation
 * _page_main_sidenavigation
 * _page_main_js
 * _page_main_footer
 *
 */
class _page_main extends _global_admin {


  //=========================================
  public function __construct() {
		parent::__construct();

    $this->_set_menu_widgets();
  }
  //=========================================
  public function _page_main_construct() {
    $this->_page_main_header();
    $this->_page_main_topnavigation();
    $this->_page_main_sidenavigation();

    $this->_page_main_content();

    $this->_page_main_js();
    $this->_page_main_footer();
  }
  //=========================================
  public function _page_main_content() {
    ?>
    <style>
      .page-wrapper .page-split-left-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.1);
        display: none;
        z-index: 1000;
      }
      .page-wrapper .page-split-right-overlay {
        position: fixed;
        top: 60px;
        right: 0;
        bottom: 0;
        width: 60px;
        background: rgba(0,0,0,0);
        display: none;
        z-index: 1002;
      }

			.page-split-left {
        width: calc(100% - 247px);
			}
			.page-split-right {
				position: fixed;
				top: 60px;
				right: 0;
				bottom: 0;
				height: calc(100vh - 60px);
				border-left: 1px dashed #ddd;
				width: 247px;
        background: #f1f1f6;
        z-index: 1001;
			}

      .page-wrapper.shrink-right > .page-split-right > .nav-tabs > li {
        display: none;
      }
      .page-wrapper.shrink-right > .page-split-right > .nav-tabs > li.active {
        display: block;
      }
      .page-wrapper.shrink-right > .page-split-right > .nav-tabs > li.active > a {
        padding: 0 5px;
      }
      .page-wrapper.shrink-right > .page-split-left {
        width: calc(100% - 60px);
      }
      .page-wrapper.shrink-right > .page-split-right {
        width: 60px;
      }
			@media (max-width: 1400px) {
				.page-wrapper > .page-split-left {
					width: calc(100% - 60px);
				}
				.page-wrapper > .page-split-right {
          box-shadow: -2px 0px 4px rgba(0,0,0,0.2);
				}
        .page-wrapper.shrink-right > .page-split-right {
          box-shadow: none;
        }

        .page-wrapper > .page-split-left-overlay {
          display: block;
        }
        .page-wrapper.shrink-right > .page-split-left-overlay {
          display: none;
        }
        .page-wrapper .page-split-right-overlay {
          display: none;
        }
        .page-wrapper.shrink-right .page-split-right-overlay {
          display: block;
        }
			}
		</style>
    <script>
    $(window).load(function() {
      if ($(window).width() < 1400) { $(".page-wrapper").addClass("shrink-right"); }
      $(window).resize(function () {
        if ($(window).width() < 1400) { $(".page-wrapper").addClass("shrink-right"); } else { $(".page-wrapper").removeClass("shrink-right"); }
      });

      $(document).on('mousedown', function (e) {
        if($(window).width() < 1400) {
          if(!$(".page-split-right").is(e.target) && $(".page-split-right").has(e.target).length === 0) {
            if(!$(".page-wrapper").hasClass("shrink-right")) { $(".page-wrapper").addClass("shrink-right"); }
          } else {
            if($(".page-wrapper").hasClass("shrink-right")) { $(".page-wrapper").removeClass("shrink-right"); }
          }
        }
    	});

    });
    </script>
    <div class="page-wrapper">
      <div class="page-split-left-overlay"></div>
      <div class="page-split-left">
        <?

        //disarray($this->member_data);
        //disarray($this->plugins);
        //disarray(get_included_files());
        if(!empty($this->menu_widgets[$this->ses_page])) { $menu_page = $this->ses_page; } elseif(empty($this->ses_page)) { $menu_page = "dashboard"; }
        if(!empty($menu_page)) {
          $plugin = "global_".$this->menu_widgets[$menu_page]['pl_title'];
          $page = new $plugin();
          $page->_set_widget_data($this->menu_widgets[$menu_page]['layout_id']);
          $page->_set_link("-".$this->menu_widgets[$menu_page]['plugin_id'].",".$this->content_data_id."-");
          if(!empty($this->content_data_id)) { $page->set_content_data("",$this->content_data_id); $this->page_title = $page->page_title; $this->content_data = $page->content_data; }
          $this->widget_data = $page->widget_data;
          $this->link = $page->link;
          $this->_set_page_widgets();
          $this->_page_inner();
        } else {
          echo "page not found";
        }
        ?>
      </div>



      <div class="page-split-right">
        <div class="chat_window" id="chat_window">
          <?
          $temp = new d_messages_chat();
          $temp->p_show_feed();
          ?>
        </div>

        <div class="page-split-right-overlay"></div>

        <style>
        .chat_window {
          position: absolute;
          right: calc(100% + 10px);
          width: 300px;
          bottom: 0;
          max-height: 400px;
        }
        .chat_window_box {
          border: 0px solid red;
          background: #ffffff;
          border-radius: 1px;
          box-shadow: 0px 1px 3px rgba(0,0,0,0.6);
        }
        .chat_window_title {
          padding: 0px 7px;
          font-size: 12px;
          font-weight: 600;
          text-transform: uppercase;
          line-height: 30px;
          height: 30px;
        }
        .chat_window_title > i.fa-circle {
          line-height: 30px;
          font-size: 9px;
          vertical-align: top;
        }
        .chat_window_content {
          padding: 5px;
          padding-bottom: 10px;
          background-color: #f1f1f6;
          overflow-y: auto;
          min-height: 300px;
          max-height: 340px;
          border-top: 1px dashed #ddd;
          border-bottom: 1px dashed #ddd;
        }
        .chat_window_content .feedlooper .fx_box_shadow1 { max-width: calc(100% - 50px); }
        .chat_window_input {
          height: 30px;
        }
        .chat_window_box.chat_hide .chat_window_content, .chat_window_box.chat_hide .chat_window_input { display: none; }
        .nav-active-only > li {
          max-height: 0px;
          overflow: hidden;
          -webkit-transition: all .3s ease-in-out;
          -moz-transition: all .3s ease-in-out;
          transition: all .3s ease-in-out;
        }
        .nav-active-only.open > li {
          border-top: 1px dashed #ddd;
          /* border-left: 2px solid #00d4bd; */
          max-height: 50px;
        }
        .nav-active-only > li > a { line-height: 37px !important; background: transparent; }
        .nav-active-only.open > li > a { background: #FFF; }
        .nav-active-only > li.active {
          max-height: 50px;
        }
        .nav-active-only > li:first-child {
          border-top: none;
        }
        .nav-active-only > li.active > a {
          background: transparent;
          color: inherit;
        }
        .nav-active-only > li.active > a:hover {
          background: transparent;
          cursor: pointer;
        }
        .nav-active-only.open > li.active > a {
          background: #FFF;
          color: inherit;
        }
        .nav-active-only > li.active > a:after {
          font-family: 'FontAwesome';
          content: "\f053";
          position: absolute;
          right: 10px;
          font-size: 10px;
          line-height: 37px;
          color: #676a6c;
          text-shadow: none;
          -webkit-transition: all .3s ease-in-out;
          -moz-transition: all .3s ease-in-out;
          transition: all .3s ease-in-out;
        }
        .nav-active-only.open > li.active > a:after {
          color: #00d4bd;
          -webkit-transform: rotate(-90deg);
          -moz-transform: rotate(-90deg);
          -ms-transform: rotate(-90deg);
          -o-transform: rotate(-90deg);
          transform: rotate(-90deg);
        }
        </style>



        <ul class="nav nav-tabs nav-hash bg-white" hash_id="5abe61451e6da">
          <li class="active">
            <a class="nav-load" href="#rnav_11" target="#rnav_1_tabs" data-toggle="tab" link="<?=URL_PATH?>?page=<?=encode("d_friendslist_dash")?>&action=<?=encode("p_show_feed")?>"><i class="fa fa-users m-18"></i></a>
          </li>
          <li>
            <a class="" href="#rnav_12" target="#rnav_1_tabs" data-toggle="tab" link="<?=URL_PATH?>?page=<?=encode("d_messages_dash")?>&action=<?=encode("p_show_feed")?>"><i class="fa fa-comments m-18"></i></a>
          </li>
          <li>
            <a class="nav-load" href="#rnav_13" target="#rnav_1_tabs" data-toggle="tab" link="<?=URL_PATH?>?page=<?=encode("d_notifications_dash")?>&action=<?=encode("p_show_feed")?>"><i class="fa fa-bell m-18"></i></a>
          </li>
        </ul>
        <div class="tab-content" id="rnav_1_tabs">
          <div class="tab-pane animated fadeIn fade active in" id="rnav_11">

            <div class="hs-block">
              <ul class="nav nav-tabs nav-stacked nav-hash nav-active-only m-t-30" hash_id="5abebf193150d">
                <li class="active">
                  <a href="#rnav_111" data-toggle="tab"><i class="fa fa-user-plus f-c-themed"></i> Friendslist</a>
                </li>
                <li>
                  <a href="#rnav_112" data-toggle="tab"><i class="fa fa-users f-c-themed"></i> Group Members</a>
                </li>
              </ul>
            </div>
            <div class="tab-content m-t-15">
              <div class="tab-pane animated fadeIn fade active in" id="rnav_111">
                <div class="feedlooper" id="feedlooper_myfriendslist" page="1" link="<?=URL_PATH?>?page=<?=encode("d_friendslist_dash")?>&action=<?=encode('p_feed_query')?>&link=<?=encode("-".$this->plugins['members']['plugin_id'].",".$this->member_data['member_id']."-")?>">
                </div>
                <div id="feedlooper_myfriendslist_msgbox"></div>
              </div>
              <div class="tab-pane animated fadeIn fade" id="rnav_112">
                <div class="feedlooper" id="feedlooper_mygroupmembers" page="1" link="<?=URL_PATH?>?page=<?=encode("d_groupmembers_dash")?>&action=<?=encode('p_feed_query')?>">
                </div>
                <div id="feedlooper_mygroupmembers_msgbox"></div>
              </div>
            </div>

          </div>
          <div class="tab-pane animated fadeIn fade" id="rnav_12">

            <div class="hs-block">
              <ul class="nav nav-tabs nav-stacked nav-hash nav-active-only m-t-30" hash_id="5abe6a32a2cc0">
                <li class="active">
                  <a href="#rnav_121" data-toggle="tab"><i class="fa fa-comments f-c-themed"></i> Conversations</a>
                </li>
                <li>
                  <a href="#rnav_122" data-toggle="tab"><i class="fa fa-users f-c-themed"></i> Group Chat</a>
                </li>
              </ul>
            </div>
            <div class="tab-content m-t-15">
              <div class="tab-pane animated fadeIn fade active in" id="rnav_121">
                <div class="feedlooper" id="feedlooper_myconversations" page="1" link="<?=URL_PATH?>?page=<?=encode("d_messages_conversations")?>&action=<?=encode('p_feed_query')?>">
                </div>
                <div id="feedlooper_myconversations_msgbox"></div>
              </div>
              <div class="tab-pane animated fadeIn fade" id="rnav_122">
                <div class="feedlooper" id="feedlooper_mygroupchat" page="1" link="<?=URL_PATH?>?page=<?=encode("d_messages_groupchat")?>&action=<?=encode('p_feed_query')?>">
                </div>
                <div id="feedlooper_mygroupchat_msgbox"></div>
              </div>
            </div>
          </div>
          <div class="tab-pane animated fadeIn fade" id="rnav_13">
            test
          </div>
        </div>
      </div>
      <? //$this->page_footer()?>
    </div>
    <?
  }



  //=========================================
  public function _page_inner() {
    ?>
    <div class="bg-white p-15 animated fadeInRight">
      <ol class="breadcrumb">
        <li><a href="<?=URL_PATH?>">Home</a></li>
        <? if(!empty($this->page_title)) { ?>
          <li><a href="<?=URL_PATH.$this->widget_data['pl_title']?>"><?=$this->widget_data['title']?></a></li>
          <li class="active"><?=$this->page_title?></li>
        <? } else { ?>
          <li class="active"><?=$this->widget_data['title']?></li>
        <? } ?>
      </ol>
    </div>
    <div class="wrapper-content animated fadeInRight">
      <?
      if(!empty($this->content_data_id)) { $page = "d_".$this->widget_data['pl_title']."_item"; $action = "p_show_item"; }
      else { $page = "d_".$this->widget_data['pl_title']."_feed"; $action = "p_show_feed"; }
      $active = "1"; $w=1;
      ?>
      <div class="m-b-30 scrollbars-x2">
        <ul class="title_nav nav-hash nav-unselect" hash_id="pagenav_<?=$this->widget_data['plugin_id']?><?=$this->content_data_id?>">
          <li class="title <? if($active == $w){ echo "active"; } ?>"><a class="nav-load"
          href="#<?=$this->widget_data['pl_title']?>_pp<?=e_a($this->content_data,'content_id')?>" target="#main-page-widgets" link="<?=URL_PATH?>?page=<?=encode($page)?>&action=<?=encode($action)?>&widg=<?=encode($this->widget_data['layout_id'])?>&link=<?=encode($this->link)?>&item=<?=encode($this->content_data_id)?>" data-toggle="tab">
            <h1 class="w-m-320 ellipsis"><i class="fa fa-<?=$this->widget_data['font_icon']?> f-c-themed"></i> <? if(!empty($this->page_title)){ echo $this->page_title; } else { echo $this->widget_data['title']; } ?></h1>
          </a></li>
          <li class="title"><div class="p-4 p-l-15 p-r-15 f-s-20 f-c-lightgray"><i class="fa fa-angle-double-right"></i></div></li>
          <? if(!empty($this->page_widgets['page'])) { foreach($this->page_widgets['page'] as $w_row) { $w++; ?>
            <li class="<? if($active == $w){ echo "active"; } ?>"><a class="nav-load"
  href="#<?=$w_row['pl_title']?>_pp<?=e_a($this->content_data,'content_id')?>" target="#main-page-widgets" link="<?=URL_PATH?>?page=<?=encode("d_".$w_row['pl_title']."_feed")?>&action=<?=encode('p_show_feed')?>&widg=<?=encode($w_row['layout_id'])?>&link=<?=encode($this->link)?>"
               data-toggle="tab" >
              <i class="fa fa-<?=$w_row['font_icon']?>"></i> <?=$w_row['title']?>
            </a></li>
          <? } } ?>
          <li></li>
        </ul>
      </div>
      <? if(!empty($this->page_widgets['dash'])) { ?>
        <div class="grid-stack grid-stack-static">
          <? foreach($this->page_widgets['dash'] as $w_row) { ?>
            <div class="grid-stack-item" <?=$w_row['widget_size']?> data-id="<?=encode($w_row['layout_id'])?>" data-gs-height="2">
              <div class="grid-stack-item-content">
                <? if(!empty($w_row['pl_title'])) { $temp = "d_".$w_row['pl_title']; $item = new $temp($w_row,$this->link); $item->p_dash_widget(); } ?>
              </div>
            </div>
          <? }?>
        </div>
      <? } ?>
      <div class="tab-content" id="main-page-widgets"> </div>
    </div>
    <?
  }
  //=========================================
	public function _page_main_header() {
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

      <? if (isset($_SESSION['loginmessages'])){ ?>
        <script>
        var timezone_offset_minutes = new Date().getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
        $.ajax({
  				type: "POST",
  				url: "<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("p_set_timezone")?>",
  				data: { timezone: timezone_offset_minutes },
  				success: function (msg) {
  					console.log(msg);
  				}
  			});
        </script>
        <? unset($_SESSION['loginmessages']); ?>
      <? } ?>

      <? if (isset($_SESSION['alertmessage'])){ ?>
        <script>new Noty({ text: '<i class="fa fa-bell m-r-xs"></i><?=$_SESSION["alertmessage"]?>' }).show();</script>
        <? unset($_SESSION['alertmessage']); ?>
      <? } ?>


    <?
  }
  //=========================================
  public function _page_main_topnavigation() {
    ?>
    <nav class="navbar navbar-fixed-top bg-themed f-c-white">
      <ul class="pull-right" id="notify_refresh">
        <li style="display:none;">
          <a class="dropdown-toggle count-info f-c-white f-c-h-dark" data-toggle="dropdown">
            <i class="fa fa-envelope"></i> <span class="messa-messagetotal label label-danger"></span>
          </a>
          <ul id="topnav-messages" class="dropdown-menu animated fadeInDown">
            <li class="text-themed"><strong>New Messages</strong></li>
            <li class="divider"></li>
            <li>
              <ul class="scrollbars"></ul>
            </li>
            <li class="divider"></li>
            <li>
              <div class="text-center">
                <a href="<?=URL_PATH?>messages">
                  <i class="fa fa-envelope"></i> <strong>View All Messages</strong>
                </a>
              </div>
            </li>
          </ul>
        </li>
        <li style="display:none;">
          <a class="dropdown-toggle count-info f-c-white f-c-h-dark" data-toggle="dropdown">
            <i class="fa fa-bell"></i> <span class="notif-notifytotal label label-danger"></span>
          </a>
          <ul id="topnav-notifications" class="dropdown-menu animated fadeInDown">
            <li class="text-themed"><a id="clear_notifications" link="<?=URL_PATH?>pipe.php?page=<?=encode("notifications")?>&action=<?=encode("a_clearnotifications")?>" class="pull-right"><i class="fa fa-eye"></i> Clear All</a><strong>New Notifications</strong></li>
            <li class="divider"></li>
            <li>
              <ul class="scrollbars"></ul>
            </li>
            <li class="divider"></li>
            <li>
              <div class="text-center">
                <a href="<?=URL_PATH?>notifications">
                  <i class="fa fa-bell"></i> <strong>View All Notifications</strong>
                </a>
              </div>
            </li>
          </ul>
        </li>
        <li class="b-s-0 b-s-l-0 b-solid b-c-t-black-5 elementlink" id="logout" href="<?=URL_PATH?>?page=<?=encode("a_members")?>&action=<?=encode("p_logout")?>" title="Logout">
          <? if(empty($this->member_data['profile_img'])) { ?>
            <img data-name="<?=$this->member_data['firstname']." ".$this->member_data['lastname']?>" class="w-50 m-5 b-s-1 b-solid b-r-1 b-c-t-black-3 profile_img">
          <? } else { ?>
            <img class="w-50 m-5 b-s-1 b-solid b-r-1 b-c-t-black-3 " src="<?=URL_PATH.$this->member_data['profile_img']?>">
          <? } ?>
        </li>
      </ul>
      <ul class="nav-width bg-dark">
        <li><a id="co_collapse_button" class="f-c-white f-c-h-themed"><i class="fa fa-bars"></i></a></li>
        <li class="f-w-600 relative">
          <a data-content='ADAPTDESIGN' class="nav-brand f-c-white f-c-h-white scroll_to" href="<?=URL_PATH?>">
            <fra>A</fra>dapt<fra>A</fra>dmin<div class="blink_line"></div>
          </a>
        </li>
      </ul>
      <ul class="">
        <li class="site-search relative elementlink" href="<?=URL_PATH?>members">
          <input class="form-control whiteplaceholder" placeholder="SEARCH GLOBAL GROUPS AND PEOPLE...">
          <i class="fa fa-search"></i>
        </li>
      </ul>
    </nav>
    <?
  }
  //=========================================
  public function _page_main_sidenavigation() {
    ?>
    <nav class="navbar-default navbar-static-side bg-gray">
      <ul class="nav nav-pills nav-stacked">
        <li class="carrotsmall bg-white carrotsmall">
          <a class="collapsed p-l-50 f-c-themed f-c-h-dark b-s-0 b-solid b-c-l-themed" data-toggle="collapse" href="#grp_lps">
            <? if(!empty($this->member_data['group_logo'])) { ?>
              <img src="<?=URL_PATH.$this->member_data['group_logo']?>">
            <? } else { ?>
              <img src="<?=URL_PATH?>dir_img/group_logo.png">
            <? } ?>
            <?=$this->member_data['group_name']?><span></span></a>
          <ul id="grp_lps" class="nav nav-pills nav-stacked collapse">
            <?
            $stmt = $this->pdo->prepare("SELECT * FROM groups WHERE status='2'
            AND member_id=:member_id AND group_id NOT IN (:group_id)
            UNION SELECT t1.* FROM groups t1 INNER JOIN connections t2 ON t2.group_id = t1.group_id WHERE t2.connection_type='groupmember' AND t1.status='2' AND t2.approval='2'
            AND t2.member_id=:member_id AND t2.group_id NOT IN (:group_id)
            ORDER BY level ASC, created_date DESC");
            $stmt->execute(array(':member_id' => $this->member_data['member_id'], ':group_id' => $this->member_data['group_id']));
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
              <li>
                <a class="do_ajax p-l-50 f-c-default f-c-h-themed b-s-0 b-solid b-c-l-themed" da_type="reload" da_target="location" da_link="<?=URL_PATH?>pipe.php?page=<?=encode("a_members")?>&action=<?=encode("p_edit_member")?>" data-inputse[group_id]="<?=encode($row['group_id'])?>" data-wheres[member_id]="<?=encode($this->member_data['member_id'])?>">
                <? if($row['level'] == "3") { ?>
                  <img src="<?=URL_PATH?>dir_img/nogroup.png">
                <? } elseif(!empty($row['group_logo'])) { ?>
                  <img src="<?=URL_PATH.$row['group_logo']?>">
                <? } else { ?>
                  <img src="<?=URL_PATH?>dir_img/group_logo.png">
                <? } ?>
                <? if($row['level'] == "3") { echo "No Group"; } else { echo $row['title']; } ?></a>
              </li>
            <? } ?>
          </ul>
        </li>

        <li <? if ($this->ses_page == $this->menu_widgets['dashboard']['pl_title']){ echo " class='active'"; } ?>>
          <a class="f-c-default f-c-h-themed" href='<?=URL_PATH.$this->menu_widgets['dashboard']['pl_title']?>'><i class='fa fa-<?=$this->menu_widgets['dashboard']['font_icon']?> f-s-14'></i>
            <?=$this->menu_widgets['dashboard']['title']?>
            <span class="notif-<?=$this->menu_widgets['dashboard']['pl_title']?> label label-warning pull-right" title="" style="display:none;"></span>
          </a>
        </li>
        <? if($this->member_data['group_status'] == "2") { ?>
          <li <? if ($this->ses_page == $this->menu_widgets['groupmembers']['pl_title']){ echo " class='active'"; } ?>>
            <a class="f-c-default f-c-h-themed" href='<?=URL_PATH.$this->menu_widgets['groupmembers']['pl_title']?>'><i class='fa fa-<?=$this->menu_widgets['groupmembers']['font_icon']?> f-s-14'></i>
              <?=$this->menu_widgets['groupmembers']['title']?>
              <span class="notif-<?=$this->menu_widgets['groupmembers']['pl_title']?> label label-warning pull-right" title="" style="display:none;"></span>
            </a>
          </li>
        <? }
        if(!empty($this->menu_items)) { foreach($this->menu_items as $pl_title=>$row) { ?>
          <li <? if ($this->ses_page == $row['pl_title']){ echo " class='active'"; } ?>>
            <a class="f-c-default f-c-h-themed" href='<?=URL_PATH.$pl_title?>'><i class='fa fa-<?=$row['font_icon']?> f-s-14'></i>
              <?=$row['title']?>
              <span class="notif-<?=$row['pl_title']?> label label-warning pull-right" title="" style="display:none;"></span>
            </a>
          </li>
        <? } } ?>
        <li <? if ($this->ses_page == $this->menu_widgets['groupsettings']['pl_title']){ echo " class='active'"; } ?>>
          <a class="f-c-default f-c-h-themed" href='<?=URL_PATH.$this->menu_widgets['groupsettings']['pl_title']?>'><i class='fa fa-<?=$this->menu_widgets['groupsettings']['font_icon']?> f-s-14'></i>
            <?=$this->menu_widgets['groupsettings']['title']?>
            <span class="notif-<?=$this->menu_widgets['groupsettings']['pl_title']?> label label-warning pull-right" title="" style="display:none;"></span>
          </a>
        </li>
        <?
        if(!empty($this->menu_folders)) { foreach($this->menu_folders as $key => $menu_folder) {
          if(empty($menu_folder['plugins'])) { continue; } ?>
          <li id="mmp_collapse<?=$key?>" class="carrotsmall">
            <a class="collapsed f-c-default f-c-h-themed b-s-0 b-solid b-c-l-themed" data-toggle="collapse" data-parent="#mmp_collapse<?=$key?>" href="#mm_collapse<?=$key?>"><i class="fa fa-folder f-s-14"></i> <?=$menu_folder['settings']['title']?><span></span></a>
            <ul id="mm_collapse<?=$key?>" class="nav nav-pills nav-stacked bg-white collapse admin_menu">
              <? if(!empty($menu_folder['plugins'])) { foreach($menu_folder['plugins'] as $pl_title=>$row) { ?>
                <li <? if ($this->ses_page == $row['pl_title']){ echo " class='active'"; } ?>>
                  <a class="f-c-default f-c-h-themed b-s-0 b-solid b-c-l-themed" href='<?=URL_PATH.$pl_title?>'><i class='fa fa-<?=$row['font_icon']?> f-s-14'></i>
                    <?=$row['title']?>
                    <span class="notif-<?=$row['pl_title']?> label label-warning pull-right" title="" style="display:none;"></span>
                  </a>
                </li>
              <? } } ?>
            </ul>
            <span class="notif-notifyfolder label label-warning pull-right" title="" style="display: none;"></span>
          </li>
        <? } } ?>
        <li style="display:block;margin-bottom:50px;content:' ';" id="pre_config" ses_link="<?=URL_PATH?>?page=<?=encode("_global")?>&action=<?=encode("p_session_var")?>" link="<?=URL_PATH?>?page=<?=encode("_global")?>&action=<?=encode("p_nav_hash")?>" get_link="<?=URL_PATH?>?page=<?=encode("_global")?>&action=<?=encode("p_nav_hash_get")?>">
          <div class="device-xs visible-xs"></div>
          <div class="device-sm visible-sm"></div>
          <div class="device-md visible-md"></div>
          <div class="device-lg visible-lg"></div>
          <div class="device-xl visible-xl"></div>
        </li>
      </ul>
    </nav>
    <?
  }
  //=========================================
  public function _page_main_js() {
    ?>
    <script type="text/javascript">
    </script>
    <?
  }
  //=========================================
  public function _page_main_footer() {
    ?>
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
        <span id="pageLogger"></span>
        <!-- <script src="https://adaptadmin.com/api.js?id=9C886B19075F6" type="text/javascript"></script> -->
        <script src="<?=URL_PATH?>dir_js/global.js?v=<?=uniqid()?>"></script>
      </body>
    </html>
    <?
  }
  //=========================================



}
?>
