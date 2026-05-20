<?php
session_start();
require 'inc/db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$car_id = $_GET['car_id'] ?? null;
if(!$car_id) die("Car not found");

$car = $conn->query("SELECT * FROM cars WHERE id=$car_id")->fetch_assoc();
$errors = [];
$success = false;
$total_price = null;

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $pickup = $_POST['pickup_date'];
    $return = $_POST['return_date'];

    if(!$pickup || !$return){
        $errors[] = "Please select pickup and return dates";
    } else {
        $start = new DateTime($pickup);
        $end = new DateTime($return);
        $diff = $start->diff($end)->days;

        if($diff <= 0){
            $errors[] = "Return date must be after pickup date";
        } else {
            $total_price = $diff * $car['price_per_day'];

            $stmt = $conn->prepare("INSERT INTO bookings(user_id,car_id,pickup_date,return_date) VALUES(?,?,?,?)");
            $stmt->bind_param("iiss", $_SESSION['user_id'], $car_id, $pickup, $return);
            if($stmt->execute()){
    $success = true;
    $booking_id = $stmt->insert_id; // ✅ get newly created booking ID
}

        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book <?php echo $car['name']; ?> - DriveNow</title>
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(135deg, #0f172a, #1e293b);
    color: #f1f5f9;
    padding: 40px 20px;
}

h2 {
    text-align: center;
    font-size: 32px;
    margin-bottom: 30px;
    color: #facc15;
    text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
}

.form-container {
    max-width: 400px;
    margin: 0 auto;
    background-color: rgba(255,255,255,0.05);
    padding: 24px 32px;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    border: 1px solid rgba(255,255,255,0.1);
}

label {
    display: block;
    margin-bottom: 6px;
    font-weight: 600;
    color: #facc15;
}

input[type="date"] {
    width: 100%;
    padding: 12px;
    margin-bottom: 20px;
    border-radius: 8px;
    border: 1px solid #475569;
    background-color: #1e293b;
    color: #f1f5f9;
    font-size: 16px;
}

button {
    width: 100%;
    padding: 14px;
    background-color: #facc15;
    color: #1f2937;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

button:hover {
    background-color: #eab308;
    transform: scale(1.03);
}

.error {
    color: #f87171;
    font-weight: bold;
    margin-bottom: 12px;
    text-align: center;
}

.back-link {
    display: block;
    text-align: center;
    margin-top: 30px;
    font-size: 14px;
    color: #facc15;
    text-decoration: none;
    font-weight: 500;
}

.back-link:hover {
    text-decoration: underline;
}

.success-box {
    max-width: 500px;
    margin: 40px auto;
    background-color: rgba(255,255,255,0.05);
    border: 1px solid #34d399;
    padding: 24px;
    border-radius: 12px;
    text-align: center;
    color: #34d399;
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    animation: fadeIn 0.6s ease-in-out;
}

.success-box h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #facc15;
}

.success-box p {
    font-size: 16px;
    margin-bottom: 10px;
    color: #e2e8f0;
}

.success-box a {
    display: inline-block;
    margin-top: 12px;
    background-color: #34d399;
    color: #1f2937;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: bold;
    transition: background-color 0.3s ease, transform 0.2s ease;
}

.success-box a:hover {
    background-color: #059669;
    transform: scale(1.05);
}

.price-info {
    text-align: center;
    font-size: 16px;
    color: #60a5fa;
    font-weight: 600;
    margin-top: 10px;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

@media(max-width: 600px){
    h2 {
        font-size: 24px;
    }

    button {
        font-size: 14px;
        padding: 12px;
    }

    .form-container, .success-box {
        padding: 20px;
    }
}
    </style>
</head>
<body>

<h2>Book <?php echo $car['name']; ?></h2>

<?php if($success): ?>
    <div class="success-box">
    <h3>✅ Booking Successful!</h3>
    <p>Your car has been reserved from <strong><?php echo $pickup; ?></strong> 
       to <strong><?php echo $return; ?></strong>.</p>
    <p>Total Price: ₹<?php echo number_format($total_price); ?></p>

    <!-- Cancel button -->
    <form method="POST" action="cancel_booking.php" 
          onsubmit="return confirm('Are you sure you want to cancel this booking?');">
        <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
        <button type="submit" 
            style="margin-top:12px;background:#dc2626;color:white;border:none;
                   padding:10px 18px;border-radius:8px;cursor:pointer;font-weight:bold;">
            Cancel Booking
        </button>
    </form>

    <a href="mybookings.php" style="display:inline-block;margin-top:15px;">View My Bookings</a>
</div>

<?php else: ?>
    <div class="form-container">
        <?php foreach($errors as $err) echo "<p class='error'>$err</p>"; ?>
        <form method="POST">
            <label for="pickup_date">Pickup Date</label>
            <input type="date" name="pickup_date" required>

            <label for="return_date">Return Date</label>
            <input type="date" name="return_date" required>

            <button type="submit">Book Now</button>
        </form>

        <?php if($total_price): ?>
            <p class="price-info">Estimated Total Price: ₹<?php echo number_format($total_price); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<a href="cars.php" class="back-link">← Back to Cars</a>

</body>
</html>