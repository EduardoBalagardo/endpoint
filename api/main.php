<?php

require_once './model/DataBase.php';
require_once './model/Query.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
date_default_timezone_set('America/Mexico_City');
$db = new Query(DataBase::DB);
//ini_set('display_errors', '1');

$request = json_decode(file_get_contents("php://input"));
$kind = $db->filter($request->params->kind, 'string');

//Obtener Mesas
if ($kind === DataBase::MESAS) {
    $values = array('type' => 'get', 'fields' => '*', 'table' => 'mesas', 'where' => "", 'inner' => 'empleados ON empleados.idempleado = mesas.idempleado ORDER BY idmesa');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    $data = $db->sanitizeObject("mesas", $db->get($sql));
    //$db->clsCnn();
    echo json_encode($data);
}

//Categoria
if ($kind === DataBase::CATEGORIAS) {
    $values = array('type' => 'get', 'fields' => '*', 'table' => 'categorias_productos', 'where' => "", 'inner' => '');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    $data = $db->sanitizeObject("categorias", $db->get($sql));
    //$db->clsCnn();
    echo json_encode($data);
}

//Productos 
if ($kind === DataBase::PRODUCTOS) {
    $values = array('type' => 'get', 'fields' => '*', 'table' => 'productos', 'where' => "", 'inner' => ' indicadores ON indicadores.idproducto = productos.idproducto');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    echo json_encode($db->sanitizeObject("productos", $db->get($sql)));
    //$db->clsCnn();
}

if( $kind == DataBase::EMPLEADOS ) {
    $values = array('type' => 'get', 'fields' => '*', 'table' => 'empleados', 'where' => "", 'inner' => '');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    echo json_encode($db->sanitizeObject("empleados", $db->get($sql)));
    //$db->clsCnn();
}

//Asignacion Mesas
if ($kind === DataBase::SIGNED_MESA) {
    $type = $request->params->type;
    if ($type === 'asociar_empleado') {
        $values = array('type' => 'get', 'fields' => " mesas.idmesa, empleados.nombres, empleados.idempleado ", 'table' => 'mesas', 'where' => " idmesa = {$request->params->idmesa} ", 'inner' => 'empleados ON empleados.idempleado = mesas.idempleado');
        $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
        $mesa = $db->get($sql);
        
        $id = (int) $mesa[0]['idempleado'];
        if ($id == 1) {
            //Fix why i dont know but is necesary 
            $db = new Query(DataBase::DB);
            $values = array('type' => 'put', 'fields' => "idempleado = {$request->params->idempleado} ", 'table' => 'mesas', 'where' => " idmesa = {$request->params->idmesa} ", 'inner' => '');
            $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
            if ($db->put($sql) == true) {
                $answer = array('nombres' => $mesa[0]['nombres'], 'idempleado' => $mesa[0]['idempleado'], 'status' => 'asignada');
            }        
        }
        if ($id != 1) {
            if ((int) $request->params->idempleado === (int) $mesa[0]['idempleado']) {
                $answer = array('nombres' => $mesa[0]['nombres'], 'idempleado' => $mesa[0]['idempleado'], 'status' => 'en_uso');
            } else {
                $answer = array('nombres' => $mesa[0]['nombres'], 'idempleado' => $mesa[0]['idempleado'], 'status' => 'rush');
            }
        }
    } else {
        $values = array('type' => 'put', 'fields' => "idempleado = 1 ", 'table' => 'mesas', 'where' => " idmesa = {$request->params->idmesa} ", 'inner' => '');
        $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
        if ($db->put($sql) == true) {
            $answer = array('nombres' => 'Disponible', 'idempleado' => $request->params->idempleado, 'status' => 'disponible');
        }
    }
    //reponce
    //$db->clsCnn();
    echo json_encode($answer);
}


//Orden Performance
/**
 * @verificar
 * Verifica que la orden exista o se encuentre disponible para crear una nueva orden.
 * estatus = 0 ordenado -> Valor de inicio desde Front End
 * estatus = 1 Servido
 * estatus = 2 Pagado
 * */
