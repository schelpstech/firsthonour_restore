<div class="row">

    <div class="col-xl-8 col-lg-12 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $form ?></h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="../../app/formhandler.php" enctype='multipart/form-data'>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Name of Last School Attended:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['last_school'] ?>" name="last_school">
                            </div>
                        </div>

                        <div class="form-group row" style="display:none;">
                            <label class="col-sm-3 col-form-label">Formnumber</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['form_number'] ?>" name="form_number">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Exit Class</label>
                            <div class="col-sm-9">
                                <select type="text" class="form-control" required="yes" value="<?php echo $form_view['last_class'] ?>" name="last_class">
                                    <option value="<?php echo $form_view['last_class'] ?>"><?php echo $form_view['last_class'] ?></option>
                                    <option value="">Select Class</option>
                                    <?php
                                    foreach ($class_list as $class_ref) {
                                    ?>
                                        <option value="<?php echo $class_ref['classname'] ?>"><?php echo $class_ref['classname'] ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Completion Year</label>
                            <div class="col-sm-9">
                                <input type="year" class="form-control" required="yes" minlength="4" maxlength="4" value="<?php echo $form_view['last_year'] ?>" name="last_year">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">Most Recent Result</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control" name="last_result">
                            </div>
                            <iframe <?php if (!empty($form_view['last_result'])) {
                                        echo 'src="../../storage/credential/' . $form_view['last_result'] . '"';
                                    } else {
                                        echo 'src="../../storage/credential/noresult.png"';
                                    }
                                    ?> title="Most Recent Result" height="400" width="600"></iframe>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="action" value="academic_data" class="btn btn-primary">Update Academic Data Record</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>