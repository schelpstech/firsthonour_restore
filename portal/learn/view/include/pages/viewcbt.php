<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <?php if (empty($cbt_details)) { ?>
            <div class="alert alert-danger">CBT assessment was not found.</div>
        <?php } else { ?>
            <div class="row">
                <div class="col-12">
                    <div class="page_title_box d-flex align-items-center justify-content-between">
                        <div class="page_title_left">
                            <h3 class="f_s_30 f_w_700 dark_text"><?php echo ucwords($cbt_details['title']); ?></h3>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Subject</a></li>
                                <li class="breadcrumb-item active"><?php echo ucwords($cbt_details['sbjname']); ?></li>
                            </ol>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Topic</a></li>
                                <li class="breadcrumb-item active"><?php echo ucwords($cbt_details['topic']); ?></li>
                            </ol>
                            <ol class="breadcrumb page_bradcam mb-0">
                                <li class="breadcrumb-item"><a href="javascript:void(0);">Mark Obtainable</a></li>
                                <li class="breadcrumb-item active"><?php echo $cbt_details['total_mark']; ?></li>
                            </ol>
                        </div>
                        <?php if ($_SESSION['user_type'] === 'Instructor') { ?>
                            <a class="white_btn3" href="../../app/router.php?pageid=resources&item=manage_cbt&item_ref=<?php echo $cbt_details['cbtid']; ?>">Manage Questions</a>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php if (!empty($cbt_details['instruction'])) { ?>
                <div class="alert alert-info"><?php echo nl2br($cbt_details['instruction']); ?></div>
            <?php } ?>

            <?php if ($_SESSION['user_type'] === 'Learner' && !empty($cbt_attempt)) { ?>
                <div class="alert alert-success">
                    You have submitted this CBT. Score: <strong><?php echo $cbt_attempt['score']; ?> / <?php echo $cbt_attempt['total_mark']; ?></strong>
                    on <?php echo $cbt_attempt['submitted_at']; ?>.
                </div>
            <?php } elseif ($_SESSION['user_type'] === 'Learner') { ?>
                <?php if (!empty($cbt_details['deadline']) && date('Y-m-d') > $cbt_details['deadline']) { ?>
                    <div class="alert alert-danger">This CBT deadline has passed.</div>
                <?php } elseif (empty($cbt_questions)) { ?>
                    <div class="alert alert-info">Questions have not been added to this CBT yet.</div>
                <?php } else { ?>
                    <form action="../../app/cbt.php" method="POST">
                        <input type="hidden" name="action" value="submit_cbt">
                        <input type="hidden" name="cbtid" value="<?php echo $cbt_details['cbtid']; ?>">
                        <?php foreach ($cbt_questions as $index => $question) { ?>
                            <div class="white_card mb_30">
                                <div class="white_card_body">
                                    <h5><?php echo ($index + 1) . '. ' . $question['question_text']; ?></h5>
                                    <?php foreach (['A' => 'option_a', 'B' => 'option_b', 'C' => 'option_c', 'D' => 'option_d'] as $option => $field) { ?>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="answer[<?php echo $question['questionid']; ?>]" value="<?php echo $option; ?>" id="q<?php echo $question['questionid'] . $option; ?>" required>
                                            <label class="form-check-label" for="q<?php echo $question['questionid'] . $option; ?>">
                                                <?php echo $option . '. ' . $question[$field]; ?>
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                        <button class="btn_1 full_width text-center" type="submit">Submit CBT</button>
                    </form>
                <?php } ?>
            <?php } else { ?>
                <?php if (!empty($cbt_questions)) { ?>
                    <?php foreach ($cbt_questions as $index => $question) { ?>
                        <div class="white_card mb_30">
                            <div class="white_card_body">
                                <h5><?php echo ($index + 1) . '. ' . $question['question_text']; ?></h5>
                                <p class="mb-1"><strong>A.</strong> <?php echo $question['option_a']; ?></p>
                                <p class="mb-1"><strong>B.</strong> <?php echo $question['option_b']; ?></p>
                                <p class="mb-1"><strong>C.</strong> <?php echo $question['option_c']; ?></p>
                                <p class="mb-1"><strong>D.</strong> <?php echo $question['option_d']; ?></p>
                                <p><strong>Answer:</strong> <?php echo $question['correct_option']; ?> | <strong>Mark:</strong> <?php echo $question['mark']; ?></p>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <div class="alert alert-info">No question has been added yet.</div>
                <?php } ?>
            <?php } ?>
        <?php } ?>
    </div>
</div>
