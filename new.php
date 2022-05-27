<?php if(!isset($error)) $error = ""; ?>

<?php if(!empty($_SESSION["username"])) { ?>
<?php if(empty($user_token)) { ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Error!</h3>
    </div>
    <div class="panel-body">
        You haven't set your AnonFiles API Token yet... <a href="<?= $url ?>user/token/">you can do it here</a>!
    </div>
</div>
<?php } else { ?>
<?= $error ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">New Disc</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" action="" method="post" name="add_disc" enctype="multipart/form-data">
            <div class="form-group">
                <label class="col-sm-3 control-label">Name of Disc:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" name="disc_name" required maxlength="200">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Further Description:</label>
                <div class="col-sm-9">
                    <textarea type="text" style="height:400px;max-width:100%" class="form-control" name="disc_desc" required maxlength="20000">Like a Songlist, from who the Songs are and from when. Maybe even File-details like Size of each song, track length, etc. Supports BBCode!</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">ZIP File (Max. <?php echo ini_get('upload_max_filesize').'B'; ?>):</label>
                <div class="col-sm-9">
                    <input class="form-control" required type="file" name="disc_zip" id="file">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input class="form-control btn btn-success" value="Upload ZIP and add Disc!" type="submit" name="add_disc" id="file">
                </div>
            </div>
        </form>
    </div>
</div>
<?php } } else { ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Error!</h3>
    </div>
    <div class="panel-body">
        You can't add new Discs because you aren't logged in...
    </div>
</div>
<?php } ?>
