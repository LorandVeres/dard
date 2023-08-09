/**
 * @author Lorand Veres user.email "lorand.mast@gmail.com"
 *
 *
 */

/*******************************************************************************
*===============================================================================
*
* Basic comparison
* and other useful functions
*
*===============================================================================
********************************************************************************
*/
function isObj(o) {
	return typeof o === "object" || toString.call(o).split(/\W/)[2].toLowerCase() === "object";
}

function isArray(a) {
	return Array.isArray(a) || toString.call(a).split(/\W/)[2].toLowerCase() === 'array';
}

function isFunc(f) {
	return typeof f === 'function';
}

function isStr(s) {
	return typeof s === 'string' ? true : false;
}

function isBool(b) {
	return typeof b === 'boolean' ? true : false;
}

function isNum(n) {
	return typeof n === 'number' ? true : false;
}

function isSet(o) {
	return typeof o === 'undefined' || o === undefined ? false : true;
}
// Checks if a string is empty
function empty(v) {
	let r = false;
	if (isStr(v)) {
		v.length == 0 ? r = true : r = false;
		if (v.length == 1 && (v == '0' || v == "0" ))
			r = true;
	} else if (v === null) {
		r = true;
	} else if (isNum(v) && v == 0) {
		r = true;
	}
	return r;
}

function type(o, arguments) {
	o.constructor.prototype = new o(arguments);
	return o.constructor;
}

function varyArgs(arguments) {
	return Array.prototype.slice.call(arguments);
}

function argsLength(arguments) {
	return varyArgs(arguments).length;
}

// Removes the specified value from array
function arrayRemove(arr, val){
	for( var i = 0; i < arr.length; i++){ 
        if ( arr[i] === val) { 
            arr.splice(i, 1);
            i--;
        }
    
    }
}

// Bond together multiple arrays, return one with all items in it
// Or use array.concat(); However merge word ismore familiar with me
function arrayMerge(){
	let arr =[], a;
	for(i = 0; i < arguments.length; i++){
		a = arguments[i];
		isArray(a) && a.forEach( function(item, index){ arr.push(item)});
	}
	return arr;
}


/*******************************************************************************
*===============================================================================
*
* A few small handfull functions
*
*
*===============================================================================
********************************************************************************
*/

/*
* Set css properties provided as an object to an elemnt
*
*/
function setCss(el, css) {
	let k,
	    f = '';
	if (isObj(css)) {
		for (k in css) {
			if (css.hasOwnProperty(k))
				f += k + ':' + css[k] + ';';
		}
		el.style.cssText = f;
	}
}
/*
* Toggle the element provided
*
*/
function toggle(el) {
	let s = window.getComputedStyle(el, null).getPropertyValue("display");
	s === 'none' ? el.style.display = 'block' : el.style.display = 'none';
};

/*
* recomended usage for testing pourpuse only
*
*/
function simulateClick(onTarget) {
	let evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
	$(onTarget).dispatchEvent(evt);
}
/*
* str2el(html_string)
* Creates a valid DOM element from a html string
*/

function str2el(html) {
   let fakeEl = document.createElement('iframe');

   fakeEl.style.display = 'none';
   document.body.appendChild(fakeEl);
   fakeEl.contentDocument.open();
   fakeEl.contentDocument.write(html);
   fakeEl.contentDocument.close();

   let el = fakeEl.contentDocument.body.firstChild;
   document.body.removeChild(fakeEl);
   return el;
};
/*
*  Empty the element provided
*
*/
function emptyEl(el) {
   let e = $(el);
   while (e.firstChild) {
	   e.removeChild(e.firstChild);
   }
}
/*
* Counter function. The perfect example for a closure
*
*/
function counter() {
	let count = 0,
		change = function(val) {
		count += val;
	};

	return {
		show : function() {
			return count;
		},

		increment : function() {
			change(1);
		},

		reset : function() {
			count = 0;
		},

		decrement : function() {
			change(-1);
		},
		incrementBy : function(val) {
			change(val);
		}
	};
};

/*
 *
 *  Here we go, we include js files on runtime
 *  A benefit for modular aproach.
 *
 *  this function is used inside the include_module()
 *
 * 	this is an ajax request for the new js file to be loaded
 * 	Not the ES6 standard , ok for earlier versions
 *
 */

function require_js_module(src) {
	ajax({
		type : 'GET',
		url : src,
		response : function(script) {
			eval.apply(window, [script]);
		},
		error : "ERROR: script not loaded: " + src
	});
};

/*
 * @ param obj{
 * 		el :  // the recipient element to be pushed in the new html
 * 		html :  // html string or one dom element from ajax request
 * 		js :   // optional
 * }
 *
 */
function include_module(obj) {
	if (obj.keyIn('el')) {
		let el = obj.el;
		while (el.hasChildNodes()) {
			el.removeChild(el.firstChild);
		}
		isObj(obj.html) ? el.appendChild(obj.html) : isStr(obj.html) ? el.appendChild(str2el(obj.html)) : null;
		if (obj.keyIn('js'))
			require_js_module(obj.js);
	}
}

// Useful Object relating functions

