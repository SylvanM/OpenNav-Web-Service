<?php

require_once("editors.php");
require_once("sql.php");

chdir("../layouts");

$f = $_GET["f"];
$c = $_GET["c"];

$pass = isset($_GET['pass']) ? $_GET['pass'] : "";
$usr =  isset($_GET['u'])    ? $_GET['u']    : "";

if ($f == "u") {
    upload($c, $usr, $pass);
} else if ($f == "d") {
    $p = $_GET["p"];
    download($c, $p);
} else if ($f == "t") {
    test($c);
}

// tests if a code exists
function codeExists($code) {
    return file_exists($code);
}

// TESTING Layouts
function test($code) {
    if (codeExists($code)) {
        echo "1";
        return true;
    } else {
        echo "0";
        return false;
    }
}

// ADDING Layouts
function upload($code, $user, $pass) {

    // first, verify that the user is logged in
    if (!verify($user, $pass)) {
        die("Incorrect Login");
    }

    // next, verify that the user exists
    if (!verifyOwnership($user, $code)) {
        die("Code does not belong to user");
    }

    // now make sure that the code belongs to the user

    $dir = $code;

    if (!codeExists($code)) {
        mkdir($dir, 0777, true);
    }

    chdir($code);
    echo getcwd() . "\n\n";

    // now update the data in the directory

    // set rooms
    $roomsURL = "rooms";
    file_put_contents($roomsURL, file_get_contents($_FILES['r']['tmp_name']));

    // set layout
    $layoutURL = "layout";
    file_put_contents($layoutURL, file_get_contents($_FILES['l']['tmp_name']));

    // set info
    $infoURL = "info";
    file_put_contents($infoURL, file_get_contents($_FILES['e']['tmp_name']));

    // set images
    
    // make directory for images
    $imagesURL = "images";
    file_put_contents($imagesURL, file_get_contents($_FILES['i']['tmp_name']));

}

// RETRIEVING Layouts

// downloads a specific part of a layout
function download($code, $part) {

    if (!codeExists($code)) {
        // How unfortunate, we need to return an error
        die("Error: $code does not exist");
    }

    $url = realpath("../layouts/$code");
    chdir($code);

    /* 
     * Values of "$part"
     * l - layout
     * r - rooms
     * e - info
     * i - images
     */

    switch ($part) {
        case "layout":
            $url .= "/layout";
            break;
        case "rooms":
            $url .= "/rooms";
            break;
        case "info":
            $url .= "/info";
            break;
        case "images":
            $url .= "/images";
            break;
    }

    readfile($url);
}

?>