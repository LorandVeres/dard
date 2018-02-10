//$('#edit1').on('click', function(){ $('#ajax').empty() ;});
$('#search').css({color : '#6f6f6f'});
//console.log(bb);


dialog($('#search')[0], {
	title : 'Page id search box',
	body : $('#box')[0],
	onside : true,
	drag : true,
	css : ['dard_d_box_two', 'dard_d_hd', 'dard_d_bt', 'dard_d_bt', 'dard_d_bt', 'dard_d_bd_two', 'dard_d_f'],
	//transform : ['dialog-transition', 'dialog_h_t', 'dialog_bd_t', 'dialog_f_t']
});

/*
 * This is just an example with included module
 *
 */
( function() {
		var done = function() {
			ajax({
				xtype : 'GET',
				response : function(s) {
					include_module({
						el : $('#ajax').me(),
						html : s,
						//js : 'share/js/load.js'
					});
				},
				url : 'pages/add-page',
				error : 'something went wrong geting your page add-page'
			});
			$('#addpages').on('click', function(e) {
				e.preventDefault();
			});
			$('#addpages').on('click', done);
		};
	}());
/*
 *
 *
 */
function searchPageId(val) {
	var live = $('#livesearch'),
		links = $('#searchlinks');
	
	ajax({
		xtype : 'GET',
		url : 'search?search=' + val,
		response : function(s) {
			var el = str2el(s);
			links.empty();
			if (el) {
				links[0].appendChild(el);
			}
			if(live){
				live.on('focusout', function() {
					setTimeout(function() {
					links.empty();
					}, 100);
				});
			}
		}
	});
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
			var e = $("#menu")[0],
				c = $("#main")[0];
			toggle(e);
			if (e.style.display === 'none') {
				c.style.marginLeft = 0;
			} else {
				if (e.style.display === 'block')
					if (screen.width < 800) {
						c.style.marginLeft = 0;
					} else {
						c.style.marginLeft = 240 + 'px';
					}
			}
		};
		
		var icon = $('#menuicon');
		icon.on('click', function(e) {
			e.preventDefault();
		});
		icon.on('click', showHideMenu);
		// mousedown one event what is detected on mobile devices
		hideMenuList = function(el) {
			var e = el.parentElement.childNodes;
			toggle(e[3]);
		};
	}());

