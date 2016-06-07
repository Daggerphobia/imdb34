<?php
include 'dbconn.php';
function login($request){
  if(!empty($request->nickname) && !empty($request->password)) 
  {
  	$nickname = strtolower($request->nickname);
  	$password = md5($request->password);
	$user_token = md5(time());
   //$reg_id = $request->reg_id;
  	$query = mysql_query("SELECT * FROM users WHERE LOWER(nickname) = '$nickname' AND password = '$password'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0) { 
       $queryupdate = "UPDATE users SET user_token='$user_token' WHERE LOWER(nickname) = '$nickname' AND password = '$password'"; 
      mysql_query($queryupdate);
     echo '{"loginResult":1,"nickname":"'.$nickname.'","token":"'.$user_token.'"}';
// $queryupdate = "UPDATE users SET gcm_regid='$reg_id' WHERE LOWER(vehicle_no) = '$vehicle_no' AND password = '$password'"; 
     // $dataupdate = mysql_query ($queryupdate)or die(mysql_error()); 
    } else {
    	echo '{"loginResult":0}';//faluire
    } 
  }
}


if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers:        {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }


    //http://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
    $postdata = file_get_contents("php://input");
	if (isset($postdata)) {
		$request = json_decode($postdata);
		login($request);
	}
?>