<?php
include_once "classes/Estudio.class.php";
date_default_timezone_set('America/Mexico_City');
$diasEstudios[] = array('dia'=>26, 'mes'=>'feb', 'noEstudios' => '1104');
$diasEstudios[] = array('dia'=>27, 'mes'=>'feb', 'noEstudios' => '1847');
$diasEstudios[] = array('dia'=>28, 'mes'=>'feb', 'noEstudios' => '1725');
$diasEstudios[] = array('dia'=>29, 'mes'=>'feb', 'noEstudios' => '-');

$estudio = new Estudio();
//$noEstudiosPorDia = $estudio->getNoEstudios( 30 );
$noEstudiosPorDia = $estudio->getNoEstudios2( 30 );

$detallePorModalidad = $estudio->getDetallePorModalidad();

$detallePorHospital = $estudio->getDetallePorHospital();

//var_dump($detallePorHospital); exit;
//echo $detallePorHospital['2017-02-27']['ISEM01']['noEstudios']; exit;

$hospitales = $estudio->getHospitales();

$modalidades = $estudio->getModalities();



/*foreach( $detallePorModalidad AS $d ){
	echo $d['alias'].'<br>';
}
exit;*/
/*var_dump($noEstudiosPorDia);

foreach( $noEstudiosPorDia AS $e ){
	echo $e['ForDate'].'<br>';
}
exit;*/
?>
<!DOCTYPE html>
 <html lang="es">
	 <head>
		<title>Paridad Synapse - BDE</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
	</head>
 
	<body>
		<img src='images/logo.png' style='float:right;'>

		<div style='color: #595959; width: 300px;text-align: center;' >
			<p style='font-weight: bold;font-size: 20px;margin-bottom: 0px;'>Ejercicio de Paridad Synapse - BDE</p>
			<p style='margin-top: 5px;font-size: 23px;margin-bottom: 0px;'>Jali</p>
			<p style='margin-top: 0px;font-size: 20px;margin-bottom: 5px;'>Corte al Martes, 28-Mar-17</p>
		</div>

		<hr style='border-style: solid; border-color: #BBD78B; margin-top: 0px;margin-bottom: 15px;'>

		<div style="text-align:center;'">
			<table cellpadding="0" cellspacing="0" style='margin: auto;'>
				<tr>
					<td style='width: 105px; height: 20px;'>&nbsp;</td>
					<td style='width: 220px;'>&nbsp;</td>
					<td></td>
					<?php 
					foreach( $noEstudiosPorDia AS $e ){
						$dia = date("d", strtotime($e['fecha']));
						echo '<td class="celdasNumeros bold">'.$dia.'</td>';
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
					foreach( $noEstudiosPorDia AS $e ){
						$mes = date("M", strtotime($e['fecha']));
						echo '<td class="celdasNumeros bold">'.$mes.'</td>';
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
					/*<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>96%</td>
					<td class='nivelAlertaSobrepaso celdasNumeros bordesCeldasNumeros'>105%</td>
					<td class='nivelAlertaMuyporDebajo celdasNumeros bordesCeldasNumeros'>0%</td>
					<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros'>100%</td>*/
						foreach( $noEstudiosPorDia AS $e ){
							echo "<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>96%</td>";
						}
					?>
					<td></td>
					<td class='nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col'>100%</td>
				</tr>
				
				<tr>
					<td class='bordes' style='border-bottom: none; border-right: none; ' ></td>		
					<td class='bordes alinIza' style='border-bottom: none; border-left: none; ' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					foreach( $noEstudiosPorDia AS $e ){
						//$mes = date("M", strtotime($e['fecha']));
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumeros">'.'%%'.'</td>';
					}
					?>
					<td></td>
					<td class='bordes sinBordeAbajo celdasNumerosSolo1Col'>33.05</td>
				</tr>
				</tr>
				
				<tr>
					<td class='bordes ' style='border-right: none; border-top: none;' ></td>		
					<td class='bordes alinIza' style='border-left: none; border-top: none;' >BDE - Central</td>
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
					foreach( $noEstudiosPorDia AS $e ){
						$dia = date("d", strtotime($e['fecha']));
						echo '<td class="celdasNumeros bold">'.$dia.'</td>';
					}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;'>Total</td>
				</tr>
				
				<tr>
					<td></td>
					<?php 
					foreach( $noEstudiosPorDia AS $e ){
						$mes = date("M", strtotime($e['fecha']));
						echo '<td class="celdasNumeros bold">'.$mes.'</td>';
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
					
					<?php foreach( $detallePorModalidad as $de ): ?>
						<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>%</td>
					<?php endforeach; ?>
					
					<td></td>
					<td class='nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col'>100%</td>		
				</tr>
				
				<tr>
					<td class='bordes bordeCeldaFirstIzq' style='border-bottom: none;' ></td>
					<td class='bordes bordeCeldaLastDer alinIza' style='border-bottom: none;' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					foreach( $diasEstudios as $di ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumeros">'.$di['noEstudios'].'</td>';
					}
					?>
					<td></td>
					<td class='bordes sinBordeAbajo celdasNumerosSolo1Col'>33.05</td>		
				</tr>
				<tr>
					<td class='bordes bordeCeldaFirstIzq'></td>
					<td class='bordes bordeCeldaLastDer alinIza'>DBE - Central</td>
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
					foreach( $noEstudiosPorDia AS $e ){
						$dia = date("d", strtotime($e['fecha']));
						echo '<td class="celdasNumeros bold">'.$dia.'</td>';
					}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;'>Total</td>
				</tr>
				
				<tr>
					<td></td>
					<?php 
					foreach( $noEstudiosPorDia AS $e ){
						$mes = date("M", strtotime($e['fecha']));
						echo '<td class="celdasNumeros bold">'.$mes.'</td>';
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
						<?php echo $h['nombre']; ?>
					</td>
					<td></td>
					<?php
					/*<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>96%</td>
					<td class='nivelAlertaSobrepaso celdasNumeros bordesCeldasNumeros'>105%</td>
					<td class='nivelAlertaMuyporDebajo celdasNumeros bordesCeldasNumeros'>0%</td>
					<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros'>100%</td>*/
						foreach( $noEstudiosPorDia AS $e ){
							echo "<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>%</td>";
						}
					?>
					<td></td>
					<td class='nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col'>100%</td>		
				</tr>
				
				<tr>
					<td class='bordes bordeCeldaFirstIzq' style='border-bottom: none;' ></td>
					<td class='bordes bordeCeldaLastDer alinIza' style='border-bottom: none;' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					$sumaNoEstudiosModalidad = 0;
					foreach( $detallePorModalidad as $de ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						$noEstudios = ( isset( $de[$d['alias']]['noEstudios'] ) ) ? number_format($de[$d['alias']]['noEstudios']): '-';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">-'.''.'</td>';
						$sumaNoEstudiosModalidad += $de[$d['alias']]['noEstudios'];
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'>-<?php //echo number_format($sumaNoEstudiosModalidad); ?></td>
				</tr>
				<tr>
					<td class='bordes bordeCeldaFirstIzq'></td>
					<td class='bordes bordeCeldaLastDer alinIza' >DBE - Central</td>
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
				<?php endforeach; ?>
				
			</table>
		</div>

	</body>
</html>