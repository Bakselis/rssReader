<?php
// interface for feeds
// this is created in order to ensure that every feed object would have getAll,
// getFeedName methods
interface Feed{
  public  function getAll();
  public  function getFeedName();
}
