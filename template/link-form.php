<?php

$isEdit = isset($_POST['isEdit']) && $_POST['isEdit'] === 'true';
$url = $_POST['url']??'';
$linkText =  $_POST['text']??'';
$note = $_POST['note']??'';
?>

<div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon1"><i class="fas fa-link"></i></span>
  </div>
  <input type="url" id="url" name="url" class="form-control" placeholder="Inserisci l'URL del link" value="<?php echo htmlspecialchars($url); ?>" required>
</div>

<div class="input-group mb-3">
  <div class="input-group-prepend">
    <span class="input-group-text" id="basic-addon2"><i class="fas fa-edit"></i></span>
  </div>
  <input type="text" id="linkText" maxlength="35"  name="linkText" class="form-control" placeholder="Testo del link" value="<?php echo htmlspecialchars($linkText); ?>" required>
</div>
<div class="input-group mb-3">
  <textarea id="note" onkeypress="onkeypress()" maxlength="50" name="note" class="form-control txtawMax" rows="3" placeholder="Nota per le modifiche (opzionale)"><?php echo htmlspecialchars(str_replace(" - ","\n",$note)); ?></textarea>
  <div class='tztRemain'></div>
</div>
<?php ?>
