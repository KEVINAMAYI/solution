<?php
function init()
{
    require_once('classes/StickyForm.php');
    $path = "index.php?page=login";
    $stickyForm = new StickyForm();

    if (!isset($_SESSION)) {
        session_start();
    }

    if ($stickyForm->checkLogin()) {

        $name = $_SESSION['user']['name'];
        return ["<h1>Welcome</h1>", "<p>Welcome {$name} </p>"];
    }
    header('location: ' . $path);
}

?>