
<?php  if(!empty($addpublisher)) {
   foreach ($addpublisher as $key => $value) {
  if($value['publisher'] == 0) { ?>
  <div class="checkbox col-md-offset-1 ">
    <label><input type="checkbox" value="<?php echo $value['id']; ?>"><?php echo $value['user_name']; ?></label>
  </div>
<?php } }?>

<button class="btn col-md-offset-1" id="" onclick="add_notify('publishers')"> Save </button>
 <?php } ?>
 <?php if(!empty($removepublisher)) {
    foreach ($removepublisher as $key => $value) {
   if($value['publisher'] == 1) { ?>
   <div class="checkbox col-md-offset-1 ">
     <label><input type="checkbox" value="<?php echo $value['id']; ?>" checked="checked"><?php echo $value['user_name']; ?></label>
   </div>
 <?php } }?>

 <button class="btn col-md-offset-1" id="" onclick="remove_notify('publishers')"> Save </button>
  <?php } ?>
  <?php if(!empty($addsubscriber)) {
     foreach ($addsubscriber as $key => $value) {
    if($value['subscriber'] == 0) { ?>
    <div class="checkbox col-md-offset-1 ">
      <label><input type="checkbox" value="<?php echo $value['id']; ?>"><?php echo $value['user_name']; ?></label>
    </div>
  <?php } }?>

  <button class="btn col-md-offset-1" id="" onclick="add_notify('subscribers')"> Save </button>
   <?php } ?>
   <?php if(!empty($removesubscriber)) {
      foreach ($removesubscriber as $key => $value) {
     if($value['subscriber'] == 1) { ?>
     <div class="checkbox col-md-offset-1 ">
       <label><input type="checkbox" checked="checked"value="<?php echo $value['id']; ?>"><?php echo $value['user_name']; ?></label>
     </div>
   <?php } }?>

   <button class="btn col-md-offset-1" id="" onclick="remove_notify('subscribers')"> Save </button>
    <?php } ?>
