<?php
session_start();
include('db.php');

if($_SERVER['REQUEST_METHOD']==='POST'&&isset($_POST['id']))
{
    $employeeid=$_POST['id'];
    $id=$_SESSION['id'];
    header('Content-Type: application/json'); // Set the content type to JSON

    if ($employeeid == $id) {
        echo json_encode(['status' => 'error', 'message' => 'Cannot delete yourself']);
    } else {
        $sql = "DELETE FROM employees WHERE id='$employeeid'";
        if ($conn->query($sql)) {
            echo json_encode(['status' => 'success', 'message' => 'Employee deleted successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error deleting employee: ' . $conn->error]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request']);
}

?>