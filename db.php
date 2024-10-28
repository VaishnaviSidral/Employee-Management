<?php
$host="localhost";
$username="root";
$password="Sidral@03";
$dbname="Employeemanagement";

$conn=new mysqli($host,$username,$password,$dbname);

if(!$conn)
{
    die("Error creating database");
}
else{
    // echo "Connection successfull!!";
}


//to create database
// $sql='CREATE DATABASE EmployeeManagement';
// if($conn->query($sql)===TRUE)
// {
//     echo "Database created successfully!!";
// }
// else{
//     echo "Error creating database = ".$conn->connect_error();
// }




//to create table
// $sql_create="CREATE TABLE Employees(id INT(255) AUTO_INCREMENT,name VARCHAR(255),
// email VARCHAR(255),password VARCHAR(255),com_pass VARCHAR(255),mobile_no VARCHAR(10),
// address VARCHAR(255),
// created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
// updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
// PRIMARY KEY(id))";

// if($conn->query($sql_create)===TRUE)
// {
//     echo "Table created successfully!!";
// }
// else{
//     echo "error creating table";
// }



?>