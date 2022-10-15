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
* Counter function. Tthe perfect example for a closure
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

	args[0] ? arg = args[0] : console.warn('Dard function has no arguments');
	isNum(args[1]) ? itemNo = args[1] : itemNo = 0;

	if ( typeof arg == 'string') {
		if (idRE.test(arg))
			el = document.getElementById(arg.substring(1));
		if (classNameRE.test(arg))
			el = document.getElementsByClassName(arg.substring(1))[itemNo];
		if (tagNameRE.test(arg))
			el = document.createElement(arg.replace(/^<+|>+$/gm, ''));
		if (plainTagRE.test(arg))
			el = document.getElementsByTagName(arg)[itemNo];
	} else if (isObj(arg) && ( arg instanceof HTMLElement || arg instanceof HTMLDocument )) {
		el = arg;
	} else if (isObj(arg) || arg instanceof HTMLCollection) {
		console.log("collection handling not yet implemented, returned as bare HTMLCollection");
		return arg;
	}

	if (el) {

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

		el.constructor.prototype.remove = function(e) {
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
		*  Adding attributes to itself, attribut and value or just an attribut
		*  Second parameter is optional
		*  .addattr(attr, value);
		*/

		el.constructor.prototype.addattr = function() {
			let arg = varyArgs(arguments);
			if (arguments.length === 2) {
				this.setAttribute(arg[0], arg[1]);
			}else if (arguments.length === 1) {
				this.createAttribute(arg[0]);
			}
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
			if (this && isStr(newclass)) {
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

		/** UNTESTED
		*  It may have an unexpected behavior yet
		*****************************************
		*
		* @param first is a callback functions, apply the array or object on childNodes
		* @param second an array or iterate object
		*/

		el.constructor.prototype.walk_Child = function() {
			let arg = varyArgs(arguments),
				callback = arg[0],
				el = this.childNodes,
				k = 0;
				for (let i = 0, j = el.length; i < j; i++) {
					if (el[i]) {
						if (arg.length === 1 && isFunc(arg[1]))
							callback(el[i]);
						// This behavior has to be checked
						if (arg.length === 2 && (isObj(arg[1] || isArray(arg[1])))) {
							callback(el[i], arg[1]);
						}
					}
				}
			return this;
		};

		return el;
	}
};

/*
 *
 *
 * AJAX function with main funcionality on POST GET and JSON
 *
 *
 *
 * The ajax object
 *
 *	var ajaxObj = {
 *
 * @  	type : 'GET',  // type of request POST or GET
 * @  	url : 'your/page/url', // the page url
 * @  	response : 'function', //handle the response from server
 * @  	send : null, // in GET request is optional
 * @  	json : true, // optional required if you do not stringify before the object
 * @  	error : 'custom error message' // optional to see for errors in consol log
 *
 * };
 *
 */

var ajax = function(obj) {
	var getPostJson = function() {
		var xhr = new XMLHttpRequest();
		xhr.open(obj.type, obj.url);
		xhr.setRequestHeader("HTTP_X_REQUESTED_WITH", "dard_ajax");
		if (obj.type === 'POST' && !obj.keyIn('json'))
			xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		param(obj.send);
		if (obj.keyIn('json') && obj.json === true) {
			xhr.setRequestHeader("Content-Type", "application/json ; charset=UTF-8");
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
		var encodedString = '';
		for (var prop in object) {
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

/*
 *
 *  Starting DOM manipulation functions
 *
 *
 * str2el()
 *
 * Create a valid DOM element from a html string
 *
 */

function str2el(html) {
	var fakeEl = document.createElement('iframe');
	fakeEl.style.display = 'none';
	document.body.appendChild(fakeEl);
	fakeEl.contentDocument.open();
	fakeEl.contentDocument.write(html);
	fakeEl.contentDocument.close();
	var el = fakeEl.contentDocument.body.firstChild;
	document.body.removeChild(fakeEl);
	return el;
};

function emptyEl(el) {
	var e = $(el);
	while (e.firstChild) {
		e.removeChild(e.firstChild);
	}
}
