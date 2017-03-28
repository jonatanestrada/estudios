<?php
$hostname_HovaHlt = "localhost";
$database_HovaHlt = "sistemas";
$username_HovaHlt = "root";
$password_HovaHlt = "123456789";
// Create connection
$dbc = new mysqli($hostname_HovaHlt , $username_HovaHlt, $password_HovaHlt);
// Check connection
if ($dbc->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
/* change character set to utf8 | Object Oriented*/
if (!$dbc->set_charset("utf8")) {
        printf("Error loading character set utf8: %s\n", $dbc->error);
        exit();
}

class DBO
{
  private static $dbc;
  public static function init($conexion){
    self::$dbc = $conexion;
  }
  public static function select_db($db){
    // echo $db;
    self::$dbc->select_db($db);
  }
  //For counting rows
  public static function getNumber($sql){
    return self::$dbc->query($sql)->num_rows;
    // echo self::$dbc->error;
  }
  //For single row
  public static function get($sql){
    $result = self::$dbc->query($sql);
    if($result)
      return $result->fetch_object();
    else
      return false;
  }
  //For single or multiple row as array
  public static function getArray($sql, $toUnset = "", $parsedJSON = false){
    $arrayResult = array();
    $st = self::$dbc->prepare($sql);
    echo  self::$dbc->error;
    if(!$st) return $arrayResult;
    $st->execute();
    $result = $st->get_result();
    // $result = self::$dbc->query($sql);
    if($result){
      while($row = $result->fetch_assoc()) {
        if($toUnset == ""){
          if(!$parsedJSON){
            $arrayResult[] = $row;
          }else{
            $arrayResult[] = self::parseStringJSON($row);
          }
        }else{
          foreach (explode(',',$toUnset) as $key => $toust) {
            unset($row[$toust]);
          }
          if($parsedJSON){
            $arrayResult[] = self::parseStringJSON($row);
          }else{
            $arrayResult[] = $row;
          }
        }
      }
      return $arrayResult;
    }else{
      return false;
    }
  }

  public static function getArrayAssoc($sql){
    $result = self::$dbc->query($sql);
    // echo  self::$dbc->error;
    $arrayResult = array();
    while($row = $result->fetch_assoc()) {
      if(is_numeric(array_values($row)[0])){
         //Si los indices son numeros agrego dummy 'e' para poder acceder a ellos con js
        $arrayResult['e'.array_values($row)[0]] = array_values($row)[1];
      }else{
        $arrayResult[array_values($row)[0]] = array_values($row)[1];
      }
    }
    return $arrayResult;
  }

  public static function getArrayValues($sql){
    $result = self::$dbc->query($sql);
    // echo  self::$dbc->error;
    $arrayResult = array();
    while($row = $result->fetch_assoc()) {
      $arrayResult[] = array_values($row)[0];
    }
    return $arrayResult;
  }

  public static function doUpdate($sql){//for updates
    $result = self::$dbc->query($sql);
    // echo $sql;
    echo  self::$dbc->error;
    return  self::$dbc->affected_rows;
  }

  public static function insert($sql, $returnLastID = false){
    if ($returnLastID) {
      self::$dbc->query($sql);
      echo  self::$dbc->error;
      return self::$dbc->insert_id;
    }else{
      // echo  self::$dbc->error;
      return  self::$dbc->query($sql);
    }
  }

  public static function s($string){ // scape string
    return "'".mysqli_real_escape_string(self::$dbc, $string)."'";
  }

  public static function delete($sql){
    if($sql == '') return;
    // var_dump($sql);
    $recs = explode(";", $sql);
    if(count($recs) > 1){
      foreach ($recs as $key => $rec) {
        self::delete($rec);
      }
    }else{
      // var_dump($sql);
      // self::doUpdate($sql);
      self::$dbc->query($sql);
      echo self::$dbc->error;
    }
    return self::$dbc->affected_rows;
  }

  //Convierte una arreglo en una cadena lista para ser usada en una query de Mysql
  //ej: [asd, asd, df] => ['"asd"', '"asd"', '"df"']
  public static function parametrizeArrayForSql($array){
    $newArray = array();
    foreach ($array as $key => $a) {
      $newArray []= self::s($a);
    }
    return $newArray;
  }

  public static function prepareObject($obj){
    foreach ($obj as $key => $value) {
      // print_r($obj->$key."<br>");
      // var_dump($obj->$key);
      if(!is_array($obj->$key) && !is_object($obj->$key)){
        // $obj->key = self::prepareObject((object) $obj->key);
        $obj->$key =  DBO::s($obj->$key);
      }else{
        $obj->$key =  DBO::s(json_encode($obj->$key));
      }
      // print_r($obj->$key);
      // print_r($key." --> ".$value);
    }
    return $obj;
  }

  public static function parseStringJSON($array){
    // var_dump($obj);

    foreach ($array as $key => $value) {
      // echo $key." Is JSON? ".isJson($obj->$key)."<br>";
      // print_r($obj->$key);
      // echo $value;
      if(isJson($value)){
        // echo $value." << --- ESJSON";
        $array[$key] = json_decode($value);
      }
    }
    return $array;
  }


  public static function getError(){
    return self::$dbc->error;
  }





  // public static function getArrayInset($sql){
  //   $result = self::$dbc->query($sql);
  //   $arrayResult = array();
  //   while($row = $result->fetch_array()) {
  //     $arrayResult[] = $row;
  //   }
  //   return $arrayResult;
  // }
}
DBO::init($dbc);
 // printf ("System status: %s\n", $dbc->stat());
function isJson($string) {
  return is_object(json_decode($string));
}
?>
