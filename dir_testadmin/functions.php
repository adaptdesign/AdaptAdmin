<?php

//=========================================
function loggedIn() {
  if(isset($_SESSION['myloggedin']) && isset($_SESSION['sessiontoken'])) { return true; }
  else { return false; }
}
//=========================================
function disarray($disarray,$args="") { ob_start();
  if(!empty($args)) {
    $keys = array(); foreach ($args as $key => $value) { if (is_string($key)) { $keys[] = '/:'.$key.'/'; } else { $keys[] = '/[?]/'; } }
    $disarray = preg_replace($keys, $args, $disarray, 1, $count);
    echo "<pre>"; print_r($disarray); echo "</pre>";
  } else {
    echo "<pre>"; print_r($disarray); echo "</pre>";
  }
  return ob_get_clean();
}
//=========================================
function e_a($array="", $index="", $clean=0, $code=0, $default=null) {
  if(isset($array[$index]) && strlen($value = trim($array[$index])) > 0) {
    if($code==1) { $value = encode($value); } elseif($code==2) { $value = decode($value); }
    if($clean) { $value = clean($value); }
    return $value;
  } else { return $default; }
}
//=========================================
function output_error($message,$json=false) {
  $message = '<div class="alert alert-danger caps m-b-md"><div>'.implode('</div><div>',$message).'</div>';
  if($json) { echo json_encode(array('status'=>'error','message'=>$message,'data'=>'none','js'=>'')); }
  else { echo $message; }
}
//=========================================
function show_methods($classname) {
  echo "<pre>/*<br> * METHOD LIST FOR ".$classname."<br> *<br>";
  $f = new ReflectionClass($classname);
  foreach ($f->getMethods() as $m) { if ($m->class == $classname) {
    echo " * ".$m->name."<br>";
  } }
  echo " *<br> */</pre>";
}
//=========================================
function show_peakmemory() {
  $sizes = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
  $factor = floor((strlen(memory_get_peak_usage()) - 1) / 3);
  $fileSize = sprintf('%.2f', memory_get_peak_usage() / pow(1024, $factor)) . $sizes[$factor];
  disarray("PEAK MEMORY USAGE: ".$fileSize);
}
//=========================================
function clean($string) {
  return preg_replace('/[^a-z A-Z0-9-_\,\.\/\:\=\@\|]/','', $string);
}
//=========================================
function uniqidReal($length = 13,$chars = 1) {
	if($chars == 1) {
		return rand(pow(10, $length-1), pow(10, $length)-1);
	} else {
		if (function_exists("random_bytes")) {
      $bytes = random_bytes(ceil($length / 2));
    } elseif (function_exists("openssl_random_pseudo_bytes")) {
      $bytes = openssl_random_pseudo_bytes(ceil($length / 2));
    } else {
      throw new Exception("no cryptographically secure random function available");
    }
    return substr(bin2hex($bytes), 0, $length);
	}
}
//=========================================
function hashPassword($pPassword, $pSalt1="24859fz5754op", $pSalt2="^&@#_-=+Afda$#%") {
  return sha1(md5($pSalt2 . $pPassword . $pSalt1));
}
//=========================================
function safe_b64encode($string) {
  $data = base64_encode($string);
  $data = str_replace(array('+','/','='),array('-','_',''),$data);
  return $data;
}
//=========================================
function safe_b64decode($string) {
  $data = str_replace(array('-','_'),array('+','/'),$string);
  $mod4 = strlen($data) % 4;
  if ($mod4) {
    $data .= substr('====', $mod4);
  }
  return base64_decode($data);
}
//=========================================
function encode($value){
  if(!$value){return false;}
  $text = $value;
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT1, $text, MCRYPT_MODE_ECB, $iv);
  return trim(safe_b64encode($crypttext));
}
//=========================================
function decode($value){
  if(!$value){return false;}
  $crypttext = safe_b64decode($value);
  $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
  $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
  $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT1, $crypttext, MCRYPT_MODE_ECB, $iv);
  return trim($decrypttext);
}
//=========================================
function require_all($dir,$filter="*.php") {
  $files = glob($dir.$filter);
  foreach ($files as $file) {
    if(preg_match('/\.php$/', $file)) { require_once $file; }
  }
  foreach (glob("$dir*", GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT) as $dir) {
    require_all($dir,$filter);
  }
}
//=========================================


?>
