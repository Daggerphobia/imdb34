<?php
include 'dbconn.php';
function NewUser($request) {
  	$nickname = strtolower($request->nickname);
	$name = $request->name;
	$surname = $request->surname;
	$password = md5($request->password);
	$user_token = md5(time());
	$registration_token = md5($nickname.$email);
	$query = "INSERT INTO users (nickname,name,surname,password,user_token,registration_token) VALUES ('$nickname','$name','$surname','$password','$user_token','$registration_token')"; 
	$data = mysql_query ($query) or die(mysql_error()); 
	if($data) { 
	 	echo '{"signupResult":0,"nickname":"'.$nickname.'","token":"'.$user_token.'"}';
	} 
} 
function SignUp($request) {
 if(!empty($request->nickname)) //checking the 'user' name which is from Sign-Up.html, is it empty or have some text
  { 
  	$query = mysql_query("SELECT * FROM users WHERE nickname = '$request->nickname'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0){
    		echo '{"signupResult":1}';
  	} else {
    	newuser($request);
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
		SignUp($request);
	}
?>
