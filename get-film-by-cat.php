<?php
include 'dbconn.php';
function get_film_list($request){
  $category = $request->category;
    $query_film_list = mysql_query("SELECT movies.*,(SELECT ROUND(avg(rating),1) FROM `ratings` WHERE movies.id = ratings.movie_id) AS rating,(SELECT count(rating) FROM `ratings` WHERE movies.id = ratings.movie_id) AS rating_count FROM `movies` WHERE genre LIKE '%$category%' ORDER BY rating DESC LIMIT 50") or die(mysql_error());

	$count = mysql_num_rows($query_film_list);
    if($count>0) {
		$film_array = array();
		while ($row = mysql_fetch_array($query_film_list, MYSQL_ASSOC)) {
			$film_array [] = $row;
		}
		echo json_encode($film_array);
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
    get_film_list($request);
  }
?>