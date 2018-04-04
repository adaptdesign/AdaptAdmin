<?php
/*

*/
class _global {
	public $pdo = null;
  public $member_data = array();
	public $plugins = array();
	public $input_array = array();
	public $where_array = array();
	public $ses_page = null;
	public $ses_params = null;
	public $member_fields = array("member_id","status","member_level","approval","member_hash","facebook_id","stripe_id","group_id","firstname","lastname","email","profile_img","profile","time_zone","count_mes","count_not","last_active","edit_date","created_date");

	//=========================================
  public function __construct(){
		if(!$this->_db_connect()) { }
    if(!$this->_setmemberdata()) { unset($_SESSION['sessiontoken']); unset($_SESSION['myloggedin']); }
		if(!$this->_setplugins()) { }
		if(!$this->_setpageparams()) { }
		if(!$this->_postFormat()) { }

  }
	//=========================================
	private function _db_connect(){
    try {
      $pdo = new PDO('mysql:host='.db_host.';dbname='.db_dbname, db_username, db_password);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); //ERRMODE_SILENT ERRMODE_WARNING ERRMODE_EXCEPTION
      $this->pdo = $pdo;
      return true;
		} catch (PDOException $e) {
			echo "Error: [".basename(__FILE__)."] [".__LINE__."]"." SQL ERROR: ".$e; return;
			return false;
		}
  }
	//=========================================
	public function p_session_var(){
		// CHECK FOR ERRORS
    if(empty($_POST)) { echo json_encode(array('status'=>'error','message'=>'POST error','data'=>'','js'=>'')); return; }
		foreach($_POST as $key=>$value){ $_SESSION[clean($key)] = clean($value); }
    echo json_encode(array('status'=>'success','message'=>'','data'=>'','js'=>''));
  }
	//=========================================
	public function p_nav_hash(){
		// CHECK FOR ERRORS
    if(empty($_POST['nav']) || empty($_POST['item'])) { echo json_encode(array('status'=>'error','message'=>'POST error','data'=>'','js'=>'')); return; }
		$_SESSION['nav_hash'][clean($_POST['nav'])] = clean($_POST['item']);
    echo json_encode(array('status'=>'success','message'=>'','data'=>'','js'=>''));
  }
	//=========================================
	public function p_nav_hash_get(){
		if(!empty($_SESSION['nav_hash'])) { $results = $_SESSION['nav_hash']; } else { $results = array(); }
    echo json_encode(array('status'=>'success','message'=>'','data'=>json_encode($results),'js'=>''));
  }
	//=========================================
	private function _setmemberdata() {
		if(isset($_SESSION['myloggedin']) && isset($_SESSION['sessiontoken'])) {
			try {
				$stmt = $this->pdo->prepare("SELECT ".implode(", ",$this->member_fields)." FROM members WHERE sessiontoken=:sessiontoken LIMIT 1");
				$stmt->execute(array(':sessiontoken' => $_SESSION['sessiontoken']));
				if($stmt->rowCount() == 1) {
					$this->member_data = $stmt->fetch(PDO::FETCH_ASSOC);
					$this->pdo->exec("UPDATE members SET last_active=UTC_TIMESTAMP() WHERE member_id='".$this->member_data['member_id']."' ");

					// SET GROUP DATA
					$group_data_sql = "SELECT
					GM.connection_id AS connection_id,
			    GM.level AS group_level,
					G.group_hash,
					G.group_logo,
					G.group_id AS group_id,
					G.title AS group_name,
					G.level AS actual_group_level
					FROM connections GM
					LEFT JOIN groups G ON (GM.group_id = G.group_id)";

					$stmt = $this->pdo->prepare($group_data_sql." WHERE GM.connection_type='groupmember' AND GM.group_id='".$this->member_data['group_id']."' AND GM.member_id='".$this->member_data['member_id']."' AND GM.approval='2' AND GM.status='2' AND G.status='2'"); $stmt->execute();
					if($stmt->rowCount() == 1) {
						$group_data = $this->_format_row_global($stmt->fetch(PDO::FETCH_ASSOC));
					} else {
						$stmt = $this->pdo->prepare($group_data_sql." WHERE GM.connection_type='groupmember' AND GM.member_id='".$this->member_data['member_id']."' AND GM.level='10' AND G.level='10' AND G.member_id='".$this->member_data['member_id']."'"); $stmt->execute();
						if($stmt->rowCount() == 1) {
							$group_data = $this->_format_row_global($stmt->fetch(PDO::FETCH_ASSOC));
							$this->pdo->exec("UPDATE members SET group_id='".$group_data['group_id']."' WHERE member_id='".$this->member_data['member_id']."' ");
							$this->member_data['group_id'] = $group_data['group_id'];
							$_SESSION['alertmessage'] = "Access to your current group is disabled.<br> Group has been changed to your private group.";
						}
					}
					// ERROR CHECK
					if(empty($group_data)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
					foreach($group_data as $name=>$value) { if(!empty($name)) { $this->member_data[$name] = $value; } }
					if($group_data['actual_group_level'] != "10") { $this->member_data['group_status'] = "2"; } else { $this->member_data['group_status'] = "1"; }

					return true;
				} else {
					return false;
					//echo "failed to find row";
				}
			} catch (PDOException $e) {
				return false;
				//return "failed ".$e;
			}
		} else {
			return false;
			//return "failed session";
		}
	}
	//=========================================
	private function _setplugins() {
		if(isset($_SESSION['myloggedin']) && isset($_SESSION['sessiontoken'])) {
			$stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE status='2' AND system_plugin='2' ORDER BY order_id ASC, created_date DESC");
			$stmt->execute();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$this->plugins[$row['title']] = $row;
			}

			$sql = "SELECT P.* FROM connections GP LEFT JOIN plugins P ON (GP.plugin_id = P.plugin_id)
			WHERE GP.status='2' AND P.status='2' AND GP.connection_type='groupplugin' AND P.system_plugin='1' AND GP.group_id=?
			ORDER BY GP.order_id ASC, P.order_id ASC, GP.created_date DESC";
			$stmt = $this->pdo->prepare($sql); $stmt->execute(array($this->member_data['group_id']));
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$this->plugins[$row['title']] = $row;
			}
		}
	}
	//=========================================
	private function _setpageparams(){
		$ses_parts = explode("/", ltrim(str_replace("/testadmin/","",$_SERVER['REQUEST_URI']), '/'));
		$ses_page = ""; $ses_params = "";
		if(!empty($ses_parts)) {
			$ipage= 0;
			foreach($ses_parts as $ses_part) { if(!empty($ses_part)){ if($ipage == 0){ $ses_page = $ses_part; }else{ $ses_params .= $ses_part."/"; } } $ipage++; }
		}
		if(!empty($ses_page)) { $this->ses_page = $ses_page; }
		if(!empty($ses_params)) { $this->ses_params = rtrim(preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', $ses_params), "/"); }
  }
	//=========================================
	private function _postFormat() {
		if($_POST) {
			unset($this->input_array);
			foreach($_POST as $key => $value) {
				if(decode($key) == "inputs") { foreach($value as $key => $value) { $this->input_array[decode($key)] = $value; } }
				elseif(decode($key) == "inputse") { foreach($value as $key => $value) { $this->input_array[decode($key)] = decode($value); } }
				elseif(decode($key) == "wheresn") { foreach($value as $key => $value) { $this->where_array[decode($key)] = $value; } }
				elseif(decode($key) == "wheres") { foreach($value as $key => $value) { $this->where_array[decode($key)] = decode($value); } }
				elseif(decode($key) == "linked_to") { foreach($value as $key => $value) { $this->input_array["linked_to"] = e_a($this->input_array,"linked_to").strtr(decode($value), array('|' => ''))."|"; } }
				elseif($key == "inputs") { foreach($value as $key => $value) { $this->input_array[$key] = $value; } }
				elseif($key == "inputse") { foreach($value as $key => $value) { $this->input_array[$key] = decode($value); } }
				elseif($key == "wheresn") { foreach($value as $key => $value) { $this->where_array[$key] = $value; } }
				elseif($key == "wheres") { foreach($value as $key => $value) { $this->where_array[$key] = decode($value); } }
				elseif($key == "linked_to") { foreach($value as $key => $value) { $this->input_array["linked_to"] .= strtr(decode($value), array('|' => ''))."|"; } }
			}
			if(!empty($this->input_array["linked_to"])){ $this->input_array["linked_to"] = rtrim($this->input_array["linked_to"], '|'); }
		}
	}
	//=========================================
	public function _format_date($date) {
    if(empty($date) || empty($this->member_data['time_zone'])) { return; }
    $temp_date = new DateTime($date ." UTC");
    $temp_date->setTimezone(new DateTimeZone(timezone_name_from_abbr("", $this->member_data['time_zone']*60, false)));
    $date = date_format($temp_date, 'Y-m-d H:i:s');
    return $date;
  }
	//=========================================
	public function _add_insert($options_table, &$input_array) {
		try {
			$nameArray = array(); $valueArray = array();
			foreach ($input_array as $name => $value) {
				array_push($nameArray, $name);
				array_push($valueArray, ":".$name);
			}
			$names = implode(', ', $nameArray); $values = implode(', ', $valueArray);
			$stmt = $this->pdo->prepare("INSERT INTO $options_table (".$names.", created_date) VALUES (".$values.", UTC_TIMESTAMP())");

			foreach ($input_array as $name => $value) {
				//if(!in_array(strtolower($name), $this->allow_html)){ $value = htmlspecialchars($value); }
				$stmt->bindValue($name, $value);
			}
			$stmt->execute();
			if ($stmt->rowCount() == 1) {
				return true;
			} else {
				$this->add_error($this->member_data['member_id'],"Error: [".basename(__FILE__)."] [".__LINE__."]");
				return false;
			}
		} catch (PDOException $e) {
			$this->add_error($this->member_data['member_id'],"Error: [".basename(__FILE__)."] [".__LINE__."]");
			echo $e;
		}
	}
	//=========================================
	public function _add_update($options_table, &$input_array, &$where_array) {
		try {
			$nameArray = array(); $whereArray = array();
			foreach ($input_array as $name => $value) {
				array_push($nameArray, $name."=:".$name);
			}
			foreach ($where_array as $name => $value) {
				array_push($whereArray, $name."=:".$name);
			}
			$names = implode(', ', $nameArray); $where = implode(' AND ', $whereArray);
			$stmt = $this->pdo->prepare("UPDATE $options_table SET ".$names.", edit_date=UTC_TIMESTAMP() WHERE ".$where." LIMIT 1");
			foreach ($input_array as $name => $value) {
				//if(!in_array(strtolower($name), $this->allow_html)){ $value = htmlspecialchars($value); }
				$stmt->bindValue($name, $value);
			}
			foreach ($where_array as $name => $value) {
				$stmt->bindValue($name, $value);
			}
			$stmt->execute();
			if ($stmt->rowCount() == 1) {
				return true;
			} else {
				$this->add_error($this->member_data['member_id'],"Error: [".basename(__FILE__)."] [".__LINE__."]");
				return false;
			}
		} catch (PDOException $e) {
			$this->add_error($this->member_data['member_id'],"Error: [".basename(__FILE__)."] [".__LINE__."]");
			return false;
		}
	}
	//=========================================
	public function _add_delete($options_table, $where_name, $where_value, $limit = "") {
		try {
			$stmt = $this->pdo->prepare("SELECT * FROM $options_table WHERE ".$where_name."=:".$where_name." LIMIT 1");
			$stmt->bindValue($where_name, $where_value);
			$stmt->execute();
			if ($stmt->rowCount() == 1) {
				$stmt = $this->pdo->prepare("DELETE FROM $options_table WHERE ".$where_name."=:".$where_name." $limit");
				$stmt->bindValue($where_name, $where_value);
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					return true;
				} else {
					$this->add_error($this->member_data['member_id'],"Error: [".basename(__FILE__)."] [".__LINE__."]");
					return false;
				}
			} else {
				return true; // No item to delete
			}
		} catch (PDOException $e) {
			$this->add_error($this->member_data['member_id'],"Error: [".basename(__FILE__)."] [".__LINE__."]");
			return false;
		}
	}
	//=========================================
	public function _add_activity($notify=false,$to_array="",$plugin_id="",$content_id="",$linked_to="",$a_msg="",$n_msg="",$link="",$replace=false,$level="1",$from_member_id="",$group_id="") {
		if(empty($from_member_id)) { $from_member_id = $this->member_data['member_id']; }
		if(empty($group_id)) { $group_id = $this->member_data['group_id']; }

		if(!empty($to_array)) { $to_array = array_unique($to_array); }
		if(empty($from_member_id) || empty($group_id)) { return array("bad","Error: [".basename(__FILE__)."] [".__LINE__."]");	}

		$return = "good";
		if(!empty($level)) { $input_array['level'] = $level; }
		if(!empty($group_id)) { $input_array['group_id'] = $group_id; }
		if(!empty($from_member_id)) { $input_array['member_id'] = $from_member_id; }
		if(!empty($plugin_id)) { $input_array['plugin_id'] = $plugin_id; }
		if(!empty($content_id)) { $input_array['content_id'] = $content_id; }
		if(!empty($linked_to)) { $input_array['linked_to'] = $linked_to; }
		if(!empty($a_msg)) { $input_array['message'] = $a_msg; }
		if(!empty($link)) { $input_array['link'] = $link; }
		if(!$this->_add_insert("activities", $input_array)) { $return = "Error: [".basename(__FILE__)."] [".__LINE__."]"; }

		if($notify == true && !empty($to_array)) {
			foreach($to_array as $to_member_id) {
				$to_member_id = strtr($to_member_id, array('-' => ''));
				if ($to_member_id == $this->member_data['member_id'] || empty($to_member_id)) { continue; }
				if($replace == true && !empty($plugin_id) && !empty($content_id) && !empty($n_msg)) {
					$filters = "";
					if(!empty($link)) { $filters .= "AND link='".$link."' "; }
					if(!empty($parent_id)) { $filters .= "AND parent_id='".$parent_id."' "; }
					$stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE viewed='1' AND to_member_id=:to_member_id AND group_id=:group_id AND plugin_id=:plugin_id AND content_id=:content_id AND message=:message LIMIT 1");
					$stmt->execute(array(':to_member_id' => $to_member_id, ':group_id' => $group_id, ':plugin_id' => $plugin_id, ':content_id' => $content_id, ':message' => $n_msg));
					if ($stmt->rowCount() == 1) {
						$stmt_data = $stmt->fetch(PDO::FETCH_OBJ);
						$stmt = $this->pdo->prepare("UPDATE notifications SET created_date=UTC_TIMESTAMP() WHERE notification_id='".$stmt_data->notification_id."' LIMIT 1");
						if($stmt->execute()) {
							continue;
						} else {
							$return = "Error: [".basename(__FILE__)."] [".__LINE__."]";
							continue;
						}
					}
				}
				$stmt = $this->pdo->prepare("INSERT INTO notifications (level, group_id, from_member_id, to_member_id, link, plugin_id, content_id, parent_id, message, created_date)
				VALUES(:level, :group_id, :from_member_id, :to_member_id, :link, :plugin_id, :content_id, :parent_id, :message, UTC_TIMESTAMP())");
				$stmt->execute(array(':level' => $level, ':group_id' => $group_id, ':from_member_id' => $from_member_id, ':to_member_id' => $to_member_id, ':link' => $link, ':plugin_id' => $plugin_id, ':content_id' => $content_id, ':parent_id' => $parent_id, ':message' => $n_msg));
				if ($stmt->rowCount() == 1) {
					continue;
				} else {
					$return = "Error: [".basename(__FILE__)."] [".__LINE__."]";
					continue;
				}
			}
		}
		if($return == "good") { return array("good",""); }
		else { return array("bad",$return); }
	}
  //=========================================
  public function _set_filters_global($prefix="") {
    $sql = "";
    $arg = array();
    return array($sql,$arg);
  }
  //=========================================
  public function _format_row_global($row) {
    if(empty($row)) { return; }
    if(!empty($row['last_active'])) { $row['last_active'] = $this->_format_date($row['last_active']); }
    if(!empty($row['due_date'])) { $row['due_date'] = $this->_format_date($row['due_date']); }
    if(!empty($row['complete_date'])) { $row['complete_date'] = $this->_format_date($row['complete_date']); }
    if(!empty($row['edit_date'])) { $row['edit_date'] = $this->_format_date($row['edit_date']); }
    if(!empty($row['created_date'])) { $row['created_date'] = $this->_format_date($row['created_date']); }
    if(!empty($row['other'])) { $pts=explode("-||-",$row['other']); foreach($pts as $pt){ $otr=explode("-|-",$pt); if(empty($row[$otr[0]])) { $row[$otr[0]]=isset($otr[1]) ? $otr[1] : null; } } }
    return $row;
  }


}
?>
