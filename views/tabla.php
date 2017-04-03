<?php
include_once "../classes/EstudioSynapse.class.php";
include_once "../classes/EstudioEspejo.class.php";
date_default_timezone_set('America/Mexico_City');
setlocale(LC_TIME,"es_MX");

$proyecto = ( isset($_REQUEST['proyecto']) ? $_REQUEST['proyecto']: '' );
$periodo = ( isset($_REQUEST['periodo']) ? $_REQUEST['periodo']: '' );

//Synapse
$estudioSynapse = new EstudioSynapse( $proyecto );
//$noEstudiosPorDia = $estudioSynapse->getNoEstudios( 30 );


$peridoFechas = getPeridoFechas( $periodo );
$fechaFinTs = $peridoFechas['fechaFinTs'];
$fechaInicioTs = $peridoFechas['fechaInicioTs'];
$dias = $peridoFechas['dias'];

$noEstudiosPorDia = $estudioSynapse->getNoEstudios2( $fechaInicioTs, $fechaFinTs );
$detallePorModalidad = $estudioSynapse->getDetallePorModalidad( $fechaInicioTs, $fechaFinTs );
$detallePorHospital = $estudioSynapse->getDetallePorHospital( $fechaInicioTs, $fechaFinTs );
$hospitales = $estudioSynapse->getHospitales( $proyecto );
$modalidades = $estudioSynapse->getModalities();

//Espejo
$estudioEspejo = new EstudioEspejo( $proyecto );
$noEstudiosPorDiaEspejo = $estudioEspejo->getNoEstudios( $fechaInicioTs, $fechaFinTs );
$detallePorModalidadEspejo = $estudioEspejo->getDetallePorModalidad( $fechaInicioTs, $fechaFinTs );
$detallePorHospitalEspejo = $estudioEspejo->getDetallePorHospital( $fechaInicioTs, $fechaFinTs );

