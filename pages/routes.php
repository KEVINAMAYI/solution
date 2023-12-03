<?php

if (!isset($_SESSION)) {
    session_start();
}

ini_set('display_errors', '0');
$path = "index.php?page=login";
$nav = '';

if ($_SESSION['user']['status'] == 'admin') {

    $nav = <<<HTML
    <nav style="margin-top:10px;">
        <ul style="list-style: none; margin-top:20px;">
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=welcome"></a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=addContact">Add Contact</a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=deleteContacts">Delete contact(s)</a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=addAdmin">Add Admin</a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=deleteAdmins">Delete Admins(s)</a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=logout">Logout</a></li>
        </ul>
    </nav>
HTML;

} else {

    $nav = <<<HTML
    <nav style="margin-top:10px;">
        <ul style="list-style: none; margin-top:20px;">
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=welcome"></a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=addContact">Add Contact</a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=deleteContacts">Delete contact(s)</a></li>
            <li style=" margin: 0 10px; display: inline"><a href="index.php?page=logout">Logout</a></li>
        </ul>
    </nav>
HTML;

}


if (isset($_GET)) {
    if ($_GET['page'] === "addContact") {
        require_once('pages/addContact.php');
        $result = init();
    } else if ($_GET['page'] === "deleteContacts") {
        require_once('pages/deleteContacts.php');
        $result = init();
    } else if ($_GET['page'] === "deleteAdmins") {
        require_once('pages/deleteAdmins.php');
        $result = init();
    } else if ($_GET['page'] === "addAdmin") {
        require_once('pages/addAdmin.php');
        $result = init();
    } else if ($_GET['page'] === "welcome") {
        require_once('pages/welcome.php');
        $result = init();
    } else if ($_GET['page'] === "login") {
        require_once('pages/login.php');
        $result = init();
    } else if ($_GET['page'] === "logout") {
        require_once('logout.php');
        $result = init();
    } else {
        header('location: ' . $path);
    }
} else {
    header('location: ' . $path);
}

?>