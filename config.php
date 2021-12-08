<?php

// You can edit this stuff
$config["name"] = "BurningDiscs"; // Page Title
$config["url"] = "https://burningdiscs.h33t.moe/"; // Main Domain, like "domain.com/" WITH SLASH ENDING!!
$config["folder"] = ""; // If inside subfolder (like domain.com/discs/), then it is "discs/" WITH SLASH!!
$config["description"] = "BurningDiscs is your place to donload Lists of Tracks pre-made for you to instantly burn them on a Disc after downloading. You can save Discs to your facourites by logging in or signing up."; // SEO Description
$config["tags"] = "music, music piracy, 1337x, music ddl, burning discs, disc, free music download"; // SEO Tags
$config["theme"] = "2"; // 1 = Light; 2 = Dark

$slave["host"] = "localhost"; // MySQL Host
$slave["user"] = "root"; // MySQL User
$slave["pass"] = "root"; // User Password
$slave["tale"] = "bd-rocks"; // MySQL Table

// Don't edit below stuff, SERIOUSLY, DO NOT EDIT IT! (only if u wanna break it)
$url = $config["url"].$config["folder"];
$name = $config["name"];

$conn = new mysqli($slave["host"], $slave["user"], $slave["pass"], $slave["tale"]);

if ($conn->connect_error) {
  die("Burning Discs failed: " . $conn->connect_error);
}

$rPage = $_GET["page"];

if(!empty($_SESSION["username"])) {
    $user = $_SESSION["username"];
    $user = $conn->query("SELECT * FROM `users` WHERE `username`='$user' LIMIT 1");
    $user = mysqli_fetch_assoc($user);
    $uID = $user["id"];
    $user_token = $user["api"];
    if($user["admin"]=="1") {
        $isAdmin = "1";
    } else {
        $isAdmin = "0";
    }
} else {
    $isAdmin = "0";
    $uID = "0";
}

?>
