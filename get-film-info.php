<?php
include 'dbconn.php';
function get_film_user_data($request){
  if(!empty($request->nickname) && !empty($request->token)) 
  {
  	$nickname = strtolower($request->nickname);
    $token = $request->token;
    $movie_id = $request->film_id;
    $query = mysql_query("SELECT views FROM movies WHERE id=$movie_id");
    if(mysql_num_rows($query)>0) {
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
            $views = $row['views'];
        }
        $views++;
    }
    $view_incr_query = mysql_query("UPDATE movies SET views=$views WHERE id=$movie_id");
   //$reg_id = $request->reg_id;
  	$query = mysql_query("SELECT * FROM users WHERE LOWER(nickname) = '$nickname' AND user_token = '$token'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0) {
       echo "[";
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
          $user_id = $row['id'];
          $watchlist_count = 0;
          $query_watchlist_info = mysql_query("SELECT movie_id FROM watchlist WHERE user_id = $user_id AND movie_id = $movie_id ") or die(mysql_error()); 
           if(mysql_num_rows($query_watchlist_info)>0) {
               $watchlist_count = 1;
           }
          $query_rated_info = mysql_query("SELECT rating FROM ratings WHERE user_id = $user_id AND movie_id = $movie_id LIMIT 1") or die(mysql_error()); 
           $return_arr = array();  
           if(mysql_num_rows($query_rated_info)>0) {
              while ($row = mysql_fetch_array($query_rated_info, MYSQL_ASSOC)) {
                $rating = $row['rating'];
                if($watchlist_count == 1)
                { echo '{"watchlist":true},'; }
                else
                { echo '{"watchlist":false},'; }
                echo '{"rated":true,"rating":'.$rating.'},';
              }
           }else{
               if($watchlist_count == 1)
                { echo '{"watchlist":true},'; }
                else
                { echo '{"watchlist":false},'; }
                echo '{"rated":false},';
           }
           
           $query_comments = mysql_query("SELECT c.comment,u.nickname FROM comments c INNER JOIN users u ON u.id = c.user_id WHERE  movie_id = $movie_id ORDER BY c.id DESC ") or die(mysql_error()); 
            $comment_arr = array();  
            if(mysql_num_rows($query_comments)>0) {
                while ($row = mysql_fetch_array($query_comments, MYSQL_ASSOC)) {
                    $comment_arr [] = $row;
                }
           }
                echo '{"comments":';
                echo json_encode($comment_arr);
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
		get_film_user_data($request);
	}
?>