<?php

// Update Token
if(isset($_POST["update_token"])) {
    $token = mysqli_real_escape_string($conn, $_POST["user_token"]);
    $user = $uID;
    $conn->query("UPDATE `users` SET `api`='$token' WHERE `id`='$user' LIMIT 1");
    header("location: ".$url."user/token/");
}

// Delete Disc Function
if(isset($_POST["delete_disc"])) {
    $disc = mysqli_real_escape_string($conn, $_POST["disc_id"]);
    $conn->query("DELETE FROM `discs` WHERE `id`='$disc' LIMIT 1");
    $conn->query("DELETE FROM `favourites` WHERE `disc`='$disc'");
    //$conn->query("DELETE FROM `comments` WHERE `disc`='$disc');
    header("location: ".$url."all/1");
}

// Edit Disc Function
if(isset($_POST["edit_disc"])) {
    $disc_id = mysqli_real_escape_string($conn, $_POST["disc_id"]);
    $disc_name = mysqli_real_escape_string($conn, $_POST["disc_name"]);
    $disc_desc = mysqli_real_escape_string($conn, $_POST["disc_desc"]);
    $conn->query("UPDATE `discs` SET `name`='$disc_name', `desc`='$disc_desc' WHERE `id`='$disc_id'");
    header("location: ".$url."disc/$disc_id");
}

// Add Disc Function
if(isset($_POST["add_disc"])) {
    $file = $_FILES["disc_zip"];
    move_uploaded_file($file["tmp_name"], "discs/" . $file["name"]);
    $anonfile = $file["name"];
    
    $ch=curl_init();
    curl_setopt_array($ch,array(
        CURLOPT_URL=>"https://api.anonfiles.com/upload?token=$user_token",
        CURLOPT_POST=>1,
        CURLOPT_RETURNTRANSFER=>1,
        CURLOPT_CONNECTTIMEOUT=>4,
        CURLOPT_POSTFIELDS=>array(
            'file'=>new CURLFile("discs/".$anonfile)
        )
    ));
    $json = curl_exec($ch);
    if(!$json) {
        echo curl_error($ch);
    }
    curl_close($ch);
    $array = json_decode($json, TRUE);
    echo $array["status"];
    if($array["status"]=="1") {
        unlink("discs/".$anonfile);
        $anonlink = $array["data"]["file"]["url"]["short"];
        $disc_name = mysqli_real_escape_string($conn, $_POST["disc_name"]);
        $disc_desc = mysqli_real_escape_string($conn, $_POST["disc_desc"]);
        $disc_user = $uID;
        $conn->query("INSERT INTO `discs`(`name`,`desc`,`by`,`anonfiles`) VALUES('$disc_name','$disc_desc','$disc_user','$anonlink')");
        header("location: ".$url."all/1/added-desc");
    } else {
        $error = "Error: ".$array["error"]["message"]." (Code: ".$array["error"]["code"].", Type: ".$array["error"]["type"].")";
    }
}

// Delete Comment
if(isset($_POST["delete_comment"])) {
    $disc = mysqli_real_escape_string($conn, $_POST["disc"]);
    $user = mysqli_real_escape_string($conn, $_POST["user"]);
    $comment = mysqli_real_escape_string($conn, $_POST["comment"]);
    $conn->query("DELETE FROM `comments` WHERE `id`='$comment'");
    header("location: ".$url."disc/$disc");
}

// Add Comment
if(isset($_POST["add_comment"])) {
    $disc = mysqli_real_escape_string($conn, $_POST["disc"]);
    $user = mysqli_real_escape_string($conn, $_POST["user"]);
    $tagid = mysqli_real_escape_string($conn, $_POST["tagid"]);
    $comment = mysqli_real_escape_string($conn, $_POST["comment"]);
    $conn->query("INSERT INTO `comments`(`by`, `disc`, `content`) VALUES('$user','$disc','$comment')");
    header("location: ".$url."disc/$disc#comment-$tagid");
}

