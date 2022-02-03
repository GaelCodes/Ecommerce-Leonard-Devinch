<?php

class Artist
{
  private $artist_email;
  private $password;
  private $full_name;
  private $total_created_artworks;
  private $date_of_birth;
  private $style;

  function __construct(
    $artist_email,
    $password = null,
    $full_name,
    $total_created_artworks,
    $date_of_birth,
    $style
  ) {
    $this->artist_email = $artist_email;
    $this->password = $password;
    $this->full_name = $full_name;
    $this->total_created_artworks = $total_created_artworks;
    $this->date_of_birth = $date_of_birth;
    $this->style = $style;
  }

  public function set_artist_email($artist_email)
  {
    $this->artist_email = $artist_email;
  }

  public function get_artist_email()
  {
    return $this->artist_email;
  }

  public function set_full_name($full_name)
  {
    $this->full_name = $full_name;
  }

  public function get_full_name()
  {
    return $this->full_name;
  }

  public function set_total_created_artworks($total_created_artworks)
  {
    $this->total_created_artworks = $total_created_artworks;
  }

  public function get_total_created_artworks()
  {
    return $this->total_created_artworks;
  }

  public function set_date_of_birth($date_of_birth)
  {
    $this->date_of_birth = $date_of_birth;
  }

  public function get_date_of_birth()
  {
    return $this->date_of_birth;
  }

  public function set_style($style)
  {
    $this->style = $style;
  }

  public function get_style()
  {
    return $this->style;
  }

  public function __toString()
  {
    return "[
            artist_email => " .
      $this->artist_email .
      ",
            full_name => " .
      $this->full_name .
      ",
            total_created_artworks => " .
      $this->total_created_artworks .
      ",
            date_of_birth => " .
      $this->date_of_birth .
      ",
            style => " .
      $this->style .
      "
        ]";
  }

  public function toArray()
  {
    return [
      "artist_email" => $this->artist_email,
      "full_name" => $this->full_name,
      "total_created_artworks" => $this->total_created_artworks,
      "date_of_birth" => $this->date_of_birth,
      "style" => $this->style,
    ];
  }
}
