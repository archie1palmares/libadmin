<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>
<body style="display:flex; background:#f8fafc;">
    <div class="main-content" style="margin-left: 300px; padding: 40px; width: 100%;">
        <div class="card" style="width: 100%;">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">
                <h2>Student Registry</h2>
                <button class="btn-modern" style="padding: 8px 15px; font-size:0.8rem;">+ Export CSV</button>
            </div>
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align:left; color:var(--text-muted); border-bottom:1px solid #eee;">
                        <th style="padding:15px;">ID Number</th>
                        <th style="padding:15px;">Full Name</th>
                        <th style="padding:15px;">Email</th>
                        <th style="padding:15px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $conn = new mysqli("localhost", "root", "", "library_system");
                    $result = $conn->query("SELECT * FROM students where isActive=1 ORDER BY reg_date DESC");
                    while($row = $result->fetch_assoc()): ?>
                    <tr style="border-bottom: 1px solid #f9f9f9;">
                        <td style="padding:15px; font-family:monospace;"><?php echo $row['student_id']; ?></td>
                        <td style="padding:15px; font-weight:600;"><?php echo $row['fullname']; ?></td>
                        <td style="padding:15px; color:var(--text-muted);"><?php echo $row['email']; ?></td>
                        <td style="padding:15px;">
                            <button style="border:none; background:none; color:var(--primary); cursor:pointer;"><i class="fas fa-edit"></i></button>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>