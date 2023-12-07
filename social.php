<?php 
$title = "Social";
include('TopPage.php');
$social = callApiEndpoint($urlAPI,"social");
?>

<div class="container-fluid">
	<div class="row">
		<div class="col-12 offset-md-1 col-md-10 text-center tutto">
			<a href=# title=Clone><i class="muovi social-icon fa fa-clone"></i></a>
			<a href=# title=Cut><i class="muovi social-icon fa fa-cut"></i></a>
			<a href=# title=Paste><i class="muovi social-icon fa fa-paste"></i></a>
			<a href=# title=Download><i class="muovi social-icon fa fa-download"></i></a>
			<a href=# title=Mail><i class="muovi social-icon fa fa-envelope fa-mail"></i></a>
			<a href=# title="Pdf"><i class="muovi social-icon fas fa-file-pdf fa-pdf"></i></a>
			<a href=# title="Bell"><i class="muovi social-icon fas fa-bell"></i></a>
			<?php if (isset($social)) : ?>
			<hr>
			<a href=<?=$social['Telegram']?> target=_blank title="Telegram"><i class="social-icon fab fa-telegram"></i></a>
			<a href=<?=$social['Whatsapp']?> target=_blank title="Whatsapp"><i class="social-icon fab fa-whatsapp"></i></a>
			<a href=<?=$social['Skype']?> target=_blank title="Skype"><i class="social-icon fab fa-skype"></i></a>
			<a href=<?=$social['Btc']?> target=_blank title="Btc"><i class="social-icon fab fa-btc"></i></a>
			<hr>
			<a href=<?=$social['Facebook']?> target=_blank title="Facebook"><i class="social-icon fab fa-facebook"></i></a>
			<a href=<?=$social['Instagram']?> target=_blank title="Instagram"><i class="social-icon fab fa-instagram"></i></a>
			<a href=<?=$social['Twitter']?> target=_blank title="Twitter"><i class="social-icon fab fa-twitter"></i></a>
			<a href=<?=$social['Linkedin']?> target=_blank title="Linkedin"><i class="social-icon fab fa-linkedin"></i></a>
			<a href=<?=$social['Tumblr']?> target=_blank title="Tumblr"><i class="social-icon fab fa-tumblr"></i></a>
			<a href=<?=$social['Pinterest']?> target=_blank title="Pinterest"><i class="social-icon fab fa-pinterest"></i></a>
			<a href=<?=$social['Snapchat']?> target=_blank title="Snapchat"><i class="social-icon fab fa-snapchat"></i></a>
			<a href=<?=$social['Tiktok']?> target=_blank title="Tiktok"><i class="social-icon fab fa-tiktok"></i></a>
			<a href=<?=$social['Quora']?> target=_blank title="Quora"><i class="social-icon fab fa-quora"></i></a>
			<a href=<?=$social['Foursquare']?> target=_blank title="Foursquare"><i class="social-icon fab fa-foursquare"></i></a>
			<hr>
			<a href=<?=$social['Youtube']?> target=_blank title="Youtube"><i class="social-icon fab fa-youtube"></i></a>
			<a href=<?=$social['Twitch']?> target=_blank title="Twitch"><i class="social-icon fab fa-twitch"></i></a>
			<a href=<?=$social['Spotify']?> target=_blank title="Spotify"><i class="social-icon fab fa-spotify"></i></a>
			<a href=<?=$social['Deezer']?> target=_blank title="Deezer"><i class="social-icon fab fa-deezer"></i></a>
			<a href=<?=$social['Soundcloud']?> target=_blank title="Soundcloud"><i class="social-icon fab fa-soundcloud"></i></a>
			<a href=<?=$social['Itunes']?> target=_blank title="Itunes"><i class="social-icon fab fa-itunes"></i></a>
			<a href=<?=$social['Vimeo']?> target=_blank title="Vimeo"><i class="social-icon fab fa-vimeo"></i></a>
			<a href=<?=$social['Dribbble']?> target=_blank title="Dribbble"><i class="social-icon fab fa-dribbble"></i></a>
			<a href=<?=$social['Yahoo']?> target=_blank title="Yahoo"><i class="social-icon fab fa-yahoo"></i></a>
			<a href=<?=$social['Audible']?> target=_blank title="Audible"><i class="social-icon fab fa-audible"></i></a>

			<hr>
			<a href=<?=$social['Google']?> target=_blank title="Google"><i class="social-icon fab fa-google"></i></a>
			<a href=<?=$social['Chromecast']?> target=_blank title="Chromecast"><i class="social-icon fab fa-chromecast"></i></a>
			<a href=<?=$social['Chrome']?> target=_blank title="Chrome"><i class="social-icon fab fa-chrome"></i></a>
			<a href=<?=$social['Android']?> target=_blank title="Android"><i class="social-icon fab fa-android"></i></a>
			<a href=<?=$social['Apple']?> target=_blank title="Apple"><i class="social-icon fab fa-apple"></i></a>
			<a href=<?=$social['Playstation']?> target=_blank title="Playstation"><i class="social-icon fab fa-playstation"></i></a>
			<a href=<?=$social['Amazon']?> target=_blank title="Amazon"><i class="social-icon fab fa-amazon"></i></a>
			<a href=<?=$social['Airbnb']?> target=_blank title="Airbnb"><i class="social-icon fab fa-airbnb"></i></a>
			<hr>
			<?php endif; ?>

		</div>
	</div>
</div>

<?php include('BottomPage.php'); ?>

<script>
	
	$(document).ready(function () {
	// $.ajax({
		// url: getTemplateUrl("XXXX"),
		// type: 'POST',
		// dataType: 'json',
		// data: { name: "aa" },
		// success: genericSuccess,
		// error: handleError
		// });
	});
</script>

</html>