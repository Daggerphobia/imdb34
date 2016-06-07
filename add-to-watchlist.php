<?php
include 'dbconn.php';
function add_to_watchlist($request){
  if(!empty($request->nickname) && !empty($request->token)) 
  {
  	$nickname = strtolower($request->nickname);
    $token = $request->token;
    $movie_id = $request->film_id;
   //$reg_id = $request->reg_id;
  	$query = mysql_query("SELECT * FROM users WHERE LOWER(nickname) = '$nickname' AND user_token = '$token'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0) {
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
          $user_id = $row['id'];
          $watchlist_count = 0;
          $query_watchlist_info = mysql_query("SELECT movie_id FROM watchlist WHERE user_id = $user_id AND movie_id = $movie_id ") or die(mysql_error()); 
           if(mysql_num_rows($query_watchlist_info)>0) {
              echo '{"result":2}';
           }else{
              $query_watchlist = mysql_query("INSERT INTO watchlist (user_id, movie_id) VALUES($user_id, $movie_id) ") or die(mysql_error()); 
              if($query_watchlist){
                echo '{"result":1}';
              }else{
                echo '{"result":0}';
              }
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
		add_to_watchlist($request);
	}
?>