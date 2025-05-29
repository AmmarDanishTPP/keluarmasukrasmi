<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Sambungan ke pangkalan data
$conn = new mysqli("localhost", "root", "", "keluarmasuk");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Semak ID rekod
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("No valid record ID provided.");
}

$record_id = intval($_GET['id']);

// Query untuk ambil maklumat pelajar berdasarkan record_id
$sql = "SELECT r.register_number, r.course, r.semester, r.room_number,
               r.phone_number, r.parent_phone, r.check_out_date, r.check_in_date,
               r.reason, r.other_details
        FROM records r
        LEFT JOIN users u ON r.student_id = u.id
        WHERE r.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $record_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Paparkan maklumat pelajar
    echo "<h2>Butiran Pelajar</h2>";
    echo "<div class='details-container'>";
    echo "<table>";
    echo "<tr><th>No. Pendaftaran</th><td>" . htmlspecialchars($row['register_number'] ?? '') . "</td></tr>";
    echo "<tr><th>Kursus</th><td>" . htmlspecialchars($row['course'] ?? '') . "</td></tr>";
    echo "<tr><th>Semester</th><td>" . htmlspecialchars($row['semester'] ?? '') . "</td></tr>";
    echo "<tr><th>Bilik</th><td>" . htmlspecialchars($row['room_number'] ?? '') . "</td></tr>";
    echo "<tr><th>Telefon Pelajar</th><td>" . htmlspecialchars($row['phone_number'] ?? '') . "</td></tr>";
    echo "<tr><th>Telefon Penjaga</th><td>" . htmlspecialchars($row['parent_phone'] ?? '') . "</td></tr>";
    echo "<tr><th>Tarikh Keluar</th><td>" . htmlspecialchars($row['check_out_date'] ?? '') . "</td></tr>";
    echo "<tr><th>Tarikh Masuk</th><td>" . htmlspecialchars($row['check_in_date'] ?? '') . "</td></tr>";
    echo "<tr><th>Tujuan Keluar</th><td>" . htmlspecialchars($row['reason'] ?? '') . "</td></tr>";
    echo "<tr><th>Sebab Tujuan Keluar Lain</th><td>" . htmlspecialchars($row['other_details'] ?? '') . "</td></tr>";
    echo "</table>";
    echo "</div>";
} else {
    echo "<p style='color:red;'>Rekod tidak dijumpai.</p>";
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Details</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f7f9fc;
            color: #333;
            margin: 5px;
            padding: 0px;
        }
        h2 {
            text-align: center;
            font-size: 2.5rem;
            color: #2c3e50;
            margin-top: 35px;
        }
        .details-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .details-container table {
            width: 100%;
            border-collapse: collapse;
        }
        .details-container th, .details-container td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .details-container th {
            background-color: #3498db;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .details-container td {
            font-size: 1.1rem;
        }
        .back-button {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
        .back-button a {
            padding: 10px 20px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .back-button a:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<div class="back-button">
    <a href="warden_records.php">Kembali ke Halaman Rekod</a>
</div>

</body>
</html>
