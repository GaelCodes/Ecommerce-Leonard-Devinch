<?php

class Response
{
  private $content;

  function __construct()
  {
  }

  public function setHeader($header)
  {
    header($header);
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setCode($code)
  {
    http_response_code($code);
  }

  public function send()
  {
    echo $this->content;
  }
}
