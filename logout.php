<?php
session_start();
include('db.php');
    session_unset();
    session_destroy();
    header("Location: list.php");
    exit();
?>
