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
		
//let new_snipet = snipet_creator();


//$.snipetHandler.gett($('form'));
//console.log($.snipetHandler.gett($('.overlay-snipet')).e_content);
if($('.dsn-body')){
	let new_snipet = snipet_creator(),
		p = $('<p>', 'Just write in');
	$.collapse();
}