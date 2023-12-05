<?php if (isset($footer) && $footer == true) : ?>
<footer style="font-size: 0.8rem;" class="container-fluid bg-dark text-light mt-3">
	<div class="container py-2">
		<?php if (isset($irl)) : ?>
		<div class="row text-center">
			<?php if (isset($irl['numeroWA'])) : ?>
				<a class="col" href="https://wa.me/<?=str_replace(' ', '', $irl['numeroWA'])?>" target=_blank title=Whatsapp><i class="social-icon fab fa-whatsapp"> Whatsapp</i></a>
			<?php endif; ?>
			<?php if (isset($irl['ig'])) : ?>
				<a class="col" href=<?=$irl['ig']?> target=_blank title=Instagram><i class="social-icon fab fa-instagram"> Instagram</i></a>
			<?php endif; ?>
			<?php if (isset($irl['yt'])) : ?>
				<a class="col" href=<?=$irl['yt']?> target=_blank title=Instagram><i class="social-icon fab fa-youtube"></i></a>
			<?php endif; ?>
		</div>
		<br>
		<div class="row">
		<div class="col-12 col-sm-6">
			<ul class="list-unstyled">
			<?php if (isset($irl['nomeCognome'])) : ?>
				<li><?=$irl['nomeCognome']?></li>
			<?php endif; ?>
			<?php if (isset($irl['indirizzoSedeLegale'])) : ?>
				<li><?=$irl['indirizzoSedeLegale']?></li>
			<?php endif; ?>
			<?php if (isset($irl['numeroTelefono'])) : ?>
				<li>Telefono: <a href="tel:<?=str_replace(' ', '', $irl['numeroTelefono'])?>"><?=$irl['numeroTelefono']?></a></li>
			<?php endif; ?>
			<?php if (isset($irl['pec'])) : ?>
				<li>PEC: <a href="mailto:<?=$irl['pec']?>"><?=$irl['pec']?></a></li>
			<?php endif; ?>
			</ul>
		</div>
		<div class="col-12 col-sm-6">
			<ul class="list-unstyled">
			<?php if (isset($irl['partitaIVA'])) : ?>
				<li>Partita IVA: <code><?=$irl['partitaIVA']?></code></li>
			<?php endif; ?>
			<?php if (isset($irl['registroImprese'])) : ?>
				<li>Ufficio del Registro delle Imprese d’Iscrizione: <code><?=$irl['registroImprese']?></code></li>
			<?php endif; ?>
			<?php if (isset($irl['numeroIscrizione'])) : ?>
				<li>Numero di Iscrizione all’Albo: <code><?=$irl['numeroIscrizione']?></code></li>
			<?php endif; ?>
			<?php if (isset($irl['numeroREA'])) : ?>
				<li>Numero REA: <code><?=$irl['numeroREA']?></code></li>
			<?php endif; ?>
			<?php if (isset($irl['PrivacyPolicy'])) : ?>
				<li class="pt-3"><a href="<?=$irl['PrivacyPolicy']?>">Privacy policy</a></li>
			<?php endif; ?>
			<?php if (isset($irl['CookiePolicy'])) : ?>
				<li><a href="<?=$irl['CookiePolicy']?>">Cookie policy sito web</a></li>
			<?php endif; ?>
			</ul>
		</div>
		</div>
        <?php endif; ?>

		<div class="row">
		<div class="col text-center text-light">
			<p>© 2023 <?= $AppName?>. Tutti i diritti riservati.</p>
		</div>
		</div>
	</div>
	</footer>
<?php endif; ?>
</body>