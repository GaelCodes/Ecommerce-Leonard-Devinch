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

  public function getCookie(string $name)
  {
    return isset($_COOKIE[$name]) ? $_COOKIE[$name] : false;
  }

  public function getMethod()
  {
    return $_SERVER["REQUEST_METHOD"];
  }
}
