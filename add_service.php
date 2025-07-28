<?php
session_start(); 


$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "pdrprimo"; 

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_code = $_POST['service_code'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $sql = "INSERT INTO services (service_code, description, price) VALUES ('$service_code', '$description', $price)";
    if ($conn->query($sql) === TRUE) {
        echo "Usługa dodana pomyślnie!";
    } else {
        echo "Błąd: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
header("Location: services.php"); 
?>
