function snipet_creator(){
	let self = {},
		current_el,
		html_blocks = [ 'form', 'section', 'cards', 'list'], // type of implemented blocks
		snipet_box, // the snipet container
		init, // function
		show_select; // function
	
	init = function(){
		let snb = $('.dsn-body'),
			smn = $('.dsn-side-menu'),
			sw = window.screen.width,
			// Total menu width = scrollWidth + left margin included
			mw =  parseInt(smn.scrollWidth + parseInt(window.getComputedStyle(smn).marginLeft.replace((/px/gi), "")));
		// Adjusting overlay-snipet-body width
		snb && (snb.style.width = parseInt(sw - mw) + 'px');
		// Including our snipet box container and setting current_el to snipet_box
		isSet(snipet_box) && snb.insertAdjacentElement('afterbegin', snipet_box);
		!isSet(current_el) && (current_el = snipet_box);
		
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