<?php require 'header.php';
require 'config/config.php';
if (empty($_SESSION['user_id']) && empty($_SESSION['user_role'])) {
    header("location: login.php");
}
?>

<!--================Cart Area =================-->
<section class="cart_area" style="padding-top: 0 !important;">
    <div class="container">
        <div class="cart_inner">
            <div class="table-responsive">
                <?php if (isset($_SESSION['cart'])) : ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Product</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            foreach ($_SESSION['cart'] as $key => $qty) :
                                $id = str_replace('id', '', $key);
                                $stmt = $pdo->prepare("SELECT * FROM products WHERE id=:id");
                                $stmt->execute([':id' => $id]);
                                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                                $total += $result['price'] * $qty;
                            ?>
                                <tr>
                                    <td>
                                        <div class="media">
                                            <div class="d-flex">
                                                <img src="admin/images/<?= escape($result['image']) ?>" width="110" height="100">
                                            </div>
                                            <div class="media-body">
                                                <p><?= escape($result['name']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <h5>$<?= escape($result['price']) ?></h5>
                                    </td>
                                    <td>
                                        <h5> <?= escape($qty) ?></h5>
                                    </td>
                                    <td>
                                        <h5>$<?= escape($result['price']) * $qty ?></h5>
                                    </td>
                                    <td>
                                        <a href="cart_item_clear.php?pid=<?= escape($result['id']) ?>" class="btn btn-danger">Clear</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>
                                    <h5>Subtotal</h5>
                                </td>
                                <td>
                                    <h5>$<?= $total ?></h5>
                                </td>
                                <td></td>
                            </tr>

                            <tr class="out_button_area">
                                <td>

                                </td>
                                <td>

                                </td>
                                <td>

                                </td>
                                <td></td>
                                <td>
                                    <div class="checkout_btn_inner d-flex align-items-center">
                                        <a class="gray_btn" href="clearall.php">Clear All</a>
                                        <a class="primary-btn" href="sale_order.php">Submit Order</a>
                                        <a class="gray_btn" href="index.php">Continue Shopping</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                <?php else : echo "<h1 style='text-align:center;margin:40px auto'>No Cart Item Yet.</h1>" ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
<!--================End Cart Area =================-->

<!-- start footer Area -->
<?php require 'footer.php' ?>