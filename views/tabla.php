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

$noEstudiosPorDia = $estudioSynapse->getNoEstudios2( $fechaInicioTs, $fechaFinTs, $periodo );
$detallePorModalidad = $estudioSynapse->getDetallePorModalidad( $fechaInicioTs, $fechaFinTs, $periodo );
$detallePorHospital = $estudioSynapse->getDetallePorHospital( $fechaInicioTs, $fechaFinTs, $periodo );
$hospitales = $estudioSynapse->getHospitales( $proyecto );
$modalidades = $estudioSynapse->getModalities();

//Espejo
$estudioEspejo = new EstudioEspejo( $proyecto );
$noEstudiosPorDiaEspejo = $estudioEspejo->getNoEstudios( $fechaInicioTs, $fechaFinTs, $periodo );
$detallePorModalidadEspejo = $estudioEspejo->getDetallePorModalidad( $fechaInicioTs, $fechaFinTs, $periodo );
$detallePorHospitalEspejo = $estudioEspejo->getDetallePorHospital( $fechaInicioTs, $fechaFinTs, $periodo );

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
						$fechaURL = 0;
						foreach( $noEstudiosPorDia AS $s ){
							if( $periodo == 3 ){
								$fecha = "0".$s['mes'];
								$mes = $s['mes'];
								$year = $s['year'];
								$fechaURL = strtotime("$year-$mes-01");
								$pUrl = 3;
							}
							else{
								$fecha = date("Ymd", strtotime($s['fecha']));
								$fechaURL = strtotime($s['fecha']);
							}
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
							$url = 'diferencias.php?p='.$pUrl.'&t=3&m='.$modalidad.'&fecha='.$fechaURL.'&proyecto='.$proyecto;
							echo "<td class='".$classNivelAlerta." celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'><a class='linkVerDifEst' href='".$url."' target='_blank'>".round($parity)."%</a></td>";
						}
						
					?>
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>%</td>
					<?php endfor; }?>
					
					<td></td>
					<?php
						$paritySuma = getParity( $sumaSynapse, $sumaEspejo );
						$classNivelAlerta = getClaseNivelAlerta( $paritySuma );
						echo '<td class="'.$classNivelAlerta.' bordes sinBordeAbajo celdasNumerosSolo1Col">'.round($paritySuma).'%</td>';
					?>

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
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class=" celdasNumeros bordesCeldasNumerosAbajo">-</td>
					<?php endfor; }?>
					
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudios); ?></td>
					
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
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class="celdasNumeros bordesCeldasNumerosAbajo">-</td>
					<?php endfor; }?>
					
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
					$fechaURL = 0;
					foreach( $detallePorModalidad as $de ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						if( $periodo == 3 ){
							$fecha = "0".$de[$d['alias']]['mes'];
							$mes = $de[$d['alias']]['mes'];
							$year = $de[$d['alias']]['year'];
							$fechaURL = strtotime("$year-$mes-01");
							$pUrl = 3;
						}
						else{
							$fecha = date("Ymd", strtotime($de[$d['alias']]['fecha']));
							$fechaURL = strtotime($de[$d['alias']]['fecha']);
						}
						$modalidad = $d['alias'];
						$synapse = $de[$d['alias']]['noEstudios'];
						$sumaSynapseM += $synapse;
						$espejo = $detallePorModalidadEspejo[$fecha][$d['alias']]['noEstudios'];
						$sumaEspejoM += $espejo;
						$parity = getParity( $synapse, $espejo );
							$mas100 	= ( $parity > 100 ) ? ( $mas100 + 1 ) : $mas100;
							$igual100 	= ( $parity == 100 ) ? ( $igual100 + 1 ) : $igual100;
							$r50_100 	= ( $parity > 49 && $parity < 100 ) ? ( $r50_100 + 1 ) : $r50_100;
							$r0_50 		= ( ($parity >= 0 && $parity < 50) || $parity < 0 ) ? ( $r0_50 + 1 ) : $r0_50;
						$classNivelAlerta = getClaseNivelAlerta( $parity );
						//echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$noEstudios.'</td>';
						
						
						$url = 'diferencias.php?p='.$pUrl.'&t=1&m='.$modalidad.'&fecha='.$fechaURL.'&proyecto='.$proyecto;
						echo '<td class="'.$firtCell.' '.$classNivelAlerta.' nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft" style="border-left: none;"><a class="linkVerDifEst" href="'.$url.'" target="_blank">'.round($parity).'%</a></td>';
						$sumaNoEstudiosModalidad += $de[$d['alias']]['noEstudios'];
					}
					?>
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>%</td>
					<?php endfor; }?>
					
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
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class=" celdasNumeros bordesCeldasNumerosAbajo">-</td>
					<?php endfor; }?>
					
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
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class="celdasNumeros bordesCeldasNumerosAbajo">-</td>
					<?php endfor; }?>
					
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
					$fechaURL = 0;
					foreach( $detallePorHospital as $deH ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : ''; //$detallePorHospital['2017-02-27']['ISEM01']['noEstudios']
						//$noEstudios = ( isset( $deH[$h['clave']]['noEstudios'] ) ) ? number_format($deH[$h['clave']]['noEstudios']): '-';

						
						if( !$deH[$h['clave']]['fecha'] ){							
							$fecha = date("Ymd", strtotime($fechaAnterior.' +1 day'));
							$fechaAnterior = date("Y-m-d", strtotime($fechaAnterior.' +1 day'));
						}
						else{
							$fechaAnterior = $deH[$h['clave']]['fecha'];
						}
						
						if( $periodo == 3 ){
							$fecha = "0".$deH[$h['clave']]['mes'];
							$mes = $deH[$h['clave']]['mes'];
							$year = $deH[$h['clave']]['year'];
							$fechaURL = strtotime("$year-$mes-01");
							$pUrl = 3;
						}
						else{
							$fecha = date("Ymd", strtotime($deH[$h['clave']]['fecha']));
							$fechaURL = strtotime($deH[$h['clave']]['fecha']);
						}
						
						$synapse = $deH[$h['clave']]['noEstudios'];
						$sumaSynapse += $synapse;
						$espejo = $detallePorHospitalEspejo[$fecha][$h['clave']]['noEstudios'];
						$sumaEspejo += $espejo;
						$parity = getParity( $synapse, $espejo );
							$mas100 	= ( $parity > 100 ) ? ( $mas100 + 1 ) : $mas100;
							$igual100 	= ( $parity == 100 ) ? ( $igual100 + 1 ) : $igual100;
							$r50_100 	= ( $parity > 49 && $parity < 100 ) ? ( $r50_100 + 1 ) : $r50_100;
							$r0_50 		= ( ($parity >= 0 && $parity < 50) || $parity < 0 ) ? ( $r0_50 + 1 ) : $r0_50;
						$classNivelAlerta = getClaseNivelAlerta( $parity );
						
						$url = 'diferencias.php?p='.$pUrl.'&t=2&m='.$modalidad.'&fecha='.$fechaURL.'&proyecto='.$h['clave'];
						echo '<td class="'.$firtCell.' '.$classNivelAlerta.' nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft" style="border-left: none;"><a class="linkVerDifEst" href="'.$url.'" target="_blank">'.round($parity).'%</a></td>';
						$sumaNoEstudiosHospital += $deH[$h['clave']]['noEstudios'];
					}
					?>
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>%</td>
					<?php endfor; }?>
					
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
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>%</td>
					<?php endfor; }?>
					
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
					
					<?php if( $periodo == 3 || $periodo == 1 ){
							$iniCont = ( $periodo == 3 ) ? $s['mes'] : date("d", strtotime($s['fecha']));							
							$di = new DateTime('last day of this month');								
							$finCont = ( $periodo == 3 ) ? 12 : $di->format('d');
					
							for( $z = $iniCont; $z < $finCont; $z++ ): ?>
							<td class="celdasNumeros bordesCeldasNumerosAbajo">-</td>
					<?php endfor; }?>
					
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'><?php echo number_format($sumaNoEstudiosHospital); ?></td>
				</tr>
				<?php endforeach; ?>
				
			</table>

		<script>
			$(document).ready(function(){
				$('#mas100').html('<?php echo $mas100; ?>');
				$('#igual100').html('<?php echo $igual100; ?>');
				$('#r50_100').html('<?php echo $r50_100; ?>');
				$('#r0_50').html('<?php echo $r0_50; ?>');
			});

		</script>

