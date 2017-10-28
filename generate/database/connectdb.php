<?php
$servername = 'localhost';
$username = 'root';
$password = '';
$databaseName = 'generate';

// Create connection
$conn = new mysqli($servername, $username, $password, $databaseName);

// Check connection
if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
}
//echo 'Connection established with DB: "'.$databaseName.'"';
?>