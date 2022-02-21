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
                        <h3 class="card-title">Best Seller Items</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <div class="row row-cols-1 row-cols-md-4">
                            <?php
                            $stmt = $pdo->prepare("SELECT * FROM products");
                            $stmt->execute();
                            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            // print_r("<pre>");
                            if ($result) :
                               
                                foreach ($result as $value) :
                                    $total = 0;
                                    $best_stmt = $pdo->prepare("SELECT quantity FROM sale_order_detail WHERE product_id=:id");
                                    $best_stmt->execute([':id' => $value['id']]);
                                    $best_result = $best_stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($best_result as $qty) {
                                        $total += $qty['quantity'];
                                    }
                                    if ($total > 1) :


                            ?>
                                        <div class="col">
                                            <div class="card">
                                                <img class="card-img-top" src="images/<?= escape($value['image']) ?>" height="250">
                                                <div class="card-body text-center">
                                                    <h5 class="text-bold"><?= escape($value['name']) ?></h5>
                                                    <p>Price - <?= escape($value['price']) ?></p>
                                                </div>
                                            </div>
                                        </div>
                            <?php
                                    endif;
                                endforeach;
                            endif;
                            ?>
                        </div>
                        <!-- <a href="logout.php" type="button" class="btn btn-secondary float-right">Logout</a><br> -->

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