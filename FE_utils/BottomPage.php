<?php if (isset($footer) && $footer == true):
	?>
	<footer style="font-size: 0.8rem;bottom: 0;" class="container-fluid mt-3 fillColoreSfondo <?= $clsTxt ?>">
		<div class="container py-1">
			<?php // Controllo se almeno una delle chiavi è impostata e non vuota
				if (
					(isset($irl['numeroWA']) && !empty($irl['numeroWA'])) ||
					(isset($irl['ig']) && !empty($irl['ig'])) ||
					(isset($irl['yt']) && !empty($irl['yt']))
				)
				:
					?>
				<div class="row text-center">
					<?php if (isset($irl['numeroWA'])): ?>
						<div class="col">
							<a href
								onClick="openEncodedLink('https://wa.me/', '<?= $service->convertiInEntitaHTML(str_replace(' ', '', $irl['numeroWA'])) ?>')"
								target=_blank title=Whatsapp><i class="social-icon fab fa-whatsapp"> Whatsapp</i></a>
						</div>
					<?php endif; ?>
					<?php if (isset($irl['ig'])): ?>
						<div class="col">
							<a href="<?= $irl['ig'] ?>" target=_blank title=Instagram><i class="social-icon fab fa-instagram">
									Instagram</i></a>
						</div>
					<?php endif; ?>
					<?php if (isset($irl['yt'])): ?>
						<div class="col">
							<a href="<?= $irl['yt'] ?>" target=_blank title=Youtube><i class="social-icon fab fa-youtube">
									Youtube</i></a>
						</div>
					<?php endif; ?>
				</div>

				<?php
				endif;
				?>
			<div class="row my-1">
				<?php
				if (isset($irl)) {
					echo VoceInformazione::staticrenderInfos([
						new VoceInformazione('ragioneSociale', null, null),
						new VoceInformazione('indirizzoSedeLegale', null, null),
						new VoceInformazione('numeroTelefono', 'telefono', function ($val) use ($service) {
							return $service->creaLinkCodificato(str_replace(' ', '', $val), 'tel:');
						}),
						new VoceInformazione('pec', 'PEC', function ($val) use ($service) {
							return $service->creaLinkCodificato($val, 'mailto:');
						}),
						new VoceInformazione('mail', 'mail', function ($val) use ($service) {
							return $service->creaLinkCodificato($val, 'mailto:');
						}),
					], $irl, $service);
				}
				if (isset($irl)) {
					echo VoceInformazione::staticrenderInfos([
						new VoceInformazione('partitaIVA', 'partitaiva', function ($val) {
							return "<code>$val</code>";
						}),
						new VoceInformazione('registroImprese', 'registroimprese', function ($val) {
							return "<code>$val</code>";
						}),
						new VoceInformazione('numeroIscrizione', 'iscrizionealbo', function ($val) {
							return "<code>$val</code>";
						}),
						new VoceInformazione('numeroREA', 'numerorea', function ($val) {
							return "<code>$val</code>";
						}),
					], $irl, $service);
				}
				if (isset($url)) {
					echo VoceInformazione::staticrenderInfos([
						new VoceInformazione('PrivacyPolicy', null, function ($val) use ($service, $routeAttuale) {
							if (!empty($val)) {
								return $routeAttuale == $val
									? "<strong>" . $service->traduci('privacypolicy') . "</strong>"
									: "<a href='" . $service->createRoute($val) . "'>" . $service->traduci('privacypolicy') . "</a>";
							}
							return null;
						}),
						new VoceInformazione('CookiePolicy', null, function ($val) use ($service, $routeAttuale) {
							if (!empty($val)) {
								return $routeAttuale == $val
									? "<strong>" . $service->traduci('cookiepolicy') . "</strong>"
									: "<a href='" . $service->createRoute($val) . "'>" . $service->traduci('cookiepolicy') . "</a>";
							}
							return null;
						}),
					], $url, $service);
				}
				?>
			</div>

			<div class="row">
				<div class="col text-center">
					<p>© 2024
						<?php if ($routeAttuale == "index") {
							echo "<strong>" . $AppName . "</strong> ";
						} else {
							echo "<a href=\"" . $service->createRoute("index") . "\">" . $AppName . "</a>";
						}
						?>
						<?= $service->traduci("dirittiriservati"); ?>.<br>
						<span class="text-muted">
							<?= $description ?>
						</span>
					</p>

				</div>
			</div>
		</div>
	</footer>
<?php endif; ?>

</body>