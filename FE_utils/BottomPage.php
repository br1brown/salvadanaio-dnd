<?php if (isset($footer) && $footer == true):
	class VoceInformazione
	{
		public $chiave;
		public $traduzioneKey;
		public $callback;

		public function __construct($chiave, $traduzioneKey, $callback)
		{
			$this->chiave = $chiave;
			$this->traduzioneKey = $traduzioneKey;
			$this->callback = $callback;
		}

		public function visualizza($dati, $service)
		{
			if (isset($dati[$this->chiave])) {
				$valore = $dati[$this->chiave];
				$testo = $this->traduzioneKey ? $service->traduci($this->traduzioneKey) . ": " : "";
				$testo .= is_callable($this->callback) ? call_user_func($this->callback, $valore) : $valore;
				return $testo;
			}
			return null;
		}

		public static function verificaPresenzaDati($arrayVoceInformazione, $irl)
		{
			foreach ($arrayVoceInformazione as $voce) {
				if (isset($irl[$voce->chiave])) {
					return true;
				}
			}
			return false;
		}
	}




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
				<br>

				<?php
				endif;


				$informazioni = [
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
				];

				$informazioniColonnaDue = [
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
				];

				// Controllo se almeno una delle informazioni è disponibile
				if (VoceInformazione::verificaPresenzaDati(array_merge($informazioni, $informazioniColonnaDue), $irl)):
					?>
				<div class="row">
					<div class="col-12 col-sm-6">
						<ul class="list-unstyled">
							<?php foreach ($informazioni as $voce): ?>
								<?php $output = $voce->visualizza($irl, $service); ?>
								<?php if ($output !== null): ?>
									<li>
										<?= $output ?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
					<div class="col-12 col-sm-6">
						<ul class="list-unstyled">
							<?php foreach ($informazioniColonnaDue as $voce): ?>
								<?php $output = $voce->visualizza($irl, $service); ?>
								<?php if ($output !== null): ?>
									<li>
										<?= $output ?>
									</li>
								<?php endif; ?>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			<?php endif;

				if (
					(isset($url['PrivacyPolicy']) && !empty($url['PrivacyPolicy']))
					|| (isset($url['CookiePolicy']) && !empty($url['CookiePolicy']))
				):
					?>
				<div class="row pt-3">
					<div class="col-12 offset-sm-6 col-sm-6">
						<ul class="list-unstyled">
							<?php
							$policies = [
								'PrivacyPolicy' => 'privacypolicy',
								'CookiePolicy' => 'cookiepolicy',
							];

							foreach ($policies as $policyKey => $translationKey) {
								if (isset($url[$policyKey]) && !empty($url[$policyKey])) {
									echo "<li>";
									if ($routeAttuale == $url[$policyKey]) {
										echo "<strong>" . $service->traduci($translationKey) . "</strong> ";
									} else {
										echo "<a href=\"" . $service->createRoute($url[$policyKey]) . "\">" . $service->traduci($translationKey) . "</a>";
									}
									echo "</li>";
								}
							}
							?>
						</ul>

					</div>
				</div>
			<?php endif; ?>

			<div class="row">
				<div class="col text-center">
					<p>© 2024
						<?php if ($routeAttuale == "index") {
							echo "<strong>" . $AppName . "</strong> ";
						} else {
							echo "<a href=\"" . $service->createRoute("index") . "\">" . $AppName . "</a>";
						}
						?>
						<?= $service->traduci("dirittiriservati"); ?>.
					</p>
					<p class="text-muted">
						<?= $description ?>
					</p>

				</div>
			</div>
		</div>
	</footer>
<?php endif; ?>

</body>