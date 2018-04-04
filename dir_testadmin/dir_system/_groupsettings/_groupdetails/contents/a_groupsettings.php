<?php

class a_groupsettings extends _global {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_edit_group() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if($this->member_data['group_level'] < "4") { $this->fail_page[] = "Access to this content is restricted"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM groups WHERE group_id=:group_id LIMIT 1");
    $stmt->execute(array(':group_id' => $this->where_array['group_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array("title");
    $this->activity_msg = "Updated group details";
    $this->notification_msg = "Updated group details";
    $this->return_msg = "Group updated";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // ADD UPLOADS
    if(!empty($_POST["my_hidden"])){
			$decoded = $_POST["my_hidden"];
			if (strpos($decoded,',') !== false) { }
			else {$this->fail_page[] = "Please select and save an image"; }
			$randomId = uniqid();
			$exp = explode(',', $decoded);
			$base64 = array_pop($exp);
			$data = base64_decode($base64);
			$image = imagecreatefromstring($data);
			imagepng($image, URL_ROOT."/dir_img/profile_img/$randomId.png", 9);
			$this->input_array["group_logo"] = "dir_img/profile_img/$randomId.png";
		}
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!empty($this->input_array["group_logo"]) && !empty($stmt_data['group_logo'])) {
      if (file_exists(URL_ROOT."/".$stmt_data['group_logo'])) { unlink(URL_ROOT."/".$stmt_data['group_logo']); }
    }
    if(!$this->_add_update("groups", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groups']['plugin_id'],$this->where_array['group_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}

}
?>
