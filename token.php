<?php
if(!empty($_SESSION["username"])) {
?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">AnonFiles API Token</h3>
    </div>
    <div class="panel-body">
        <form class="form-horizontal" method="post" name="update_token" action="">
            <div class="form-group">
                <label class="col-sm-3 control-label">API Key:</label>
                <div class="col-sm-9">
                    <input type="text" value="<?= $user_token ?>" name="user_token" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-12 text-center">You can find your key here: <a href="https://anonfiles.com/docs/api" target="_blank">https://anonfiles.com/docs/api</a><br>(when logged in, e.g: "hajn2ifs98" AND NOT "?token=hajn2ifs98")</label>
            </div>
            <div class="form-group">
                <div class="col-sm-12">
                    <input type="submit" class="btn btn-success form-control" value="Save Token" name="update_token">
                </div>
            </div>
        </form>
    </div>
</div>
<?php } else { ?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">Error!</h3>
    </div>
    <div class="panel-body">
        This page is only for Members...
    </div>
</div>
<?php } ?>
