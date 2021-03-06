/**
 * @author Lorand Veres user.email "lorand.mast@gmail.com"
 *
 *
 */

/*
 * Basic comparison
 * and other useful functions
 *
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

function empty(v) {
	var r = false;
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

function setCss(el, css) {
	var k,
	    f = '';
	if (isObj(css)) {
		for (k in css) {
			if (css.hasOwnProperty(k))
				f += k + ':' + css[k] + ';';
		}
		el.style.cssText = f;
	}
}

function toggle(el) {
	var s = window.getComputedStyle(el, null).getPropertyValue("display");
	s === 'none' ? el.style.display = 'block' : el.style.display = 'none';
};

//
// recomended usage for testing pourpuse only
//
function simulateClick(onTarget) {
	var evt = document.createEvent("MouseEvents");
	evt.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
	$(onTarget).dispatchEvent(evt);
}

function counter() {
	var count = 0;
	var change = function(val) {
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
		var el = obj.el;
		while (el.hasChildNodes()) {
			el.removeChild(el.firstChild);
		}
		isObj(obj.html) ? el.appendChild(obj.html) : el.appendChild(str2el(obj.html));
		if (obj.keyIn('js'))
			require_js_module(obj.js);
	}
}

// Useful Object relating functions

function myObj() {
	self = {};
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
		var c = 0,
		    k,
		    o = this;
		if ( typeof this == 'object') {
			for (k in o) {
				if (!( k in Object.prototype)) {
					o.keyIn(k) ? c++ : null;
				}
			}
			return c;
		}
	};
	return self;
}

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

// end of basic functions

// the MAIN selector function
//

var $ = ( function() {
	var args = [],
	    document = window.document,
	    idRE = /^#{1}[a-z0-9\-\_]+\-*$/i, // id REgex
	    classNameRE = /^\.{1}[a-z0-9\-\_\s]+$/i, // class REgex
	    tagNameRE = /^<{1}[a-z0-9]+>{1}$/i, // html tag REgex
	    plainTagRE = /^[a-z1-6]+$/,
	    toType = {},
	    toString = toType.toString;
	//
	Object.assign(Object.prototype, new myObj());

	var Dard = function() {
		var self = this;
		self.extendProto = function(prop) {
			if ( typeof prop === 'object')
				Object.assign(self, prop);
			return this;
		};
		// has been modified
		self.append = function(e) {
			if (this[0]) {
				if ( typeof e === 'object') {
					try {
						this[0].appendChild(e[0]);
					} catch(err) {
						try {
							this[0].appendChild(e);
						} catch(err2) {
							console.log(err + "\n" + err2);
						}
					}
				}
				if (isStr(e)) {
					this[0].appendChild(str2el(e[0]));
				}
			}
			return this;
		};
		// new function
		self.remove = function(e) {
			if (this[0]) {
				this[0].removeChild(e);
			}
			return this;
		};
		self.clone = function(fn) {
			if (this[0] && isFunc(fn)) {
				fn(this[0].cloneNode(true));
			}
			return this;
		};
		self.ihtml = function(t) {
			if (this[0]) {
				if (isStr(t)) {
					this[0].innerHTML = t;
				} else if (isFunc(t)) {
					t(this[0].innerHTML);
				}
			}
			return this;
		};
		self.ohtml = function(fn) {
			if (this[0] && isFunc(fn)) {
				fn(his[0].outerHTML);
			}
			return this;
		};
		self.cnode = function() {
			if (this[0]) {
				var arg = arguments;
				el = this[0].childNodes,
				k = 0;
				for (var i = 0,
				    j = el.length; i < j; i++) {
					if (el[i]) {
						if (el[i].nodeName.toLowerCase() === arg[0].toLowerCase()) {
							if (arg.length == 2 && isFunc(arg[1]))
								arg[1](el[i]);
							if (arg.length == 3 && el[i].nodeName.toLowerCase() === arg[0].toLowerCase()) {
								if (arg[1] == k++ && isFunc(arg[2])) {
									arg[2](el[i]);
								}
							}
						}
					}
				}
			}
			return this;
		};
		self.toggle = function() {
			if (this[0]) {
				var s = window.getComputedStyle(this[0], null).getPropertyValue("display");
				s === 'none' ? this[0].style.display = 'block' : this[0].style.display = 'none';
			}
			return this;
		};
		self.css = function(val) {
			var k,
			    f = '',
			    c;
			if (isObj(val) && this[0]) {
				for (k in val) {
					if (val.hasOwnProperty(k)) {
						c = k.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
						f += c + ':' + val[k] + ';';
					}
				}
				this[0].style.cssText = f;
			}
			return this;
		};
		self.me = function() {
			if (this[0]) {
				return this[0];
			}
		};
		self.on = function(ev, fn) {
			if (this[0]) {
				window.onload = this[0].addEventListener(ev, fn, false);
			}
			return this;
		};
		// new function
		self.onclick = function() {
			var callback = arguments[0],
			    prevent = arguments[1],
			    once = arguments[2],
			    tip;
			function doclick() {
				var el = this;
				callback();
				if (prevent) {
					event.preventDefault("click");
				}
				if (once) {
					this.removeEventListener("click", doclick, tip);
				}
			}
			if (this[0]) {
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
			}
		};
		self.val = function(v) {
			if (this[0]) {
				if (isStr(v)) {
					this[0].value = v;
				} else if (isFunc(v)) {
					v(this[0].value);
				}
			}
			return this;
		};
		self.empty = function() {
			if (this[0]) {
				while (this[0].firstChild) {
					this[0].removeChild(this[0].firstChild);
				}
			}
			return this;
		};
		// new function
		self.addattr = function(att, val) {
			if (this[0]) {
				this[0].setAttribute(att, val);
			}
			return this;
		};
		// new function
		self.addclass = function(newclass) {
			if (this[0] && isStr(newclass)) {
				this[0].classList.add(newclass);
			}
			return this;
		};
		// new function
		self.getTags = function() {
			var tags;
			if (this[0] && isStr(arguments[0])) {
				tags = this[0].getElementsByTagName(arguments[0]);
			}
			if (arguments.length > 1) {
				tags = $(tags[arguments[1]]);
				if (tags) {
					return tags;
				} else {
					console.log("No html element has been found.");
				}
				//return tags;
			}
			if (arguments.length === 1) {
				if (tags) {
					return tags;
				} else {
					console.log("No html elements has been found.");
				}
			}
		};
		return self;
	};

	// Selecting the element from first parameter
	function GetEl(arg, item) {
		var itemNo,
		    el = this,
		    args = varyArgs(arguments);
		el.constructor.prototype = new Dard();
		isNum(args[1]) ? itemNo = args[1] : itemNo = 0;
		if ( typeof arg == 'string') {
			if (idRE.test(arg))
				el[0] = document.getElementById(arg.substring(1));
			if (classNameRE.test(arg))
				el[0] = document.getElementsByClassName(arg.substring(1))[itemNo];
			if (tagNameRE.test(arg))
				el[0] = document.createElement(arg.replace(/^<+|>+$/gm, ''));
			if (plainTagRE.test(arg))
				el[0] = document.getElementsByTagName(arg)[itemNo];
		} else if (isObj(arg) && ( arg instanceof HTMLElement || arg instanceof HTMLDocument )) {
			el[0] = arg;
		} else if (isObj(arg) || arg instanceof HTMLCollection) {
			console.log("collection handling not yet implemented, returned as bare HTMLCollection");
			return arg;
		}
		if (el[0]) {
			return el;
		}
	}

	type(Dard);
	type(GetEl);

	return function() {
		var arg = varyArgs(arguments),
		    itemNo,
		    el;
		if (arg.length > 0) {
			isNum(arg[1]) ? itemNo = arg[1] : itemNo = null;
			return new GetEl(arg[0], itemNo);
		}
	};
}());
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