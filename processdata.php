<?php
// Initialize variables to avoid PHP warnings
$name = $password = "";
$success = false;

// --- DATABASE CONNECTION ---
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "practice";
$port = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $port);

// 1. Connection Check
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// NOTE: We only display "Database connected successfully" once the script is done, 
// or only if the POST check is false, to keep the output clean.
// For now, let's keep it simple:
// echo "Database connected successfully"; 

// --- FORM PROCESSING & DATABASE INSERTION ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // 2. Data Retrieval
    $name = trim($_POST["fullname"]);
    $password = trim($_POST["pass"]);
    
    // Assuming validation passed (as per your example)
    
    // 3. Prepare the SQL Statement
    // Ensure the table 'users' and columns 'name', 'password' exist.
    $stmt = $conn->prepare("INSERT INTO users (fullname, password) VALUES (?, ?)");
    
    // Check if the statement prepared successfully (good practice)
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    
    // 4. Bind parameters
    $stmt->bind_param("ss", $param_name, $param_password);
    
    // 5. Set parameters and hash the password
    $param_name = $name;
    $param_password = password_hash($password, PASSWORD_DEFAULT);

    // 6. Execute the statement and check for success
    if ($stmt->execute()) {
        $success = true; 
        
        // 7. Success Action (Alert)
        echo "<script>";
        echo 'alert("Success! Your data has been stored");';
        echo '</script>';
        
    } else {
        // Handle insertion failure
        echo "Error executing query: " . $stmt->error;
    }
    
    // Close the statement
    $stmt->close();
}

// Close the database connection regardless of submission status
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
    // This is where you can show the status if needed (e.g., if $success is true)
    if ($success) {
        echo "<p style='color: green;'>Data inserted and connection closed.</p>";
    } else {
        echo "<p style='color: gray;'>Awaiting form submission...</p>";
    }
    ?>
    <a href="http://localhost/Php/domain/getPost.php#">Go back?</a>
</body>
</html>