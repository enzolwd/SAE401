<?php
session_start();
session_destroy();
header('Location: ../Vue/Page_De_Connexion.php');
exit();
?>