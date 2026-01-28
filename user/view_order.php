<?php
include("includes/header.php");
include("includes/navbar.php");

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
$username = $_SESSION['user'];

// Fetch Order Header
$order_q = mysqli_query($conn, "SELECT * FROM orders WHERE id='$order_id' AND customer='$username'");

if (mysqli_num_rows($order_q) == 0) {
    echo "<div class='container mt-5'><h3>Order not found or access denied.</h3><a href='orders.php' class='btn btn-primary'>Back</a></div>";
    include("includes/footer.php");
    exit();
}

$order = mysqli_fetch_assoc($order_q);

// Fetch Order Items
$items_q = mysqli_query($conn, "SELECT * FROM order_items WHERE order_id='$order_id'");
?>

<div class="container mt-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="fas fa-file-invoice text-secondary me-2"></i> ORDER DETAILS #<?= $order['id']; ?></h3>
        <a href="orders.php" class="btn btn-outline-dark rounded-pill px-4">
            <i class="fas fa-arrow-left me-2"></i> Back to History
        </a>
    </div>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-4 order-md-2 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Order Summary</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Status</span>
                            <?php if ($order['status'] == "Pending") { ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php } else { ?>
                                <span class="badge bg-success">Completed</span>
                            <?php } ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Date</span>
                            <span class="fw-bold"><?= date('M d, Y h:i A', strtotime($order['order_date'])); ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Total Items</span>
                            <span class="fw-bold"><?= $order['quantity']; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Payment Method</span>
                            <span class="fw-bold"><?= $order['payment_method']; ?></span>
                        </li>
                        <?php if($order['payment_phone']){ ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Payment Phone</span>
                            <span class="fw-bold"><?= $order['payment_phone']; ?></span>
                        </li>
                        <?php } ?>
                        <li class="list-group-item d-flex justify-content-between">
                            <span class="text-muted">Payment Status</span>
                            <?php if ($order['payment_status'] == "Paid") { ?>
                                <span class="badge bg-success">Paid</span>
                            <?php } else { ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php } ?>
                        </li>
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <span class="fw-bold">GRAND TOTAL</span>
                            <span class="fw-bold text-success fs-5">$<?= number_format($order['total'], 2); ?></span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="col-md-8 order-md-1">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">Items in this Order</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">Item Name</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($item = mysqli_fetch_assoc($items_q)) { ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?= $item['item_name']; ?></td>
                                        <td>$<?= number_format($item['price'], 2); ?></td>
                                        <td>x <?= $item['quantity']; ?></td>
                                        <td class="fw-bold text-dark">$<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php include("includes/footer.php"); ?>
