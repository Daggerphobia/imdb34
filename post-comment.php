<?php
include 'dbconn.php';
function rate_movie($request){
  if(!empty($request->nickname) && !empty($request->token)) 
  {
  	$nickname = strtolower($request->nickname);
    $token = $request->token;
    $movie_id = $request->film_id;
    $comment = $request->comment;
   //$reg_id = $request->reg_id;
  	$query = mysql_query("SELECT * FROM users WHERE LOWER(nickname) = '$nickname' AND user_token = '$token'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0) {
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
          $user_id = $row['id'];
          
            $query_rating = mysql_query("INSERT INTO comments (user_id, movie_id,comment) VALUES($user_id, $movie_id,'$comment') ") or die(mysql_error()); 
            if($query_rating){
                
           }
        }
    } else {
    	echo '{"result":0}';//faluire
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
		rate_movie($request);
	}
?>

