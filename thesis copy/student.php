<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style.css">  

</head>
<body>
    <div class="container" id="studentCheckIn">
    <h1 class="form-title">Student Check-In</h1>
    <form action="process_checkin.php" method="POST">
        <div class="input-group">
            <i class="fas fa-id-card"></i>
            <input type="text" name="student_id" placeholder="Student ID Number" required>
        </div>
        <input type="submit" value="Check In & Get Voucher" class="btn">
    </form>
    
    <?php if(isset($_GET['voucher'])): ?>
        <div class="voucher-box" style="margin-top:20px; padding:15px; background:#eef; border-radius:8px; border:2px dashed #6c63ff; text-align:center;">
            <p>Your Wi-Fi Voucher:</p>
            <h2 style="color:#6c63ff;"><?php echo $_GET['voucher']; ?></h2>
            <small>Valid for 2 hours</small>
        </div>
    <?php endif; ?>
</div>

    <script src="script.js"></script>  
</body>
</html>