<?php
header("Access-Control-Allow-Origin: *");
// it allow all origins localhost, domains, any sub domains
header("Access-Control-Allow-Methods: GET");
// method type
include("../config/database.php");
include("../controller/student.php");

$db=new Database();
$connection=$db->connect();
$student=new Student($connection); //$connection is the parameter passed to constructor of Student class

if($_SERVER['REQUEST_METHOD'] === "GET"){
  $data = $student->get_data();
  if($data->num_rows > 0){   //num_row represent no.of rows in the db
    $students["results"] = array();
    while ($rows = $data->fetch_assoc()) {  //fetch_assoc() returns all data in array format
      //matter name if changed to fetch_arrary() it shows in array with array position
      // matter name if changed to fetch_object() it shows in object view
      // print_r($rows);
      array_push($students["results"], array(
        "id" => $rows['id'],
        "name" => $rows['name'],
        "mobile" => $rows['mobile'],
        "status" => $rows['status'],
        "created_time" =>  date("d-m-Y", strtotime($rows['created_time'])) //Very Important date formate code
      ));
    }
    http_response_code(200);
    echo json_encode(array(
    "status" => 1,
    "data" => $students["results"]
  ));  
  }
}
else {
  http_response_code(503);
  echo json_encode(array(
    "status" => 0,
    "message" => "Access Denied"
  ));
}