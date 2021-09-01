<?php
ini_set('error_reporting', E_ALL);
require_once __CLASS__ . "DataBase.php";

class Query extends DataBase {

    public function __construct($db) {
        parent::__construct($db);
    }

    public function queryManagment($type = "", $fields, $table, $where, $inner = "") {
        $sql = "";
        if ($type == 'get') {
            $sql = "SELECT {$fields} FROM  {$table} ";
            (is_string($inner) && strlen($inner) > 0 ) ? $sql .= " INNER JOIN {$inner} " : $sql .= "";
            (is_string($where) && strlen($where) > 0 ) ? $sql .= " WHERE {$where} " : $sql .= "";
        } else if ($type == 'put') {
            $sql = "UPDATE {$table} SET {$fields} WHERE {$where}";
        } else if ($type == 'ins') {
            $sql = "INSERT INTO {$table} ({$fields}) VALUES ({$where})";
        } else if(  $type == 'del'){
            $sql = "DELETE FROM {$table} WHERE $where";
        }
        return $sql;
    }

    public function sanitizeObject($type, $arr) {

        $a = array();
        if ($type == "session") {
            for ($i = 0; $i < count($arr); $i++) {
                $a[$i] = array('idempleado' => $arr[$i]['idempleado'],
                    'idperfiles' => $arr[$i]['idperfiles'],
                    'nombres' => $arr[$i]['nombres'],
                    'apellidos' => $arr[$i]['apellidos'],
                    'user' => $arr[$i]['user']);
            }
        } 
        else if ( $type == 'mesas'      ) {
            
            for ($i = 0; $i < count($arr); $i++) {
                $a[$i] = array(
                    'idmesa'     => $arr[$i]['idmesa'],
                    'idempleado' => $arr[$i]['idempleado'],                    
                    'nombre'     => $arr[$i]['nombres']);
                    
            }
            
        } 
        else if ( $type == 'empleados'  ) {
            
            for ($i = 0; $i < count($arr); $i++) {
                $a[$i] = array('idempleado' => $arr[$i]['idempleado'],
                    'idperfiles' => $arr[$i]['idperfiles'],
                    'nombres' => $arr[$i]['nombres'],
                    'apellidos' => $arr[$i]['apellidos'],
                    'user' => $arr[$i]['user'],
                    'pass' => $arr[$i]['pass'],
                    'fecha_nacimiento' => $arr[$i]['fecha_nacimiento']);
            }
            
        } 
        else if ( $type == 'categorias' ) {
            
            for ($i = 0; $i < count($arr); $i++) {
                $a[$i] = array('idcategoria' => $arr[$i]['idcategoria'],
                    'descripcion' => $arr[$i]['descripcion']);
            }
            
        } 
        else if ( $type == 'productos'  ) {
            
            for ($i = 0; $i < count($arr); $i++) {
                $a[$i] = array('idproducto' => $arr[$i]['idproducto'],
                    'idcategoria' => $arr[$i]['idcategoria'],
                    'precio' => $arr[$i]['precio'],
                    'descripcion' => utf8_encode(utf8_decode(utf8_encode($arr[$i]['descripcion']))),
                    'opcion1' => ( trim($arr[$i]['opcion1']) == NULL ? "empty" : $arr[$i]['opcion1'] ),
                    'opcion2' => ( trim($arr[$i]['opcion2']) == NULL ? "empty" : $arr[$i]['opcion2'] ),
                    'opcion3' => ( trim($arr[$i]['opcion3']) == NULL ? "empty" : $arr[$i]['opcion3'] ),
                    'opcion4' => ( trim($arr[$i]['opcion4']) == NULL ? "empty" : $arr[$i]['opcion4'] ),
                    'opcion5' => ( trim($arr[$i]['opcion5']) == NULL ? "empty" : $arr[$i]['opcion5'] ),
                    'opcion6' => ( trim($arr[$i]['opcion6']) == NULL ? "empty" : $arr[$i]['opcion6'] ),
                    'opcion7' => ( trim($arr[$i]['opcion7']) == NULL ? "empty" : $arr[$i]['opcion7'] ),
                    'opcion8' => ( trim($arr[$i]['opcion8']) == NULL ? "empty" : $arr[$i]['opcion8'] ),
                    'opcion9' => ( trim($arr[$i]['opcion9']) == NULL ? "empty" : $arr[$i]['opcion9'] ),
                    'opcion10' => (trim($arr[$i]['opcion10']) == NULL ? "empty" : $arr[$i]['opcion10'] ));
            }
        } 
        return $a;
    }
    
