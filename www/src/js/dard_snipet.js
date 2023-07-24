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
	 * Initializig $d and the work enviroment
	 */
	$d.init = function(){
		// init el if not set
		!el && ( el = $( snb.firstElementChild )); 
		!ep && ( ep = $( el.parentElement ));
		
		// Adjusting the snipet body container width
		// Total menu width = scrollWidth + left margin included
		let mw =  parseInt(smn.scrollWidth + parseInt(window.getComputedStyle(smn).marginLeft.replace((/px/gi), "")));
		snb && (snb.style.width = parseInt( window.screen.width - mw) + 'px');
		
		$('head').append($('<style>', $n.style.reset + $n.style.general + $n.style.classes + $n.style.hover + $n.style.active ));


		// event listener on all elements of the snipet
		$(snb).walkChild( $d.snipetListener );
		
		if( !isSet(attributes) ) attributes = new $d.attr();
		attributes.listen();
		
		// Setting up the event listeners on node links navigation
		$d.menuListener.call(['dsn_105', $d.elDroped ]);
		$d.menuListener.call(['dsn_104', $d.goToFirstKid ]);
		$d.menuListener.call(['dsn_103', $d.goToParent ]);
		$d.menuListener.call(['dsn_102', $d.goToYoungerBrother ]);
		$d.menuListener.call(['dsn_101', $d.goToOlderBrother ]);
		copy = new $d.copyEl();
		$d.listen.call(weListen);
		
	};
	
	/**
	 * This is the dard JSON HTML data structure
	 * This will stay here for a while for illustrative purposes only
	 */
	
	$n.customAttribute = {
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
	 * This is the dard JSON HTML data structure
	 * This will stay here for a while
	 *
	 */
	
	$n.expectedAttribute = {
		e_name: 'div',
		e_type: 1,
		e_attr: { class:"section group dsn-attr", "data-type":"", "data-value":"" },
		e_content: {
			0: {
				e_name: 'label',
				e_type: 1,
				e_attr: { for:"", "data-dsn-txt-id": "0" },
				e_content: ''
			},
			1: {
				e_name: 'input',
				e_type: 1,
				e_attr: { type:"text" , placeholder:""},
			}
		}
	}
	

	/**
	 *  Seting up the event listener on the snipet 
	 *  this will remain global inside the snipet.js 
	 *  to be accessible at addition and removal of elemnts
	 *
	 *  @Use 
	 *  Example for a full snipet block of html
	 *
	 *  let asd = {recipient:$('.dsn-body')};
	 *  asd.obj = $.snipetHandler.gett($('.dsn-body'), true);
	 *  let bbg = $.snipetHandler.sett.call(asd);
	 *  $d.snipetListener.call(bbg);
	 *
	 */
		
	function snipetMoveover(e){
		e.stopPropagation();
		this.classList.toggle('dsn-hover');
	}
	
	function snipetMoveout(e){
		e.stopPropagation();
		this.classList.toggle('dsn-hover');
	}
	
	function snipetClickable(e){
		e.stopPropagation();
		$d.change.call(this);
	}
	
	// accessible for one element
	
	function removeSnipetEvent() {
		this.removeEventListener('mouseover', snipetMoveover);
		this.removeEventListener('mouseout', snipetMoveout);
		this.removeEventListener('click', snipetClickable);
	}
	// accessible for one element
	
	function addSnipetEvent() {
		this.addEventListener('mouseover', snipetMoveover);
		this.addEventListener('mouseout', snipetMoveout);
		this.addEventListener('click', snipetClickable);
	}
	
	// accessible for one element and recursively all it's child elements

	$d.snipetListener = function(){
		
		if(this instanceof HTMLElement) {
			addSnipetEvent.call(this);
		}else if(isObj(this)) {
			for( prop in this){
				$d.snipetListener.call(this[prop]);
			}
		}
		
		if( this.childElementCount > 0 ) {
			$(this).walkChild( $d.snipetListener ) ;
		}
	};
	
	/**
	 *  @Unfinnished Idea yet
	 *  Add event listeners on the weListen array like format [ { 0:element, 1:event, 2:function }]
	 *  Can add liteners to HTMLElements $d.listen.call(element, 'eventName', callback)
	 *  
	 *  @Need a good review or deleted
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
	 *  Sets and get value of input elements based on id
	 *  Has in instance in init function 
	 *  @Use $i.get('id'), $i.set('id', 'new_value')
	 */
	function input(){
		let ext ={};
		// get the value of an input element
		ext.get = function (id){
			let e;
			isStr(id) && ( e = document.getElementById(id) );
			if ( e instanceof HTMLElement ) {
				return e.value ;
			}
		}
		// set the value of in input element
		ext.set = function(id, val){
			let e = document.getElementById(id);
			isStr(id) && e instanceof HTMLElement && ( e.value = val );
		}
		return ext;
	};
	
	function forms(){
		let f;
		
		
		
	}
	
	/**
	*************************************
	* Changing the current element
	* @returns the new element
	*/
	 $d.change = function(){
		// remove the active class from current element
	
		el.classList.contains("dsn-active") && el.classList.toggle('dsn-active');
		
		el = this;
		elName = el.nodeName.toLowerCase();
		ep = el.parentElement; 
		// Add the active class on the new element
		el.classList.toggle('dsn-active'); 
		if( !isSet( attributes )){
			attributes = new $d.attr();
		}
			attributes.resetEl();
			
		function myindex(el){
			let i = 0, e =el;
			while(e.previousElementSibling){
				e = e.previousElementSibling;
			i++
			};
			return i+1;
		}
		$i.set('dsn_90', elName);
		$i.set('dsn_91', myindex(el));
		$i.set('dsn_92', ep.children.length - 1);
		$i.set('dsn_93', el.children.length);
		
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
		let copy, self = {}, newEl = {}, copyButton, pasteButton, e;
		
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
			e = $.snipetHandler.sett.call(newEl );
			$d.snipetListener.call( e );
			$d.change.call(e);
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
	*/
	$d.attr = function(){
		let self = {},
			showObj = { snipet:{ } }, // the object passed to showAttr {obj, arr, datatype, datavalue, callback}
			bool = false, bool1 = false, // Do not touch! true once we have the listeners set up
			reseting = false, // used to pause the attributes change while reseting listAttr
			at = { ar:[], rq:[], cl:[], cu:{}, all:{ } };
		
		
		showObj.snipet.obj = $n.customAttribute;
		
		/**
		* Creating a deleting menu block for the atributes
		* @Param {obj, arr, datatype, datavalue}
		*
		* @Note this function despite the name is for custom attributes used
		*       The name it may be chanched in future.
		*/
		
		function showAttr( ) {
			let menu = $.snipetHandler.sett.call(this.snipet, this.arr),
				link;
			
			link = menu.children[1];
			link.setAttribute( "data-type", this.datatype);
			link.setAttribute( "data-value", this.datavalue);
			link.addEventListener('click', function( ){ removeAttr.call(link) ;});
			
		};
		
		/**
		* Removing custom attributes using the menu button
		*
		* @Note this function despite the name is for custom attributes used
		*       The name it may be chanched in future.
		*/
		
		function removeAttr() {
			let att = this.getAttribute("data-value");
			this.removeEventListener("click", removeAttr);
			el.removeAttribute(att);
			at.all[att] && delete at.all[att];
			this.parentElement.parentElement.removeChild(this.parentElement);
		};
		
		/**
		* Show the classes in the menu
		*/
		function showClass() {
			let menu = $.snipetHandler.sett.call(this.snipet, this.arr),
				link, 
				arr = this.arr;
				
				el.classList.add(this.arr[0]);
				link = menu.children[1];
				link.setAttribute( "data-type", this.datatype);
				link.setAttribute( "data-value", this.datavalue);
				link.addEventListener('click', function (){ removeClass.call(link, arr[0]) ;});
		};
		
		/**
		* Delete the classes using the menu button
		*/
		function removeClass() {
			let att = this.getAttribute("data-value");
			this.removeEventListener("click", removeClass);
			el.removeAttribute(att);
			el.classList.remove(arguments[0]);
			this.parentElement.parentElement.removeChild(this.parentElement);
		}
		
		/**
		 *  Required attributes fields in setings menu like href, name etc
		 *  Expected attributes as above less common 
		 *  However Id and title will stay permanent for all elements
		 *
		 */
		function requiredAttr() {
			let ext = {}, parent ; // External access, called from outside
				parent = document.getElementById("dsn_97");
			
			// Generete required attributes array, including the id and title
			function bond() {
				at.rq = []; at.rq = [ 'id', 'title']; // an initial value set, as we have checks against it's lenght
				if(isSet(elStruct[elName])) {
					at.rq = arrayMerge( at.rq , elStruct[elName].reqAttr, elStruct[elName].exAttr );
				}
			}
			
			// Set the attributes for the snipet included in the settings menu
			function fieldsAttr(i) {
				this.children[1].setAttribute( "id", 'dsn_a_' + i);
				this.children[0].setAttribute( "for", 'dsn_a_' + i);
				this.setAttribute( "data-type", 'attribute');
				this.setAttribute( "data-value", at.rq[i]);
			}
			
			// from hard code text color a class should be implemented
			function currentElAttr() {
				let j = arguments[0], attr = at.rq[j], field = arguments[1];
				j == 0 && ( attr = 'id');
				j == 1 && ( attr = 'title');
				at.all[attr] !== undefined ? field.previousElementSibling.style.color = "#0a9c05" : null;
				return at.all[attr];
			}
			
			// add the attribute, and change color in menu
			function setAttr(attr, val, s){
				el.setAttribute( attr, val);
				s.previousElementSibling.style.color = "#0a9c05";
			}
			
			// delete the attribute, and change color in menu
			function remAttr (attr, s) {
				el.removeAttribute(attr);
				s.previousElementSibling.style.color = "#b9b9b9";
			}
			
			// Callback in the event listener, Add or delete attributes. No empty attributes option
			function listen(){
				let attr, val = this.value, er = this.getAttribute('id');
				if(this.parentElement) {
					attr = this.parentElement.getAttribute('data-value');
					!empty(val) ? setAttr( attr, val, this) : remAttr(attr, this);
				}
			}
			
			// Adjusting the parent element max height in the menu
			function parentMaxHeight () {
				let parent;
				if(this.parentElement) {
					parent = this.parentElement;
					if( parent.classList.contains('collapse-content') && parent.style.maxHeight ){
						parent.style.maxHeight = parent.scrollHeight + 'px';
					}
				}
			}
			
			// listener for id and title field one time set up
			function onelistener(){
				if(!bool1){ // bool initial is false
					parent.children[0].children[1].addEventListener('change', listen);
					parent.children[1].children[1].addEventListener('change', listen);
					bool1=true;
				}
			}
			
			// append the attribute fields in the element settings menu section {
			ext.add = function() {
				let snipet, field, attr;
				snipet = {
					obj: $n.expectedAttribute,
					recipient: parent,
					position: 'beforeend'
				};
				onelistener(); // for id and title fields
				bond();
				if( at.rq.length > 2) {
					for( i = 0; i < at.rq.length; i++){
						i < 2 ? field = parent.children[i]  : field = $.snipetHandler.sett.call(snipet, [ capitalize(at.rq[i])+' :' ] ) ;
						if( i > 1) {
							field.children[1].addEventListener('change', listen);
							fieldsAttr.call(field, i);
						}
						attr = currentElAttr(i, field.children[1]);
						attr !== undefined && ( field.children[1].value = attr );
					}
					parentMaxHeight.call(parent);
				}else {
					// changing the color for label text and add id and title attribute 
					for( i = 0; i < 2; i++){
						field = parent.children[i];
						parent.children[i].children[1];
						attr = currentElAttr(i, field.children[1]);
						attr !== undefined && ( field.children[1].value = attr );
					}
				}
			}
			
			ext.rem = function(){
				let field;
				if( at.rq.length > 2){
					for( i = 0; i < at.rq.length; i++){
						if( parent.children[i] ) {
							field = parent.children[i].children[1];
							field.removeEventListener('change', listen) && console.log('event removed');
							if(i > 1 && parent.children[i]) {
								while(parent.children.length > 2){
									parent.removeChild(parent.children[i]);
								}
							}
							field.value = "";
							field.previousElementSibling.style.color = "#b9b9b9";
						}
					}
				}else{
					// changing the color for label text 
					for( i = 0; i < 2; i++){
						field = parent.children[i].children[1];
						field.value = "";
						field.previousElementSibling.style.color = "#b9b9b9";
					}
				}
				parentMaxHeight.call(parent);
			}
			
			return ext;
		}
		
		/**
		* Delete the classes from the settings menu shown while swaping elements focus
		* and populate with the new current element classes
		*/
		function resetClasses() {
			let parent, sel = { };
				
			parent = document.getElementById("dsn_98");
			at.cl.splice( 0, at.cl.length - 1 );
			
			sel.rm = function (){
				let box ;
				while(parent.firstElementChild){
					box = parent.firstElementChild;
					if(box.children[1]) {
						box.children[1].removeEventListener('click', removeClass );
						parent.removeChild(box);
					}
				}
			};
			
			sel.add = function () {
				let l ='';
				showObj.snipet.recipient = document.getElementById("dsn_98");
				el.hasAttribute('class') && ( l = el.getAttribute('class'));
				!empty(l) && ( at.cl = l.split(' '));
				arrayRemove(at.cl, "dsn-active");
				arrayRemove(at.cl, "dsn-hover");
				for(let i = 0; i < at.cl.length; i++){
					showObj.snipet.recipient = document.getElementById("dsn_98");
					showObj.datatype = "class";
					showObj.datavalue = at.cl[i];
					showObj.arr = [ at.cl[i] ];
					showClass.call(showObj);
				}
				
			};
			return sel;
		};
		
		/**
		* Delete the attributes from the menu shown while swaping elements focus
		* and populate the current element ones
		*/
		function resetAttributes() {
			let parent = document.getElementById("dsn_99"),
				sel = {};
				//at.rq = [];
			
			function buildCustomAttribute() {
				let value = arguments[0], prop = arguments[1];
				showObj.snipet.recipient = parent;
				showObj.datatype = "attribute";
				showObj.datavalue = prop;
				showObj.arr = [ prop + "=" + value ];
				showAttr.call(showObj);
			}
			
			sel.rm = function (){
				let box;
				while(parent.firstElementChild){
					box = parent.firstElementChild;
					if(box.children[1]){
						box.children[1].removeEventListener('click', removeAttr);
						parent.removeChild(box);
					}
				}
			};
			
			sel.add = function () {
				showObj.snipet.recipient = parent;
				if(el.hasAttributes()) {
					for (const attr of el.attributes) {
						at.all[attr.name] = attr.value;
					}
				}
				for( let prop in at.all ){
					if(at.all.keyIn(prop) && prop !== "class"){
						( at.rq.indexOf(prop) < 0 ) && buildCustomAttribute(at.all[prop], prop);
					}
				}
				
			};
			return sel;
			
		};
		
		/**
		*   Creating the attributes and checking their existence on the element
		*   We won't over write the same attribute multiple times
		*   @todo As it is at this time it is a bit hard to understand
		*         It may be rewriten
		*/
		function customAttr(){
			let name, value, i, parent, fields = arguments[0];
			parent = document.getElementById("dsn_99");
			showObj.snipet.recipient = parent;
			showObj.datatype = "attribute";
			
			// Delete the duplicate menu entry after a value has been asigned to the attribute
			// Compare argument against the data-value from menu 
			function cleanMenuEntry(arg){
				let box;
				box = parent.firstElementChild;
				if(box) {
					do {
						if(box.children[1].getAttribute("data-value") === arg){
							box.removeEventListener('click', handleType);
							box.parentElement.removeChild(box);
							return;
						}
						box.nextElementSibling && ( box = box.nextElementSibling );
					}while(box.nextElementSibling);
				}
			}
			
			function checks(a, v) {
				let state = false;
				
				showObj.datavalue = a;
				
				if( ! at.all.hasOwnProperty(a)){
					if( !empty(a) && !empty( v ) ) { 
						el.setAttribute(a, v);//cleanMenuEntry(a)
						at.all[a] = v;
						showObj.arr = [ a + '=' + v]; // inserting the text in the snipet
						showAttr.call(showObj);
						state = true
					}else if ( !empty(a) && empty(v) ){
						el.setAttribute(a, "");
						at.all[a] = "";
						showObj.arr = [ a + "= " ]; // inserting the text in the snipet
						showAttr.call(showObj);
						state = false;
					}
				}else if (at.all.hasOwnProperty(a)){
					if ( empty(at.all[a]) && !empty(a) ) {
						if(!empty( v )){
							cleanMenuEntry(a);
							// Update need to delete attributes with no value after update
							el.setAttribute(a, v);
							at.all[a] = v;
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
						fields[i].e.value = "";console.log(fields[i].e);
						fields[this.i-1].e.value = "";
					}console.log(this.e);
				} 
			}
			/**
			*   We can't create just one attribute value without a name
			*   Clear the input fields once a atttribute/name pair has been added
			*/
			if(this.d === "attr-value"){
				i = this.i - 1;console.log(fields);
				name = fields[i].e.value
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
		
		function handleType(){
			let classname = this.v;
			if (! reseting ){
				switch(this.d){
					case 'class':
						showObj.snipet.recipient = document.getElementById("dsn_98");
						showObj.datatype = "class";
						showObj.datavalue = classname;
						showObj.arr = [ classname ];
						!empty(this.v) && showClass.call(showObj);
						this.e.value = "";
						at.cl.push(this.v)
						break;
					case 'attr-name':
						customAttr.call(this, arguments[0]);
						break;
					case 'attr-value':
						customAttr.call(this, arguments[0]);
						break;
					default:
						el.setAttribute(this.d, this.v) && ( at.all[this.d] = this.v );
				}
			}
		};
		
		function getFields(){
			let fields =[], ids = [ 'dsn_96', 'dsn_95', 'dsn_94'], cont=[];
			for(let i =0; i < ids.length; i++) {
				cont[i] = document.getElementById(ids[i]);
			}
			for(let i =0; i < cont.length; i++){
				fields.push( { e:cont[i], d:cont[i].getAttribute('data-type') } );
			}
			return fields;
		}
		
		/**
		 *  It sets the event listeners at this time for class input and the 2 input fields
		 *  for custom attributes
		 */
		self.listen = function() {
			let fields = getFields() ;
			if (!bool) {
				for(let i =0; i < fields.length; i++){
					fields[i].e.addEventListener('change', function(){
						// { d: data-type, i: index, e: element, v: element.value}
						handleType.call( { d:fields[i].d, i:i, e:fields[i].e, v:fields[i].e.value}, fields );
					});
				}
				bool = true;
			}
		};
		
		self.resetEl = function() {
			let att, val, i, rc, ra, rq;
			reseting = true ; // reseting start 
			at.all = {};
			rc = new resetClasses(); // reset classes
			ra = new resetAttributes(); // reset attributes
			rq = new requiredAttr();
			rc.rm();
			ra.rm();
			rq.rem();
			// reseting the attributes obj
			att = el.getAttributeNames();
			for(i = 0; i < att.length; i++){
				at.all[att[i]] = el.getAttribute(att[i]);
			}
			
			rq.add();
			rc.add();
			ra.add();
			reseting = false; // reseting done .
		}
		
		return self;
	};
	
	//console.log(location.href.split("=")[2]);
	// Initializig the work enviroment
	
		
	$d.init();
	
	// Multiplying a few elements in the body for testing 
	let asd = {recipient:$('.dsn-body')};
	asd.obj = $.snipetHandler.gett($('.dsn-body'), true);
	let bbg = $.snipetHandler.sett.call(asd);
	$d.snipetListener.call(bbg);
	
	
	
	
	
	
	return $d;
}());


//let new_snipet = snipet_creator();


//$.snipetHandler.gett($('form'));
//console.log($.snipetHandler.gett($('.overlay-snipet')).e_content);
if($('.dsn-body')){
	//let new_snipet = new snipet_creator();
	$.collapse();
	//console.log(new_snipet.el());
}

let asd = {recipient:$('.dsn-body')};
asd.obj = $.snipetHandler.gett($('.dsn-body'), true);
console.log(asd);
let bbg = $.snipetHandler.sett.call(asd);
console.log(bbg);
