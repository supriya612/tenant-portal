<?php
session_start();
require_once 'classes/Database.php';

if (!isset($_SESSION['tenant_id'])) {
    header('Location: index.php');
    exit;
}

// Initialize database
try {
    $db = (new Database())->getConnection();
    if (!$db) {
        die("Failed to get database connection.");
    }
} catch (Exception $e) {
    die("Database error: " . $e->getMessage());
}

// Fetch tenant info
try {
    $stmt = $db->prepare("SELECT full_name, rent FROM tenants WHERE id = ?");
    $stmt->execute([$_SESSION['tenant_id']]);
    $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$tenant) {
        die("Tenant not found.");
    }
} catch (PDOException $e) {
    die("Query error: " . $e->getMessage());
}

// Handle maintenance request
$maintenance_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['details'])) {
    $details = $_POST['details'];
    if (!empty($details)) {
        try {
            $stmt = $db->prepare("INSERT INTO maintenance_requests (tenant_id, request_details) VALUES (?, ?)");
            $stmt->execute([$_SESSION['tenant_id'], $details]);
            $maintenance_message = 'Request submitted!';
        } catch (PDOException $e) {
            $maintenance_message = 'Error submitting request: ' . $e->getMessage();
        }
    } else {
        $maintenance_message = 'Please enter details.';
    }
}

// Handle rent payment
$payment_message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['amount'])) {
    $amount = floatval($_POST['amount']);
    if ($amount > 0) {
        try {
            $stmt = $db->prepare("INSERT INTO rent_payments (tenant_id, amount, payment_date) VALUES (?, ?, CURDATE())");
            $stmt->execute([$_SESSION['tenant_id'], $amount]);
            $payment_message = 'Payment recorded!';
        } catch (PDOException $e) {
            $payment_message = 'Error recording payment: ' . $e->getMessage();
        }
    } else {
        $payment_message = 'Enter a valid amount.';
    }
}

// Fetch rent payments (for current month)
try {
    $stmt = $db->prepare("SELECT amount, payment_date FROM rent_payments WHERE tenant_id = ? ORDER BY payment_date DESC");
    $stmt->execute([$_SESSION['tenant_id']]);
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $payments = [];
    $payment_message .= ' Error fetching payments: ' . $e->getMessage();
}

// Calculate total paid and balance for current month
$total_paid = array_sum(array_column($payments, 'amount'));
$balance = $tenant['rent'] - $total_paid;

//Fetch all time total paid
try{

    $stmt=$db->prepare("SELECT SUM(amount) as total_paid from rent_payments where tenant_id=?");
    $stmt->execute([$_SESSION['tenant_id']]);
    $total_paid=$stmt->fetch(PDO::FETCH_ASSOC)['total_paid'] ?? 0;

}
catch(PDOException $e)
{
    $total_paid=0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome, <?php echo $tenant['full_name']; ?>!</h1>
    <p>Your Monthly Rent: $<?php echo number_format($tenant['rent'], 2); ?></p>
    <p>Total Paid: $<?php echo number_format($total_paid, 2); ?></p>
    <p>Balance Due: $<?php echo number_format($balance, 2); ?></p>
    <p>LifeTime Total Paid: $<?php echo number_format($total_paid,2); ?></p>
   <p><a href="announcements.php">View Announcements</a></p>
    <h2>Record a Rent Payment</h2>
    <form method="POST">
        <input type="number" name="amount" step="0.01" placeholder="Amount Paid" required><br>
        <button type="submit">Submit Payment</button>
    </form>
    <p><?php echo $payment_message; ?></p>

    <h2>Past Payments</h2>
    <?php if (empty($payments)): ?>
        <p>No payments recorded yet.</p>
    <?php else: ?>
        <table>
            <tr><th>Amount</th><th>Date</th></tr>
            <?php foreach ($payments as $payment): ?>
                <tr>
                    <td>$<?php echo number_format($payment['amount'], 2); ?></td>
                    <td><?php echo $payment['payment_date']; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <h2>Maintenance Request</h2>
    <form method="POST">
        <textarea name="details" placeholder="What needs fixing?" required></textarea><br>
        <button type="submit">Submit</button>
    </form>
    <p><?php echo $maintenance_message; ?></p>

    <p><a href="logout.php">Logout</a></p>
    
</body>
</html>