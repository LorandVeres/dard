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
		$n = { dummy:{}, c:{}, p:{}, style:{} },    // dsn snippet objects
		settings = { },                             // will store various settings
		elStruct = { },                             // elStruct.elementName{ id:'', exAttr:[], rqAttr:[], dsnId:''}
		snipets = {},                               // the snippet tempalates used for the page
		attributes,                                 // function in $d.init
		$st = {id:0, body:{}, name:'', type:'', status:'live', css:'' },    // stash
		$p = {id: 3, name:'sandbox', maxid: 0, maxclass: 0, $: {} },        // project
		$i  = {},                                   // Sets and get value of input elements based on id
		$fn = { body: {}, menu: {}};                // object containing functions to handle events on snippet container static body, and on the menu side
		
		
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
		let noneditable = ['html', 'meta', 'link', 'form', 'input', 'select', 'textarea', 'br', 'hr', 'ul', 'ol', 'dl', 'img', 'embed', 'bgsound', 'base', 'col', 'source', 'fieldset'];
		
		$n.style.dsn += ".dsn-highlighted { outline: 1px dashed #7d9eb9 !important; outline-offset: -1px; }\n";
		$n.style.dsn += ".dsn-hover { outline: 1px solid #0264b4 !important; outline-offset: -1px; box-shadow: 0px 0px 1px 1px rgba(2, 100, 180, .2) inset; }\n";//background-color: rgba(212, 235, 255, 0.1)
		$n.style.dsn += ".dsn-active { outline: 2px solid #3b97e3 !important; outline-offset: -2px; box-shadow: 0px 0px 1px 1px rgba(2, 100, 180, .2) inset; }\n";//background: rgba(212, 235, 255, 0.1)
		$n.style.dsn += ".dsn-minsize, dsn-min-size { min-height:2rem; padding:5px; }\n";
		
		// default general settings 
		settings.layoutPosition = "beforeend";          // used when iserting new tags or layouts
		settings.elChangeLock = true;                   // Used to stop mouse over and click events on elements to show the outline, and set the current el variable
		settings.staticBody = undefined;
		settings.targetElement = undefined;             // The event.targetElement while settings.elChangeLock = false. May be used by overlays or deleted in future
		settings.lastCurrentElement = undefined;        // Saving the last active el if we would like to alter it from static settings pages
		
		
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
			settings.changeSwitch && $d.change.call($('.dsn-body').firstElementChild);
			
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
	
		el && el.classList.contains('dsn-active') && el.classList.remove('dsn-active');
		el && el.classList.contains('dsn-hover') && el.classList.remove('dsn-hover');
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
			if(settings.elChangeLock) {
				settings.elChangeLock = false;
				undefineEl();
				!$n.c.tempbody && ( $n.c.tempbody = $.snipetHandler.gett(snb, true));
				snb.empty();
				isFunc(fn1) ? fn1() : throwError ('Parameter 1 in \'staticBody\' function is not a function!: ' + fn1);
			} else {
				isFunc(fn2) ? fn2() : throwError ('Parameter 2 in \'staticBody\' function is not a function!: ' + fn2);
				snb.empty();
				$n.c.tempbody !== undefined && $.snipetHandler.sett.call({ obj:$n.c.tempbody, recipient:snb})
				delete $n.c.tempbody;
				settings.elChangeLock = true;
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
			let vis = s = window.getComputedStyle(snb, null).getPropertyValue("display");
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
				$d.snipetListener.call(editable);
				pushNotes("Edit mode activated...");
			} else if( !this.classList.contains('active')) { 
				undefineEl();
				$n.c.body = $.snipetHandler.gett($('.dsn-body'), true);
				$d.listenerRemover.call($('.dsn-body'));
				$('.dsn-body').empty();
				$.send_json({
					url: 'snippet?a=responsive',
					data: { body: $n.c.body},
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
			function rem(){
				removeSnipetEvent.call(this);
				if( this.childElementCount > 0 )
					$(this).walkChild(rem);
			}
			$('.dsn-body').walkChild(rem);
			$('.dsn-body').empty();
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
			for( let i =0; i < fields.length; i++) {
				field = $('#' + fields[i]);
				!field.hasAttribute('disabled') && ( field.value = "" );
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
			isSet($n.p.name) ? $('#dsn-314').value = $n.p.name : $('#dsn-314').value = '';
			isSet($n.p.type) ? $('#dsn-315').value = $n.p.type : $('#dsn-315').value = '' ;
			isSet($n.p.status ) ? $('#dsn-316').value = $n.p.status : $('#dsn-316').value = ''; 
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
			let obj = {}; 
			$p.name = $('#dsn-319').value;
			obj.name = $p.name;
			//getDefaultSnippet();
			function getProjectData(r){
				if(isSet(r) && r !== null) {
					simulateClick($('#dsn-326'));
					$p.maxid = r.maxid;
					$p.maxclass = r.maxclass;
					$p.id = r.id;
					pushProject($p.name);
					// set time out to delay the notification :))
					getDefaultSnippet();
					setTimeout( () => pushNotes('Switched to project: ' + $p.name), 50);
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
				$('.dsn-body').append(catchel);
				snipet.body =  $.snipetHandler.gett($('.dsn-body'), true);
				addSnipetEvent.call(catchel);
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
						( isObj( $n.c.body )  && $n.c.body !== null ) && $d.snipetListener.call( $.snipetHandler.sett.call({ obj:$n.c.body, recipient:$('.dsn-body')}) );
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
			let backup = $n.p, name = $('#dsn-314').value, obj = {};
			obj.name = $('#dsn-314').value;
			obj.project = $('#dsn-317').value;
			empty(obj.project) && ( obj.project = $p.name );
			
			function down(t) {
				if( isSet(t) ){
					$n.p = {};
					if( isObj(t) && t.body !== null) {
						$n.p = t;
						pushNotes('Download finished for snippet: ' + name);
						refreshDownloadFieldsValue();
					}
					if( t.body === null || t === undefined) { 
						pushNotes("Couldn't find snippet with name: " + name);
						$n.p = backup;
					}
				}
				fieldDisable(true);
			}
			$.send_json( { url: 'snippet?a=get-snippet-by-name',  data: obj, callback: down } );
		}
		
		// Later implementation of updating the stashes with the new name should be done
		// Or an id based association should be implemented
		function saveSnippet () {
			let s = {}, namev = $('#dsn-314').value, typev = $('#dsn-315').value, statusv = $('#dsn-316').value;
			if($('#dsn-334').classList.contains('active')) { 
				if( pushNotes("Responsive mode activated. Can't save...")) {
					return;
				}
			}
			if(!isSet(el)) { return; }
			// Updating the current snippet object if name, type or status are desired for change
			!empty(namev) && ( $n.c.name = namev );
			!empty(typev) && ( $n.c.type = typev );
			!empty(statusv) && ( $n.c.status = statusv);
			el.classList.remove('dsn-active');
			$n.c.body = $.snipetHandler.gett($('.dsn-body'), true);
			s = $n.c;
			s.project = $p.name;
			
			function down(r) {
				if(isSet(r.lastId)) pushNotes('Snippet saved...');
			}
			
			$.send_json( { url: 'snippet?a=save-snippet',  data: s, callback: down } );
			el.classList.add('dsn-active');
		}
		
		function applyExtension () {
			let id, parentel, pos, newel;
			this.hasAttribute('id') && ( id = this.getAttribute('id') );
			if(id === "dsn-323") {
				if (isSet(el) && el instanceof HTMLElement) {
					parentel = el;
					pos = settings.layoutPosition;
					newel = $.snipetHandler.sett.call( { obj: $n.p.body, recipient:parentel, position:pos});
				} else {
					parentel = $('.dsn-body');
					newel = $.snipetHandler.sett.call( { obj: $n.p.body, recipient:parentel});
				}
				$d.snipetListener.call(newel);
			}
		}
		
		function editDownload() {
			let newel;
			fieldDisable(false);
			emptyFieldsValue();
			saveSnippet();
			setTimeout(	function() {
				$d.listenerRemover.call($('.dsn-body'));
				clearWorkspace();
				$.snipetHandler.sett.call( { obj: $n.p.body, recipient:$('.dsn-body')});
				$('.dsn-body').walkChild($d.snipetListener);
				$n.c = $n.p;
				pushName($n.c.name);
				pushNotes($n.c.name + ' snippet ready...');
				fieldDisable(true);
				$n.p = {};
			},50);
		}
		
		/** Empty then
		*   Populate the select option with data
		*   @def [ optional ] object default first item {v: 'any', t: 'text to be inserted' }
		*/
		function fillSelectFields(urlarg, id ) {
			let s = $(id), arg;
			s.empty();
			isSet(arguments[2] ) && ( arg = arguments[2]);
			if(isSet(arg) && empty(arg.v))
				s.append ( $('<option>', "").addattr('value', "").addattr('selected') );
			function setSelect(r){
				let p, o;
				
				for( let i = 0; i < r.length; i++) {
					p = r[i];
					o = $('<option>', p.name).addattr('value', p.name);
					if(isSet(arg) && p.name === arg.v)
						o.addattr('selected');
					s.append(o);
				}
			}
			
			$.get_json({
				url: 'snippet?a=' + urlarg,
				callback: setSelect
			});
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
		
		$('.dsn-307', fieldLockListener );
		$('.dsn-308', fieldEmptyListener );
		$('#dsn-309').addEventListener('click', downloadSnipet);
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
	 * 
	 */
	 function generalSettings() {
	    
	    // highlight all elemnts
	    function highlightAll() {
	        let hil = 0;
	        function addDottedBorder(){
	            this.classList.toggle('dsn-highlighted');
	            if(this.childElementCount > 0)
	                $(this).walkChild(addDottedBorder);
	        }
	        $('.dsn-body').walkChild( addDottedBorder);
		}

		function addCssFiles() {
			let self={}, files, pos, path = '/src/css/', existing = [], parent, newpath, open = false;
			//h.append($('<link>').addattr('rel', 'stylesheet').addattr('type', 'text/css').addattr('href', '/src/css/dsn/dard.css'));
			
			function getCssFiles() {
				$.get_json({
					url: 'snippet?a=list_css_files',
					callback: function() {files = arguments[0]}
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
					});
			}
			
			function printTop(){
				let top = $('<div>', 'LOADED CSS FILES').addattrlist({'class':'span-80 c-box dsn-add-list-title'}),
					f = $('<div>', "FOLDER : " +   path).addattrlist({'class':'span-80 c-box dsn-add-list-title'});
				parent.append(top).append(f);
			}
			
			function listExisting() {
				let ex = $('<div>').addattrlist({'id':'dsn-loaded-css','class':'span-80 c-box space-20'}),
					sp;
				if($('#dsn-loaded-css')) {
					$('#dsn-loaded-css').parentElement.removeChild($('#dsn-loaded-css'));
				}
				for(let i =0; i < existing.length; i++) {
					sp = $('<span>', existing[i]).addattrlist({'class':'col-f dsn-add-list-existing'});
					ex.append(sp);
				}
				$('.dsn-add-list-title').insertAdjacentElement("afterend", ex);
				ex.style.height = ex.scrollHeight + "px"
			}
			
			function addFile () {
				let link, rbt, filepath;
				filepath = this.parentElement.parentElement.children[0].getAttribute('data-file-path')
				link = $('<link>').addattrlist({'rel':'stylesheet', 'type':'text/css', 'href':filepath});
				rbt = $('<button>').addattrlist({'class':'span-100 box dsn-ctl-btn-l dsn-24-m-rem-bt'});
				pos.insertAdjacentElement("beforebegin", link)
				this.parentElement.previousElementSibling.appendChild(rbt);
				this.removeEventListener('click', addFile);
				this.parentElement.removeChild(this);
				rbt.addEventListener('click', remFile);
				existing.push(filepath);
				listExisting();
			}
			
			function remFile() {
				let link, abt, rempath;
				abt = $('<button>').addattrlist({'class':'span-100 box dsn-ctl-btn-l dsn-24-m-add-bt'});
				rempath = this.parentElement.previousElementSibling.getAttribute('data-file-path');
				$('head').walkChild(
					function(){
						if(this.nodeName.toLowerCase() === 'link' && this.hasAttribute('type')){
							if(this.getAttribute('type') === "text/css" && this.getAttribute('href') === rempath) {
								this.parentElement.removeChild(this);
							}
						}
					}
				);
				this.parentElement.nextElementSibling.appendChild(abt);
				abt.addEventListener('click', addFile);
				this.removeEventListener('click', remFile);
				this.parentElement.removeChild(this);
				arrayRemove(existing, rempath);
				listExisting();
			}
			
			function build(o, path){
				let ul = $('<ul>').addattrlist({'class':'span-80 c-box space-20 dsn-add-list'}), i, ob, li, f, r, a, rbt, abt;
				
				if(o.keyIn('files')){
					for(i = 0; i < o.files.length ; i++){
						newpath = '';
						newpath = path + o.files[i];
						li = $('<li>').addattrlist({'class':'span-90 c-box'});
						f = $('<span>', o.files[i]).addattrlist({'class':'span-80 col-f left', 'data-file-path': newpath});
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
					
					for(let prop in ob) {
						if(ob.hasOwnProperty(prop)){
							newpath = '';
							newpath = path + prop + '/';
							parent.append($('<div>', 'FOLDER : ' + path + prop).addattrlist({'class':'span-80 c-box dsn-add-list-title'}));
							build(ob[prop], newpath);
						}
					}
				}
			}
			
			function removeRemovalEvents(){
				for(let i = 0; i < this.length; i++) {
					this[i].removeEventListener('click', remFile);
				}
			}
			
			function removeAdditionEvents(){
				for(let i = 0; i < this.length; i++) {
					this[i].removeEventListener('click', addFile);
				}
			}
			
			self.add = function(){
				if (open && $('.dsn-overlay-body')){
					$('.dsn-24-m-rem-bt', removeRemovalEvents);
					$('.dsn-24-m-add-bt', removeAdditionEvents);
				}
				overlay.do();
				if(parent = $('.dsn-overlay-body')) {
					getCssFiles();
					checkExisting();
					setPosition
					printTop()
					listExisting();
					build(files, path);
				}
				open ? open = false : open = true;
			}
			!files && getCssFiles();
			!pos && setPosition();
			return self;
		}
		
	    let cssfiles = new addCssFiles();
	    $('#dsn-335').addEventListener('click', function() {highlightAll(); this.classList.toggle('active'); } );
	    $('#dsn-336').addEventListener('click', cssfiles.add );
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
	 *  Seting up the event listener on the snipet container
	 *
	 */
	function snippetListener(){
		let self = {}, snipetClickable, snipetMoveout, snipetMoveover;
		
		snipetMoveover = function(ev){
			let e = ev.target;
			if(settings.elChangeLock){
				e.classList.toggle('dsn-hover');
			}
		}
		
		snipetMoveout = function(ev){
			let e = ev.target;
			if(settings.elChangeLock){
				if(e && e.classList.contains("dsn-hover")){
					e.classList.toggle('dsn-hover');
					e.hasAttribute('class') && ( e.getAttribute('class') === '' && e.removeAttribute('class'));
				}
			}
		}
		
		snipetClickable = function(ev){
			let e = ev.target, fname;
			if(settings.elChangeLock){
				$(e).attr('id') !== 'dsn_5' && $d.change.call(e);
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
		// remove the active class from current element
		if(el && el.classList.contains("dsn-active"))
			el.classList.toggle('dsn-active');
		
		
		el = this;
		elName = el.nodeName.toLowerCase();
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
		$i.sett('dsn_90', elName);
		$i.sett('dsn_91', myindex(el));
		$i.sett('dsn_92', ep.children.length - 1);
		$i.sett('dsn_93', el.children.length);
		
		return el;
	};
	
	/** Menu Items
	****************************************
	*/
	
	 /** Move to the first element child */
	 
	$d.goToFirstKid = function(){ if(el && el.firstElementChild) $d.change.call(el.firstElementChild); };
	
	/** Move to parent element. Stops before exiting the snipet container */
	
	$d.goToParent = function(){ if(el && el.parentElement && !el.parentElement.classList.contains("dsn-body") ) $d.change.call(el.parentElement); };
	
	/** Move to the younger sibling DOWN :) */
	
	$d.goToYoungerBrother = function( ){ if(el && el.nextElementSibling) $d.change.call(el.nextElementSibling); };
	
	/** Move to the older sibling UP :) */
	
	$d.goToOlderBrother = function(){ if(el && el.previousElementSibling) $d.change.call(el.previousElementSibling); };
	
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
			at = { ar:[], rq:[], exp:[], cl:[], cu:{}, all:{ } };
		
		
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
				at.rq = []; at.rq = [ 'id', 'title']; at.exp = [];// an initial value set, as we have checks against it's lenght
				if(isSet(elStruct[elName])) {
					at.exp = elStruct[elName].exAttr;
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
						i < 2 && ( field = parent.children[i] );
						if( i > 1) {
							field = $.snipetHandler.sett.call(snipet, [ capitalize(at.rq[i])+' :' ] ) ;
							// First expected attribute div will have an extra space from required
							if( isSet(at.exp) && at.exp.length > 0 ){
								at.rq[i] === at.exp[0] && field.classList.add('dsn-vary-properties-top');
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
					for( i = 0; i < 2; i++){
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
				$i.gett('dsn-205').value = pos;
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
					elStruct[elname].goup = snipet_obj[i]['tgroup'] ;
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
				temp && $d.snipetListener.call(temp);
				temp.classList.add('dsn-min-size');
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
	
	$d.overlay = function() {
		let inswitch = false, self = {};
		
		self.body = $('<div>').addattrlist({'class':'overlay-body'});
		
		self.addbody = function() {
			let h = $('head'),
				content = $('<div>').addattrlist({'class':'c-box dsn-overlay-body'});
			self.body.append(content);
			$('#dsn_5').append(self.body);
		}
		
		self.rembody = function() {
			if($('.overlay-body')){
				self.body.empty();
				$('#dsn_5').removeChild(self.body);
				inswitch = false;
			}else{
				self.body.empty();
				self.addbody();
				inswitch = true;
			}
		}
		
		function controler() {
			if(inswitch) {
				self.rembody();
			}else{
				self.addbody();
				inswitch = true;
			}
		}
		
		self.do = function() {
			controler();
		}
		
		self.printSwitch = function() {
			return inswitch;
		}
		return self;
	}
	
		
	// Multiplying a few elements in the body for testing 
	let asd = {recipient:$('.dsn-body')};
	asd.obj = $.snipetHandler.gett($('.dsn-body'), true);
	let bbg = $.snipetHandler.sett.call(asd);
	
	
	
	
	
	$d.init();
	return $d;
}());


// Finaly let the fun begun
if($('#dsn_5')){
	$.ds = new dard_snipet();
	$.collapse();
}

