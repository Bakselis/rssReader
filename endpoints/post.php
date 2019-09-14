<?php
require_once(__DIR__ . '/../models/feeds/rssFeed.php');
require_once(__DIR__ . '/../models/feeds/flickrFeed.php');
require_once(__DIR__ . '/../feedList.php');

// some simple checking of crucial information
if(isset($_GET["feed"])){
  $feed = $_GET["feed"];
}else{
  die;
}

if(isset($_GET["post"])){
  $postTitle = $_GET["post"];
}else{
  die;
}

if(!array_key_exists($feed, $feeds)){
  die;
}

$jsonResponse = [];
$previous = "";
$next = "";
$finalPost = [];
if($feeds[$feed]['type'] == "RSS"){
  $feedElement = new RssFeed($feeds[$feed]['url']);
}else if($feeds[$feed]['type'] == "Flickr"){
  $feedElement = new FlickrFeed($feeds[$feed]['url']);
}

$posts = $feedElement->getAll();
foreach ($posts as $index=>$post) {
  if($post->getTitle() == urldecode($postTitle)){

    $finalPost = $post->getPost();
    // check if previous post exist
    if($index-1 > 0){
      $previous = '?feed='.$feed.'&post='.urlencode($posts[$index-1]->getTitle());
    }
    // check if next post exist
    if($index+1 < count($posts)){
      $next = '?feed='.$feed.'&post='.urlencode($posts[$index+1]->getTitle());
    }
    break;
  }

}


$jsonResponse = [
  'feedTitle' => $feedElement->getFeedName(),
  'post' => $finalPost,
  'previousPostUrl' => $previous,
  'nextPostUrl' => $next,
  'feedUrl' => '?feed='.$feed
];



$jsonResponse = json_encode($jsonResponse);
echo $jsonResponse;

 ?>
