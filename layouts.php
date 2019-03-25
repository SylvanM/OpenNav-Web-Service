<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);

//include_once "editors.php";

chdir("../layouts");

$f = $_GET["f"];
$c = $_GET["c"];
if ($f == "u") {
    upload($c);
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
    } else {
        echo "0";
    }
}

// ADDING Layouts

function upload($code) {

    // first, verify the user
    //if (!verify($usr, $hsh)) {
    //    die("Invalid user");
    //}

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
        case "l":
            $url .= "/layout";
            break;
        case "r":
            $url .= "/rooms";
            break;
        case "e":
            $url .= "/info";
            break;
        case "i":
            $url .= "/images";
            break;
    }

    readfile($url);

}

?>