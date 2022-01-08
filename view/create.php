<?php
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
$student=new Student($connection); //$connection is the parameter passed to constructor of Student class

// $student object is assigned to access the properties of class Student like declared variables public name,email,mobile
if ($_SERVER['REQUEST_METHOD']==="POST") {
  $data=json_decode(file_get_contents("php://input"));
  // php is the function
  // input is a way to get all the data of the body parameter

  $all_headers = getallheaders(); // Inbuilt php fn to get all the header while we make a request

  if (( !empty($data->name)) && ( !empty($data->email)) && ( !empty($data->mobile))) {
    
    try 
      {
        $data->jwt = $all_headers['Authorization'];

        $secret_key = "castro"; // Signature secret key
        $decoded_data = JWT::decode($data->jwt, $secret_key, array('HS512'));
        $user_id = $decoded_data->data->id;

        $student->name=$data->name;
        $student->email=$data->email;
        $student->mobile=$data->mobile;

        // name,email,mobile getting as values inside this object data
        if ($student->create_data()) {
        http_response_code(200); //php fn
        echo json_encode(array("status"=> 1,
            "message"=> "Student created successfully"
          ));
        }

        else {
        http_response_code(500); //internal server error
        echo json_encode(array("status"=> 0,
            "message"=> "Failed to Create"
          ));
        }

      } catch (Exception $e) 
      {
        http_response_code(500);
        echo  json_encode(array(
          "status" => 0,
          "message" => $e->getMessage()
        ));
      }

  }

  else {
    http_response_code(404); //not found
    echo json_encode(array("status"=> 0,
        "message"=> "All elements needed"
      ));
  }
}

else {
  http_response_code(503); //service unavailable
  echo json_encode(array("status"=> 0,
      "message"=> "Access Denied"
    ));
}