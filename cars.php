<?php
session_start();
require 'inc/db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$sql = "SELECT * FROM cars WHERE status='available'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Cars - DriveNow</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #0f172a, #1e293b);
            color: #f1f5f9;
            padding: 40px 20px;
        }

        h2, h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #facc15;
            text-shadow: 1px 1px 6px rgba(0,0,0,0.5);
        }

        a.logout {
            display: block;
            text-align: center;
            margin-bottom: 30px;
            color: #f87171;
            font-weight: bold;
            text-decoration: none;
        }

        a.logout:hover {
            text-decoration: underline;
        }

        .container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .card {
            background-color: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.3);
            padding: 16px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-6px);
        }

        .card img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .card h3 {
            font-size: 20px;
            margin-bottom: 6px;
            color: #facc15;
        }

        .card p {
            font-size: 15px;
            margin: 4px 0;
            color: #e2e8f0;
        }

        .card a {
            display: inline-block;
            margin-top: 10px;
            background-color: #facc15;
            color: #1f2937;
            padding: 10px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .card a:hover {
            background-color: #eab308;
            transform: scale(1.05);
        }

        @media(max-width: 600px){
            .card img {
                height: 140px;
            }

            h2, h3 {
                font-size: 22px;
            }

            .card a {
                font-size: 14px;
                padding: 8px 12px;
            }
        }
    </style>
</head>
<body>

<h2>Welcome, <?php echo $_SESSION['user_name']; ?>!</h2>
<a href="logout.php" class="logout">Logout</a>
<h3>Available Cars</h3>

<div class="container">
<?php while($car = $result->fetch_assoc()){ ?>
    <div class="card">
        <img src="assets/images/<?php echo $car['image']; ?>" alt="">
        <h3><?php echo $car['name']; ?></h3>
        <p><?php echo $car['category']; ?></p>
        <p>₹<?php echo $car['price_per_day']; ?>/day</p>
        <a href="book.php?car_id=<?php echo $car['id']; ?>">Book Now</a>
    </div>
<?php } ?>
</div>

</body>
</html>