function myObj() {
	let self = {};
	self.keyIn = function(k) {
		return this.hasOwnProperty(k) ? true : false;
	};
	self.isTypeOf = function(i) {
		if (i && !isSet(i)) {
			return 'undefined';
		} else if (i && isFunc(i) && isSet(i.prototype)) {
			return (this instanceof i) || this.constructor.name === 'Dard' ? true : false;
		} else if (!i) {
			return this.constructor.name.toLowerCase();
		}
	};
	self.size = function() {
		let c = 0,
		    k,
		    o = this;
		if ( typeof this == 'object') {
			for (k in o) {
				o.keyIn(k) ? c++ : null;
			}
			return c;
		}
	};
	return self;
}

//   Asigning myObj to Object prototype
Object.assign(Object.prototype, new myObj());

function camelCase(str) {
	// Lower cases the string
	return str.toLowerCase()
	// Replaces any - or _ characters with a space
	.replace(/[-_]+/g, ' ')
	// Removes any non alphanumeric characters
	.replace(/[^\w\s]/g, '')
	// Uppercases the first character in each group immediately following a space
	// (delimited by spaces)
	.replace(/ (.)/g, function($1) {
		return $1.toUpperCase();
	})
	// Removes spaces
	.replace(/ /g, '');
}

// Capitalize the first letter
function capitalize(s){
	if ( typeof s !== 'string') return '';
	return s.charAt(0).toUpperCase() + s.slice(1);
}

// Removes potential underscore and dash characters
// Return a string to lower case with spaces
function camel2String (arg) {
	if ( typeof arg !== 'string') return '';
	return arg.replace(/([A-Z0-9])/g, " $1").replace(/([_\-])/g, "").toLowerCase();
}

// Split words merged with underscore and dash characters and removes underscore
// Return the string with each word first letter uppercase
function stringToTitle (arg) {
	let newstr ='';
	if ( typeof arg !== 'string') return '';
	arg.replace(/([_])/g, " ").replace(/([\-])/g, " $1 ").trim().split(" ").forEach( (item)  =>  item !== "_"  && ( newstr += item.charAt(0).toUpperCase() + item.slice(1) + " " ) );
	return newstr.trim();
}

/*
********************************************************************************
*===============================================================================
*
* This is the MAIN selector function
*
*
*
*===============================================================================
********************************************************************************
*/



