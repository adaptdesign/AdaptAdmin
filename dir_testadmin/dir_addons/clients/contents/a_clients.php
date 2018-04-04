<?php

class a_clients extends global_clients {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_client() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET MESSAGES
    $this->required = array("plugin_id","member_id","group_id","title");
    $this->activity_msg = "added a new client";
    $this->notification_msg = "assigned you to a client";
    $this->return_msg = "Client created";
    // ADD CONTENT
    $content_data = $this->_add_content();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_edit_client() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET MESSAGES
    $this->required = array("title");
    $this->activity_msg = "updated client details";
    $this->notification_msg = "updated a client you are assigned to";
    $this->return_msg = "Client updated";
    // UPDATE CONTENT
    $content_data = $this->_update_content();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_delete_client() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET MESSAGES
    $this->required = array("title");
    $this->activity_msg = "deleted a client";
    $this->notification_msg = "deleted a client you where assigned to";
    $this->return_msg = "Client deleted";
    // DELETE CONTENT
    $content_data = $this->_delete_content();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
  //=========================================

}
?>
