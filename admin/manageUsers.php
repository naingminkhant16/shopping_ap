<?php
session_start();
require '../config/config.php';
require '../config/common.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'])) {
    header("location: login.php?error=login");
}
if ($_SESSION['user_role'] != 1) {
    header("location: login.php?error=password");
}

?>
<?php include 'header.php' ?>
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Manage Users</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="createUser.php" class="btn btn-success">Create User</a><br><br>
                        <table class="table table-striped table-dark">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Role</th>
                                    <th style="width: 30%">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($_POST['search'])) {
                                    $statement = $pdo->prepare("SELECT * FROM users");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_OBJ);
                                } else {
                                    $searchKey = $_POST['search'];
                                    $statement = $pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%'");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_OBJ);
                                }

                                if ($result) :
                                    foreach ($result as $user) : ?>
                                        <tr>
                                            <th scope="row"><?= $user->id ?></th>
                                            <td><?= escape($user->name) ?></td>
                                            <td><?= escape($user->email) ?></td>
                                            <td><?= escape($user->phone) ?></td>
                                            <td><?= escape($user->address) ?></td>
                                            <td><?php if ($user->role > 0) {
                                                    echo 'Admin';
                                                } else {
                                                    echo "User";
                                                }
                                                ?></td>
                                            <td><?php if ($_SESSION['user_id'] == $user->id) {
                                                    echo "###";
                                                } else { ?>
                                                    <a href="changeRole.php?id=<?= $user->id ?>& role=<?= $user->role ?>" onclick="return confirm('Are you sure you want to change user\'s role?')" class="btn btn-sm btn-primary">Role</a>
                                                    <a href="editUser.php?id=<?= $user->id ?>" class="btn btn-sm btn-warning">Edit</a>
                                                    <a href="deleteUser.php?id=<?= $user->id ?>" onclick="return confirm('Are you sure you want to delete?')" class="btn btn-sm btn-danger">Delete</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                <?php endforeach;
                                endif; ?>
                            </tbody>
                        </table>

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