var $ = function () {
	let itemNo,
		el = this,
		args = varyArgs(arguments),
		arg ,
		toType = {},
		toString = toType.toString;
	const idRE = /^#{1}[a-z0-9\-\_]+\-*$/i, // id REgex
	    classNameRE = /^\.{1}[a-z0-9\-\_\s]+$/i, // class REgex
	    tagNameRE = /^<{1}[a-z0-9]+>{1}$/i, // html tag REgex
	    plainTagRE = /^[a-z1-6]+$/;

	args[0] ? arg = args[0] : console.warn('Dard warn : Dard function has no arguments ' + args[0]);
	isNum(args[1]) && !isFunc(args[1]) ? itemNo = args[1] : itemNo = 0;

	if ( typeof arg == 'string') {
		if (idRE.test(arg))
			el = document.getElementById(arg.substring(1));
		if (classNameRE.test(arg)){
			if (isFunc(args[1])){
				el = document.getElementsByClassName(arg.substring(1));
				args[1].call( el );
			} else {
				el = document.getElementsByClassName(arg.substring(1))[itemNo];
			}
		}
		if (tagNameRE.test(arg))
			el = document.createElement(arg.replace(/^<+|>+$/gm, ''));
			isSet(args[1]) && isStr(args[1]) ? el.textContent = args[1] : null ;
		if (plainTagRE.test(arg))
			if ( isFunc(args[1])) {
				el = document.getElementsByTagName(arg);
				args[1].call( el );
			} else {
				el = document.getElementsByTagName(arg)[itemNo];
			}
	} else if (isObj(arg)) {
		if(arg instanceof HTMLElement ) {
			el = arg;
		} else if( arg instanceof HTMLCollection) {
			console.warn("Dard warn : Dard parameter is a HTMLCollection and returned as HTMLCollection");
			return arg;
		}
	}

	if (el instanceof HTMLElement) {

		/**
		* On window.onload attaching an event to this element
		*
		*/

		el.constructor.prototype.on= function(ev, fn) {
			if (isFunc(fn)) {
				window.onload = this.addEventListener(ev, fn, false);
				return this;
			}
		};

		/**
		* Append one Html object or an HTML string to itself (element)
		*
		*/

		el.constructor.prototype.append = function(e) {
			if ( typeof e === 'object') {
				try {
					this.appendChild(e);
				} catch(err) {
					try {
						if (isStr(e))
							this.appendChild(str2el(e[0]));
					} catch(err2) {
						console.log(err + "\n" + err2);
					}
				}
			}
			return this;
		};

		/**
		*  Removes one child element specified as the parameter
		*
		*/

		el.constructor.prototype.rm = function(e) {
			if(e.parentElement === this )
				this.removeChild(e);
			return this;
		};

		/**
		*  Empty the element removing all its child elements
		*
		*/

		el.constructor.prototype.empty = function() {
			while (this.firstChild) {
					this.removeChild(this.firstChild);
			}
			return this;
		};

		/**
		*  It clones itself,
		*  if a callback is pased as parameter than the cloned element becomes
		*  that callback parameter oterwise it will return itself
		*
		*/

		el.constructor.prototype.clone = function(fn) {
			if (this && isFunc(fn)) {
				fn(this.cloneNode(true));
			}else{
				return this.cloneNode(true);
			}
			return this;
		};

		/**
		*  Adding attributes to itself, attribute and value or just an attribute
		*  Second parameter is optional
		*  .addattr(attr, value);
		*/

		el.constructor.prototype.addattr = function() {
			let arg = varyArgs(arguments);
			if (arguments.length === 2) {
				this.setAttribute(arg[0], arg[1]);
			}else if (arguments.length === 1) {
				this.setAttributeNode(document.createAttribute( arg[0] ) );
			}
			return this;
		};
		
		/**
		*  Add multiple attributes to itself, attribute and value or just an attribute
		*  @Param Obj{ class:'whole class list', id:'anyid', style:'margin: 0;width:80%'}
		*  .addattrlist( obj );
		*/
		
		el.constructor.prototype.addattrlist = function() {
			let attr={},
				attr_Obj = arguments[0];
			for (let prop in attr_Obj){
				if(attr_Obj.hasOwnProperty(prop)){
					attr = document.createAttribute(prop);
					if(!empty(attr_Obj[prop]))
						attr.value = attr_Obj[prop];
					this.setAttributeNode(attr);
				}
			}
			return this;
		};
		
		/**
		*  Toggle an attribute. If the attribute does not exit it will create one with the first param in list
		*  @Param attr // the attribute name
		*  @param item1 // first posible value 
		*  @param item2 // second posible value
		*  @Use .attrtoggle( 'href', 'good-page', 'bad-page' );
		*/
		
		el.constructor.prototype.attrtoggle = function(attr, item1, item2) {
			let att;
			this.hasAttribute(attr) && ( att = this.getAttribute(attr) ) && this.removeAttribute(attr);
			attr = document.createAttribute(attr);
			att === item1 ? attr.value = item2 : attr.value = item1;
			this.setAttributeNode(attr);
			return this;
		};

		/**
		*  Adds or change the value of an element, or if a callback is pased it
		*  will use this element value as parameter for the callbacks
		*
		*/

		el.constructor.prototype.val = function(v) {
			if (isStr(v)) {
				this.value = v;
			} else if (isFunc(v)) {
				v(this.value);
			}
			return this;
		};

		/**
		*  Toggle the element
		*
		*/

		el.constructor.prototype.toggle = function() {
			let s = window.getComputedStyle(this, null).getPropertyValue("display");
			s === 'none' ? this.style.display = 'block' : this.style.display = 'none';
			return this;
		};

		/**
		*  Adding a class to the element class list
		*
		*/

		el.constructor.prototype.addclass = function(newclass) {
			if (this && isStr(newclass) && !empty(newclass)) {
				this.classList.add(newclass);
			}
			return this;
		};

		/**
		*  Adding css to itself (element). It takes an object as parameter like
		*  {margin:"3%", padding:"3%", "font-size":"16px"}
		*/

		el.constructor.prototype.css = function(val) {
			let k,
			    f = '',
			    c;
			if (isObj(val) && this) {
				for (k in val) {
					if (val.hasOwnProperty(k)) {
						c = k.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
						f += c + ':' + val[k] + ';';
					}
				}
				this.style.cssText = f;
			}
			return this;
		};

		/**
		*  It sets innerHTML if string is passed as parameter
		*  It gets the innerHTML if no parameter is passed
		*  It gets the innerHTML and pased it to the callback function
		*/

		el.constructor.prototype.ihtml = function() {
			let t = varyArgs(arguments);
			if(t.length === 1){
				if (isStr(t[0])) {
					this.innerHTML = t;
				} else if (isFunc(t[0])) {
					let callback = t[0];
					callback(this.innerHTML);
				}
			}else if (t.length === 0) {
				return this.innerHTML;
			}

			return this;
		};

		/**
		*  It sets outerHTML if string is passed as parameter
		*  It gets the outerHTML if no parameter is passed
		*  It gets the outerHTML an pased it to the callback function if the
		*  parameter is a function
		*/

		el.constructor.prototype.ohtml = function() {
			let t = varyArgs(arguments);
			if(t.length === 1){
				if (isStr(t[0])) {
					this.outerHTML = t;
				} else if (isFunc(t[0])) {
					let callback = t[0];
					callback(this.outerHTML);
				}
			}else if (t.length === 0) {
				return this.outerHTML;
			}
			if(dom_object.e_content)
			return this;
		};

		/** UNTESTED YET
		*  It may have an unexpected behavior yet
		*****************************************
		*  It supouse to attach an event to the element. One time click it may
		*  be possible
		*/

		el.constructor.prototype.click = function() {
			let callback = arguments[0],
			    prevent = arguments[1],
			    once = arguments[2],
			    tip;
			function doclick() {
				let el = this;
				callback();
				if (prevent) {
					event.preventDefault("click");
				}
				if (once) {
					this.removeEventListener("click", doclick, tip);
				}
			}
			if (isSet(prevent) && prevent == true) {
				if (isSet(once) && once == true) {
					this[0].addEventListener("click", doclick, false);
					tip = false;
				} else if (!isSet(once) || once == false) {
					this[0].addEventListener("click", doclick, true);
					tip = true;
				}
			} else {
				if (isSet(once) && once == true) {
					this[0].addEventListener("click", doclick, false);
					tip = false;
				} else if (!isSet(once) || once == false) {
					this[0].addEventListener("click", doclick, true);
					tip = true;
				}
			}
			return this;
		};

		/** 
		*  Apply a callback function on every child element node
		*
		*
		* @param first is a callback functions, apply the array or object on childNodes
		* @param second an array or iterate object
		*/

		el.constructor.prototype.walkChild = function() {
			let arg = varyArgs(arguments),
				callback = arg[0],
				el = this.children;
				for (let i = 0, j = el.length; i < j; i++) {
					if (el[i] && isFunc(callback)) {
						if (arg.length === 1)
							callback.call(el[i]);
						if (arg.length === 2 && (isObj(arg[1] || isArray(arg[1])))) {
							callback.call(el[i], arg[1]);
						}
					}
				}
			return this;
		};
		
		/** stepup 
		 * 
		 * @param {*} num the number of steping upwards on the parents from nested elements
		 * @param {*} func [ optional ] function to apply on that parent element
		 *
		 * @returns this if function is provided, otherwise the parent.parent-nth element
		 *
		 * @Use $('#dsn-317').stepup(2).style.visibility = "hidden";
		 *      $('#dsn-317').stepup( 2, function(){ this.style.visibility = "visible" } );
		 */
		el.constructor.prototype.stepup = function(num) {
			let p = this.parentElement, func;
			for(let i = 1; i < num; i++) {
				p = p.parentElement;
			}
			if( isSet(arguments[1]) ) {
				func = arguments[1];
				(isNum(num) && isFunc(func) ) && func.call(p);
				return this;
			}else {
				return p;
			}
		}

		return el;
	}
	return el;
};





