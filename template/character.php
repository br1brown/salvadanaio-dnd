<div class="card m-2 portafoglio shadow rounded">
	<div class="card-body">
		<h5 class="card-title"><a href="detail.php?name={{filename}}">{{name}}<a></h5>
		<p class="card-text">
		<div class="row text-center">
			<span class="col-12 col-md-12"><i class="fas fa-award platinum-color bordo-ico"></i> Platino: {{platinum}}</span>
		</div>
		<div class="row small text-center">
			<span class="col-12 col-md-6"><i class="fas fa-medal gold-color bordo-ico"></i> Oro: {{gold}}</span>
			<span class="col-12 col-md-6"><i class="fas fa-trophy silver-color bordo-ico"></i> Argento: {{silver}}</span>
		</div>
		<div class="row small text-center">
			<span class="col-12 col-md-12"><i class="fas fa-coins copper-color bordo-ico"></i> Rame: {{copper}}</span>
		</div>
		</p>

		<a href="#{{filename}}" class="btn link-secondary" data-toggle="collapse">Gestione</a>
		<div id="{{filename}}" class="collapse text-center mt-3">
				<input type="button" data-type="success" value=Ricevi onclick="manageMoney('{{name}}', true)" class="btn btn-success">
				<input type="button" data-type="success" value=Spendi onclick="manageMoney('{{name}}', false)" class="btn btn-danger">
		</div>

	</div>
</div>