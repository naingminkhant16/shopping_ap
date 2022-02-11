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
$id = $_GET['id'];
$statement = $pdo->prepare("SELECT * FROM categories WHERE id=:id");
$statement->execute([':id' => $id]);
$value = $statement->fetch(PDO::FETCH_OBJ);

if ($_POST) {
    if (empty($_POST['name']) || empty($_POST['description'])) {
        if (empty($_POST['name'])) {
            $nameError = "Category Name is required";
        }
        if (empty($_POST['description'])) {
            $descError = "Category Description is required";
        }
    } else {
        $name = $_POST['name'];
        $description = $_POST['description'];
        $id = $_POST['id'];
        $statement = $pdo->prepare("UPDATE categories SET name=:name,description=:description WHERE id=:id");
        $result = $statement->execute([
            ':name' => $name,
            ':description' => $description,
            ':id' => $id
        ]);
        if ($result) {
            echo "<script>alert('Successfully Updated Category.');window.location.href='category.php';</script>";
        }
    }
}

?>

<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Category</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="" class="" method="POST">
                            <input type="hidden" name="_token" class="form-control" value="<?= $_SESSION['_token'] ?>">
                            <input type="hidden" name="id" class="form-control" value="<?= escape($value->id) ?>">
                            <div class="form-group">
                                <label type="text" class="form-label">Name</label>
                                <p style="color:red"><?= isset($nameError) ? '*' . $nameError : '' ?></p>
                                <input type="text" class="form-control" name='name' value="<?= escape($value->name) ?>">
                            </div>
                            <div class="form-group">
                                <label type="text" class="form-label">Description</label>
                                <p style="color:red"><?= isset($descError) ? '*' . $descError : '' ?></p>
                                <textarea name="description" class="form-control" rows="5"><?= escape($value->description) ?></textarea>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="submit" value="SUBMIT" class="btn btn-primary">
                                <a href="category.php" type="button" class="btn btn-default">Back</a>
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