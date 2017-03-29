<?php
$diasEstudios[] = array('dia'=>26, 'mes'=>'feb', 'noEstudios' => '1104');
$diasEstudios[] = array('dia'=>27, 'mes'=>'feb', 'noEstudios' => '1847');
$diasEstudios[] = array('dia'=>28, 'mes'=>'feb', 'noEstudios' => '1725');
$diasEstudios[] = array('dia'=>29, 'mes'=>'feb', 'noEstudios' => '-');
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
					foreach( $diasEstudios as $d ){
						echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
					}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;' >Total</td>
				</tr>
				<tr>
					<td >Clave</td>
					<td>Hospital</td>
					<td></td>
					<?php 
					foreach( $diasEstudios as $d ){
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
					<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>96%</td>
					<td class='nivelAlertaSobrepaso celdasNumeros bordesCeldasNumeros'>105%</td>
					<td class='nivelAlertaMuyporDebajo celdasNumeros bordesCeldasNumeros'>0%</td>
					<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros'>100%</td>
					<td></td>
					<td class='nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col'>100%</td>
				</tr>
				
				<tr>
					<td class='bordes' style='border-bottom: none; border-right: none; ' ></td>		
					<td class='bordes alinIza' style='border-bottom: none; border-left: none; ' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					foreach( $diasEstudios as $d ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumeros">'.$d['noEstudios'].'</td>';
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
					foreach( $diasEstudios as $d ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$d['noEstudios'].'</td>';
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'>33.56</td>
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
						foreach( $diasEstudios as $d ){
							echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
						}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;'>Total</td>
				</tr>
				
				<tr>
					<td></td>
					<?php 
						foreach( $diasEstudios as $d ){
							echo '<td class="celdasNumeros bold">'.$d['mes'].'</td>';
						}
					?>		
				</tr>

				<tr>
					<td>
						&nbsp;
					</td>
				</tr>

				<tr>
					<td style='text-align:center; background-color: #E2EFDA; border-right: none;' class='bordes' >
						CR
					</td>
					<td style='background-color: #E2EFDA; border-left: none;' class='bordes'>
						Placas Simples
					</td>
					<td></td>
					<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>96%</td>
					<td class='nivelAlertaSobrepaso celdasNumeros bordesCeldasNumeros'>105%</td>
					<td class='nivelAlertaMuyporDebajo celdasNumeros bordesCeldasNumeros'>0%</td>
					<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros'>100%</td>
					<td></td>
					<td class='nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col'>100%</td>		
				</tr>
				
				<tr>
					<td class='bordes bordeCeldaFirstIzq' style='border-bottom: none;' ></td>
					<td class='bordes bordeCeldaLastDer alinIza' style='border-bottom: none;' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					foreach( $diasEstudios as $d ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumeros">'.$d['noEstudios'].'</td>';
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
					foreach( $diasEstudios as $d ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$d['noEstudios'].'</td>';
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'>33.56</td>
				</tr>
				
				
				
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
						foreach( $diasEstudios as $d ){
							echo '<td class="celdasNumeros bold">'.$d['dia'].'</td>';
						}
					?>
					<td style='width: 10px;'></td>
					<td rowspan=2 class='bold celdasNumerosSolo1Col' style='vertical-align: bottom;'>Total</td>
				</tr>
				
				<tr>
					<td></td>
					<?php 
						foreach( $diasEstudios as $d ){
							echo '<td class="celdasNumeros bold">'.$d['mes'].'</td>';
						}
					?>		
				</tr>

				<tr>
					<td>
						&nbsp;
					</td>
				</tr>

				<tr>
					<td style='text-align:center; background-color: #EDEDED; border-right: none;' class='bordes' >
						ISEM01
					</td>
					<td style='background-color: #EDEDED; border-left: none;' class='bordes'>
						HG DR FERNANDO QUIROZ G
					</td>
					<td></td>
					<td class='nivelAlertaNormal celdasNumeros bordesCeldasNumeros bordeLeft' style='border-left: none;'>96%</td>
					<td class='nivelAlertaSobrepaso celdasNumeros bordesCeldasNumeros'>105%</td>
					<td class='nivelAlertaMuyporDebajo celdasNumeros bordesCeldasNumeros'>0%</td>
					<td class='nivelAlerta100 celdasNumeros bordesCeldasNumeros'>100%</td>
					<td></td>
					<td class='nivelAlertaNormal bordes sinBordeAbajo celdasNumerosSolo1Col'>100%</td>		
				</tr>
				
				<tr>
					<td class='bordes bordeCeldaFirstIzq' style='border-bottom: none;' ></td>
					<td class='bordes bordeCeldaLastDer alinIza' style='border-bottom: none;' >Synapse - Central</td>
					<td></td>
					<?php 
					$i = 0;
					foreach( $diasEstudios as $d ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumeros">'.$d['noEstudios'].'</td>';
					}
					?>
					<td></td>
					<td class='bordes sinBordeAbajo celdasNumerosSolo1Col'>33.05</td>		
				</tr>
				<tr>
					<td class='bordes bordeCeldaFirstIzq'></td>
					<td class='bordes bordeCeldaLastDer alinIza' >DBE - Central</td>
					<td></td>
					<?php 
					$i = 0;
					foreach( $diasEstudios as $d ){
						$firtCell = ( $i++ == 0 ) ? 'bordeLeft' : '';
						echo '<td class="'.$firtCell.' celdasNumeros bordesCeldasNumerosAbajo">'.$d['noEstudios'].'</td>';
					}
					?>
					<td></td>
					<td class='bordes celdasNumerosSolo1Col'>33.56</td>
				</tr>
				
				
			</table>
		</div>

	</body>
</html>