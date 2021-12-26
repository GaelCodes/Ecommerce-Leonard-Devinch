<?php

class Artworks {
    private $tituto;
    private $correo_pintor;
    private $tematica;
    private $fecha_inicio;
    private $fecha_fin;
    private $cantidad_disponible;
    private $cantidad_creada;
    private $dimension_x;
    private $dimension_y;
    private $precio;

    function set_titulo($titulo) {
        $this->titulo = $titulo;
    }

    function get_titulo() {
        return $this->titulo;
    }

    function set_correo_pintor($correo_pintor) {
        $this->correo_pintor = $correo_pintor;
    }

    function get_correo_pintor() {
        return $this->correo_pintor;
    }

    function set_tematica($tematica) {
        $this->tematica = $tematica;
    }

    function get_tematica() {
        return $this->tematica;
    }

    function set_fecha_inicio($fecha_inicio) {
        $this->fecha_inicio = $fecha_inicio;
    }

    function get_fecha_inicio() {
        return $this->fecha_inicio;
    }

    function set_fecha_fin($fecha_fin) {
        $this->fecha_fin = $fecha_fin;
    }

    function get_fecha_fin() {
        return $this->fecha_fin;
    }

    function set_cantidad_disponible($cantidad_disponible) {
        $this->cantidad_disponible = $cantidad_disponible;
    }

    function get_cantidad_disponible() {
        return $this->cantidad_disponible;
    }

    function set_cantidad_creada($cantidad_creada) {
        $this->cantidad_creada = $cantidad_creada;
    }

    function get_cantidad_creada() {
        return $this->cantidad_creada;
    }

    function set_dimension_x($dimension_x) {
        $this->dimension_x = $dimension_x;
    }

    function get_dimension_x() {
        return $this->dimension_x;
    }

    function set_dimension_y($dimension_y) {
        $this->dimension_y = $dimension_y;
    }

    function get_dimension_y() {
        return $this->dimension_y;
    }

    function set_precio($precio) {
        $this->precio = $precio;
    }

    function get_precio() {
        return $this->precio;
    }
}