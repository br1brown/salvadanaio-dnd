<?php
$checkResto = false;
if (isset($_GET['checkResto']))
  $checkResto = filter_var($_GET['checkResto'], FILTER_VALIDATE_BOOLEAN);

$lista = null;
if (isset($_GET['lista']))
  $lista = json_decode($_GET['lista']);
?>
<div class=row>
  <div class="col-12 col-md-3">
    <i class="fas fa-award platinum-color bordo-ico"></i>
    <input id="platinum" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Platino">
  </div>
  <div class="col-12 col-md-3">
    <i class="fas fa-medal gold-color bordo-ico"></i>
    <input id="gold" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Oro">
  </div>
  <div class="col-12 col-md-3">
    <i class="fas fa-trophy silver-color bordo-ico"></i>
    <input id="silver" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Argento">
  </div>
  <div class="col-12 col-md-3">
    <i class="fas fa-coins copper-color bordo-ico"></i>
    <input id="copper" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Rame">
  </div>
  <?php if ($checkResto) { ?>
    <div class="col" class="text-center">
      <input type="checkbox" id="canReceiveChange" class="form-check-input" checked>
      <label for=canReceiveChange class=small> Posso ricevere resto</label>
    </div>
  <?php } ?>
</div>
<hr class=my-1> <textarea id="description" maxlength="100" class="form-control form-control-sm my-2" rows="2"
  placeholder="Descrizione" style="resize: none;"></textarea>