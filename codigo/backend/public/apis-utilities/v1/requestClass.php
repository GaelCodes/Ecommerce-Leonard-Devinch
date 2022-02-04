<?php

class Request
{
  private $content;
  function __construct()
  {
    $this->content = file_get_contents("php://input");
  }

  public function getContent()
  {
    return $this->content;
  }
}
