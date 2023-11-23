<?php
$isEdit = isset($_POST['isEdit']) && $_POST['isEdit'] === 'true';
$itemName = $_POST['itemName']??'';
$quantity =  $_POST['quantity']??'1';
$description = $_POST['description']??'';
?>
<div class="row">
  <div class="col-12 col-md-9">
<input type="text" id="itemName" name="itemName" class="form-control form-control-sm" placeholder="Nome dell'oggetto" value="<?php echo htmlspecialchars($itemName); ?>" required>
</div>

<div class="col-12 col-md-3">
<input type="number" min=0 id="quantity" name="quantity" class="form-control form-control-sm" placeholder="Quantità" value="<?php echo htmlspecialchars($quantity); ?>" required>
</div>

<div class="col-12 mb-2">
  <textarea id="description" name="description" class="form-control" maxlength=100 rows="2" placeholder="Descrizione (opzionale)" style="resize: none;"><?php echo htmlspecialchars(str_replace(" - ","\n",$description)); ?></textarea>
</div>
