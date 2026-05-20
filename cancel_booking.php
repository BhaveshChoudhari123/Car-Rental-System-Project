<?php
session_start();
require 'inc/db.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE bookings 
                            SET status='Cancelled' 
                            WHERE id=? AND user_id=? AND status='Pending'");
    $stmt->bind_param("ii", $booking_id, $user_id);

    if($stmt->execute() && $stmt->affected_rows > 0) {
        $_SESSION['message'] = "Booking cancelled successfully.";
    } else {
        $_SESSION['message'] = "Unable to cancel booking. It may already be confirmed or completed.";
    }

    header("Location: mybookings.php");
    exit();
}
header("Location: mybookings.php");
exit();
