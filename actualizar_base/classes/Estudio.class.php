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
		if( $id_estudio = $this->existAccessionNo( $datos ) ){
			$this->updateRow( $id_estudio, $datos );
		}
		else
			$this->insertDb( $datos );
	}

	public function updateRow( $id_estudio, $datos ){
		$enEspejo = ( $this->existInEspejoDB( $datos['accession_No'] ) ) ? 1 : 0;
	
		$sql = "UPDATE estudios SET
		enEspejo = '".$enEspejo."',
		is_valid = '".$datos['isValid']."',
		modality = '".$datos['modality']."', 
		status = '".$datos['status']."', 
		procedure_Code = '".$datos['procedure_Code']."', 
		image_Count = '".$datos['image_Count']."', 
		patient_Name = '".$datos['patient_Name']."', 
		study_Date_Time = '".getFormatInsertMysqlDate($datos['study_Date_Time_Last'])."', 
		last_Modified_Timestamp = '".getFormatInsertMysqlDate($datos['last_Modified'])."', 
		gender = '".$datos['gender']."', 
		clave = '".$datos['clave']."', 
		imageSender = '".$datos['imageSender']."',
		birth_Date = ".$datos['fecha_nacimiento']."
		WHERE id_estudio = '".$id_estudio."';";

		if (!mysqli_query($this->connection, $sql))
		  {
			echo("SQL: ".$sql."<br>Error description: " . mysqli_error($this->connection)).'<br><br>';
		  }
	}

	public function existAccessionNo( $datos ){	
		$query = "SELECT * FROM estudios WHERE accession_No LIKE '".$datos['accession_No']."';" or die("Error in the consult.." . mysqli_error($this->connection));    
		$resultado = $this->connection->query($query);
		$num_rows = $resultado->num_rows; 
		
		if( $num_rows > 0 ){ //Existe
			$row = mysqli_fetch_array($resultado);
			return $row['id_estudio'];
		}
		else //No existe
			return false;
	}
	
	public function existInEspejoDB( $id ){
		$sql = "SELECT * FROM monitoreo_activo.productividad_tr WHERE id LIKE '".$id."'";
		$resultado = $this->connection->query($sql);
		$num_rows = $resultado->num_rows; 

		if( $num_rows > 0 ){ //Existe
			$row = mysqli_fetch_array($resultado);
			return $row['id'];
		}
		else //No existe
			return false;
	}
 
	public function insertDb( $datos ){
		/*$datos['base']						= $l[0];
		$datos['patient_ID']				= $l[1];
		$datos['accession_No']				= $l[2];
		$datos['modality']					= $l[3];
		$datos['status']					= $l[4];
		$datos['procedure_Code']			= $l[5];
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

		$enEspejo = ( $this->existInEspejoDB( $datos['accession_No'] ) ) ? 1 : 0;
	
		//$datos['observaciones'] = isset($datos['observaciones']) ? $datos['observaciones'] : 'NULL';
		$sql = "INSERT INTO estudios ( is_valid, patient_ID, accession_No, modality, status, procedure_Code, image_Count, patient_Name, study_Date_Time, last_Modified_Timestamp, birth_Date, 
		gender, clave, imageSender, enEspejo ) 
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
			'".$datos['imageSender']."',
			'".$enEspejo."'
			);";

		if (!mysqli_query($this->connection, $sql))
		  {
			echo("SQL: ".$sql."<br>Error description: " . mysqli_error($this->connection)).'<br><br>';
		  }

	}
}