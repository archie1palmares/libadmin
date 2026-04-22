<?php
include 'phpqrcode/qrlib.php';

$conn = new mysqli("localhost", "root", "", "login");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = mysqli_real_escape_string($conn, $_POST['Fname']);
    $lname = mysqli_real_escape_string($conn, $_POST['Lname']);
    $sid   = mysqli_real_escape_string($conn, $_POST['student_id']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $fullname = $fname . " " . $lname;

    // Insert student record
    $sql = "INSERT INTO students (student_id, fullname, email, isActive) VALUES ('$sid', '$fullname', '$email', 1)";

    // File path
    $filePath = 'qrcodes/' . $sid . '.png';

    // Generate QR code
    QRcode::png($sid, $filePath);

    echo "QR code generated at: " . $filePath;

    if ($conn->query($sql)) {
        // SUCCESS: Redirect to the printable QR page
        header("Location: registration_success.php?sid=" . urlencode($sid) . "&name=" . urlencode($fullname));
        exit();
    } else {
        // Handle error (e.g., duplicate ID)
        header("Location: admin.php?status=error&msg=" . urlencode($conn->error));
        exit();
    }
}
?>