if ($kind === DataBase::VERIFY_ORDER) {
    $idempleado = @$request->params->idempleado;
    $idmesa = @$request->params->idmesa;
    $values = array('type' => 'get', 'fields' => " estatus, idorden ", 'table' => 'ordenes_mesas', 'where' => "idmesa = $idmesa AND idempleado = $idempleado AND estatus = 1 ", 'inner' => '');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    $orderStatus = $db->get($sql);
    $id = ( count($orderStatus) > 0 ? (int) $orderStatus[0]['idorden'] : 0);
    // Si existe un Id Para Esta Orden Lo Asigna
    if ($id != 0) {
        //Generamos Orden y Id Para que siga pidiendo nuevas cosas $order         
        //$db->clsCnn();
        echo json_encode(array('id' => $id));
    }
    // Si no existe un Id Para Esta Orden lo Crea
    else if ($id == 0) {
        $db = new Query(DataBase::DB);
        $values = array('type' => 'ins', 'fields' => " idmesa,idempleado,estatus ", 'table' => 'ordenes_mesas', 'where' => " {$idmesa}, {$idempleado}, 1 ", 'inner' => '');
        $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
        $is = $db->getIdRow($sql);
        //$db->clsCnn();
        echo json_encode(array('id' => $is['id']));
    }
 
}

//Agregar Elementos a tabla inorder para contabilizarlos
if ($kind == DataBase::ADD_ORDER) {
    $order = @$request->params->order;
    $id = @$request->params->idorden;
    $i = 0;
    
    foreach ($order as $key => $cve) {
        $sql= "";
        $db = new Query(DataBase::DB);
        $date = new DateTime();
        $d = $date->format('Y-m-d H:i:s');
        $values = array('type' => 'ins', 'fields' => " idorden, idproducto, estatus, indicador, cantidad, fecha ", 'table' => 'inorder', 'where' => " {$id}, {$cve->idproducto}, 1, '{$cve->descripcion}', 1, '{$d}' ", 'inner' => '');
        $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
        if ($db->put($sql) == true) {
            $i++;
        }
        //$ssql.= $sql;
    }
    //$db->clsCnn();
    echo json_encode(array('count' => $i, 'len' => count($order), 'sql'=>$sql ));
    
}

//Obtener Orden Actual
if ($kind == DataBase::CHECK_ORDER) {
    $idmesa = @$request->params->idmesa;
    $idempleado = @$request->params->idempleado;
    $values = array('type' => 'get', 'fields' => " idorden ", 'table' => 'ordenes_mesas', 'where' => " idmesa = {$idmesa} AND idempleado = {$idempleado} AND estatus = 1 ", 'inner' => '');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    $idorden = $db->get($sql);
    $id = ( count($idorden) > 0 ? (int) $idorden[0]['idorden'] : 0 );
    $resOrder = array();
    $cantidad = 0;
    $precio = 0;
    if ($id != 0) {
        $db = new Query(DataBase::DB);
        $values = array('type' => 'get', 'fields' => " DISTINCT idproducto ", 'table' => 'inorder', 'where' => " idorden = {$id} AND estatus = 1 ", 'inner' => '');
        $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
        $prod = $db->get($sql);
        if (count($prod) > 0) {            
            for ($i = 0; $i < count($prod); $i++) {                       
                $db = new Query(DataBase::DB);
//                //SELECT SUM(cantidad) as cantidad, indicador, precio FROM `inorder` INNER JOIN productos ON productos.idproducto = inorder.idproducto WHERE inorder.idproducto = 1
                $values = array();
                $values = array('type' => 'get', 'fields' => " SUM(cantidad) as cantidad, indicador, SUM(precio) as precio, idcategoria ", 'table' => 'inorder', 'where' => " inorder.idproducto = {$prod[$i]['idproducto']} AND estatus = 1 ", 'inner' => ' productos ON productos.idproducto = inorder.idproducto');
                $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);                                
                $total = $db->get($sql);
                if (count($total) > 0) {
                    //// categoria 
                    $resOrder[$i] = array('idmesa' => $idmesa, 
                                          'idempleado' => $idempleado, 
                                          'idproducto' => $prod[$i]['idproducto'], 
                                          'indicador' => trim($total[0]['indicador']), 
                                          'descripcion' => 'servido',
                                          'estatus' => 1, 
                                          'cantidad' => $total[0]['cantidad'], 
                                          'nota' => 'S e r v i d o', 
                                          'precio' => $total[0]['precio'], 'idcategoria'=>$total[0]['idcategoria']);
                    $cantidad += $total[0]['cantidad'];
                    $precio   += $total[0]['precio'];
                }
            }
            //$db->clsCnn();
            echo json_encode(array('order' => $resOrder, 'cantidad' => $cantidad, 'precio' => $precio, 'id'=>$id));
        }
    } else {
        //$db->clsCnn();
        echo json_encode(array('order' => $resOrder, 'cantidad' => $cantidad, 'precio' => $precio, 'id' => $id));
    }
    
}

