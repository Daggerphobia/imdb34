<?php 
$url = $_SERVER['REQUEST_URI'];
if (strpos($url,'movies') !== false) {
    echo 'Car exists.';
} else {
    echo 'No cars.';
}