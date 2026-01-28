<?php
include("includes/header.php");
include("includes/navbar.php");

if (empty($_SESSION['cart'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit();
}

// Calculate Total for Display
$grand_total = 0;
foreach ($_SESSION['cart'] as $item) {
    $grand_total += ($item['price'] * $item['qty']);
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-4"><i class="fas fa-credit-card text-warning me-2"></i> Checkout</h2>
            
            <form action="place_order.php" method="POST">
                <div class="row g-4">
                    
                    <!-- Order Summary -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="fw-bold mb-0">Order Summary</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-group list-group-flush mb-3">
                                    <?php foreach ($_SESSION['cart'] as $item) { ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                            <div>
                                                <small class="fw-bold"><?= $item['item_name']; ?></small>
                                                <div class="text-muted small">x <?= $item['qty']; ?></div>
                                            </div>
                                            <span class="text-muted">$<?= number_format($item['price'] * $item['qty'], 2); ?></span>
                                        </li>
                                    <?php } ?>
                                    <li class="list-group-item d-flex justify-content-between px-0 fw-bold border-top mt-2 pt-2">
                                        <span>TOTAL TO PAY</span>
                                        <span class="text-success fs-5">$<?= number_format($grand_total, 2); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Details -->
                    <div class="col-md-6">
                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-header bg-white py-3">
                                <h5 class="fw-bold mb-0">Payment Method</h5>
                            </div>
                            <div class="card-body">
                                
                                <div class="mb-3">
                                    <h6 class="text-muted small fw-bold mb-3">MOBILE MONEY</h6>

                                    <div class="form-check p-3 border rounded mb-2" style="background-color: #e8f5e9;">
                                        <input class="form-check-input" type="radio" name="payment_method" value="EVC Plus" id="evc" onchange="togglePhone(true)">
                                        <label class="form-check-label fw-bold w-100 text-success" for="evc">
                                            <i class="fas fa-mobile-alt me-2"></i> EVC Plus
                                        </label>
                                    </div>

                                    <div class="form-check p-3 border rounded mb-2" style="background-color: #fff8e1;">
                                        <input class="form-check-input" type="radio" name="payment_method" value="eDahab" id="edahab" onchange="togglePhone(true)">
                                        <label class="form-check-label fw-bold w-100 text-warning" for="edahab">
                                            <i class="fas fa-sim-card me-2"></i> eDahab
                                        </label>
                                    </div>

                                    <div class="form-check p-3 border rounded mb-2" style="background-color: #dcffe4;">
                                        <input class="form-check-input" type="radio" name="payment_method" value="Somnet" id="somnet" onchange="togglePhone(true)">
                                        <label class="form-check-label fw-bold w-100 text-success" for="somnet">
                                            <i class="fas fa-signal me-2"></i> Somnet
                                        </label>
                                    </div>
                                    
                                    <hr>

                                    <div class="form-check p-3 border rounded mb-2">
                                        <input class="form-check-input" type="radio" name="payment_method" value="Cash On Delivery" id="cod" checked onchange="togglePhone(false)">
                                        <label class="form-check-label fw-bold w-100" for="cod">
                                            <i class="fas fa-money-bill-wave text-secondary me-2"></i> Cash on Delivery
                                        </label>
                                    </div>
                                </div>

                                <!-- Phone Number Input -->
                                <div id="phone-details" class="d-none bg-light p-3 rounded mb-3">
                                    <label class="small text-muted fw-bold">PAYMENT PHONE NUMBER</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0 text-muted">+252</span>
                                        <input type="number" name="payment_phone" class="form-control border-start-0 ps-0" placeholder="61 XXX XXXX">
                                    </div>
                                    <small class="text-muted" style="font-size: 0.75rem;">Enter the number to pay from.</small>
                                </div>

                                <button type="submit" name="place_order" class="btn btn-warning w-100 fw-bold py-2 rounded-pill mt-2">
                                    Complete Payment <i class="fas fa-check-circle ms-2"></i>
                                </button>
                                
                                <div class="text-center mt-3">
                                    <a href="view_cart.php" class="text-muted small text-decoration-none">Back to Cart</a>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePhone(show) {
    const phoneDiv = document.getElementById('phone-details');
    const phoneInput = phoneDiv.querySelector('input');
    
    if (show) {
        phoneDiv.classList.remove('d-none');
        phoneInput.required = true;
    } else {
        phoneDiv.classList.add('d-none');
        phoneInput.required = false;
        phoneInput.value = ''; // clear value
    }
}

document.querySelector('form').addEventListener('submit', function(e) {
    const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
    const phoneInput = document.querySelector('input[name="payment_phone"]');
    const phoneNumber = phoneInput.value.trim();
    
    if (['EVC Plus', 'eDahab', 'Somnet'].includes(paymentMethod)) {
        if (phoneNumber.length !== 9) {
            alert("Phone number must be 9 digits long.");
            e.preventDefault();
            return;
        }

        const prefix = phoneNumber.substring(0, 2);
        let valid = false;
        let errorMsg = "";

        if (paymentMethod === 'EVC Plus') {
            if (['61', '68', '77'].includes(prefix)) valid = true;
            else errorMsg = "For EVC Plus, number must start with 61 or 77.";
        } else if (paymentMethod === 'eDahab') {
            if (['65', '62', '60'].includes(prefix)) valid = true;
            else errorMsg = "For eDahab, number must start with 65 or 62.";
        } else if (paymentMethod === 'Somnet') {
            if (['68'].includes(prefix)) valid = true;
            else errorMsg = "For Somnet, number must start with 68.";
        }

        if (!valid) {
            alert(errorMsg);
            e.preventDefault();
        }
    }
});
</script>

<?php include("includes/footer.php"); ?>
