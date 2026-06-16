<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <?php if (empty($cbt_details)) { ?>
            <div class="alert alert-danger">CBT assessment was not found.</div>
        <?php } else { ?>
            <div class="row">
                <div class="col-lg-5">
                    <div class="white_card card_height_100 mb_30">
                        <div class="white_card_header">
                            <div class="box_header m-0">
                                <div class="main-title">
                                    <h3 class="m-0">Add CBT Question</h3>
                                    <span><?php echo ucwords($cbt_details['title']); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="white_card_body">
                            <form action="../../app/cbt.php" method="POST">
                                <input type="hidden" name="action" value="add_question">
                                <input type="hidden" name="cbtid" value="<?php echo $cbt_details['cbtid']; ?>">

                                <div class="mb-3">
                                    <label class="form-label" for="question_text">Question</label>
                                    <textarea id="question_text" name="question_text" class="form-control" rows="4" required></textarea>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">A</span>
                                    <input type="text" name="option_a" class="form-control" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">B</span>
                                    <input type="text" name="option_b" class="form-control" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">C</span>
                                    <input type="text" name="option_c" class="form-control" required>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">D</span>
                                    <input type="text" name="option_d" class="form-control" required>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Correct Option</span>
                                    <select name="correct_option" class="form-select" required>
                                        <option value="">select</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>

                                <div class="input-group mb-3">
                                    <span class="input-group-text">Question Mark</span>
                                    <input type="number" name="mark" min="0.01" step="0.01" class="form-control" required>
                                </div>

                                <button class="btn_1 full_width text-center" type="submit">Add Question</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-7">
                    <div class="white_card mb_30">
                        <div class="white_card_header">
                            <div class="box_header m-0">
                                <div class="main-title">
                                    <h3 class="m-0">Questions</h3>
                                    <span><?php echo count($cbt_questions); ?> question(s) added</span>
                                </div>
                                <a class="btn_3" href="../../app/router.php?pageid=cbt&ref=<?php echo $cbt_details['cbtid']; ?>">Preview CBT</a>
                            </div>
                        </div>
                        <div class="white_card_body">
                            <?php if (!empty($cbt_questions)) { ?>
                                <?php foreach ($cbt_questions as $index => $question) { ?>
                                    <div class="card mb-3">
                                        <div class="card-body">
                                            <h5><?php echo ($index + 1) . '. ' . $question['question_text']; ?></h5>
                                            <p class="mb-1"><strong>A.</strong> <?php echo $question['option_a']; ?></p>
                                            <p class="mb-1"><strong>B.</strong> <?php echo $question['option_b']; ?></p>
                                            <p class="mb-1"><strong>C.</strong> <?php echo $question['option_c']; ?></p>
                                            <p class="mb-1"><strong>D.</strong> <?php echo $question['option_d']; ?></p>
                                            <p><strong>Answer:</strong> <?php echo $question['correct_option']; ?> | <strong>Mark:</strong> <?php echo $question['mark']; ?></p>
                                            <form action="../../app/cbt.php" method="POST">
                                                <input type="hidden" name="action" value="remove_question">
                                                <input type="hidden" name="cbtid" value="<?php echo $cbt_details['cbtid']; ?>">
                                                <input type="hidden" name="questionid" value="<?php echo $question['questionid']; ?>">
                                                <button class="btn btn-danger" type="submit">Remove Question</button>
                                            </form>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } else { ?>
                                <div class="alert alert-info">No question has been added yet.</div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
