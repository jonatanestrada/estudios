<?php
include_once "classes/EstudioSynapse.class.php";
include_once "classes/EstudioEspejo.class.php";

$ayer = date("d-M-Y", strtotime('now -1 DAY')); 
$diasSemana = array( '',"Lunes","Martes","Miercoles","Jueves","Viernes","Sábado","Domingo");
$diaSem = $diasSemana[date("N", strtotime('now -1 DAY'))]; 

$proyecto = ( isset($_REQUEST['proyecto']) ? $_REQUEST['proyecto']: 'ISEM' );
$fechaTS = ( isset($_REQUEST['fecha']) ? $_REQUEST['fecha']: '' );
$modalidad = ( isset($_REQUEST['m']) ? $_REQUEST['m']: '' );
$type = ( isset($_REQUEST['t']) ? $_REQUEST['t']: '' );


//#f3f3f4

//Synapse
$estudioSynapse = new EstudioSynapse( $proyecto );
//$noEstudiosPorDia = $estudioSynapse->getNoEstudios( 30 );

$a = $estudioSynapse->getFaltantesDbE_Synapse( $modalidad, $fechaTS, $type );


?>
<!DOCTYPE html>
 <html lang="es">
	 <head>
		<title>Paridad Synapse - BDE</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<script type="text/javascript" src="js/jquery-3.2.0.min.js"></script>
		<style>
			.tdstyles{
			    border: 1px solid #DDDDDD;
				padding: 8px;
			}
			
			.thstyle{
				    background-color: #F5F5F6;
			}
		</style>
		
	</head>
 
	<body style='background-color: #f3f3f4; color:#676A6C;margin: 20px;'>
	
	<div style='background-color: white;padding: 15px;' >
		<h1 style='margin-bottom: 0px;'>
			Proyecto: <?php echo $proyecto; ?>
		</h1>
		<hr>
		<h2>
			Modalidad: <?php echo $modalidad; ?>
			<br>Fecha: <?php echo date('d-m-Y', $fechaTS); ?>
		</h2>
	</div><br>
	
<div style='background-color: white;padding: 15px;' >
<?php

echo '<table style="width: 100%;" cellpadding="0" cellspacing="0">';
echo '<tr>';
echo '<th class="tdstyles thstyle">Patient_ID</th>';
echo '<th class="tdstyles thstyle">Accession_No</th>';
echo '<th class="tdstyles thstyle">Modality</th>';
echo '<th class="tdstyles thstyle">Status</th>';
echo '</tr>';

foreach( $a as $e ){
	echo '<tr>';
	echo '<td class="tdstyles">'.$e['patient_ID'].'</td>';
	echo '<td class="tdstyles">'.$e['accession_No'].'</td>';
	echo '<td class="tdstyles">'.$e['modality'].'</td>';
	echo '<td class="tdstyles">'.$e['status'].'</td>';
	echo '</tr>';
}

echo '</table>';
?>
		</div>

	</body>
</html>
