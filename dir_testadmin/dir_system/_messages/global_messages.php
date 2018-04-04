<?php

class global_messages extends _global_messages {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function install_plugin($group_id,$member_id) {
		if(!empty($group_id) && !empty($member_id)) {
			$stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='messages'"); $stmt->execute();
			if ($stmt->rowCount() == 1) {
				$plugin_data = $stmt->fetch(PDO::FETCH_OBJ);

				$stmt = $this->pdo->prepare("SELECT * FROM layout WHERE layout_type='widg_menu' AND group_id='".$group_id."' AND plugin_id='".$plugin_data->plugin_id."'"); $stmt->execute();
				if ($stmt->rowCount() == 0) {
					$input_array['layout_type'] = "widg_menu";
					$input_array['member_id'] = $member_id;
					$input_array['group_id'] = $group_id;
					$input_array['plugin_id'] = $plugin_data->plugin_id;
					$input_array['title'] = $plugin_data->title;
					if(!$this->_add_insert("layout", $input_array)) { return "Error: [".basename(__FILE__)."] [".__LINE__."]"; }
				}
			} else { return "Error: [".basename(__FILE__)."] [".__LINE__."]"; }
		} else { return "Error: [".basename(__FILE__)."] [".__LINE__."]"; }
		return "good";
	}

	//=========================================
  public function set_query($prefix="M.",$item="",$type="") {
    // ADD DBTYPE FILTERS
    $return = $this->_set_filters_dbtype($prefix,$item); $add_sql = $return[0]; $add_arg = $return[1];
    if(!empty($_POST["update_date"])) {
      $add_sql .= "AND (".$prefix."created_date > ? OR ".$prefix."edit_date > ?) ";
      $add_arg[] = e_a($_POST,"update_date",1); $add_arg[] = e_a($_POST,"update_date",1);
    }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // START QUERY
    if($type == "conversations") {
      $sql = "
      SELECT M.status, M.level, M.message_id, M.member_id, M.to_member_id, M.message, U.firstname, U.lastname, U.profile_img, U.member_hash, U.last_active, M.created_date
      FROM messages M
      LEFT JOIN messages M2 on M.member_id = M2.member_id AND M.message_id < M2.message_id
      JOIN members U on M.member_id = U.member_id
      WHERE U.status=? AND M.status=? AND M2.member_id is null AND  M.message_type=? ";
      $arg[] = "2"; $arg[] = "2"; $add_arg[] = "private";
    } elseif($type == "private" && !empty(e_a($_SESSION,'chat_id',1))) {
      $sql = "
      SELECT M.status, M.level, M.message_id, M.member_id, M.to_member_id, M.message, U.firstname, U.lastname, U.profile_img, U.member_hash, U.last_active, M.created_date
      FROM messages M
      LEFT JOIN members U ON (M.member_id = U.member_id)
      WHERE M.status=? AND M.message_type=? AND ((M.member_id=? AND M.to_member_id=?) OR (M.member_id=? AND M.to_member_id=?)) ";
      $arg[] = "2"; $arg[] = "private"; $arg[] = e_a($_SESSION,'chat_id',1); $arg[] = $this->member_data['member_id']; $arg[] = $this->member_data['member_id']; $arg[] = e_a($_SESSION,'chat_id',1);
    } elseif($type == "group") {
      $sql = "
      SELECT M.status, M.level, M.message_id, M.member_id, M.to_member_id, M.message, U.firstname, U.lastname, U.profile_img, U.member_hash, U.last_active, M.created_date
      FROM messages M
      LEFT JOIN members U ON (M.member_id = U.member_id)
      WHERE M.status=? AND M.message_type=? AND M.group_id=? ";
      $arg[] = "2"; $arg[] = "groupchat"; $arg[] = $this->member_data['group_id'];
    } else { return; }
    // COMBINE MAIN AND ADDON SQLS
    $sql .= $add_sql; $arg = array_merge($arg, $add_arg);
    // GET / SET PER PAGE
    if(!empty($_POST["perpage"])) { $per_page = filter_var($_POST["perpage"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); } else { $per_page = 20; }
    if(!empty($_POST["page"])) { $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); } else { $page_number = 1; }
    if(!empty($_POST["perpage"]) && !empty($_POST["page"])) { $perpage = "LIMIT ".($page_number-1) * $per_page.", ".$per_page." "; } else { $perpage = ""; }
    if(!empty($_POST["update_date"])) { $perpage = "LIMIT 500"; }
    // COMPLETE SQL
    $sql .= " ORDER BY level ASC, created_date DESC ".$perpage;
    return array($sql,$arg);
	}
  //=========================================
  public function set_content_data($prefix="",$item="") {
    // GET QUERY
    $return = $this->set_query($prefix,$item); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // START QUERY
    $stmt = $this->pdo->prepare($sql);
    if($stmt->execute($arg)) {
      while($row = $this->_format_row_dbtype($stmt->fetch(PDO::FETCH_ASSOC))){
        $this->page_title = $row['title'];
        $this->content_data = $row;
      }
    }
	}

}
?>
