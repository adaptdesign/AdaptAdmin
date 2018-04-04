<?php

class _global_content extends _global_admin {
  public $dbtype_inputs = array("content_id","status","level","order_id","member_id","group_id","plugin_id","privacy","approval","priority","due_date","complete_date","edit_date","created_date","title","linked_to","message","u_name","u_link","other");
  public $required = array();
  public $activity_msg = "added new content";
  public $notification_msg = "assigned you to an item";
  public $return_msg = "Content created";

  public function __construct(){
		parent::__construct();

    if(!$this->_post_format_dbtype()) { }
  }
  //=========================================
  public function _set_filters_dbtype($prefix="",$item="") {
    // MAY NOW BE OBSOLITE
		$parent_id = e_a($_GET,'parent',1,1); //preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_GET['parent']));

    // ADD _global_display FILTERS
    $return = $this->_set_filters_global($prefix); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

		// SET APPROVAL
		if(e_a($this->widget_data,'approval') == "2") {
			if(e_a($this->member_data,'group_level') > '2') {
				$sql .= "AND ".$prefix."approval IN ('1','2') ";
			} else {
				$sql .= "AND (".$prefix."approval IN ('2') OR ".$prefix."member_id='?') ";
        $arg[] = $this->member_data['member_id'];
			}
		}

		// ADD SEARCH FILTERS
    if(!empty($_POST['params'])){
      $params = explode(",", rtrim(preg_replace('/[^a-z A-Z0-9-_\,\~\.\/\:\=\@\|]/','', $_POST['params']), ','));
      foreach($params as $param) {
        $pts = explode("|", $param);
        if($pts[0] == 'search') {
          $searchs = array("title","message","other","u_name");
          if(!empty($searchs) && !empty($pts[1])){
            $sql .= "AND (lower(".$prefix."".$searchs[0].") LIKE ? "; $arg[] = "%".$pts[1]."%"; unset($searchs[0]);
            foreach($searchs as $search) { $sql .= "OR lower(".$prefix."".$search.") LIKE ? "; $arg[] = "%".$pts[1]."%"; }
            $sql .= ") ";
          }
        } elseif($pts[0] == 'level' && $this->widget_data['pl_title'] == "tasks") {
          $sql .= "AND ".$prefix."level=? ";
          $arg[] = $pts[1];
        } elseif($pts[0] == 'creator') {
          $sql .= "AND ".$prefix."member_id=? ";
          $arg[] = $pts[1];
        } elseif($pts[0] == 'assigned') {
          $sql .= "AND (".$prefix."member_id=? OR lower(".$prefix."linked_to) LIKE ?) ";
          $arg[] = $pts[1]; $arg[] = "%-".$this->plugins['groupmembers']['plugin_id'].",".$pts[1]."-%";
        } elseif($pts[0] == 'daterange') {
          $dates =  explode(" ~ ", $pts[1]);
          $sql .= "AND (".$prefix."created_date BETWEEN ? AND ?) ";
          $arg[] = $dates[0]; $arg[] = $dates[1];
        }
      }
    }

		// SET SINGLE ELEMENT
		if(!empty($item) && is_numeric($item)){
			$sql .= "AND ".$prefix."content_id=? ";
      $arg[] = $item;
			$sql .= "AND (".$prefix."privacy IN ('1') OR (".$prefix."member_id=? OR lower(".$prefix."linked_to) LIKE ?)) ";
      $arg[] = $this->member_data['member_id']; $arg[] = "%-".$this->plugins['groupmembers']['plugin_id'].",".$this->member_data['member_id']."-%";
		} else {
			// SET IF REPLY
			if(!empty($parent_id) && is_numeric($parent_id)){
				$sql .= "AND lower(".$prefix."linked_to) LIKE ? ";
        $arg[] = "%-reply,".$parent_id."-%";
				$sql .= "AND (".$prefix."privacy IN ('1') OR (".$prefix."member_id=? OR lower(".$prefix."linked_to) LIKE ?)) ";
        $arg[] = $this->member_data['member_id']; $arg[] = "%-".$this->plugins['groupmembers']['plugin_id'].",".$this->member_data['member_id']."-%";
			} else {
				// SET PRIVACY
				if(e_a($this->widget_data,'privacy') == "2") {
					$sql .= "AND ".$prefix."privacy IN ('1','2') ";
					$sql .= "AND (".$prefix."member_id=? OR lower(".$prefix."linked_to) LIKE ?) ";
          $arg[] = $this->member_data['member_id']; $arg[] = "%-".$this->plugins['groupmembers']['plugin_id'].",".$this->member_data['member_id']."-%";
				} else {
					$sql .= "AND (".$prefix."privacy IN ('1') OR (".$prefix."member_id=? OR lower(".$prefix."linked_to) LIKE ?)) ";
          $arg[] = $this->member_data['member_id']; $arg[] = "%-".$this->plugins['groupmembers']['plugin_id'].",".$this->member_data['member_id']."-%";
				}
				// SET WIDGETONLY
				if(e_a($this->widget_data,'widgetonly') == "2") {
					if(empty($this->link_plugin_id)) { return array("bad","Error: [".basename(__FILE__)."] [".__LINE__."]"); }
					if(empty($this->link_content_id)) { $sql .= "AND lower(".$prefix."linked_to) LIKE ? "; $arg[] = "%-".$this->link_plugin_id.",%"; }
					else { $sql .= "AND lower(".$prefix."linked_to) LIKE ? "; $arg[] = "%-".$this->link_plugin_id.",".$this->link_content_id."-%"; }
				}
				$sql .= "AND (lower(".$prefix."linked_to) LIKE '%-reply,%') IS NOT TRUE ";
				// ADD PENDING APPROVAL SECOND QUERY
				if(e_a($this->widget_data,'approval') == "2" && e_a($this->member_data,'group_level') > '2') {
					$sql .= "UNION SELECT t1.* FROM content t1 INNER JOIN content t2 ON t2.content_id = t1.content_id WHERE t2.plugin_id='?' AND t2.group_id='?' AND t2.approval IN ('1') ";
          $arg[] = $this->widget_data['plugin_id']; $arg[] = $this->member_data['group_id'];
				}
			}
		}
		return array($sql,$arg);
	}
  //=========================================
  public function _format_row_dbtype($row) {
    if(empty($row)) { return; }
    // FORMAT GLOBAL
    $row = $this->_format_row_global($row);
    // START FORMAT
    return $row;
  }
  //=========================================
	private function _post_format_dbtype() {
    if(!empty($this->input_array)) {
      foreach($this->input_array as $key => $value) {
  			if(!in_array($key, $this->dbtype_inputs)) {
  				$this->input_array["other"] = e_a($this->input_array,"other").strtr($key, array('|' => ''))."-|-".strtr($value, array('|' => ''))."-||-";
  				unset($this->input_array[$key]);
  			}
  		}
  		if(!empty($this->input_array["other"])) { $this->input_array["other"] = rtrim($this->input_array["other"], '||'); }
    }
	}
  //=========================================
	public function _add_content() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }

    // APPROVAL SETTINGS
    if(e_a($this->widget_data,'approval') == "2" && $this->member_data['group_level'] < "3") { $this->input_array['approval'] = "1"; }
    else { $this->input_array['approval'] = "2"; }

    // NOTIFICATION SETTINGS
    if(e_a($this->widget_data,'notifications') == "2") { $notify = true; }
    else { $notify = false; }

    // FORMAT INPUT_ARRAY
    if(empty($this->input_array['plugin_id'])) { $this->input_array['plugin_id'] = $this->plugins[$this->widget_data['pl_title']]['plugin_id']; }
    if(empty($this->input_array['group_id'])) { $this->input_array['group_id'] = $this->member_data['group_id']; }
    if(empty($this->input_array['member_id'])) { $this->input_array['member_id'] = $this->member_data['member_id']; }

    foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    // ATTACH UPLOADS
    if(!empty($_FILES["attachment"]["name"])) {
      $target_dir = DOC_UPLOADS."/";
      $u_id = uniqid();
      $extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
      $newName = $target_dir . $u_id . "_" . basename($_FILES["attachment"]["name"]);
      if(!in_array(strtolower($extension), $this->allowed_files)){ $this->fail_page[] = "Invalid file extension"; }
      if (file_exists($newName)) { $this->fail_page[] = "File name already exists"; }
      if ($_FILES["attachment"]["size"] > (1024*1024 * 10)) { $this->fail_page[] = "File is to large (10 MB limit)"; }
      if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $newName)) { $finalname = $u_id . "_" . basename($_FILES["attachment"]["name"]); }
      else { $this->fail_page[] = "Error uploading attachment"; }
      $this->input_array["u_name"] = basename($_FILES['attachment']['name']);
      $this->input_array["u_link"] = $finalname;
    } elseif(!empty($_POST["my_hidden"])){
      $decoded = $_POST["my_hidden"];
      if (strpos($decoded,',') !== false) { }
      else { $this->fail_page[] = "Please select and save an image"; }
      $randomId = uniqid();
      $exp = explode(',', $decoded);
      $base64 = array_pop($exp);
      $data = base64_decode($base64);
      $image = imagecreatefromstring($data);
      imagepng($image, URL_ROOT."/dir_img/profile_img/$randomId.png", 9);
      $this->input_array["u_name"] = $randomId.".png";
      $this->input_array["u_link"] = "dir_img/profile_img/$randomId.png";
    }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // ADD INSERT
    if(!$this->_add_insert("content", $this->input_array)) { $this->fail_page[] = "Error: [".basename(__FILE__)."] [".__LINE__."]"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    $last_id = $this->pdo->lastInsertId();
    $this->input_array['content_id'] = $last_id;
    $this->input_array["created_date"] = date("Y-m-d H:i:s");

    // START ACTIVITIES AND NOTIFICATIONS
    $act_linked = $this->input_array['linked_to'];
    $notif_url = $this->widget_data['pl_title']."/".$last_id;

    $to_array= array();
    if($this->input_array['approval'] == "1") {
      $notify = true;
      foreach($this->pdo->query("SELECT member_id FROM groupmembers WHERE approval='2' AND level > '2' AND group_id='".$this->member_data['group_id']."'") as $row) {
        $to_array[] = $row['member_id'];
      }
    } elseif($notify == true) {
      $to_array[] = $this->input_array['member_id'];
      $linkto_parts = explode("|", $this->input_array['linked_to']);
      foreach($linkto_parts as $linkto_part) {
        if(strpos($linkto_part, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
          $linkto_part = explode(",", $linkto_part);
          $to_array[] = strtr($linkto_part[1], array('-' => ''));
        } else {
          $linkto_part = explode(",", $linkto_part);
          $stmt = $this->pdo->prepare("SELECT linked_to FROM content WHERE content_id=:content_id AND group_id=:group_id LIMIT 1");
          $stmt->execute(array(':content_id' => strtr($linkto_part[1], array('-' => '')), ':group_id' => $this->member_data['group_id']));
          if($stmt->rowCount() == 1){
            $content_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $to_array[] = $content_data['member_id'];
            $content_parts = explode("|", $content_data['linked_to']);
            $act_linked .= "|".$content_data['linked_to'];
            foreach($content_parts as $content_part) {
              if(strpos($content_part, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
                $content_part = explode(",", $content_part);
                $to_array[] = strtr($content_part[1], array('-' => ''));
              }
            }
          }
        }
      }
    }
    $act_linked = rtrim($act_linked, '|');
    $this->_add_activity($notify,$to_array,$this->plugins[$this->widget_data['pl_title']]['plugin_id'],$last_id,$act_linked,$this->activity_msg,$this->notification_msg,$notif_url);
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // ADD CONTENT SUCCESS
    return array($this->input_array);
	}
	//=========================================
	public function _update_content() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }

    // APPROVAL SETTINGS
    if(e_a($this->widget_data,'approval') == "2" && $this->member_data['group_level'] < "3") { $this->input_array['approval'] = "1"; }
    else { $this->input_array['approval'] = "2"; }

    // NOTIFICATION SETTINGS
    if(e_a($this->widget_data,'notifications') == "2") { $notify = true; }
    else { $notify = false; }

    foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    // GET CONTENT DATA
    $stmt = $this->pdo->prepare("SELECT * FROM content WHERE content_id=:content_id LIMIT 1"); $stmt->execute(array(':content_id' => $this->where_array['content_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Unable to select content"; }
    if(e_a($stmt_data,'notifications') != "2" && $this->member_data['group_level'] > "2") { $this->input_array['approval'] = "2"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    // ATTACH UPLOADS
    if(!empty($_FILES["attachment"]["name"])) {
      $target_dir = DOC_UPLOADS."/";
      $u_id = uniqid();
      $extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
      $newName = $target_dir . $u_id . "_" . basename($_FILES["attachment"]["name"]);
      if(!in_array(strtolower($extension), $this->allowed_files)){ $this->fail_page[] = "Invalid file extension"; }
      if (file_exists($newName)) { $this->fail_page[] = "File name already exists"; }
      if ($_FILES["attachment"]["size"] > (1024*1024 * 10)) { $this->fail_page[] = "File is to large (10 MB limit)"; }
      if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $newName)) { $finalname = $u_id . "_" . basename($_FILES["attachment"]["name"]); }
      else { $this->fail_page[] = "Error uploading attachment"; }
      $this->input_array["u_name"] = basename($_FILES['attachment']['name']);
      $this->input_array["u_link"] = $finalname;
      if(!empty($stmt_data['u_link'])) { if(file_exists(DOC_UPLOADS."/".$stmt_data['u_link'])) { unlink(DOC_UPLOADS."/".$stmt_data['u_link']); } };
    } elseif(!empty($_POST["my_hidden"])){
      $decoded = $_POST["my_hidden"];
      if (strpos($decoded,',') !== false) { }
      else { $this->fail_page[] = "Please select and save an image"; }
      $randomId = uniqid();
      $exp = explode(',', $decoded);
      $base64 = array_pop($exp);
      $data = base64_decode($base64);
      $image = imagecreatefromstring($data);
      imagepng($image, URL_ROOT."/dir_img/profile_img/$randomId.png", 9);
      $this->input_array["u_name"] = $randomId.".png";
      $this->input_array["u_link"] = "dir_img/profile_img/$randomId.png";
      if(!empty($stmt_data['u_link'])) { if(file_exists(URL_ROOT."/".$stmt_data['u_link'])) { unlink(URL_ROOT."/".$stmt_data['u_link']); } };
    }

    // UPDATE CONTENT
    if(!$this->_add_update("content", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error: [".basename(__FILE__)."] [".__LINE__."]"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    $last_id = e_a($stmt_data,'content_id');
    foreach($stmt_data as $key=>$value) { if(empty($this->input_array[$key])) { $this->input_array[$key] = $value; } }

    // START ACTIVITIES AND NOTIFICATIONS
    $act_linked = $this->input_array['linked_to'];
    $notif_url = $this->widget_data['pl_title']."/".$last_id;

    if($this->input_array['approval'] == "1") {
      $notify = true;
      foreach($this->pdo->query("SELECT member_id FROM groupmembers WHERE approval='2' AND level > '2' AND group_id='".$this->member_data['group_id']."'") as $row) {
        $to_array[] = $row['member_id'];
      }
    } elseif($notify == true) {
      $to_array[] = $this->input_array['member_id'];
      $linkto_parts = explode("|", $this->input_array['linked_to']);
      foreach($linkto_parts as $linkto_part) {
        if(strpos($linkto_part, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
          $linkto_part = explode(",", $linkto_part);
          $to_array[] = strtr($linkto_part[1], array('-' => ''));
        } else {
          $linkto_part = explode(",", $linkto_part);
          $stmt = $this->pdo->prepare("SELECT linked_to FROM content WHERE content_id=:content_id AND group_id=:group_id LIMIT 1");
          $stmt->execute(array(':content_id' => strtr($linkto_part[1], array('-' => '')), ':group_id' => $this->member_data['group_id']));
          if($stmt->rowCount() == 1){
            $content_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $to_array[] = $content_data['member_id'];
            $content_parts = explode("|", $content_data['linked_to']);
            $act_linked .= "|".$content_data['linked_to'];
            foreach($content_parts as $content_part) {
              if(strpos($content_part, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
                $content_part = explode(",", $content_part);
                $to_array[] = strtr($content_part[1], array('-' => ''));
              }
            }
          }
        }
      }
    }
    $act_linked = rtrim($act_linked, '|');
    $this->_add_activity($notify,$to_array,$this->plugins[$this->widget_data['pl_title']]['plugin_id'],$last_id,$act_linked,$this->activity_msg,$this->notification_msg,$notif_url);
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // ADD CONTENT SUCCESS
    return array($this->input_array);
	}
	//=========================================
	public function delete_content() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }

    // NOTIFICATION SETTINGS
    if(e_a($this->widget_data,'notifications') == "2") { $notify = true; }
    else { $notify = false; }

    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    // GET CONTENT DATA
    $stmt = $this->pdo->prepare("SELECT * FROM content WHERE content_id=:content_id LIMIT 1"); $stmt->execute(array(':content_id' => $this->where_array['content_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Unable to select content"; }
    // VERIFY MEMBER
    if($this->member_data['group_level'] < '3' || e_a($stmt_data,'member_id') != $this->member_data['member_id']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============

    // DELETE ATTACHMENTS
    $stmt = $this->pdo->prepare("SELECT * FROM content WHERE lower(linked_to) LIKE :content_id AND group_id=:group_id");
    if($stmt->execute(array(':content_id'=>"%-reply,".$stmt_data['content_id']."-%",':group_id'=>$this->member_data['group_id']))) {
      while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        if(!empty($row['u_link'])) { if (file_exists(URL_ROOT."/".$row['u_link'])) { unlink(URL_ROOT."/".$row['u_link']); } };
        if(!empty($row['u_link'])) { if (file_exists(DOC_UPLOADS."/".$row['u_link'])) { unlink(DOC_UPLOADS."/".$row['u_link']); } };
        if(!$this->add_delete("content", "content_id", $row['content_id'], " LIMIT 1")) { $this->fail_page[] = "Issue removing linked content"; }
      }
    }
    if(!empty($stmt_data['u_link'])) { if (file_exists(URL_ROOT."/".$stmt_data['u_link'])) { unlink(URL_ROOT."/".$stmt_data['u_link']); } };
    if(!empty($stmt_data['u_link'])) { if (file_exists(DOC_UPLOADS."/".$stmt_data['u_link'])) { unlink(DOC_UPLOADS."/".$stmt_data['u_link']); } };
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // DELETE CONTENT
    if(!$this->add_delete("content", "content_id", $this->where_array['content_id'], " LIMIT 1")) { $this->fail_page[] = "Error: [".basename(__FILE__)."] [".__LINE__."]"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    $last_id = e_a($stmt_data,'content_id');
    foreach($stmt_data as $key=>$value) { if(empty($this->input_array[$key])) { $this->input_array[$key] = $value; } }

    // START ACTIVITIES AND NOTIFICATIONS
    $act_linked = $this->input_array['linked_to'];
    $notif_url = $this->widget_data['pl_title']."/".$last_id;

    if($this->input_array['approval'] == "1") {
      $notify = true;
      foreach($this->pdo->query("SELECT member_id FROM groupmembers WHERE approval='2' AND level > '2' AND group_id='".$this->member_data['group_id']."'") as $row) {
        $to_array[] = $row['member_id'];
      }
    } elseif($notify == true) {
      $to_array[] = $this->input_array['member_id'];
      $linkto_parts = explode("|", $this->input_array['linked_to']);
      foreach($linkto_parts as $linkto_part) {
        if(strpos($linkto_part, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
          $linkto_part = explode(",", $linkto_part);
          $to_array[] = strtr($linkto_part[1], array('-' => ''));
        } else {
          $linkto_part = explode(",", $linkto_part);
          $stmt = $this->pdo->prepare("SELECT linked_to FROM content WHERE content_id=:content_id AND group_id=:group_id LIMIT 1");
          $stmt->execute(array(':content_id' => strtr($linkto_part[1], array('-' => '')), ':group_id' => $this->member_data['group_id']));
          if($stmt->rowCount() == 1){
            $content_data = $stmt->fetch(PDO::FETCH_ASSOC);
            $to_array[] = $content_data['member_id'];
            $content_parts = explode("|", $content_data['linked_to']);
            $act_linked .= "|".$content_data['linked_to'];
            foreach($content_parts as $content_part) {
              if(strpos($content_part, "-".$this->plugins['groupmembers']['plugin_id'].",") !== false) {
                $content_part = explode(",", $content_part);
                $to_array[] = strtr($content_part[1], array('-' => ''));
              }
            }
          }
        }
      }
    }
    $act_linked = rtrim($act_linked, '|');
    $this->_add_activity($notify,$to_array,$this->plugins[$this->widget_data['pl_title']]['plugin_id'],$last_id,$act_linked,$this->activity_msg,$this->notification_msg,$notif_url);
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // ADD CONTENT SUCCESS
    return array($this->input_array);
	}

}
?>
