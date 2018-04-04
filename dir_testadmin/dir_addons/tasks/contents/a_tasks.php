<?php

class a_tasks extends global_tasks {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_task() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET MESSAGES
    $this->required = array("plugin_id","member_id","group_id","title","due_date");
    $this->activity_msg = "added a new task";
    $this->notification_msg = "assigned you to a task";
    $this->return_msg = "Task created";
    // ADD CONTENT
    $content_data = $this->_add_content();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // RETURN SUCCESS
    echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));
	}
	//=========================================
	public function p_edit_task() {
    if(!empty($_POST['item_id'])) {
      $this->where_array['content_id'] = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', decode($_POST['item_id']));
      $this->input_array['level'] = preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', $_POST['t_level']);
			if($this->input_array['level'] == '3') { $this->input_array["complete_date"] = gmdate("Y-m-d H:i:s"); }
      $stmt = $this->pdo->prepare("SELECT * FROM content WHERE content_id=:content_id LIMIT 1"); $stmt->execute(array(':content_id' => $this->where_array['content_id']));
			if($stmt->rowCount() == 1) { $stmt_data = $this->_format_row_global($stmt->fetch(PDO::FETCH_ASSOC)); } else {$fail_page .= "Error: content_id not found"; }
      $this->required = array("level");
    } else {
      $this->required = array("title","due_date");
    }
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET MESSAGES
    $this->activity_msg = "updated task details";
    $this->notification_msg = "updated a task you are assigned to";
    $this->return_msg = "Task updated";
    // UPDATE CONTENT
    $content_data = $this->_update_content();
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // RETURN SUCCESS
    //echo json_encode(array('status'=>'success','message'=>$this->return_msg,'data'=>'','js'=>''));

    if(!empty($_POST['item_id'])) {
      if($this->input_array["level"] != '3') {
    		$return_html = 'Due: <span title="'.date("F j, Y, g:i a", strtotime($stmt_data["due_date"])).'">'.$this->_time_diff($stmt_data["due_date"]).'</span>';
		  } else {
		  	$return_html = 'Completed: <span class="text-success" title="'.date("F j, Y, g:i a", strtotime($this->input_array["complete_date"])).'">'.date("F-j-Y", strtotime($this->input_array["complete_date"])).'</span>';
		  }
      echo "true|".$return_html."| ".$this->return_msg;
    } else {
      $_SESSION['alertmessage'] = $this->return_msg;
      echo "true| ".$this->return_msg;
    }
	}
	//=========================================
	public function p_delete_task() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "Session has expired. Please <a href='".URL_PATH."'>log in</a>"; }
    if(!$_POST){ $this->fail_page[] = "Post error occurred"; }
    if(empty($this->widget_data)) { $this->fail_page[] = "Unable to load widget data"; }
		if(empty($this->link)) { $this->fail_page[] = "Unable to find link"; }
		if($this->member_data['group_level'] < $this->widget_data['level']) { $this->fail_page[] = "Access to this content is restricted"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { output_error($this->fail_page,1); return; } //==============
    // SET MESSAGES
    $this->activity_msg = "deleted a task";
    $this->notification_msg = "deleted a task you where assigned to";
    $this->return_msg = "Task deleted";
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
