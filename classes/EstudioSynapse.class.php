<?php
error_reporting(E_ALL & ~E_NOTICE);

include_once "base.api.php";
include_once "Paginacion.class.php";

class EstudioSynapse{

var $db;
var $proyecto;

function __construct( $proyecto ) {
       $this->db = 'synapse_espejo_noc';
	   $this->proyecto = $proyecto;
   }

public function getHospitales(){
	$sql = "SELECT * FROM hospital.hospital ORDER BY clave";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}   
   
public function getDetallePorHospital( $fechaInicioTs, $fechaFinTs ){
	$noEstudiosPorHospital = $this->getNoEstudiosPorHospital( $fechaInicioTs, $fechaFinTs );
	$m = array();

	foreach( $noEstudiosPorHospital AS $e ){
		$m[$e['fecha']][$e['clave']] = $e;
		//echo $e['fecha'].'<br>';
	}
	
	return $m;
}

public function getNoEstudiosPorHospital( $fechaInicioTs, $fechaFinTs ){
	$fechaInicio = date ( 'Y-m-d' , $fechaInicioTs); 
	$fechaFin = date ( 'Y-m-d' , $fechaFinTs); 
	
	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	
	$sql = "SELECT DATE(study_Date_Time) AS fecha, clave, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY DATE(study_Date_Time), clave ORDER BY fecha";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}
   
public function getDetallePorModalidad( $fechaInicioTs, $fechaFinTs ){
	$noEstudiosPorModalidad = $this->getNoEstudiosPorModalidad( $fechaInicioTs, $fechaFinTs );
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

public function getNoEstudiosPorModalidad( $fechaInicioTs, $fechaFinTs ){
	$fechaInicio = date ( 'Y-m-d' , $fechaInicioTs); 
	$fechaFin = date ( 'Y-m-d' , $fechaFinTs); 

	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	
	$sql = "SELECT DATE(study_Date_Time) AS fecha, modality, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY DATE(study_Date_Time), modality ORDER BY fecha";

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

public function getNoEstudios2( $fechaInicioTs, $fechaFinTs ){
	$fechaInicio = date ( 'Y-m-d' , $fechaInicioTs); 
	$fechaFin = date("Y-m-d", $fechaFinTs); 
	
	
	//echo "$fechaFin, $fechaInicio";

	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	
	$sql = "SELECT DATE(study_Date_Time) AS fecha, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY DATE(study_Date_Time) ORDER BY fecha";
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

	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	
	$sql = "SELECT count(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaIni."' AND study_Date_Time < '".$fechaFin."'";
	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a[0]['noEstudios'];
}   
   
public function getHorarioMiembro(){
	$sql = "SELECT * FROM estudios LIMIT 10;";
	DBO::select_db($this->db);
	return DBO::getArray($sql);
}
   
}