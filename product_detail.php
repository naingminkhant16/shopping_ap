<?php include('header.php') ?>
<?php
require 'config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['user_role'])) {
  header("location: login.php");
}

$statement = $pdo->prepare("SELECT * FROM products WHERE id=:pid");
$statement->execute([':pid' => $_GET['pid']]);
$result = $statement->fetch(PDO::FETCH_ASSOC)
?>
<!--================Single Product Area =================-->
<div class="product_image_area">
  <div class="container">
    <div class="row s_product_inner">
      <div class="col-lg-6">
        <div class="single-prd-item">
          <img class="img-fluid" src="admin/images/<?=escape($result['image'])?>" alt="">
        </div>
      </div>
      <div class="col-lg-5 offset-lg-1">
        <div class="s_product_text">
          <h3><?= escape($result['name']) ?></h3>
          <h2>$<?= escape($result['price']) ?></h2>
          <ul class="list">
            <li><a class="active" href="#"><span>Category</span> :<?php $cat_stmt = $pdo->prepare("SELECT * FROM categories WHERE id=:id");
                                                                  $cat_stmt->execute([':id' => $result['category_id']]);
                                                                  $cat_result = $cat_stmt->fetch(PDO::FETCH_ASSOC);
                                                                  echo strtoupper(escape($cat_result['name'])) ?> </a></li>
            <li><a href="#"><span>Availibility</span> : In Stock(<?= escape($result['quantity'])?>)</a></li>
          </ul>
          <p> <?= escape($result['description']) ?></p>
          <div class="product_count">
            <label for="qty">Quantity:</label>
            <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:" class="input-text qty">
            <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;" class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
            <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) && sst > 0 ) result.value--;return false;" class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
          </div>
          <div class="card_area d-flex align-items-center">
          <a href="index.php" class="primary-btn">Products</a>  
          <a class="primary-btn" href="#">Add to Cart</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><br>
<!--================End Single Product Area =================-->

<!--================End Product Description Area =================-->
<?php include('footer.php'); ?>