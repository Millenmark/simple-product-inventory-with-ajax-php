<?php require "database.php"; $conn = connectToDatabase(); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Millen Mark | PHP Crud App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  </head>
  <body>
    <!-- ADD PRODUCT MODAL -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Add Product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="saveProduct" enctype="multipart/form-data">
            <div class="modal-body">
              <div id="errorMessage" class="alert alert-warning d-none">
                
              </div>
              <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" class="form-control">
              </div>

              <div class="mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" class="form-control">
              </div>

              <div class="mb-3">
                <label for="price">Price</label>
                <input type="number" name="price" step="0.01" class="form-control">
              </div>


              <div class="mb-3">
                <label for="expdate">Expiration Date</label>
                <input type="date" name="expdate" class="form-control">
              </div>
              
              <div class="mb-3">
                <label for="stocks">Stocks</label>
                <input type="number" name="stocks" class="form-control">
              </div>

              <div class="mb-3">
                <label for="image">Image</label>
                <input type="file" name="image" accept=".jpg, .jpeg, .png">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Save Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <!-- EDIT PRODUCT MODAL -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h1 class="modal-title fs-5" id="exampleModalLabel">Edit This Product</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <form id="editProduct" enctype="multipart/form-data">
            <div class="modal-body">
              <div id="errorMessage" class="alert alert-warning d-none"></div>
              <img id="existingImage" class="mb-3">
              <input type="hidden" name="productId" id="productId">
              <div class="mb-3">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" class="form-control">
              </div>

              <div class="mb-3">
                <label for="unit">Unit</label>
                <input type="text" name="unit" id="unit" class="form-control">
              </div>

              <div class="mb-3">
                <label for="price">Price</label>
                <input type="number" name="price" id="price" step="0.01" class="form-control">
              </div>


              <div class="mb-3">
                <label for="expdate">Expiration Date</label>
                <input type="date" name="expdate" id="expdate" class="form-control">
                <h6>Note: Set the exp date again to be able to click the update</h6>
              </div>
              
              <div class="mb-3">
                <label for="stocks">Stocks</label>
                <input type="number" name="stocks" id="stocks" class="form-control">
              </div>

              <div class="mb-3">
                <label for="image">Image</label>
                <input type="file" name="image" id="image" accept=".jpg, .jpeg, .png">
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary">Update Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>


  <!-- CONTAINER -->
    <div class="container pt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex flex-column justify-content-between align-items-center flex-md-row">
              <h3>Product Management</h3>
              <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add Product</button>
            </div>
            <div class="card-body">
              <div id="myData" class="row">
                    <?php 
                      $query = "SELECT * FROM products";
                      $query_run = mysqli_query($conn, $query);

                      if(mysqli_num_rows($query_run) > 0) {
                        foreach($query_run as $product) {
                          ?>
                          <div class="col-md-4">
                            <div class="card product-item overflow-hidden">
                              <img src="./img/<?=$product["image"]?>" alt="Product Image">
                              <div class="card-body">
                                <h5><?= $product["name"] ?></h5>
                                <div class="d-flex flex-row justify-content-between">
                                  <p style="margin: 0;" class="fw-semibold">Unit: <?= $product["unit"] ?></p>
                                  <p style="margin: 0;" class="fw-semibold">₱<?= $product["price"] ?></p>
                                </div>

                                <div class="d-flex flex-row justify-content-between mt-1 align-items-center">
                                  <p style="margin: 0; font-size: 14px; color: #707070;" class="mt-1">Exp: <?= $product["expiration_date"] ?></p>
                                  <p class="m-0 fw-semibold" style="font-size: 14px; color: #707070;">Stocks: <?= $product["stocks"] ?></p>
                                </div>
                                
                                <div class="float-end mt-3">
                                  <button type="button" value="<?= $product["id"] ?>" class="editProductBtn btn"><i class="fa-solid fa-pen-to-square" style="color: #80ff80;"></i></button>
                                  <button type="button" value="<?= $product["id"] ?>" class="deleteProductBtn btn"><i class="fa-solid fa-trash-can" style="color: #ff0000;"></i></button>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php
                        }
                      }
                    ?>
                  </div>
            </div>
          </div>
        </div>
      </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    
  </body>
</html>
