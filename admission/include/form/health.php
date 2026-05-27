<div class="row">

    <div class="col-xl-8 col-lg-12 offset-2">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title"><?php echo $form ?></h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form method="POST" action="../../app/formhandler.php" enctype='multipart/form-data'>
                        <div class="form-group row" style="display:none;">
                            <label class="col-sm-3 col-form-label">Formnumber</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" required="yes" value="<?php echo $form_view['form_number'] ?>" name="form_number">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-8 col-form-label"><P><strong>Has the applicant undergone any surgical operation before? </strong></P></label>
                            <div class="col-sm-4">
                                <select type="text" class="form-control" required="yes" value="<?php echo $form_view['health_surgery'] ?>" name="surgery">
                                    <option value="<?php echo $form_view['health_surgery'] ?>"><?php echo $form_view['health_surgery'] ?></option>
                                    <option value="">Select</option>
                                    <option value="YES">YES</option>
                                    <option value="NO">NO</option>
                                </select>
                            </div>
                        </div>
                        
                        <h4 class="col-sm-12 col-form-label"><strong> Has the applicant suffered / is suffering from any of the following ILLNESSES before?  </strong> </h4>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_1" name="illness[]" value="TYPHOID , " 
                                <?php 
                                    if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "TYPHOID")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_1">TYPHOID</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_2" name="illness[]" value="POX , "
                                <?php 
                                    if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "POX")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_2">CHICKEN POX</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_3" name="illness[]" value="COUGH , "
                                <?php 
                                if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "COUGH")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_3">WHOOPING COUGH</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_4" name="illness[]" value="RUBELLA , "
                                <?php 
                                if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "RUBELLA")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_4">RUBELLA</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_5" name="illness[]" value="MEASLES , "
                                <?php 
                                if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "MEASLES")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_5">MEASLES</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_6" name="illness[]" value="RESPIRATORY , "
                                <?php 
                                if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "RESPIRATORY")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_6">RESPIRATORY INFECTION</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_7" name="illness[]" value="EARING , "
                                <?php 
                                if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "EARING")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_7">EARING DISABILITY</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_8" name="illness[]" value="EYE , "
                                <?php 
                                if(!empty($form_view['health_illness'])){
                                    if(str_contains($form_view['health_illness'], "EYE")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_8">EYE PROBLEM</label>
                            </div>
                        </div>

                        <h4 class="col-sm-12 col-form-label"><strong> Please tick the VACCINES already given to the applicant </strong> </h4>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_9" name="vaccine[]" value="Polio , "
                                <?php 
                                if(!empty($form_view['health_vaccine'])){
                                    if(str_contains($form_view['health_vaccine'], "Polio")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_9">Polio Vaccine</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_10" name="vaccine[]" value="Pox , "
                                <?php 
                                if(!empty($form_view['health_vaccine'])){
                                    if(str_contains($form_view['health_vaccine'], "Pox")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_10">Small Pox</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_11" name="vaccine[]" value="BCG , "
                                <?php 
                                if(!empty($form_view['health_vaccine'])){
                                    if(str_contains($form_view['health_vaccine'], "BCG")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_11">BCG</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_12" name="vaccine[]" value="Tetanus , "
                                <?php 
                                if(!empty($form_view['health_vaccine'])){
                                    if(str_contains($form_view['health_vaccine'], "Tetanus")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_12">Tetanus</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="custom-control custom-checkbox ml-1">
                                <input type="checkbox" class="custom-control-input" id="basic_checkbox_13" name="vaccine[]" value="Measles , "
                                <?php 
                                if(!empty($form_view['health_vaccine'])){
                                    if(str_contains($form_view['health_vaccine'], "Measles")){
                                    echo 'checked';}}
                                 ?>>
                                <label class="custom-control-label" for="basic_checkbox_13">Measles</label>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-10">
                                <button type="submit" name="action" value="health_data" class="btn btn-primary">Update Academic Data Record</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>