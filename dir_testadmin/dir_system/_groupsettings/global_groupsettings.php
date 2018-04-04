<?php

class global_groupsettings extends _display_groups {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
	//=========================================
  public function set_query($item="",$prefix="") {
    // START QUERY
    $sql = "SELECT * FROM groups WHERE group_id=? ";
    $arg[] = $this->member_data['group_id'];
    // ADD DBTYPE FILTERS
    $return = $this->_set_filters_dbtype($prefix,$item); if($return[0] != "good") { echo $return[1]; return; }
    $sql .= $return[1]; $arg = array_merge($arg, $return[2]);
    // GET / SET PER PAGE
    if(!empty($_POST["perpage"])) { $per_page = filter_var($_POST["perpage"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); } else { $per_page = 20; }
    if(!empty($_POST["page"])) { $page_number = filter_var($_POST["page"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); } else { $page_number = 1; }
    if(!empty($_POST["perpage"]) && !empty($_POST["page"])) { $perpage = "LIMIT ".($page_number-1) * $per_page.", ".$per_page." "; } else { $perpage = "";}
    // COMPLETE SQL
    $sql .= " ORDER BY level ASC, created_date DESC ".$perpage;
    //disarray($sql,$arg);
    return array("good",$sql,$arg);
	}
  //=========================================
  public function set_content_data($item) {
    // GET QUERY
    $return = $this->set_query($item); if($return[0] != "good") { echo "error"; return; }
    $sql = $return[1]; $arg = $return[2];
    $stmt = $this->pdo->prepare($sql);
    if($stmt->execute($arg)) {
      while($row = $this->_format_row_dbtype($stmt->fetch(PDO::FETCH_ASSOC))){
        //$this->page_title = $row['title'];
        $this->content_data = $row;
      }
    }
	}

}
?>
