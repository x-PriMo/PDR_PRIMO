<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Rozpoczęcie sesji tylko, jeśli nie jest aktywna
}
?>
