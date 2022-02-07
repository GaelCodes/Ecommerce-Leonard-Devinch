<?php

class Artwork
{
  private $title;
  private $url;
  private $artist;
  private $topics;
  private $starting_date;
  private $ending_date;
  private $available_quantity;
  private $created_quantity;
  private $dimension_x;
  private $dimension_y;
  private $price;

  function __construct(
    $title,
    $url,
    $artist,
    $topics,
    $starting_date,
    $ending_date,
    $available_quantity,
    $created_quantity,
    $dimension_x,
    $dimension_y,
    $price
  ) {
    $this->title = $title;
    $this->url = $url;
    $this->artist = $artist;
    $this->topics = $topics;
    $this->starting_date = $starting_date;
    $this->ending_date = $ending_date;
    $this->available_quantity = $available_quantity;
    $this->created_quantity = $created_quantity;
    $this->dimension_x = $dimension_x;
    $this->dimension_y = $dimension_y;
    $this->price = $price;
  }

  function set_title($title)
  {
    $this->title = $title;
  }

  function get_title()
  {
    return $this->title;
  }

  function set_topics($topics)
  {
    $this->topics = $topics;
  }

  function get_topics()
  {
    return $this->topics;
  }

  function set_starting_date($starting_date)
  {
    $this->starting_date = $starting_date;
  }

  function get_starting_date()
  {
    return $this->starting_date;
  }

  function set_ending_date($ending_date)
  {
    $this->ending_date = $ending_date;
  }

  function get_ending_date()
  {
    return $this->ending_date;
  }

  function set_available_quantity($available_quantity)
  {
    $this->available_quantity = $available_quantity;
  }

  function get_available_quantity()
  {
    return $this->available_quantity;
  }

  function set_created_quantity($created_quantity)
  {
    $this->created_quantity = $created_quantity;
  }

  function get_created_quantity()
  {
    return $this->created_quantity;
  }

  function set_dimension_x($dimension_x)
  {
    $this->dimension_x = $dimension_x;
  }

  function get_dimension_x()
  {
    return $this->dimension_x;
  }

  function set_dimension_y($dimension_y)
  {
    $this->dimension_y = $dimension_y;
  }

  function get_dimension_y()
  {
    return $this->dimension_y;
  }

  function set_price($price)
  {
    $this->price = $price;
  }

  function get_price()
  {
    return $this->price;
  }

  public function __toString()
  {
    return "[
            title => " .
      $this->title .
      ",
            url => " .
      $this->url .
      ",
            artist => " .
      $this->artist .
      ",
            topics => " .
      $this->topics .
      ",
            starting_date => " .
      $this->starting_date .
      ",
            ending_date => " .
      $this->ending_date .
      ",
            available_quantity => " .
      $this->available_quantity .
      ",
            created_quantity => " .
      $this->created_quantity .
      ",
            dimension_x => " .
      $this->dimension_x .
      ",
            dimension_y => " .
      $this->dimension_y .
      ",
            price => " .
      $this->price .
      "
        ]";
  }

  public function toArray()
  {
    return [
      "title" => $this->title,
      "url" => $this->url,
      "artist" => $this->artist,
      "topics" => $this->topics,
      "starting_date" => $this->starting_date,
      "ending_date" => $this->ending_date,
      "available_quantity" => $this->available_quantity,
      "created_quantity" => $this->created_quantity,
      "dimension_x" => $this->dimension_x,
      "dimension_y" => $this->dimension_y,
      "price" => $this->price,
    ];
  }
}
