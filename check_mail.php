<?php
include('db.php');

$requestPayload = file_get_contents("php://input");
$data = json_decode($requestPayload, true);

$email = $data['email'] ?? '';
$response = ['exists' => false];

if ($email) {
    $sql = "SELECT * FROM employees WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $response['exists'] = true;
    }
}

header('Content-Type: application/json');
echo json_encode($response);
?>
