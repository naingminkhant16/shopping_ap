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

    if (empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || strlen($_POST['password']) < 6 || empty($_POST['phone']) || empty($_POST['address'])) {
        if (empty($_POST['name'])) {
            $nameError = "Name is required";
        }
        if (empty($_POST['email'])) {
            $emailError = "Email is required";
        }
        if (empty($_POST['password'])) {
            $passwordError = "Password is required";
        } elseif (strlen($_POST['password']) < 6) {
            $passwordError = "Password must have atleast 6 characters";
        }
        if (empty($_POST['phone'])) {
            $phoneError = "Phone is required";
        }
        if (empty($_POST['address'])) {
            $addressError = "Address is required";
        }
    } else {
        if (isset($_POST['admin'])) {
            $role = 1;
        } else {
            $role = 0;
        }
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        $statement = $pdo->prepare("SELECT * FROM users WHERE email=:email");
        $statement->execute([':email' => $email]);
        $user = $statement->fetchAll();
        if ($user) {
            echo "<script>alert('Email already exist! Try again.');window.location.href='manageUsers.php'</script>";
        } else {
            $statement = $pdo->prepare("INSERT INTO users(name,email,password,phone,address,role) VALUES (:name,:email,:password,:phone,:address,:role)");
            $result = $statement->execute([
                ':name' => $name,
                ':email' => $email,
                ':password' => $password,
                ':phone' => $phone,
                ':address' => $address,
                ':role' => $role
            ]);
            if ($result) {
                echo "<script>alert('Successfully added User');window.location.href='manageUsers.php'</script>";
            }
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
                        <h3 class="card-title">Create New User</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <form action="createUser.php" class="" method="POST" enctype="multipart/form-data">
                            <input type="hidden" name="_token" class="form-control" value="<?= $_SESSION['_token'] ?>">
                            <div class="form-group">
                                <label type="text" class="form-label">Name</label>
                                <p style="color:red"><?= isset($nameError) ? '*' . $nameError : '' ?></p>
                                <input type="text" class="form-control" name='name' required>
                            </div>
                            <div class="form-group">
                                <label type="text" class="form-label">E-mail</label>
                                <p style="color:red"><?= isset($emailError) ? '*' . $emailError : '' ?></p>
                                <input type="email" class="form-control" name='email' required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Password</label>
                                <p style="color:red"><?= isset($passwordError) ? '*' . $passwordError : '' ?></p>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone</label>
                                <p style="color:red"><?= isset($phoneError) ? '*' . $phoneError : '' ?></p>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Address</label>
                                <p style="color:red"><?= isset($addressError) ? '*' . $addressError : '' ?></p>
                                <textarea name="address" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="form-check form-group">
                                <input type="checkbox" class="form-check-input" name="admin" value="1">
                                <label class="form-check-label">Admin</label>
                            </div>
                            <br><br>
                            <div class="form-group">
                                <input type="submit" value="SUBMIT" class="btn btn-primary">
                                <a href="manageUsers.php" type="button" class="btn btn-default">Back</a>
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