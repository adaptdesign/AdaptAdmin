<?php

class a_friendslist extends global_friendslist {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_friendslist() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    // FORMAT INPUT_ARRAY
    if(empty($this->input_array['approval'])) { $this->input_array['approval'] = "1"; }
    if(empty($this->input_array['connection_type'])) { $this->input_array['connection_type'] = "friendslist"; }
    if(empty($this->input_array['member_id'])) { $this->input_array['member_id'] = $this->member_data['member_id']; }
    if(empty($this->input_array['from_member_id'])) { $this->input_array['from_member_id'] = $this->member_data['member_id']; }
    // SET MESSAGES
    $this->required = array("approval","connection_type","member_id","from_member_id","to_member_id");
    $this->activity_msg = "Sent a friend request";
    $this->notification_msg = "sent you a friend request";
    $this->return_msg = "Request sent";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_insert("connections", $this->input_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['friendslist']['plugin_id'],$this->where_array['connection_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
  //=========================================
  public function p_edit_friendslist() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM connections WHERE connection_type='friendslist' AND connection_id=:connection_id LIMIT 1");
    $stmt->execute(array(':connection_id' => $this->where_array['connection_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_OBJ); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Updated their friendslist";
    $this->notification_msg = "updated a friend request";
    $this->return_msg = "Friendslist updated";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_update("connections", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['friendslist']['plugin_id'],$this->where_array['connection_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
  }
  //=========================================
  public function p_delete_friendslist() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM connections WHERE connection_type='friendslist' AND connection_id=:connection_id LIMIT 1");
    $stmt->execute(array(':connection_id' => $this->where_array['connection_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_OBJ); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Removed a friendslist item";
    $this->notification_msg = "removed a friendslist item";
    $this->return_msg = "Friendslist item removed";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_delete("connections", "connection_id", $this->where_array['connection_id'], " LIMIT 1")) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['friendslist']['plugin_id'],$this->where_array['connection_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}

}
?>
