<?php include 'session_start.php'; ?>

<header>
    <h1>PDR PRIMO</h1>
</header>

<nav>     
    <div class="nav-center">
        <a href="index.php">O nas</a>
        <a href="pdrhistory.php">PDR</a>
        <a href="services.php">Usługi</a>
        <a href="gallery.php">Galeria</a>
        <a href="contact.php">Kontakt</a>
    </div>
    
    <?php
    if (isset($_SESSION['username'])) {
        // Użytkownik jest zalogowany
        echo '<a href="logout.php" class="login-button">Wyloguj się</a>';
        
        // Sprawdzenie roli użytkownika
        if ($_SESSION['role'] === 'admin') {    
            // Ikona profilu dla administratora
            echo '<a href="admin_dashboard.php" class="login-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.31 0-10 1.67-10 5v2h20v-2c0-3.33-6.69-5-10-5z"/>
                    </svg>
                  </a>';
        } elseif ($_SESSION['role'] === 'user') {
            // Ikona koszyka dla użytkowników
            echo '<a href="cart.php" class="login-button">
                    <svg fill="#ffffff" xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 902.86 902.86">
                        <path d="M671.504,577.829l110.485-432.609H902.86v-68H729.174L703.128,179.2L0,178.697l74.753,399.129h596.751V577.829z M685.766,247.188l-67.077,262.64H131.199L81.928,246.756L685.766,247.188z"/>
                        <path d="M578.418,825.641c59.961,0,108.743-48.783,108.743-108.744s-48.782-108.742-108.743-108.742H168.717c-59.961,0-108.744,48.781-108.744,108.742s48.782,108.744,108.744,108.744c59.962,0,108.743-48.783,108.743-108.744c0-14.4-2.821-28.152-7.927-40.742h208.069c-5.107,12.59-7.928,26.342-7.928,40.742C469.675,776.858,518.457,825.641,578.418,825.641z M209.46,716.897c0,22.467-18.277,40.744-40.743,40.744c-22.466,0-40.744-18.277-40.744-40.744c0-22.465,18.277-40.742,40.744-40.742C191.183,676.155,209.46,694.432,209.46,716.897z M619.162,716.897c0,22.467-18.277,40.744-40.743,40.744s-40.743-18.277-40.743-40.744c0-22.465,18.277-40.742,40.743-40.742S619.162,694.432,619.162,716.897z"/>
                    </svg>

                  </a>';
        }
    } else {
        // Użytkownik nie jest zalogowany
        echo '<a href="login.php" class="login-button">Zaloguj się</a>';
    }
    ?>
</nav>
