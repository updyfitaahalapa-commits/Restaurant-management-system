<?php
include("includes/header.php");
include("includes/navbar.php");
?>

<div class="container mt-5 mb-5">
    <h2 class="fw-bold mb-4"><i class="fas fa-shopping-cart text-warning me-2"></i> My Cart</h2>

    <?php if (empty($_SESSION['cart'])) { ?>
        <div class="text-center py-5">
            <img src="https://cdni.iconscout.com/illustration/premium/thumb/empty-cart-2130356-1800917.png" alt="Empty Cart" style="width: 200px; opacity: 0.7;">
            <h4 class="mt-3 text-muted">Your cart is empty</h4>
            <a href="index.php" class="btn btn-warning rounded-pill mt-3 fw-bold px-4">Browse Menu</a>
        </div>
    <?php } else { ?>
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Item</th>
                                <th class="py-3">Price</th>
                                <th class="py-3">Quantity</th>
                                <th class="py-3">Total</th>
                                <th class="py-3">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $grand_total = 0;
                            foreach ($_SESSION['cart'] as $index => $item) {
                                $total = $item['price'] * $item['qty'];
                                $grand_total += $total;
                            ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-dark"><?= $item['item_name']; ?></td>
                                    <td>$<?= number_format($item['price'], 2); ?></td>
                                    <td style="width: 150px;">
                                        <form action="cart.php" method="POST" class="d-flex">
                                            <input type="hidden" name="index" value="<?= $index; ?>">
                                            <input type="number" name="qty" value="<?= $item['qty']; ?>" min="1" class="form-control form-control-sm text-center me-2" style="width: 60px;">
                                            <button type="submit" name="update_cart" class="btn btn-sm btn-outline-primary"><i class="fas fa-sync-alt"></i></button>
                                        </form>
                                    </td>
                                    <td class="fw-bold text-primary">$<?= number_format($total, 2); ?></td>
                                    <td>
                                        <a href="cart.php?remove=<?= $index; ?>" class="btn btn-sm btn-light text-danger rounded-circle" onclick="return confirm('Remove this item?');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <a href="cart.php?clear=true" class="text-danger text-decoration-none fw-bold small" onclick="return confirm('Clear entire cart?');">
                        <i class="fas fa-trash me-1"></i> Clear Cart
                    </a>
                    <div class="text-end">
                        <h4 class="fw-bold mb-3">Total: <span class="text-success">$<?= number_format($grand_total, 2); ?></span></h4>
                        <a href="index.php" class="btn btn-outline-secondary rounded-pill me-2">Continue Shopping</a>
                        <a href="checkout.php" class="btn btn-warning rounded-pill fw-bold px-5">Checkout <i class="fas fa-arrow-right ms-2"></i></a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include("includes/footer.php"); ?>
