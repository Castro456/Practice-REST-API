<?php

class Student{
  //properties of student object
  public $name;
  public $email;
  public $mobile;

  private $conn;
  private $table_name;

  public function __construct($db){  //db is the connection offset
    $this->conn = $db;
    $this->table_name = "students";
  }

  public function create_data(){
     $query = "INSERT INTO $this->table_name (name, email, mobile) VALUES (?,?,?)"; //correct query
    // $query = "INSERT INTO " .$this->table_name. "SET name = ?, email = ?, mobile = ? "; //completely wrong query
    $obj = $this->conn->prepare($query);
    //sanitization, means removing unwanted spl char from the data, sometimes input data may contain some tag in it
    //Both are php fns 
    // strip_tag removes input-data tags
    // htmlspecialchars moves spl char for that string
    $this->name=htmlspecialchars(strip_tags($this->name)); 
    $this->email=htmlspecialchars(strip_tags($this->email)); 
    $this->mobile=htmlspecialchars(strip_tags($this->mobile)); 
    // bind these parameter for prepare statement (for filling "?" values)
    // s are the datatype for those specific values can use c, i for respective
    $obj->bind_param('sss', $this->name, $this->email, $this->mobile);
    if ($obj->execute()) {
      return true;
    }
    return false;
  }

  public function get_data(){
    $query = "SELECT * FROM ".$this->table_name;
    $sql = $this->conn->prepare($query);
    $sql->execute();
    return $sql->get_result(); 
  }

  public function single_data(){
    $query= "SELECT * FROM $this->table_name WHERE id = ?";
    $sql= $this->conn->prepare($query);
    $sql->bind_param("i", $this->id);
    $sql->execute();
    $data = $sql->get_result();
    return $data->fetch_assoc();
  }
}