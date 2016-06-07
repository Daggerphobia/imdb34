<?php
include 'dbconn.php';
function get_watchlist($request){
  if(!empty($request->nickname) && !empty($request->token)) 
  {
  	$nickname = strtolower($request->nickname);
    $token = $request->token;
  	$query = mysql_query("SELECT * FROM users WHERE LOWER(nickname) = '$nickname' AND user_token = '$token'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0) {
       echo "[";
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
          $user_id = $row['id'];
          echo '{"filmlistwatch":';
          $query_watchlist = mysql_query("SELECT m.*,(SELECT ROUND(avg(rating),1) FROM `ratings` WHERE m.id = ratings.movie_id) AS rating,(SELECT count(rating) FROM `ratings` WHERE m.id = ratings.movie_id) AS rating_count  FROM watchlist w INNER JOIN movies m ON w.movie_id = m.id WHERE w.user_id = $user_id ") or die(mysql_error()); 
            $watchlist_count = mysql_num_rows($query_watchlist);
           if($watchlist_count>0) {
               $film_watchlist_array = array();
              while ($row = mysql_fetch_array($query_watchlist, MYSQL_ASSOC)) {
                  $film_watchlist_array[] = $row;
              }
              echo json_encode($film_watchlist_array);
              echo ',"count":'.$watchlist_count.'';
           }else{
              echo '[],"count":0}';
           }
          echo '}';


        }
        echo "]";
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
		get_watchlist($request);
	}
?>