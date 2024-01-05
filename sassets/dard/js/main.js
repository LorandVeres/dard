/**
 * main.js
 * @author Lorand Veres lorand.mast@gmail.com
 * 
 * @copyright Lorand Veres lorand.mast@gmail.com
 * @license MIT
 *
 */

// Will hold function names for body element for events
!isSet( $fn ) && ( $fn = {} );

// Adding a global click event listener on body element
// If the target element has data-dsnfname=functionName attribute, the event will be handled by $fn.functionName
window.addEventListener("load", (event) => {
	document.body.addEventListener('click',function(e){
		try{
			let fname = ( $(e.target).attr('data-dsnfname') || $(e.target.parentElement).attr('data-dsnfname') );
			$fn && $fn.hasOwnProperty(fname) && isFunc($fn[fname]) && $fn[fname].call(e.target);
		} catch ( error ){
			console.log( error );
		}
	});
});

// toggle nav element on screens smaller than tablets vertical sizes
$fn.menu = function(){
	if(window.screen.width <= 960){
		const nav = $($('.main-container').children[0]);
		nav.classList.contains('moved') ? nav.classList.remove('moved') : nav.classList.add('moved');
	}
};
