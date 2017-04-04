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

public function getFaltantesDbE_Synapse( $modalidad, $fechaTS, $type, $periodo ){
	$fechaIni = date("Y-m-d", $fechaTS);
	if( $periodo == 3 )
		$fechaFin = date("Y-m-d", strtotime("$fechaIni +1 month"));
	else
		$fechaFin = date("Y-m-d", strtotime("$fechaIni +1 day"));

	$m = ( $type == 1 ) ? " modality LIKE '%".$modalidad."%' AND " : '';

	echo $sql = "SELECT * FROM estudios WHERE ".$m." clave LIKE '%".$this->proyecto."%' AND study_Date_Time > '".$fechaIni."' AND study_Date_Time < '".$fechaFin."' AND enEspejo = 0 LIMIT 5000;";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}
   
/*public function getFaltantesDbE_Synapse( $modalidad, $fechaTS ){
$fechaIni = date("Y-m-d", $fechaTS);
$fechaFin = date("Y-m-d", strtotime("$fechaIni +1 day"));

	$sql = "SELECT * FROM estudios e
LEFT JOIN monitoreo_activo.productividad_tr ptr ON ptr.id = e.accession_No
WHERE e.modality LIKE '%".$modalidad."%' AND e.clave LIKE '%".$this->proyecto."%' AND e.study_Date_Time > '".$fechaIni."' AND e.study_Date_Time < '".$fechaFin."' AND ptr.id IS NULL

UNION

SELECT * FROM monitoreo_activo.productividad_tr ptr
LEFT JOIN synapse_espejo_noc.estudios s ON s.accession_No = ptr.id

WHERE ptr.modalidad LIKE '%".$modalidad."%' AND ptr.fecha_estudio > '".$fechaIni."' AND ptr.fecha_estudio < '".$fechaFin."' AND ptr.sitio LIKE '%".$this->proyecto."%' AND s.accession_No IS null";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}*/
   
public function getHospitales( $proyecto ){
	$sql = "SELECT * FROM hospital.hospital WHERE clave LIKE '%".$proyecto."%' ORDER BY clave";

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}   
   
public function getDetallePorHospital( $fechaInicioTs, $fechaFinTs, $periodo ){
	$noEstudiosPorHospital = $this->getNoEstudiosPorHospital( $fechaInicioTs, $fechaFinTs, $periodo );
	$m = array();

	if( $periodo == 3 ){
		foreach( $noEstudiosPorHospital AS $e ){
			$m[$e['mes']][$e['clave']] = $e;
			//echo $e['fecha'].'<br>';
		}
	}
	else{
		foreach( $noEstudiosPorHospital AS $e ){
			$m[$e['fecha']][$e['clave']] = $e;
			//echo $e['fecha'].'<br>';
		}
	}
	
	return $m;
}

public function getNoEstudiosPorHospital( $fechaInicioTs, $fechaFinTs, $periodo ){
	$fechaInicio = date ( 'Y-m-d' , $fechaInicioTs); 
	$fechaFin = date ( 'Y-m-d' , $fechaFinTs); 
	
	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	
	if( $periodo == 3 )
		echo $sql = "SELECT Year(study_Date_Time) AS year, Month(study_Date_Time) AS mes, clave, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY Year(study_Date_Time), Month(study_Date_Time), clave ORDER BY year, mes";
	else
		$sql = "SELECT DATE(study_Date_Time) AS fecha, clave, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY DATE(study_Date_Time), clave ORDER BY fecha";
	

	DBO::select_db($this->db);
	$a = DBO::getArray($sql);
	return $a;
}
   
public function getDetallePorModalidad( $fechaInicioTs, $fechaFinTs, $periodo ){
	$noEstudiosPorModalidad = $this->getNoEstudiosPorModalidad( $fechaInicioTs, $fechaFinTs, $periodo );
	
	$m = array();

	if( $periodo == 3 ){
		foreach( $noEstudiosPorModalidad AS $e ){
			$m[$e['mes']][$e['modality']] = $e;
			//echo $e['mes'].'<br>';
		}
	}
	else{
		foreach( $noEstudiosPorModalidad AS $e ){
			$m[$e['fecha']][$e['modality']] = $e;
			//echo $e['fecha'].'<br>';
		}
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

public function getNoEstudiosPorModalidad( $fechaInicioTs, $fechaFinTs, $periodo ){
	$fechaInicio = date ( 'Y-m-d' , $fechaInicioTs); 
	$fechaFin = date ( 'Y-m-d' , $fechaFinTs); 

	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	
	if( $periodo == 3 )
		$sql = "SELECT Year(study_Date_Time) AS year, Month(study_Date_Time) AS mes, modality, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY Year(study_Date_Time), Month(study_Date_Time), modality ORDER BY year, mes";
	else
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

public function getNoEstudios2( $fechaInicioTs, $fechaFinTs, $periodo ){
	$fechaInicio = date ( 'Y-m-d' , $fechaInicioTs); 
	$fechaFin = date("Y-m-d", $fechaFinTs); 
	
	
	//echo "$fechaFin, $fechaInicio";

	$whereProyecto = ( $this->proyecto != '' ) ? " clave LIKE '%".$this->proyecto."%' " : '1';
	if( $periodo == 3 )
		$sql = "SELECT Year(study_Date_Time) AS year, Month(study_Date_Time) AS mes, COUNT(*) AS noEstudios FROM estudios WHERE ".$whereProyecto." AND study_Date_Time > '".$fechaInicio."' AND study_Date_Time < '".$fechaFin."' GROUP BY Year(study_Date_Time), Month(study_Date_Time) ORDER BY year, mes";
	else
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
		$diasEstudios[] = date ( 'Y-m-j' , $nuevafecha );
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