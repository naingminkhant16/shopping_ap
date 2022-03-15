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
                        <h3 class="card-title">Premium Customers</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">

                        <table id="d-table">
                            <thead>
                                <tr>
                                    <th style="width: 10px;">#</th>
                                    <th>Customer Name</th>
                                    <th>Total Price Bought</th>
                                    <th>E-mail</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM users");
                                $stmt->execute();
                                $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                // print_r("<pre>");
                                if ($result) :
                                    $i = 1;
                                    foreach ($result as $value) :
                                        $total = 0;
                                        $cus_stmt = $pdo->prepare("SELECT total_price FROM sale_orders WHERE user_id=:id");
                                        $cus_stmt->execute([':id' => $value['id']]);
                                        $cus_result = $cus_stmt->fetchAll(PDO::FETCH_ASSOC);

                                        foreach ($cus_result as $price) {
                                            $total += $price['total_price'];
                                        }
                                        if ($total > 1000) :

                                ?>
                                            <tr>
                                                <td><?= $i ?></td>
                                                <td><?= escape($value['name']) ?></td>
                                                <td>$<?= escape($total) ?></td>
                                                <td><?= escape($value['email']) ?></td>
                                            </tr>
                                <?php $i++;
                                        endif;
                                    endforeach;
                                endif;
                                ?>
                            </tbody>
                        </table><br>

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
<script>
    $(document).ready(function() {
        $('#d-table').DataTable();
    });
</script>