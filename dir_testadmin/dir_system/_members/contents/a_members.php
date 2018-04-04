<?php

class a_members extends global_members {

  //=========================================
  public function __construct(){
		parent::__construct();

  }
  //=========================================
  public function p_add_member() {
    // CHECK IF POST
    if(!$_POST) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; }
    // LIMIT SUBMIT COUNT
    if(!isset($_SESSION['rcount'])) { $_SESSION['rcount'] = 1; $_SESSION['rfirst'] = time(); }
    else { $_SESSION['rcount']++;
      if($_SESSION['rfirst'] < (time() - 100)) { $_SESSION['rcount'] = 1; $_SESSION['rfirst'] = time(); }
      if($_SESSION['rcount'] > 10) { $this->fail_page[] = "<div>To many attempts. Please wait a short time before trying again</div>"; }
    }
    // VERIFY TERMS OF SERVICE
    if($_POST['terms'] != "2") { $this->fail_page[] = "<div>You must agree to the terms and conditions to use AdaptAdmin</div>"; }
    // VERIFY FIRST NAME
    if(empty($_POST['firstname'])) { $this->fail_page[] = "<div>Fist Name required</div>"; }
    if(!preg_match("/^[a-zA-Z ._-]{1,30}$/i",$_POST['firstname'])) { $this->fail_page[] = "<div>Only letters and [.][-][_] characters allow for the First Name</div>"; }
    // VERIFY LAST NAME
    if(empty($_POST['lastname'])) { $this->fail_page[] = "<div>Last Name required</div>"; }
    if(!preg_match("/^[a-zA-Z ._-]{1,30}$/i",$_POST['lastname'])) { $this->fail_page[] = "<div>Only letters and [.][-][_] characters allow for the Last Name</div>"; }
    // VERIFY EMAIL
    if(empty($_POST['email'])) { $this->fail_page[] = "<div>Email required</div>"; }
    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) { $this->fail_page[] = "<div>Please enter a valid email address</div>"; }
    $stmt = $this->pdo->prepare('SELECT * FROM members WHERE email=:email LIMIT 1');
    $stmt->execute(array(':email' => $_POST['email']));
    if($stmt->rowCount() > 0) { $this->fail_page[] = "<div>Email already in use. Please try another</div>"; }
    // VERIFY PASSWORD
    if(empty($_POST['password'])) { $this->fail_page[] = "<div>Password required</div>"; }
    if(!preg_match ('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*)[0-9A-Za-z!@#$%]{6,20}$/', $_POST['password'])) { $this->fail_page[] = "<div>Password must be between 5-50 characters.<br> At least one uppercase letter.<br> At least one number. At least one lowercase letter.<br> Allowed special characters are <strong>[!@#$%*-_]</strong></div>"; }
    // VERIFY CAPTCHA
    if(isset($_POST["captcha"]) && $_POST["captcha"] != "" && $_SESSION["code"] == $_POST["captcha"]) {
    }	else { $this->fail_page[] = "<div>Incorrect captcha code entered</div>"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }

    // ADD NEW MEMBER
    for($i=0;$i<20;$i++) {
      $rand_num = uniqidReal(13,0);
      $sql = $this->pdo->query("SELECT * FROM members WHERE member_hash='".$rand_num."'");
      if($sql->rowCount() == 0) { break; }
    }
    $approval = "2";
    $stmtc = $this->pdo->prepare("INSERT INTO members (approval, member_hash, firstname, lastname, email, password, last_active, created_date)
    VALUES(:approval, :member_hash, :firstname, :lastname, :email, :password, UTC_TIMESTAMP(), UTC_TIMESTAMP())");
    $stmtc->execute(array(':approval' => $approval, ':member_hash' => $rand_num, ':firstname' => $_POST['firstname'], ':lastname' => $_POST['lastname'], ':email' => $_POST['email'], ':password' => hashPassword($_POST['password'])));
    $last_id = $this->pdo->lastInsertId();

    if($stmtc->rowCount() == 1) {
      $input_array['member_id'] = $last_id;
      $input_array['level'] = "10";
      $input_array['title'] = "Private Group";

      $groups = new a_groups();
      $return = $groups->p_add_group($input_array);
      if(is_numeric($return) && $return > 0) { $group_id = $return; }
      else { echo "Failed to add private group"; return; }

      $a_msg = "New account created";

      $to = "alltracst185@gmail.com";
      $subject = "New Member Registration";
      $message = '
      <!doctype html>
      <html>
        <head>
          <meta name="viewport" content="width=device-width" />
          <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
          <title>AdaptAdmin</title>
        </head>
        <body style="background:#f5f5f7;padding:20px;margin:0;font-family:Verdana;font-size:12px;color:#666;">
          <table style="background: #FFFFFF; margin-bottom: 80px; width: 600px; border: 1px solid #ddd; border-radius: 4px; text-align: center;">
            <tbody>
              <tr>
                <td style="padding: 10px;">
                  <div style="padding: 30px 0px; background: #1ab394; color: #ffffff;">
                    <h2>AdaptAdmin</h2>
                    New Member Registered
                  </div>
                </td>
              </tr>
              <tr>
                <td style="padding: 10px;">
                  <h3>Message Details</h3>
                  Name: '.$_POST['firstname'].' '.$_POST['lastname'].'<br>
                  E-mail: '.$_POST['email'].'
                </td>
              </tr>
            </tbody>
          </table>
        </body>
      </html>
      ';
      $headers = "MIME-Version: 1.0" . "\r\n";
      $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
      $headers .= 'From: AdaptAdmin <support@adaptdesign.us>' . "\r\n";
      mail($to,$subject,$message,$headers);

      $return = $this->_add_activity(false,"",$this->plugins['members']['plugin_id'],$last_id,"",$a_msg,"","",false,"1",$last_id,$group_id);
      if($return[0] != "good") { echo $return[1]; return; }

      $_SESSION['sessiontoken'] = encode($last_id);
      $stmt = $this->pdo->prepare("UPDATE members SET sessiontoken=:sessiontoken WHERE member_id=:member_id LIMIT 1");
      $stmt->execute(array(':sessiontoken' => encode($last_id), ':member_id' => $last_id));
      $_SESSION['myloggedin'] = true;
      $_SESSION['loginmessages'] = "set";
      $_SESSION['alertmessage'] = "Account created!";
      echo "true| Account has been created. You may now login!";
    } else {
      echo "false| Error processing request ";
    }
  }
	//=========================================
  public function p_edit_member() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
    if(!$_POST) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK IF POST
    if($this->member_data['member_id'] != $this->where_array['member_id']) { $this->fail_page[] = "<div>Access to this content is restricted</div>"; } // CHECK ACCESS LEVEL
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    // DO UPDATE
    $this->activity_msg = "Updated account details";
    $this->return_msg = "Update complete";
    if(!$this->_add_update("members", $this->input_array, $this->where_array)) { $this->fail_page[] = "<div>Error processing request</div>"; }
    // ADD ACTIVITY LOG
    $return = $this->_add_activity(false,"",$this->plugins['members']['plugin_id'],$this->where_array['member_id'],"",$this->activity_msg);
    if($return[0] != "good") { $this->fail_page[] = $return[1]; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    // COMPLETE
    $_SESSION['alertmessage'] = $this->return_msg;
    echo "true| ".$this->return_msg;
	}
  //=========================================
  public function p_set_timezone() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
    if(!$_POST) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK IF POST
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    $input_array['time_zone'] = clean($_POST['timezone']);
    $where_array['member_id'] = $this->member_data['member_id'];
    // DO UPDATE
    if(!$this->_add_update("members", $input_array, $where_array)) { $this->fail_page[] = "<div>Error processing request</div>"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
	}
  //=========================================
  public function p_edit_profile() {
    // CHECK FOR ERRORS
    if(!loggedIn()){ $this->fail_page[] = "<div>Session has expired. Please <a href='".URL_PATH."'>log in</a></div>"; } // CHECK LOGGED IN
    if(!$_POST) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK IF POST
    if($this->member_data['member_id'] != $this->where_array['member_id']) { $this->fail_page[] = "<div>Access to this content is restricted</div>"; } // CHECK ACCESS LEVEL
    // VERIFY EMAIL
    if(empty($this->input_array['email'])) { $this->fail_page[] = "<div>Email required</div>"; }
    if(!filter_var($this->input_array['email'], FILTER_VALIDATE_EMAIL)) { $this->fail_page[] = "<div>Please enter a valid email address</div>"; }
    if($this->member_data['email'] != $this->input_array['email']) {
      $stmt = $this->pdo->prepare('SELECT * FROM members WHERE email=:email LIMIT 1');
      $stmt->execute(array(':email' => $this->input_array['email']));
      if($stmt->rowCount() > 0) { $this->fail_page[] = "<div>Email already in use. Please try another</div>"; }
    }
    // VERIFY FIRST NAME
    if(empty($this->input_array['firstname'])) { $this->fail_page[] = "<div>Fist Name required</div>"; }
    if(!preg_match("/^[a-zA-Z ._-]{1,30}$/i",$this->input_array['firstname'])) { $this->fail_page[] = "<div>Only letters and [.][-][_] characters allow for the First Name</div>"; }
    // VERIFY LAST NAME
    if(empty($this->input_array['lastname'])) { $this->fail_page[] = "<div>Last Name required</div>"; }
    if(!preg_match("/^[a-zA-Z ._-]{1,30}$/i",$this->input_array['lastname'])) { $this->fail_page[] = "<div>Only letters and [.][-][_] characters allow for the Last Name</div>"; }
    // VERIFY PASSWORDS
    if(empty($this->input_array["currentpassword"]) && empty($this->input_array["password"]) && empty($this->input_array["confirmpassword"])) {
      unset($this->input_array['currentpassword']);
      unset($this->input_array['password']);
      unset($this->input_array['confirmpassword']);
    } else {
      if(empty($this->input_array['currentpassword'])) { $this->fail_page[] = "<div>Current Password required. If you do not wish to update your password please be sure that all password fields are blank</div>"; }
      if(empty($this->input_array['password'])) { $this->fail_page[] = "<div>New Password required. If you do not wish to update your password please be sure that all password fields are blank</div>"; }
      if(empty($this->input_array['confirmpassword'])) { $this->fail_page[] = "<div>Confirm New Password required. If you do not wish to update your password please be sure that all password fields are blank</div>"; }
      if(!preg_match ('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*)[0-9A-Za-z!@#$%]{5,50}$/', $this->input_array['password'])) { $this->fail_page[] = "<div>Password must be between 5-50 characters.<br> At least one uppercase letter.<br> At least one number. At least one lowercase letter.<br> Allowed special characters are <strong>[!@#$%*-_]</strong></div>"; }
      if($this->input_array['password'] != $this->input_array['confirmpassword']) { $this->fail_page[] = "<div>New Password and New Confirm Password must match</div>"; }
      $stmt = $this->pdo->prepare("SELECT * FROM members WHERE member_id=:member_id AND password=:password LIMIT 1");
      $stmt->execute(array(':member_id' => $this->member_data['member_id'], ':password' => hashPassword($this->input_array['currentpassword'])));
      if($stmt->rowCount() != 1) { $this->fail_page[] = "<div>Current password does not match the one on file</div>"; }
      unset($this->input_array['currentpassword']);
      unset($this->input_array['confirmpassword']);
      $this->input_array['password'] = hashPassword($this->input_array["password"]);
      $this->where_array['member_id'] = $this->member_data['member_id'];
    }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    // GET CONTENT DATA
    $stmt = $this->pdo->prepare("SELECT * FROM members WHERE member_id=:member_id LIMIT 1"); $stmt->execute(array(':member_id' => $this->where_array['member_id']));
    if($stmt->rowCount() == 1) { $stmt_data = $stmt->fetch(PDO::FETCH_ASSOC); }
    else { return array("bad","Error: [".basename(__FILE__)."] [".__LINE__."]"); }
    // ADD PROFILE IMG
    if(!empty($_POST["my_hidden"])){
      $decoded = $_POST["my_hidden"];
      if (strpos($decoded,',') !== false) { }
      else { echo "false| Please select and save an image"; exit; }
      $randomId = uniqid();
      $exp = explode(',', $decoded);
      $base64 = array_pop($exp);
      $data = base64_decode($base64);
      $image = imagecreatefromstring($data);
      imagepng($image, URL_ROOT."/dir_img/profile_img/$randomId.png", 9);
      $this->input_array["profile_img"] = "dir_img/profile_img/$randomId.png";
      if(!empty($stmt_data['profile_img'])) { if(file_exists(URL_ROOT."/".$stmt_data['profile_img'])) { unlink(URL_ROOT."/".$stmt_data['profile_img']); } };
    }
    // DO UPDATE
    $this->activity_msg = "Updated account settings";
    $this->return_msg = "Update complete";
    if(!$this->_add_update("members", $this->input_array, $this->where_array)) { $this->fail_page[] = "<div>Error processing request</div>"; }
    // ADD ACTIVITY LOG
    $return = $this->_add_activity(false,"",$this->plugins['members']['plugin_id'],$this->where_array['member_id'],"",$this->activity_msg);
    if($return[0] != "good") { $this->fail_page[] = $return[1]; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    // COMPLETE
    $_SESSION['alertmessage'] = $this->return_msg;
    echo "true| ".$this->return_msg;
	}
  //=========================================
  public function p_login() {
    // CHECK FOR ERRORS
    if(!$_POST) { $this->fail_page[] = "<div>Error: Unable to load widget data</div>"; } // CHECK IF POST
    // LIMIT SUBMIT COUNT
    if(!isset($_SESSION['rcount'])) { $_SESSION['rcount'] = 1; $_SESSION['rfirst'] = time(); }
    else { $_SESSION['rcount']++;
      if($_SESSION['rfirst'] < (time() - 100)) { $_SESSION['rcount'] = 1; $_SESSION['rfirst'] = time(); }
      if($_SESSION['rcount'] > 10) { $this->fail_page[] = "<div>To many attempts. Please wait a short time before trying again</div>"; }
    }
    // VERIFY EMAIL
    if(empty($_POST['singleEmail'])) { $this->fail_page[] = "<div>Email required</div>"; }
    if(!filter_var($_POST['singleEmail'], FILTER_VALIDATE_EMAIL)) { $this->fail_page[] = "<div>Please enter a valid email address</div>"; }
    // VERIFY PASSWORDS
    if(empty($_POST['singlePassword'])) { $this->fail_page[] = "<div>Password required</div>"; }
    if(!preg_match ('/^(\w*(?=\w*\d)(?=\w*[a-z])(?=\w*[A-Z])\w*){6,20}$/', $_POST['singlePassword'])) { $this->fail_page[] = "<div>Password must contain at least one uppercase letter, lowercase letter and number</div>"; }
    // VERIFY EMAIL AND PASSWORD MATCH
    $stmt = $this->pdo->prepare('SELECT * FROM members WHERE email=:email AND password=:password LIMIT 1');
    $stmt->execute(array(':email' => $_POST['singleEmail'], ':password' => hashPassword($_POST['singlePassword'])));
    if($stmt->rowCount() == 1) {
      $member_data = $stmt->fetch(PDO::FETCH_ASSOC);
    } else { $this->fail_page[] = "<div>Invalid Email or Password</div>"; }
    // SET APPROVAL
    $approval = "2";
    // CHECK IF DISABLED
    if($member_data['member_level'] == '1') { $this->fail_page[] = "<div>Accout has been disabled</div>"; }
    // CHECK IF APPROVED
    if($approval == '2' && $member_data['approval'] == '1') { $this->fail_page[] = "<div>Account not yet approval by a supervisor</div>"; }
    // DISPLAY ERRORS
    if(!empty($this->fail_page)) { ?><div class="alert alert-danger caps m-b-md"><?="<div>".implode('</div><div>',$this->fail_page)."</div>"?></div><? return; }
    // DO LOGIN
    $_SESSION['sessiontoken'] = encode($member_data['member_id']);
    $stmt = $this->pdo->prepare("UPDATE members SET sessiontoken=:sessiontoken WHERE member_id=:member_id LIMIT 1");
    $stmt->execute(array(':sessiontoken' => encode($member_data['member_id']), ':member_id' => $member_data['member_id']));
    $_SESSION['myloggedin'] = true;
    $_SESSION['loginmessages'] = "set";
    $this->pdo->exec("UPDATE members SET last_active=UTC_TIMESTAMP() WHERE member_id='".$member_data['member_id']."' ");
    // COMPLETE
    echo "login| Login successfull";
	}
  //=========================================
	public function p_logout() {
		unset($_SESSION['sessiontoken']);
		unset($_SESSION['myloggedin']);
		session_destroy();
		header('Location: '.URL_PATH.'');
	}

}
?>
