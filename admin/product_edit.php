<?php
session_start();
require '../config/config.php';
require '../config/common.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php");
}
if ($_SESSION['user_role'] != 1) {
    header("location: login.php?error=password");
}
if ($_POST) {
    if (
        empty($_POST['name']) || empty($_POST['description']) || empty($_POST['quantity'])
        || empty($_POST['category']) || empty($_POST['price'])
    ) {
        if (empty($_POST['name'])) {
            $nameError = "Product's name is required";
        }
        if (empty($_POST['description'])) {
            $descError = "Product's description is required";
        }
        if (empty($_POST['quantity'])) {
            $quantityError = "Product's quantity is required";
        }
        if (empty($_POST['category'])) {
            $catError = "Product's category is required";
        }
        if (empty($_POST['price'])) {
            $priceError = "Product's price is required";
        }
    } elseif (!is_numeric($_POST['quantity']) || !is_numeric($_POST['price'])) {
        if (!is_numeric($_POST['quantity'])) $quantityError = "Quantity should be integer value";
        if (!is_numeric($_POST['price'])) $priceError = "Price should be integer value";
    } else {
        if ($_FILES['image']['error'] == 0) {
            $file = "images/" . $_FILES['image']['name'];
            $imageType = pathinfo($file, PATHINFO_EXTENSION);
            // var_dump($_FILES['image']);
            // die();
            if ($imageType != 'jpg' && $imageType != 'jpeg' && $imageType != 'png') {
                echo "<script>alert('Invlaid image type')</script>";
            } else {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $description = $_POST['description'];
                $quantity = $_POST['quantity'];
                $category = $_POST['category'];
                $price = $_POST['price'];
                $image = $_FILES['image']['name'];

                move_uploaded_file($_FILES['image']['tmp_name'], $file);
                $statement = $pdo->prepare("UPDATE products SET  name=:name,description=:description,category_id=:category,quantity=:quantity,price=:price,image=:image WHERE id=:id");
                $result = $statement->execute([
                    ':id' => $id,
                    ':name' => $name,
                    ':description' => $description,
                    ':category' => $category,
                    ':quantity' => $quantity,
                    ':price' => $price,
                    ':image' => $image
                ]);
                if ($result) {
                    echo "<script>alert('Successfully Updated Product.');window.location.href='index.php';</script>";
                }
            }
        } else {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $quantity = $_POST['quantity'];
            $category = $_POST['category'];
            $price = $_POST['price'];

            $statement = $pdo->prepare("UPDATE products SET  name=:name,description=:description,category_id=:category,quantity=:quantity,price=:price WHERE id=:id");
            $result = $statement->execute([
                ':id' => $id,
                ':name' => $name,
                ':description' => $description,
                ':category' => $category,
                ':quantity' => $quantity,
                ':price' => $price,
            ]);
            if ($result) {
                echo "<script>alert('Successfully Updated Product.');window.location.href='index.php';</script>";
            }
        }
    }
}
$statement = $pdo->prepare("SELECT * FROM products WHERE id=" . $_GET['id']);
$statement->execute();
$result = $statement->fetch(PDO::FETCH_ASSOC);
?>

<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Product</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" class="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" class="form-control" value="<?= $_SESSION['_token'] ?>">
                            <input type="hidden" name="id" class="form-control" value="<?= $result['id'] ?>">
                            <div class="form-group">
                                <label class="form-label">Name</label>
                                <p style="color:red"><?= isset($nameError) ? '*' . $nameError : '' ?></p>
                                <input type="text" class="form-control" name='name' value="<?= escape($result['name']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Description</label>
                                <p style="color:red"><?= isset($descError) ? '*' . $descError : '' ?></p>
                                <textarea name="description" class="form-control" rows="5"><?= escape($result['description']) ?></textarea>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Category</label>
                                <p style="color:red"><?= isset($catError) ? '*' . $catError : '' ?></p>
                                <select name="category" class="form-control">
                                    <option value="">Select Category</option>
                                    <?php
                                    $cat_stmt = $pdo->prepare("SELECT * FROM categories");
                                    $cat_stmt->execute();
                                    $cat_result = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($cat_result as $cat) :
                                        if ($cat['id'] == $result['category_id']) :
                                    ?>
                                            <option value="<?= $cat['id'] ?>" selected><?= $cat['name'] ?></option>
                                        <?php else : ?>
                                            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                                        <?php endif ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Quantity</label>
                                <p style="color:red"><?= isset($quantityError) ? '*' . $quantityError : '' ?></p>
                                <input type="number" name="quantity" class="form-control" value="<?= escape($result['quantity']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Price</label>
                                <p style="color:red"><?= isset($priceError) ? '*' . $priceError : '' ?></p>
                                <input type="number" name="price" class="form-control" value="<?= escape($result['price']) ?>">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Image</label><br>
                                <img src="images/<?= escape($result['image']) ?>" alt="" class="img img-thumbnail" width="300">
                                <p style="color:red"><?= isset($imageError) ? '*' . $imageError : '' ?></p>
                                <input type="file" name="image">
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="submit" value="SUBMIT" class="btn btn-primary">
                                <a href="index.php" type="button" class="btn btn-default">Back</a>
                            </div>
                        </form>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include 'footer.html' ?>