////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
//
// $ object prototype extended
//
//
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////





/** ajax
 *AJAX function with main funcionality on POST GET and JSON
 **************************************************
 *
 *
 * The ajax object
 *
 * @ajaxObj = {
 *
 *      @type : 'GET',  // type of request POST or GET
 *      @url : 'your/page/url', // the page url
 *      @response : 'function', //handle the response from server
 *      @send : null, // in GET request is optional or can be set to null
 *      @json : true, // optional required if you do not stringify before the object, otherwise can be set to false
 *      @error : 'custom error message' // optional to see for errors in consol log
 *
 * };
 *
 */

$.constructor.prototype.ajax = function(obj) {
	let getPostJson = function() {
		let xhr = new XMLHttpRequest();
		xhr.open(obj.type, obj.url);
		xhr.setRequestHeader("HTTP_X_REQUESTED_WITH", "dard_ajax");
		if (obj.type === 'POST' && !obj.keyIn('json')){
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
			param(obj.send);
		}
		if (obj.keyIn('json') ) {
			xhr.setRequestHeader("Content-Type", "application/json ; charset=UTF-8");
			if(obj.json === true)
				obj.send = JSON.stringify(obj.send);
		}
		if (obj.type === 'GET' && obj.keyIn('send')) {
			if (isObj(obj.send))
				obj.send = param(obj.send);
		}
		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4 && xhr.status === 200) {
				obj.response(xhr.responseText);
				//console.log(xhr.responseText);
			}
			if (xhr.readyState === 4 && xhr.status !== 200) {
				if (obj.keyIn('error')) {
					obj.error ? console.log(obj.error + xhr.status) : '';
				}
			}
		};
		obj.keyIn('send') ? xhr.send(obj.send) : xhr.send(null);
	};

	function param(object) {
		let encodedString = '';
		for (let prop in object) {
			if (object.hasOwnProperty(prop)) {
				if (encodedString.length > 0) {
					encodedString += '&';
				}
				encodedString += encodeURI(prop + '=' + object[prop]);
			}
		}
		return encodedString;
	}

	getPostJson();
};

/** send_json
 *  Send an object to server via ajax in json format
 **************************************************
 *  Give clarity in code about how we comunicate with the server
 *  Extra meta parameter compared to ajax function
 *  The parameters object
 * @obj = {
 *
 *      @data : $.snipetHandler.gett($('form')),  // A valid object or a function what will return any object
 *      @url : url: 'funnypage?parameter=value', // Mandatory parameter, the page url the data to be sent
 *      @meta : {
 *          other: 'any type of data', // [ optionals ] Optional parameters to help sorting on server side. Pameter names at your choice
 *          other1: 'any type of data' // [ optionals ] Optional parameters to help sorting on server side. Pameter names at your choice
 *      }
 *      @callback: function // [ optional ] Function to handle the response from server
 *  };
 *
 * @Use
 *  $.send_json({
 *      data: $.snipetHandler.gett($('form')), //any valid js object
 *      url:'modules?a=add_module',
 *      meta: { random:'any funny data you wish' },   [ optional ]
 *      callback: function(){ console.log(arguments[0]);}
 *      @log : [ optional ] for debuging use helpfull
 *  });
 */

$.constructor.prototype.send_json = function() {
	let obj = {}, sending = {};
	obj = arguments[0];
	!isSet(obj.meta) ? sending = { data:obj.data } : sending = { meta:obj.meta, data:obj.data };
	$.ajax({
		type : 'POST',
		url : obj.url,
		response : function(r) {
			let newr;
			if( ! (JSON.parse(JSON.stringify(r))) ){
				console.warn('Dard warn: Received invalid JSON data from: ' + obj.url);
			} else {
				newr = JSON.parse(r);
				obj.keyIn('log') && console.log(newr); // for debuging
				if(obj.keyIn('callback')){
					if(isFunc( obj.callback)){
						obj.callback(newr);
					}
				}
			}
		},
		json : true,
		send : sending,
		error : 'An error ocured sending data to  ' + obj.url + ' '
	});
}

