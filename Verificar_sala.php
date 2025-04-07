<?php
session_start();

if (!isset($_SESSION['sala_status']) && $_SESSION['sala_status'] === "criada") {
    echo 'criada';
} else {
    echo 'aguardando';
}
?>