//Pagar La Orden Actual
if ($kind == DataBase::PAY_ORDER) {
    $db = new Query(DataBase::DB);
    $idmesa = @$request->params->idmesa;
    $idempleado  = @$request->params->idempleado;
    $empleado  = @$request->params->empleado;
    $idorden = @$request->params->idorden;
    
    $ordenes_mesas = false;
    $inorder       = false;
    $mesas         = false;    
    
    $values = array('type' => 'put', 'fields' => " idempleado = 1 ", 'table' => 'mesas', 'where' => " idmesa = {$idmesa}", 'inner' => ' ');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    if ($db->put($sql) === true) {
        $mesas = true;               
        $values = array('type' => 'put', 'fields' => " estatus = 2 ", 'table' => 'inorder', 'where' => " idorden = {$idorden}", 'inner' => ' ');
        $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
        $db = new Query(DataBase::DB);
        if ($db->put($sql) === true) {        
            $inorder = true;
            $values = array('type' => 'put', 'fields' => " estatus = 2 ", 'table' => 'ordenes_mesas', 'where' => " idorden = {$idorden}", 'inner' => ' ');
            $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
            $db = new Query(DataBase::DB);
            if ($db->put($sql) === true) {                
                 $ordenes_mesas = true;        
            }            
        }
    }
    //$db->clsCnn();
    echo json_encode(array('ordenes_mesas'=>$ordenes_mesas,'inorder'=>$inorder,'mesas'=>$mesas, 'sql'=>$sql));
    
}


//Printer Issues 
if($kind == DataBase::PRINT_CENTER){
    $db->setIdmesa(@$request->params->idmesa);
    $db->setIdempleado(@$request->params->idempleado);
    $db->setNombre(@$request->params->empleado->nombres);
    $db->setIdorden(@$request->params->idorden);
    //cantidad: cantidad, idproducto: idproducto, descripcion: descripcion, precio: precio, estatus: 1, categoria: 1, size: indicador, nota: nota        
    var_dump($request->params->target);
    var_dump(@$request->params->ticket);
    // echo json_encode($db->printAllThings(@$request->params->target, @$request->params->ticket));       
    echo json_encode(array('message'=>'OK'));

    
}

