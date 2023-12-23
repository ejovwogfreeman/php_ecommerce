<?php

include('admincheck.php');
include('../config/db.php');
include('../partials/header.php');

function redirectWithMessage($message)
{
    header('Location: /php_ecommerce/index.php?message=' . urlencode($message));
    exit();
}

$name = $description = $price = $image = $Err = '';

if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        $Err = 'PLEASE ADD PRODUCT NAME';
    } else {
        $name = htmlspecialchars($_POST['name']);
        if (empty($_POST['description'])) {
            $Err = 'PLEASE ADD PRODUCT DESCRIPTION';
        } else {
            $description = htmlspecialchars($_POST['description']);
            if (empty($_POST['price'])) {
                $Err = 'PLEASE ADD PRODUCT PRICE';
            } else {
                $price = htmlspecialchars($_POST['price']);
                if (empty($_FILES['image']['name'])) {
                    $Err = 'PLEASE ADD PRODUCT IMAGE';
                } else {
                    // Image handling
                    $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

                    if (in_array($imageFileType, $allowedExtensions)) {
                        $image = $_FILES['image']['tmp_name'];
                        $imageData = file_get_contents($image);
                        $imageData =  mysqli_real_escape_string($conn, $imageData);

                        $sql = "INSERT INTO products (product_name, product_description, product_price, product_image) VALUES ('$name', '$description', '$price', '$imageData')";

                        if (mysqli_query($conn, $sql)) {
                            $message = 'Product Uploaded Successfully';
                            redirectWithMessage($message);
                        } else {
                            echo "Error uploading product: " . mysqli_error($conn);
                        }
                    } else {
                        echo "Invalid file type. Allowed types: " . implode(', ', $allowedExtensions);
                    }
                }
            }
        }
    }
}

?>

<div class="container" style="margin-top: 100px;">

    <form action="upload_product.php" class='border rounded p-3 mt-5 m-auto form-style' method="post" enctype="multipart/form-data">
        <?php if ($Err) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong><?php echo $Err ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
        <?php endif ?>
        <h4 class="mb-3">UPLOAD A PRODUCT</h4>
        <div class="form-group mb-3">
            <label class="mb-2" for="name">Product Name:</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php echo $name ?>">
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="description">Product Description:</label>
            <textarea class="form-control" name="description" id="description" rows="4"><?php echo $description ?></textarea>
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="price">Product Price:</label>
            <input type="number" class="form-control" name="price" id="price" value="<?php echo $price ?>">
        </div>

        <div class="form-group mb-3">
            <label class="mb-2" for="image">Select Image (PNG, JPG, JPEG, WebP):</label> <br>
            <input type="file" class="form-control-file border rounded p-2" name="image" id="image" accept="image/png, image/jpeg, image/webp" style="width: 100% ">
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Upload Product</button>
    </form>

</div>

<?php include('../partials/footer.php'); ?>