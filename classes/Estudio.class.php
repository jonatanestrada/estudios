<?php
error_reporting(E_ALL & ~E_NOTICE);

include_once "base.api.php";
include_once "Paginacion.class.php";

class Estudio{

var $db;

function __construct() {
       $this->db = 'synapse_espejo_noc';
   }

public function getHospitales(){
	$sql = "SELECT * FROM hospital.hospital ORDER BY clave";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}   
   
public function getDetallePorHospital(){
	$noEstudiosPorHospital = $this->getNoEstudiosPorHospital();
	$m = array();

	foreach( $noEstudiosPorHospital AS $e ){
		$m[$e['fecha']][$e['clave']] = $e;
		//echo $e['fecha'].'<br>';
	}
	
	return $m;
}

public function getNoEstudiosPorHospital(){
	echo $sql = "SELECT DATE(study_Date_Time) AS fecha, clave, COUNT(*) AS noEstudios FROM estudios WHERE study_Date_Time > '2017-02-27' AND study_Date_Time < '2017-03-29' GROUP BY DATE(study_Date_Time), clave ORDER BY fecha";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}
   
public function getDetallePorModalidad(){
	$noEstudiosPorModalidad = $this->getNoEstudiosPorModalidad();
	$m = array();

	foreach( $noEstudiosPorModalidad AS $e ){
		$m[$e['fecha']][$e['modality']] = $e;
		//echo $e['fecha'].'<br>';
	}
	
	return $m;
}
   
public function getModalities(){
	//$sql = "SELECT DISTINCT(modality) FROM estudios ORDER BY modality";
	$sql = "SELECT * FROM modalidades ORDER BY alias";
	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}

public function getNoEstudiosPorModalidad(){
	$sql = "SELECT DATE(study_Date_Time) AS fecha, modality, COUNT(*) AS noEstudios FROM estudios WHERE study_Date_Time > '2017-02-27' AND study_Date_Time < '2017-03-29' GROUP BY DATE(study_Date_Time), modality ORDER BY fecha";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}

public function getNoEstudios( $noDias ){
	$diasEstudios = $this->getDiasEstudios( $noDias );
	$noEstdudiosPorDia = array();
	foreach( $diasEstudios as $fecha ){
		$noEstdudiosPorDia[] = $this->getNoEstudiosPorDia( $fecha );
	}
	
	return $noEstdudiosPorDia;
}

public function getNoEstudios2( $noDias ){
	$hoy = strtotime('now');
	$hoy = date("Y-m-d", $hoy); 
	
	$fechaInicio = strtotime ( '-'.$noDias.' day' , strtotime ( $hoy ) ) ;
	$fechaInicio = date ( 'Y-m-d' , $fechaInicio); 

	$sql = "SELECT DATE(study_Date_Time) AS fecha, COUNT(*) AS noEstudios FROM estudios WHERE study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$hoy."' GROUP BY DATE(study_Date_Time) ORDER BY fecha";
	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}

private function getDiasEstudios( $noDias ){
	$hoy = strtotime('now');
	$hoy = date("Y-m-d", $hoy); 
	//echo 'Dias2: '.$noDias;
	$diasEstudios = array();
	
	for( $i = 0; $i <= $noDias; $i++ ){
		$nuevafecha = strtotime ( '-'.$i.' day' , strtotime ( $hoy ) ) ;
		echo '<br>'.$diasEstudios[] = date ( 'Y-m-j' , $nuevafecha );
	}

	return $diasEstudios;
}
   
public function getNoEstudiosPorDia( $fecha ){
	$fechaIni = strtotime($fecha);
	$fechaIni = date("Y-m-d", $fechaIni); 
	$fechaFin = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
	$fechaFin = date ( 'Y-m-d' , $fechaFin); 

	$sql = "SELECT count(*) AS noEstudios FROM estudios WHERE study_Date_Time > '".$fechaIni."' AND study_Date_Time < '".$fechaFin."'";
	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a[0]['noEstudios'];
}   
   
public function getHorarioMiembro(){
	$sql = "SELECT * FROM estudios LIMIT 10;";
	DBO::select_db($this->db);
	return DBO::getArray($sql);
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