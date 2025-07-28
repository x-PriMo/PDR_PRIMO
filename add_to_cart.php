<?php include 'session_start.php'; ?>
<?php

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = []; 
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_code'])) {
    $service_code = $_POST['service_code'];
    

    if (!in_array($service_code, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $service_code;
        echo "Usługa dodana do koszyka!";
    } else {
        echo "Usługa już w koszyku!";
    }
}


header("Location: services.php");
exit();
?>
