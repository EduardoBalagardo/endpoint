<?php 

interface IDB {   
    const USER = "hempmexc_ahogado";
    const PASS = "puffuppass.1986";
    const HOST = "localhost";
    const DB   = "hempmexc_ahogadas";
    const REQ_CTR = "api/controller/";
    const REQ_MDL = "api/model/";
    const MESAS   = 'mesas';
    const CATEGORIAS = 'categorias';
    const PRODUCTOS  = 'productos';
    const EMPLEADOS  = 'empleados';
    
    const SIGNED_MESA  = "signed_mesa";
    const VERIFY_ORDER = "verify_order";
    const ADD_ORDER    = 'add_order';
    const CHECK_ORDER  = 'check_order';
    const PAY_ORDER    = 'pay_order';
    const PRINT_CENTER = 'print_center';
    const GET_ORDERS   = 'get_orders';
    const GET_CHARTS   = 'get_charts';
    const CRUD_CATALOG = 'crud_catalogs';
    public function filter($data,$type);
}

?>