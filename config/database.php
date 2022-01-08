<?php 
class Database {
  private $hostname="localhost:8111";
  private $username="root";
  private $password="giveaccess";
  private $dbname="rest_php_api";
  private $conn;

  public function connect() {
    $this->conn=new mysqli($this->hostname, $this->username, $this->password, $this->dbname);
    if ($this->conn->connect_errno) { /*errno is a boolean type */
      print_r($this->conn->connect_error);
      exit;
    }
    else {
      // print_r($this->conn);
      return $this->conn;
    }
  }
}
// $db= new Database();
// $db->connect();
?>