?>
			<table cellpadding="0" cellspacing="0" style='margin: auto;'>
				<tr>
					<td style='width: 105px; height: 20px;'>&nbsp;</td>
					<td style='width: 220px;'>&nbsp;</td>
					<td></td>
					<?php 
					foreach( $dias AS $d ){
						echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
					}
					
					/*foreach( $diasEstudios as $d ){
						echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
					}*/
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;' >Total</td>
				</tr>
				<tr>
					<td >Clave</td>
					<td>Hospital</td>
					<td></td>
					<?php 
					foreach( $dias AS $d ){
						echo '<td class="celdasNumeros bold">'.$d['mes'].'</td>';
					}
					?>
				</tr>
				<tr>
				<td>&nbsp;</td>
				</tr>
				<tr>
					<td class='titleTable bordes' style='border-right: none; border-bottom: none;' >Total</td>
					<td class='titleTable bordes' style='border-left: none; border-bottom: none;' >ISEM</td>
					<td style='width: 20px;'></td>
					<?php
						$sumaSynapse = $sumaEspejo = 0;
						$mas100 = 0;
						$igual100 = 0;
						$r50_100 = 0;
						$r0_50 = 0;
						foreach( $noEstudiosPorDia AS $s ){
							$fecha = date("Ymd", strtotime($s['fecha']));
							$synapse = $s['noEstudios'];
							$sumaSynapse += $synapse;
							$espejo = $noEstudiosPorDiaEspejo[$fecha]['noEstudios'];
							$sumaEspejo += $espejo;
							$parity = getParity( $synapse, $espejo );
							$mas100 	= ( $parity > 100 ) ? ( $mas100 + 1 ) : $mas100;
							$igual100 	= ( $parity == 100 ) ? ( $igual100 + 1 ) : $igual100;
							$r50_100 	= ( $parity > 49 && $parity < 100 ) ? ( $r50_100 + 1 ) : $r50_100;
							$r0_50 		= ( ($parity >= 0 && $parity < 50) || $parity < 0 ) ? ( $r0_50 + 1 ) : $r0_50;
							$classNivelAlerta = getClaseNivelAlerta( $parity );
							echo "<td class='".$classNivelAlerta." celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>".round($parity)."%</td>";
						}
					?>
					<td></td>
					<?php
						$paritySuma = getParity( $sumaSynapse, $sumaEspejo );
						$classNivelAlerta = getClaseNivelAlerta( $paritySuma );
						echo '<td class="'.$classNivelAlerta.' bordes sinBordeAbajo celdasNumerosSolo1Col">'.round($paritySuma).'%</td>';
					?>

					<td style='width: 10px;'></td>
					<td class="nivelAlertaSobrepaso bordes sinBordeAbajo celdasNumerosSolo1Col">100></td>
					<td class="nivelAlerta100 bordes sinBordeAbajo celdasNumerosSolo1Col">100=</td>
					<td class="nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col">50-100</td>
					<td class="nivelAlertaMuyporDebajo bordes sinBordeAbajo celdasNumerosSolo1Col">0-50</td>
					
				</tr>
				
				<tr class='viewMore'>
					<td class='bordes' style='border-bottom: none; border-right: none; ' ></td>		
					<td class='bordes alinIza' style='border-bottom: none; border-left: none; ' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					$sumaNoEstudios = 0;
						foreach( $noEstudiosPorDia AS $e ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';						
						$sumaNoEstudios += $e['noEstudios'];						
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.number_format($e['noEstudios']).'</td>';
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudios); ?></td>
					
					<td style='width: 10px;'></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo $mas100; ?></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo $igual100; ?></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo $r50_100; ?></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo $r0_50; ?></td>
				</tr>
				</tr>
				
				<tr class='viewMore' >
					<td class='bordes ' style='border-right: none; border-top: none;' ></td>		
					<td class='bordes alinIza' style='border-left: none; border-top: none;' >BDE - Central</td>
					<td></td>
					<?php 
					$i = 0;
					$sumaNoEstudios = 0;
						foreach( $noEstudiosPorDiaEspejo AS $e ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';						
						$sumaNoEstudios += $e['noEstudios'];						
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.number_format($e['noEstudios']) .'</td>';
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudios); ?></td>
				</tr>
				
				<tr>
					<td>
						&nbsp;
					</td>
				</tr>
					<tr>
					<td colspan=2 rowspan = 2 style='text-align: center; font-size: 18px; font-weight: bold; border: solid; border: none;  border-bottom: solid; border-color: #70AD47;'>
						Detalle Por Modalidad
					</td>
					<td></td>
					<?php 
					foreach( $dias AS $d ){
						echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
					}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;'>Total</td>
				</tr>
				
				<tr>
					<td></td>
					<?php 
					foreach( $dias AS $d ){
						echo '<td class="celdasNumeros bold">'.$d['mes'].'</td>';
					}
					?>
				</tr>

				<tr>
					<td>
						&nbsp;
					</td>
				</tr>

				<?php foreach( $modalidades AS $d ): ?>
						
				<tr>
					<td style='text-align:center; background-color: #E2EFDA; border-right: none;' class='bordes' >
						<?php echo $d['alias']; ?>
					</td>
					<td style='text-align:left; background-color: #E2EFDA; border-left: none;' class='bordes'>
						<?php echo $d['modalidad']; ?>
					</td>
					<td></td>
					
					<?php 
					$i = 0;
					$sumaNoEstudiosModalidad = 0;
					foreach( $detallePorModalidad as $de ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						$fecha = date("Ymd", strtotime($de[$d['alias']]['fecha']));
						$modalidad = $d['alias'];
						$synapse = $de[$d['alias']]['noEstudios'];
						$sumaSynapseM += $synapse;
						$espejo = $detallePorModalidadEspejo[$fecha][$d['alias']]['noEstudios'];
						$sumaEspejoM += $espejo;
						$parity = getParity( $synapse, $espejo );
						$classNivelAlerta = getClaseNivelAlerta( $parity );
						//echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$noEstudios.'</td>';
						$url = 'diferencias.php?m='.$modalidad.'&fecha='.strtotime($de[$d['alias']]['fecha']).'&proyecto='.$proyecto;
						echo '<td class="'.$firtCell.' '.$classNivelAlerta.' nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft" style="border-left: none;"><a class="linkVerDifEst" href="'.$url.'" target="_blank">'.round($parity).'%</a></td>';
						$sumaNoEstudiosModalidad += $de[$d['alias']]['noEstudios'];
					}
					?>
					<td></td>
					<?php
					$parityM = getParity( $sumaSynapseM, $sumaEspejoM );
					$classNivelAlertaM = getClaseNivelAlerta( $parityM );
					echo '<td class="'.$classNivelAlertaM.' bordes sinBordeAbajo celdasNumerosSolo1Col">'.round($parityM).'%</td>';
					?>
					
				</tr>
				
				<tr class='viewMore'>
					<td class='bordes bordeCeldaFirstIzq' style='border-bottom: none;' ></td>
					<td class='bordes bordeCeldaLastDer alinIza' style='border-bottom: none;' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					$sumaNoEstudiosModalidad = 0;
					foreach( $detallePorModalidad as $de ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						$noEstudios = ( isset( $de[$d['alias']]['noEstudios'] ) ) ? number_format($de[$d['alias']]['noEstudios']): '-';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$noEstudios.'</td>';
						$sumaNoEstudiosModalidad += $de[$d['alias']]['noEstudios'];
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudiosModalidad); ?></td>
				</tr>
				<tr class='viewMore'>
					<td class='bordes bordeCeldaFirstIzq'></td>
					<td class='bordes bordeCeldaLastDer alinIza'>DBE - Central</td>
					<td></td>
					
					<?php 
					$i = 0;
					$sumaNoEstudiosModalidad = 0;
					foreach( $detallePorModalidadEspejo as $de ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						$noEstudios = ( isset( $de[$d['alias']]['noEstudios'] ) ) ? number_format($de[$d['alias']]['noEstudios']): '-';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$noEstudios.'</td>';
						$sumaNoEstudiosModalidad += $de[$d['alias']]['noEstudios'];
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudiosModalidad); ?></td>
				</tr>
				
				<?php endforeach; ?>
				
				

				<tr>
					<td>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td colspan=2 rowspan = 2 style='text-align: center; font-size: 18px; font-weight: bold; border: solid; border: none;  border-bottom: solid; border-color: #70AD47;'>
						Detalle Por Hospital
					</td>
					<td></td>
					<?php 
					foreach( $dias AS $d ){
						echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
					}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;'>Total</td>
				</tr>
				
				<tr>
					<td></td>
					<?php 
					foreach( $dias AS $d ){
						echo '<td class="celdasNumeros bold">'.$d['mes'].'</td>';
					}
					?>
				</tr>

				<tr>
					<td>
						&nbsp;
					</td>
				</tr>

				<?php foreach( $hospitales as $h ): ?>
				<tr>
					<td style='text-align:center; background-color: #EDEDED; border-right: none;' class='bordes' >
						<?php echo $h['clave']; ?>
					</td>
					<td style='text-align:left;background-color: #EDEDED; border-left: none;' class='bordes'>
						<?php echo $h['nombre_corto']; ?>
					</td>
					<td></td>

					<?php 
					$i = 0;
					$sumaNoEstudiosHospital = 0;
					$espejo = $synapse = $sumaSynapse = $sumaEspejo = 0;
					foreach( $detallePorHospital as $deH ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : ''; //$detallePorHospital['2017-02-27']['ISEM01']['noEstudios']
						//$noEstudios = ( isset( $deH[$h['clave']]['noEstudios'] ) ) ? number_format($deH[$h['clave']]['noEstudios']): '-';
						$fecha = date("Ymd", strtotime($deH[$h['clave']]['fecha']));
						
						$synapse = $deH[$h['clave']]['noEstudios'];
						$sumaSynapse += $synapse;
						$espejo = $detallePorHospitalEspejo[$fecha][$h['clave']]['noEstudios'];
						$sumaEspejo += $espejo;
						$parity = getParity( $synapse, $espejo );
						$classNivelAlerta = getClaseNivelAlerta( $parity );
						echo '<td class="'.$firtCell.' '.$classNivelAlerta.' nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft" style="border-left: none;">'.round($parity).'%</td>';
						$sumaNoEstudiosHospital += $deH[$h['clave']]['noEstudios'];
					}
					?>
					<td></td>
					<?php
						$parityH = getParity( $sumaSynapse, $sumaEspejo );
						$classNivelAlertaH = getClaseNivelAlerta( $parityH );
						echo '<td class="'.$classNivelAlertaH.' bordes sinBordeAbajo celdasNumerosSolo1Col">'.round($parityH).'%</td>';
					?>
				</tr>
				
				<tr class='viewMore'>
					<td class='bordes bordeCeldaFirstIzq' style='border-bottom: none;' ></td>
					<td class='bordes bordeCeldaLastDer alinIza' style='border-bottom: none;' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					$sumaNoEstudiosHospital = 0;
					foreach( $detallePorHospital as $deH ){
					
					//var_dump($de); exit;
					
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : ''; //$detallePorHospital['2017-02-27']['ISEM01']['noEstudios']
						$noEstudios = ( isset( $deH[$h['clave']]['noEstudios'] ) ) ? number_format($deH[$h['clave']]['noEstudios']): '-';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$noEstudios.'</td>';
						$sumaNoEstudiosHospital += $deH[$h['clave']]['noEstudios'];
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudiosHospital); ?></td>
				</tr>
				<tr class='viewMore'>
					<td class='bordes bordeCeldaFirstIzq'></td>
					<td class='bordes bordeCeldaLastDer alinIza' >DBE - Central</td>
					<td></td>
					<?php 
					$i = 0;
					$sumaNoEstudiosHospital = 0;
					foreach( $detallePorHospitalEspejo as $deH ){
					
					//var_dump($de); exit;
					
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : ''; //$detallePorHospital['2017-02-27']['ISEM01']['noEstudios']
						$noEstudios = ( isset( $deH[$h['clave']]['noEstudios'] ) ) ? number_format($deH[$h['clave']]['noEstudios']): '-';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$noEstudios.'</td>';
						$sumaNoEstudiosHospital += $deH[$h['clave']]['noEstudios'];
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudiosHospital); ?></td>
				</tr>
				<?php endforeach; ?>
				
			</table>

