<?php

class a_layout extends global_layout {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_folder() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // FORMAT INPUT_ARRAY
    if(empty($this->input_array["layout_type"])) { $this->input_array["layout_type"] = "menu_folder"; }
    if(empty($this->input_array["group_id"])) { $this->input_array["group_id"] = $this->member_data['group_id']; }
    if(empty($this->input_array["member_id"])) { $this->input_array["member_id"] = $this->member_data['member_id']; }
    // CHECK IF TITLE EXISTS
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='menu_folder' AND title=:title AND group_id=:group_id LIMIT 1");
    $stmt->execute(array(':title' => $this->input_array['title'], ':group_id' => $this->member_data['group_id']));
    if($stmt->rowCount() > 0) { $this->fail_page[] = "Title already in use. Please try another"; }
    // SET MESSAGES
    $this->required = array("title","group_id","member_id","layout_type");
    $this->activity_msg = "Added a menu folder";
    $this->notification_msg = "Added a menu folder";
    $this->return_msg = "Folder added";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_insert("layout", $this->input_array)) { $this->fail_page[] = "Error processing insert"; }
    $last_id = $this->pdo->lastInsertId();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$last_id,"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
  public function p_edit_folder() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='menu_folder' AND layout_id=:layout_id LIMIT 1");
    $stmt->execute(array(':layout_id' => $this->where_array['layout_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // CHECK IF TITLE EXISTS
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='menu_folder' AND title=:title AND group_id=:group_id LIMIT 1");
    $stmt->execute(array(':title' => $this->input_array['title'], ':group_id' => $this->member_data['group_id']));
    if($stmt->rowCount() > 0) { $this->fail_page[] = "Title already in use. Please try another"; }
    // SET MESSAGES
    $this->required = array("title");
    $this->activity_msg = "Updated a menu folder";
    $this->notification_msg = "Updated a menu folder";
    $this->return_msg = "Folder updated";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_update("layout", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$this->where_array['layout_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
  }
	//=========================================
  public function p_delete_folder() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='menu_folder' AND layout_id=:layout_id LIMIT 1");
    $stmt->execute(array(':layout_id' => $this->where_array['layout_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Removed a menu folder";
    $this->notification_msg = "Removed a menu removed";
    $this->return_msg = "Folder removed";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_delete("layout", "layout_id", $this->where_array['layout_id'], " LIMIT 1")) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$this->where_array['layout_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
  //=========================================
  public function p_add_widget() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // FORMAT INPUT_ARRAY
    if(empty($this->input_array["group_id"])) { $this->input_array["group_id"] = $this->member_data['group_id']; }
    if(empty($this->input_array["member_id"])) { $this->input_array["member_id"] = $this->member_data['member_id']; }
    // SET MESSAGES
    $this->required = array("group_id","member_id","layout_type","parent_id");
    $this->activity_msg = "Added a widget";
    $this->notification_msg = "Added a widget";
    $this->return_msg = "Widget added";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_insert("layout", $this->input_array)) { $this->fail_page[] = "Error processing insert"; }
    $last_id = $this->pdo->lastInsertId();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$last_id,"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_edit_widget() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_id=:layout_id LIMIT 1");
    $stmt->execute(array(':layout_id' => $this->where_array['layout_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Updated a widget";
    $this->notification_msg = "Updated a widget";
    $this->return_msg = "Widget updated";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_update("layout", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$this->where_array['layout_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_delete_widget() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_id=:layout_id LIMIT 1");
    $stmt->execute(array(':layout_id' => $this->where_array['layout_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Removed a widget";
    $this->notification_msg = "Removed a widget";
    $this->return_msg = "Widget removed";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_delete("layout", "layout_id", $this->where_array['layout_id'], " LIMIT 1")) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$this->where_array['layout_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
  //=========================================
  public function p_add_option() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // FORMAT INPUT_ARRAY
    if(empty($this->input_array["layout_type"])) { $this->input_array["layout_type"] = "add_option"; }
    if(empty($this->input_array["group_id"])) { $this->input_array["group_id"] = $this->member_data['group_id']; }
    if(empty($this->input_array["member_id"])) { $this->input_array["member_id"] = $this->member_data['member_id']; }
    // SET OTHER // OPTION OPTIONS
    $option_options = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', $_POST['option_options']);
    if(!empty($option_options)) { foreach($option_options as $value) {
      $this->input_array["option_options"] .= strtr($value, array('\/' => ''))."\/";
    } }
    if(!empty($this->input_array["option_options"])) {
      $this->input_array["option_options"] = rtrim($this->input_array["option_options"], '\/');
      $this->input_array["other"] .= strtr("option_options", array('|' => ''))."-|-".strtr($this->input_array["option_options"], array('|' => ''))."-||-";
      unset($this->input_array["option_options"]);
    }
    // SET OTHER // OPTION TYPE
    if(!empty($this->input_array["option_type"])) {
      $this->input_array["other"] .= strtr("option_type", array('|' => ''))."-|-".strtr($this->input_array["option_type"], array('|' => ''))."-||-";
      unset($this->input_array["option_type"]);
    }
    if(!empty($this->input_array["other"])) { $this->input_array["other"] = rtrim($this->input_array["other"], '-||-'); }
    // SET MESSAGES
    $this->required = array("title","group_id","member_id","plugin_id","layout_type");
    $this->activity_msg = "Added an additional option";
    $this->notification_msg = "Added an additional option";
    $this->return_msg = "Option added";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_insert("layout", $this->input_array)) { $this->fail_page[] = "Error processing insert"; }
    $last_id = $this->pdo->lastInsertId();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$last_id,"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_edit_option() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // FORMAT INPUT_ARRAY
    if(empty($this->input_array["layout_type"])) { $this->input_array["layout_type"] = "add_option"; }
    if(empty($this->input_array["group_id"])) { $this->input_array["group_id"] = $this->member_data['group_id']; }
    if(empty($this->input_array["member_id"])) { $this->input_array["member_id"] = $this->member_data['member_id']; }
    // SET OTHER // OPTION OPTIONS
    $option_options = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', $_POST['option_options']);
    if(!empty($option_options)) { foreach($option_options as $value) {
      $this->input_array["option_options"] .= strtr($value, array('\/' => ''))."\/";
    } }
    if(!empty($this->input_array["option_options"])) {
      $this->input_array["option_options"] = rtrim($this->input_array["option_options"], '\/');
      $this->input_array["other"] .= strtr("option_options", array('|' => ''))."-|-".strtr($this->input_array["option_options"], array('|' => ''))."-||-";
      unset($this->input_array["option_options"]);
    }
    // SET OTHER // OPTION TYPE
    if(!empty($this->input_array["option_type"])) {
      $this->input_array["other"] .= strtr("option_type", array('|' => ''))."-|-".strtr($this->input_array["option_type"], array('|' => ''))."-||-";
      unset($this->input_array["option_type"]);
    }
    if(!empty($this->input_array["other"])) { $this->input_array["other"] = rtrim($this->input_array["other"], '-||-'); }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_id=:layout_id LIMIT 1");
    $stmt->execute(array(':layout_id' => $this->where_array['layout_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array("title");
    $this->activity_msg = "Updated an additional option";
    $this->notification_msg = "Updated an additional option";
    $this->return_msg = "Option updated";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_update("layout", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$this->where_array['layout_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_delete_option() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_id=:layout_id LIMIT 1");
    $stmt->execute(array(':layout_id' => $this->where_array['layout_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Removed an additional option";
    $this->notification_msg = "Removed an additional option";
    $this->return_msg = "Option removed";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_delete("layout", "layout_id", $this->where_array['layout_id'], " LIMIT 1")) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupsettings']['plugin_id'],$this->where_array['layout_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
  //=========================================
  public function p_layout_order() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page); return; } //==============

		if($_POST['type'] == "orderPages") {
			parse_str($_POST['pages'], $pageOrder);
			if(!empty($pageOrder)) { foreach ($pageOrder['page'] as $key => $value) {
        $stmt = $this->pdo->prepare("UPDATE layout SET order_id=:order_id WHERE layout_id=:layout_id");
  			$stmt->execute(array(':order_id' => $key, ':layout_id' => $value));
	    } }
			echo "true| good";
		} elseif($_POST['type'] == "changeParent") {
			$stmt = $this->pdo->prepare("UPDATE layout SET parent_id=:parent_id WHERE layout_id=:layout_id");
			$stmt->execute(array(':parent_id' => $_POST['parent_id'], ':layout_id' => strtr($_POST['item_id'], array('page_' => ''))));
			echo "true| good";
		} else {
			echo "Error";
		}
	}
  //=========================================

}
?>
