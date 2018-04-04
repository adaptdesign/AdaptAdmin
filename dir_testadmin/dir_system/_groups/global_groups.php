<?php

class global_groups extends _global_groups {
  public $initial_plugins = array("activities","dashboard","friendslist","groupmembers","groups","groupsettings","layout","plugins","members","messages","notifications","clients","tasks");

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function install_plugin($group_id,$member_id) {
		if(!empty($group_id) && !empty($member_id)) {
			$stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='groups'"); $stmt->execute();
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
  public function set_query($prefix="M.",$item="") {
    // ADD DBTYPE FILTERS
    $return = $this->_set_filters_dbtype($prefix,$item); $add_sql = $return[0]; $add_arg = $return[1];
    if(!empty($_POST["update_date"])) {
      $add_sql .= "AND (".$prefix."created_date > ? OR ".$prefix."edit_date > ?) ";
      $add_arg[] = e_a($_POST,"update_date",1); $add_arg[] = e_a($_POST,"update_date",1);
    }
    if($this->link_plugin_id == $this->plugins['members']['plugin_id'] && is_numeric($this->link_content_id)) {
      $add_sql .= "AND C.member_id=? "; $add_arg[] = $this->link_content_id;
    }
    if($this->link_plugin_id == $this->plugins['members']['plugin_id'] && $this->link_content_id == $this->member_data['member_id']) {
      $add_sql .= "AND C.approval IN ('1','2') AND M.status IN ('1','2') AND M.visible IN ('1','2') ";
    } else {
      $add_sql .= "AND C.approval IN ('2') AND M.status='2' AND M.visible='2' AND M.level='2' ";
    }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // START QUERY
    $sql = "
    SELECT M.*, C.approval
    FROM groups M
    LEFT JOIN connections C ON (M.group_id = C.group_id)
    WHERE C.connection_type=? ";
    $arg[] = "groupmember";
    // COMBINE MAIN AND ADDON SQLS
    $sql .= $add_sql; $arg = array_merge($arg, $add_arg);
    // GET / SET PER PAGE
    if(!empty($_POST["perpage"])) { $per_page = filter_var($_POST["perpage"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); } else { $per_page = 20; }
    if(!empty($_POST["page"])) { $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); } else { $page_number = 1; }
    if(!empty($_POST["perpage"]) && !empty($_POST["page"])) { $perpage = "LIMIT ".($page_number-1) * $per_page.", ".$per_page." "; } else { $perpage = ""; }
    if(!empty($_POST["update_date"])) { $perpage = "LIMIT 500"; }
    // COMPLETE SQL
    $sql .= " ORDER BY approval ASC, level ASC, created_date DESC ".$perpage;
    return array($sql,$arg);
	}
  //=========================================
  public function set_content_data($prefix="",$item="") {
    // GET QUERY
    $return = $this->set_query("M.",$item); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // START QUERY
    //echo disarray($sql,$arg);
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
