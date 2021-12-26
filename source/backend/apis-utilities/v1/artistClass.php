<?php

class Artist {
    private $correo;
    private $password;
    private $nombre_completo;
    private $cantidad_de_obras;
    private $fecha_de_nacimiento;
    private $estilo;

    function __construct($correo,$password=null,$nombre_completo,$cantidad_de_obras,$fecha_de_nacimiento,$estilo) {
        $this->correo = $correo;
        $this->password = $password;
        $this->nombre_completo = $nombre_completo;
        $this->cantidad_de_obras = $cantidad_de_obras;
        $this->fecha_de_nacimiento = $fecha_de_nacimiento;
        $this->estilo = $estilo;
    }

    public function set_correo($correo) {
        $this->correo = $correo;
    }

    public function get_correo() {
        return $this->correo;
    }

    public function set_nombre_completo($nombre_completo) {
        $this->nombre_completo = $nombre_completo;
    }

    public function get_nombre_completo() {
        return $this->nombre_completo;
    }

    public function set_cantidad_de_obras($cantidad_de_obras) {
        $this->cantidad_de_obras = $cantidad_de_obras;
    }

    public function get_cantidad_de_obras() {
        return $this->cantidad_de_obras;
    }

    public function set_fecha_de_nacimiento($fecha_de_nacimiento) {
        $this->fecha_de_nacimiento = $fecha_de_nacimiento;
    }

    public function get_fecha_de_nacimiento() {
        return $this->fecha_de_nacimiento;
    }

    public function set_estilo($estilo) {
        $this->estilo = $estilo;
    }

    public function get_estilo() {
        return $this->estilo;
    }

    public function __toString() {
        return "[
            correo => ".$this->correo.",
            nombre_completo => ".$this->nombre_completo.",
            cantidad_de_obras => ".$this->cantidad_de_obras.",
            fecha_de_nacimiento => ".$this->fecha_de_nacimiento.",
            estilo => ".$this->estilo."
        ]";
    }

    public function toArray() {
        return [
            "correo" => $this->correo,
            "nombre_completo" => $this->nombre_completo,
            "cantidad_de_obras" => $this->cantidad_de_obras,
            "fecha_de_nacimiento" => $this->fecha_de_nacimiento,
            "estilo" => $this->estilo
        ];
    }
}