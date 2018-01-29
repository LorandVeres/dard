myEvent('click', '#edit1', function(){ empty('#ajax') ;});


 
 ( function() {
        var done = function() {
            ajax({
                type : 'GET',
                response : function(s) {
                    include_module({
                        El : '#ajax',
                        html : s,
                        //js : 'share/js/load.js'
                    });
                },
                url : 'pages/add-page',
                error : 'something went wrong geting your page add-page'
            });
        };
        myEvent('click', '#addpages', function(e){
            e.preventDefault();
        });
        myEvent('click', '#addpages', done);
    }());


function liveSearch(val){
    ajax({
        type: 'GET',
        url: 'error-messages?search=' + val,
        response: function(s){
            var el = str2el(s);
            $('#livesearch').appendChild(el);
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
            var e = $("#menu");
            var c = $("#main");
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
        myEvent('click', '#menuicon', function(e) {
            e.preventDefault();
        });
        myEvent('click', '#menuicon', showHideMenu);
        // mousedown one event what is detected on mobile devices
        hideMenuList = function(el) {
            var e = el.parentElement.childNodes;
            toggle(e[3]);
        };
    }());
