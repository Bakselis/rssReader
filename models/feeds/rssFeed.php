<?php
require_once(__DIR__ . '/../feed.php');
require_once(__DIR__ . '/../post.php');

// this is the class for RssFeed access
class RssFeed implements Feed{
  private $xml;

  private $feedName;
  private $feedLink;
  private $posts = [];

  public function __construct($xml){
       $this->xml = $xml;

       $xmlDoc = new DOMDocument();
       $xmlDoc->load($xml);

       $channel = $xmlDoc->getElementsByTagName('channel')->item(0);

       $this->feedName = $channel->getElementsByTagName('title')
                   ->item(0)->childNodes->item(0)->nodeValue;

       $this->feedLink = $channel->getElementsByTagName('link')
                   ->item(0)->childNodes->item(0)->nodeValue;



       $items=$xmlDoc->getElementsByTagName('item');

       for ($i=0; $i<$items->length; $i++) {
         $item = $items->item($i);
         $postTitle= $item->getElementsByTagName('title')
                    ->item(0)->childNodes->item(0)->nodeValue;
         $postLink= $item->getElementsByTagName('link')
                    ->item(0)->childNodes->item(0)->nodeValue;
         $postDesc= $item->getElementsByTagName('description')
                    ->item(0)->childNodes->item(0)->nodeValue;

         $postDate = $item->getElementsByTagName('pubDate')
                    ->item(0)->childNodes->item(0)->nodeValue;

         $postImage = "";
         $post = new Post($postTitle, $postLink, $postDesc, $postImage, $postDate);

         array_push($this->posts, $post);
       }
  }

  public function getAll(){
    return $this->posts;
  }

  public function getAllArray(){
    $postsResponse = [];
    foreach ($this->posts as $post) {
      $postInfo = $post->getPost();
      array_push($postsResponse, $postInfo);
    }
    return $postsResponse;
  }

  public function getFeedName(){
    return $this->feedName;
  }
}