/** get_json
 *  get an object to server via ajax in json format
 **************************************************
 *  Almost identical like send_json but no meta and data propety . we don't send any data
 *  A short hand for ajax and give clarity in code about how we comunicate with the server
 *
 * The parameters object
 * @obj = {
 *
 *      @url : url: 'funnypage?parameter=value', // Mandatory parameter, the page url the data to be sent
 *      @callback: function // [ optional ] Function to handle the response from server
        @log : [ optional ] for debuging use helpfull
 *  };
 *
 * @Use
 *  $.get_json({
 *      url:'modules?a=add_module',
 *      callback: function(){ console.log(arguments[0]);}
 *  });
 */
 
$.constructor.prototype.get_json = function() {
	let obj = {};
	obj = arguments[0];
	$.ajax({
		type : 'GET',
		url : obj.url,
		response : function(r) {
			let newobj;
			if( ! (JSON.parse(JSON.stringify(r))) ){
				console.warn('Dard warn: Received invalid JSON data from: ' + obj.url);
			} else {
				newobj = JSON.parse(r);
				obj.keyIn('log') && console.log(r); // for debuging
				if(obj.keyIn('callback')){
					if(isFunc( obj.callback)){
						obj.callback(newobj);
					}
				}
			}
		},
		error : 'Could not load anything via get_json' + obj.url + ' '
	});
}

/** SnipetHandler
*  Dynamic and asynchronous HTML snipet handling
*************************************************
*
*  snipetHandler object, with methods and properties to handle one own style
*  of JSON type HTML snipets to and from the server. Adding a convenient way of
*  handling, storing in a data base on the server, or in a js object on the client.
*
*/

