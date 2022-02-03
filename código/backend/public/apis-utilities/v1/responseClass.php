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

  public function send()
  {
    echo $this->content;
  }
}
