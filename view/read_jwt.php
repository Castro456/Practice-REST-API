<?php
ini_set("display_errors",1);
header("Access-Control-Allow-Origin: *");
// it allow all origins localhost, domains, any sub domains
header("Content-type: application/json; charset=UTF-8");
// receiving in json format, data which are getting inside request
header("Access-Control-Allow-Methods: POST");
// method type
include("../config/database.php");
include("../controller/student.php");

//JWT Vendor
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

$db=new Database();
$connection=$db->connect();
$student=new Student($connection);

if ($_SERVER['REQUEST_METHOD']==="POST") 
{
  // $data = json_decode(file_get_contents('php://input')); // Sending jwt token in json format

  $all_headers = getallheaders(); // Inbuilt php fn to get all the header while we make a request
  $data->jwt = $all_headers['Authorization'];

  if (!empty($data->jwt)) 
  {
      try 
      {
        $secret_key = "castro"; // Signature secret key
        $decoded_data = JWT::decode($data->jwt, $secret_key, array('HS512'));
        $user_id = $decoded_data->data->id;
        http_response_code(200);
        echo  json_encode(array(
          "status" => 1,
          "message" => "We got the token",
          "data" => $decoded_data,
          "user id" => $user_id
        ));
      } catch (Exception $e) 
      {
        http_response_code(500);
        echo  json_encode(array(
          "status" => 0,
          "message" => $e->getMessage()
        ));
      }

  }
  else 
  {
    http_response_code(404);
    echo json_encode(array(
      "status" => 0,
      "message" => "Provide a JWT Token"
    ));
  }
}
