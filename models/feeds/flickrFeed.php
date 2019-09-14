<?php
require_once(__DIR__ . '/../feed.php');
require_once(__DIR__ . '/../post.php');

// this is the class for RssFeed access
class FlickrFeed implements Feed{
  private $xml;

  private $feedName;
  private $feedLink;
  private $posts = [];

  public function __construct($xml){
       $this->xml = $xml;

       $xmlDoc = new DOMDocument();
       $xmlDoc->load($xml);

       $this->feedName = $xmlDoc->getElementsByTagName('title')
                   ->item(0)->childNodes->item(0)->nodeValue;

       $this->feedLink = $xmlDoc->getElementsByTagName('link')->item(0)
                                                        ->getAttribute('href');

       $items=$xmlDoc->getElementsByTagName('entry');

       for ($i=0; $i<$items->length; $i++) {
         $item = $items->item($i);
         $postTitle= $item->getElementsByTagName('title')
                    ->item(0)->childNodes->item(0)->nodeValue;

         $postDesc = $item->getElementsByTagName('content')
                    ->item(0)->childNodes->item(0)->nodeValue;

         $postDate = $item->getElementsByTagName('published')
                    ->item(0)->childNodes->item(0)->nodeValue;


          // i am using xmlDoc not xpath so in order not to change that
          // this is a hack
          foreach ($item->getElementsByTagName('link') as $link){
              if ($link->getAttribute('rel') == "alternate")
              {
                $postLink= $link->getAttribute('href');
              }else if($link->getAttribute('rel') == "enclosure"){
                $postImage= $link->getAttribute('href');
              }
          }

         $post = new Post($postTitle, $postLink, $postDesc, $postImage, $postDate);

         array_push($this->posts, $post);
       }
  }

  public function getAll(){
    return $this->posts;
  }

  public function getFeedName(){
    return $this->feedName;
  }
}
