<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once(__DIR__ . '/../models/feeds/rssFeed.php');
require_once(__DIR__ . '/../models/feeds/flickrFeed.php');
require_once(__DIR__ . '/../feedList.php');

$feed = "";

if(isset($_GET["feed"])){
  $feed = $_GET["feed"];
}

if(array_key_exists($feed, $feeds)){
  // getting just one feed
  $newFeed = [];
  $newFeed[$feed] = $feeds[$feed];
  $feeds = $newFeed;
}

$jsonResponse = [];

foreach ($feeds as $key=>$element) {

  if($element['type'] == "RSS"){
    $feedElement = new RssFeed($element['url']);
  }else if($element['type'] == "Flickr"){
    $feedElement = new FlickrFeed($element['url']);
  }
  $postsResponse = [];
  $posts = $feedElement->getAll();
  foreach ($posts as $post) {
    $url = "?feed=".$key."&post=". urlencode($post->getTitle());
    $postInfo = $post->getPost();
    $postInfo["url"] = $url;
    array_push($postsResponse, $postInfo);
  }

  $elementResponse = [
    "title" => $feedElement->getFeedName(),
    "posts" => $postsResponse
  ];

  array_push($jsonResponse, $elementResponse);
}


$jsonResponse = json_encode($jsonResponse);
echo $jsonResponse;
?>