<?php

function getParity( $synapse, $DBE ){
	if( $synapse == 0 && $DBE == 0 )
		return 100;
	if( $synapse > 0 && $DBE == 0 )
		return 0;
	if( $synapse == 0 && $DBE > 0 )
		return 0;
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
	$meses = array( '', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
	
	for( $i = 1; $i <= $noDias; $i++ ){
		$fechaI = strtotime ( '-'.$i.' day' , strtotime ( $fechaFin ) ) ;
		//echo '<br>'.$fechaInicio = date ( 'Y-m-d' , $fechaInicio); 
		$dia = date ( 'd' , $fechaI);
		$mes = date ( 'n' , $fechaI);
		$dias[] = array( 'mes' => $meses[$mes], 'dia' => $dia );
	}
	return $dias;
	
}

function getMeses( $fechaInicioTs, $fechaFinTs ){
	$fechaInicio = date("Y-m-d", $fechaInicioTs);
	$fechaFin = date("Y-m-d", $fechaFinTs);

	$datetime1 = new DateTime($fechaInicio);
	$datetime2 = new DateTime($fechaFin);
	$interval = $datetime1->diff($datetime2);
	$noDias = $interval->format('%m');
	
	$meses = array( '', 'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic');
	
	$dias = array();
	
	for( $i = 0; $i <= $noDias; $i++ ){
		$fechaI = strtotime ( '-'.$i.' month' , strtotime ( $fechaFin ) ) ;
		//echo '<br>'.$fechaInicio = date ( 'Y-m-d' , $fechaInicio); 
		//$dia = date ( 'Y' , $fechaI);
		$dia = '';
		$mes = date ( 'n' , $fechaI);
		$dias[] = array( 'mes' => $meses[$mes], 'dia' => $dia );
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
		case 3: //Ultimos 30 dias
			return getPeridoFechasYTD( $periodo );
			break;
		default:
		return getPeridoFechasUltimos30( $periodo );
	}
}

function getPeridoFechasMesActual( $periodo ){ 
	//$fechaFinTs = strtotime('now');
	$d = new DateTime('first day of next month');
	$fechaFinTs = strtotime($d->format('Y-m-d'));
	
	$hoy = date("Y-m-d", $fechaFinTs);
	$d = new DateTime('first day of this month');
	$fechaInicioTs = strtotime($d->format('Y-m-d'));
	
	$dias = array_reverse(getDias( $fechaInicioTs, $fechaFinTs ));
	
	return array( "fechaInicioTs" => $fechaInicioTs, "fechaFinTs" => $fechaFinTs, "dias" => $dias );
}

function getPeridoFechasYTD( $periodo ){ 
	//$fechaFinTs = strtotime('now');
	$fechaFinTs = strtotime( date('Y')."-12-28");
	$hoy = date("Y-m-d", $fechaFinTs);
	$d = new DateTime('first day of January ' . date('Y'));
	$fechaInicioTs = strtotime($d->format('Y-m-d'));

	$dias = array_reverse(getMeses( $fechaInicioTs, $fechaFinTs ));
	
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