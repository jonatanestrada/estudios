<?php
error_reporting(E_ALL & ~E_NOTICE);

include_once "base.api.php";
include_once "Paginacion.class.php";

class Estudio{

var $db;

function __construct() {
       $this->db = 'synapse_espejo_noc';
   }
   
/*public function addMiembro( $datos ){
	//$fn = explode("/", $datos['fecha_nacimiento']);
	//var_dump($fn);
	//$fecha_nacimiento = $fn[2].'-'.$fn[0].'-'.$fn[1].' 00:00:00';
	//echo "$d, $m, $y";
	$fecha_nacimiento = $datos['fecha_nacimiento'] == '' ? "NULL" : "'".$datos['fecha_nacimiento'].' 00:00:00'."'";
	$fecha_ingreso = $datos['fecha_ingreso'] == '' ? 'CURRENT_TIMESTAMP' : "'".$datos['fecha_ingreso'].' 00:00:00'."'";
	
	$datos['nombre_sec'] = isset($datos['nombre_sec']) ? $datos['nombre_sec'] : '';
	$datos['observaciones'] = isset($datos['observaciones']) ? $datos['observaciones'] : '';
	$datos['amaterno'] = isset($datos['amaterno']) ? $datos['amaterno'] : '';
	$datos['celular'] = isset($datos['celular']) ? $datos['celular'] : '';
	$preferencia_nombre = isset($datos['preferencia_nombre']) && $datos['preferencia_nombre'] == 1 ? 1 : 0;
	$preferencia_nombre_sec = isset($datos['preferencia_nombre_sec']) && $datos['preferencia_nombre_sec'] == 1 ? 1 : 0;	

	echo $sql = "INSERT INTO miembros ( user_update, sexo, preferencia_nombre_sec, preferencia_nombre, id_puesto, nombre, nombre_sec, apaterno, amaterno, email, telefono_directo, observaciones, celular, foto, fecha_nacimiento, fecha_ingreso, active) 
	VALUES ( '".$this->idUser."', '".$datos['sexo']."', '".$preferencia_nombre_sec."', '".$preferencia_nombre."', '0', '".$datos['nombre']."', '".$datos['nombre_sec']."', '".$datos['apaterno']."', '".$datos['amaterno']."', '".$datos['email']."', '".$datos['telefono_directo']."', '".$datos['observaciones']."', '".$datos['celular']."', 'foto', ".$fecha_nacimiento.", ".$fecha_ingreso.", '1');";
//echo $sql;
  DBO::select_db($this->db);
  
  $a = DBO::insert($sql);
  
  //var_dump($a);
  
  //Response::$data->result = DBO::getArray($sql);
//  Response::showResult();
	
}

public function updatePassUserPortal( $usu_id, $password ){

	$sql = "UPDATE usuario SET usu_alias = '".$password."' WHERE usu_id = '".$usu_id."';";

	DBO::select_db('hovahlt');
	$a = DBO::doUpdate($sql);
}


public function getHorarioMiembro( $datos, $dia ){
	$sql = "SELECT * FROM horarios h INNER JOIN descripciones d ON d.id_descripcion = h.id_descripcion WHERE h.id_miembro = '".$datos['id']."' AND h.dia = '".$dia."'";
	DBO::select_db($this->db);
	return DBO::getArray($sql);
}
*/

}