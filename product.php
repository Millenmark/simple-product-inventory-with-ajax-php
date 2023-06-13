<?php
require "database.php";
$conn = connectToDatabase();

// GET THE INFO OF A SINGLE PRODUCT
if(isset($_GET["productId"])) {
  $product_id = mysqli_real_escape_string($conn, $_GET["productId"]);

  $query = "SELECT * FROM products WHERE id='$product_id'";
  $query_run = mysqli_query($conn, $query);

  if(mysqli_num_rows($query_run) == 1) {
    $product = mysqli_fetch_array($query_run);
    $res = [
      "status" => 200,
      "message" => "Product Fetch Successfully",
      "data" => $product
    ];

    echo json_encode($res);
    return false;
  } else {
    $res = [
      "status" => 404,
      "message" => "Product Id Not Found",
    ];

    echo json_encode($res);
    return false;
  }
}

// SAVING PRODUCT INTO THE DATABASE
if(isset($_POST["save_product"])) {
  $name = mysqli_real_escape_string($conn, $_POST["name"]);
  $unit = mysqli_real_escape_string($conn, $_POST["unit"]);
  $price = mysqli_real_escape_string($conn, $_POST["price"]);
  $expdate = mysqli_real_escape_string($conn, $_POST["expdate"]);
  $stocks = mysqli_real_escape_string($conn, $_POST["stocks"]);
  
  if($_FILES["image"]["error"] == 4) {
    $res = [
      "status" => 422,
      "message" => "Error Uploading Image",
    ];

    echo json_encode($res);
    return false;
  } else {
    $fileName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode(".", $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if(!in_array($imageExtension, $validImageExtension)) {
      $res = [
        "status" => 422,
        "message" => "Invalid Image Extension",
      ];

      echo json_encode($res);
      return false;
    } else if ($fileSize > 1000000) {
      $res = [
        "status" => 422,
        "message" => "Image Size Too Large",
      ];

      echo json_encode($res);
      return false;
    } else {
      $newImageName = uniqid();
      $newImageName .= "." . $imageExtension;

      move_uploaded_file($tmpName, "./img/" . $newImageName);
    }
  }


  if($name == NULL || $unit == NULL || $price == NULL || $stocks == NULL) {
    $res = [
      "status" => 422,
      "message" => "All fields are requirsed",
    ];

    echo json_encode($res);
    return false;
  }

  $query = "INSERT INTO products (name, unit, price, expiration_date, stocks , image) VALUES ('$name', '$unit', '$price', '$expdate', '$stocks', '$newImageName')";

  $query_run = mysqli_query($conn, $query);

  if($query_run) {
    $res = [
      "status" => 200,
      "message" => "Product Created Successfully!"
    ];
    echo json_encode($res);
    return false;
  } else {
    $res = [
      "status" => 500,
      "message" => "Product Not Created"
    ];
    echo json_encode($res);
    return false;
  }
}


// EDIT PRODUCT FROM THE DATABASE
if(isset($_POST["edit_product"])) {
  $productID = mysqli_real_escape_string($conn, $_POST["productId"]);
  $name = mysqli_real_escape_string($conn, $_POST["name"]);
  $unit = mysqli_real_escape_string($conn, $_POST["unit"]);
  $price = mysqli_real_escape_string($conn, $_POST["price"]);
  $expdate = mysqli_real_escape_string($conn, $_POST["expdate"]);
  $stocks = mysqli_real_escape_string($conn, $_POST["stocks"]);

  // Retrieve existing image name from the database
  $query = "SELECT image FROM products WHERE id = '$productID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $existingImage = $row["image"];

  // Handle image upload
  if($_FILES["image"]["error"] != 4) {
    // Image is changed, upload the new image
    $fileName = $_FILES["image"]["name"];
    $fileSize = $_FILES["image"]["size"];
    $tmpName = $_FILES["image"]["tmp_name"];

    $validImageExtension = ['jpg', 'jpeg', 'png'];
    $imageExtension = explode(".", $fileName);
    $imageExtension = strtolower(end($imageExtension));
    if(!in_array($imageExtension, $validImageExtension)) {
      $res = [
        "status" => 422,
        "message" => "Invalid Image Extension",
      ];

      echo json_encode($res);
      return false;
    } else if ($fileSize > 1000000) {
      $res = [
        "status" => 422,
        "message" => "Image Size Too Large",
      ];

      echo json_encode($res);
      return false;
    } else {
      // Delete the existing image file if it exists
      if (!empty($existingImage)) {
        $existingImagePath = "./img/" . $existingImage;
        if (file_exists($existingImagePath)) {
          unlink($existingImagePath);
        }
      }
      
      $newImageName = uniqid();
      $newImageName .= "." . $imageExtension;

      move_uploaded_file($tmpName, "./img/" . $newImageName);

      
    }
  } else {
    // Image is not changed, keep the existing image
    $newImageName = $existingImage;
  }

  if($name == NULL || $unit == NULL || $price == NULL || $stocks == NULL) {
    $res = [
      "status" => 422,
      "message" => "All fields are required",
    ];

    echo json_encode($res);
    return false;
  }

  $query = "UPDATE products SET name = '$name', unit = '$unit', price = '$price', expiration_date = '$expdate', stocks = '$stocks', image = '$newImageName' WHERE id = '$productID'";

  $query_run = mysqli_query($conn, $query);

  if($query_run) {
    $res = [
      "status" => 200,
      "message" => "Product Updated Successfully!"
    ];
    echo json_encode($res);
    return false;
  } else {
    $res = [
      "status" => 500,
      "message" => "Product Not Updated"
    ];
    echo json_encode($res);
    return false;
  }
}

// DELETING A PRODUCT
if(isset($_POST["delete_product"])) {
  $productID = mysqli_real_escape_string($conn, $_POST["product_id"]);

  // Retrieve image name from the database
  $query = "SELECT image FROM products WHERE id = '$productID'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $image = $row["image"];

  // Delete the product from the database
  $deleteQuery = "DELETE FROM products WHERE id = '$productID'";
  $deleteResult = mysqli_query($conn, $deleteQuery);

  if($deleteResult) {
    // Delete the associated image file if it exists
    if (!empty($image)) {
      $imagePath = "./img/" . $image;
      if (file_exists($imagePath)) {
        unlink($imagePath);
      }
    }

    $res = [
      "status" => 200,
      "message" => "Product Deleted Successfully!"
    ];
    echo json_encode($res);
    return false;
  } else {
    $res = [
      "status" => 500,
      "message" => "Product Not Deleted"
    ];
    echo json_encode($res);
    return false;
  }
}
?>