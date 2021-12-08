<?php

session_start();

require_once("config.php");
require_once("functions.php");

if($rPage=="logout") {
    session_destroy();
    session_unset();
    setcookie("loggedincookie", "", time() - 3600);
    header("location: ".$url."home/");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="Saintly2k">
    <meta name="description" content="<?= $config["description"] ?>">
    <meta name="keywords" content="<?= $config["tags"] ?>">

    <meta property="og:site_name" content="<?= $config["name"] ?>">
    <meta property="og:title" content="<?= $pPage." | ".$name ?>">
    <meta property="og:image" content="<?= $url."favicon.ico" ?>">

    <title><?= $pPage." | ".$name ?></title>

    <link href="<?= $url ?>scripts/bootstrap/css/bootstrap.<?= $config["theme"] ?>.css" rel="stylesheet">
    <link href="<?= $url ?>scripts/bootstrap/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="<?= $url ?>scripts/bootstrap/css/bootstrap-checkbox.css" rel="stylesheet">
    <link href="<?= $url ?>scripts/theme.css" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="<?= $url ?>"><span class="glyphicon glyphicon-music"></span> <?= $name ?></a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li <?php if($rPage=="all") { echo 'class="active"'; } ?></l><a href="<?= $url ?>all/1"><span class="glyphicon glyphicon-home"></span> All <span class="sr-only">(current)</span></a></li>
                    <li <?php if($rPage=="new") { echo 'class="active"'; } ?>><a href="<?= $url ?>new/"><span class="glyphicon glyphicon-open"></span> New</a></li>
                    <!-- <li <?php if($rPage=="search") { echo 'class="active"'; } ?>><a href="<?= $url ?>search/"><span class="glyphicon glyphicon-search"></span> Search</a> -->
                    </li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <form class="navbar-form navbar-left" method="post" name="quicsearch" action="">
                        <div class="form-group">
                            <input type="text" class="form-control" name="disc_name" placeholder="What Discin the shelf?">
                        </div>
                        <button type="submit" class="btn btn-default" name="quicsearch">Search!</button>
                    </form>
                    <?php if(empty($_SESSION["username"])) { ?>
                    <li <?php if($rPage=="login") { echo 'class="active"'; } ?>><a href="<?= $url ?>login/"><span class="glyphicon glyphicon-log-in"></span> Login <span class="sr-only">(current)</span></a></li>
                    <li <?php if($rPage=="signup") { echo 'class="active"'; } ?>><a href="<?= $url ?>signup/"><span class="glyphicon glyphicon-plus"></span> Signup</a></li>
                    <?php } else { ?><li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><span class="glyphicon glyphicon-user"></span> <?= $_SESSION["username"] ?> <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="<?= $url ?>user/favourites/">My Favourites</a></li>
                            <li><a href="<?= $url ?>user/comments/">My Comments</a></li>
                            <li><a href="<?= $url ?>user/uploads/">My Uploads</a></li>
                            <li><a href="<?= $url ?>user/token/">My API Token</a></li>
                            <li role="separator" class="divider"></li>
                            <li><a href="<?= $url ?>logout/">Logout</a></li>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>

    <div class="container">

        <div class="row">

            <div class="col-sm-9">

                <?php include($rPage.".php"); ?>

            </div>

            <div class="col-sm-3">

                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h3 class="panel-title">Quicksearch</h3>
                    </div>
                    <div class="panel-body">
                        <form class="" method="post" name="quicsearch" action="">
                            <div class="form-group">
                                <input type="text" class="form-control" name="disc_name" placeholder="What Discin the shelf?">
                            </div>
                            <button type="submit" class="btn btn-success" style="width:100%" name="quicsearch">Search!</button>
                        </form>
                    </div>
                </div>

                <!--<div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Newest Discs</h3>
                    </div>
                    <div class="panel-body">
                        Newest Discs here.
                    </div>
                </div>-->
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <h3 class="panel-title">Your Account</h3>
                    </div>
                    <div class="panel-body">
                        <?php if(empty($_SESSION["username"])) { ?>
                        You aren't logged in. You can't:
                        <ul>
                            <li>Add Discs</li>
                            <li>Edit Discs</li>
                            <li>Comment</li>
                            <li>Vote/Like</li>
                        </ul>
                        <a href="<?= $url ?>login/">Login</a> or <a href="<?= $url ?>signup/">Signup</a>?
                        <?php } else { ?>
                        Welcome, <?= $_SESSION["username"] ?>!
                        <ul>
                            <li><a href="<?= $url ?>user/comments/">My Comments</a></li>
                            <li><a href="<?= $url ?>user/favourites/">My Favourites</a></li>
                            <li><a href=" <?= $url ?>user/uploads/">My Uploads</a></li>
                            <li><a href=" <?= $url ?>user/token/">My API Token</a></li>
                        </ul>
                        <a href="<?= $url ?>logout">Logout?</a>
                        <?php } ?>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- message_container -->
    <div id="message_container" class="display-none"></div>
    <!-- /container -->

    <footer class="footer">
        <p class="text-center text-muted">
            Copyright &copy; <?= date("Y") ?> <a href="<?= $url ?>"><?= $config["name"] ?></a> | Powered by <a href="https://github.com/saintly2k/BurningDiscs" target="_blank">BurningDiscs</a> by saintly2k
        </p>
    </footer>


    <script src="<?= $url ?>scripts/jquery.min.js"></script>
    <script src="<?= $url ?>scripts/jquery.touchSwipe.min.js"></script>
    <script src="<?= $url ?>scripts/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?= $url ?>scripts/bootstrap/js/bootstrap-select.min.js"></script>

</body>

</html>
