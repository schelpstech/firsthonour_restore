<?php
$count = 1;
if (!empty($list_cbt)) {
    foreach ($list_cbt as $cbt) {
        $attempt = $cbt['attempt_score'] ?? null;
?>
        <div class="col-md-3">
            <div class="white_card position-relative mb_20 ">
                <div class="card-body">
                    <div class="ribbon1 rib1-primary"><span class="text-white text-center rib1-primary"><?php echo $count++; ?></span></div>
                    <i class="fas fa-laptop-code" style="font-size:108px; color:green; text-align:center;"></i>
                    <div class="row my-4">
                        <div class="col">
                            <span class="badge_btn_3 mb-1">CBT</span>
                            <a href="#" class="f_w_400 color_text_3 f_s_14 d-block"><?php echo ucwords($cbt['title']); ?></a>
                            <small><?php echo ucwords($cbt['topic']); ?></small>
                        </div>
                        <div class="col-auto">
                            <h4 class="text-dark mt-0"><small class="text-muted font-14"><?php echo ucwords($cbt['week']); ?></small></h4>
                        </div>
                    </div>
                    <div class="row my-4">
                        <div class="col">
                            <strong>Deadline:</strong><br><?php echo $cbt['deadline'] ?: 'Open'; ?>
                        </div>
                        <div class="col">
                            <strong>Mark:</strong><br><?php echo $cbt['total_mark']; ?>
                        </div>
                    </div>
                    <?php if ($_SESSION['user_type'] === 'Learner' && $attempt !== null) { ?>
                        <div class="alert alert-success">Submitted: <?php echo $attempt; ?> / <?php echo $cbt['attempt_total']; ?></div>
                    <?php } ?>
                    <div class="d-grid">
                        <a href="../../app/router.php?pageid=cbt&ref=<?php echo $cbt['cbtid']; ?>" class="btn_3" style="text-align:center;">
                            <?php echo $_SESSION['user_type'] === 'Learner' ? 'Take/View CBT' : 'Preview CBT'; ?>
                        </a>
                    </div>
                    <?php if ($_SESSION['user_type'] === 'Instructor') { ?>
                        <div class="d-grid">
                            <a href="../../app/router.php?pageid=resources&item=manage_cbt&item_ref=<?php echo $cbt['cbtid']; ?>" class="btn_2" style="text-align:center;">Manage Questions</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php
    }
} else {
    echo '<div class="col-md-3">
        <div class="white_card position-relative mb_20 ">
            <div class="card-body">
                <i class="fas fa-ban" style="font-size:108px; color:red; text-align:center;"></i>
                <div class="row my-4">
                    <div class="col-auto">
                        <h4 class="text-dark mt-0">No CBT assessment created for this subject yet</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
?>
