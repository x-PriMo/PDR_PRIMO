<?php
session_start(); // Rozpoczęcie sesji
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie - PDR PRIMO</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php include 'header_nav.php'; ?>

<section>
    <h2>Logowanie</h2>
    <div class="contact-form">
        <form action="process_login.php" method="POST">
            <label for="username">Nazwa użytkownika:</label>
            <input type="text" id="username" name="username" class="form-input" required>
            
            <label for="password">Hasło:</label>
            <input type="password" id="password" name="password" class="form-input" required>
            
            <button type="submit">Zaloguj się</button>
        </form>

        <!-- Dodanie opcji rejestracji -->
        <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>

        <!-- Wyświetlenie komunikatu o błędzie logowania -->
        <?php
        if (isset($_SESSION['login_error'])) {
            echo "<div class='error-message'>" . $_SESSION['login_error'] . "</div>";
            unset($_SESSION['login_error']); // Usunięcie komunikatu po wyświetleniu
        }
        ?>
    </div>
</section>

<footer>
    <p>&copy; 2024 PDR PRIMO. Wszelkie prawa zastrzeżone.</p>
</footer>

</body>
</html>
