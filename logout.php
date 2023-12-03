<?php
if (!isset($_SESSION)) {
    session_start();
}

/* DELETES THE SESSION DATA **/
session_unset();

/* DESTROY SESSION*/
session_destroy();

/* REDIRECT TO THE INDEX.PHP PAGE*/
header('Location: index.php');