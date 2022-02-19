<?php include('header.php') ?>
<?php
require "config/config.php";

if (empty($_SESSION['user_id']) && empty($_SESSION['user_role'])) {
	header("location: login.php");
}
if (isset($_POST['search'])) {
	setcookie('search', $_POST['search'], time() + (86400 * 30), "/"); // 86400 = 1 day
} else {
	if (empty($_GET['pageNo'])) {
		unset($_COOKIE['search']);
		setcookie('search', null, -1, '/');
	}
}

if (isset($_GET['pageNo'])) {
	$pageNo = $_GET['pageNo'];
} else {
	$pageNo = 1;
}
$numOfrecs = 3;
//algorithm to find offset value 
$offset = ($pageNo - 1) * $numOfrecs;

if (empty($_POST['search']) && empty($_COOKIE['search']) && empty($_GET['cat_id'])) {
	////pdo section
	$statement = $pdo->prepare("SELECT * FROM products WHERE quantity>0 ORDER BY id desc");
	$statement->execute();
	$RawResult = $statement->fetchALl();
	//calculate count of pages 
	$totalPages = ceil(count($RawResult) / $numOfrecs);
	//offseted result values
	$statement = $pdo->prepare("SELECT * FROM products WHERE quantity>0 ORDER BY id desc LIMIT $offset,$numOfrecs");
	$statement->execute();
	$result = $statement->fetchALl();
} elseif (isset($_GET['cat_id'])) {
	$statement = $pdo->prepare("SELECT * FROM products WHERE category_id=:cat_id AND quantity>0 ORDER BY id desc");
	$statement->execute([':cat_id' => $_GET['cat_id']]);
	$RawResult = $statement->fetchALl();
	//calculate count of pages 
	$totalPages = ceil(count($RawResult) / $numOfrecs);
	//offseted result values
	$statement = $pdo->prepare("SELECT * FROM products WHERE category_id=:cat_id AND quantity>0 ORDER BY id desc LIMIT $offset,$numOfrecs");
	$statement->execute([':cat_id' => $_GET['cat_id']]);
	$result = $statement->fetchALl();
} else {
	////find Search value from search bar
	////pdo section
	$searchKey = isset($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];
	$statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' AND quantity>0 ORDER BY id desc");
	$statement->execute();
	$RawResult = $statement->fetchALl();
	//calculate count of pages 
	$totalPages = ceil(count($RawResult) / $numOfrecs);
	//offseted result values
	$statement = $pdo->prepare("SELECT * FROM products WHERE name LIKE '%$searchKey%' AND quantity>0 ORDER BY id desc LIMIT $offset,$numOfrecs");
	$statement->execute();
	$result = $statement->fetchALl();
}
?>

<div class="container">
	<div class="row">
		<div class="col-xl-3 col-lg-4 col-md-5">
			<div class="sidebar-categories">
				<div class="head"><a href="index.php" style="text-decoration:none;color:white">Browse Categories</a></div>
				<ul class="main-categories">
					<li class="main-nav-list">
						<?php
						$catstmt = $pdo->prepare("SELECT * FROM categories ORDER BY id DESC");
						$catstmt->execute();
						$categories = $catstmt->fetchAll(PDO::FETCH_ASSOC);
						foreach ($categories as $category) :
						?>
							<a class="border-bottom-0" href="?cat_id=<?= $category['id'] ?>" style="color:<?php if (isset($_GET['cat_id'])) {
																												echo ($_GET['cat_id'] == escape($category['id'])) ? "#828bb3" : '';
																											} ?>">
								<span class="lnr lnr-arrow-right"></span><?= strtoupper(escape($category['name'])) ?>
							</a>
						<?php endforeach; ?>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-xl-9 col-lg-8 col-md-7">
			<!-- Start Filter Bar -->
			<div class="filter-bar d-flex flex-wrap align-items-center">
				<div class="pagination">
					<a class="" href="?pageNo=1">First</a>
					<a <?php
						if ($pageNo <= 1) {
							echo "disabled";
						} ?>" href="<?php if ($pageNo <= 1) {
										echo "#";
									} else {
										echo "?pageNo=" . $pageNo - 1;
									}

									?>" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>
					<a href="#" class="active"><?= $pageNo . ' of ' . $totalPages; ?></a>
					<a <?php if ($pageNo == $totalPages) {
							echo "disabled";
						} ?>" href="<?php if ($pageNo >= $totalPages) {
										echo '#';
									} else {
										echo "?pageNo=" . $pageNo + 1;
									}
									?>" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>
					<a class="" href="?pageNo=<?= $totalPages ?>">Last</a>
				</div>
			</div>
			<!-- End Filter Bar -->
			<!-- Start Best Seller -->
			<section class="lattest-product-area pb-40 category-list">
				<div class="row">
					<?php
					if ($result) :
						$i = 1;
						foreach ($result as $value) :
							$cat_stmt = $pdo->prepare("SELECT * FROM categories WHERE id=:id");
							$cat_stmt->execute([':id' => $value['category_id']]);
							$cat_result = $cat_stmt->fetch(PDO::FETCH_ASSOC);
					?>
							<!-- single product -->
							<div class="col-lg-4 col-md-6">
								<div class="single-product">
									<img class="img-fluid" src="admin/images/<?= escape($value['image']) ?>">
									<div class="product-details">
										<h6><?= escape($value['name']) ?></h6>
										<div class="price">
											<h6>$<?= escape($value['price']) ?></h6>
											<h6 class="l-through">$210.00</h6>
										</div>
										<div class="prd-bottom">
											<a href="addtocart.php?id=<?= $value['id'] ?>&qty=1" class="social-info">
												<span class="ti-bag"></span>
												<p class="hover-text">add to bag</p>
											</a>
											<a href="product_detail.php?pid=<?= $value['id'] ?>" class="social-info">
												<span class="lnr lnr-move"></span>
												<p class="hover-text">view more</p>
											</a>
										</div>
									</div>
								</div>
							</div>
					<?php endforeach;
					endif;
					?>
				</div>
				<a href="logout.php" type="button" class="btn btn-secondary float-right">Logout</a><br>
			</section>
			<!-- End Best Seller -->
			<?php include('footer.php'); ?>