<?php

function getParity( $synapse, $DBE ){
	if( $synapse == 0 && $DBE == 0 )
		return 100;
	if( $synapse > 0 && $DBE == 0 )
		return 0;
	if( $synapse == 0 && $DBE > 0 )
		return 100;
	if( $synapse > 0 && $DBE > 0 )
		return ($synapse / $DBE)*100;
}

function getClaseNivelAlerta( $parity ){
	if( $parity == 100 ) //Gris
		return 'nivelAlerta100';
	if( $parity > 49 && $parity < 100 ) //Amarillo;
		return 'nivelAlertaNormal';		
	if( $parity >= 0 && $parity < 50 ) //Rojo
		return 'nivelAlertaMuyporDebajo';
	if( $parity < 0 ) //Rojo
		return 'nivelAlertaMuyporDebajo';
	if( $parity > 100 ) //Azul
		return 'nivelAlertaSobrepaso';
}

/*function getDias( $noDias ){
	$hoy = strtotime('now');
	$hoy = date("Y-m-d", $hoy); 
	
	$dias = array();
	
	for( $i = 1; $i <= $noDias; $i++ ){
		$fechaInicio = strtotime ( '-'.$i.' day' , strtotime ( $hoy ) ) ;
		//echo '<br>'.$fechaInicio = date ( 'Y-m-d' , $fechaInicio); 
		$dia = date ( 'd' , $fechaInicio);
		$mes = date ( 'M' , $fechaInicio);
		$dias[] = array( 'mes' => $mes, 'dia' => $dia );
	}
	return $dias;
}*/