$.constructor.prototype.snipetHandler = (function() {
	let self = {},
		snipet_JSON = {},
		snipets = {};

	/** $.snipetHandler.gett
	*  Creating an snipet object from an existing HTMLdom element from the document
	*
	*  @param el A DOM element
	*
	*  @return object
	*
	*  @Use $.snipetHandler.gett($('body').firstElementChild); for one HTMLElement and all its childElements
	*
	*       $.snipetHandler.gett($('body'), true); to get a return of array like object of elements from body
	*           return Object { 0:div, 1:div }
	*/

	self.gett = function(el)  {
		let block = {},
		    build_Block,
		    get_Attributes,
		    walk_Siblings,
		    stopBool = false,
		    childsWalk = arguments[1];

		get_Attributes = function(e) {
			let attr = {},
			    attr_Name;
			for (let i = 0; i < e.attributes.length ; i++) {
				attr_Name = e.attributes.item(i).name.toString();
				if (attr_Name.toLocaleString())
					attr[attr_Name.toLocaleString()] = e.attributes.item(i).value;
			}
			return attr;
		};

		walk_Siblings = function(element) {
			let i = 0,
				j = 0,
			    the_Child = element.childNodes,
			    siblings = {},
			    text;
			    do {
					if (the_Child[i].nodeType === 1) {
						siblings[j] = self.gett(the_Child[i]);
						j++;
					} else if (the_Child[i].nodeType === 3) {
						text = the_Child[i].nodeValue.replace(((/\n|\t/gi)), "");
						if (text.trim() != "") {
							siblings[j] = {};
							siblings[j].e_name = "#text";
							siblings[j].e_type = 3;
							siblings[j].e_content = text;
							j++;
						}
					}
					i++;
				} while(i < the_Child.length);
			return siblings;
		};

		build_Block = function(e) {
			let inner_Block = {},
			    the_First_Child;
			(e.firstElementChild != (null || undefined)) ? the_First_Child = e.firstElementChild : the_First_Child = null;
			inner_Block.e_name = e.nodeName;
			inner_Block.e_type = e.nodeType;
			inner_Block.e_type === 1 ? inner_Block.e_attr = get_Attributes(e) : null;
			the_First_Child ? inner_Block.e_content = walk_Siblings(e) : (inner_Block.e_type === 1 ? inner_Block.e_content = e.innerHTML.replace((/\n|\t/gi), "") : null);

			return inner_Block;
		};

		if( isSet(el)){
			if( !isSet(arguments[1]) ){
				if (el.nodeType === 1 && !stopBool)  {
					block = build_Block(el);
				}
			}else if( isSet(childsWalk && !stopBool) ) {
				let nextEl = el.firstElementChild, j = 0;
				while(nextEl){
					block[j] = build_Block(nextEl);
					j++;
					nextEl = nextEl.nextElementSibling;
				}
				stopBool = true;
			}
		}

		return block;
	}

	/** $.snipetHandler.sett
	*  Insert an HTML node in the document from a snipets object or JSON snipet string
	*   @Need to be implemented a js event hook as for the text 
	*  @param snipet {
	*           obj: {},    //  snipet object
	*           recipient: element, // The recipient or adjacent element [ HTMLElement object ]
	*           position: 'afterend' // [ optional ] one of ( afterbegin, beforeend, beforebegin, afterend )
	*           },
	*  @param contentArray // [ optional ] The array for the content. Handy for large snipets, with miss match text to be included
	*  @param contentText // [ optional ] The text for the content. Handy in loops, with a liniar array
	*
	*  @Use
	*  snipetHandler.sett.call(
	*       snipet {
	*           obj: {},
	*           recipient: element,
	*           position: 'afterend'
	*           },
	*       contentArray 
	*       };
	*   );
	*  
	*
	*  @return element { The newly created HTMLElement object }
	*/

	self.sett = function() {
		let set_Attributes, // Function
			set_TextNode, // Function
			walk_Content, // Function
			set_Element, // Function
			insert_Element, // Function
			arg = varyArgs(arguments), // array of arguments
			dom_object = this.obj, // DOM object pased to the function
			recipient_Element = this.recipient, // Recipient or reference element
			el_Position, // The element position when adjacent element is used
			contentArray = [], // Array elements to be inserted in the obj.e_content based on data-dsn-id attribute value 
			contentText = '', // String to be inserted in the obj.e_content based on data-dsn-id attribute value. Good for small snipets in loop 
			inserted = false, // Once adjacent element is set it will became true, used as a switch variable
			arrayElements = {},
			oneElement,
			j = 0,
			stopBool = false;
			isSet( this.position ) && ( el_Position = this.position ) ;
			arg.length > 0 && ( isArray(arg[0]) && ( contentArray = arg[0] ) );
			arg.length > 0 && ( isStr(arg[0]) && ( contentText = arg[0] ) );
			
		const e_attr = 'e_attr',
			e_content = 'e_content';

		set_Attributes = function(attr_Obj, el){
			let attr={};
			for (let prop in attr_Obj){
				if(attr_Obj.hasOwnProperty(prop)){
					if(!empty(attr_Obj[prop]) && prop !== 'data-dsn-txt-id' ){
						attr = document.createAttribute(prop);
						if(!empty(attr_Obj[prop])){
							attr.value = attr_Obj[prop];
							el.setAttributeNode(attr);
						}else{
							el.createAttribute(prop);
						}
					}
				}
			}
		};

		walk_Content = function(content_obj, parent_El){
			if(content_obj.keyIn(e_content)){
				for (let prop in content_obj.e_content){
					set_Element(content_obj.e_content[prop], parent_El);
				};
			}
			if (!content_obj.keyIn(e_content)){
				for (let prop in content_obj){
					content_obj[prop].keyIn('e_type') && set_Element(content_obj[prop], parent_El);
				}
			}
		};

		insert_Element = function(el, parent_El){
			if(isSet(el_Position) && !inserted){
				if(parent_El.insertAdjacentElement(el_Position, el)){
					inserted = true;
				}
			}else if(!isSet(el_Position) || inserted ){
				parent_El.appendChild(el);
			}
		};

		set_TextNode = function(txt_Obj, parent){
			const text = document.createTextNode(txt_Obj);
			parent.appendChild(text);
		};

		set_Element = function(obj, parent_El){
			if ( !isSet(obj) || !isSet(parent_El) ) {
				! isSet(obj) && console.warn( 'Dard warn: snipetHandler.sett first parameter is not an object');
				! isSet(parent_El) && console.warn( 'Dard warn: snipetHandler.sett second (or recipient) parameter is not an object');
				return;
			} 
			if(isSet(obj.e_type) && (obj.e_type === 1 || obj.e_type === 3)){
				if(obj.e_type === 1){
					const element = document.createElement(obj.e_name);
					!isSet(parent_El) ? console.log(element) : insert_Element(element, parent_El);
					if(obj.keyIn(e_attr))
						set_Attributes(obj.e_attr, element);
					if(obj.keyIn(e_content)){
						if(isStr(obj.e_content)){
							if( obj.e_content === "" || ( obj.e_attr.keyIn('data-dsn-txt-id') || obj.e_attr.keyIn('data-dsntext') ) ) {
								isSet( contentArray ) && set_TextNode( contentArray [ obj.e_attr['data-dsn-txt-id']] , element );
								isSet( contentText ) && set_TextNode( contentText , element ); // simple for small snipets in loop
							}
								//set_TextNode( contentArray [ obj.e_attr['data-dsn-txt-id']] , element );
							if(obj.e_content !== "")
								set_TextNode(obj.e_content, element);
						}
						if(isObj(obj.e_content) && !isStr(obj.e_content)){
							walk_Content(obj.e_content, element);
						}
					}return element
				// Not a practical mode to include a one single text node.
				}else if(obj.e_type === 3 || isStr(obj.e_content)){
					set_TextNode(obj.e_content, parent_El);
				}
			}else {
				// Dealing with Array like object 
				for( let prop in obj){
					if(isObj(obj[prop]) && obj[prop].hasOwnProperty('e_type')){
						arrayElements[j] = set_Element(obj[prop], parent_El);
						j++;
						stopBool = true;
					}
					
				}
			}
			
		};
		oneElement = set_Element(dom_object, recipient_Element);
		if(oneElement === undefined ){
			return arrayElements;
		}else{
			return oneElement
		}
	};

	/** $.snipetHandler.get_http
	*  Get via ajax a snipet from server, and store it or insert in a HTMLElement
	*
	*  @param object {
	*       @url : Relative url including parameters too
	*       @el : [ optional ] The parent element for this snipet 
	*       @pos : [ optional ] String as position param for insertAdjacentElement, one of ( afterbegin, beforeend, beforebegin, afterend )
	*       @
	*   }
	*  @Use
	*   $.snipetHandler.get_http({ 
	*       url: 'modules?a=add_module',
	*       el: $.overlay(),
	*       pos: 'afterend'
	*   });
	*   To store it 
	*  $.snipetHandler.get_http({ 
	*       url: 'modules?a=add_module'
	*       name: snipet_name
	*       fn: functionName  // this will handle the storage new snipet
	*       log: true  // [ optional ] useful for debuging, will log to console the server response
	*   })
	*
	*  @return void
	*/
	
	self.get_http = function () {
		let obj = {}, parent;
		obj = arguments[0];
		$.ajax({
			type : 'GET',
			url : obj.url,
			response : function(r) {
				let snipet = {};
				isSet(obj.log) && console.log(r);
				if( ! (JSON.parse(JSON.stringify(r))) ){
					console.warn('Dard warn: Received invalid JSON data from: ' + obj.url);
				} else {
					snipet = JSON.parse(r);
					if (obj.keyIn('el')){
						obj.keyIn('pos')  ? self.sett.call( { 'obj':snipet , recipient:obj.el, position:obj.pos}) : self.sett.call({ 'obj':snipet , recipient:obj.el} );
					}
					// All good and works, tested. Store the snipet
					// declaring the external storing should be as simple as: function(name, snipet){ desiredObject[name] = snipet }
					if(obj.keyIn('name') && isFunc(obj.fn)){
						obj.fn(obj.name, snipet.form);
					}
				}
			},
			json : false,
			send : null,
			error : "Could not get the snipet from " + obj.url + " "
		});
		
	};

	return self;
}());

