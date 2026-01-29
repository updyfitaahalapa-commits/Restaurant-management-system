<?php
session_start();
include("../config/db.php");
include("includes/header.php");

if(!isset($_GET['order_id'])){
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['order_id'];
$amount = 0;

// Fallback to get user_id if not in session (for users logged in before fix)
if(!isset($_SESSION['user_id'])){
    $u_chk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM users WHERE username='".$_SESSION['user']."'"));
    $_SESSION['user_id'] = $u_chk['id'];
}

// Fetch Order Details
$q = mysqli_query($conn, "SELECT total, status, payment_status FROM orders WHERE id='$order_id' AND user_id='".$_SESSION['user_id']."'"); // Need user check for security
if(mysqli_num_rows($q) > 0){
    $row = mysqli_fetch_assoc($q);
    $amount = $row['total'];
    if($row['payment_status'] == 'Paid'){
        header("Location: orders.php"); // Already paid
        exit();
    }
} else {
    // Should probably redirect if order not found, but maybe simple error here
    echo "<div class='container mt-5'>Order not found.</div>";
    exit();
}
?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-primary text-white py-3 rounded-top-4 text-center">
                    <h5 class="fw-bold mb-0"><i class="fas fa-lock me-2"></i> Secure Payment Gateway</h5>
                </div>
                <div class="card-body p-4">
                    
                    <div class="text-center mb-4">
                        <small class="text-muted d-block uppercase fw-bold">TOTAL TO PAY</small>
                        <h1 class="text-primary fw-bold">$<?= number_format($amount, 2); ?></h1>
                        <span class="badge bg-light text-dark">Order #<?= $order_id; ?></span>
                    </div>

                    <form action="process_payment.php" method="POST" id="paymentForm">
                        <input type="hidden" name="order_id" value="<?= $order_id; ?>">
                        <input type="hidden" name="amount" value="<?= $amount; ?>">

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">CARD HOLDER NAME</label>
                            <input type="text" class="form-control" name="card_name" placeholder="JOHN DOE" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small text-muted">CARD NUMBER</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-credit-card text-muted"></i></span>
                                <input type="text" class="form-control" name="card_number" placeholder="0000 0000 0000 0000" maxlength="19" required id="cardNum">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold small text-muted">EXPIRY</label>
                                    <input type="text" class="form-control" name="expiry" placeholder="MM/YY" maxlength="5" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="mb-4">
                                    <label class="form-label fw-bold small text-muted">CVV</label>
                                    <input type="text" class="form-control" name="cvv" placeholder="123" maxlength="3" required>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="pay_now" class="btn btn-primary btn-lg fw-bold rounded-pill">
                                Pay Now <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <div class="text-muted small"><i class="fas fa-check-circle text-success me-1"></i> 256-bit SSL Encrypted Payment</div>
                        </div>
                    </form>
                    
                    <div id="loader" class="d-none text-center mt-4">
                        <div class="spinner-border text-primary" role="status"></div>
                        <p class="text-muted mt-2 small">Processing Transaction...</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    // Show Loader
    const btn = this.querySelector('button');
    btn.disabled = true;
    btn.innerHTML = 'Processing...';
    document.getElementById('loader').classList.remove('d-none');
    
    // Allow form to submit normally after UI update
});

// Format Card Number
document.getElementById('cardNum').addEventListener('input', function (e) {
    this.value = this.value.replace(/\D/g, '').replace(/(.{4})/g, '$1 ').trim();
});
</script>

<?php include("includes/footer.php"); ?>
