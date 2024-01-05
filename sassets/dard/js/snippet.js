/**
 * DARD SNIPET CREATOR
 *
 * @author Lorand Veres lorand.mast@gmail.com
 * 
 * @copyright Lorand Veres lorand.mast@gmail.com
 * @license MIT
 * 
 * @ToDo 
 *      @snipet
 *              
 *
 * 
 *
 */
"use strict";

 let dard_snipet = function (){
	let $d = {},                                    // this will be the returned Object. All functions accesible externaly will be in this object
		snb = $('#dsn_5'),                          // snipet body ( container )
		smn = $('.dsn-side-menu'),                  // side menu
		el,                                         // current element
		elName,                                     // current element name to lowercase
		esb = [],                                   // element siblings if any
		ep,                                         // element parentElement
		$n = { dummy:{}, c:{}, p:{}, style:{}, menu:{} },    // dsn snippet objects
		settings = { },                             // will store various settings
		elStruct = { },                             // elStruct.elementName{ id:'', exAttr:[], rqAttr:[], dsnId:''}
		snipets = {},                               // the snippet tempalates used for the page
		attributes,                                 // function in $d.init
		$st = {id:0, body:{}, name:'', type:'', status:'live', css:'' },    // stash
		$p = {id: 3, name:'sandbox', maxid: 0, maxclass: 0, $: {} },        // project
		$i  = {},                                   // Sets and get value of input elements based on id
		$fn = { body: {}, menu: {}},                // object containing functions to handle events on snippet container static body, and on the menu side
		noneditable,                                // Not editable elements 
		hideAttributes,                             // Attributes not shown on element settings
		hideClasses;                                // Classes not shown on element settings
		
		
		// Inline styling structure
		$n.style = {
			reset : "",
			ui : "",
			dsnFrame : "",
			general : "",
			classes : "",
			snippetCss : "",
			dsn: "/* Dsn last rules to override everything*/ \n"
		};
		
		// Not editable elements 
		noneditable = ['html', 'meta', 'link', 'form', 'input', 'select', 'textarea', 'br', 'hr', 'ul', 'ol', 'dl', 'img', 'embed', 'bgsound', 'base', 'col', 'source', 'fieldset'];
		
		// Attributes not shown on element settings
		hideAttributes = [ 'class', 'contenteditable', 'dsn-highlighted', 'style', 'dsn-ep-hover', 'data-dsnthemebody', 'data-dsninclude'];
		
		// Classes not shown on element settings
		hideClasses = [ 'dsn-hover', 'dsn-active', 'dsn-highlighted'];
		
		$n.style.dsn += ".dsn-highlighted { outline: 1px dashed #7d9eb9 !important; outline-offset: -3px; }\n";
		$n.style.dsn += ".dsn-hover { outline: 1px solid #0264b4 !important; outline-offset: -1px; box-shadow: 0px 0px 5px 5px rgba(2, 100, 180, .15) inset; }\n";//background-color: rgba(212, 235, 255, 0.1)
		$n.style.dsn += ".dsn-active { outline: 2px solid #3b97e3 !important; outline-offset: -2px; box-shadow: 0px 0px 3px 3px rgba(2, 100, 180, .1) inset; }\n";//background: rgba(212, 235, 255, 0.1)
		$n.style.dsn += ".dsn-ep-hover { outline: 2px solid rgba(172, 224, 0, .2) !important; outline-offset: 2px; box-shadow: 0px 0px 5px 5px rgba(2, 100, 180, .1) inset; }\n";
		$n.style.dsn += ".dsn-minsize, dsn-min-size { min-height:2rem; padding:5px; }\n";
		
		// default general settings 
		settings.layoutPosition = "beforeend";          // used when iserting new tags or layouts
		settings.elChangeLock = false;                  // Used to stop mouse over and click events on elements to show the outline, and set the current el variable
		settings.staticBody = undefined;
		settings.targetElement = undefined;             // The event.targetElement while settings.elChangeLock = true. May be used by overlays or deleted in future
		settings.lastCurrentElement = undefined;        // Saving the last active el if we would like to alter it from static settings pages
		settings.safeContainer = false;                  // Define an element as snippet container. Used in functions: saveSnippet, editDownload, createNewSnipet, ,highlightAll, clearWorkspace
		settings.whiteBoard = undefined;
		
		
		// default project settings
		$p.name = "sandbox";                            // we need a default project and that is sandbox
		$p.maxid = 0;                                   // max auto id generated
		$p.maxclass = 0;                                // max auto class generated
		$p.$.live = { form: [], block: [], header: [], footer: [], menu: [], article : [], cards: [], list: [], page: [], table: []};
		$p.$.template = { form: [], block: [], header: [], footer: [], menu: [], article : [], cards: [], list: [], page: [], table: []};
		$p.$.draft = { form: [], block: [], header: [], footer: [], menu: [], article : [], cards: [], list: [], page: [], table: [] };
		$p.$.type = ['form','block','header','footer','menu','article','cards','list','page','table'];
		$n.c = { id:0, body:{}, name:'', type:'', status:'', css:[] };      // current snippet
		$n.p = { id:0, body:{}, name:'', type:'', status:'', css:[]};       // downloaded snippet ( p stands from parked :) temporary parking )
		
		
		//elStruct.input = {  id:'0', reqAttr:['type', 'hidden'], exAttr:[ 'name', 'value', 'placeholder', 'required'] }
		
		
	/**
	 * Initializig $d and the work enviroment
	 */
	$d.init = function(){
		// init el if not set
		window.addEventListener("load", (event) => { 
			!settings.elChangeLock && $d.change.call($('.dsn-body').firstElementChild);
			
			// event listener on all elements of the side menu
			smn.addEventListener('click', (ev) => {
				let targetel = ev.target, at;
				at = $(targetel).attr('data-dsnfname');
				isFunc($fn.menu[at]) && $fn.menu[at].call(ev.target);
			});
			
			// event listener on all elements of the snipet
			$d.snipetListener = snippetListener();
			$d.snipetListener.add();
		});
		
		// Adjusting the snipet body container width
		screenEnviro();
		
		$('head').append($('<style>'));
		resetStyleTag('', '');


		// Using a tab sytem for side menu
		$.tabs( { tab:['dsn_110', 'dsn_107', 'dsn_108'],  content:["dsn_4", "dsn_7", "dsn_6"], active:'active', event:'click', default:2 } );
		
		if( !isSet(attributes) ) attributes = new $d.attr();
		attributes.listen();
		
		// Setting up the event listeners on node links navigation
		$d.menuListener.call(['dsn_105', $d.elDroped ]);
		$d.menuListener.call(['dsn_104', $d.goToFirstKid ]);
		$d.menuListener.call(['dsn_103', $d.goToParent ]);
		$d.menuListener.call(['dsn_102', $d.goToYoungerBrother ]);
		$d.menuListener.call(['dsn_101', $d.goToOlderBrother ]);
		
		snipetSetings();
		
		generalSettings();
		
		$d.copyEl();
		
		$d.tagsLayout();
		snippetTemplateMenu();
	};
	
	// Can be used in one line conditional statement to throw errors
	function throwError(arg) {
		throw new Error(arg);
	}
	
	function resetStyleTag (g, rules) {
		let arg = arguments,
			s = $('style'),
			r;                  // general coments
		r = ["/* Reset rules */ \n", "/* Ui rules */ \n", "/* Dsn snippet creator rules */ \n", "/* General rules */ \n", "/* Classes rules */ \n", "/* Css from snippet rules */ \n",];
		g === 'reset' && ($n.style.reset = r[0] + rules + "\n\n");
		g === 'ui' && ($n.style.ui = r[1] + rules + "\n\n" );
		g === 'dsnFrame' && ($n.style.dsnFrame = r[2] + rules + "\n\n");
		g === 'general' && ($n.style.general = r[3] + rules + "\n\n");
		g === 'classes' && ($n.style.classes = r[4] + rules + "\n\n");
		g === 'snippetCss' && ($n.style.snippetCss = r[5] + rules + "\n\n");
		s.empty();
		s.append(document.createTextNode($n.style.reset + $n.style.ui + $n.style.dsnFrame + $n.style.general + $n.style.classes + $n.style.snippetCss + $n.style.dsn));
	}
	
	// undefine the active element, 
	function undefineEl() {
		el && el.classList.contains('dsn-active') && el.classList.remove('dsn-active');
		el && el.classList.contains('dsn-hover') && el.classList.remove('dsn-hover');
		el && el.hasAttribute('contenteditable') && el.removeAttribute('contenteditable');
		el && ( settings.lastCurrentElement = el );
		el && ( el = undefined );
		ep && ( ep = undefined );
		elName && ( elName = '' );
		$i.sett('dsn_90', '');
		$i.sett('dsn_91', '');
		$i.sett('dsn_92', '');
		$i.sett('dsn_93', '');
	}
	
	/**
	 * Clear the Snippet container of the snippet, and fill with any static settings content
	 * @param {*} fn1 function to insert the static body
	 * @param {*} fn2  function to clear the added element. If no needed just pass a void function as reference like $d.staticBody(func1, ()=>{})
	 */
	$d.staticBody = function(fn1, fn2) {
		try{
			if(!settings.elChangeLock) {
				settings.elChangeLock = true;
				undefineEl();
				!$n.c.tempbody && ( $n.c.tempbody = $.snipetHandler.gett(snb, true));
				snb.empty();
				isFunc(fn1) ? fn1() : throwError ('Parameter 1 in \'staticBody\' function is not a function!: ' + fn1);
			} else {
				isFunc(fn2) ? fn2() : throwError ('Parameter 2 in \'staticBody\' function is not a function!: ' + fn2);
				snb.empty();
				$n.c.tempbody !== undefined && $.snipetHandler.sett.call({ obj:$n.c.tempbody, recipient:snb})
				delete $n.c.tempbody;
				settings.elChangeLock = false;
				settings.targetElement = undefined;
				settings.lastCurrentElement = undefined;
				settings.staticBody = undefined;
			}
		} catch (error){
			console.log(error);
		}
	}
	
	/**
	 *  Sets and get value of input elements based on id
	 *  
	 *  @Use $i.gett('id'), $i.sett('id', 'new_value')
	 */
	$i.sett = function(id, val){
		let e = document.getElementById(id);
		isStr(id) && e instanceof HTMLElement && ( e.value = val );
	}
	$i.gett = function (id){
		let e;
		isStr(id) && ( e = document.getElementById(id) );
		if ( e instanceof HTMLElement ) {
			return e.value ;
		}
	}
	
	/**
	 * @param Array Should be an array with the form fields ids to colect values from
	 * 
	 * @returns Object      In the form of {filedName: value, fieldname2 : value2} for each element that has the id in the array, if the element exist
	 */
	function getFormValuesById() {
		let reobj = {}, o = arguments[0], val, name;
		if(isSet(arguments[1]) && isObj(arguments[1]))
			reobj = arguments[1];
		if(isArray(o)) {
			for (let i = 0; i < o.length; i++) {
				const id = o[i];
				$('#' + id) && ( val = $('#' + id).value );
				$('#' + id) && ( name = $('#' + id).attr('name') );
				reobj[name] = val;
			}
		}
		return reobj;
	}
	
	function setFormValuesById () {
		let vobj = arguments[1], o = arguments[0], val, name;
		for (let i = 0; i < o.length; i++) {
			const element = $('#' + o[i]);
			let nameattr = element.attr('name');
			if( element.nodeName.toLowerCase() === 'input' && element.attr('type') === 'text') {
				vobj.keyIn(nameattr) && ( element.value = vobj[nameattr] );
			}
			if(element.nodeName.toLowerCase() === 'select' ){
				vobj.keyIn(nameattr) && element.walkChild(function() { 
					this.value === vobj[nameattr] && $(this).addattr('selected')
				});
			}
			if( element.nodeName.toLowerCase() === 'textarea') {
				vobj.keyIn(nameattr) && ( element.textContent = vobj[nameattr] );
			}
		}
	}
	
	/** Empty then
	*   Populate the select option with data
	*   @param urlarg It is the url parameter to get the data from [ optional ] object default first item {v: 'any value', t: 'text to be inserted' }
	*   @param id it is the id of the select field. Can be a string or an valid element
	*   @param object {     [ optional ]                    The whole object is optional
	*       @prop v:        [ text ]                        Stands from the default option value
	*       @prop t:        [ text ]                        Stands from the default option visible text
	*       @prop send:     [ object{} ]                    It is an object , Data to be sent to server
	*       @prop attr:     [ objrct{} ]                    Attributes value:pair to be added on option elements if needed
	*       @prop empty:    [ obj{ v:'', t:'None'} ]        One first empty element will be added just if object.v not empty, empty.t  the text to be displayed. Not selected as defaullt
	*   }
	*/
	function fillSelectFields(urlarg, id ) {
		let s = $(id), arg, send = {}, at, emp;
		s.empty();
		( isSet(arguments[2] ) && isObj(arguments[2]) && ( arg = arguments[2] ));
		isSet(arg) && arg.keyIn('send') && ( send = arg.send );
		isSet(arg) && arg.keyIn('attr') && ( at = arg.attr );
		isSet(arg) && arg.keyIn('empty') && ( emp = arg.empty );
		if(isSet(arg) && empty(arg.v))
			s.append ( $('<option>', "").addattr('value', "").addattr('selected') );
		function setSelect(r){
			let p, o;
			//console.log(at);
			if(r) {
				emp && (isSet(arg) && !empty(arg.v)) && s.append($('<option>', emp.t ).addattr('value', ""));
				for( let i = 0; i < r.length; i++) {
					p = r[i];
					o = $('<option>', p.name).addattr('value', p.name);
					if(isSet(arg) && p.name === arg.v)
						o.addattr('selected');
					if(isSet(at) && isObj(at)){
						for( let prop in at){
							at.hasOwnProperty(prop) && o.addattr(prop, at[prop]);
						}
					}
					s.append(o);
				}
			}
		}
		
		$.send_json({
			data: send,
			url: 'snippet?a=' + urlarg,
			callback: setSelect,
			log: 'Failed while loading select option values'
		});
	}
	
	// Pushing text in notifications bar
	function pushNotes() {
		let arg = arguments[0], box = $('#dsn-notifications-text'), bg = $('#dsn-notifications');
		isSet(arg) && box.ihtml(capitalize( arg ) );
		bg.style.border = '1px solid #b0ff22';//#f23a01';
		setTimeout( () =>  bg.style.border = '1px solid #424242', 800);
	}
	
	// Pushing current snipet name in notifications bar
	function pushName() {
		let arg = arguments[0], box = $('#dsn-snipet-name');
		isSet(arg) && !empty(arg) ? box.ihtml ( capitalize( arg )  ) : empty(arg) && box.ihtml("") && box.append($("<span>", "can't see me").css({'visibility':'hidden'}));
	}
	
	// Pushing current snipet name in notifications bar
	function pushProject() {
		let arg = arguments[0], box = $('#dsn-project-title');
		isSet(arg) && box.ihtml ( capitalize( arg )  );
	}
	
	// Adjusting the work enviroment width:  Mobile, tablet, monitor, full screen view or a custom width
	// Total menu width = scrollWidth + left margin included
	function screenEnviro() {
		let menuWidth =  parseInt(smn.scrollWidth + parseInt(window.getComputedStyle(smn).marginLeft.replace((/px/gi), ""))),
			mobileWidth = 360,
			tabletWidth = 820,
			monitorWidth = parseInt( window.screen.width - menuWidth),
			nowWidth,
			color;
			
		nowWidth = monitorWidth + 'px';
		
		function checkSideMenu() {
			let vis = window.getComputedStyle(snb, null).getPropertyValue("display");
			vis === 'none' && ( snb.style.display = 'block' );
		}
		
		function setProperties(arg){
			let marg = parseInt( (window.screen.width - menuWidth - arg) / 2 );
			marg = '0 ' + marg + 'px';
			nowWidth = arg + 'px';
			!isSet(color) ? snb.css( { 'width':nowWidth, 'margin':marg } ) : snb.css( { 'width':nowWidth, 'margin':marg, 'background-color':color  } );
			checkSideMenu();
		}
		
		function selectMobile (){
			setProperties(mobileWidth);
			$('#dsn-304').value = "";
		}
		
		function selectTablet (){
			setProperties(tabletWidth);
			$('#dsn-304').value = "";
		}
		
		function selectMonitor (){
			setProperties(monitorWidth);
			$('#dsn-304').value = "";
		}
		
		function  selectCustom() {
			!empty(this.value) ? setProperties(this.value) : setProperties(monitorWidth);
		}
		
		function selectFullScreen (){
			nowWidth = mobileWidth + 'px';
			snb.css( { 'width':'100%' } );
			smn.style.display = 'none';
			$('#dsn-304').value = "";
		}
		
		function watchColorPicker(event) {
		let c = event.target.value;
			snb.style.backgroundColor = c;
			$('#dsn-305').value = c;
			color = c;
		}
		
		function watchColorField(event) {
			let v = event.target.value;
			$('#dsn-306').value = v;
			snb.style.backgroundColor = v;
			color = v;
		}
		
		function reponsiveMode(){
			let editable;
			if(this.classList.contains('active') ){
				setProperties(monitorWidth);
				$('.dsn-body').empty();
				editable = $.snipetHandler.sett.call( { obj:$n.c.body, recipient: $('.dsn-body'), position:'beforeend' } );
				pushNotes("Edit mode activated...");
			} else if( !this.classList.contains('active')) { 
				undefineEl();
				$n.c.body = $.snipetHandler.gett($('#dsn_5'), true);
				$('#dsn_5').empty();
				$.send_json({
					url: 'snippet?a=responsive',
					data: { body: $n.c.body, projectname: $p.name },
					callback: function(r){
						setTimeout( (r == null && framing()) , 200);
					}
				});
				pushNotes("Responsive mode activated...");
			}
			$(this).attrtoggle('title', 'Responsive mode', 'Edit mode') ;
			this.classList.toggle('active');
		}
		
		function framing () {
			let fr, attr = {};
			attr = {
				id: 'dsn-frame',
				src: 'snippet?a=responsive',
				title: 'Mobile frame',
				style: "min-height:100%;width:100%;border:none;"
			};
			fr = $('<iframe>').addattrlist(attr);
			$('#dsn_5').append( fr );
		}
		
		$('#dsn-301').addEventListener('click', selectMobile);
		$('#dsn-302').addEventListener('click', selectTablet);
		$('#dsn-303').addEventListener('click', selectMonitor);
		$('#dsn-304').addEventListener('change', selectCustom);
		$('#dsn-306').addEventListener('input', watchColorPicker);
		$('#dsn-305').addEventListener('change', watchColorField);
		$('#dsn-334').addEventListener('click', reponsiveMode);
		$('#dsn-333').addEventListener('click', () => { let a = new fullScreen(); a.toggle(); });
		
		snb && ( snb.style.width = nowWidth );
	}
	
	/**
	 * 
	 */
	function snipetSetings() {
		
		// Delete the snipet from working enviroment to create a clean slate
		function clearWorkspace() {
			!settings.safeContainer ? $('#dsn_5').empty() : $('.dsn-safe-container').empty();
			undefineEl();
			//fieldDisable(false);
			//emptyFieldsValue();
		}
		
		// Will lock or unlock the fields using the disabled attributes from the fields
		// Fields are disabled by default to not accidentaly overwrite before saving
		function fieldDisable() {
			let bool;
			let fields = [ "dsn-314", "dsn-315", "dsn-316", "dsn-317"], field;
			for( let i =0; i < fields.length; i++) {
				field = $('#' + fields[i]);
				if(arguments.length > 0) {
					bool = arguments[0];
					!bool && field.hasAttribute('disabled') && field.removeAttribute('disabled');
					bool && !field.hasAttribute('disabled') && field.addattr('disabled');
				}else{
					field.hasAttribute('disabled') ? field.removeAttribute('disabled') : field.addattr('disabled');
				}
			}
		}
		
		// Fast way to clear the fields
		function emptyFieldsValue() {
			let fields = [ "dsn-314", "dsn-315", "dsn-316", "dsn-317"], field;
			
			function fieldempty() {
				for( let i =0; i < fields.length; i++) {
					field = $('#' + fields[i]);
					!field.hasAttribute('disabled') && ( field.value = "" );
				}
			}
			if ( this && $(this).attr('id') === 'dsn-325') {
				$n.p = {}  && fieldDisable(false); 
				fieldempty();
				fieldDisable();
				pushNotes('Downloaded snipet deleted.');
			} else { 
				fieldempty() 
			}
		}
		
		// Used when switching tabs in snippet settings
		// It refers when we are on snipet tab
		function refreshFieldsValue() {
			$('#dsn-314').value = $n.c.name; 
			$('#dsn-315').value = $n.c.type;
			$('#dsn-316').value = $n.c.status;
			$('#dsn-317').value = "";
			fieldDisable(true);
		}
		
		// Used when switching tabs in snippet settings
		function refreshDownloadFieldsValue() {
			isSet($n.p) && isSet($n.p.name) ? $('#dsn-314').value = $n.p.name : $('#dsn-314').value = '';
			isSet($n.p) && isSet($n.p.type) ? $('#dsn-315').value = $n.p.type : $('#dsn-315').value = '' ;
			isSet($n.p) && isSet($n.p.status ) ? $('#dsn-316').value = $n.p.status : $('#dsn-316').value = ''; 
			$('#dsn-317').value = "";
			fieldDisable(true);
		}
		
		function createNewProject(){
			let project = {};
			project.Name = $('#dsn-320').value.toLowerCase();
			
			if(empty(project.Name)) {
				pushNotes('Project name is empty...');
				return;
			}
			
			function handle (r) {
				let res = r;
				if(isObj(res) && isSet(res.lastId)){
					pushNotes('New project created : ' + project.Name);
					fillSelectFields('load-projects-name', '#dsn-319', {v:$p.name, t:$p.name} );
				}else if(isStr(res)){
					pushNotes(res);
				}
			}
			$.send_json({
				data : project,
				url: 'snippet?a=add-project',
				callback: handle
			});
		}
		
		function setProject () {
			let obj = {}, oldName = $p.name, pos; 
			$p.name = $('#dsn-319').value;
			obj.name = $p.name;
			//getDefaultSnippet();
			function getProjectData(r){
				if(isSet(r) && r !== null) {
					settings.elChangeLock = false;
					simulateEvent('click', $('#dsn-326'));
					$p.maxid = r.maxid;
					$p.maxclass = r.maxclass;
					$p.id = r.id;
					pushProject($p.name);
					// set time out to delay the notification :))
					//getDefaultSnippet();
					//searchSnippets();
					setTimeout( () => { searchSnippets(); pushNotes('Switched to project: ' + $p.name) }, 50);
				}
			}
			
			$.send_json({
				url: 'snippet?a=get-project-data',
				data: obj,
				callback: getProjectData
			});
		}
		
		function createNewSnipet() {
			let snipet = {}, info,
				alertText = "To create a new snippet, the following fields can't be empty :" ;
				
			function createDummySnipet() {
				let catchel;
				clearWorkspace();
				catchel = $('<p>', 'I am the catcher in the rye !');
				!settings.safeContainer ? $('#dsn_5').append(catchel) : $('.dsn-safe-container').append(catchel);
				!settings.safeContainer ? snipet.body =  $.snipetHandler.gett( $('#dsn_5'), true) : snipet.body =  $.snipetHandler.gett( $( '.dsn-safe-container' ), true);
			}
			
			function handleResponse(r){
				let res = r,
					backup = $n.c;
				if(isObj(res) && isSet(res.lastId) ){
					$n.c.id = res.lastId;
					// If res.lastId it means we get the last inserted id 
					// Setting up the current snipet
					$n.c.name = snipet.name;
					$n.c.type = snipet.type;
					$n.c.status = snipet.status
					$n.c.body = snipet.body;
					pushNotes('New snippet created : ' + snipet.name);
					fieldDisable(true);
					pushName(snipet.name);
				}else if(isStr(res)){
					pushNotes(res);
				}
			}
			// Assign values to snipet object before is sent to the server
			snipet.body = {};
			snipet.project = $p.name;
			snipet.name = $('#dsn-314').value;
			snipet.type = $('#dsn-315').value;
			snipet.status = $('#dsn-316').value;
			snipet.css = "";
			
			info = "\nName : " + snipet.name + "\nType : " + snipet.type + "\nStatus : " +snipet.status;
			
			// If the fields are not empty, data can be sent
			if(!empty(snipet.name) && !empty(snipet.type) && !empty(snipet.status)) {
				createDummySnipet();
				$.send_json({
					url: 'snippet?a=add-snippet',
					data: snipet,
					callback: handleResponse
				});
			}else{
				alert( alertText + info );
			}
		}
		
		// this can store just the body of the snippet
		function get_Store_Snipet(snipetName){
			function homeSnipet(name, snipet_obj){
				snipets[ name ]  = snipet_obj;
			}
			$.snipetHandler.get_http(
				{
					url: 'snipet?a=get-snipet&name=' + snipetName,
					name: snipetName,
					fn: homeSnipet
					//el:$('#dsn_5')
				}
			);
		}
		
		// Called when switching projects in setProject()
		function getDefaultSnippet(){
			let backup = $n.c, obj ={name:'default', project:$p.name };
			
			function down(t) {
				if( isSet(t) ){
					if( isObj(t) && t !== null) {
						$n.c = t;
						pushNotes('Download finished for snippet: ' + obj.name);
						refreshFieldsValue();
						pushName(obj.name);
						clearWorkspace();
						( isObj( $n.c.body )  && $n.c.body !== null ) && ( $.snipetHandler.sett.call({ obj:$n.c.body, recipient:$('.dsn-body')}) );
					}
					if( t === null || t === undefined) { 
						pushNotes("Couldn't find snippet with name: " + obj.name);
						$n.c = backup;
					}
				}
				fieldDisable(true);
			}
			$.send_json( { url: 'snippet?a=get-snippet-by-name',  data: obj, callback: down } );
		}
		
		// Function responsible to download snippets by name
		function downloadSnipet(){
			let backup = $n.p, name, obj = {}, a = null;
			this.id === 'dsn-309' ? obj.name = $('#dsn-314').value : obj.name = this.getAttribute('data-snippet-name');
			
			obj.project = $('#dsn-317').value || empty(obj.project) && ( obj.project = $p.name );
			this.id = 'd-2-7' && ( obj.project = $('#d-2-2').value ) ;
			function down(t) {
				if( isSet(t) ){
					$n.p = {};
					if( isObj(t) && t.body !== null) {
						$n.p = t;
						pushNotes('Download finished for snippet: ' + obj.name);
						refreshDownloadFieldsValue();
					}
					if( t.body === null || t === undefined) { 
						pushNotes("Couldn't find snippet with name: " + obj.name);
						$n.p = backup;
					}
				}
				fieldDisable(true);
			}
			empty(obj.name) && pushNotes("The snippet name missing for download");
			empty(obj.project) && pushNotes("The project name missing for download");
			! empty(obj.name) && ! empty(obj.project) && $.send_json( { url: 'snippet?a=get-snippet-by-name',  data: obj, callback: down } );
		}
		$fn.menu.downloadSnipet = downloadSnipet;
		$fn.body.downloadSnipet = downloadSnipet;
		
		// Later implementation of updating the stashes with the new name should be done
		// Or an id based association should be implemented
		function saveSnippet () {
			// If locked we can't modify it, to protect the ones used with js scripts
			// However the lock can be opened from snippet settings 
			if( $n.c.locked === 'y') {
				pushNotes("Can't save this snippet. It's locked. Read snippet description why...");
				return;
			}
			let s = {}, namev = $('#dsn-314').value, typev = $('#dsn-315').value, statusv = $('#dsn-316').value, tEl;
			if($('#dsn-334').classList.contains('active')) { 
				if( pushNotes("Responsive mode activated. Can't save...")) {
					return;
				}
			}
			if(!isSet(el)) { pushNotes("You must have an active element to save. Can't save..."); return; }
			// Updating the current snippet object if name, type or status are desired for change
			!empty(namev) && ( $n.c.name = namev );
			!empty(typev) && ( $n.c.type = typev );
			!empty(statusv) && ( $n.c.status = statusv);
			
			// Removing dsn classes
			function hideClassesRemove() {
				for(let i = 0; i < hideClasses.length; i++) {
					this.classList.contains(hideClasses[i]) && this.classList.remove(hideClasses[i]);
				}
				this.hasAttribute('class') && this.getAttribute('class') === '' && this.removeAttribute('class'); 
				this.childElementCount > 0 && $(this).walkChild( hideClassesRemove );
			}
			!settings.safeContainer ? $('#dsn_5').walkChild( hideClassesRemove ) : $('.dsn-safe-container').walkChild( hideClassesRemove );
			tEl = el;
			undefineEl();
			!settings.safeContainer ? $n.c.body = $.snipetHandler.gett( $( '#dsn_5' ), true) : $n.c.body = $.snipetHandler.gett( $('.dsn-safe-container'), true);
			s = $n.c;
			s.project = $p.name;
			
			function down(r) {
				if(isSet(r.lastId)) pushNotes('Snippet saved...');
			}
			
			$.send_json( { url: 'snippet?a=save-snippet',  data: s, callback: down } );
			el = tEl;
			el.classList.add('dsn-active');
		}
		
		// Used in applyExtension and editDownload to add the css files from snippet settings automatically to a style tag
		function applyCss(obj) {
			let cssf, incl = [], index = 0, child = 0;
			$('head').walkChild(
				function(){
					this.hasAttribute('data-filepath') && incl.push( this.getAttribute('data-filepath') );
					this.nodeName.toLowerCase() === 'style' && ( child = index);
					index++;
				}
			);
			cssf = obj.cssf.split("\n");
			for (let i = 0; i < cssf.length; i++){
				cssf[i].trim(' ');
				let ch = cssf[i].split('');
				ch[0] !== '/' && ( cssf[i] = '/' + cssf[i]);
				if( incl.includes(cssf[i]) === false) {
					$.send_json({
						data: { addcssfile: cssf[i] },
						url: 'snippet?a=load-css-file',
						callback: function(rs){
							$('head').children[child].insertAdjacentElement('beforebegin', $('<style>', rs).addattr('data-filepath', cssf[i]) );
						},
						log: 'Edit download snippet css loading failed.'
					});
				}
			}
		}
		
		function applyExtension () {
			let parentel, pos, newel;
			if( $(this).attr('id') === "dsn-323" ) {
				settings.elChangeLock && pushNotes('Close the opened admin page to get to the live snippet.');
				if (isSet($n.p) && $n.p.body !== undefined && $n.p.body.size() > 0 && !settings.elChangeLock) {
					isSet(el) && el instanceof HTMLElement ? 
					( parentel = el) && ( pos = settings.layoutPosition ) :  
					( parentel = snb ) && (pos = 'beforeend');
					$.snipetHandler.sett.call( { obj: $n.p.body, recipient: parentel, position: pos});
					$n.p.cssf !== '' && applyCss($n.p);
				}
			}
		}
		
		function editDownload() {
			settings.elChangeLock && pushNotes('Close the opened admin page to get to the live snippet.');
			
			if ( isSet($n.p) && $n.p.body !== undefined && $n.p.body.size() > 0 && !settings.elChangeLock) {
				saveSnippet();
				setTimeout(	function() {
					clearWorkspace();
					!settings.safeContainer ? $.snipetHandler.sett.call( { obj: $n.p.body, recipient: snb }) : $.snipetHandler.sett.call( { obj: $n.p.body, recipient: $('.dsn-safe-container') });
					$n.c = $n.p;
					$n.c.cssf !== '' && applyCss($n.c);
					pushName($n.c.name);
					pushNotes($n.c.name + ' snippet ready...');
					fieldDisable(true);
				},50);
			}
		}
		
		// Switch what and when to fill in the fields under snippet tabs
		function snipetTabBehavior (){
			let att;
			att = this.getAttribute('id');
			if(att === "dsn-326" || att === "dsn-328" ) {
				$('#dsn-317').stepup(2).style.visibility = "hidden";
				att === "dsn-326" ? refreshFieldsValue() : null;
			}else {
				$('#dsn-317').stepup(2).style.visibility = "visible";
				refreshDownloadFieldsValue();
			}
			fieldDisable(true);
		}
		
		function fieldLockListener() {
			for(let i = 0; i < this.length; i++) {
				this[i].addEventListener('click', function(){ fieldDisable()});
			}
		}
		
		function fieldEmptyListener() {
			for(let i = 0; i < this.length; i++) {
				this[i].addEventListener('click', emptyFieldsValue);
			}
		}
		
		$fn.menu.generateIdAndClass = function () {
			let field, link, val;
			this.id === 'dsn-346' && ( field = $('#dsn_a_0') ) && (link = 'get-auto-id') && (val = 'newid' );
			this.id === 'dsn-345' && ( field = $('#dsn_96') ) && (link = 'get-auto-class') && (val = 'newclass');
			if(!el) {
				pushNotes('NO element selected. Can\'t generate new id');
				return;
			}
			$.send_json({
				data: { project: $p.name },
				url: 'snippet?a=' + link,
				callback: function (r){
					if(r[val]){
						field.value = 'd-' + $p.id + '-' + r[val];
						simulateEvent('change', field);
					}
				}
			});
		}
		
		function updateSnippetSettings() {
			let selfCaller = this, snippmenu = false, fieldIds, send = { project : $p.name, id : $n.c.id }, search = {}, over;
			fieldIds = [ 'd-2-8', 'd-2-9', 'd-2-A', 'd-2-B', 'd-2-J', 'd-2-C', 'd-2-D', 'd-2-E', 'd-2-F', 'd-2-G', 'd-2-H', 'd-2-O', 'd-2-N', 'd-2-P' ];
			
			this.id === 'dsn-344' && ( snippmenu = true );
			
			// Return void from snippet settings if there is no current snippet set
			if(snippmenu && !$n.c.name) {
				pushNotes('No snippet set for working enviroment. Exiting settings...  ');
				return;
			}
			// From download search list menu altering the default values
			if(!snippmenu && $('#d-2-2') ){
				send.project = $('#d-2-2').value;
				send.name = selfCaller.getAttribute('data-snippet-name');
				over = $.overlay({el: snb, elc:'overlay-body', elb: 'overlaybtn'});
			}
						
			// Sending data to server and updating current snippet settings
			// $fn.body get this function for the save button
			$fn.body.doUpdateSnippetSettings = function () {
				send = getFormValuesById(fieldIds, send);
				send.newgroup !== '' && ( send.sgroup = send.newgroup );
				send.newsubcat !== '' && ( send.subcat = send.newsubcat );
				send.newsubcat === '' && send.subcat === '' ? send.subcat = null : null;
				$.send_json ({
					data : send,
					url : 'snippet?a=update-snippet-settings',
					callback : function(r) {
						// Called from snippet settings menu
						if( isSet(r.lastId) && r.lastId === '0' && snippmenu ) {
							$.send_json({ 
								data : { project: $p.name, name : send.name},
								url : 'snippet?a=get-snippet-by-name',
								callback : function(re) {
									if(isSet(re)) {
										for( let prop in re) {
											prop !== ('body' || 'id') && ( $n.c[prop] = re[prop] );
										}
										fieldDisable();
										refreshFieldsValue();
										pushNotes('Snippet: ' + send.name + ' - settings updated');
										pushName(send.name);
										$d.staticBody( () => {}, () => {});
									}
								},
								log : 'Error ocured assigning the new snippet settings update'
							});
						// Called from download search list menu
						} else if( isSet(r.lastId) && r.lastId === '0' && !snippmenu ) {
							pushNotes('Snippet: ' + search.name + ' - settings updated');
							// Upadating the name shown in the search list and on the caller button
							selfCaller.parentElement.previousElementSibling.textContent = send.name;
							$(selfCaller).addattr('data-snippet-name', send.name);
							search = {};
							snb.removeChild(over);
						} else {
							pushNotes('Snippet settings update failed');
						}
					}
				});
			}
			// Called for snippet settings menu
			function loadStaticBody(r) {
				const elem = $.snipetHandler.sett.call({ obj: r, recipient: snb, position: 'beforeend' });
				elem && fillSelectFields('load-snippet-type', '#d-2-9', { v: $n.c.type, send:{ project: $p.name} });
				elem && fillSelectFields('load-snippet-status', '#d-2-A', { v: $n.c.status, send:{ project: $p.name} });
				elem && fillSelectFields('load-snippet-group', '#d-2-B', { v: $n.c.sgroup, send:{ project: $p.name}, empty:{ v:'', t:'Delete from group'} });
				( elem && $n.c.sgroup ) && fillSelectFields('load-snippet-subcat', '#d-2-O', { v: $n.c.subcat, send:{ project: $p.name, sgroup: $n.c.sgroup }, empty:{ v:'', t:'Delete from subcategory'} });
				elem && setFormValuesById( fieldIds, $n.c );
				return elem;
				
			}
			// Called for download search list menu
			function loadSearchBody(r) {
				const elem = $.snipetHandler.sett.call({ obj: r, recipient: over, position: 'beforeend' });
				$.send_json({
					data : { project : send.project, name : send.name },
					url : 'snippet?a=get-snippet-by-name',
					callback : function(r) {
						if(r){
							for (let prop in r) {
								if(r.hasOwnProperty(prop)) {
									prop !== 'body' && ( search[prop] = r[prop] );
									prop === 'id' && (send.id = r[prop]);
								}
							}
							elem && fillSelectFields('load-snippet-type', '#d-2-9', { v: search.type, send:{ project: send.project} });
							elem && fillSelectFields('load-snippet-status', '#d-2-A', { v: search.status, send:{ project: send.project} });
							elem && fillSelectFields('load-snippet-group', '#d-2-B', { v: search.sgroup, send:{ project: send.project}, empty:{ v:'', t:'Delete from group'} });
							( elem && search.sgroup ) && fillSelectFields('load-snippet-subcat', '#d-2-O', { v: search.subcat, send:{ project: send.project, sgroup: search.sgroup}, empty:{ v:'', t:'Delete from subcategory'} });
							elem && setFormValuesById( fieldIds, search );
						}
					}
				});
				return elem;
			}
			
			// Getting the snippet body
			function getSnippetBody() {
				$.send_json({
					data : { name :'snippet settings', project: 'dard'},
					url : 'snippet?a=get-snippet-body',
					callback: function(r) {
						if( isSet(r) ){
							snippmenu && loadStaticBody(r) ;
							!snippmenu && loadSearchBody(r) ;
						}
					},
					log: 'snippet settings page body loading failed'
				});
			}
			snippmenu ? $d.staticBody( getSnippetBody, () => {} ) : getSnippetBody();
		}
		$fn.menu.updateSnippetSettings = updateSnippetSettings;
		$fn.body.updateSnippetSettings = updateSnippetSettings;
		
		// Preview the snippet from search rows
		function previewSnippet() {
			let send = {}, over, contattr, contw, contb, frameattr, cssf = [], frame, htm, head, body;
			send.project = $('#d-2-2').value;                                               // project name from select field value
			send.name = this.getAttribute('data-snippet-name');                             // snipet name from button data atribute
			
			// Defining iframe main elements
			function initFrame(r) {
				r.background !== '' ? contb = r.background : contb = '#ffffff';             // Default background is white
				r.width !== '' ? contw = r.width : contw = '100%';                          // Default width is 100%
				contattr = {
					id : 'dsn-preview-content',
					class: "section group c-box",
					style : "width:" + contw + ";background:" + contb + ";height: 100%;"    // Future: consider a height from snippet settings
				}
				// Initial elements and the frame attributes
				htm = $('<html>');
				head = $('<head>')
					.append( $('<link>').addattrlist( {rel:"stylesheet", type:"text/css", href:"/src/css/reset.css"} ) )
					.append( $('<link>').addattrlist( {rel:"stylesheet", type:"text/css", href:"/src/css/ui.css"} ) );
				body = $('<body>').addattrlist(contattr);
				$.snipetHandler.sett.call( { obj: r.body, recipient: body, position: 'beforeend' });
				frameattr = {
					id : 'dsn-preview',
					style: "display:block;height:85%;max-width:90%;width:calc(" + contw + " + 4rem);margin: 5rem auto 1rem;padding:2rem;overflow:auto;border:none;background: rgba(0,0,0,.2);",
					sandbox : "allow-same-origin"
				};
			}
			
			// Loading the css files 
			function getStyle(resstyle) {
				cssf = resstyle.split("\n");
				for (let i = 0; i < cssf.length; i++){
					cssf[i].trim(' ');
					let ch = cssf[i].split('');
					ch[0] !== '/' && ( cssf[i] = '/' + cssf[i]);
					$.send_json({
						data: { addcssfile: cssf[i] },
						url: 'snippet?a=load-css-file',
						callback: function(rs){
							head && head.append($('<style>', rs));
							// At last css file loaded displaying the frame
							if( i == cssf.length -1 ) {
								displayFrame();
							}
						}
					});
				}
			}
			
			// Displaying the frame 
			function displayFrame() {
				htm.append(head).append(body);
				frameattr.srcdoc = htm.outerHTML;
				frame = $('<iframe>').addattrlist(frameattr);
				over = $.overlay({el: snb, elc:'overlay-body', elb: 'overlaybtn'});
				over.classList.add('section');over.classList.add('group');
				$(over).append(frame);
			}
			
			$.send_json({
				data : { project : send.project, name : send.name },
				url : 'snippet?a=get-snippet-by-name',
				callback : function (r) {
					if(isSet(r)) {
						initFrame(r);
						r.cssf !== '' ? getStyle(r.cssf) : displayFrame();
					}
				},
				log : 'Snippet preview failed.'
			});
		}
		$fn.body.previewSnippet = previewSnippet;
		
		$('.dsn-307', fieldLockListener );
		$('.dsn-308', fieldEmptyListener );
		$('#dsn-309').addEventListener('click', $fn.menu.downloadSnipet);
		$('#dsn-310').addEventListener('click', saveSnippet);
		$('#dsn-311').addEventListener('click', createNewSnipet);
		$('#dsn-312').addEventListener('click', editDownload);
		$('#dsn-313').addEventListener('click', clearWorkspace);
		$('#dsn-322').addEventListener('click', createNewProject);
		$('#dsn-321').addEventListener('click', setProject);
		$('#dsn-323').addEventListener('click', applyExtension);
		fillSelectFields('load-projects-name', '#dsn-319', {v:'sandbox',t:'sandbox'} );
		fillSelectFields('load-projects-name', '#dsn-317', {v:'',t:''} );
		fillSelectFields('load-snippet-type', '#dsn-315');
		fillSelectFields('load-snippet-status', '#dsn-316');
		$.tabs( { tab: 'dsn-snipet-tab',  content: 'dsn-snipet-tab-content', active:'active', event:'click', default:0, callback:snipetTabBehavior } );
	}
	
	/**
	 * These are meant to be larger more complex functionalities, for various settings 
	 * Probably in future will be moved to an external file
	 */
	 function generalSettings() {
	    let highlight;
	    
	    // highlight all elemnts
	    function highlightAll() {
	        let bool = false, self = {};
	        function addDottedBorder(){
	            !bool && this.classList.add('dsn-highlighted');
	            bool && this.classList.remove('dsn-highlighted') && (this.classList.length === 0 && this.removeAttribute('class') );
	            if(this.childElementCount > 0)
	                $(this).walkChild(addDottedBorder);
	        }
	        self.run = function() {
	            !settings.safeContainer ? $('#dsn_5').walkChild( addDottedBorder) : $('.dsn-safe-container').walkChild( addDottedBorder);
	            bool ? bool = false : bool = true ;
	        };
	        
	        self.get = () => {return bool;};
	        return self;
	    }
	    $fn.menu.highlightAll = new highlightAll();

		function addCssFiles() {
			let self={}, files, pos, path = '/src/css/', existing = [], parent = $('#dsn_5'), newpath, open = false;
			//h.append($('<link>').addattr('rel', 'stylesheet').addattr('type', 'text/css').addattr('href', '/src/css/dsn/dard.css'));
			
			function getCssFiles() {
				$.get_json({
					url: 'snippet?a=list_css_files',
					callback: function(r) {
						if(r) {
							files = r;
							checkExisting();
							setPosition();
							printTop();
							listExisting();
							build(files, path);
						}
					},
					log: 'No css file list'
				});
			}
			
			function setPosition() {
				$('head').walkChild(
					function(){
						if(this.nodeName.toLowerCase() === 'style'){
							pos = this;
							return;
						}
				});
			}
			
			function checkExisting(){
				let i = 0;
				existing = [];
				$('head').walkChild(
					function(){
						if(this.nodeName.toLowerCase() === 'link' && this.hasAttribute('type')){
							if(this.getAttribute('type') === "text/css"){
								existing[i] = this.getAttribute('href')
								i++;
							}
						}
						if(this.nodeName.toLowerCase() === 'style' && this.hasAttribute('data-filepath')){
							existing[i] = this.getAttribute('data-filepath')
							i++;
						}
					});
			}
			
			function printTop(){
				let top = $('<div>', 'LOADED CSS FILES').addattrlist({'class':'span-70 c-box dsn-add-list-title'}),
					f = $('<div>', "FOLDER : " +   path).addattrlist({'class':'span-70 c-box dsn-add-list-title'});
				parent.append(top).append(f);
			}
			
			function listExisting() {
				let ex = $('<div>').addattrlist({'id':'dsn-loaded-css','class':'span-70 c-box space-20'}),
					sp;
				if($('#dsn-loaded-css')) {
					$('#dsn-loaded-css').parentElement.removeChild($('#dsn-loaded-css'));
				}
				for(let i =0; i < existing.length; i++) {
					sp = $('<span>', existing[i]).addattrlist({'class':'col-f dsn-add-list-existing'});
					ex.append(sp);
				}
				$('.dsn-add-list-title').insertAdjacentElement("afterend", ex);
				ex.style.height = ex.scrollHeight + "px";
			}
			
			function addFile () {
				let style, rbt, filepath, elem = this;
				filepath = this.parentElement.parentElement.children[0].getAttribute('data-filepath');
				$.send_json({
					data : { addcssfile: filepath},
					url : 'snippet?a=load-css-file',
					callback : function (r) {
						style = $('<style>', r ).addattrlist({'data-filepath': filepath});
						rbt = $('<button>').addattrlist({'class':'span-100 box dsn-ctl-btn-l dsn-24-m-rem-bt'});
						pos.insertAdjacentElement("beforebegin", style)
						elem.parentElement.previousElementSibling.appendChild(rbt);
						elem.removeEventListener('click', addFile);
						elem.parentElement.removeChild(elem);
						rbt.addEventListener('click', remFile);
						existing.push(filepath);
						listExisting();
					},
					log : 'Bad json css file read...'
				});
			}
			
			function remFile() {
				let link, abt, rempath;
				abt = $('<button>').addattrlist({'class':'span-100 box dsn-ctl-btn-l dsn-24-m-add-bt'});
				rempath = this.parentElement.previousElementSibling.getAttribute('data-filepath');
				$('head').walkChild(
					function(){
						if(this.nodeName.toLowerCase() === 'style' && this.hasAttribute('data-filepath')){
							if(this.getAttribute('data-filepath') === rempath) {
								this.parentElement.removeChild(this);
							}
						}
					}
				);
				$.send_json({
					data : { removecssfile: rempath},
					url : 'snippet?a=load-css-file',
					callback : function (r) {
						;
					}
				});
				this.parentElement.nextElementSibling.appendChild(abt);
				abt.addEventListener('click', addFile);
				this.removeEventListener('click', remFile);
				this.parentElement.removeChild(this);
				arrayRemove(existing, rempath);
				listExisting();
			}
			
			function build(o, path){
				let ul = $('<ul>').addattrlist({'class':'span-70 c-box space-20 dsn-add-list'}), i, ob, li, f, r, a, rbt, abt;
				
				if(o.keyIn('files')){
					for(i = 0; i < o.files.length ; i++){
						newpath = '';
						newpath = path + o.files[i];
						li = $('<li>').addattrlist({'class':'span-90 c-box'});
						f = $('<span>', o.files[i]).addattrlist({'class':'span-80 col-f left', 'data-filepath': newpath});
						r = $('<span>').addattrlist({'class':'span-10 col-f left', 'style':'height: 2rem'});
						a = $('<span>').addattrlist({'class':'span-10 col-f left', 'style':'height: 2rem'});
						if(existing.includes(newpath)){
							rbt = $('<button>').addattrlist({'class':'span-100 box dsn-ctl-btn-l dsn-24-m-rem-bt'});
							r.append(rbt);
							if(newpath !== "/src/css/dsn.css" && newpath !== "/src/css/reset.css" && newpath !== "/src/css/ui.css") {
								rbt.addEventListener('click', remFile);
							}
						} else {
							abt = $('<button>').addattrlist({'class':'span-100 box dsn-ctl-btn-l dsn-24-m-add-bt'});
							a.append(abt);
							abt.addEventListener('click', addFile);
						}
						ul.append(li.append(f).append(r).append(a));
					}
					parent.append(ul);
				}
				if(o.keyIn('dir')){
					ob = o.dir;
					// if the folder has files in it will append a div with the folder name
					for(let prop in ob) {
						if(ob.hasOwnProperty(prop)){
							newpath = '';
							newpath = path + prop + '/';
							if(ob[prop].keyIn('files'))
								parent.append($('<div>', 'FOLDER : ' + path + prop).addattrlist({'class':'span-70 c-box dsn-add-list-title'}));
							build(ob[prop], newpath);
						}
					}
				}
				// If the folder has multiple directories in it 
				if(!o.keyIn('dir') && !o.keyIn('files') && isArray(o)) {
					for (let j = 0; j < o.length; j++){
						o[j].dir.keyIn('sassets') && (path = '/');
						build(o[j], path);
					}
				}
			}
			
			self.add = function(){
				$d.staticBody(getCssFiles, () => {});
			}
			
			return self;
		}
		
		function safeBoard () {
			if(this.classList.contains('active')) {
				if(confirm('Would like to remove the safeboard?')) {
					settings.whiteBoard.classList.remove('dsn-safe-container')
					snb.classList.add('dsn-safe-container');
					undefineEl();
					settings.whiteBoard = undefined;
					this.classList.toggle('active');
					settings.safeContainer = false;
					pushNotes('Safe board disabled...');
				}
			} else {
				if(!el){
					pushNotes('No active element for a safe board ');
					return;
				}
				snb.classList.remove('dsn-safe-container');
				$(el).addclass('dsn-safe-container');
				undefineEl();
				settings.whiteBoard = $('.dsn-safe-container');
				this.classList.toggle('active');
				pushNotes('Safe board activated...');
				settings.safeContainer = true;
				pushNotes('Safe board activated...');
			}
		};
		$fn.menu.safeBoard = safeBoard;
		
	    let cssfiles = new addCssFiles();
	    $('#dsn-335').addEventListener('click', () => {$fn.menu.highlightAll.run() } );
	    $('#dsn-336').addEventListener('click', () => { cssfiles.add() } );
	 }
	
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
				e_attr: { class:'dsn-el-property', "data-dsntextid": "0" },
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
				e_attr: { for:"", "data-dsntextid": "0" },
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
	 *  Seting up the event listener on the snipet container
	 *
	 */
	function snippetListener(){
		let self = {}, snipetClickable, snipetMoveout, snipetMoveover, editable, defineEditable;
		
		defineEditable = function(ev) {
			let p ;
			if (settings.safeContainer && ev && ev.parentElement && !editable) {
				p = ev.parentElement;
				editable = false;
				if( !p.classList.contains('dsn-safe-container') ) {
					if(p.parentElement) {
						defineEditable(p)
					}
				} else if ( p.classList.contains('dsn-safe-container')) {
					editable = true;
					return;
				}
			} else if(ev && !settings.safeContainer){ 
				editable = true; 
			}
		}
		
		snipetMoveover = function(ev){
			let e = ev.target, ep = ev.target.parentElement;
			if( !settings.elChangeLock ){
				defineEditable(e);
				if(editable) {
					e.classList.toggle('dsn-hover');
					ep.classList.toggle('dsn-ep-hover');
				}
			}
		}
		
		snipetMoveout = function(ev){
			let e = ev.target, ep = ev.target.parentElement;
			if(!settings.elChangeLock && editable){
				if(e && e.classList.contains("dsn-hover")){
					e.classList.toggle('dsn-hover');
					ep.classList.contains("dsn-ep-hover") && ep.classList.toggle('dsn-ep-hover');
					e.hasAttribute('class') && ( e.getAttribute('class') === '' && e.removeAttribute('class'));
				}
				editable = false;
			}
		}
		
		snipetClickable = function(ev){
			let e = ev.target, fname;
			if(!settings.elChangeLock){
				if(editable) {
					( e.id !== 'dsn_5' )  && $d.change.call(e);
				}
				ev.preventDefault();
			} else {
				settings.targetElement = e;
				try{
					fname = $(e).attr('data-dsnfname');
					$fn.body.hasOwnProperty(fname) && $fn.body[fname].call(e);
				} catch(error){
					console.log(error);
				}
			}
	}
		
		self.add = function() {
			snb.addEventListener('mouseover', snipetMoveover);
			snb.addEventListener('mouseout', snipetMoveout);
			snb.addEventListener('click', snipetClickable);
		}
		
		self.remove = function() {
			snb.removeEventListener('mouseover', snipetMoveover);
			snb.removeEventListener('mouseout', snipetMoveout);
			snb.removeEventListener('click', snipetClickable);
		}
		
		return self;
	};
	
	/**
	*************************************
	* Changing the current element
	* @returns the new element
	*/
	 $d.change = function(){
		// If this is the snipet container exit from function
		if($(this).attr('id') !== 'dsn_5' && !$(this).hasAttribute('dsn-snippet-container') ) {
			let noneditable = ['html', 'meta', 'link', 'form', 'input', 'select', 'textarea', 'br', 'hr', 'ul', 'ol', 'dl', 'img', 'embed', 'bgsound', 'base', 'col', 'source', 'fieldset'];
			// remove the active class from current element
			if(el && el.classList.contains("dsn-active")){
				el.classList.toggle('dsn-active');
				el.hasAttribute('class') && ( el.getAttribute('class') === '' && el.removeAttribute('class'));
				el.hasAttribute('contenteditable') && el.removeAttribute('contenteditable');
			}
		
			el = this;
			elName = el.nodeName.toLowerCase();
			!noneditable.includes(el.nodeName.toLowerCase()) ? el.setAttribute('contenteditable', 'true') : el.setAttribute('contenteditable', 'false');
			ep = el.parentElement; 
			// Add the active class on the new element
			el.classList ? el.classList.add('dsn-active') : el.setAttribute('class', 'dsn-active'); 
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
			el && $i.sett('dsn_90', elName);
			el && $i.sett('dsn_91', myindex(el));
			ep && $i.sett('dsn_92', ep.children.length - 1);
			el && $i.sett('dsn_93', el.children.length);
		}
		return el;
	};
	
	/** Menu Items
	****************************************
	*/
	
	 /** Move to the first element child */
	 
	$d.goToFirstKid = function(){ if(el && el.firstElementChild) $d.change.call(el.firstElementChild); };
	
	/** Move to parent element. Stops before exiting the snipet container */
	
	$d.goToParent = function(){ if(el && el.parentElement && !el.parentElement.classList.contains('dsn-safe-container') ) $d.change.call(el.parentElement); };
	
	/** Move to the younger sibling DOWN :) */
	
	$d.goToYoungerBrother = function( ){ if(el && el.nextElementSibling && !el.nextElementSibling.classList.contains('dsn-safe-container')) $d.change.call(el.nextElementSibling); };
	
	/** Move to the older sibling UP :) */
	
	$d.goToOlderBrother = function(){ if(el && el.previousElementSibling && !el.previousElementSibling.classList.contains('dsn-safe-container')) $d.change.call(el.previousElementSibling); };
	
	/**
	*************************************
	* Removing the current element
	* Set the curent element to the parent element, if not exist try the first child of the snipet container
	* Removing the event listeners too.
	* @returns the new element
	*/
	$d.elDroped = function(){
		let nextel;
		if(confirm('Would you, Realy want to delete this element ?') == true ){
			if( el ) {
				el.parentElement.removeChild(el);
				if(!ep.classList.contains('dsn-body') ) {
					nextel = ep;
				}else {
					snb.firstElementChild ? nextel = snb.firstElementChild : nextel = undefined;
				} 
				nextel !== undefined ? $d.change.call(nextel) : el = undefined;
			}
		}
	};
	
	/**
	 *  The first primitive copy function 
	 *  
	 */
	 
	$d.copyEl = function(){
		let copy, self = {}, newEl = {}, copyButton, pasteButton, e;
		
		self.save = function(){
			if($('#dsn-334').classList.contains('active')) { 
				pushNotes("Responsive mode activated. Can't use copy...");
				return;
			}
			el.classList.contains("dsn-active") && el.classList.toggle('dsn-active');
			el.classList.contains("dsn-hover") && el.classList.toggle('dsn-hover');
			copy = $.snipetHandler.gett(el);
			el.classList.toggle("dsn-active");
			pushNotes("Element copied...");
		};
		
		self.paste = function(obj) {
			//$.snipetHandler.sett.call();
		}
		
		copyButton = document.getElementById("dsn_106");
		copyButton.addEventListener('click', function(){self.save();});
		
		pasteButton = document.getElementById("dsn_100");
		pasteButton.addEventListener('click', function(){
			if($('#dsn-334').classList.contains('active')) { 
				pushNotes("Responsive mode activated. Can't use paste...");
				return;
			}
			if(copy !== undefined ) {
				newEl.obj = copy;
				newEl.position = settings.layoutPosition ;   //  default in to the current element at the end
				 // default recipient is the curently selected element 
				isSet(el) ? newEl.recipient = el : newEl.recipient = $('.dsn-body'); 
				newEl.contentArray = [];
				e = $.snipetHandler.sett.call( newEl );
				$d.change.call(e);
			}
		});
		
		return self;
	};

	/**
	 *  Seting up the event listeners on the menu items 
	 *  @todo it is good for the moment, it's an ugly way of doing it. Will need replaced
	 */
	 
	$d.menuListener = function(){
		let callable =this[1],
			warn0 = 'oops the element with id ' + this[0] + ' is not found in menuListener',
			warn1 = 'oops the callback is not a function in menuListener',
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
			at = { ar:[], rq:[], exp:[], cl:[], cu:{}, all:{ }, dsn:[] };
		
		
		showObj.snipet.obj = $n.customAttribute;
		
		function parentMaxHeight () {
			let customContainer, settingsContainer = $('#dsn-el-setings').children[1];
			customContainer = settingsContainer.children[4];
			if( settingsContainer.classList.contains('collapse-content') && settingsContainer.style.maxHeight ) {
				if(customContainer.style.maxHeight)
					setTimeout( () => {customContainer.style.maxHeight = customContainer.scrollHeight + 'px'}, 200);
				settingsContainer.style.maxHeight = settingsContainer.scrollHeight + 'px';
			}
		}
		/**
		* Creating a deleting menu block for the atributes
		* @Param {obj, arr, datatype, datavalue}
		*
		* @Note this function despite the name is for custom attributes used
		*       The name it may be chanched in future.
		*/
		
		function showAttr( ) {
			let menu = $.snipetHandler.sett.call(this.snipet, { contentArray: [ this.arr ] } ), link;
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
			el && el.removeAttribute(att);
			at.all[att] && delete at.all[att];
			this.parentElement.parentElement.removeChild(this.parentElement);
		};
		
		/**
		* Show the classes in the menu
		*/
		function showClass() {
			let menu = $.snipetHandler.sett.call(this.snipet, { contentArray:  this.arr  } ),
				link, 
				arr = this.arr;
				
				el.classList.add(this.arr[0]);
				link = menu.children[1];
				link.setAttribute( "data-type", this.datatype);
				link.setAttribute( "data-value", this.datavalue);
				link.addEventListener('click', function (){ removeClass.call(link, arr[0]) ;});
				parentMaxHeight();
		};
		
		/**
		* Delete the classes using the menu button
		*/
		function removeClass() {
			let att = this.getAttribute("data-value");
			this.removeEventListener("click", removeClass);
			el && el.removeAttribute(att);
			el && el.classList.remove(arguments[0]);
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
				at.rq = []; at.rq = [ 'id', 'title']; at.exp = []; at.dsn = [];// an initial value set, as we have checks against it's lenght
				if(isSet(elStruct[elName])) {
					at.exp = elStruct[elName].exAttr;
					at.dsn = elStruct[elName].dsnAttr;
					at.rq = arrayMerge( at.rq , elStruct[elName].reqAttr, elStruct[elName].exAttr, elStruct[elName].dsnAttr );
				}
			}
			
			// Set the attributes for the snipet included in the settings menu
			function fieldsAttr(i) {
				this.children[1].setAttribute( "id", 'dsn_a_' + i);
				this.children[0].setAttribute( "for", 'dsn_a_' + i);
				this.setAttribute( "data-type", 'attribute');
				( isSet(at.dsn) && at.dsn.includes( at.rq[i] ) ) ? this.setAttribute( "data-value", "data-" + at.rq[i]) : this.setAttribute( "data-value", at.rq[i]);
			}
			
			// from hard code text color a class should be implemented
			function currentElAttr() {
				let j = arguments[0], attr = at.rq[j], field = arguments[1];
				( isSet(at.dsn) && at.dsn.includes( attr ) ) && ( attr = 'data-' + at.rq[j] );
				j == 0 && ( attr = 'id');
				j == 1 && ( attr = 'title');
				(at.all[attr] !== undefined && isStr(at.all[attr]))? field.previousElementSibling.style.color = "#0a9c05" : null;
				return at.all[attr];
			}
			
			// add the attribute, and change color in menu
			function setAttr(attr, val, s){
				empty(val) || val === "" ? el.setAttributeNode(document.createAttribute( attr )) : el.setAttribute( attr, val); 
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
				let snipet, field, attr, txt;
				snipet = {
					obj: $n.expectedAttribute,
					recipient: parent,
					position: 'beforeend'
				};
				onelistener(); // for id and title fields
				bond();
				if( at.rq.length > 2) {
					for( let i = 0; i < at.rq.length; i++){
						i < 2 && ( field = parent.children[i] );
						if( i > 1) {
							txt = capitalize(at.rq[i]);
							field = $.snipetHandler.sett.call(snipet, { contentArray: [ txt ] } ) ;
							// First expected attribute div will have an extra space from required
							if( isSet(at.exp) && at.exp.length > 0 ){
								at.rq[i] === at.exp[0] && field.classList.add('dsn-vary-properties-top');
							}
							// First dsn attribute div will have an extra space from required
							if( isSet(at.dsn) && at.dsn.length > 0 ){
								at.rq[i] === at.dsn[0] && field.classList.add('dsn-vary-properties-top');
							}
							field.children[1].addEventListener('change', listen);
							fieldsAttr.call(field, i);
						}
						attr = currentElAttr(i, field.children[1]);
						if( attr !== undefined && isStr(attr) ) field.children[1].value = attr ;
					}
					parentMaxHeight.call(parent);
				}else {
					// changing the color for label text and add id and title attribute 
					for( let i = 0; i < 2; i++){
						field = parent.children[i];
						parent.children[i].children[1];
						attr = currentElAttr(i, field.children[1]);
						if( attr !== undefined && isStr(attr) ) field.children[1].value = attr ;
					}
				}
			}
			
			ext.rem = function(){
				let field;
				if( at.rq.length > 2){
					for( let i = 0; i < at.rq.length; i++){
						if( parent.children[i] ) {
							field = parent.children[i].children[1];
							field.removeEventListener('change', listen);
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
					for( let i = 0; i < 2; i++){
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
				for (let i = 0; i < hideClasses.length; i++) {
					arrayRemove(at.cl, hideClasses[i] );
				}
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
					if(at.all.keyIn(prop) && !hideAttributes.includes( prop ) ){
						( at.rq.indexOf(prop) < 0 ) && buildCustomAttribute(at.all[prop], prop);
					}
				}
				
			};
			parentMaxHeight();
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
						fields[i].e.value = "";
						fields[this.i-1].e.value = "";
					}
				} 
			}
			/**
			*   We can't create just one attribute value without a name
			*   Clear the input fields once a atttribute/name pair has been added
			*/
			if(this.d === "attr-value"){
				i = this.i - 1;
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
			parentMaxHeight();
		}
		
		/**
		*   Sorting the results for handling respective of type of data
		*/
		
		function handleType(){
			let classname = this.v;
			if (! reseting && el){
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
			parentMaxHeight();
		};
		
		function getFields(){
			let fields =[], ids = [ 'dsn_96', 'dsn_95', 'dsn_94'], cont;
			for(let i =0; i < ids.length; i++) {
				cont = document.getElementById(ids[i]);
				fields.push( { e:cont, d:cont.getAttribute('data-type') } );
			}
			return fields;
		}
		
		/**snipets
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
	
	$d.tagsLayout =function() {
		self = {};
		// Setting the position in the toggle for new tags and snipets added
		function setPosition(){
			let pos;
			
			function releaseActive() {
				$(this).walkChild(
					function(){
						if(this.nodeName.toLowerCase() ==="button")
							this.classList.contains('active') && this.classList.toggle("active");
					}
				);
			}
			
			function setSettings() {
				pos = this.getAttribute('data-pos')
				$('#dsn-205').value = pos;
				settings.layoutPosition = pos;
				this.classList.add('active');
			}
			
			function setActive() {
				let other, me;
				this.parentElement.getAttribute('id') === "dsn-204" ? other = $('#dsn-318') : other = $('#dsn-204');
					releaseActive.call(other);
					other.walkChild(
						function(){
							this.getAttribute("data-pos") === pos && this.classList.add('active') ;
						}
					);
			}
			
			releaseActive.call(this.parentElement);
			setSettings.call(this);
			setActive.call(this);
		}
		
		// seting up event listener for position buttons in layout and snipet
		// This two places are joined to change the same position setings
		// The buttons have been replicated for accessibility
		function positionListen (){
			let e = [ $('#dsn-204'), $('#dsn-318') ] ;
			for(let i = 0 ; i < e.length; i++){
				e[i].walkChild( function(){
					if(this.nodeName.toLowerCase() ==="button") {
						this.addEventListener('click', setPosition);
						this.getAttribute('data-pos') === settings.layoutPosition && this.classList.add('active');
					}
				});
			}
		}
		
		// Get and store snipets from the server
		function get_Store_Snipet(snipetName){
			function homeSnipet(name, snipet_obj){
				snipets[ name ]  = snipet_obj;
			}
			$.snipetHandler.get_http(
				{
					url: 'snippet?a=get-snipet-by-name&name=' + snipetName,
					name: snipetName,
					fn: homeSnipet
					//el:$('#dsn_5')
				}
			);
		}
		
		// Get and insert snipet on the active element
		function get_Insert_Snipet(snipetName){
			$.snipetHandler.get_http(
				{
					url: 'snippet?a=get-snipet-by-name&name=' + snipetName,
					el: el,
					pos: settings.layoutPosition
				}
			);
		}
		
		// Setting up elStruct with elements and attributes
		function getHttpTags(){
			function homeTags( name, snipet_obj){
				let elname;
				for ( let i = 0; i < snipet_obj.length; i++) {
					elname = snipet_obj[i]['name'];
					elStruct[ elname]  = {};
					elStruct[ elname ].id = snipet_obj[i]['id'] ;
					
					!empty( snipet_obj[i]['exp'] ) && ( elStruct[ elname ].exAttr = snipet_obj[i]['exp'].split(",") );
					!empty( snipet_obj[i]['req'] ) && ( elStruct[elname].reqAttr = snipet_obj[i]['req'].split(",") );
					!empty( snipet_obj[i]['dsn'] ) && ( elStruct[elname].dsnAttr = snipet_obj[i]['dsn'].split(",") );
					elStruct[elname].group = snipet_obj[i]['tgroup'] ;
				}
			}
			$.snipetHandler.get_http(
				{
					url: 'snippet?a=load-tags',
					name: 'o',
					fn: homeTags
				}
			);
			
		}
		
		// Geting dummy text from server, Setting up $n.dummy{ X-small:[], small:[], large:[], X-large:[], XX-large:[] }
		function getHttpDummyText() {
			
			function homeDummy( dummy_obj) {
				let dum, newobj = dummy_obj;
				for ( let i = 0; i < newobj.length; i++) {
					dum = newobj[i]['type'];
					if( !$n.dummy.keyIn(newobj[i].type)) {
						$n.dummy[dum]  = [];
						$n.dummy[dum].push(newobj[i].id = newobj[i].text );
					} else{ 
						$n.dummy[dum].push(newobj[i].id = newobj[i].text);
					}
				}
			}
			$.get_json({
				url: 'snippet?a=load_dummy_text',
				callback : homeDummy,
			});
		}
		
		function makeElement() {
			let e = {}, ev = $("#dsn-200").value, r = $("#dsn-201").value, d = $("#dsn-202").value, t, temp;
			for( let i = 0; i < r; i++) {
				isSet(d) && (d !== "") && ( t = $n.dummy[d][ Math.floor(Math.random() * 5) ] );
				( isSet(d) && !empty(d) ) ? temp = $('<'+ ev +'>', t) : temp = $('<'+ ev +'>') ;
				e.e_type = 1; e.e_attr =""; e.e_name = ev; e.e_content = t;
				if(el) {
					// if el exist and we are not on a empty slate
					temp = $.snipetHandler.sett.call( { obj:e, recipient:el, position:settings.layoutPosition } );
				}else if(!el) {
					// we are on a empty slate,default position is beforeend
					temp = $.snipetHandler.sett.call( { obj:e, recipient:$('.dsn-body') } );
				}
				temp.classList.add('dsn-minsize');
			}
		}
				
		
		self.init = function() {
			positionListen();
			//get_Store_Snipet('add_module');
			getHttpDummyText();
			getHttpTags();
			//insertTag();
			$('#dsn-203').addEventListener('click', makeElement );
		}
		return self.init(); 
	};
	
	$d.loadDsn =function() {
		
	}
	
	// Multiplying a few elements in the body for testing 
	let asd = {recipient:$('.dsn-body')};
	asd.obj = $.snipetHandler.gett($('.dsn-body'), true);
	let bbg = $.snipetHandler.sett.call(asd);
	
		
	/**
	* Will search and list snippets for download, favorites as default for initial loading or apply filters for search
	* $fn functions derived from this
	*      $fn.menu.searchSnippets = searchSnippets;
	*      $fn.body.searchSnippets = searchSnippets;
	*/
	function searchSnippets() {
		let selfCaller = this, gro = { callback: { } }, opp = { callback: { } }, parent = arguments[0], searchIds;
		searchIds = ['d-2-2', 'd-2-3', 'd-2-4', 'd-2-5', 'd-2-6'];
		function addSnippetListEvents() {
			if(this.classList.contains('download')) {
				this.setAttribute('data-dsnfname', 'downloadSnipet');
				this.setAttribute('data-snippet-name', arguments[0]);
			}
			if(this.classList.contains('settings')) {
				this.setAttribute('data-dsnfname', 'updateSnippetSettings');
				this.setAttribute('data-snippet-name', arguments[0]);
			}
			if(this.classList.contains('preview')) {
				this.setAttribute('data-dsnfname', 'previewSnippet');
				this.setAttribute('data-snippet-name', arguments[0]);
			}
		}
		
		// Will repeat the rows showing the snippets
		// Called in opp.callback.listSnippets function
		function buildSnippetList() {
			let row, j, buttons, k = 0, r = arguments[0];
			if(r){
				for(j = 0; j < r.length; j++){
					row = $.snipetHandler.sett.call({obj: this.rowElement, recipient: this.rowElementParent});
					row && (row.firstElementChild.textContent = r[j].name);
					row && ( buttons = row.children[1].childNodes );
					while(k < buttons.length){
						addSnippetListEvents.call(buttons[k], r[j].name);
						k++;
					}
					k = 0;
				}
			}
		}
			
		// Update the groups select field once the project was changed in the filters
		$fn.body.updateGroups = function() {
			fillSelectFields('load-snippet-group', '#d-2-5', {v:'', t:'', send:{project: this.value}} );
		};
		
		// Attached to opp object pased to snipethandler parameters to be called on the row snippet. 
		// Activated from snipethandler found on dsnfnobj data attribute on the snippet object
		opp.callback.listSnippets = function() {
			let intobj = {rowElement:this, rowElementParent: arguments[0]}, send = {};
			send.project = $p.name;
			
			selfCaller && selfCaller.id === 'd-2-7' && ( send = getFormValuesById(searchIds, send) );
			
			$.send_json({
				data: send,
				url: 'snippet?a=search-snippets',
				log: 'snippet list loading failed',
				callback: (r) => { buildSnippetList.call(intobj, r)}
			});
		};
			
		function loadFilters() {
			fillSelectFields('load-projects-name', '#d-2-2', {v:$p.name, t:$p.name, attr:{'data-dsnfname':'updateGroups'}} );
			fillSelectFields('load-snippet-type', '#d-2-3', {v:'', t:''} );
			fillSelectFields('load-snippet-status', '#d-2-4', {v:'', t:''} );
			fillSelectFields('load-snippet-group', '#d-2-5', {v:'', t:'', send:{ project: $p.name} });
		}
			
		// opp.callback = fn;
		// This will open the search page. Called when a search button is pressed by user from menu
		function getSearchSnippetBody() {
			$.send_json({
				data : { name :'search snippet page', project: 'dard'},
				url : 'snippet?a=get-snippet-by-name',
				callback: function(r) {
					if( isSet(r.body) ){
						$.snipetHandler.sett.call({ obj: r.body, recipient: snb, position: 'beforeend' }, opp);
						loadFilters();
					}
				},
				log: 'search snippet page body loading failed'
			});
		}

		// Called from the search button after filters has been applied by user
		function searchAndListSnippets () {
			$.send_json({
				data : { name :'search snippet row', project: 'dard'},
				url : 'snippet?a=get-snippet-by-name',
				callback: function(r) {
					let recipient_parent = $('#d-2-1');
					if(r) {
						$('#d-2-1').empty();
						$.snipetHandler.sett.call({ obj: r.body, recipient: $('#d-2-1'), position: 'beforeend' }, opp);
					}
				}
			});
		}
		if(this){
			this.id === 'd-2-7' && searchAndListSnippets();
			this.id === 'dsn-337' && $d.staticBody( getSearchSnippetBody, () => {} );
		} else {
			$d.staticBody( getSearchSnippetBody, () => {} );
		}
	}
	$fn.menu.searchSnippets = searchSnippets;
	$fn.body.searchSnippets = searchSnippets;
	
	/**
	*
	*
	*/
	function snippetTemplateMenu(){
		let prj, entry, content, index = 0;
		!arguments[0] ? prj = 'dsn' : prj = arguments[0];
		const menubody = $('#dsn_7');
		
		
		function menuEntry(arg) {
			entry = $('<button>', capitalize(arg)).addattr('class', 'dns-collapse-button collapse');
			content = $('<div>').addattr('class', 'collapse-content dsn-element-properties');
			menubody.append(entry);
			menubody.append(content);
		}
		
		function subcatBody(arg) {
			const bt = $('<button>', capitalize(arg.catname)).addattr('class', 'dsn-naked-collapse-button dark collapse');
			const bd = $('<div>').addattr('class', 'collapse-content dsn-element-properties dsnlmenu');
			// listing the items
			for( let k = 0; k < arg.items.length; k++) {
				const row = $('<div>').addattr('class', 'section group row');
				const cell = $('<div>', capitalize(arg.items[k].name)).addattr('class', 'col-f cell');
				const btn = $('<div>', '+').addattr('class', 'col-f btn').addattr('data-index', index).addattr('data-dsnfname', 'snippetFromMenu').addattr('data-name', arg.items[k].name);
				row.append(cell).append(btn);
				bd.append(row);
				isSet(arg.items[k].body) && ( $n.menu[index] = JSON.parse(arg.items[k].body )) && index++;
			}
			content.append(bt) && content.append(bd);
		}
		
		$fn.menu.snippetFromMenu = function () {
			let ob = $n.menu[this.getAttribute('data-index')], elem = el, pos = settings.layoutPosition;
			if( settings.elChangeLock ) { return; }
			if(!el) {
				elem = snb;
				pos = 'beforeend';
			}
			if(ob) {
				$.snipetHandler.sett.call( { obj : ob, recipient: elem, position : pos });
				pushNotes('Snippet inserted...');
			} else {
				pushNotes("Can't locate the snippet from menu...");
			}
		}
		
		$.send_json({
			data : { project: prj},
			url : 'snippet?a=get-basic-menu',
			callback : function(r) {
				if(r) {
					for(let i = 0; i < r.length; i++) {
						menuEntry(r[0].menu);
						for( let j = 0; j < r[0].subcat.length; j++){
							subcatBody(r[0].subcat[j]);
						}
					}
				}
			},
			log: 'opps no menu'
		});
	
	}
	
	$d.init();
	searchSnippets();
	return $d;
};


// Finaly let the fun begun
if($('#dsn_5')){
	$.ds = new dard_snipet();
	$.ds && setTimeout( () => { $.collapse() }, 2000);
}
