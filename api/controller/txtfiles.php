<?php
require_once '../model/Query.php';
$db         = new Query("hempmexc_ahogadas");
$type       = $_GET['type'];
$idmesa     = $_GET['idmesa'];
$idempleado = $_GET['idempleado'];
$db->setIdmesa(@$idmesa);
$db->setIdempleado(@$idempleado);
/** Header All Tickets **/

$html = " ** Tortas Ahogadas Guadalajara ** ";
$html .= chr(13).chr(10);
$html .= utf8_encode( utf8_decode(" Direccion : Col. Ensueños, 54740 ") );
$html .= chr(13).chr(10);
$html .= utf8_decode( utf8_encode(" Cuautitlán Izcalli, Méx " ) );
$html .= chr(13).chr(10);
$html .= " Telefono : 58680712               ";
$html .= chr(13).chr(10);
$html .= " ". date('Y-m-d');
$html .= chr(13).chr(10);
$html .= chr(13).chr(10);
$html .= chr(13).chr(10);
//<<Resumen Cuenta productos Based Concat >>
if( $type == 'resumenCuenta' ){    
    $html .= " ** [ Resumen de Cuenta ] ** ";    
    //$html .= " {$idmesa} || {$idempleado}  ";
    //echo $db->queryManagment("get", " DISTINCT ordenes.idorden, productos.idproducto, precio, indicador, orden_productos.estatus, orden_productos.size, count(precio) as total, SUM(precio) as money ", "ordenes", "idempleado =  {$db->getIdempleado()}  AND idmesa =  {$db->getIdmesa()} AND ordenes.estatus = 1 GROUP BY productos.idproducto", "orden_productos ON orden_productos.idorden = ordenes.idorden INNER JOIN productos ON productos.idproducto = orden_productos.idproducto");
    $order = $db->get($db->queryManagment("get", " DISTINCT ordenes.idorden, productos.idproducto, precio, indicador, orden_productos.estatus, orden_productos.size, count(precio) as total, SUM(precio) as money ", "ordenes", "idempleado =  {$db->getIdempleado()}  AND idmesa =  {$db->getIdmesa()} AND ordenes.estatus = 1 GROUP BY productos.idproducto", "orden_productos ON orden_productos.idorden = ordenes.idorden INNER JOIN productos ON productos.idproducto = orden_productos.idproducto"));            
    $html .= chr(13).chr(10);
    $html .= str_pad("cantidad", 5, " ", STR_PAD_RIGHT) .  "|" . str_pad(substr(trim("producto"),0,12),12, " ", STR_PAD_RIGHT) . '|  ' . str_pad(trim("precio"), 13," ", STR_PAD_RIGHT )  . chr(13).chr(10);    
    for ( $i=0; $i<count($order); $i++){
        $html .=  " " . str_pad($i+1, 5, " ", STR_PAD_RIGHT) .  "|" . str_pad(substr(trim($order[$i]['indicador']),0,12),12, " ", STR_PAD_RIGHT) . '|  ' . str_pad(trim($order[$i]['precio']), 13," ", STR_PAD_RIGHT )  . chr(13).chr(10);                                 
    }
    
    echo $html;
        
} else if( $type == 'detalleCuenta' ){
    
} 

// Changed in Red 
//$printer ="NPI06D6B1 (HP LaserJet 500 color MFP M575)";
//$printer = "NPI10E8FA (HP LaserJet M605)";
//$enlace = printer_open($printer);
//printer_write($enlace, $html);
//printer_close($enlace);