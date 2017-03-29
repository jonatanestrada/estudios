<?php
$res = new stdClass();
$res->errno = 0;
$res->error = "Ok";

class Response
{
  public static $data;
  public static function init(){ // start off but creating the object
      self::$data = new stdClass;
      self::$data->errno = 0;
      self::$data->error = "Ok";
      self::$data->result = new stdClass;
  }
  public static function showResult($die = true){
    header('Content-Type:application/json');
    echo json_encode(self::$data);
    if($die) die();
  }
  public static function noDataResponse(){
    self::$data->errno = -1;
    self::$data->error = "Faltan datos";
    self::showResult();
  }
  public static function getEscaped($str){
      return " '".$str."' ";
  }
}
Response::init();


?>
