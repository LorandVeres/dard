/**
 * DARD SNIPET CREATOR
 *
 * @author Lorand Veres lorand.mast@gmail.com
 * 
 * @copyright Lorand Veres lorand.mast@gmail.com
 * @license MIT
 *
 *
 *  This is just a two-three days work, to have a basic start on the idea and direction
 *     of structures.
 *
 */

 let snipet_creator = (function (){
	let $d = {},
		snb = $('.dsn-body'), //snipet container
		smn = $('.dsn-side-menu'), // side menu
		el, // current element
		esb = [], // element siblings if any
		ep, // element parentElement
		$n = { }, // dsn snipet objects
		weListen = [], // an array objects  { 0:element, 1:event, 2:function };
		$de,
		html_blocks = [ 'form', 'section', 'cards', 'list'], // type of implemented blocks
		copy, // the new copy function
		snipet_box, // the snipet container
		show_select, // function
		attributes; // function
		
		// Inline styling structure
		$n.style = {
			reset : "\n",
			ui : "\n",
			dsn : "\n",
			general : ".dsn-body div{ max-height:max-content; background: #ececec; margin:1.5%;overflow:auto;} .dsn-body p{ padding:10px; line-height:1.5rem}\n",
			classes : "\n",
			hover : ".dsn-hover { outline: 1px dashed #0264b4 !important; outline-offset: -1px; background-color: rgba(47, 157, 248, 0.2); box-shadow: 0px 0px 5px 5px rgba(2, 100, 180, .3) inset; }\n",
			active : ".dsn-active { outline: 2px solid #3b97e3 !important; outline-offset: -2px; background: rgba(47, 157, 248, 0.2); box-shadow: 0px 0px 5px 5px rgba(2, 100, 180, .3) inset; }\n"
		};
		
		
		
	
	/**
	 * It is the dard JSON HTML mode
	 * This will stay here for a while for illustrative purposes only
	 */
	
	$n.attrDisplaySnipet = {
		e_name: 'div',
		e_type: 1,
		e_attr: { class: 'dsn-button-like-box' },
		e_content: {
			0: {
				e_name: 'span',
				e_type: 1,
				e_attr: { class:'dsn-el-property', "data-dsn-txt-id": "0" },
				e_content: ''
			},
			1: {
				e_name: 'span',
				e_type: 1,
				e_attr: { class:'dsn-el-property-del'},
				e_content: "×"
			}
		}
		
	};
	
	/**
	 *  Seting up the event listener on the snipet 
	 * 
	 */
	$d.snipetListener = function(){
		
		this.addEventListener('mouseover', function(e){
			e.stopPropagation();
			this.classList.toggle('dsn-hover');
		});
		
		this.addEventListener('mouseout', function(e){
			e.stopPropagation();
			this.classList.toggle('dsn-hover');
		});
		
		this.addEventListener('click', function(e){
			e.stopPropagation();
			$d.change.call(this);
		});
		
		if( this.childElementCount > 0) {
			$(this).walkChild( $d.snipetListener ) ;
		}
	};
	
	/**
	 *  @Unfinnished Idea yet
	 *  Add event listeners on the weListen array like format [ { 0:element, 1:event, 2:function }]
	 *  Can add liteners to HTMLElements $d.listen.call(element, 'eventName', callback)
	 * 
	 */
	
	$d.listen = function(){
		let warn0 = 'oops the element with id ' + this[0] + ' is not found in menuListener',
			warn1 = 'oops the callback is not a function in menuListener for element ' + this[0] ;
		
		// Add event listeners on the weListen array like format [ { 0:element, 1:event, 2:function }]
		if(isArray(this)){
			for(let i = 0; i < this.length; i++ ){
				if( this[i][0] && isFunc(this[i][2]) ) { 
					this[i][0].addEventListener(this[i][1], function(e){
						this[i][2].call(this[i][0]);
					});
				}
				( !this[i][0] && console.log(warn0) ) && ( !isFunc( this[i][2] ) && console.log(warn1) ) ;
			}
		}
		
		// Add liteners to HTMLElements $d.listen.call(element, 'eventName', callback)
		if(this instanceof HTMLElement){ console.log(arguments);
			this.addEventListener(arguments[0], function(e){
				isFunc(arguments[1]) ? arguments[1].call(this) : console.warn(warn1);
				//arguments[1].call(this)
			});
		}
	};
	
	/**
	 *  Don't think this will be used. maybe will be removed
	 *  
	 */
	
	$d.pushListen = function(a, b, c){
		weListen.push({0:a, 1:b, 2:c});
	};
	
	/**
	*************************************
	* Changing the current element
	* @returns the new element
	*/
	 $d.change = function(){
		// remove the active class from current element
	
		el.classList.contains("dsn-active") && el.classList.toggle('dsn-active');
		
		el = this;
		ep = el.parentElement; 
		// Add the active class on the new element
		el.classList.toggle('dsn-active'); 
		if( !isSet( attributes )){
			attributes = new $d.attr();
		}
			attributes.resetEl();
		
		return el;
	};
	
	/** Menu Items
	****************************************
	*/
	
	 /** Move to the first element child */
	 
	$d.goToFirstKid = function(){ if(el.firstElementChild) $d.change.call(el.firstElementChild); };
	
	/** Move to parent element. Stops before exiting the snipet container */
	
	$d.goToParent = function(){ if(el.parentElement && !el.parentElement.classList.contains("dsn-body") ) $d.change.call(el.parentElement); };
	
	/** Move to the younger sibling DOWN :) */
	
	$d.goToYoungerBrother = function( ){ if(el.nextElementSibling) $d.change.call(el.nextElementSibling); };
	
	/** Move to the older sibling UP :) */
	
	$d.goToOlderBrother = function(){ if(el.previousElementSibling) $d.change.call(el.previousElementSibling); };
	
	/**
	*************************************
	* Removing the current element
	* Set the curent element to the first child of snipet container
	* @returns the new element
	*/
	$d.elDroped = function(){
		if(confirm('Would you, Realy want to  delete this element ?') == true ){
			el && ep.removeChild(el);
			el = $( snb.firstElementChild );
			ep = el.parentElement;
			return el;
		}
	};
	
	/**
	 *  The first primitive copy function 
	 *  
	 */
	 
	$d.copyEl = function(){
		let copy, self = {}, newEl = {}, copyButton, pasteButton;
		
		self.save = function(){
			el.classList.contains("dsn-active") && el.classList.toggle('dsn-active');
			el.classList.contains("dsn-hover") && el.classList.toggle('dsn-hover');
			copy = $.snipetHandler.gett(el);
			el.classList.toggle("dsn-active");
		};
		
		self.paste = function(obj) {
			$.snipetHandler.sett.call();
		}
		
		copyButton = document.getElementById("dsn_106");
		copyButton.addEventListener('click', function(){self.save(); console.log(copy);});
		
		pasteButton = document.getElementById("dsn_100");
		pasteButton.addEventListener('click', function(){
			newEl.obj = copy;
			newEl.position = "beforeend";   //  default in to the current element at the end
			newEl.recipient = el; // default the recipient is the curently selected element 
			newEl.contentArray = [];
			$.snipetHandler.sett.call(newEl);
		});
		
		return self;
	};
	
	/**
	 *  Seting up the event listeners on the menu items 
	 *  
	 */
	 
	$d.menuListener = function(){
		let callable =this[1],
			warn0 = 'oops the element with id ' + this[0] + ' is not found in menuListener',
			warn1 = 'oops the callback is not a function in menuListener' ;
			e = document.getElementById(this[0]);
		e ? e.addEventListener("click", function(e){
			e.stopPropagation();
			!isFunc( callable ) ? console.warn( warn1 ) : callable() ;
		}) : console.warn( warn0 ) ;
	};
	
	/**
	**************************************	
	* Returns the current element if called from outside;
	* @returns current element
	*/ 
	$d.getEl = function(){
		return el;
	};
		
	/** 
	*************************************
	*   Dealing with element attributes
	*
	*   @Need to be implemented a better sorting for showing the attributes (The fixed ones like id title, name etc)
	*           they have to ocupy their space in the general settings
	*   
	*
	*/
	$d.attr = function(){
		let self = {},
			showObj = { snipet:{} }, // the object passed to showAttr {obj, arr, datatype, datavalue, callback}
			bool = false, // true once we have the listeners set up
			reseting = false, // used to pause the attributes change while reseting eattr
			eattr = {}, // All current element attributes including set by the editor
			cont = document.getElementsByClassName('dsn-attr'), // = $('.dsn-attr')
			fields = [], // array of obj for attributes sorting{ element, data-type }
			listClasses = [],
			listAttr = {},
			getFields, // function
			handleType, // function
			customAttr, // function
			showAttr, // function
			removeAttr, //function
			showClass, //function
			removeClass, // function
			resetClasses, // function
			resetAttributes; //function
		
		
		showObj.snipet.obj = $n.attrDisplaySnipet;
		
		/**
		* Creating a deleting menu block for the atributes
		* @Param {obj, arr, datatype, datavalue}
		*/
		
		showAttr = function( ) {
			let menu = $.snipetHandler.sett.call(this.snipet, this.arr),
				link;
			
			link = menu.children[1];
			link.setAttribute( "data-type", this.datatype);
			link.setAttribute( "data-value", this.datavalue);
			link.addEventListener('click', function( ){ removeAttr.call(link) ;});
			
		};
		
		/**
		* Removing the attributes using the menu button
		*/
		
		removeAttr = function() {
			let att = this.getAttribute("data-value");
			this.removeEventListener("click", removeAttr);
			el.removeAttribute(att);
			eattr[att] && delete eattr[att];
			this.parentElement.parentElement.removeChild(this.parentElement);
		};
		
		/**
		* Show the classes in the menu
		*/
		showClass = function() {
			let menu,// $.snipetHandler.sett.call(this.snipet, this.arr),
				link, arr = this.arr;
			if( this.arr[0] !== "dsn-active" && this.arr[0] !== "dsn-hover" ){
				menu = $.snipetHandler.sett.call(this.snipet, this.arr),
				el.classList.add(this.arr[0]);
				link = menu.children[1];
				link.setAttribute( "data-type", this.datatype);
				link.setAttribute( "data-value", this.datavalue);
				link.addEventListener('click', function (){ removeClass.call(link, arr[0]) ;});
			}
		};
		
		/**
		* Delete the classes using the menu button
		*/
		removeClass = function() {
			let att = this.getAttribute("data-value");
			this.removeEventListener("click", removeClass);
			el.removeAttribute(att);
			el.classList.remove(arguments[0]);
			this.parentElement.parentElement.removeChild(this.parentElement);
		}
		
		/**
		* Delete the classes from the menu shown while swaping elemnts focus
		* and populate the current element ones
		*/
		resetClasses = function() {
			let classmenu, sel = { };
				
			classmenu = document.getElementById("dsn_5");
			listClasses.splice(0, listClasses.length-1);
			
			sel.rm = function (){
				//listClasses.splice(0, listClasses.length-1);
				while(classmenu.firstElementChild){
					if(classmenu.firstElementChild.children[1])
						classmenu.firstElementChild.children[1].removeEventListener('click', removeClass );
					classmenu.removeChild(classmenu.firstElementChild);
				}
			};
			
			sel.add = function () {
				let l ='';
				showObj.snipet.recipient = document.getElementById("dsn_5");
				el.hasAttribute('class') && ( l = el.getAttribute('class'));
				!empty(l) && ( listClasses = l.split(' '));console.log(listClasses);
				arrayRemove(listClasses, "dsn-active");
				for(let i = 0; i < listClasses.length; i++){
					showObj.snipet.recipient = document.getElementById("dsn_5");
					showObj.datatype = "class";
					showObj.datavalue = listClasses[i];
					showObj.arr = [ listClasses[i] ];
					if (listClasses[i] !== "dsn-hover" || listClasses[i] !== "dsn-active" )
						showClass.call(showObj);
				}
				
			};
			return sel;
		};
		
		/**
		* Delete the attributes from the menu shown while swaping elemnts focus
		* and populate the current element ones
		*/
		resetAttributes = function() {
			let attrmenu = $("#dsn_6"),
				sel = {};
			
			sel.rm = function (){
				while(attrmenu.firstElementChild){
					if(attrmenu.firstElementChild.children[1])
					attrmenu.firstElementChild.children[1].removeEventListener('click', removeAttr);
					attrmenu.removeChild(attrmenu.firstElementChild);
				}
			};
			
			sel.add = function () {
				let l = {}, val;
				showObj.snipet.recipient = attrmenu;
				
				if(el.hasAttributes()) {
					for (const attr of el.attributes) {
						l[attr.name] = attr.value;
					}
				}console.log(l);
				for( let prop in l ){
					if(prop !== "class"){
						showObj.snipet.recipient = attrmenu;
						showObj.datatype = prop;
						val = l[prop];l.hasOwnProperty(prop) && console.log(l[prop]);
						showObj.datavalue = prop;
						showObj.arr = [ val ];
						l.hasOwnProperty(prop) && showAttr.call(showObj);
					}
				}
				
			};
			return sel;
			
		};
			
		/**
		*   Creating the attributes and checking their existence
		*   We won't over write the same attribute multiple times
		*/
		customAttr = function(){
			let name, value, i, checks;
			showObj.snipet.recipient = document.getElementById("dsn_6");
			showObj.datatype = "attribute";
			
			checks = function(a, v) {
				let state = false;
				
				showObj.datavalue = a;
				
				if( ! eattr.hasOwnProperty(a)){
					if( !empty(a) && !empty( v ) ) { 
						el.setAttribute(a, v);
						eattr[a] = v;
						showObj.arr = [ a + '=' + v]; // inserting the text in the snipet
						showAttr.call(showObj);
						state = true
					}else if ( !empty(a) && empty(v) ){
						el.setAttribute(a, "");
						eattr[a] = "";
						showObj.arr = [ a ]; // inserting the text in the snipet
						showAttr.call(showObj);
						state = false;
					}
				}else if (eattr.hasOwnProperty(a)){
					if ( empty(eattr[a]) && !empty(a) ) {
						if(!empty( v )){
							el.setAttribute(a, v);
							eattr[a] = v;
							showObj.arr = [ a + '=' + v]; // inserting the text in the snipet
							showAttr.call(showObj);
							state = true;
						}else{
							state = false;
						}
					}
				}
				
				return state;
			};
			
			/** 
			*  We can create an empty attribute, 
			*  Clear the input fields once a atttribute/name pair has been added 
			*
			*/
			if(this.d === 'attr-name'){
				i = this.i + 1; 
				fields[i].e instanceof HTMLElement && (value = fields[i].e.value) ; 
				if (!empty(this.v) ) {
					fields[this.i].e.placeholder = "name here" ;
					if( checks(this.v, value ) ){
						fields[i].e.value = "";
						fields[this.i].e.value = "";
					}
				} 
			}
			/**
			*   We can't create just one attribute value without a name
			*   Clear the input fields once a atttribute/name pair has been added
			*/
			if(this.d === "attr-value"){
				i = this.i - 1;
				fields[i].e instanceof HTMLElement && (name = fields[i].e.value) ;
				if( !empty(this.v) ) {
					if(checks(name, this.v )) {
						fields[i].e.value = "";
						fields[this.i].e.value = "";
						fields[i].e.placeholder = "name here";
					}else{
						fields[i].e.placeholder = "Attribute Name Required";
					}
				}
			}
		}
		
		/**
		*   Sorting the results for handling respective of type of data
		*/
		
		handleType = function(){
			let classname = this.v 
			if (! reseting ){
				switch(this.d){
					case 'class':
						showObj.snipet.recipient = document.getElementById("dsn_5");
						showObj.datatype = "class";
						showObj.datavalue = classname;
						showObj.arr = [ classname ];
						!empty(this.v) && showClass.call(showObj);
						fields[this.i].e.value = "";
						listClasses.push(this.v)
						break;
					case 'attr-name':
						customAttr.call(this);
						break;
					case 'attr-value':
						customAttr.call(this);
						break;
					default:
						el.setAttribute(this.d, this.v);
						eattr[this.d] = this.v;
				}
			}
		};
		
		getFields = function(){
			for(let i =0; i < cont.length; i++){
				fields.push( { e:cont[i].children[1], d:cont[i].getAttribute('data-type') } );
			}
		}
		
		self.listen = function() {
			fields.length === 0 && getFields() ;
			if (!bool) {
				for(let i =0; i < fields.length; i++){
					fields[i].e.addEventListener('change', function(){
						handleType.call( { d:fields[i].d, i:i, v:fields[i].e.value} );
					});
				}
				bool = true;
			}
		};
		
		self.resetEl = function() {
			let att, val, i, rc, ra;
			reseting = true ; // reseting start 
			eattr = {};
			rc = new resetClasses(); // reset classes
			ra = new resetAttributes();
			rc.rm();
			ra.rm();
			// reseting the attributes obj
			att = el.getAttributeNames();
			for(i = 0; i < att.length; i++){
				eattr[att[i]] = el.getAttribute(att[i]);
			}
			
			// clearing all fields values
			{   
				for(i=0; i < fields.length; i++){
					fields[i].e.value = "";
				}
				//fields.splice(0, (fields.length));
			}
			rc.add();
			ra.add();
			reseting = false; // reseting done .
		}
		
		return self;
	};
	$d.cl = function(){
	
	};
	
	//console.log(location.href.split("=")[2]);
	// Initializig the work enviroment
	$d.init();
	return $d;
}());


//let new_snipet = snipet_creator();


//$.snipetHandler.gett($('form'));
//console.log($.snipetHandler.gett($('.overlay-snipet')).e_content);
if($('.dsn-body')){
	let new_snipet = snipet_creator(),
		p = $('<p>', 'Just write in');
	$.collapse();
}