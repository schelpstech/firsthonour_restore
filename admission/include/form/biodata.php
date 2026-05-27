
 <div class="row">
 <script>
    var loadFile = function(event) {
        var image = document.getElementById('output');
        image.src = URL.createObjectURL(event.target.files[0]);
    };
</script>
 <div class="col-xl-8 col-lg-12 offset-2">
     <div class="card">
         <div class="card-header">
             <h4 class="card-title"><?php echo $form ?></h4>
         </div>
         <div class="card-body">
             <div class="basic-form">
                 <form method="POST" action="../../app/formhandler.php" enctype='multipart/form-data'>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">Passport</label>
                         <div class="col-sm-9">
                             <p><img <?php if(!empty($form_view['passport'])){
                                echo 'src="../../storage/passport/'. $form_view['passport'].'"';
                             }else{
                                echo 'id="output" ';
                             }
                              ?> width="200" /></p>
                             <input type="file"  id="file"  onchange="loadFile(event)" max-size="1000" accept ="image/png, image/jpg, image/jpeg" class="form-control" name="passport">
                         </div>
                     </div>
                     <div class="form-group row" style="display:none;">
                         <label class="col-sm-3 col-form-label">Formnumber</label>
                         <div class="col-sm-9">
                             <input type="text" class="form-control" required="yes" value="<?php echo $form_view['form_number'] ?>" name="form_number">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">Surname</label>
                         <div class="col-sm-9">
                             <input type="text" class="form-control" required="yes" value="<?php echo $form_view['surname'] ?>" name="surname">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">Firstname</label>
                         <div class="col-sm-9">
                             <input type="text" class="form-control" required="yes" value="<?php echo $form_view['firstname'] ?>" name="firstname">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">Othername</label>
                         <div class="col-sm-9">
                             <input type="text" class="form-control" required="yes" value="<?php echo $form_view['othername'] ?>" name="othername">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">Date of birth</label>
                         <div class="col-sm-9">
                             <input type="date" class="form-control" required="yes" value="<?php echo  $form_view['dateofbirth'] ?? '' ?>" name="dateofbirth">
                         </div>
                     </div>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">Gender</label>
                         <div class="col-sm-9">
                             <select type="text" class="form-control" required="yes" name="gender">
                                 <option value="<?php echo $form_view['gender'] ?? ''; ?>"><?php echo  $form_view['gender'] ?? 'Select Gender'; ?></option>
                                 <option value="Male">Male</option>
                                 <option value="Female">Female</option>
                             </select>
                         </div>
                     </div>
                     <div class="form-group row">
                         <label class="col-sm-3 col-form-label">State of Origin</label>
                         <div class="col-sm-9">
                             <select type="text" class="form-control" required="yes" name="stateoforigin">
                                 <option value="<?php echo  $form_view['state_of_origin'] ?? '';  ?>"><?php echo $form_view['state_of_origin'] ?? 'Select State of Origin';  ?></option>
                                 <?php
                                 foreach ($location as $location) {
                                 ?>
                                     <option value="<?php echo $location['state'] ?>"><?php echo $location['state'] ?></option>
                                 <?php
                                 }
                                 ?>
                                 <option value="Other">Other</option>

                             </select>
                         </div>
                     </div>


                     <div class="form-group row">
                         <div class="col-sm-10">
                             <button name="action" value="bio_data_form" type="submit" class="btn btn-primary">Update Bio Data Record</button>
                         </div>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </div>
</div>