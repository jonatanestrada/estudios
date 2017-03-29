<?php

function getUnixTime( $date ){
	return strtotime($date);	
}

function getFormatDDMMYYHHSS( $unixTime  ){
	return ( $unixTime != '' )? date("d/m/Y (g:i a)", $unixTime ) : '';
}

function getFormatInsertMysqlDate( $unixTime ){
	return ( $unixTime != '' )? date("Y-m-d H:i:s", $unixTime ) : '';
}

function convertDateFormatDDMMYY( $date ){
	$fechaHora = explode(" ", $date);
	$d = explode("/", $fechaHora[0]);

	$dia = $d[0];
	$mes = $d[1];
	$anio = $d[2];

	$fecha = $mes.'/'.$dia.'/'.$anio;
	return $fecha.' '.$fechaHora[1].' '.$fechaHora[2];
}

function isDateformatDDMMYY( $date ){
	$fechaHora = explode(" ", $date);
	$d = explode("/", $fechaHora[0]);

	$dia = $d[0];
	$mes = $d[1];
	$anio = $d[2];

	if(checkdate( $mes, $dia, $anio))
		return true;
	else
		return false;
}

?>