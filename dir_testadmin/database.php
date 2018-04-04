<?php
/*
 * METHOD LIST FOR database
 *
 * __construct
 * db_update_table
 *
 */
$database = new database();
class database extends _global {
  private $create_db = true;

  public function __construct(){
		parent::__construct();
		if($this->create_db) { $this->create_table(); }
  }
  //===========================================================================================
	private function db_update_table($dbupdate_name='', &$dbupdate_rows='', $dbupdate_primary_key='', &$dbupdate_foreign_keys='') {
    $stmt = $this->pdo->prepare("SHOW TABLES LIKE '$dbupdate_name'");
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
      foreach($dbupdate_rows as $name1 => $value1) {
        try {
          $stmt = $this->pdo->exec('
          SET @preparedStatement = (SELECT IF(
            (SELECT COUNT(*)
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE  table_name = "'.$dbupdate_name.'"
                AND table_schema = DATABASE()
                AND column_name = "'.$name1.'"
            ) > 0,
            "SELECT 1",
            "ALTER TABLE '.$dbupdate_name.' ADD '.$name1.' '.$value1.';"
          ));
          PREPARE alterIfNotExists FROM @preparedStatement;
          EXECUTE alterIfNotExists;
          DEALLOCATE PREPARE alterIfNotExists;
          ');
        } catch(PDOException $e) {
          echo $e;
        }
      }

      $sql2 = "SHOW COLUMNS FROM ".$dbupdate_name;
      $rs2 = $this->pdo->query($sql2);
      while($rowm = $rs2->fetchAll(PDO::FETCH_COLUMN)) {
        foreach ($rowm as $r) {
          if(!array_key_exists($r, $dbupdate_rows)){
            //echo "(<strong>".$dbupdate_name." > ".$r."</strong>) exists in table but not blueprints.<br>";
          }
        }
      }
    } else {
      if(empty($dbupdate_name) || empty($dbupdate_primary_key)) { return false; exit; }
      try {
        $sql = "CREATE TABLE ".$dbupdate_name." ( ";
        if(!empty($dbupdate_rows)) { foreach($dbupdate_rows as $name2 => $value2) { $sql = $sql.$name2." ".$value2.", "; } }
        $sql = $sql."PRIMARY KEY (".$dbupdate_primary_key."), ";
        if(!empty($dbupdate_foreign_keys)) { foreach($dbupdate_foreign_keys as $name3 => $value3) { $sql = $sql.$value3.", "; } }
        $sql = rtrim($sql, ', ');
        $sql = $sql.")ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $stmt = $this->pdo->exec($sql);
      } catch(PDOException $e) {
        echo $e;
      }
    }
  }
	//===========================================================================================

