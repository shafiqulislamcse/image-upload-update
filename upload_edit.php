<?php 
include('include/connection.php');?>
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
        <?php 
          if (isset($_GET['id'])) {
              $id=$_GET['id'];

              $stmt_edit = $conn -> prepare("SELECT * FROM images WHERE id= ?");

              $stmt_edit -> bind_param("i",$id);

              $stmt_edit -> execute();

              $edit_result = $stmt_edit -> get_result();

              while ($row = $edit_result -> fetch_assoc()) {
                $name = $row['name'];
                $up_id = $row['id'];
                $img_file_old =$row['images'];
              }
            }

         ?>
      <div class="row justify-content-center">
       <div class="col-lg-8">
         <form class="row g-3" method="post" enctype="multipart/form-data">
          <div class="col-md-6">
            <label for="name" class="form-label">Name</label>
            <input type="text" class="form-control" value="<?php echo $name; ?>" id="name" name="name_new" required>
            <input type="hidden" class="form-control" value="<?php echo  $up_id; ?>" id="id" name="id">
          </div>
           <div class="col-md-6">
            <label for="photo" class="form-label">Choose Photo</label>
            <input type="file" class="form-control" id="photo" name="photo_new">
            <input type="hidden" class="form-control" value="<?php echo $img_file_old; ?>" id="photo" name="photo_old">
            <img src="images/users/<?php echo $img_file_old; ?>" style="width: 100px; height: 100px;">
          </div>
          <div class="col-12">
            <button type="submit" class="btn btn-primary" name="update">update</button>
          </div>
        </form>
       </div>
      </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
  </body>
</html>

<?php
  if (isset($_POST['update'])) {

    $id =$_POST['id'];
    $newName =$_POST['name_new'];

    $newImage =$_FILES['photo_new']['name'];
    $image_tmp_new = $_FILES['photo_new']['tmp_name'];
    $location_new ="images/users/";
    $image_div_new = explode('.', $newImage);
    $image_uniq_new =strtolower(end($image_div_new));
    $image_uniq_name_new = substr(md5(time()),0,10).'.'.$image_uniq_new;

    $old_image =$_POST['photo_old'];

   if ($newImage) {
      unlink("images/users/".$old_image);
      $stmt_up = $conn -> prepare("UPDATE images SET name= ?, images= ?  WHERE id= ?");
      $stmt_up -> bind_param("ssi",$newName,$image_uniq_name_new,$id);
      if ($stmt_up -> execute()) {
        move_uploaded_file($image_tmp_new, $location_new.$image_uniq_name_new);
        echo"<script>alert('updated successfully.');</script>";
        header('location:index.php');
      }
   }
   else{
    $stmt_up = $conn -> prepare("UPDATE images SET name= ?, images= ?  WHERE id= ?");
    $stmt_up -> bind_param("ssi",$newName,$old_image,$id);

    if ($stmt_up -> execute()) {
        echo"<script>alert('updated successfully.');</script>";
        header('location:index.php');
      }
   }

   
  
  }

?>