<div class="main_content_iner overly_inner ">
    <div class="container-fluid p-0 ">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="white_card card_height_100 mb_30">
                    <div class="white_card_header">
                        <div class="box_header m-0">
                            <div class="main-title">
                                <h3 class="m-0">Create CBT assessment for <?php echo $active_term['term']; ?></h3>
                            </div>
                        </div>
                    </div>
                    <div class="white_card_body">
                        <form action="../../app/cbt.php" method="POST">
                            <input type="hidden" name="action" value="create_assessment">

                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <label for="title">CBT Title</label>
                                </div>
                                <input type="text" id="title" name="title" class="form-control" placeholder="Example: Week 3 Quick Test" required>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <label for="classid">Select Class</label>
                                </div>
                                <select class="form-select" id="classid" name="classid" required onchange="fetchsubject()">
                                    <option value="">select</option>
                                    <?php foreach ($class_subject_allocated as $data) { ?>
                                        <option value="<?php echo $data['classid']; ?>"><?php echo $data['classname']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <label for="subject">Select Subject</label>
                                </div>
                                <select class="form-select" id="subject" name="subject" required onchange="fetchtask()">
                                </select>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <label for="topic_list">Select Topic</label>
                                </div>
                                <select class="form-select" id="topic_list" name="topic" required>
                                </select>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <span>Deadline</span>
                                </div>
                                <input type="date" name="deadline" min="<?php echo date('Y-m-d'); ?>" class="form-control">
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-text">
                                    <span>Mark Obtainable</span>
                                </div>
                                <input type="number" min="1" step="0.01" name="total_mark" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="instruction" class="form-label">Instruction</label>
                                <textarea id="instruction" name="instruction" rows="4" class="form-control" placeholder="Optional instruction for learners"></textarea>
                            </div>

                            <button class="btn_1 full_width text-center" type="submit">Create CBT Assessment</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center" id="notedata"></div>
    </div>
</div>