  private function create_table() {
    if($this->create_db) {
      unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      $dbupdate_name = "plugins";
      $dbupdate_rows['plugin_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['order_id'] = "INT(11) DEFAULT NULL";

  		$dbupdate_rows['system_plugin'] = "INT(2) NOT NULL DEFAULT '1'";
  		$dbupdate_rows['has_menuwidget'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['has_dashwidget'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['has_pagewidget'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['has_content'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['has_addoptions'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['font_icon'] = "VARCHAR(25) DEFAULT NULL";
      $dbupdate_rows['img_icon'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['price'] = "VARCHAR(25) NOT NULL DEFAULT '0'";
  		$dbupdate_rows['title'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['description'] = "TEXT DEFAULT NULL";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
      $dbupdate_primary_key = "plugin_id";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
      unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      $dbupdate_name = "members";
      $dbupdate_rows['member_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['member_level'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['approval'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['member_hash'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['facebook_id'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['stripe_id'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['firstname'] = "VARCHAR(50) DEFAULT NULL";
      $dbupdate_rows['lastname'] = "VARCHAR(50) DEFAULT NULL";
      $dbupdate_rows['email'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['password'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['profile'] = "TEXT DEFAULT NULL";
      $dbupdate_rows['profile_img'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['time_zone'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['sessiontoken'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['authcode'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['count_mes'] = "INT(11) NOT NULL DEFAULT '0'";
      $dbupdate_rows['count_not'] = "INT(11) NOT NULL DEFAULT '0'";
      $dbupdate_rows['last_active'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
      $dbupdate_primary_key = "member_id";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      $dbupdate_name = "member_orders";
      $dbupdate_rows['memberorder_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['stripe_id'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['order_id'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['post'] = "TEXT DEFAULT NULL";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
      $dbupdate_primary_key = "memberorder_id";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      //=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      $dbupdate_name = "groups";
  		$dbupdate_rows['group_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['order_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_rows['visible'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['join_allow'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['join_approval'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['title'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['description'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['group_hash'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['group_domain'] = "VARCHAR(100) DEFAULT NULL";
  		$dbupdate_rows['group_logo'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['other'] = "TEXT DEFAULT NULL";
  		$dbupdate_primary_key = "group_id";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      //=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		$dbupdate_name = "backups";
      $dbupdate_rows['backup_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['backup_type'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['title'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['description'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['plugins'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['db_backup'] = "TEXT DEFAULT NULL";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
      $dbupdate_primary_key = "backup_id";
      $dbupdate_foreign_keys[] = "FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE";
  		$this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		$dbupdate_name = "connections";
      $dbupdate_rows['connection_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
      $dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['order_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['from_member_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['to_member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['plugin_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['approval'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_rows['connection_type'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['other'] = "TEXT DEFAULT NULL";
      $dbupdate_primary_key = "connection_id";
      $dbupdate_foreign_keys[] = "FOREIGN KEY (group_id) REFERENCES groups(group_id) ON DELETE CASCADE";
      $dbupdate_foreign_keys[] = "FOREIGN KEY (from_member_id) REFERENCES members(member_id) ON DELETE CASCADE";
      $dbupdate_foreign_keys[] = "FOREIGN KEY (to_member_id) REFERENCES members(member_id) ON DELETE CASCADE";
  		$this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		$dbupdate_name = "layout";
      $dbupdate_rows['layout_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
      $dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['order_id'] = "VARCHAR(255) DEFAULT NULL";
      $dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['plugin_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['parent_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
      $dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_rows['layout_type'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['title'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['other'] = "TEXT DEFAULT NULL";
      $dbupdate_primary_key = "layout_id";
      $dbupdate_foreign_keys[] = "FOREIGN KEY (group_id) REFERENCES groups(group_id) ON DELETE CASCADE";
  		$this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
      unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      $dbupdate_name = "activities";
  		$dbupdate_rows['activity_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
      $dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['plugin_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['content_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['linked_to'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['message'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['link'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_primary_key = "activity_id";
  		$dbupdate_foreign_keys[] = "FOREIGN KEY (group_id) REFERENCES groups(group_id) ON DELETE CASCADE";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		$dbupdate_name = "content";
  		$dbupdate_rows['content_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['order_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['plugin_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['privacy'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['approval'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['priority'] = "INT(2) NOT NULL DEFAULT '1'";
  		$dbupdate_rows['due_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['complete_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_rows['title'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['linked_to'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['message'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['u_name'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['u_link'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['other'] = "TEXT DEFAULT NULL";
  		$dbupdate_primary_key = "content_id";
  		$dbupdate_foreign_keys[] = "FOREIGN KEY (group_id) REFERENCES groups(group_id) ON DELETE CASCADE";
  		$this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
  		unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		$dbupdate_name = "messages";
  		$dbupdate_rows['message_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";

  		$dbupdate_rows['viewed'] = "INT(2) NOT NULL DEFAULT '1'";
  		$dbupdate_rows['message_type'] = "VARCHAR(25) DEFAULT NULL";
  		$dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['to_member_id'] = "INT(11) DEFAULT NULL";
      $dbupdate_rows['linked_to'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['message'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_primary_key = "message_id";
  		$dbupdate_foreign_keys[] = "FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE";
  		$this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		//=============================================================================================
      unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      $dbupdate_name = "notifications";
  		$dbupdate_rows['notification_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
  		$dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
      $dbupdate_rows['level'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['plugin_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['content_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['viewed'] = "INT(2) NOT NULL DEFAULT '1'";
  		$dbupdate_rows['to_member_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['message'] = "TEXT DEFAULT NULL";
  		$dbupdate_rows['link'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_primary_key = "notification_id";
  		$dbupdate_foreign_keys[] = "FOREIGN KEY (group_id) REFERENCES groups(group_id) ON DELETE CASCADE";
  		$dbupdate_foreign_keys[] = "FOREIGN KEY (to_member_id) REFERENCES members(member_id) ON DELETE CASCADE";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      //=============================================================================================
      unset($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
  		$dbupdate_name = "pageviews";
  		$dbupdate_rows['pageview_id'] = "INT(11) NOT NULL AUTO_INCREMENT";
      $dbupdate_rows['status'] = "INT(2) NOT NULL DEFAULT '2'";
  		$dbupdate_rows['group_id'] = "INT(11) DEFAULT NULL";
  		$dbupdate_rows['inc_domain'] = "VARCHAR(50) DEFAULT NULL";
  		$dbupdate_rows['group_hash'] = "VARCHAR(50) DEFAULT NULL";
  		$dbupdate_rows['page'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['referrer'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['browser'] = "VARCHAR(255) DEFAULT NULL";
  		$dbupdate_rows['ip'] = "VARCHAR(50) DEFAULT NULL";
  		$dbupdate_rows['edit_date'] = "DATETIME DEFAULT NULL";
  		$dbupdate_rows['created_date'] = "TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
  		$dbupdate_primary_key = "pageview_id";
  		$dbupdate_foreign_keys[] = "FOREIGN KEY (group_id) REFERENCES groups(group_id) ON DELETE CASCADE";
      $this->db_update_table($dbupdate_name, $dbupdate_rows, $dbupdate_primary_key, $dbupdate_foreign_keys);
      //=============================================================================================


			//=============================================================================================
			$stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='activities'"); $stmt->execute();
			if ($stmt->rowCount() == 0) {
				unset($input_array);
        $input_array['system_plugin'] = "2";
				$input_array['title'] = "activities";
				$input_array['font_icon'] = "paw";
				if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
			}
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='dashboard'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "dashboard";
        $input_array['font_icon'] = "home";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='friendslist'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "friendslist";
        $input_array['font_icon'] = "users";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='groupmembers'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "groupmembers";
        $input_array['font_icon'] = "id-badge";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='groups'"); $stmt->execute();
  		if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
  			$input_array['title'] = "groups";
  			$input_array['font_icon'] = "handshake-o";
  			if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
  		}
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='groupsettings'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "groupsettings";
        $input_array['font_icon'] = "gear";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='layout'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "layout";
        $input_array['font_icon'] = "object-ungroup";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='plugins'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "plugins";
        $input_array['font_icon'] = "plug";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='members'"); $stmt->execute();
  		if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
  			$input_array['title'] = "members";
  			$input_array['font_icon'] = "users";
  			if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
  		}
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='messages'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "messages";
        $input_array['font_icon'] = "envelope";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='notifications'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['system_plugin'] = "2";
        $input_array['has_menuwidget'] = "1";
        $input_array['title'] = "notifications";
        $input_array['font_icon'] = "bell";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='clients'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['title'] = "clients";
        $input_array['font_icon'] = "briefcase";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================
      $stmt = $this->pdo->prepare("SELECT * FROM plugins WHERE title='tasks'"); $stmt->execute();
      if ($stmt->rowCount() == 0) {
        unset($input_array);
        $input_array['title'] = "tasks";
        $input_array['font_icon'] = "check-square-o";
        if(!$this->_add_insert("plugins", $input_array)) { echo "Error: [".basename(__FILE__)."] [".__LINE__."]"; exit; }
      }
      //=============================================================================================

      //=============================================================================================

      //=============================================================================================


    }
  }
	//===========================================================================================

}
?>
