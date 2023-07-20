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
		
		
		
		//snb.append( $('<iframe>').addattrlist( { width:'100%', height:'100%', src:"https://dard.dard/modules", contenteditable:"true"} ) ); //, sandbox:'allow-scripts allow-same-origin'} ) );
	}
	
	
	
	//
	// testing area
	//
	//console.log(html_blocks);
	//$.overlay({el:$("body"), elclass:"overlay-snipet", headerclass:"overlay-header"});
	//console.log(location.href.split("=")[2]);
	
	// Initializig the work enviroment
	init();
	return self;
}


//let new_snipet = snipet_creator();


//$.snipetHandler.gett($('form'));
//console.log($.snipetHandler.gett($('.overlay-snipet')).e_content);
if($('.dsn-body')){
	let new_snipet = snipet_creator(),
		p = $('<p>', 'Just write in');
	$.collapse();
}