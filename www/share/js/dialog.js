var dialog = {

    mywindow : function() {

        var d = document.createElement('div'),
            h = document.createElement('div'),
            b = document.createElement('div'),
            bd = document.createElement('div'),
            f = document.createElement('div'),
            uniq = new Date().getTime(),
            id = 'dard_d_bd' + uniq;

        function create() {

            d.setAttribute('id', id);
            h.setAttribute('id', 'dard_d_hd');
            b.setAttribute('id', 'dard_d_cl');
            b.appendChild(document.createTextNode('X'));
            h.appendChild(b);
            d.appendChild(h);
            d.appendChild(bd);
            d.appendChild(f);
            
            document.body.appendChild(d);
        };
        return (function() {
                create();
                return [d, h, b, bd, f];
            }());

    },
    
    setClass : function(elements, obj){
        if(MyObj.keyIn(obj, 'css')){
            for(var i = 0, j = elements.length; i < j; i ++ ){
                elements[i].className += ' '+ obj.css[i];
            }
        }
    },
    
    appendBody : function (body, obj){
        if(MyObj.keyIn(obj, 'html')){
            var el = str2el(obj.html);
            body.appendChild(el);
        }
    },
    
    appendName : function (head, obj){
        if(MyObj.keyIn(obj, 'name')){
            var n = document.createElement('h4');
            n.appendChild(document.createTextNode(obj.name));
            head.appendChild(n);
        }
    },
    
    clearBody : function(el){
        empty(el);
    },
    
    myEvents : function (obj){
        if(MyObj.keyIn(obj, 'acton')){
            for(var i=0,j=obj.acton.myevent.length; i<j; i++){
              $(obj.acton.triger[i]).addEventListener(obj.acton.myevent[i], obj.acton.handler[i], false);
            };
        }
    },
    
    init : function(triger, obj) {
        triger.addEventListener('click', function() {
            var el = dialog.mywindow() ,
                d = el[0],
                h = el[1],
                b = el[2],
                bd = el[3],
                f = el[4],
                x,
                y;
                
            dialog.setClass(el, obj);
            dialog.appendName(h, obj);
            dialog.appendBody(bd, obj);
                                    
            h.addEventListener('mousedown', mouseDown, false);
            f.addEventListener('mousedown', mouseDown, false);
            window.addEventListener('mouseup', mouseUp, false);
            b.addEventListener('click', function() {
                document.body.removeChild(d);
            });

            function getStyle(e, s) {
                return window.getComputedStyle(e, null).getPropertyValue(s);
            }

            function mouseDown(e) {
                window.addEventListener('mousemove', divMove, true);
                y = e.clientY - getStyle(d, 'top').slice(0, -2),
                x = e.clientX - getStyle(d, 'left').slice(0, -2);
            }

            function mouseUp() {
                window.removeEventListener('mousemove', divMove, true);
            }

            function divMove(e) {
                //d.style.position = 'absolute';
                d.style.top = (e.clientY - y) + 'px';
                d.style.left = (e.clientX - x) + 'px';
            }
            dialog.myEvents(obj);
        });
        
    }
};

dialog.init('#dialog',
    {
        class : ['dard_d_db', 'dard_d_hb', 'dard_d_bt', 'dard_d_body', 'dard_d_f']
    }
);

