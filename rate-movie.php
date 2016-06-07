<?php
include 'dbconn.php';
function rate_movie($request){
  if(!empty($request->nickname) && !empty($request->token)) 
  {
  	$nickname = strtolower($request->nickname);
    $token = $request->token;
    $movie_id = $request->film_id;
    $rating = $request->rating;
   //$reg_id = $request->reg_id;
  	$query = mysql_query("SELECT * FROM users WHERE LOWER(nickname) = '$nickname' AND user_token = '$token'") or die(mysql_error()); 
  	if(mysql_num_rows($query)>0) {
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
          $user_id = $row['id'];
          $query_rated_info = mysql_query("SELECT rating FROM ratings WHERE user_id = $user_id AND movie_id = $movie_id LIMIT 1") or die(mysql_error()); 
           if(mysql_num_rows($query_rated_info)>0) {
              echo '{"result":2}';
           }else{
              $query_rating = mysql_query("INSERT INTO ratings (user_id, movie_id,rating) VALUES($user_id, $movie_id,$rating) ") or die(mysql_error()); 
              if($query_rating){
                $query_film_list = mysql_query("SELECT  (SELECT ROUND(avg(rating),1) FROM `ratings` WHERE movies.id = ratings.movie_id) AS rating,  (SELECT rating FROM `ratings` WHERE movies.id = ratings.movie_id AND user_id = $user_id) AS your_rating,(SELECT count(rating) FROM `ratings` WHERE movies.id = ratings.movie_id) AS rating_count FROM `movies` WHERE movies.id = $movie_id ORDER BY rating DESC LIMIT 10") or die(mysql_error());
                    $count = mysql_num_rows($query_film_list);
                      if($count>0) {
                      $film_array = array();
                      while ($row = mysql_fetch_array($query_film_list, MYSQL_ASSOC)) {
                        $film_array [] = $row;
                      }
                      echo '{"ratings":';
                      echo json_encode($film_array);
                      echo ',"result":1}';
                    }
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
		rate_movie($request);
	}
?>

