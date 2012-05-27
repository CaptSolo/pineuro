<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title><?php echo isset($_GET['q'])? ($_GET['q']." - "):""; ?>Europ.in - explore and share Europeana</title>
	<meta name="viewport" content="width=device-width,initial-scale=1" />
	<link rel="stylesheet" href="/assets/css/reset.css" />
	<link rel="stylesheet" href="/assets/css/style.css" />
	<link rel="stylesheet" href="/assets/css/bootstrap.css" />
	<link rel="stylesheet" href="/assets/fancybox/jquery.fancybox-1.3.4.css" />
</head>
<body>
	<div id="header">
		<a href="/"><img src="/assets/images/logo_small.png" height="30" width="118" alt="" class="logo"></a>
		<form id="f" action="/search" class="search">
			<input type="text" id="q" name="q" value="<?php echo isset($_GET['q'])?$_GET['q']:""; ?>" class="search_bar" />
			<button id="s" class="btn btn-small">Search</button>
		</form>
		<div id="count"></div>
	</div>
	<div id="main" class="clearfix">
		<ul id="tiles"></ul>
	</div>
	<div style="display:none;">
		<div id="popup">
			<div id="popup_img">
				<div id="popup_img_title">Saldus vidusskola</div>
			</div>
			<div id="popup_side">
				<ul>
					<lh>Country:</lh>
					<li id="datacountry"></li>
					<lh>Provider:</lh>
					<li id="dataprovider"></li>
					<lh>Europeana URI:</lh>
					<li id="dataoriginaluri"></li>
				</ul>
			</div>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="/assets/fancybox/jquery.fancybox-1.3.4.js"></script>
	<script src="/assets/fancybox/jquery.easing-1.3.pack.js"></script>
	<script src="/assets/fancybox/jquery.mousewheel-3.0.4.pack.js"></script>
	<script src="/assets/js/wookmark.js"></script>
	<script src="http://balupton.github.com/history.js/scripts/bundled/html4+html5/jquery.history.js"></script>
	<script>
		var searchTerm = "<?php echo isset($_GET['q'])?$_GET['q']:""; ?>";
	</script>
	<script src="/assets/js/app.js"></script>
</body>
</html>
