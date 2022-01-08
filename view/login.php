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
if ($_SERVER['REQUEST_METHOD']==="POST") 
{
  $email = $_POST['email'];
  $password = $_POST['password'];

  if (( !empty($email)) && ( !empty($password))) 
  {
    // print_r($data);
    $student->email=$email;
    $student->password=$password;
    $data = $student->login_admin();

    if (!empty($data)) 
    {

      $user_data = array(
        "id"=>$data['id'],
        "name"=>$data['name'],
        "email"=>$data['email']
      );
      $payload_info = array(
        "iss"=>"localhost:8111", //issued by
        "iat"=>time(),// issued at (which time )
        "nbf"=>time()+10,// not before (This jwt will be valid after 10s)
        "exp"=>time()+60,// expiry data (This jwt will be invalid after 60s)
        "aud"=>"my_users",// audience -> For which user (eg: in a department of multiple users like support_user, customer etc...)
        "data"=>$user_data // actual data

      );

      $secret_key = "castro"; // Signature secret key

      $jwt = JWT::encode($payload_info,$secret_key,'HS512'); // can pass the algorithm, by using jwt library it automatically passes the header and that header has a default algo 'HS256'. 
      // eg: JWT::encode($payload_info,$secret_key,'HS512');

    http_response_code(200); //success
    echo json_encode(array(
      "status"=> 1,
      "jwt"=>$jwt,
      "message"=> "Logged in Successfully"
      ));
    }

    else {
    http_response_code(500); //internal server error
    echo json_encode(array("status"=> 0,
        "message"=> "Failed to Login"
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