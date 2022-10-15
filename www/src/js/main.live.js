
function addpageid(e, doit){
	var page, mod, href,
		path = window.location.pathname;
		path.indexOf("?") == -1 ?
			href = path.slice(path.indexOf("/"))+"?a="+ doit :
			href = path.slice(path.indexOf("/"), path.lastIndexOf("?")) + "?a=" + doit;
	e.removeAttribute('href');
	$('#pageid').ihtml(function (s){ page = s; });
	mod = $('#moduleid')[0].innerHTML;
	if(!empty(page) && !empty(mod)){
		e.setAttribute('href', href+'&pageid='+page+'&moduleid='+mod);
	}else{
		e.setAttribute('href', '#');
		alert('Please select one page in the search !');
		return ;
	}
}

function addmoduleid(e, doit){
	var mod, href,
		path = window.location.pathname;
		path.indexOf("?") == -1 ?
			href = path.slice(path.indexOf("/"))+"?a="+ doit :
			href = path.slice(path.indexOf("/"), path.lastIndexOf("?"))+"?a="+ doit;
	e.removeAttribute('href');
	mod = $('#moduleid')[0].innerHTML;
	if(!empty(mod)){
		e.setAttribute('href', href+'&moduleid='+mod);
	}else{
		e.setAttribute('href', '#');
		alert('Please select one page in the search !');
		return ;
	}
}
(function() {
		function getx(w) {
			var m,
			    c,
			    x;
			x = window.screen.width;

			if (x > 1000) {
				m = '18%';
				c = '82%';
			}
			if (x < 1001 && x > 700) {
				m = '25%';
				c = '75%';
			}
			if (x < 701 && x > 480) {
				m = '35%';
				c = '65%';
			}
			return [m, c, x];
		}

		function rotate(v){
			var val = v + 'deg';
			$('.sub-menu-h-i').css({
				transform: 'rotate('+ val +')',
				'-webkit-transform': 'rotate('+ val +')',
				'-ms-transform': 'rotate('+ val +')'
			});
		}

		if ($('.sub-menu')) {
			var el = $('.sub-menu'),
				elH = $('#content').scrollHeight.toString() +'px';
			setInterval(function(el) {
				var w;//el = $('.sub-menu'),
				   // w;
				if (window.screen.width < 481 && el.style.height !== 'auto'){
					el.css({
						height : '0px'
					});
					$('.content').css({width : '100%'});
					rotate(90);
				}
				if (window.screen.width > 480 && (el.style.height !== ('100%' || elH))) {
					w = el.style.width;
					el.css({
						height : elH,
						width : w
					});
					if(w == '') el.style.width = '0%';
					if(w == '0%' || w == '') rotate(0);
					w == '0%' || w == '' ? $('.content').css({width : '100%'}) : $('.content').css({width : getx()[1]});
				}
			}, 300, el, elH);
			$('.sub-menu-h-i').onclick = function() {
				var d = getx(),
				    m = d[0],
				    c = d[1],
				    kit = ('-ms-' || '-mos-' || '-webkit-' || ''),
				    transition = kit + 'transition';
				if (d[2] > 480) {
					if($('.sub-menu').style.width == '0%'){
						rotate(+180);
						$('.sub-menu').css({
							transition : 'width .3s ease-in-out',
							width : m
						});
						$('.content').css({
							transition : 'width .3s ease-in-out',
							width : c
						});
						$('.sub-menu-b').toggle();
					}else{
						rotate(0);
						$('.sub-menu').css({
							transition : 'width .3s ease-in-out',
							width : '0%'
						});
						$('.content').css({
							transition : 'width .3s ease-in-out',
							width : '100%'
						});
						setTimeout(function(){$('.sub-menu-b').toggle();}, 300);
					};
				} else if (d[2] < 481) {
					if($('.sub-menu').style.height == '0px'){
						rotate(270);
						$('.sub-menu').css({
							transition : 'height .9s ease-in-out',
							height : 'auto'
						});
						$('.sub-menu-b').toggle();
					}else{
						rotate(90);
						$('.sub-menu').css({
							transition : 'height .9s ease-in-out',
							height : '0px',
							overflow : 'hidden'
						});
						$('.sub-menu-b').toggle();
					};
				}

			};
		}
	}());

dialog($('#search'), {
	title : 'Page id search box',
	body : $('#box'),
	onside : true,
	drag : true,
	css : ['dard_d_box_two', 'dard_d_hd', 'dard_d_bt', 'dard_d_bt', 'dard_d_bt', 'dard_d_bd_two', 'dard_d_f'],
	//transform : ['dialog-transition', 'dialog_h_t', 'dialog_bd_t', 'dialog_f_t']
});

