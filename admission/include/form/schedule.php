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
                            <label class="col-sm-4 col-form-label"><strong>Applicant Fullname:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" disabled value="<?php echo $form_view['surname'] . ' ' . $form_view['firstname'] . ' ' . $form_view['othername'] ?>" name="fullname">
                            </div>
                        </div>
                        <div class="form-group row" style="display:none;">
                            <label class="col-sm-4 col-form-label">Formnumber</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['form_number'] ?>" name="form_number">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label"><strong>Select Preffered Exam Date and Time</strong></label>
                            <div class="col-sm-8">
                                <input type="datetime-local" class="form-control" required="yes" value="<?php echo $form_view['exam_date'] ?>" name="schedule">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="action" value="schedule_data" class="btn btn-primary">Schedule Entrance Exam</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>