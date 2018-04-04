<?php

class a_groupmembers extends global_groupmembers {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_groupmember() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    // FORMAT INPUT_ARRAY
    if(empty($this->input_array['approval'])) { $this->input_array['approval'] = "1"; }
    if(empty($this->input_array['connection_type'])) { $this->input_array['connection_type'] = "groupmember"; }
    if(empty($this->input_array['member_id'])) { $this->input_array['member_id'] = $this->member_data['member_id']; }
    // SET MESSAGES
    $this->required = array("approval","connection_type","member_id","group_id");
    $this->activity_msg = "Requested to join a group";
    $this->notification_msg = "requested to join the group";
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
    $return = $this->_add_activity(false,"",$this->plugins['groupmembers']['plugin_id'],$this->member_data['group_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
  //=========================================
  public function p_edit_groupmember() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM connections WHERE connection_type='groupmember' AND connection_id=:connection_id LIMIT 1");
    $stmt->execute(array(':connection_id' => $this->where_array['connection_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Updated a groupmember";
    $this->notification_msg = "Updated a groupmember";
    $this->return_msg = "Groupmember updated";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_update("connections", $this->input_array, $this->where_array)) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupmembers']['plugin_id'],$this->where_array['connection_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
  }
  //=========================================
  public function p_delete_groupmember() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    // GET ITEM DATA
    $stmt = $this->pdo->prepare("SELECT * FROM connections WHERE connection_type='groupmember' AND connection_id=:connection_id LIMIT 1");
    $stmt->execute(array(':connection_id' => $this->where_array['connection_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { $this->fail_page[] = "Content not found"; }
    // SET MESSAGES
    $this->required = array();
    $this->activity_msg = "Removed a groupmember";
    $this->notification_msg = "removed a groupmember";
    $this->return_msg = "Groupmember removed";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_delete("connections", "connection_id", $this->where_array['connection_id'], " LIMIT 1")) { $this->fail_page[] = "Error processing insert"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SEND ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groupmembers']['plugin_id'],$this->where_array['connection_id'],"",$this->activity_msg);
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}

}
?>
