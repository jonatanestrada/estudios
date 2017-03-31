<?php
include_once "classes/EstudioSynapse.class.php";
include_once "classes/EstudioEspejo.class.php";

echo $proyecto = ( isset($_REQUEST['proyecto']) ? $_REQUEST['proyecto']: 'ISEM' );
echo $fechaTS = ( isset($_REQUEST['fecha']) ? $_REQUEST['fecha']: '' );
echo $modalidad = ( isset($_REQUEST['m']) ? $_REQUEST['m']: '' );

//Synapse
$estudioSynapse = new EstudioSynapse( $proyecto );
//$noEstudiosPorDia = $estudioSynapse->getNoEstudios( 30 );

$a = $estudioSynapse->getFaltantesDbE_Synapse( $modalidad, $fechaTS );

echo '<table border=1>';
echo '<tr>';
echo '<td>Patient_ID</td>';
echo '<td>Accession_No</td>';
echo '<td>Modality</td>';
echo '<td>Status</td>';
echo '</tr>';

foreach( $a as $e ){
	echo '<tr>';
	echo '<td>'.$e['patient_ID'].'</td>';
	echo '<td>'.$e['accession_No'].'</td>';
	echo '<td>'.$e['modality'].'</td>';
	echo '<td>'.$e['status'].'</td>';
	echo '</tr>';
}

echo '</table>';