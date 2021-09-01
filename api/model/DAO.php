<?php
class DAO {
    
    const TB_MESAS = "mesas";
    const TB_ORDEN = "ordenes";
    const TB_ORDEN_PRODUCTOS = "orden_productos";
    const FIELDS_ORDENES   = "idorden, idmesa, idempleado, fecha, estatus";
    const FIELDS_ORDEN_PRO = "idorden_productos, idorden, idproducto, indicador, cantidad, size, estatus";
    const FIELDS_EMPLOYEE  = "idperfiles, nombres, apellidos, user, pass, telefono, fecha_nacimiento";
    // const PRINTER_BEBIDAS  = "Bebidas";
    // const PRINTER_COCINAS  = "Cocina";
    
   const PRINTER_BEBIDAS  = "HP DJ 2130 series";
   const PRINTER_COCINAS  = "HP DJ 2130 series";    
    
    
    
    private $id;
    private $descripcion;
    
    /** Orden **/
    private $idmesa;
    private $idempleado;
    private $fecha;
    
    /** Orden Productos **/
    private $idorden;        
    private $idproducto;
    private $indicador;
    private $canditad;
    private $size;
    private $estatus;
    
    /** Empleados **/
    private $user;
    private $pass;
    private $nombre;
                    
    /** setters getters **/
    function getId() {
        return $this->id;
    }

    function getDescripcion() {
        return $this->descripcion;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
    }

    function getIdmesa() {
        return $this->idmesa;
    }

    function getIdempleado() {
        return $this->idempleado;
    }

    function getIdorden() {
        return $this->idorden;
    }

    function setIdmesa($idmesa) {
        $this->idmesa = $idmesa;
    }

    function setIdempleado($idempleado) {
        $this->idempleado = $idempleado;
    }

    function setIdorden($idorden) {
        $this->idorden = $idorden;
    }

    function getFecha() {
        return $this->fecha;
    }

    function getIdproducto() {
        return $this->idproducto;
    }

    function getIndicador() {
        return $this->indicador;
    }

    function getCanditad() {
        return $this->canditad;
    }

    function getSize() {
        return $this->size;
    }

    function getEstatus() {
        return $this->estatus;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }

    function setIdproducto($idproducto) {
        $this->idproducto = $idproducto;
    }

    function setIndicador($indicador) {
        $this->indicador = $indicador;
    }

    function setCanditad($canditad) {
        $this->canditad = $canditad;
    }

    function setSize($size) {
        $this->size = $size;
    }

    function setEstatus($estatus) {
        $this->estatus = $estatus;
    }

    function getPass() {
        return $this->pass;
    }
    function getUser() {
        return $this->user;
    }

    function setUser($user) {
        $this->user = $user;
    }

        function setPass($pass) {
        $this->pass = $pass;
    }

    function getNombre() {
        return $this->nombre;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }



}
