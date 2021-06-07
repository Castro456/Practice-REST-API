<?php 
include("../config/database.php");
include("../controller/student.php");
$db=new Database();
$connection=$db->connect();
$student = new Student($connection); //$connection is the parameter passed to constructor of Student class
// $student object is assigned to access the properties of class Student like declared variables public name,email,mobile
if ($_SERVER['REQUEST_METHOD'] ==="POST")  {
  $student->name= "rem";
  $student->email= "rem@gmail.com";
  $student->mobile= "1248643";

  if ($student->create_data()) {
    echo "Student created successfully";
  }
  else {
    echo "Failed to Create";
  }
}
else {
  echo "Access Denied";
}