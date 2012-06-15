(function(window,undefined){
	var History = window.History,
		$ = window.jQuery,
		document = window.document;
	if(!History.enabled){
		return false
	}
})(window)
var totalpages		= 0,
	current_page	= 1,
	doscrollevent	= true,
	imagesLoaded	= 0,
	objectsLoaded	= 0,
	imagesToLoad	= 0,
	objectsTotal		= 0,
	handler				= null
function fancing(){
	$(".imagepopup").fancybox({
		'width'				: 800,
		'height'				: 500,
		'padding'			: 0,
		'centerOnScroll': true,
		'transitionIn'		: 'elastic',
		'transitionOut'	: 'elastic',
		'easingIn'			: 'easeOutBack',
		'easingOut'		: 'easeInBack',
		'onClosed'			: function(){
			history.pushState(null, null, "/search?q="+escape(searchTerm))
		}
	})
}
function wookmarking(){
	$('#tiles li').wookmark({
		autoResize: true,
		container: $('#main'),
		offset: 4
	})
	fancing()
}
function load_images(){
	$("#loading_status").fadeIn("slow")
	doscrollevent = false
	$.getJSON("/request.php?callback=?", {
		searchTerms: searchTerm,
		qf: "TYPE:IMAGE",
		startPage: current_page
	}, function(data){
		objectsTotal = data.totalResults
		totalpages = Math.ceil(data.totalResults / data.itemsPerPage) - 1
		$("#count").html(data.totalResults)
		$.each(data.items, function(i){
			$.getJSON("/request_object.php?uri="+encodeURIComponent(data.items[i].link), function(item){
				objectsLoaded++
				if(item['europeana:object'] != undefined){
					newimg = new Image()
					newimg.src = "http://openshift.apps.lv/image.php?w=150&zc=2&src="+encodeURIComponent(item['europeana:object'])
					newimg.onload = function(){
						imagesLoaded++
						var subjects = []
						if(typeof(item['dc:subject']) == "object"){
							$.each(item['dc:subject'], function(i){
								subjects.push("<a href='/search?q="+encodeURIComponent(item['dc:subject'][i].replace("'","%27"))+"'>"+item['dc:subject'][i]+"</a>")
							})
						} else if(typeof(item['dc:subject']) == "string"){
							subjects.push("<a href='/search?q="+encodeURIComponent(item['dc:subject'].replace("'","%27"))+"'>"+item['dc:subject']+"</a>")
						}
						imagesLoaded++
						$("#tiles").append(
							"<li><a class='imagepopup' href='#popup'><img width='"+this.width+
							"' height='"+this.height+
							"' data-subjects=\""+encodeURIComponent(subjects.join(", "))+
							"\" data-description='"+encodeURIComponent(item['dc:description'])+
							"' data-originaluri='"+escape(item['europeana:uri'])+
							"' data-provider='"+escape(item['europeana:provider'])+
							"' data-country='"+escape(item['europeana:country'])+
							"' data-creator='"+escape(item['dc:creator'])+
							"' data-imgsrc='"+escape(item['europeana:object'].replace(/\s/g,"%20"))+
							"' data-title='"+escape(item['dc:title'])+
							"' src='http://openshift.apps.lv/image.php?w=150&zc=3&src="+encodeURIComponent(item['europeana:object'].replace(/\s/g,"%20").replace("'","%27"))+
							"' /></a></li>")
						if(handler) handler.wookmarkClear()
						wookmarking()
						$("img").error(function(){
							console.log($(this))
							$(this).remove()
						})
					}
				}
			})
		})
		doscrollevent = true
		$("#loading_status").fadeOut("slow")
	})
	current_page++
}
$(function(){
	$(document).bind('scroll', onScroll)
	if(searchTerm != ""){
		$("#q").val(searchTerm)
		load_images()
	}
	var initialloads = 0
	$('#main').ajaxComplete(function(){
		initialloads++
		if(initialloads == 12){
			if(objectsTotal > 0 && $("#main").height() < $(window).height() && objectsLoaded < objectsTotal){
				load_images()
			}
			initialloads = 0
		}
	})
	$("#tiles").on("click", ".imagepopup", function(){
		history.pushState(null, null, "/item/"+unescape($(this).children("img").data("originaluri")).replace("http://www.europeana.eu/resolve/record/","")+"?q="+escape(searchTerm))
		$("#popup_img").css('background-image', 'url(http://openshift.apps.lv/image.php?donotscale=1&cc=333&w=470&h=470&zc=2&src='+encodeURIComponent(unescape($(this).children("img").data("imgsrc")))+')')
		if($(this).children("img").data("title") != undefined){
			$("#popup_img_title").html(unescape($(this).children("img").data("title")))
			$("#datacountry").html(unescape($(this).children("img").data("country").capitalize()))
			$("#dataprovider").html(unescape($(this).children("img").data("provider")))
			if($(this).children("img").data("creator") != "undefined"){
				$("#datacreator").prev("lh").show()
				$("#datacreator").html(unescape($(this).children("img").data("creator")))
			} else {
				$("#datacreator").prev("lh").hide()
			}
			$("#dataoriginaluri").html('<a target="_blank" href="'+unescape($(this).children("img").data("originaluri"))+'">view this item at Europeana</a>')
			if($(this).children("img").data("subjects").length){
				$("#datasubjects").prev("lh").show()
				$("#datasubjects").html(decodeURIComponent($(this).children("img").data("subjects")))
			} else {
				$("#datasubjects").prev("lh").hide()
			}
			if($(this).children("img").data("description") != "undefined"){
				$("#datadescription").html(decodeURIComponent($(this).children("img").data("description")))
			} else {
				$("#datadescription").html("")
			}
			$("#pinbutton").html(
				'<a href="http://pinterest.com/pin/create/button/?url='+encodeURIComponent(document.location.href)+'&media='+encodeURIComponent(unescape($(this).children("img").data("imgsrc")))+'&description='+encodeURIComponent(unescape($(this).children("img").data("title")))+'" class="pin-it-button" count-layout="vertical"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a><script type="text/javascript" src="http://assets.pinterest.com/js/pinit.js"><'+'/script>'+
				'<iframe src="//www.facebook.com/plugins/like.php?href='+encodeURIComponent(document.location.href)+'&amp;send=false&amp;layout=box_count&amp;width=55&amp;show_faces=false&amp;action=like&amp;colorscheme=light&amp;font&amp;height=60&amp;appId=389315061119000" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:55px; height:60px; margin-left: 6px;" allowTransparency="true"></iframe>'
			);
		} else {
			$("#popup_img_title").html("")
		}
		fancing()
		return false
	})
})
String.prototype.capitalize = function(){
	str = this
	var doneStr = '',
		len = str.length,
		wordIdx = 0,
		char
	for (var i = 0;i < len;i++){
		char = str.substring(i,i + 1)
		if(' -/#$&.()'.indexOf(char) > -1){
			wordIdx = -1
		}
		if(wordIdx == 0){
			char = char.toUpperCase()
		} else if(wordIdx > 0){
			char = char.toLowerCase()
		}
		doneStr += char
		wordIdx++
	}
	return doneStr
}
function onScroll(event){
	if(doscrollevent){
		var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 1)
		if(closeToBottom){
			load_images()
			if(handler){
				handler.wookmarkClear()
			}
			wookmarking()
		}
	}
}