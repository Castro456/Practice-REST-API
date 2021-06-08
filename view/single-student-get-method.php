<?php 
// ini_set("display-errors", 1);
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET");
// method type
include("../config/database.php");
include("../controller/student.php");

$db=new Database();
$connection=$db->connect();
$student=new Student($connection); //$connection is the parameter passed to constructor of Student class

if($_SERVER['REQUEST_METHOD']==="GET") {
  $student_id = isset($_GET['id']) ? intval($_GET['id']) : "";
  // if get has value connverts to a (php fn) int value if it doesn't have a value then return empty value
  if( !empty($student_id)) {
    $student->id=$student_id; //even without declaring id in student class can pass the value id
    $data=$student->single_data();

    if( !empty($data)) {
      http_response_code(200);
      echo json_encode(array("status"=> 1,
          "data"=> $data));
    }
    else {
      http_response_code(404); //not found
      echo json_encode(array("status"=> 0,
          "message"=> "Student Not Found"
        ));
    }
  }
}
else {
  http_response_code(503);
  echo json_encode(array("status"=> 0,
      "message"=> "Access Denied"
    ));
}