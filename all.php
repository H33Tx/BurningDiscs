<?php

if(empty($_GET["order"])) {
    $order = "id-asc";
} else {
    $order = mysqli_real_escape_string($conn, $_GET["order"]);
}
if($order=="name-desc") {
    $dream = 'SELECT * FROM `discs` ORDER BY `name` DESC LIMIT ?,?';
} elseif($order=="id-asc") {
    $dream = 'SELECT * FROM `discs` ORDER BY `id` ASC LIMIT ?,?';
} elseif($order=="id-desc") {
    $dream = 'SELECT * FROM `discs` ORDER BY `id` DESC LIMIT ?,?';
} elseif($order=="added-asc") {
    $dream = 'SELECT * FROM `discs` ORDER BY `added` ASC LIMIT ?,?';
} elseif($order=="added-desc") {
    $dream = 'SELECT * FROM `discs` ORDER BY `added` DESC LIMIT ?,?';
} else {
    $dream = 'SELECT * FROM `discs` ORDER BY `name` ASC LIMIT ?,?';
}

$total_pages = $conn->query('SELECT COUNT(*) FROM `discs`')->fetch_row()[0];

$page = isset($_GET['pagination']) && is_numeric($_GET['pagination']) ? $_GET['pagination'] : 1;

$num_results_on_page = "50";

if ($stmt = $conn->prepare($dream)) {
	$calc_page = ($page - 1) * $num_results_on_page;
	$stmt->bind_param('ii', $calc_page, $num_results_on_page);
	$stmt->execute(); 
	$result = $stmt->get_result();
	$stmt->close();
}

?>
<div class="panel panel-success">
    <div class="panel-heading">
        <h3 class="panel-title">All Discs - Page <?= $page ?></h3>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-condensed table-hover">
                <thead>
                    <tr>
                        <th width="10%"><a href="<?= $url ?>all/<?= $page ?>/id-<?php if($order=="id-desc") { echo "asc"; } else { echo "desc"; } ?>">ID <span class="glyphicon glyphicon-arrow-<?php if($order=="id-desc") { echo "down"; } else { echo "up"; } ?>"></span></a></th>
                        <th width="50%"><a href="<?= $url ?>all/<?= $page ?>/name-<?php if($order=="name-desc") { echo "asc"; } else { echo "desc"; } ?>">Name <span class="glyphicon glyphicon-arrow-<?php if($order=="name-desc") { echo "down"; } else { echo "up"; } ?>"></span></a></th>
                        <th width="10%" class="text-right">Uploader</th>
                        <th width="10%" class="text-right">Comments</th>
                        <th width="20%" class="text-right"><a href="<?= $url ?>all/<?= $page ?>/added-<?php if($order=="added-desc") { echo "asc"; } else { echo "desc"; } ?>">Added <span class="glyphicon glyphicon-arrow-<?php if($order=="added-desc") { echo "down"; } else { echo "up"; } ?>"></span></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($disc = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?= $disc["id"] ?></td>
                        <td><a href=" <?= $url ?>disc/<?= $disc["id"] ?>"><?= $disc["name"] ?></a></td>
                        <?php
                            $uploader = $disc["by"];
                            $uploader = $conn->query("SELECT * FROM `users` WHERE `id`='$uploader' LIMIT 1");
                            $uploader = mysqli_fetch_assoc($uploader);
                            $uploader = $uploader["username"];
                            $discID = $disc["id"];
                            $comments = $conn->query("SELECT COUNT(*) AS total FROM `comments` WHERE `disc`='$discID'");
                            $comments = mysqli_fetch_assoc($comments);
                        ?>
                        <td class="text-right"><?= $uploader ?></td>
                        <td class="text-right"><?= $comments["total"] ?></td>
                        <td class="text-right"><?= $disc["added"] ?></td>
                    </tr>

                    <?php }
                    } else {
                        echo "<tr><td>0</td><td>No Discs found matching your criteria.</td><td class='text-right'>NULL</td><td class='text-right'>NULL</td><td class='text-right'>NULL</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="panel-body text-center">

        <?php if (ceil($total_pages / $num_results_on_page) > 0): ?>
        <ul class="pagination">
            <?php if ($page > 1): ?>
            <li class="prev"><a href="<?php echo $url; echo "all/"; echo $page-1 ?>">Prev</a></li>
            <?php endif; ?>

            <?php if ($page > 3): ?>
            <li class="start"><a href="<?php echo $url; echo "all/"; ?>1">1</a></li>
            <li class="dots"></li>
            <?php endif; ?>

            <?php if ($page-2 > 0): ?><li class="page"><a href="<?php echo $url; echo "all/"; echo $page-2 ?>"><?php echo $page-2 ?></a></li><?php endif; ?>
            <?php if ($page-1 > 0): ?><li class="page"><a href="<?php echo $url; echo "all/"; echo $page-1 ?>"><?php echo $page-1 ?></a></li><?php endif; ?>

            <li class="currentpage"><a href="<?php echo $url; echo "all/"; echo $page; ?>"><?php echo $page ?></a></li>

            <?php if ($page+1 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="<?php echo $url; echo "all/"; echo $page+1 ?>"><?php echo $page+1 ?></a></li><?php endif; ?>
            <?php if ($page+2 < ceil($total_pages / $num_results_on_page)+1): ?><li class="page"><a href="<?php echo $url; echo "all/"; echo $page+2 ?>"><?php echo $page+2 ?></a></li><?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)-2): ?>
            <li class="dots"></li>
            <li class="end"><a href="<?php echo $url; echo "all/"; echo ceil($total_pages / $num_results_on_page) ?>"><?php echo ceil($total_pages / $num_results_on_page) ?></a></li>
            <?php endif; ?>

            <?php if ($page < ceil($total_pages / $num_results_on_page)): ?>
            <li class="next"><a href="<?php echo $url; echo "all/"; echo $page+1 ?>">Next</a></li>
            <?php endif; ?>
        </ul>
        <?php endif; ?>
    </div>
</div>
