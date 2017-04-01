<?php
$ayer = date("d-M-Y", strtotime('now -1 DAY')); 
$diasSemana = array( '',"Lunes","Martes","Miercoles","Jueves","Viernes","Sábado","Domingo");
$diaSem = $diasSemana[date("N", strtotime('now -1 DAY'))]; 
?>
<!DOCTYPE html>
 <html lang="es">
	 <head>
		<title>Paridad Synapse - BDE</title>
		<meta charset="utf-8" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<script type="text/javascript" src="js/jquery-3.2.0.min.js"></script>
		
		<script>
			$(document).ready(function(){
				getTableAjax( 'ISEM', 2 );
				
				$("#boton").on( "click", function() {	 
					$('.viewMore').toggle();
				});
				
			});
			
			function getTableParidad()
				{
					var proyecto = $( "#proyecto" ).val();
					var periodo = $( "#periodo" ).val();
					//alert(sel.value);
					getTableAjax( proyecto, periodo );
				}
			
			function getTableAjax( proyecto, periodo ){
				$('#divLoading').show();
				$('#divTabla').hide();
				
				var data = { "proyecto": proyecto, "periodo": periodo }; 
				
				$.ajax({
				   url:'views/tabla.php',
				   type:'GET',
				   data: data,
				   success: function(data){
					   $('#divTabla').html(data);
					   $('#divLoading').hide();
					   $('#divTabla').show();
				   }
				});
			}
			
		</script>
		
	</head>
 
	<body>
		<img src='images/logo.png' style='float:right;'>
		<div style='color: #595959; width: 300px;text-align: center;' >
			<p style='font-weight: bold;font-size: 20px;margin-bottom: 0px;'>Ejercicio de Paridad Synapse - BDE</p>
			<p style='margin-top: 5px;font-size: 23px;margin-bottom: 0px;'>
				Proyecto: <select id='proyecto' onchange="getTableParidad();">
				  <option value="ISEM">ISEM</option>
				  <option value="JALI">JALI</option>
				  <option value="MICH">MICH</option>
				  <option value="TLAX">TLAX</option>
				</select>
				Periodo: <select id='periodo' onchange="getTableParidad();">
				  <option value="1">Mes Actual</option>
				  <option value="2">Monthly</option>
				  <option value="3">YTD</option>
				
				<input id="boton" type="button" value="Ver/Ocultar Detalles">
			</p>
			<p style='margin-top: 0px;font-size: 20px;margin-bottom: 5px;'>Corte al <?php echo $diaSem.', '.$ayer; ?></p>
		</div>

		<hr style='border-style: solid; border-color: #BBD78B; margin-top: 0px;margin-bottom: 15px;'>

		<div style="text-align:center;display:none;'" id='divLoading'>
			<img src='images/large_loader.gif'>
		</div>
		<div style="text-align:center;'" id='divTabla'>
		</div>

	</body>
</html>
