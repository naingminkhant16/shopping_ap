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
if (isset($_POST['search'])) {
    setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
} else {
    if (empty($_GET['pageNo'])) {
        unset($_COOKIE['search']);
        setcookie('search', null, -1, '/');
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
                        <h3 class="card-title">Categories</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <a href="cat_add.php" class="btn btn-success">Create Category</a><br><br>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th style="width: 40px">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($_GET['pageNo'])) {
                                    $pageNo = $_GET['pageNo'];
                                } else {
                                    $pageNo = 1;
                                }
                                $numOfrecs = 2;
                                //algorithm to find offset value 
                                $offset = ($pageNo - 1) * $numOfrecs;
                                if (empty($_POST['search']) && empty($_COOKIE['search'])) {
                                    ////pdo section
                                    $statement = $pdo->prepare("SELECT * FROM categories ORDER BY id desc");
                                    $statement->execute();
                                    $RawResult = $statement->fetchALl();
                                    //calculate count of pages 
                                    $totalPages = ceil(count($RawResult) / $numOfrecs);
                                    //offseted result values
                                    $statement = $pdo->prepare("SELECT * FROM categories ORDER BY id desc LIMIT $offset,$numOfrecs");
                                    $statement->execute();
                                    $result = $statement->fetchALl();
                                } else {
                                    ////find Search value from search bar
                                    ////pdo section
                                    $searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
                                    $statement = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id desc");
                                    $statement->execute();
                                    $RawResult = $statement->fetchALl();
                                    //calculate count of pages 
                                    $totalPages = ceil(count($RawResult) / $numOfrecs);
                                    //offseted result values
                                    $statement = $pdo->prepare("SELECT * FROM categories WHERE name LIKE '%$searchKey%' ORDER BY id desc LIMIT $offset,$numOfrecs");
                                    $statement->execute();
                                    $result = $statement->fetchALl();
                                }
                                if ($result) :
                                    $i = 1;
                                    foreach ($result as $value) :
                                ?>
                                        <tr>
                                            <td><?= $i ?></td>
                                            <td><?= escape($value['name']) ?></td>
                                            <td>
                                                <p><?= escape(substr($value['description'], 0, 100)) ?></p>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <a href="cat_edit.php?id=<?= $value['id'] ?>" type="button" class="btn btn-warning m-1">Edit</a>
                                                    <a href="cat_delete.php?id=<?= $value['id'] ?>" onclick="return confirm('Are you sure you want to delete?')" type="button" class="btn btn-danger m-1">Delete</a>
                                                </div>
                                            </td>
                                        </tr>
                                <?php $i++;
                                    endforeach;
                                endif; ?>
                            </tbody>
                        </table><br>
                        <nav aria-label="Page navigation example" style="float:right">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?pageNo=1">First</a></li>
                                <li class="page-item">
                                    <a class="page-link btn <?php
                                                            if ($pageNo <= 1) {
                                                                echo "disabled";
                                                            } ?>" href="<?php if ($pageNo <= 1) {
                                                            echo "#";
                                                        } else {
                                                            echo "?pageNo=" . $pageNo - 1;
                                                        }

                                                        ?>">Previous</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="#"><?= $pageNo . ' of ' . $totalPages; ?></a></li>
                                <li class="page-item">
                                    <a class="page-link btn <?php if ($pageNo == $totalPages) {
                                                                echo "disabled";
                                                            } ?>" href="<?php if ($pageNo >= $totalPages) {
                                                            echo '#';
                                                        } else {
                                                            echo "?pageNo=" . $pageNo + 1;
                                                        }
                                                        ?>">Next</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="?pageNo=<?= $totalPages ?>">Last</a></li>
                            </ul>
                        </nav>
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