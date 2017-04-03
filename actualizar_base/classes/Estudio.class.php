<?php
//include_once "conexion.php";
include_once "date_functions.php";

class Estudio{

var $db;
var $connection;

function __construct( $connection ) {
       $this->db = 'synapse_espejo_noc';
	   $this->connection = $connection;
   }

public function add( $datos ){	

	/*$datos['base']						= $l[0];
	$datos['patient_ID']				= $l[1];
	$datos['accession_No']				= $l[2];
	$datos['modality']					= $l[3];
	$datos['status']					= $l[4];
	$datos['procedureCode']			= $l[5];
	$datos['imageCount']				= $l[6];
	$datos['patientName']				= $l[7];
	$datos['studyDateTime']			= $l[8];
	$datos['last_Modified']	= $l[9];
	$datos['birthDate']				= $l[10];
	$datos['gender']					= $l[11];
	$datos['x']						= $l[12];
	$datos['x2']						= $l[13];
	$datos['clave']					= $l[14];
	$datos['claveMod']					= $l[15];
	$datos['SD']						= $l[16];
	$datos['SdDate']					= $l[17];
	$datos['SdHr']						= $l[18];
	$datos['SdWeekday']				= $l[19];
	$datos['SdYY_MM']					= $l[20];
	$datos['LM']						= $l[21];
	$datos['LmDate']					= $l[22];
	$datos['LM_YY_MM']					= $l[23];*/
//var_dump($datos);

	//$datos['observaciones'] = isset($datos['observaciones']) ? $datos['observaciones'] : 'NULL';
	$sql = "INSERT INTO estudios ( is_valid, patient_ID, accession_No, modality, status, procedure_Code, image_Count, patient_Name, study_Date_Time, last_Modified_Timestamp, birth_Date, 
	gender, clave, imageSender ) 
	VALUES 
	( 	'".$datos['isValid']."', 
		'".$datos['patient_ID']."', 
		'".$datos['accession_No']."', 
		'".$datos['modality']."', 
		'".$datos['status']."', 
		'".$datos['procedure_Code']."', 
		'".$datos['image_Count']."', 
		'".$datos['patient_Name']."', 
		'".getFormatInsertMysqlDate($datos['study_Date_Time_Last'])."', 
		'".getFormatInsertMysqlDate($datos['last_Modified'])."', 
		".$datos['fecha_nacimiento'].", 
		'".$datos['gender']."', 
		'".$datos['clave']."',
		'".$datos['imageSender']."'
		);";
//echo $sql.'<br>';
	/*DBO::select_db($this->db);  
	$a = DBO::insert($sql);  	
	if(!$a)
		echo $sql.'<br>';*/
		
		



if (!mysqli_query($this->connection, $sql))
  {
  echo("SQL: ".$sql."<br>Error description: " . mysqli_error($this->connection)).'<br><br>';
  }



		
		
	
}




}