function getDias( $fechaInicioTs, $fechaFinTs ){
	$fechaInicio = date("Y-m-d", $fechaInicioTs);
	$fechaFin = date("Y-m-d", $fechaFinTs);

	$datetime1 = new DateTime($fechaInicio);
	$datetime2 = new DateTime($fechaFin);
	$interval = $datetime1->diff($datetime2);
	$noDias = $interval->format('%a');
	
	$dias = array();
	
	for( $i = 1; $i <= $noDias; $i++ ){
		$fechaI = strtotime ( '-'.$i.' day' , strtotime ( $fechaFin ) ) ;
		//echo '<br>'.$fechaInicio = date ( 'Y-m-d' , $fechaInicio); 
		$dia = date ( 'd' , $fechaI);
		$mes = date ( 'M' , $fechaI);
		$dias[] = array( 'mes' => $mes, 'dia' => $dia );
	}
	return $dias;
	
}

function getPeridoFechas( $periodo ){

	switch ( $periodo ) {
		case 1: //Mes actual
			return getPeridoFechasMesActual( $periodo );
			break;
		case 2: //Ultimos 30 dias
			return getPeridoFechasUltimos30( $periodo );
			break;
		default:
			return getPeridoFechasUltimos30( $periodo );
	}
}

function getPeridoFechasMesActual( $periodo ){ 
	$fechaFinTs = strtotime('now');
	$hoy = date("Y-m-d", $fechaFinTs);
	$d = new DateTime('first day of this month');
	$fechaInicioTs = strtotime($d->format('Y-m-d'));
	
	$dias = array_reverse(getDias( $fechaInicioTs, $fechaFinTs ));
	
	return array( "fechaInicioTs" => $fechaInicioTs, "fechaFinTs" => $fechaFinTs, "dias" => $dias );
}

function getPeridoFechasUltimos30( $periodo ){
	$noDias = 30;
	$fechaFinTs = strtotime('now');
	$hoy = date("Y-m-d", $fechaFinTs); 	
	$fechaInicioTs = strtotime ( '-'.$noDias.' day' , strtotime ( $hoy ) ) ;
	
	$dias = array_reverse(getDias( $fechaInicioTs, $fechaFinTs ));
	
	return array( "fechaInicioTs" => $fechaInicioTs, "fechaFinTs" => $fechaFinTs, "dias" => $dias );
}

?>