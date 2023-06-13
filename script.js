$(document).on("submit", "#saveProduct", function (e) {
  e.preventDefault();

  let formData = new FormData(this);
  formData.append("save_product", true);

  $.ajax({
    type: "POST",
    url: "./product.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      let res = jQuery.parseJSON(response);
      if (res.status === 422 || res.status === 500) {
        $("#errorMessage").removeClass("d-none");
        $("#errorMessage").text(res.message);
      } else if (res.status === 200) {
        $("#errorMessage").addClass("d-none");
        $("#addProductModal").modal("hide");
        $("#saveProduct")[0].reset();

        $("#myData").load(location.href + " " + "#myData");
      }
    },
  });
});

$(document).on("click", ".editProductBtn", function () {
  let productId = $(this).val();

  $.ajax({
    type: "GET",
    url: "./product.php?productId=" + productId,
    success: function (response) {
      let res = jQuery.parseJSON(response);
      if (res.status === 422) {
        alert(res.message);
      } else if (res.status === 200) {
        $("#productId").val(res.data.id);
        $("#name").val(res.data.name);
        $("#unit").val(res.data.unit);
        $("#price").val(res.data.price);
        $("#expdate").val(res.data.expdate);
        $("#stocks").val(res.data.stocks);
        $("#existingImage").attr("src", "./img/" + res.data.image);
        $("#editProductModal").modal("show");
      }
    },
  });
});

$(document).on("submit", "#editProduct", function (e) {
  e.preventDefault();

  let formData = new FormData(this);
  formData.append("edit_product", true);

  $.ajax({
    type: "POST",
    url: "./product.php",
    data: formData,
    processData: false,
    contentType: false,
    success: function (response) {
      let res = jQuery.parseJSON(response);
      if (res.status === 422) {
        $("#errorMessageUpdate").removeClass("d-none");
        $("#errorMessageUpdate").text(res.message);
      } else if (res.status === 200) {
        $("#errorMessageUpdate").addClass("d-none");
        $("#editProductModal").modal("hide");
        $("#myData").load(location.href + " " + "#myData");
      }
    },
  });
});

$(document).on("click", ".deleteProductBtn", function (e) {
  e.preventDefault();

  if (confirm("Are you sure you want to delete this?")) {
    let productId = $(this).val();
    $.ajax({
      type: "POST",
      url: "./product.php",
      data: {
        delete_product: true,
        product_id: productId,
      },
      success: function (response) {
        let res = jQuery.parseJSON(response);

        if (res.status == 500) {
          alert(res.message);
        } else {
          alert(res.message);

          $("#myData").load(location.href + " " + "#myData");
        }
      },
    });
  }
});