/*
 * This is just an example with included module
 *
 *
 ( function() {
 var done = function() {
 ajax({
 type : 'GET',
 response : function(s) {
 include_module({
 el : $('#ajax')[0],
 html : s,
 //js : 'share/js/load.js'
 });
 },
 url : 'pages/add-page',
 error : 'something went wrong geting your page add-page'
 });
 $('#addpages').on('click', function(e) {
 e.preventDefault();
 }).on('click', done);
 };
 }());
 /*
 *
 *
 */
function searchPageId(val) {
	let links = $('#searchlinks');

	function log() {
		let page,
		    mod;
		if (this.hasAttribute('data-pageid'))
			$('#pageid').ihtml(this.getAttribute('data-pageid'));
		if (this.hasAttribute('data-moduleid'))
			$('#moduleid').ihtml(this.getAttribute('data-moduleid'));
		if (this.hasAttribute('data-pagename')){
			$('.pagename').ihtml(this.getAttribute('data-pagename'));
			$('.pagename', 1).ihtml(this.getAttribute('data-pagename'));
		}
		$('#searchbox').val('');
		$('.modulename').ihtml('');
		$('.wrap', 0).walk_Child('table', function(el){
			el.parentNode.removeChild(el);
		});
		$('.wrap', 0).walk_Child('div', function(el){
			el.parentNode.removeChild(el);
		});
	}

	if (!empty(val)) {
		$.ajax({
			type : 'GET',
			url : 'search?search=' + val,
			response : function(s) {
				var el = str2el(s);
				links.empty();
				links.css({
					display : 'block'
				});
				if (el) {
					if (links.append(el)) {
						var c = links.childNodes[0].childNodes;
						for (var i = 0,
						    j = c.length; i < j; i++) {
							if (c[i].nodeName.toLowerCase() == 'p') {
								if (c[i].firstChild.nodeName.toLowerCase() == 'a') {
									c[i].firstChild.onmousedown = log;
								}
							}

						}
					}
				}
				if (links) {
					$('.livesearch').on('focusout', function() {
						links.empty().css({
							display : 'none'
						});
					});
				}
			}
		});
	}else if(empty(val)){
		links.empty().css({display : 'none'});
	}
}

/*
 *
 *
 *
 **
 *
 */

var toggleMenu = ( function() {
		showHideMenu = function() {
			var e = $("#menu"),
			    c = $("#main"),
			    om = $("#menuicon"),
			    mes = $("#menu-body");
			if (e.style.width === 0 | e.clientWidth === 0) {
				e.style.width = '260px';
				c.style.marginLeft = '260px';
				om.style.display = 'none';
					if (screen.width <= 768) {
						c.style.marginLeft = 0;
					} else {
						c.style.marginLeft = '260px';
					}
				setTimeout(function(){mes.style.visibility = 'visible';}, 150);
			} else {
				e.style.width = 0;;
				c.style.marginLeft = 0;
				//om.style.display = 'block';
				setTimeout(function(){om.style.display = 'block';}, 250);
				setTimeout(function(){mes.style.visibility = 'hidden';}, 150);
			}
		};

		var icon = $("#menuicon"),
			menu = $(".close-menu", 0);
		menu.on('click', function(e) {
			e.preventDefault();
			showHideMenu();
		});
		icon.on('click', function(e) {
			e.preventDefault();
			showHideMenu();
		});//.on('click', showHideMenu);
		// mousedown one event what is detected on mobile devices
		hideMenuList = function(el) {
			var e = el.parentElement.childNodes;
			if(e[3].style.maxHeight){
				e[3].style.maxHeight = null;
				//e[3].style.maxHeight = e[3].scrollHeight + "px";
			}else{
				e[3].style.maxHeight = e[3].scrollHeight + "px";
			}
		};
	}());

//
//
function toggle_login_menu(){
	var panel = $('.user-account-logins-panel', 0),
		logins = $('.user-account-logins', 0);
	if(panel.style.display === 'none' | panel.style.display === ""){
		logins.style.backgroundColor ='#b55400';
		logins.style.borderBottom = '1px solid #914501';
		panel.style.display = 'block';
	}else{
		logins.style.backgroundColor ='#bd5800';
		logins.style.borderBottom = 'none';
		panel.style.display = 'none';
	}
	return false;
}