// Remove Favoutires Function
if(isset($_POST["remove_favourite"])) {
    $disc = mysqli_real_escape_string($conn, $_POST["disc"]);
    $user = mysqli_real_escape_string($conn, $_POST["user"]);
    $conn->query("DELETE FROM `favourites` WHERE `disc`='$disc' AND `user`='$user' LIMIT 1");
    header("location: ".$url."disc/$disc");
}

// Add Favoutires Function
if(isset($_POST["add_favourite"])) {
    $disc = mysqli_real_escape_string($conn, $_POST["disc"]);
    $user = mysqli_real_escape_string($conn, $_POST["user"]);
    $conn->query("INSERT INTO `favourites`(`disc`, `user`) VALUES('$disc','$user')");
    header("location: ".$url."disc/$disc");
}

// Login Function
if(isset($_POST["login_user"])) {
    //$error = '<div class="alert alert-danger text-center" role="alert"><b>Error:</b> Something went wrong...</div>';
    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $password_hash = md5($password);
    $remember = mysqli_real_escape_string($conn, $_POST["remember_me"]);
    
    $user = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password_hash'");
    if(mysqli_num_rows($user)=="1") {
        $_SESSION['username'] = $username;
        if($remember=="1") {
            setcookie("loggedincookie", $_SESSION["username"], time()+(86400*30), "/");
        }
        header("location: ".$url."home/");
    } else {
        $error = '<div class="alert alert-danger text-center" role="alert"><b>Error:</b> Password/Username wrong.</div>';
    }
}

// User-Registration
if (isset($_POST['reg_user'])) {
    // receive all input values from the form
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password_1 = mysqli_real_escape_string($conn, $_POST['password_1']);
    $password_2 = mysqli_real_escape_string($conn, $_POST['password_2']);

    // Checking if user already exists
    $user_check_query = "SELECT * FROM `users` WHERE `username`='$username' LIMIT 1";
    $result = mysqli_query($conn, $user_check_query);
    $user = mysqli_fetch_assoc($result);
  
    if ($user) { // if already exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already taken!");
        }
        if ($user['email'] === $email) {
            array_push($errors, "Email already used!");
        }
    }

    // Register User, if everything fine
    if (count($errors) == 0) {
        $password = md5($password_1); //Encrypting password

        $query = "INSERT INTO `users` (`username`, `password`, `admin`, `api`) VALUES ('$username','$password','0','')";
        mysqli_query($conn, $query);
        header("location: ".$url."login/");
    }
}

// Search Function
if(isset($_POST["quicsearch"])) {
    $disc = mysqli_real_escape_string($conn, $_POST["disc_name"]);
    header("location: ".$url."search/$disc/");
    
}

// Page Functions
if($rPage== "home") {
    $rPage = "all";
    $pPage = "All Discs";
} elseif($rPage == "all") {
    $rPage = "all";
    $pPage = "All Discs";
} elseif($rPage == "new") {
    $rPage = "new";
    $pPage = "New Disc";
} elseif($rPage == "search") {
    $rPage = "search";
    $pPage = "Search Disc";
} elseif($rPage == "login") {
    $rPage = "login";
    $pPage = "Login";
} elseif($rPage == "signup") {
    $rPage = "signup";
    $pPage = "Signup";
} elseif($rPage == "logout") {
    $rPage = "logout";
    $pPage = "Logout";
} elseif($rPage == "disc") {
    $rPage = "disc";
    $pPage = "View Disc";
} elseif($rPage == "comments") {
    $rPage = "comments";
    $pPage = "My Comments";
} elseif($rPage == "favourites") {
    $rPage = "favourites";
    $pPage = "My Favourites";
} elseif($rPage == "uploads") {
    $rPage = "uploads";
    $pPage = "My Uploads";
} elseif($rPage == "token") {
    $rPage = "token";
    $pPage = "AnonFiles API Token";
} else {
    $rPage = "all";
    $pPage = "All Discs";
}

?>
