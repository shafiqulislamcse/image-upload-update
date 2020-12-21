<?php 
include('include/connection.php');

if (isset($_POST['submit'])) {
     $name =$_POST['name'];

    $image = $_FILES['photo']['name'];
    $image_tmp = $_FILES['photo']['tmp_name'];

    $location ="images/users/";

    $image_div = explode('.', $image);

    $image_uniq =strtolower(end($image_div));

    $image_uniq_name = substr(md5(time()),0,10).'.'.$image_uniq;

    $stmt_ins = $conn -> prepare("INSERT INTO images( name, images) VALUES (?, ?)");

    $stmt_ins -> bind_param("ss",$name, $image_uniq_name);

    if ($stmt_ins -> execute()) {
        move_uploaded_file($image_tmp, $location.$image_uniq_name);
        echo"<script>alert('Data inserted successfully.');</script>";
    }
    
}
 ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <title>File Uploads</title>
  </head>
  <body>
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="text-center mb-5">File upload and Edit</h1>
        </div> 
      </div>

      <div class="row justify-content-center">
       <div class="col-lg-8">
         <form class="row g-3" method="post" enctype="multipart/form-data">
          <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
          </div>
           <div class="col-md-6">
             <label for="photo" class="form-label">Choose Photo</label>
            <input type="file" class="form-control" id="photo" name="photo">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary" name="submit">Submit</button>
          </div>
        </form>
       </div>
      </div>
    </div>


    <!-- Data show -->
    <section>
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <h1 class="text-center mb-5">Show All Data</h1>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-12">
            <table class="table table-striped">
              <thead>
                 <tr>
                   <th>SL</th>
                   <th>Name</th>
                   <th>Photo</th>
                   <th>Action</th>
                 </tr>
              </thead>
              <tbody>
                <?php 
                  $stmt_view = $conn -> prepare("SELECT * FROM images");

                  $stmt_view -> execute();

                  $view_result = $stmt_view -> get_result();


                  while ($row = $view_result -> fetch_assoc()) {
                   ?>

                   <tr>
                     <td><?php echo $row['id'];?></td>
                     <td><?php echo $row['name'];?></td>
                     <td><img src="images/users/<?php echo $row['images'];?>" style="width: 100px;height: 120px;"></td>
                     <td>
                       <a href="upload_edit.php?id=<?php echo $row['id'];?>" class="badge bg-primary">Edit</a>
                       ||
                       <a href="upload_delete.php?id=<?php echo $row['id'];?>" class="badge bg-danger">Delete</a>
                     </td>
                   </tr>


                   <?php
                  }
                 ?>
          
              </tbody>
            </table>
          </div>
        </div>
      </div> 
    </section>



    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  </body>
</html>