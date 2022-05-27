<?php

$discID = mysqli_real_escape_string($conn, $_GET["id"]);
$disc = $conn->query("SELECT * FROM `discs` WHERE `id`='$discID' LIMIT 1");
$disc = mysqli_fetch_assoc($disc);

$tagID = "1";

$commentsCount = $conn->query("SELECT COUNT(*) AS total FROM `comments` WHERE `disc`='$discID'");
$commentsCount = mysqli_fetch_assoc($commentsCount);

$uid = $conn->query("SELECT `id` FROM `users` WHERE `username`='".$_SESSION["username"]."' LIMIT 1");
$uid = mysqli_fetch_assoc($uid);
$uid = $uid["id"];
if(!empty($_SESSION["username"])) {
    $isFav = $conn->query("SELECT * FROM `favourites` WHERE `user`='$uid' AND `disc`='$discID'");
    if(mysqli_num_rows($isFav)==1) {
        $isFav = "1";
    } else {
        $isFav = "0";
    }
}

?>

<?php if(!empty($disc["id"])) { ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Viewing Disc: <?= $disc["name"] ?></h3>
    </div>
    <div class="panel-body">
        <div id="viewing-container">
            <p>
                <?= $disc["desc"] ?>
            </p>
            <div class="row">
                <div class="col-sm-6">
                    <a href="<?= $disc["anonfiles"] ?>" targe="_blank" class="btn btn-success form-control">Download</a>
                </div>
                <div class="col-sm-6">
                    <?php if(!empty($_SESSION["username"])) { ?>
                    <?php if($isFav=="0") { ?>
                    <form method="post" action="" name="add_favourite">
                        <input type="text" hidden name="disc" value="<?= $discID ?>">
                        <input type="text" hidden name="user" value="<?= $uid ?>">
                        <input type="submit" name="add_favourite" value="Add to Favourites!" class="btn btn-success form-control">
                    </form>
                    <?php } else { ?>
                    <form method="post" action="" name="remove_favourite">
                        <input type="text" hidden name="disc" value="<?= $discID ?>">
                        <input type="text" hidden name="user" value="<?= $uid ?>">
                        <input type="submit" name="remove_favourite" value="Remove from Favourites!" class="btn btn-danger form-control">
                    </form>
                    <?php } ?>
                    <?php } else { ?>
                    <a href="<?= $url ?>discs/1.<?= $disc["type"] ?>" download class="btn btn-success form-control disabled">Favourite</a>
                    <?php } ?>
                </div>
                <br><br><br>
                <div class="col-sm-6">
                    <a href="#edit" onclick="editDisc()" class="btn btn-warning form-control <?php if(($isAdmin=="0") && ($disc["by"]!==$uID)) { echo "disabled"; } ?>">Edit</a>
                </div>
                <div class="col-sm-6">
                    <a href="#delete" onclick="deleteDisc()" class="btn btn-danger form-control <?php if(($isAdmin=="0") && ($disc["by"]!==$uID)) { echo "disabled"; } ?>">Delete</a>
                </div>
            </div>
        </div>
        <div id="delete">
            <div id="delete-container" style="display:none">
                <form method="post" action="" name="delete_disc">
                    <input type="text" value="<?= $discID ?>" hidden name="disc_id">
                    <label class="col-sm-12 text-center">Are you sure you want to delete this Disc?</label>
                    <input type="submit" value="Delete Disc" name="delete_disc" class="form-control btn btn-danger">
                </form>
                <hr>
                <button onclick="cancelDisc()" class="btn btn-success form-control">No, keep Disc in shelf!</button>
            </div>
        </div>
        <div id="editing-container" style="display:none">
            <?php if(($disc["by"]==$uID) || ($isAdmin=="1")) { ?>
            <form class="form-horizontal" id="edit" method="post" name="edit_disc" action="">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Disc Name:</label>
                    <div class="col-sm-9">
                        <input hidden type="text" name="disc_id" value="<?= $disc["id"] ?>">
                        <input type="text" class="form-control" name="disc_name" value="<?= $disc["name"] ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Description:</label>
                    <div class="col-sm-9">
                        <textarea type="text" style="height:400px;max-width:100%" class="form-control" name="disc_desc" maxlength="20000"><?= $disc["desc"] ?></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-12 text-center">To edit the File, please delete this Disc and create a new one.</label>
                </div>
                <div class="form-group">
                    <div class="col-sm-12">
                        <input type="submit" name="edit_disc" value="Save Disc!" class="form-control btn btn-success">
                    </div>
                </div>
            </form>
            <hr>
            <button class="btn btn-danger form-control" onclick="cancelEdit()">Cancel Editing</button>
            <?php } else { ?>
            You shouldn't be able to view this... but you do...???
            <?php } ?>
        </div>
    </div>
</div>

<script>
    function editDisc() {
        document.getElementById("viewing-container").style.display = "none";
        document.getElementById("editing-container").style.display = "block";
        document.getElementById("commentarys").style.display = "none";
    }

    function deleteDisc() {
        document.getElementById("viewing-container").style.display = "none";
        document.getElementById("delete-container").style.display = "block";
        document.getElementById("commentarys").style.display = "none";
    }

    function cancelDisc() {
        document.getElementById("viewing-container").style.display = "block";
        document.getElementById("delete-container").style.display = "none";
        document.getElementById("commentarys").style.display = "block";
    }

    function cancelEdit() {
        document.getElementById("viewing-container").style.display = "block";
        document.getElementById("editing-container").style.display = "none";
        document.getElementById("commentarys").style.display = "block";
    }

</script>

<div class="panel panel-warning" id="commentarys">
    <div class="panel-heading">
        <h3 class="panel-title">Comments <span class="badge"><?= $commentsCount["total"] ?></span></h3>
    </div>
    <?php
    
    $comments = $conn->query("SELECT * FROM `comments` WHERE `disc`='$discID' ORDER BY `added` ASC");
    
    if($comments->num_rows > 0) {
        while($comment = $comments->fetch_assoc()) { ?>
    <div class="panel-body">
        <?php $username = $conn->query("SELECT `username` AS name FROM `users` WHERE `id`='".$comment["by"]."' LIMIT 1"); $username = mysqli_fetch_assoc($username); ?>
        <b id="comment-<?= $tagID ?>"><a href="#comment-<?= $tagID++ ?>"><?= $username["name"] ?></a></b> said at <b><?= $comment["added"] ?></b>:<br><?= $comment["content"] ?>
        <?php if(($username["name"]==$_SESSION["username"]) || ($isAdmin=="1")) { ?>
        <form name="delete_comment" action="" method="post">
            <input type="text" hidden name="disc" value="<?= $discID ?>">
            <input type="text" hidden name="comment" value="<?= $comment["id"] ?>">
            <input class="btn btn-danger col-sm-offset-9 col-sm-3" type="submit" value="Delete Comment" name="delete_comment">
        </form>
        <?php } ?>
    </div>
    <?php }
    } else {
        echo "<div class='panel-body'>There are no comments at this time.</div>";
    }
    
    ?>
    <div class="panel-body">
        <?php if(!empty($_SESSION["username"])) { ?>
        <form name="add_comment" method="post" action="">
            <input type="tex" name="tagid" value="<?= $tagID ?>" hidden>
            <input type="text" name="user" value="<?= $uid ?>" hidden>
            <input type="text" name="disc" value="<?= $discID ?>" hidden>
            <textarea required class="form-control" style="max-width:100%" name="comment"></textarea><br>
            <input type="submit" class="form-control btn btn-success" value="Add Comment!" name="add_comment">
        </form>
        <?php } ?>
    </div>
</div>
<?php } else { ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Error: Disc not Found</h3>
    </div>
    <div class="panel-body">
        Couldn't find the Disc you were looking for anywhere in the shelf...
    </div>
</div>
<?php } ?>
