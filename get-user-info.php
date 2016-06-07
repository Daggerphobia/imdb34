<?php
include 'dbconn.php';
function get_user_info($request){
  if(!empty($request->nickname) && !empty($request->token)) 
  {
    $nickname = strtolower($request->nickname);
    $token = $request->token;
    $query = mysql_query("SELECT users.*,(SELECT count(rating) FROM `ratings` WHERE users.id = ratings.user_id) AS rating_count,(SELECT count(*) FROM `watchlist` WHERE users.id= watchlist.user_id) AS watchlist_count FROM users WHERE LOWER(nickname) = '$nickname' AND user_token = '$token'") or die(mysql_error()); 
    if(mysql_num_rows($query)>0) {
        $user_array = array();
        while ($row = mysql_fetch_array($query, MYSQL_ASSOC)) {
           $user_array[] = $row;
        }
        echo json_encode($user_array);
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
    get_user_info($request);
  }
?>