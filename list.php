<?php
session_start();
include('db.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Employee List</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

  <style>
      table {
          width: 100%; 
      }
      th, td {
          text-align: center; 
      }
      .navbar {
    background-color:#00796B; 
}

.navbar .navbar-brand,
.navbar .btn-light, 
.navbar .btn-danger { 
    font-weight: bold; 
}
.navbar-brand{
    margin-left:5%;
}

.navbar .btn-primary {
    background-color: #00796B; 
    border-color: #00796B;
    font-weight: bold;
}

.navbar .btn-primary:hover {
    background-color: #004D40;
    border-color: #004D40;
}

      .btn-danger {
          background-color: #dc3545; 
          border: none; 
      }
      .btn-danger:hover {
          background-color: #c82333; 
          transform: scale(1.05); 
      }
      .btn-primary {
          background-color: #007bff;
          border: none; 
      }
      .btn-primary:hover {
          background-color: #0056b3; 
          transform: scale(1.05); 
      }

      .custom-thead {
    background-color: #00796B; 
    color: #ffffff; 
}
.ml-auto{
    margin-right:3%;
}

  </style>
</head>
<body class="bg-light">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark ">
    <a class="navbar-brand" href="#">Welcome,
     <?php echo isset($_SESSION['name'])?$_SESSION['name']:'User' ;?>
         </a>
      <div class="ml-auto">
    <?php if (isset($_SESSION['id'])):  ?>
        <a href="signup.php" class="btn btn-light mr-2">Add New</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    <?php else:  ?>
        <a href="login.php" class="btn btn-light">Login</a>
    <?php endif; ?>
</div>
  </nav>

  <div class=" mt-5">
    <h3 class="text-center mb-4">Employee List</h3>
    <table class="table table-bordered table-striped">
      <thead class="custom-thead">
        <tr>
          <th>Sr. No</th>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile No</th>
          <th>Address</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
    
      <?php
      $sql_select="SELECT * FROM employees";
      $result=$conn->query($sql_select);
      $srno=0;
      if($result->num_rows>0)
      {
        while($row=$result->fetch_assoc())
        {
          $srno++;
          echo "<tr>".
          "<td>$srno</td>".
          "<td>{$row['name']}</td>".
          "<td>{$row['email']}</td>".
          "<td>{$row['mobile_no']}</td>".
          "<td>{$row['address']}</td>".
         "<td>".
       " <a class='btn btn-primary' title='Edit' onclick='checkLogin(event, \"signup.php?id={$row['id']}\")'>".
         " <i class='bi bi-pencil'></i>".
      "</a>".
         " <button class='btn btn-danger' title='Delete' onclick='confirmDelete({$row['id']})'>".
        "  <i class='bi bi-trash'></i>".
     " </button>".
         "</td>".
          "</tr>";
        }
      }

      ?>
      </tbody>
    </table>
  </div>


   <!-- Login Required Modal -->
   <div class="modal fade" id="loginRequiredModal" tabindex="-1" role="dialog" aria-labelledby="loginRequiredLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="loginRequiredLabel">Login Required</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  You need to log in first.
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                  <a href="login.php" class="btn btn-primary">Login</a>
              </div>
          </div>
      </div>
  </div>
  
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Do you really want to delete this employee?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">Yes, Delete</button>
            </div>
        </div>
    </div>
</div>

<!-- Notification for delete,need to login first Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" role="dialog" aria-labelledby="notificationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="notificationModalLabel">Login Required</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="notificationMessage">
                <!-- Message will be shown here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">OK</button>
            </div>
        </div>
    </div>
</div>



  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    let deleteId;

    function confirmDelete(id) {
        const currentUserId = <?php echo json_encode(isset($_SESSION['id']) ? $_SESSION['id'] : null); ?>;
        if (!currentUserId)//if user not loggedin then
         {
        $('#notificationMessage').text('You need to log in first to delete.');
        $('#notificationModal').modal('show');
    } else if (id == currentUserId) //if login user try to delete his own record then
    {
            $('#notificationMessage').text('Cannot delete yourself');
            $('#notificationModal').modal('show');
        } else {
            deleteId = id;
            $('#deleteModal').modal('show');
        }
    }


    function checkLogin(event, link) {
    const userId = <?php echo json_encode(isset($_SESSION['id']) ? $_SESSION['id'] : null); ?>; 
    if (!userId) {
        event.preventDefault(); 
        $('#notificationMessage').text('You need to log in first to update.');
        $('#notificationModal').modal('show');
    } else {
        window.location.href = link; 
    }
}

    document.getElementById('confirmDeleteButton').addEventListener('click', function() {
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { id: deleteId },
            dataType: 'json', 
            success: function(response) {
                $('#deleteModal').modal('hide');  
                if (response.status === 'success') {
                    $('#notificationMessage').text('Employee Deleted Successfully!!');
                } else {
                    $('#notificationMessage').text(response.message); 
                }
                $('#notificationModal').modal('show'); 
                $('#notificationModal').on('hidden.bs.modal', function() {
                    window.location.reload();  
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText); // Log the error response for debugging
                $('#notificationMessage').text('Error processing request: ' + xhr.responseText);
                $('#notificationModal').modal('show'); // Show the notification modal
            }
        });
    });
</script>

</body>
</html>
