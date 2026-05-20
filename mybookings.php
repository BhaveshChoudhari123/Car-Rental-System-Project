<?php
session_start();
require 'inc/db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$user_id = $_SESSION['user_id'];
$sql = "SELECT b.*, c.name AS car_name, c.price_per_day 
        FROM bookings b 
        JOIN cars c ON b.car_id = c.id 
        WHERE b.user_id=$user_id 
        ORDER BY b.created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Bookings - DriveNow</title>
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

.nav-links {
    text-align: center;
    margin-bottom: 30px;
}

.nav-links a {
    margin: 0 10px;
    color: #facc15;
    text-decoration: none;
    font-weight: 600;
}

.nav-links a:hover {
    text-decoration: underline;
}

table {
    width: 95%;
    margin: 0 auto;
    border-collapse: collapse;
    background-color: #ffffff; /* White box */
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
    border-radius: 12px;
    overflow: hidden;
}

th, td {
    padding: 16px;
    text-align: center;
    border-bottom: 1px solid #e2e8f0;
}

th {
    background-color: #facc15;
    color: #1f2937;
    font-size: 16px;
    text-transform: uppercase;
}

tr:hover {
    background-color: #f1f5f9;
}

td {
    font-size: 15px;
    color: #1f2937;
}

@media(max-width: 600px){
    table, th, td {
        font-size: 14px;
        padding: 10px;
    }

    .nav-links a {
        display: block;
        margin: 10px 0;
    }
}
    </style>
</head>
<body>

<h2>My Bookings</h2>

<div class="nav-links">
    <a href="cars.php">Browse Cars</a> |
    <a href="logout.php">Logout</a>
</div>

<table>
    <tr>
        <th>Car</th>
        <th>Pickup</th>
        <th>Return</th>
        <th>Status</th>
        <th>Total Price</th>
    </tr>
    <?php while($row = $result->fetch_assoc()){ 
        $pickup = new DateTime($row['pickup_date']);
        $return = new DateTime($row['return_date']);
        $days = $pickup->diff($return)->days;
        $total = $days > 0 ? $days * $row['price_per_day'] : 0;
    ?>
    <tr>
        <td><?php echo $row['car_name']; ?></td>
        <td><?php echo $row['pickup_date']; ?></td>
        <td><?php echo $row['return_date']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>₹<?php echo number_format($total); ?></td>
    </tr>
    <?php } ?>
</table>

</body>
</html>