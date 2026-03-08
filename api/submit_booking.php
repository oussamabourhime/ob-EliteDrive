<?php
// api/submit_booking.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

require_once '../includes/db.php';

// Sanitize and Validate Inputs
$car_id = filter_input(INPUT_POST, 'car_id', FILTER_VALIDATE_INT);
$full_name = htmlspecialchars(trim($_POST['full_name'] ?? ''));
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$phone = htmlspecialchars(trim($_POST['phone'] ?? ''));
$pickup_location = htmlspecialchars(trim($_POST['pickup_location'] ?? ''));
$dropoff_location = htmlspecialchars(trim($_POST['dropoff_location'] ?? ''));
$pickup_date_raw = $_POST['pickup_date'] ?? '';
$dropoff_date_raw = $_POST['dropoff_date'] ?? '';

if (!$car_id || !$full_name || !$email || !$phone || !$pickup_location || !$dropoff_location || !$pickup_date_raw || !$dropoff_date_raw) {
    echo json_encode(['success' => false, 'message' => 'All fields are required and must be valid.']);
    exit;
}

try {
    $pickup = new DateTime($pickup_date_raw);
    $dropoff = new DateTime($dropoff_date_raw);
    $now = new DateTime();
    
    if ($pickup < $now) {
        // allowing slightly past for today, but ideally pickup is in future
    }
    
    if ($dropoff <= $pickup) {
        echo json_encode(['success' => false, 'message' => 'Drop-off date must be after pickup date.']);
        exit;
    }
    
    // Calculate difference in days
    $diff = $pickup->diff($dropoff);
    $days = $diff->days;
    // Minimum 1 day rental
    if ($days < 1) $days = 1;
    // Add 1 extra day if there is any hours over
    if ($diff->h > 0 || $diff->i > 0) {
        $days++;
    }

    // Backend Pricing Validation
    $stmt = $pdo->prepare("SELECT price_per_day FROM cars WHERE id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();
    
    if (!$car) {
        echo json_encode(['success' => false, 'message' => 'Invalid car selection.']);
        exit;
    }
    
    $total_price = $days * $car['price_per_day'];

    // Insert into DB using Prepared Statements for SQL Injection protection
    $sql = "INSERT INTO bookings (car_id, full_name, email, phone, pickup_location, dropoff_location, pickup_date, dropoff_date, total_price) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
    $insertStmt = $pdo->prepare($sql);
    $success = $insertStmt->execute([
        $car_id,
        $full_name,
        $email,
        $phone,
        $pickup_location,
        $dropoff_location,
        $pickup->format('Y-m-d H:i:s'),
        $dropoff->format('Y-m-d H:i:s'),
        $total_price
    ]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save booking. Please try again.']);
    }

} catch (Exception $e) {
    // Log exception in real world
    echo json_encode(['success' => false, 'message' => 'An error occurred processing the dates or database.']);
}
?>