/** Element overlay
*  Creates a modal or partial overlay over the given element 
*************************************************
* Parameters
* @obj {
*
*       @el : The parent element for the overlay
*       @elc : [ optional ]  The class aplied to the overlay main element
*       @elb : [ optional ] The class aplied for the button styling
*       @nobtn : [ optional ] no button diplayed
* }
*
* @return The overlay HTMLElement
*
* @Use $.overlay({el:$("body")); // For a full body overlay. styling can be changed from css file
* @use $.overlay({el:$("any element")); // for a random element overlay
*/

$.constructor.prototype.overlay = function() {
	let obj = {}, bodyname = false;
	arguments.length === 1 ? obj = arguments[0] : obj = { el:$('body'), elc:"overlay-body" };
	
	// Init the main variables
	let overlay = $("<div>"), // the main overlay body
	    closeBtn = $("<button>").ihtml("&times;"), 
	    body = obj.el;
	    
	obj.el.nodeName.toLowerCase() === 'body' && ( bodyname = true );
	if (bodyname && !isSet(obj.elc)) {
		bodyname =true;
		overlay.addclass("overlay-body");
		!isSet(obj.elb) && closeBtn.addclass("overlay-cl-btn");
	}
	
	isSet(obj.elc) && overlay.addclass(obj.elc);
	isSet(obj.elb) && closeBtn.addclass(obj.elb);
	
	// Appending the elments to each other
	body.append(overlay.append(closeBtn));
	// defining the closing function
	function closeme() {
		let bool = false;
		obj.el.nodeName.toLowerCase() === 'body' && (bool = true);
		body.removeChild(overlay);
		closeBtn.removeEventListener("click", closeme, false);
		if(bodyname){
			body.css({overflow : "auto"});
			body.scroll = "yes";
		}
	};
	// Full body overlay properties
	if(bodyname){
		body.css({overflow : "hidden"});
		body.scroll = "no";
		
	}
	
	// fall back styling 
	if(!isSet(obj.elc) && !bodyname) {
		// if there is no class defined
		overlay.style.position = 'absolute';
		overlay.style.width = body.scrollWidth + 'px';
		overlay.style.height = body.scrollHeight +'px';
		overlay.style.top = '0';
		overlay.style.left = '0';
		overlay.style.zIndex = '1000';
	}
	if(!isSet(obj.elb) && !bodyname) {
		closeBtn.css({
			'float': 'right;',
			'padding': '1.2rem;',
			'outline': 'none;',
			'border': 'none;',
			'color': '#ffffff;',
			'font-size': '2rem;',
			'line-height': '2rem;',
			'opacity': '0.6;',
			'background-color': 'rgba(0,0,0, 0);'
		});
	}
	
	overlay.style.margin !== '0px' && ( overlay.style.margin = '0px' );
	overlay.style.backgroundColor === body.style.backgroundColor && ( overlay.style.backgroundColor = 'rgba(0, 0, 0, 0.8)' );
	
	!isSet(obj.nobtn) ? closeBtn.addEventListener("click", closeme, false) : overlay.removeChild(closeBtn);
	// Return the overlay body to have a reference to attache elements to
	return overlay;
}

/** collapse
*  By default collapses the next sibling element
*************************************************
* 
*
* Parameters
* @obj {
*
*       @event : [ optional ] The event what would triger the collapse. Default to click
*       @active : [ optional ] A class to toggle a collapsible sign
*       @class : [ optional ] The element/s class to attache the event. Default class: collapse
*       @content : [ optional ] The content element/s  class name to be collapsed. Default : next sibling element
*       @html : [ Not yet implemented ] [ optional ] An array of elements to attach an event. 
* }
*
* @NOTE The number of elemets returned by obj.class and obj.content has to be equal. Otherwise the function will exit.
*       However if obj.clas is provided and not the obj.content the next sibling element is colapsed
*       Also a good practice if obj.class has ben provided obj.active should be too.
*       
* @FIX_NEED Some more attention require for nested elements
*
* @return Void
*
* @Use $.collapse();
* @use $.collapse( { class: 'faq-deliveries', content: 'faq-answers' } ); 
*/

