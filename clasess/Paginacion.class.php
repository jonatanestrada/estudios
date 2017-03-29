<?php

include_once "../../sistema/api/v1/conexion.php";

class Paginacion{
  private static $dbc;
  public static function init($conexion){
    self::$dbc = $conexion;
  }
  
  public static function getPaginacion( $sql, $db, $page = 1, $adjacents = 3, $per_page = 50 ){
	$records = self::getRecords( $sql, $db, $page, $per_page );
	$noPages = self::getNoPages( $sql, $db, $per_page );
  
	//return self::reponseJson( $records, $page, $noPages, $adjacents  );
	return self::reponseArray( $records, $page, $noPages, $adjacents  );
  }

  private static function getNoPages( $sql, $db, $per_page ){
		$noRecords = self::getNumRecords( $sql, $db );
		$noPages = ceil($noRecords/$per_page);

		return $noPages;
  }
  
  private static function getNumRecords( $sql, $db ){
	DBO::select_db($db);
	return  DBO::getNumber($sql);
  }
  
    private static function getRecords( $sql, $db, $page, $per_page ){
	
	$offset = ($page - 1) * $per_page;
	DBO::select_db($db);
	return  DBO::getArray($sql." LIMIT $offset,$per_page;");
  }

  private static function reponseJson( $records, $page, $noPages, $adjacents ){

	$data = array(
					'noPages' => $noPages,
					'page' => $page,
					'adjacents' => $adjacents,
					'registros' => $records
				);

	header('Content-Type: application/json');
				
	return json_encode($data);
}

private static function reponseArray( $records, $page, $noPages, $adjacents ){

	$data = array(
					'noPages' => $noPages,
					'page' => $page,
					'adjacents' => $adjacents,
					'registros' => $records
				);

	return $data;
}
  
}

?>