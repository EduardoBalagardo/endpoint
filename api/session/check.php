<?php
session_start();
if( isset($_SESSION['uid'])  ){
    echo json_encode(array('responce'=>'autentified','session'=>$_SESSION['uid']));
} else {
    echo json_encode(array('responce'=>'not_autentified','session'=>'xxxx'));
}