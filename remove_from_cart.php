<?php
include 'session_start.php';

// Sprawdzenie, czy koszyk istnieje w sesji
if (isset($_SESSION['cart'])) {
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['service_code'])) {
        $service_code = $_POST['service_code'];
        
        // Usunięcie usługi z koszyka
        if (($key = array_search($service_code, $_SESSION['cart'])) !== false) {
            unset($_SESSION['cart'][$key]); // Usunięcie usługi z koszyka
            echo "Usługa usunięta z koszyka!";
        }
    }
}

// Przekierowanie z powrotem do strony koszyka
header("Location: cart.php");
exit();
?>
