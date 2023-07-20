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

//let new_snipet = snipet_creator();


//$.snipetHandler.gett($('form'));
//console.log($.snipetHandler.gett($('.overlay-snipet')).e_content);
if($('.dsn-body')){
	let new_snipet = snipet_creator(),
		p = $('<p>', 'Just write in');
	$.collapse();
}