<?php

class Client
{
  private $client_email;
  private $password;
  private $full_name;
  private $shipping_address;
  private $telephone_number;

  public function __construct(
    $client_email,
    $password,
    $full_name,
    $shipping_address = null,
    $telephone_number = null
  ) {
    $this->client_email = $client_email;
    $this->password = $password;
    $this->full_name = $full_name;
    $this->shipping_address = $shipping_address;
    $this->telephone_number = $telephone_number;
  }

  public function get_email()
  {
    return $this->client_email;
  }
  public function get_full_name()
  {
    return $this->full_name;
  }
  public function get_password()
  {
    return $this->password;
  }
  public function get_telephone_number()
  {
    return $this->telephone_number;
  }

  public function get_shipping_address()
  {
    return $this->shipping_address;
  }
}