$.constructor.prototype.collapse = function() {
	let obj = arguments,
		element,
		collapsible,
		content,
		active = 'collapse-active',
		e,
		show_hide;
		
	find_height = function(number) {
		let myclass;
		!isSet( obj.content ) ? myclass = 'collapse-content' : myclass = obj.content ;
		if( !this.style.maxHeight && this.parentElement && this.parentElement !== null) {
			if(this.parentElement.classList.contains(myclass)) {
				this.parentElement.style.maxHeight = this.parentElement.scrollHeight + number + 'px';
			}
			find_height.call(this.parentElement, number);
		}
	};
	
	show_hide = function() {
		let c = arguments[0];
		c.style.maxHeight ? c.style.maxHeight = null : c.style.maxHeight = c.scrollHeight + "px";
	};
	
	!isSet( obj.class ) ? element = document.getElementsByClassName( 'collapse' ) : element = document.getElementsByClassName ( obj.class ) ;
	!isSet( obj.content ) ? collapsible = 'undefined' : collapsible = document.getElementsByClassName ( obj.content );
	!isSet( obj.event ) ? e = 'click' : e = obj.event;
	!isSet( obj.active ) ? active = 'collapse-active' : active = obj.active;
	// Checking if the number of elements are equal
	if ( ( isSet( obj.class ) && isSet( obj.content ) ) && ( element.length !== collapsible.length )){
		console.warn( 'Dard warn: Collapse function parameters legth are not equal. exiting function.' );
		return ;
	}
	for(let i = 0; i < element.length; i++){
		element[i].addEventListener(e, function() {
			this.classList.toggle(active);
			collapsible === 'undefined' ? content = this.nextElementSibling : content = collapsible[i] ;
			find_height.call(content, content.scrollHeight);
			content.style.maxHeight ? content.style.maxHeight = null : content.style.maxHeight = content.scrollHeight + "px";
		});
	}
	
}

/** tabs
*  Tabs made easy
*************************************************
* 
*
* Parameters
* @obj {
*
*       @tab :  A class name OR an array of id's
*       @content : Class name for content OR an array of id's
*       @active : Class name for active buttons 
*       @event : Type of event to attache for the tab button
*       @default: [ optional ] The index of content to be displayed at start. Default to the first element on list. Or FLASE won't show anything
*       @func: [ optional ] additional function to activate upon click on the tab
* }
*
* @NOTE The number of elements returned by obj.tab and obj.content has to be equal
*       
*       
* @FIX_NEED None
*
* @return Void
*
* @Use $.tabs( { tab: 'classname',  content: 'otherclassname', active:'active', event:'click' , default:2} );
* @use $.tabs( { tab:['dsn_110', 'dsn_107', 'dsn_108'],  content:["dsn_4", "dsn_7", "dsn_6"], active:'active', event:'click', default:0 } );
*/

$.constructor.prototype.tabs = function() {
	let obj = arguments[0], defaultTab,
		defaultContent, tab = [], content = [];
		
	function grabEl(){
		let grabed = [], str, classREgx = /^[a-zA-Z]{1}[a-z0-9\-\_\s]+$/i; 
		
		if( !isArray(this) && this instanceof String) {
			str = this.toString();
			if(classREgx.test(str)) {
				 ( str === obj.content || str === obj.tab ) && ( grabed = document.getElementsByClassName(str) )
			} 
		} else if(isArray(this)){
			for ( let i = 0; i < this.length; i++) {
				grabed[i] = document.getElementById(this[i]);
			}
		}
		return grabed;
	}
	
	function showDefault() {
		let stopBool = true;
		if( isSet(obj.default)) {
			if( !isBool(obj.default) && isNum(obj.default)) {
				defaultContent = content[obj.default];
				defaultTab = tab[obj.default];
			}
			if( isBool(obj.default) && obj.default === false ) {
				stopBool = false;
			}
		}else{ 
			defaultContent = content[0];
			defaultTab = tab[0];
		}
		hideContent.call(content);
		stopBool ? ( ( defaultContent.style.display = "block" ) && defaultTab.classList.toggle(obj.active) ) : defaultContent.style.display = "none";
	}
	
	function hideContent() {
		for (let i = 0; i < this.length; i++){
			this[i].style.display = 'none';
		}
	}
	
	function removeClass(name) {
		for (let i = 0; i < this.length; i++){
			this[i].className = this[i].className.replace(" " + name, "");
		}
	}
	
	function actOn(){
		for (let i = 0; i < tab.length; i++){
			tab[i].addEventListener(obj.event, function() {
				removeClass.call(tab, obj.active);
				hideContent.call(content);
				tab[i].classList.toggle(obj.active);
				content[i].style.display = "block";
				isSet(obj.callback) && isFunc(obj.callback) ? obj.callback.call(this) : null;
			});
		}
	}
	
	isSet(obj.tab) && ( tab = grabEl.call(obj.tab) );
	isSet(obj.content) && ( content = grabEl.call(obj.content) );
	
	if( content.length > 0 && content.length == tab.length) {
		hideContent.call(content);
		showDefault();
		actOn();
	}else {
		console.warn('Dard warn: $.tabs content 0 or tabs and content not equal in length');
		return;
	}
	
}