<?php
$spendi = filter_var($_GET['spendi'], FILTER_VALIDATE_BOOLEAN);
?>

<div style="display: flex">
  <i class="fas fa-award platinum-color bordo-ico"></i>
  <input id="platinum" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Platino">
</div>
<div style="display: flex">
  <i class="fas fa-medal gold-color bordo-ico"></i>
  <input id="gold" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Oro">
</div>
<div style="display: flex">
  <i class="fas fa-trophy silver-color bordo-ico"></i>
  <input id="silver" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Argento">
</div>
<div style="display: flex">
  <i class="fas fa-coins copper-color bordo-ico"></i>
  <input id="copper" type="number" min=0 class="form-control form-control-sm my-2" placeholder="Rame">
</div>
<?php if ($spendi){ ?>
  <div style="display: flex" class="text-center">
  <input type="checkbox" id="canReceiveChange" class="form-check-input" checked>
  <label for=canReceiveChange class=small> Posso ricevere resto</label>
</div>
<?php } ?>

<hr class=my-2> <textarea id="description" class="form-control form-control-sm my-2" rows="3" placeholder="Descrizione" style="resize: none;"></textarea>