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
<?php include 'header.php'; ?>

<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Order Details</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Order ID</th>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Order Date</th>
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

                                ////pdo section
                                $statement = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=" . $_GET['id']);
                                $statement->execute();
                                $RawResult = $statement->fetchAll();
                                //calculate count of pages 
                                $totalPages = ceil(count($RawResult) / $numOfrecs);
                                //offseted result values
                                $statement = $pdo->prepare("SELECT * FROM sale_order_detail WHERE sale_order_id=" . $_GET['id'] . " LIMIT $offset,$numOfrecs");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);

                                if ($result) :
                                    $i = 1;
                                    foreach ($result as $value) :
                                        $pstmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
                                        $pstmt->execute([':id' => $value['product_id']]);
                                        $presult = $pstmt->fetch(PDO::FETCH_ASSOC);
                                ?>
                                        <tr>
                                            <td><?= $i ?></td>

                                            <td><?= escape($value['sale_order_id']) ?></td>
                                            <td>
                                                <p><?= escape($presult['name']) ?></p>
                                            </td>
                                            <td>
                                                <p><?= escape($value['quantity']) ?></p>
                                            </td>
                                            <td>
                                                <p><?= escape(date('Y-m-d', strtotime($value['order_date']))) ?></p>
                                            </td>
                                        </tr>
                                <?php $i++;
                                    endforeach;
                                endif; ?>
                            </tbody>
                        </table><br>
                        <a href="order_list.php" type="button" class="btn btn-default">Back</a>
                        <nav aria-label="Page navigation example" style="float:right">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="?id=<?= $_GET['id'] ?>&pageNo=1">First</a></li>
                                <li class="page-item">
                                    <a class="page-link btn <?php
                                                            if ($pageNo <= 1) {
                                                                echo "disabled";
                                                            } ?>" href="<?php if ($pageNo <= 1) {
                                                                            echo "#";
                                                                        } else {
                                                                            echo "?id=" . $_GET['id'] . "&pageNo=" . $pageNo - 1;
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
                                                                            echo "?id=" . $_GET['id'] . "&pageNo=" . $pageNo + 1;
                                                                        }
                                                                        ?>">Next</a>
                                </li>
                                <li class="page-item"><a class="page-link" href="?id=<?= $_GET['id'] ?>&pageNo=<?= $totalPages ?>">Last</a></li>
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