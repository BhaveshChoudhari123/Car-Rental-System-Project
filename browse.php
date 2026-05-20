<?php
require 'db.php';
require 'inc/header.php';

// Simple auth redirect
if(!isset($_SESSION['user_id'])){ 
    header('Location: login.php'); 
    exit; 
}

// Fetch available cars
$res = $mysqli->query('SELECT * FROM cars WHERE available=1 ORDER BY id DESC');

// Do NOT redeclare esc() here if it's already in db.php
// function esc($str){ return htmlspecialchars($str, ENT_QUOTES, 'UTF-8'); }
?>

<style>
/* Embedded CSS for browse cars page */
body {
    font-family: Arial, Helvetica, sans-serif;
    background-color: #f5f7fb;
    color: #222;
    margin: 0;
    padding: 0;
}

h2 {
    text-align: center;
    margin: 20px 0;
}

.grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    max-width: 1000px;
    margin: 0 auto;
    padding: 0 20px;
}

.card {
    background-color: #fff;
    border-radius: 8px;
    padding: 16px;
    border: 1px solid #e6eef8;
    box-shadow: 0 6px 18px rgba(16,24,40,0.06);
    text-align: center;
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-4px);
}

.car-img {
    width: 100%;
    height: 160px;
    object-fit: cover;
    border-radius: 6px;
}

.card h3 {
    margin: 12px 0 6px 0;
}

.card p {
    font-size: 14px;
    line-height: 1.4;
    margin: 6px 0;
}

.btn {
    display: inline-block;
    padding: 8px 14px;
    border-radius: 6px;
    text-decoration: none;
    margin-top: 10px;
}

.btn-primary {
    background-color: #0ea5a4;
    color: #fff;
    font-weight: bold;
}
</style>

<h2>Available Cars</h2>
<div class="grid">
<?php while($car = $res->fetch_assoc()): ?>
<div class="card">
    <img class="car-img" src="images/<?=esc($car['image'])?>" alt="<?=esc($car['title'])?>">
    <h3><?=esc($car['title'])?></h3>
    <p><?=nl2br(esc($car['description']))?></p>
    <p><strong>Seats:</strong> <?=esc($car['seats'])?> &nbsp; 
       <strong>₹<?=number_format($car['price_per_day'],2)?></strong>/day</p>
    <a class="btn btn-primary" href="car.php?id=<?=esc($car['id'])?>">Book &raquo;</a>
</div>
<?php endwhile; ?>
</div>

<?php require 'inc/footer.php'; ?>