//Get All Order By Filters 
if($kind == DataBase::GET_ORDERS) {
    //SELECT DISTINCT idorden FROM `inorder` WHERE YEAR(fecha)=2018 AND MONTH(fecha)=10 AND DAY(fecha)=30
    $subKind =  $db->filter($request->params->subKind,'string');
    $filterKind =  $db->filter($request->params->filterKind,'string');
    $date = explode('-',date("Y-m-d"));
    $values = array('type'   => 'get', 
                    'fields' => " DISTINCT idorden ", 
                    'table'  => ' `inorder` ', 
                    'where'  => "  YEAR(fecha)= {$date[0]} AND MONTH(fecha)={$date[1]} AND DAY(fecha)={$date[2]}", 
                    'inner'  => '');
    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
    $orders = $db->get($sql);
    //$db->clsCnn();
    if( $subKind == 'todas_current' ){
        $or = array();
        for($i=0; $i<count($orders);$i++){            
            $or[$i]['idorden'] = $orders[$i]['idorden'];            
            $values = array('type'   => 'get', 
                    'fields' => " idmesa, empleados.idempleado, estatus, nombres ", 
                    'table'  => ' ordenes_mesas ', 
                    'where'  => "  idorden = {$orders[$i]['idorden']} " , 
                    'inner'  => '  empleados ON empleados.idempleado = ordenes_mesas.idempleado ');
            $sql  = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);                        
            $mesa = array();
            $mesa = $db->get($sql);            
            $or[$i]['mesa'] = array('idmesa'=>(int)$mesa[0]['idmesa'],'idempleado'=>(int)$mesa[0]['idempleado'],'estatus'=>$mesa[0]['estatus'],'nombres'=>$mesa[0]['nombres']);
            $values = array('type' => 'get', 
                'fields' => " DISTINCT inorder.idproducto, indicador, SUM(precio) as precio, SUM(cantidad) as cantidad  ", 
                'table' => 'inorder', 
                'where' => " idorden = {$orders[$i]['idorden']} GROUP BY inorder.idproducto ", 
                'inner' => ' productos ON productos.idproducto = inorder.idproducto   ');
            $sql  = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);                                    
            $productos = array();            
            $productos =  $db->get($sql);              
            $prod = array();
            for($p=0; $p<count($productos); $p++){
                $prod[$p] = array ( 'indicador'=>$productos[$p]['indicador'],
                                    'precio'=>(int)$productos[$p]['precio'],
                                    'cantidad'=>(int)$productos[$p]['cantidad']);                                
            }
            $or[$i]['orderDetails'] = $prod;
                                                            
        }
        //$db->clsCnn();
        echo json_encode(array("order"=>$or));

        
    }
}

//Get ALL CHARTS 
if($kind == DataBase::GET_CHARTS){
    $date = explode('-',date("Y-m-d"));
    $values = array('type'   => 'get', 
                    'fields' => " DISTINCT SUM(cantidad) as cantidad, idproducto, indicador ", 
                    'table'  => ' `inorder` ', 
                    'where'  => "  YEAR(fecha)= {$date[0]} AND MONTH(fecha)={$date[1]} AND DAY(fecha)={$date[2]} GROUP BY idproducto, indicador ", 
                    'inner'  => '');
    $sql  = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);                        
    $charts = $db->get($sql);
    $orChart = array();
    if(count($charts)>0){
        for( $c=0; $c<count($charts); $c++ ){
            $orChart[$c] = array( 'cantidad'=>$charts[$c]['cantidad'],
                                  'idproducto'=>$charts[$c]['idproducto'],
                                  'indicador'=>$charts[$c]['indicador']);

        }
    }
    //$db->clsCnn();
    echo json_encode(  array('chart'=>$orChart ) );
    
                    
}

