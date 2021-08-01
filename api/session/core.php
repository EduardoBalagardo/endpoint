<?php
//: hempmexc_ahogadas » 
require_once '../model/Query.php';
require_once '../model/DataBase.php';
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
$request = json_decode(file_get_contents("php://input"));
$db = new Query(DataBase::DB);
$db->setUser($db->filter($request->user, 'string'));
$db->setPass($db->filter($request->pass, 'string'));
$values = array('type'=>'get','fields'=>'*','table'=>'empleados','where'=>" user = '{$db->getUser()}' AND pass = '{$db->getPass()}' ", 'inner'=>'' );
$data = $db->get( $db->queryManagment($values['type'],$values['fields'], $values['table'], $values['where'], $values['inner']) );
if( count( $data )> 0 ) {
     session_start();
    $_SESSION['uid']=uniqid('ahogadas_');
    echo json_encode(array("responce"=>'success', 'session'=>$_SESSION['uid'], 'user_'=>$data)) ;   
} else {
    echo json_encode(array("responce"=>'error', 'session'=>'NOT_SESSION', 'user'=>'NoneUser')) ;
}