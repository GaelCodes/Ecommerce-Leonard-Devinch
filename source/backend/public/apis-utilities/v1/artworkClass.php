<?php

class Artwork
{
  private $tituto;
  private $url;
  private $artista;
  private $tematica;
  private $fecha_inicio;
  private $fecha_fin;
  private $cantidad_disponible;
  private $cantidad_creada;
  private $dimension_x;
  private $dimension_y;
  private $precio;

  function __construct(
    $tituto,
    $url,
    $artista,
    $tematica,
    $fecha_inicio,
    $fecha_fin,
    $cantidad_disponible,
    $cantidad_creada,
    $dimension_x,
    $dimension_y,
    $precio
  ) {
    $this->tituto = $tituto;
    $this->url = $url;
    $this->artista = $artista;
    $this->tematica = $tematica;
    $this->fecha_inicio = $fecha_inicio;
    $this->fecha_fin = $fecha_fin;
    $this->cantidad_disponible = $cantidad_disponible;
    $this->cantidad_creada = $cantidad_creada;
    $this->dimension_x = $dimension_x;
    $this->dimension_y = $dimension_y;
    $this->precio = $precio;
  }

  function set_titulo($titulo)
  {
    $this->titulo = $titulo;
  }

  function get_titulo()
  {
    return $this->titulo;
  }

  function set_tematica($tematica)
  {
    $this->tematica = $tematica;
  }

  function get_tematica()
  {
    return $this->tematica;
  }

  function set_fecha_inicio($fecha_inicio)
  {
    $this->fecha_inicio = $fecha_inicio;
  }

  function get_fecha_inicio()
  {
    return $this->fecha_inicio;
  }

  function set_fecha_fin($fecha_fin)
  {
    $this->fecha_fin = $fecha_fin;
  }

  function get_fecha_fin()
  {
    return $this->fecha_fin;
  }

  function set_cantidad_disponible($cantidad_disponible)
  {
    $this->cantidad_disponible = $cantidad_disponible;
  }

  function get_cantidad_disponible()
  {
    return $this->cantidad_disponible;
  }

  function set_cantidad_creada($cantidad_creada)
  {
    $this->cantidad_creada = $cantidad_creada;
  }

  function get_cantidad_creada()
  {
    return $this->cantidad_creada;
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

  function set_precio($precio)
  {
    $this->precio = $precio;
  }

  function get_precio()
  {
    return $this->precio;
  }

  public function __toString()
  {
    return "[
            tituto => " .
      $this->tituto .
      ",
            url => " .
      $this->url .
      ",
            artista => " .
      $this->artista .
      ",
            tematica => " .
      $this->tematica .
      ",
            fecha_inicio => " .
      $this->fecha_inicio .
      ",
            fecha_fin => " .
      $this->fecha_fin .
      ",
            cantidad_disponible => " .
      $this->cantidad_disponible .
      ",
            cantidad_creada => " .
      $this->cantidad_creada .
      ",
            dimension_x => " .
      $this->dimension_x .
      ",
            dimension_y => " .
      $this->dimension_y .
      ",
            precio => " .
      $this->precio .
      "
        ]";
  }

  public function toArray()
  {
    return [
      "tituto" => $this->tituto,
      "url" => $this->url,
      "artista" => $this->artista,
      "tematica" => $this->tematica,
      "fecha_inicio" => $this->fecha_inicio,
      "fecha_fin" => $this->fecha_fin,
      "cantidad_disponible" => $this->cantidad_disponible,
      "cantidad_creada" => $this->cantidad_creada,
      "dimension_x" => $this->dimension_x,
      "dimension_y" => $this->dimension_y,
      "precio" => $this->precio,
    ];
  }
}
