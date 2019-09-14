<?php
class Post{
  private $postTitle, $postLink, $postDesc, $postImage, $postDate;

  public function __construct($postTitle, $postLink, $postDesc, $postImage, $postDate){
    $this->postTitle = $postTitle;
    $this->postLink = $postLink;
    $this->postDesc = $postDesc;
    $this->postImage = $postImage;
    $this->postDate = new DateTime($postDate);
  }

  public function getPost(){
    return [
      "title" => $this->postTitle,
      "link" => $this->postLink,
      "description" => $this->postDesc,
      "image" => $this->postImage,
      "date" => $this->postDate->format('Y-m-d H:i:s')
    ];
  }

  public function getTitle(){
    return $this->postTitle;
  }
}

 ?>
