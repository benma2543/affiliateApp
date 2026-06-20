<?php
require_once __DIR__ . '/../config.php';
$GLOBALS['l1']=$l1;
class Users {
  
  private $pdo = null;
  private $stmt = null;
  public $error = null;
  function __construct () {
    $this->pdo = new PDO(
      "mysql:host=".USER_DB_HOST.";dbname=".USER_DB_NAME.";charset=".USER_DB_CHARSET,
      USER_DB_USER, USER_DB_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
  }

  
  function __destruct () {
    if ($this->stmt !== null) { $this->stmt = null; }
    if ($this->pdo !== null) { $this->pdo = null; }
  }

  
  function query ($sql, $data=null) {
    $this->stmt = $this->pdo->prepare($sql);
    $this->stmt->execute($data);
  }

  
  function get ($id) {
    $this->query(sprintf("SELECT * FROM `users` WHERE `user_%s`=?", is_numeric($id) ? "id" : "email"), [$id]);
    return $this->stmt->fetch();
  }

  
  function login ($email, $password) {
  
    if (isset($_SESSION["user".$GLOBALS['l1']])) { return true; }

  
    $user = $this->get($email);
    if (!is_array($user)) { return false; }
  
	if ($user["user_status"]==0){return false;}
    
	
    if (password_verify($password, $user["user_password"])) {
      $_SESSION["user".$GLOBALS['l1']] = [];
      $userlogins=$user["user_logins"];
	  $userlogins++;
	  $userlogins=$user["user_logins"]=$userlogins;
	  $user["user_lastlogin"]=date('Y-m-d H:i:s');
	  $sql = "UPDATE `users` SET `user_logins`=?, `user_lastlogin`=? WHERE `user_id`=?";
      $data = [$userlogins, $user["user_lastlogin"], $user["user_id"]];
	  $this->query($sql, $data);
	  foreach ($user as $k=>$v) { if ($k!="user_password") { $_SESSION["user".$GLOBALS['l1']][$k] = $v; }}
      
	  return true;
    }
    return false;
  }

  
  function save ($name, $email, $pass, $id=null) {
  

	$nowtime=date('Y-m-d H:i:s');
    if ($id===null) {
	  $sql = "INSERT INTO `users` (`user_name`, `user_email`, `user_password`,`user_creation`,`user_lastlogin`,`user_logins`,`user_apikey`,`user_status`,`user_txid_wplus`,`user_txid_jvzoo`) VALUES (?,?,?,?,?,?,?,?,?,?)";
      $data = [$name, $email, password_hash($pass, PASSWORD_DEFAULT),$nowtime,$nowtime,0,"",1,"",""];
		if (file_exists(__DIR__ . '/../add3.php')) {require __DIR__ . '/../add3.php';}else{$add3=0;}
		if ($add3==1){
			$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
			$currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
			// Move up one directory to get to the correct directory
			$runtimeURL = dirname($currentURL);
			$script2URL = $runtimeURL . "/adminsendlead.php";
			$curldata = array(
				'name' => $name,
				'email' => $email,
			);
			$postFields = http_build_query($curldata);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $script2URL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
			$response = curl_exec($ch);
			curl_close($ch);
		}	
    } else {
      $sql = "UPDATE `users` SET `user_name`=?, `user_email`=?, `user_password`=? WHERE `user_id`=?";
      $data = [$name, $email, password_hash($pass, PASSWORD_DEFAULT), $id];
    }

    
    $this->query($sql, $data);
    return true;
  }
 
  function saveapi($apikey,$id) {
	
	$sql = "UPDATE `users` SET `user_apikey`=? WHERE `user_id`=?";
	$data = [$apikey, $id];
	$this->query($sql, $data);
	$_SESSION["user".$GLOBALS['l1']]["user_apikey"]=$apikey;
    return true;
	}
  
  function lostpassword($emailforpassword,$sitename) {
	 $this->query(sprintf("SELECT user_id FROM `users` WHERE `user_%s`=?", "email"), [$emailforpassword]);
     $userfound=$this->stmt->fetch();
	 
	 if ($userfound!=""){
		 $id=$userfound["user_id"];
		 $password=randomPassword();
		 $sql = "UPDATE `users` SET `user_password`=? WHERE `user_id`=?";
		 $data = [password_hash($password, PASSWORD_DEFAULT), $id];		 
		 $qresult=$this->query($sql, $data);
		 $message="Hi\r\n\r\nYou requested a new password from ".$sitename." - Here it is : ".$password."\r\n\r\nYou can use your email and this password to login.\r\n\r\nMany Thanks.";
		$fromemail="noreply@".$_SERVER['SERVER_NAME'];
		$headers = array(
			'From' => $fromemail,
			'Reply-To' => $fromemail,
			'X-Mailer' => 'PHP/' . phpversion()
		);		 
		mail($emailforpassword,"New Password From ".$sitename,$message,$headers);

		 
	 }

  }
   function ban($id,$mode) {
	
	$sql = "UPDATE `users` SET `user_status`=? WHERE `user_id`=?";
	$data = [$mode, $id];
	$this->query($sql, $data);
    return true;
	}
	
	function del($id) {
		$sql = "DELETE FROM `users` WHERE `user_id`=?";
		$data = [$id];
		$this->query($sql, $data);
		return true;
	}

  function setpassword($userid,$password) {
		$sql = "UPDATE `users` SET `user_password`=? WHERE `user_id`=?";
		$data = [password_hash($password, PASSWORD_DEFAULT), $userid];		 
		$this->query($sql, $data);

  }  
}


function randomPassword() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $pass = array(); 
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < 12; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); 
}



require_once("config.php");

$USR = new Users();