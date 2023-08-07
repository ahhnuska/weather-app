<?php
// Database connection parameters
// Database connection parameters
$host = "localhost"; 
$username = "root";   
$password = "";     
$database = "your_database"; 

// Create a connection
$dbConnection = mysqli_connect($host, $username, $password, $database);

// Check the connection
if (!$dbConnection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select the database
mysqli_select_db($dbConnection, $database);

// Sample query
$query = "SELECT * FROM weather_data";
$result = mysqli_query($dbConnection, $query);

// Check query result
if (!$result) {
    die("Query failed: " . mysqli_error($dbConnection));
}

// Fetch and display results
while ($row = mysqli_fetch_assoc($result)) {
    echo "City: " . $row['city'] . " - Temperature: " . $row['temperature'] . "<br>";
}

// Close the connection
mysqli_close($dbConnection);
?>
