<?php

// Database Connection Details
$servername = "127.0.0.1";
$username = "root";
$password = null;
$dbname = "kukoma_taxi";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if database already exists
$result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$dbname'");

if ($result->num_rows > 0) {
    echo null;
} else {
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS ".$dbname;
    if ($conn->query($sql) === FALSE) {
        echo "Error creating database: " . $conn->error;
        exit();
    }

    // Select the database
    $conn->select_db($dbname);

    // Get the SQL dump file contents
    $sql_dump = file_get_contents("kukoma_taxi.sql");

    // Execute the SQL queries to create tables
    if ($conn->multi_query($sql_dump) === TRUE) {
        echo "Tables created successfully";
    } else {
        echo "Error creating tables: " . $conn->error;
    }

    // Close the database connection
    $conn->close();

	header("Location:index.php");
}


?>
