<!doctype html>
<html class="no-js" lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>europ.in</title>
	<meta name="viewport" content="width=device-width,initial-scale=1">

	<link rel="stylesheet" href="assets/css/reset.css">
	<link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

	<div id="header">
		<form id="f" action="/search">
			<input type="text" id="q" value="<?php echo isset($_GET['q'])?$_GET['q']:""; ?>" />
			<button id="s">Search</button>
		</form>
	</div>

	<div id="main">
		<ul id="tiles"></ul>
	</div>

	<style>
		#popup {
			display: none;
			position: absolute;
			top: 100px;
			left: 100px;
			width: 800px;
			height: 500px;
			background-color: #333;
			z-index: 1000;
			border: 2px solid #000;
		}
		#popup_img {
			float: left;
			margin: 15px;
			width: 500px;
			height: 470px;
			background: #dedede url("http://social.apps.lv/image.php?cc=dedede&w=470&h=470&zc=2&src=http%3A%2F%2Fzudusilatvija.lv%2Fstatic%2Ffiles%2F11%2F02%2F09%2FSaldus-Gimnazija_1_png_600x375_watermark-zl_watermark-r20xb20_q85.jpg") no-repeat center center;
		}
		#popup_side {
			float: left;
			margin: 15px auto;
			padding: 15px;
			width: 225px;
			height: 440px;
			background-color: #efefef;
		}
		#popup_background {
			display: none;
			position: relative;
			top: 0;
			left: 0;
			height: 100%;
			width: 100%;
			background-color: rgba(0,0,0,0.8);
			z-index: 5;
		}
		#popup_img_title {
			background-color: rgba(0,0,0,0.7);
			font: 14px Arial;
			color: #fff;
			padding: 10px 7px;
		}
		#popup_side ul {
			list-style-type: none;
			margin: 0; padding: 0;
		}
		#popup_side lh {
			font-weight: bold;
		}
		#popup_side li {
			padding-left: 5px;
		}
	</style>
	<div id="popup_background"></div>
	<div id="popup">
			<div id="popup_img">
				<div id="popup_img_title">Saldus vidusskola</div>
			</div>
			<div id="popup_side">
				<ul>
					<lh>Country:</lh>
					<li>Latvia</li>
					<lh>Provider:</lh>
					<li>Latvijas nacionālā bibliotēka</li>
				</ul>
			</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
	<script src="assets/js/wookmark.js"></script>
	<script>
		var totalpages = 0,
			api_key = "HTMQFSCKKB",
			current_page = 1,
			searchTerm = "<?php echo isset($_GET['q'])?$_GET['q']:""; ?>";
		/*
		$("#f").submit(function(){
			if($("#q").val() != ""){
				document.location.hash = "!/search/"+encodeURIComponent($.trim($("#q").val()))
			}
			return false
		})
		$("#s").on("click", function(){
			if($("#q").val() != ""){
				document.location.hash = "!/search/"+encodeURIComponent($.trim($("#q").val()))
				$("#main").css({height: 0})
				current_page = 1
				$("#tiles").html("")
				searchTerm = $("#q").val()
				load_images({
					searchTerm: searchTerm,
					page: current_page
				})
				load_images({
					searchTerm: searchTerm,
					page: current_page
				})
				load_images({
					searchTerm: searchTerm,
					page: current_page
				})
			}
		})
		*/

		function load_images(options){
			$.getJSON("http://api.europeana.eu/api/opensearch.json?callback=?", {
					searchTerms: options.searchTerm,
					wskey: api_key,
					qf: "TYPE:IMAGE",
					startPage: options.page
				}, function(data){
					totalpages = Math.ceil(data.totalResults / data.itemsPerPage) - 1
					$.each(data.items, function(i){
						$.getJSON(data.items[i].link+"&callback=?", function(item){
							newimg = new Image()
							newimg.src = "http://social.apps.lv/image.php?w=200&zc=2&src="+encodeURIComponent(item['europeana:object'])
							newimg.onload = function(){
								$("#tiles").append("<li><img data-url='"+item['europeana:uri']+"' src='http://social.apps.lv/image.php?w=200&zc=3&src="+encodeURIComponent(item['europeana:object'])+"' /></li>")
								if(handler) handler.wookmarkClear();
								handler = $('#tiles li');
								handler.wookmark(options);
							}
						})
					})
				}
			)
			current_page++
		}
		$(function(){
			/*
			searchTerm = decodeURIComponent(document.location.hash.replace("#!/search/",""))
			*/
			if(searchTerm != ""){
				$("#q").val(searchTerm)
				load_images({
					searchTerm: searchTerm,
					page: current_page
				})
				load_images({
					searchTerm: searchTerm,
					page: current_page
				})
				load_images({
					searchTerm: searchTerm,
					page: current_page
				})
			}
			$("#tiles").on("click", "img", function(){
				alert($(this).data("url"))
			})
		})
	</script>

	<!-- Once the page is loaded, initalize the plug-in. -->
	<script type="text/javascript">
		var handler = null;

		// Prepare layout options.
		var options = {
			autoResize: true, // This will auto-update the layout when the browser window is resized.
			container: $('#main'), // Optional, used for some extra CSS styling
			offset: 4, // Optional, the distance between grid items
			itemWidth: 210 // Optional, the width of a grid item
		};

		/**
		 * When scrolled all the way to the bottom, add more tiles.
		 */
		function onScroll(event) {
			// Check if we're within 100 pixels of the bottom edge of the broser window.
			var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 100);
			if(closeToBottom) {
				// Get the first then items from the grid, clone them, and add them to the bottom of the grid.
				load_images({
			searchTerm: searchTerm,
			page: current_page
		})

				// Clear our previous layout handler.
				if(handler) handler.wookmarkClear();

				// Create a new layout handler.
				handler = $('#tiles li');
				handler.wookmark(options);
			}
		};

		$(document).ready(new function() {
			// Capture scroll event.
			$(document).bind('scroll', onScroll);

			// Call the layout function.
			handler = $('#tiles li');
			handler.wookmark(options);
		});
	</script>

</body>
</html>