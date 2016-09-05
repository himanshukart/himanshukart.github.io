<div class="container padding">
  <div class="row">
    <?php if(!empty($result)){ foreach($result as $value) {?>
    <div class="col-lg-10 col-lg-offset-2 notify">
      <?php echo $value['message']; ?>
    </div>
<?php }} ?>
  </div>
</div>
