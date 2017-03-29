<?php
include_once "classes/Estudio.class.php";
include_once "date_functions.php";
date_default_timezone_set('America/Mexico_City');
$archivosEstudios = getFilesEstudios();
?>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<table border=1>
<?php /*	<tr>
		<td>Base</td>
		<td>Patient ID</td>
		<td>Accession No</td>
		<td>Modality</td>
		<td>Status</td>
		<td>Procedure Code</td>
		<td>Image Count</td>
		<td>Patient Name</td>
		<td>Study Date Time	Last</td>
		<td>Modified Timestamp</td>
		<td>Birth Date</td>
		<td>Gender</td>
		<td>Image Sender</td>
		<td>Clave</td>
		<td>Clave-Mod</td>
		<td>SD</td>
	</tr>*/
	
?>

	<tr>
		<td>Archivo</td>
		<td>Status</td>
	</tr>

<?php // Ejemplo aprenderaprogramar.com
// Iremos leyendo línea a línea del fichero.txt hasta llegar al fin (feof($fp))
// fichero.txt tienen que estar en la misma carpeta que el fichero php
// fichero.txt es un archivo de texto normal creado con notepad, por ejemplo.


$connection = mysqli_connect('localhost', 'root', '', 'synapse_espejo_noc');

foreach ( $archivosEstudios as $file ){
	$fp = fopen( $file, "r");
	while(!feof($fp)) {
		$linea = fgets($fp);
		if( trim($linea) != '' )
			{
				$fields = getFields($linea);
				//echo writeRowHTML( $fields );
				saveEstudioDB( $connection, $fields );
		}
	}
	fclose($fp);
	echo '<tr>';
	echo '<td>'.$file.'</td>';
	echo '<td>Completado</td>';
	echo '</tr>';
}
?>

</table>


<?php

function saveEstudioDB( $connection, $data ){
	$estudio = new Estudio( $connection );
	$estudio->add( $data );
}

function writeRowHTML( $fields ){
	$html = '<tr>';
	$html .= '<td>'.$fields['isValid'].'</td>';
	$html .= '<td>'.$fields['patient_ID'].'</td>';
	$html .= '<td>'.$fields['accession_No'].'</td>';
	$html .= '<td>'.$fields['modality'].'</td>';
	$html .= '<td>'.$fields['status'].'</td>';
	$html .= '<td>'.$fields['procedure_Code'].'</td>';
	$html .= '<td>'.$fields['image_Count'].'</td>';
	$html .= '<td>'.$fields['patient_Name'].'</td>';
	$html .= '<td>'.$fields['study_Date_Time_Last'].'</td>';
	$html .= '<td>'.$fields['last_Modified'].'</td>';
	$html .= '<td>'.$fields['fecha_nacimiento'].'</td>';
	$html .= '<td>'.$fields['gender'].'</td>';
	$html .= '<td>'.$fields['imageSender'].'</td>';
	$html .= '<td>'.$fields['clave'].'</td>';
	$html .= '<td>'.getFieldClaveMod( $fields ).'</td>';
	$html .= '<td>'.getFormatDDMMYYHHSS($fields['study_Date_Time_Last']).'</td>';
	$html .= '</tr>';
	return $html;
}

function getFieldClaveMod( $fields ){
	return $fields['clave'].'-'.$fields['modality'];
}

function getFIeldSD( $fields ){
	return getUnixTime($fields['study_Date_Time_Last']);
}



function getFieldClave( $accession_No ){
/*= SI(
		NO(
		
		O(	
			IZQUIERDA([@[Accession No]],4)="ISEM",
			IZQUIERDA([@[Accession No]],4)="MICH",
			IZQUIERDA([@[Accession No]],4)="JALI",
			IZQUIERDA([@[Accession No]],4)="TLAX")
		)
		
		,
		"Sin Clave",
		IZQUIERDA([@[Accession No]],6)
	)*/
	$proyecto = substr( $accession_No, 0, 4);

	$patron1 = "/ISEM/i";
	$patron2 = "/MICH/i";
	$patron3 = "/JALI/i";
	$patron4 = "/TLAX/i";
	
	if ( preg_match($patron1, $proyecto) OR preg_match($patron2, $proyecto) OR preg_match($patron3, $proyecto) ) {
		$clave = substr( $accession_No, 0, 6);
		return $clave;
	}
	else
		return 'Sin Clave';
}

function getFieldBase( $patient_Name ){
/*= SI( Y 
		(SI.ERROR(NO(
			HALLAR("test",[@[Patient Name]])=0),FALSO)=FALSO,SI.ERROR(NO(
			HALLAR("prueba",[@[Patient Name]])=0),FALSO)=FALSO,SI.ERROR(NO(
			HALLAR("hova",[@[Patient Name]])=0),FALSO)=FALSO,NO(O([@Status]="Status",[@Status]="Cancel",*/
			
	$cadena1 = $patient_Name;
	$patron1 = "/test/i";
	$patron2 = "/prueba/i";
	$patron3 = "/hova/i";
	
	if ( preg_match($patron1, $cadena1) OR preg_match($patron2, $cadena1) OR preg_match($patron3, $cadena1) ) {
		return 0;
		//return 'Eliminado';
	}
	else
		return 1;
		//return 'Validado';
	
}

function FormatMysql( $date ){
	$c = strtotime( $date );
	return ( $date != '' ) ? date("Y-m-d", $c): '';
}

function getFields( $line ){
	$l = explode("\t", $line );

	
	$fields['patient_ID']			= $l[0];
	$fields['accession_No']			= $l[1];
	$fields['modality']				= $l[2];
	$fields['status']				= $l[3];
	$fields['procedure_Code']		= addslashes($l[4]);
	$fields['image_Count']			= $l[5];
	$fields['patient_Name']			= addslashes($l[6]);
	$fields['study_Date_Time_Last']	= getUnixTime($l[7]);
	$fields['last_Modified']		= getUnixTime($l[8]); 
	//$fields['birth_Date']			= ( trim($l[9]) != '' ) ? "'".getUnixTime($l[9]." 00:00:00")."'" : 'NULL';
	$fields['birth_Date']				= '';//( trim($l[9] != '') ) ? "'".z($l[9].' 00:00:00')."'" : 'NULL';
	if( isset($l[9]) )
		$fields['fecha_nacimiento']	= ( trim($l[9] != '') ) ? "'".FormatMysql($l[9])."'" : 'NULL';
	else
		$fields['fecha_nacimiento']	="NULL";
	
	$fields['gender']				= ( isset($l[10]) ) ? $l[10]:'';
	$fields['imageSender']			= ( isset($l[11]) ) ? $l[11]:'';
	$fields['clave']				= getFieldClave( $fields['accession_No'] );
	$fields['isValid']				= getFieldBase( $fields['patient_Name'] );

	return $fields;
}


function getFilesEstudios(){
	$dir = "archivos_estudios";
	$directorio = opendir( $dir ); //ruta actual
	
	$archivosEstudios = array();

	while ($archivo = readdir($directorio)) //obtenemos un archivo y luego otro sucesivamente
	{
		if (is_dir($archivo))//verificamos si es o no un directorio
		{
			//echo "[".$archivo . "]<br />"; //de ser un directorio lo envolvemos entre corchetes
		}
		else
		{
			if( substr( $archivo, -4 ) == ".txt" )
				$archivosEstudios[] = $dir.'/'.$archivo;
		}
	}
	return $archivosEstudios;
}

?>