<?php
 ################################# login check #######################################
  session_start();
  #### included connection.php
  include_once ('connection.php');
  #checking for login
  if (!isset($_SESSION['person_id']))
     header('Location: http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/login-panel.php');

  #checking if pid and peson id is same or not
  if(!isset($_GET['id']))
     die("<h2 style='text-align:center;'>Access Denied !!</h2> ");

  # fetching id of category or product
  $id = $_GET['id'];

  # for category only for admin
  if ($_SESSION['role'] == 1)
     $result = mysqli_query($conn,"SELECT * FROM category WHERE cat_id = $id") or die("Unsuccessfull");

  #for product only for buyer
  else if($_SESSION['role'] == 0)
     $result = mysqli_query($conn,"SELECT * FROM product WHERE product_id = $id  AND dealer_id = {$_SESSION['person_id']}") or die("Unsuccessfull");

  #checking if there is any result
  if(mysqli_num_rows($result) == 0)
    die('Unsuccessfully');

    $row = mysqli_fetch_array($result);
 ?>




<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <title></title>
  </head>
  <body>

<?php

## show category form only to admin
if($_SESSION['role'] == 1){
?>


    <!-- update category form --------------------------------------------------->
    <div class='container mt-5 jumbotron'>

      <form action='<?php $_SERVER["PHP_SELF"] ;?>' method="post" enctype="multipart/form-data">

        <h1 style ='text-align:center;'><b>UPDATE CATEGORY<br></b></h1>

        <div class="container form-group">
          <label for="catname"><b>Category Name</b></label>
          <input class="form-control" type="text" placeholder="Enter Category Name" name="catname" value="<?php echo $row['cat_name'] ?>" required >

          <label for="catimg" class = "labelimg mt-3">Select Image:</label>
          <input  type="file" id="catimg" name="filephoto" class = "labelimg" >
          <img class='my-5' src="<?php echo $cat_image_location . $row['cat_img'] ?>" alt="" border="3" height="150" width="250"></img>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-success" name="submit">Submit</button>
          <span class='float-right'><a class='btn btn-success' href="admin-panel.php">Cancel</a></span>
        </div>


      </form>
    </div>
    <!-- end of update category form --------------------------------------------------->




<?php
}
## show update product form only to  thier dealer
else{
?>




<!-- add product form ------------------------------------------------------------------------------------------->
<div class='container mt-5 jumbotron'>

  <form action='<?php $_SERVER["PHP_SELF"] ;?>' method="post" enctype="multipart/form-data">

    <h1 style ='text-align:center;'><b>UPDATE PRODUCT<br></b></h1>

    <div class="container form-group">

      <label for="pro_name"><b>Product Name</b></label>
      <input class="form-control" type="text" placeholder="Enter Product Name" name="pro_name" value="<?php echo $row['Name'] ?>" required>

      <label for="cat_id"><b>Type</b></label>
      <select class="form-control" class="form-control" name="cat_id" required>

         <?php
################################## category option start ##########################################
            $get_category_sql = "SELECT * FROM category";
            $result2 = mysqli_query($conn,$get_category_sql) or die('Query failed');
            while($row2 = mysqli_fetch_assoc($result2))
            {
              $selected ='';
              if($row['category_id'] == $row2['cat_id'])
                 $selected = 'selected';
         ?>
         <option value="<?php echo $row2['cat_id'] ?>" <?php echo $selected ?> >   <?php echo $row2['cat_name']; ?>  </option>

      <?php
         }
###############################category option close ##############################################
       ?>

      </select>

      <label for="brand"><b>Brand</b></label>
      <input class="form-control" type="text" placeholder="Enter Brand Name" name="brand" value="<?php echo $row['Brand'] ?>" required>

      <label for="desc">Description:</label>
      <textarea class="form-control" rows="6" id="desc" name='desc' required> <?php echo $row['Desc'] ?></textarea>

      <label for="price"><b>Price</b></label>
      <input type="number" class="form-control" placeholder="Enter price" name="price" min='100' max='100000' value="<?php echo $row['Price'] ?>" required>

      <label for="qty"><b>Quantity</b></label>
      <input class="form-control" type="number" class="form-control" placeholder="Enter Quantity" name="qty" min='1' max='100000' value="<?php echo $row['Quantity'] ?>" required>


        <label for="photo1" class = "labelimg my-3">.Select 1st Image:</label>
        <input type="file" id="photo1" name="photo1" class = "labelimg">
        <img class='my-3' src="<?php echo $prod_image_location .$row['first_image'] ?>" alt="" border="3" height="150" width="250">
        <br>
        <label for="photo2" class = "labelimg">Select 2nd Image:</label>
        <input type="file" id="photo2" name="photo2" class = "labelimg">
        <img class='my-3' src="<?php echo $prod_image_location .$row['second_image'] ?>" alt="" border="3" height="150" width="250">

        <div class="form-group">
          <button type="submit" class="btn btn-success" name="submit">Submit</button>
          <span class='float-right'><a class='btn btn-success' href="dealer-home.php">Cancel</a></span>
        </div>

    </div>

  </form>
</div>
<!--add product form close--------------------------------------------------------------------------------->

<?php
}#else closing
 ?>
  </body>
</html>