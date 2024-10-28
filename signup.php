<?php
session_start();
include('db.php');

$employee=null;
$passwordError="";

if(isset($_GET['id']))
{
  $id=$_GET['id'];
  $sql_get="SELECT * FROM employees WHERE id='$id'";
  $result=$conn->query($sql_get);
  if($result->num_rows>0)
  {
    $employee=$result->fetch_assoc();
  }
}

$showModal=false;
if($_SERVER['REQUEST_METHOD']=='POST')
{
  $name=$_POST['name'];
  $email=$_POST['email'];
  $mobile_no=$_POST['mobile_no'];
  $address=$_POST['address'];
  $isUpdate = isset($_POST['id']);

    // Only process passwords for new employees
    if ( isset($_POST['password']) && isset($_POST['com_pass'])) {
        $password = $_POST['password'];
        $com_pass = $_POST['com_pass'];
        
        if ($password != $com_pass) {
          $passwordError="Passwords do not match";
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $hashed_com_pass = password_hash($com_pass, PASSWORD_DEFAULT);
    }


    if ($passwordError === "") {
  if($isUpdate)
  {
    $id=$_POST['id'];
    $sql="UPDATE employees SET name='$name',email='$email',mobile_no='$mobile_no',address='$address' WHERE id='$id'";
    $isUpdate = true;
  }

  else{
  $sql="INSERT INTO employees(name,email,mobile_no,address,password,com_pass) 
  VALUES('$name','$email','$mobile_no','$address','$hashed_password','$hashed_com_pass')";
    $isUpdate = false;
  }

  if($conn->query($sql)===TRUE)
  {
    // echo "Employee added successfully!!";
    $showModal=true;
  }
  else{
    echo "Error adding employee = ".$conn->connect_error();
  }
}
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

  <style>     
      .btn-primary {
          background-color: #00796B; 
          border-color: #00796B;
          font-weight: bold; 
      }
      .btn-primary:hover {
      background-color: #004D40;
      border-color: #004D40;
    } 
      .p-text {
          font-weight: bold; 
      }
  </style>

</head>
<body class="bg-light">
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;background-color: #E3F2FD; border-radius:5%">
      <h2 class="text-center mb-4"><?php echo $employee?' Update User':'Add User';?></h2>
      <form action="signup.php" method="post" onsubmit="return checkEmail(event)">
        <div class="form-group">
        <?php if ($employee): ?>
        <input type="hidden" name="id" value="<?php echo $employee['id']; ?>">
    <?php endif; ?>
    
          <label for="name">Name:</label>
          <input type="text" name="name" id="name" class="form-control" required 
           value="<?php echo $employee ? htmlspecialchars($employee['name']) : ''; ?>">
        </div>
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" class="form-control" required
          value="<?php echo $employee ? htmlspecialchars($employee['email']) : ''; ?>"
          >
          <div id="emailError" class="error-message text-danger" ></div>

        </div>
        <div class="form-group">
          <label for="mobile_no">Mobile No:</label>
          <input type="text" name="mobile_no" id="mobile_no" class="form-control" required
          value="<?php echo $employee ? htmlspecialchars($employee['mobile_no']) : ''; ?>"
          >
        </div>
        <div class="form-group">
          <label for="address">Address:</label>
          <input type="text" name="address" id="address" class="form-control" required
          value="<?php echo $employee ? htmlspecialchars($employee['address']) : ''; ?>"
          >
        </div>
        <?php if(!$employee):?>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" class="form-control" required>

        </div>
        <div class="form-group">
          <label for="confirm_password">Confirm Password:</label>
          <input type="password" name="com_pass" id="com_pass" class="form-control" required>
          <div id="passwordError" class="error-message text-danger"><?php echo htmlspecialchars($passwordError); ?></div>
        </div>
        <?php endif;?>
        <button type="submit"  onclick="checkEmail(event)" class="btn btn-primary btn-block"><?php echo $employee?'Update':'Add';?></button>
       <?php if(!isset($_SESSION['id'])):?>
        <div class="text-center mt-3 p-text">
          <p>Already registered? <a href="login.php">Login</a></p>
        </div>
        <?php 
       endif;
        ?>
      </form>
    </div>
  </div>


   <!-- Bootstrap Modal -->
   <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="successModalLabel">Success</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location.href='list.php';">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        Employee <?php echo isset($isUpdate) && $isUpdate ? 'updated' : 'added'; ?> successfully!!
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="window.location.href='list.php';">OK</button>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Show modal if PHP variable is set
    <?php if ($showModal) : ?>
      $(document).ready(function() {
          $('#successModal').modal('show');
      });
    <?php endif; ?>


 function checkEmail(event) {
  event.preventDefault(); // Prevents the form from submitting immediately
  
  const email = document.getElementById('email').value;
  const emailError = document.getElementById("emailError");

   // Check for adding a new employee 
   const isUpdate = document.querySelector("input[name='id']") !== null;

// If it's an update, submit the form without checking the email
if (isUpdate) {
    document.querySelector("form").submit();
    return;
}

  if (email.trim() === "") {
    return;
  }

  fetch('check_mail.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json'
    },
    body: JSON.stringify({ email })
  })
  .then(response => response.json())
  .then(data => {
    if (data.exists) {
      emailError.textContent = "Email is already registered!";
    } else {
      document.querySelector("form").submit(); // Submit the form only if email is unique
    }
  })
  .catch(error => console.error('Error:', error));
}


  document.getElementById("email").addEventListener("input", function() {
      document.getElementById("emailError").textContent = "";
    });

    document.getElementById("password").addEventListener("input", function() {
    document.getElementById("passwordError").textContent = "";
});

document.getElementById("com_pass").addEventListener("input", function() {
    document.getElementById("passwordError").textContent = "";
});

  </script>
</body>
</html>
