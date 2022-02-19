<?php
session_start();
require 'config/config.php';
require 'config/common.php';

if (isset($_SESSION['cart'])) {
	$userId = $_SESSION['user_id'];
	$total = 0;
	foreach ($_SESSION['cart'] as $key => $qty) {
		$id = str_replace('id', '', $key);
		$stmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
		$stmt->execute([':id' => $id]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);

		$total += $result['price'] * $qty;
	}
	//insert into sale_orders table
	$SOstmt = $pdo->prepare("INSERT INTO sale_orders(user_id,total_price,order_date) VALUES (:user_id,:total_price,:order_date)");
	$SOresult = $SOstmt->execute([
		':user_id' => $userId,
		':total_price' => $total,
		':order_date' => date("Y-m-d H:i:s")
	]);
	if ($SOresult) {
		$saleOrderId = $pdo->lastInsertId();
		foreach ($_SESSION['cart'] as $key => $qty) {
			$id = str_replace('id', '', $key); //product id
			$SODstmt = $pdo->prepare("INSERT INTO sale_order_detail(sale_order_id,product_id,quantity,order_date) VALUES (:sale_order_id,:product_id,:quantity,:order_date)");
			$SODresult = $SODstmt->execute([
				':sale_order_id' => $saleOrderId,
				':product_id' => $id,
				':quantity' => $qty,
				':order_date' => date("Y-m-d H:i:s")
			]);

			$qtystmt = $pdo->prepare("SELECT quantity FROM products WHERE id=:id");
			$qtystmt->execute([
				':id' => $id
			]);
			$qtyresult = $qtystmt->fetch(PDO::FETCH_ASSOC);

			$updateQty = $qtyresult['quantity'] - $qty;

			$newqtystmt = $pdo->prepare("UPDATE products SET quantity=:updateQty WHERE id=:id");
			$newqtystmt->execute([
				':updateQty' => $updateQty,
				':id' => $id
			]);
		}
	}
}
?>
<!DOCTYPE html>
<html lang="zxx" class="no-js">

<head>
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Favicon-->
	<link rel="shortcut icon" href="img/fav.png">
	<!-- Author Meta -->
	<meta name="author" content="CodePixar">
	<!-- Meta Description -->
	<meta name="description" content="">
	<!-- Meta Keyword -->
	<meta name="keywords" content="">
	<!-- meta character set -->
	<meta charset="UTF-8">
	<!-- Site Title -->
	<title>Code1 Shop</title>

	<!--
		CSS
		============================================= -->
	<link rel="stylesheet" href="css/linearicons.css">
	<link rel="stylesheet" href="css/owl.carousel.css">
	<link rel="stylesheet" href="css/themify-icons.css">
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/nice-select.css">
	<link rel="stylesheet" href="css/nouislider.min.css">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/main.css">
</head>

<body>

	<!-- Start Header Area -->
	<header class="header_area sticky-header">
		<div class="main_menu">
			<nav class="navbar navbar-expand-lg navbar-light main_box">
				<div class="container">
					<!-- Brand and toggle get grouped for better mobile display -->
					<a class="navbar-brand logo_h" href="index.php">
						<h4>AP Shopping<h4>
					</a>

					<!-- Collect the nav links, forms, and other content for toggling -->

				</div>
			</nav>
		</div>
		<div class="search_input" id="search_input_box">
			<div class="container">
				<form class="d-flex justify-content-between">
					<input type="text" class="form-control" id="search_input" placeholder="Search Here">
					<button type="submit" class="btn"></button>
					<span class="lnr lnr-cross" id="close_search" title="Close Search"></span>
				</form>
			</div>
		</div>
	</header>
	<!-- End Header Area -->

	<!-- Start Banner Area -->
	<section class="banner-area organic-breadcrumb">
		<div class="container">
			<div class="breadcrumb-banner d-flex flex-wrap align-items-center justify-content-end">
				<div class="col-first">
					<h1>Confirmation</h1>
					<nav class="d-flex align-items-center">
						<a href="index.php">Home<span class="lnr lnr-arrow-right"></span></a>
					</nav>
				</div>
			</div>
		</div>
	</section>
	<!-- End Banner Area -->

	<!--================Order Details Area =================-->
	<?php if (isset($_SESSION['cart'])) : ?>
		<section class="order_details section_gap ">
			<div class="container" style="max-width:400px;">
				<h3 class="title_confirmation">Thank you. Your order has been received.</h3>
				<div class="row order_d_inner text-justify">
					<div class="col-lg-12">
						<div class="details_item">
							<h4>Order Info</h4>
							<?php
							$statement = $pdo->prepare("SELECT * FROM sale_orders WHERE id=:id");
							$statement->execute([':id' => $saleOrderId]);
							$order = $statement->fetch(PDO::FETCH_ASSOC);
							?>
							<ul class="list">
								<li><a href="#"><span>Order Id</span> : <?= escape($order['id']) ?></a></li>
								<li><a href="#"><span>Date</span> : <?= escape($order['order_date']) ?></a></li>
								<li><a href="#"><span>Total</span> : $<?= $total ?></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php else : echo "<h1 style='text-align:center;margin:40px auto'>No Order Yet.</h1>" ?>
	<?php
	endif;
	unset($_SESSION['cart']);
	?>
	<!--================End Order Details Area =================-->

	<!-- start footer Area -->
	<footer class="footer-area section_gap">
		<div class="container">
			<div class="footer-bottom d-flex justify-content-center align-items-center flex-wrap">
				<p class="footer-text m-0">
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
					Copyright &copy;<script>
						document.write(new Date().getFullYear());
					</script> All rights reserved | This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
					<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
				</p>
			</div>
		</div>
	</footer>
	<!-- End footer Area -->




	<script src="js/vendor/jquery-2.2.4.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous">
	</script>
	<script src="js/vendor/bootstrap.min.js"></script>
	<script src="js/jquery.ajaxchimp.min.js"></script>
	<script src="js/jquery.nice-select.min.js"></script>
	<script src="js/jquery.sticky.js"></script>
	<script src="js/nouislider.min.js"></script>
	<script src="js/jquery.magnific-popup.min.js"></script>
	<script src="js/owl.carousel.min.js"></script>
	<!--gmaps Js-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjCGmQ0Uq4exrzdcL6rvxywDDOvfAu6eE"></script>
	<script src="js/gmaps.min.js"></script>
	<script src="js/main.js"></script>
</body>

</html>