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
            <h3 class="card-title">Weekly Report</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">

            <table id="d-table">
              <thead>
                <tr>
                  <th style="width: 10px;">#</th>
                  <th>Customer Name</th>
                  <th>Total Price</th>
                  <th>Order Date</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $currentDate = date('Y-m-d');
                $fromDate = date('Y-m-d', strtotime('tomorrow'));
                $toDate = date('Y-m-d', strtotime($currentDate . '-7 day'));

                $statement = $pdo->prepare("SELECT * FROM sale_orders WHERE order_date<:fromDate AND order_date>:toDate  ORDER BY id desc");
                $statement->execute([
                  ':fromDate' => $fromDate,
                  ':toDate' => $toDate
                ]);
                $result = $statement->fetchALl();

                if ($result) :
                  $i = 1;
                  foreach ($result as $value) :
                    $user_stmt = $pdo->prepare("SELECT * FROM users WHERE id=:id");
                    $user_stmt->execute([':id' => $value['user_id']]);
                    $user_result = $user_stmt->fetch(PDO::FETCH_ASSOC);
                ?>
                    <tr>
                      <td><?= $i ?></td>
                      <td><?= escape($user_result['name']) ?></td>
                      <td><?= escape($value['total_price']) ?></td>
                      <td><?= escape(date('Y-m-d', strtotime($value['order_date']))) ?></td>
                    </tr>
                <?php $i++;
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