<?php

class a_groups extends global_groups {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_group($input_array="") {
    // CHECK FOR INITIAL GROUP
    if(!empty($input_array)) {
      $stmt = $this->pdo->prepare("SELECT * FROM groups WHERE level='10' AND member_id=:member_id");
      $stmt->execute(array(':member_id' => $input_array['member_id']));
      if($stmt->rowCount() > 0) { return; }
      $this->input_array = $input_array;
    }
    // SET GROUP HASH
    for($i=0;$i<20;$i++) {
      $rand_num = uniqidReal(13,0);
      $sql = $this->pdo->query("SELECT * FROM groups WHERE group_hash='".$rand_num."'");
      if($sql->rowCount() == 0) { break; }
    }
    $this->input_array['group_hash'] = $rand_num;
    if(empty($this->input_array['member_id'])) { $this->input_array['member_id'] = $this->member_data['member_id']; }
    // SET MESSAGES
    $this->required = array("title","member_id","group_hash");
    $this->activity_msg = "Created a new group";
    $this->notification_msg = "Created a new group";
    $this->return_msg = "Group created";
    // CHECK REQUIRED
    if(!empty($this->required)){ foreach($this->required as $key) { if(empty($this->input_array[$key])){ $this->fail_page[] = "Error: ".$key." required"; } } }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // ADD CONTENT
    if(!$this->_add_insert("groups", $this->input_array)) { $this->fail_page[] = "Error processing request"; }
    $group_id = $this->pdo->lastInsertId();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // CREATE GROUPMEMBER
    $install_array['connection_type'] = "groupmember";
    $install_array['level'] = "10";
    $install_array['member_id'] = $this->input_array['member_id'];
    $install_array['group_id'] = $group_id;
    if(!$this->_add_insert("connections", $install_array)) { $this->fail_page[] = "Error processing request"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET INITAL PLUGINS
    if(!empty($this->initial_plugins)) {
      foreach($this->initial_plugins as $plugin_name) {
        if(!class_exists($plugin_name)) {
          require_all(DOC_ADDONS."/dir_addons/".$plugin_name);
        }
        $temp_name = "a_".$plugin_name;
        $temp_class = new $temp_name();
        $return = $temp_class->install_plugin($install_array['group_id'],$install_array['member_id']);
        if($return != "good") { $this->fail_page[] = $return; }
      }
    }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET ACTIVITY
    $return = $this->_add_activity(false,"",$this->plugins['groups']['plugin_id'],$group_id,"",$this->activity_msg);
    // RETURN IF REGISTERING MEMBER
    if(!empty($input_array)) { return $group_id; }
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================


}
?>
