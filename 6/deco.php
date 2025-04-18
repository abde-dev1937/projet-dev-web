<?php
session_start();
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == 'true') {

$_SESSION = [];

session_destroy();

    echo "
    <script>
        alert('Vous avez été déconnecté avec succès.');
        window.location.href = 'login.php';
    </script>";

header("Location: login.php");
exit;


}

else {
    echo "
    <script>
        alert(\"Vous n'êtes pas connecté.\");
        window.location.href = 'login.php';
    </script>";

}


?>