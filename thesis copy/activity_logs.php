<?php 
// 1. Connect to your 'login' database
$conn = new mysqli("localhost", "root", "", "login");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Fetch total counts for the summary header
$total_entries = $conn->query("SELECT COUNT(*) as total FROM checkin_logs")->fetch_assoc()['total'];
$peak_entries = $conn->query("SELECT COUNT(*) as total FROM checkin_logs WHERE is_peak_hour = 1")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activity Logs | LibAdmin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="style2.css"> </head>
<body style="display: flex; background: #f8fafc;">

    <div class="sidebar">
        <h2 style="color: var(--primary); margin-bottom: 40px; padding-left: 20px;">
            <i class="fas fa-book-reader"></i> LibAdmin
        </h2>
        <div class="nav-container">
            <a href="admin.php" class="nav-btn"><i class="fas fa-chart-line"></i> Dashboard</a>
            <a href="manage_students.php" class="nav-btn"><i class="fas fa-users"></i> Students</a>
            <a href="activity_logs.php" class="nav-btn active"><i class="fas fa-history"></i> Logs</a>
            <a href="analytics.php" class="nav-btn"><i class="fas fa-brain"></i> Analysis</a>
            <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 10px;">
            <a href="logout.php" class="nav-btn" style="color: #ff4d4d;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
            <div>
                <h1 style="font-weight: 800;">Entry Records</h1>
                <p style="color: var(--text-muted);">Monitoring <?php echo $total_entries; ?> total student sessions.</p>
            </div>
            <button onclick="window.print()" class="btn-modern" style="background: #64748b;">
                <i class="fas fa-print"></i> Export PDF
            </button>
        </header>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px;">
            <div class="card" style="padding: 20px; border-left: 5px solid var(--primary);">
                <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold;">Total Visits</small>
                <h2 style="margin-top: 5px;"><?php echo $total_entries; ?></h2>
            </div>
            <div class="card" style="padding: 20px; border-left: 5px solid #f59e0b;">
                <small style="color: var(--text-muted); text-transform: uppercase; font-weight: bold;">Peak Hour Traffic</small>
                <h2 style="margin-top: 5px;"><?php echo $peak_entries; ?></h2>
            </div>
        </div>

        <div class="card">
            <table style="width: 100%;">
                <thead>
                    <tr>
                        <th>Check-in Time</th>
                        <th>Student Name</th>
                        <th>Student ID</th>
                        <th>Voucher Code</th>
                        <th>System Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // JOIN query to get names from students table based on student_id
                    $sql = "SELECT checkin_logs.*, students.fullname 
                            FROM checkin_logs 
                            LEFT JOIN students ON checkin_logs.student_id = students.student_id 
                            ORDER BY checkin_logs.id DESC";
                    
                    $result = $conn->query($sql);

                    if ($result && $result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            // Match your backend variable: is_peak_hour
                            $statusText = ($row['is_peak_hour'] == 1) ? "Peak Period" : "Normal";
                            $badgeClass = ($row['is_peak_hour'] == 1) ? "badge-warning" : "badge-success";
                            $formattedTime = date('M d, h:i A', strtotime($row['checkin_time']));

                            echo "<tr>
                                    <td style='color: var(--text-muted); font-size: 0.85rem;'>$formattedTime</td>
                                    <td style='font-weight: 600;'>".($row['fullname'] ?? 'Unknown Student')."</td>
                                    <td style='font-family: monospace; font-weight: bold; color: var(--primary);'>{$row['student_id']}</td>
                                    <td><code style='background: #f1f5f9; padding: 5px 10px; border-radius: 8px; color: #4338ca; font-weight: bold;'>{$row['voucher_code']}</code></td>
                                    <td><span class='badge $badgeClass'>$statusText</span></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5' style='text-align:center; padding:50px; color:var(--text-muted);'>No activity logs found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
<?php $conn->close(); ?>