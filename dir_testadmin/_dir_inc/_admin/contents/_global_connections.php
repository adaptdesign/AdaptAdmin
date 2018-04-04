<?php

class _global_connections extends _global_admin {
  public $dbtype_inputs = array("connection_id","status","level","order_id","member_id","from_member_id","to_member_id","group_id","plugin_id","approval","edit_date","created_date","connection_type","other");

  public function __construct(){
		parent::__construct();

    if(!$this->_post_format_dbtype()) { }
  }
  //=========================================
  public function _set_filters_dbtype($prefix="",$item="") {
    // ADD _global_display FILTERS
    $return = $this->_set_filters_global($prefix); $sql = $return[0]; $arg = $return[1];
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { return; } //==============
    // IF SINGLE ITEM
    if(!empty($item) && is_numeric($item)){ $sql .= "AND ".$prefix."connection_id=? "; $arg[] = $item; }
    // RETURN ARRAY
		return array($sql,$arg);
	}
  //=========================================
  public function _format_row_dbtype($row) {
    if(empty($row)) { return; }
    // FORMAT GLOBAL
    $row = $this->_format_row_global($row);
    // START FORMAT
    return $row;
  }
  //=========================================
	private function _post_format_dbtype() {
    if(!empty($this->input_array)) {
      foreach($this->input_array as $key => $value) {
  			if(!in_array($key, $this->dbtype_inputs)) {
  				$this->input_array["other"] = e_a($this->input_array,"other").strtr($key, array('|' => ''))."-|-".strtr($value, array('|' => ''))."-||-";
  				unset($this->input_array[$key]);
  			}
  		}
  		if(!empty($this->input_array["other"])) { $this->input_array["other"] = rtrim($this->input_array["other"], '||'); }
    }
	}

}
?>
