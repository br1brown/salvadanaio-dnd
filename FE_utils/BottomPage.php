</div>
<?php if (isset($footer) && $footer == true) : 
	
	?>
<footer style="font-size: 0.8rem; background-color:var(--coloreTema)" class="container-fluid mt-3 <?=$isDarkTextPreferred? "text-dark":"text-light" ?>">
	<div class="container py-2">
		<?php // Controllo se almeno una delle chiavi è impostata e non vuota
		if ((isset($irl['numeroWA']) && !empty($irl['numeroWA'])) ||
			(isset($irl['ig']) && !empty($irl['ig'])) ||
			(isset($irl['yt']) && !empty($irl['yt'])))
			:
		?>
			<div class="row text-center">
				<?php if (isset($irl['numeroWA'])) : ?>
					<a class="col" href onClick="openEncodedLink('https://wa.me/', '<?= $service->convertiInEntitaHTML(str_replace(' ', '', $irl['numeroWA'])) ?>')" target=_blank title=Whatsapp><i class="social-icon fab fa-whatsapp"> Whatsapp</i></a>
				<?php endif; ?>
				<?php if (isset($irl['ig'])) : ?>
					<a class="col" href="<?= $irl['ig'] ?>" target=_blank title=Instagram><i class="social-icon fab fa-instagram"> Instagram</i></a>
				<?php endif; ?>
				<?php if (isset($irl['yt'])) : ?>
					<a class="col" href="<?= $irl['yt'] ?>" target=_blank title=Youtube><i class="social-icon fab fa-youtube"> Youtube</i></a>
				<?php endif; ?>
			</div>
			<br>

		<?php
		endif;
		// Controllo se almeno una delle informazioni è disponibile
		if (
		   isset($irl['nomeCognome'])
		|| isset($irl['indirizzoSedeLegale'])
		|| isset($irl['numeroTelefono'])
		|| isset($irl['pec'])
		|| isset($irl['mail'])
		|| isset($irl['partitaIVA'])
		|| isset($irl['registroImprese'])
		|| isset($irl['numeroIscrizione'])
		|| isset($irl['numeroREA'])
		|| (isset($url['PrivacyPolicy']) && !empty($url['PrivacyPolicy']))
		|| (isset($url['CookiePolicy']) && !empty($url['CookiePolicy']))
		) :
		?>
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
							<li>Telefono: <?=$service->creaLinkCodificato(str_replace(' ', '', $irl['numeroTelefono']), 'tel:')?></li>
						<?php endif; ?>
						<?php if (isset($irl['pec'])) : ?>
							<li>PEC: <?=$service->creaLinkCodificato($irl['pec'], 'mailto:')?></li>
						<?php endif; ?>
						<?php if (isset($irl['mail'])) : ?>
							<li>Mail: <?=$service->creaLinkCodificato($irl['mail'], 'mailto:')?></li>
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
						<?php if (isset($url['PrivacyPolicy']) && !empty($url['PrivacyPolicy'])) : ?>
							<li class="pt-3"><a href="<?=$url['PrivacyPolicy']?>">Privacy policy</a></li>
						<?php endif; ?>
						<?php if (isset($url['CookiePolicy']) && !empty($url['CookiePolicy'])) : ?>
							<li><a href="<?=$url['CookiePolicy']?>">Cookie policy sito web</a></li>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		<?php
		endif; ?>
		<div class="row">
		<div class="col text-center">
			<p>© 2023 <?= $AppName?>. Tutti i diritti riservati.</p>
		</div>
		</div>
	</div>
	</footer>
<?php endif; ?>
</body>