//CRUD CATALOGOS 
if($kind == DataBase::CRUD_CATALOG){
    $table     = $db->filter($request->params->type,'string');
    $action   = $db->filter($request->params->action,'string');
    $currentE = $request->params->currentE;
    $answer  = "";
    $fields = "";
    $where  = "";
    //UPDATE
    if( $action == 'put' ){        
        //EMPLEADOS 
        if( $table == 'empleados'){
            $fields = "  nombres = '{$currentE->nombres}',  apellidos = '{$currentE->apellidos}',  user = '{$currentE->user}',  pass = '{$currentE->pass}'";
            $where = " idempleado =  {$currentE->idempleado}" ;
        }else if ($table =='productos') {
            $fields= " precio = {$currentE->idproducto},  descripcion = '{$currentE->descripcion}' , precio = {$currentE->precio}";
            $where = " idproducto = {$currentE->idproducto} ";
            
        }  
        
        $status = false;    
        $values = array('type'   => 'put', 
                    'fields' => $fields, 
                    'table'  => " `{$table}` ", 
                    'where'  => $where, 
                    'inner'  => '');  
                    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
                    if( $db->put($sql) == true ){                        
                        if ($table =='productos') {
                            //UPDATE `hempmexc_ahogadas`.`indicadores` SET `opcion2` = 'Tres cuartos ahogadas' WHERE `indicadores`.`idindicadores` = 1;
                            $fields= " opcion1 = '{$currentE->opcion1}',  opcion2 = '{$currentE->opcion2}', opcion3 = '{$currentE->opcion3}', opcion4 = '{$currentE->opcion4}', opcion5 = '{$currentE->opcion5}', opcion6 = '{$currentE->opcion6}', opcion7 = '{$currentE->opcion7}', opcion8 = '{$currentE->opcion8}', opcion9 = '{$currentE->opcion9}', opcion10 = '{$currentE->opcion10}'";
                            $where = " idproducto = {$currentE->idproducto} ";
                            $status = false;    
                            $table = "indicadores";
                            $values = array('type'   => 'put', 'fields' => $fields, 'table'  => " `{$table}` ", 'where'  => $where, 'inner'  => '');  
                            $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
                            if( $db->put($sql) == true ){                                
                                $status = true;    
                                $answer = array('msg'=>"catalogo indicadores y {$table} actualizados OK ",'status'=>$status);                               
                            }                            
                            
                            
                        }else{
                            $status = true;    
                            $answer = array('msg'=>"datos actualizados",'status'=>$status);                               
                        }

                    }
    } 
    //INSERT
    else if( $action == 'ins'){        
        $status = false;   
        $date = date('Ymd');
        //EMPLEADOS
        if($table == 'empleados'){
            $fields = " idperfiles, nombres ,  apellidos ,  user ,  pass, telefono, fecha_nacimiento";
            $where = " {$currentE->idperfiles}, '{$currentE->nombres}',  '{$currentE->apellidos}', '{$currentE->user}',  '{$currentE->pass}', '', '{$date}' ";
            $values = array('type'   => 'ins', 
                    'fields' => $fields, 
                    'table'  => " `{$table}` ", 
                    'where'  => $where, 
                    'inner'  => '');  
            $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
            if( $db->put($sql) == true ){
                    $status = true;    
                    $answer = array('msg'=>"datos insertados",'status'=>$status);           
            }            
        } 
        
        else if ($table =='productos') {
            $fields= " precio, descripcion, idcategoria";
            $where = " {$currentE->precio}, '{$currentE->descripcion}', {$currentE->idcategoria} ";
            $values = array('type'   => 'ins', 
                    'fields' => $fields, 
                    'table'  => " `{$table}` ", 
                    'where'  => $where, 
                    'inner'  => '');  
            $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);
            $id = $db->getIdRow($sql);
            if(  $id['status'] == true ){                
                $fields= " opcion1, opcion2, opcion3, opcion4, opcion5, opcion6, opcion7, opcion8, opcion9, opcion10, idproducto ";
                $where = " '{$currentE->opcion1}', '{$currentE->opcion2}','{$currentE->opcion3}','{$currentE->opcion4}','{$currentE->opcion5}','{$currentE->opcion6}','{$currentE->opcion7}','{$currentE->opcion8}','{$currentE->opcion9}','{$currentE->opcion10}',{$id['id']}";
                $values = array('type'   => 'ins', 
                    'fields' => $fields, 
                    'table'  => " `indicadores` ", 
                    'where'  => $where, 
                    'inner'  => '');  
                    $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);  
                    if( $db->put($sql) == true  ){
                        $status = true;    
                        $answer = array('msg'=>"datos insertados en tabla y catalogo indicadores ",'status'=>$status);           
                        }
            }             
            
        }       
        
        
        
    } 
    //DELETESET 
    else if ( $action == 'del'){
        //EMPLEADOS
        if($table == 'empleados'){
            $fields = "";
            $where = " idempleado =  {$currentE->idempleado}" ;
        } else if( $table == 'productos'){
            $fields = "";
            $where = " idproducto =  {$currentE->idproducto}" ; 
        }
        
        $status = false;    
                    $values = array('type'   => 'del', 
                    'fields' => $fields, 
                    'table'  => " `{$table}` ", 
                    'where'  => $where, 
                    'inner'  => '');         
                                        
      $sql = $db->queryManagment($values['type'], $values['fields'], $values['table'], $values['where'], $values['inner']);  
           if( $db->put($sql) == true ){
                        $status = true;    
                        $answer = array('msg'=>"se borro el registro",'status'=>$status);   
                    }      
    }       
    //$db->clsCnn();   
    
    echo json_encode( array(  'kind'=>$kind,
                              'type'=>$table,
                              'action'=>$action,
                              'currentE'=>$currentE,
                              'answer'=>$sql
                            ));
}
$db->clsCnn();    
?>