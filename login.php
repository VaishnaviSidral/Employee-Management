<?php
include('db.php');
$username='';
$password='';
$errorMessage='';

$log=false;

if($_SERVER['REQUEST_METHOD']=='POST')
{
  $username=$_POST['email'];
  $password=$_POST['password'];

  $sql="SELECT * FROM employees WHERE email='$username'";
  $result=$conn->query($sql);

  if($result->num_rows>0)
  {
    $row=$result->fetch_assoc();

    if(password_verify($password,$row['password']))
    {

      session_start();
      $_SESSION['email']=$username;
      $_SESSION['id']=$row['id'];
      $_SESSION['name']=$row['name'];
      $log=true;
      header("Location:list.php");
      exit();
    }
    else{
      $errorMessage = "Invalid password";
    }
  }
  else{
    $errorMessage = "Mail is not registered please do registration!!";

  }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
 body {
      background-color: #E0F7FA; 
    }

    .card {
      background-color: #FFFFFF; 
      border: none; 
    }  
    .text-center {
      color: #00695C;
    }

    .btn-primary {
      background-color: #00796B;
      border-color: #00796B;
      font-weight: bold;
    }

    .btn-primary:hover {
      background-color: #004D40;
      border-color: #004D40;
    }

.alert-danger {
    background-color: #FFCCBC; 
    color: #BF360C; 
}
.btn-login {
      margin-top: 10%; 
    }
    .p-text{
      font-weight:bold;
    }

</style>

</head>

<body class="bg-light">
  <div class="container d-flex align-items-center justify-content-center" style="min-height: 100vh;">
    <div class="card shadow-lg p-4" style="width: 100%; max-width: 400px;background-color: #E3F2FD;border-radius:5%">
      <h2 class="text-center mb-4">Login</h2>
      <form  id="loginForm" action="login.php" method="post">
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" name="email" id="email" class="form-control" >
          <div id="emailError" class="error-message text-danger" ></div>
     
        </div>
        <div class="form-group">
          <label for="password">Password:</label>
          <input type="password" name="password" id="password" class="form-control" >
          <div id="passwordError" class="error-message text-danger" ></div>
     
        </div>
        <?php if ($errorMessage):  ?>
                <div class="alert alert-danger mt-3 text-center">
                    <?php echo $errorMessage; ?>
                </div>
            <?php endif; ?>
            
              <button type="submit" class="btn btn-primary btn-block btn-login">Login</button>

        <div class="text-center mt-3 p-text">
          <p>Not registered? <a href="signup.php">Sign up</a></p>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.getElementById("loginForm").addEventListener("submit", function(event) {
      let hasErrors = false;

      const email = document.getElementById("email");
      const password = document.getElementById("password");
      const emailError = document.getElementById("emailError");
      const passwordError = document.getElementById("passwordError");

      // Clear previous error messages
      emailError.textContent = "";
      passwordError.textContent = "";

      // Validate email field
      if (email.value.trim() === "") {
        emailError.textContent = "Email is required.";
        hasErrors = true;
      }

      // Validate password field
      if (password.value.trim() === "") {
        passwordError.textContent = "Password is required.";
        hasErrors = true;
      }

      // Prevent form submission if there are errors
      if (hasErrors) {
        event.preventDefault();
      }
    });

     // Clear error messages when user starts typing
     document.getElementById("email").addEventListener("input", function() {
      document.getElementById("emailError").textContent = "";
    });

    document.getElementById("password").addEventListener("input", function() {
      document.getElementById("passwordError").textContent = "";
    });
  </script>
</body>
</html>