    public function printAllThings ($target, $ticket ){
        date_default_timezone_set("America/Mexico_City");
        $s_l = chr(13).chr(10);
        if( count($ticket)>0 ){  		
            $printText  = " Mesa    : {$this->getIdmesa()} "  . $s_l; 
            $printText .= " Comanda : {$this->getIdorden()}"  . $s_l; 
            $printText .= " Fecha   : ".date("d-m-Y")  . '|' .  date('h:i:s') . $s_l;
            $printText .= " Mesero  : {$this->getNombre()} "   . $s_l; 
            $printText .= $s_l;                        
            $printBebidas = "";        
            $printCocina  = "";                         
  
            /**
            * resource printer_create_font ( string $face , int $height , int $width , int $font_weight , bool $italic , bool $underline , bool $strikeout , int $orientation )
            ***/             
            //{cantidad: , idproducto: idproducto, descripcion: descripcion, precio: precio, estatus: 1, categoria: 1, size: indicador, nota: nota}
            if( $target  === 'Orden'){
                foreach($ticket as $key => $some){            
                    if($some->categoria == 2 || $some->categoria == 3 ){
                        $printBebidas  .= " Cant. {$some->cantidad} " . strtoupper(trim($some->descripcion) . ' | ' .trim($some->size)) . '|' . strtolower( trim($some->nota) ) . $s_l;
                    } 
                    if($some->categoria == 1 ){                         
                        $printCocina .= " Cant. {$some->cantidad} " . strtoupper(trim($some->descripcion) . ' | ' .trim($some->size)) . '|' . strtolower( trim($some->nota) )  . $s_l;
                    }            
                }           
             
                ( strlen($printCocina)>0 ? $this->printing( $printText.$printCocina,  self::PRINTER_COCINAS) : '');
                ( strlen($printBebidas)>0 ? $this->printing($printText.$printBebidas, self::PRINTER_BEBIDAS) : '');                                                    
                echo json_encode(array("cocinaText"=>$printText.$printCocina,"bebidasText"=>$printText.$printBebidas));                
                //return array("cocinaText"=>$printText.$printCocina,"bebidasText"=>$printText.$printBebidas);
            } 
            
            if( $target === 'Pagar'){ 
                $detalleOrden = "";			
		        $keyArrays = array();        
                foreach($ticket as $key => $some){
                    $detalleOrden .= "Cantidad $some->cantidad " . ' ' .  " $some->indicador" . ' ' . $some->nota;                                        
                }
                
				// for($i=0; $i<count($ticket); $i++){								
                    
                    
    			//     // $key = $ticket[$i]["idproducto"].'|'.$ticket[$i]["size"].'|'.$ticket[$i]["indicador"].'|'.$ticket[$i]["nota"];													
                //     $key = $ticket[$i]["idproducto"].'|'.$ticket[$i]["size"].'|'.$ticket[$i]["indicador"];
				// 	if( in_array( $key, $keyArrays )  === false ){
				// 		array_push($keyArrays, $key);						
				// 	}																						
				// }
							
				// for($t=0;$t<count($keyArrays);$t++){
				// 	$expVal = explode("|",$keyArrays[$t]);
				// 	$id   = $expVal[0];
				// 	$size = $expVal[1];
				// 	$desc = $expVal[2];
                //                         //$nota = $expVal[3];
				// 	$cantidad = 0;
				// 	for($i=0; $i<count($ticket); $i++){
                //                                 //&&  $nota == $ticket[$i]['nota']
				// 		if($id == $ticket[$i]["idproducto"] && $size == $ticket[$i]["size"] && $desc == $ticket[$i]["indicador"] ){	
				// 			$cantidad++;
				// 			$nombre = $ticket[$i]["indicador"];
				// 			$size   = $ticket[$i]["size"];
				// 			//$id     = $ticket[$i]["idproducto"];																							
				// 		}
				// 	}					
				// 	$detalleOrden .= " Cant." . $cantidad . " " . strtoupper(trim($nombre) . ' | ' .( trim($size) == 'empty' ? '' : trim($size) ) ) . $s_l;
				// }	
                ( strlen($detalleOrden)>0 ? $this->printing( " ---------- TICKET ----------- " . $s_l . $printText.$detalleOrden, self::PRINTER_COCINAS) : '');							
                return array('pagado'=>true);
            }

        }                
    }
    /***
     * printing   impresora where 
     * $text      text will be impress
     * $impresora chosse where going impress
     * **/    
    private function printing($text, $impresora)    {
        //$printer = printer_open("NPI06D6B1 (HP LaserJet 500 color MFP M575)");
        $printer = printer_open($impresora);
        printer_start_doc           ($printer, 'My Document');
        printer_start_page          ($printer);
        $font = printer_create_font ('Arial',50,48,20,false,false, false,0);
        printer_select_font         ($printer, $font);
        printer_set_option          ($printer, PRINTER_MODE, 'RAW');
        printer_write               ($printer, $text);
        printer_delete_font         ($font);
        printer_end_page            ($printer);
        printer_end_doc             ($printer);
        printer_close               ($printer);  
    }    
    
}
