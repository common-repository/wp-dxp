
(function( $ ) {
	'use strict';

	const debug = false;
	const delayed = {};
	const DateTime = luxon.DateTime;
	const current_timestamp = DateTime.now();

	let returningVisitor = true;
	let daysSinceLastVisit = 1;
	let timeOfDay = "";
	let usersCurrentTime = current_timestamp.toFormat("HH:mm:ss");
	let usersCurrentTimestamp = current_timestamp;
	let arrUsersDeviceType = [];
	let urlQueryString = window.location.search;
	let referrerURL = document.referrer;

	/*****
		SECTION DETERMINES WHAT DEVICE THE USER IS USING - core_users_device_type
	*****/

	if (isMobile()) {
		arrUsersDeviceType.push("mobile");
		arrUsersDeviceType.push(getMobileOperatingSystem());
	} else if (isTablet()) {
		arrUsersDeviceType.push("tablet");
		arrUsersDeviceType.push(getMobileOperatingSystem());
	} else {
		arrUsersDeviceType.push("desktop");
	}
	/* END - SECTION DETERMINES WHAT DEVICE THE USER IS USING  */




	/*****
		SECTION DETERMINES WHAT TIME OF THE DAY A USER IS VISITING THE SITE - core_users_visiting_time
	*****/

	var currentHour = current_timestamp.toFormat("H");

	if (currentHour >= 0 && currentHour < 6) {
		timeOfDay = "nighttime";
	} else if (currentHour >= 6 && currentHour < 12) {
		timeOfDay = "morning";
	} else if (currentHour >= 12 && currentHour < 18) {
		timeOfDay = "afternoon";
	} else if (currentHour >= 18 && currentHour <= 23) {
		timeOfDay = "evening";
	}
	/* END - SECTION DETERMINES WHAT TIME OF THE DAY A USER IS VISITING THE SITE  */






	/*****
		SECTION DETERMINES IF THE USER IS A NEW USER OR AN EXISTING USER - 'core_new_visitor'
	*****/

	// set local storage variable for when user first visited the site
	if (localStorage.getItem("wp_dxp_first_visit") === null) {
		localStorage.setItem('wp_dxp_first_visit', current_timestamp);
		returningVisitor = false;
	} else {
		// get first visit from local storage
		const first_visit_timestamp = localStorage.getItem("wp_dxp_first_visit");

		// calculate the number of days between current date and first visit
		const objDaysSinceFirstVisit = differenceBetweenTwoTimestampsInDays(current_timestamp, first_visit_timestamp);

		// if user visited less than a day ago then they are still a new visitor
		if (objDaysSinceFirstVisit.values.days < 1) {
			returningVisitor = false;
		}
	}
	/* END - SECTION DETERMINES IF THE USER IS A NEW USER OR AN EXISTING USER */






	/*****
		SECTION DETERMINES WHEN THE USER LAST VISITED THE SITE - 'core_users_last_visit'
	*****/

	// set session variable to hold when the user last visited the site - THIS SESSION VARIABLE WILL BE USED TO CALCULATE THE LAST VISIT
	if (sessionStorage.getItem("wp_dxp_last_session") === null) {
		if (localStorage.getItem("wp_dxp_last_visit") === null) {
			sessionStorage.wp_dxp_last_session = current_timestamp;
		} else {
			sessionStorage.wp_dxp_last_session = localStorage.getItem("wp_dxp_last_visit");
		}
	}

	// calculate the number of days between current date and the users last session
	const objDaysSinceLastVisit = differenceBetweenTwoTimestampsInDays(current_timestamp, sessionStorage.getItem("wp_dxp_last_session"));
	// round number of days to nearest integer for simplicity
	daysSinceLastVisit = Math.round(objDaysSinceLastVisit.values.days);

	// set local storage variable and update everytime the user loads a page
	localStorage.setItem('wp_dxp_last_visit', current_timestamp);

	/* END - SECTION DETERMINES WHEN THE USER LAST VISITED THE SITE */






	function differenceBetweenTwoTimestampsInDays(date1, date2) {

		// convert date values ready for comparison
		const date1Converted = DateTime.fromISO(date1);
		const date2Converted = DateTime.fromISO(date2);

		return date1Converted.diff(date2Converted, 'days');
	}

	function isMobile() {
		var check = false;
		(function(a){
		  if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4)))
			check = true;
		})(navigator.userAgent||navigator.vendor||window.opera);
		return check;
	};

	function isTablet() {
		const userAgent = navigator.userAgent.toLowerCase();
		const isTablet = /(ipad|tablet|(android(?!.*mobile))|(windows(?!.*phone)(.*touch))|kindle|playbook|silk|(puffin(?!.*(IP|AP|WP))))/.test(userAgent);

		return isTablet;
	}

	/**
	 * Determine the mobile operating system.
	 * This function returns one of 'iOS', 'Android', 'Windows Phone', or 'unknown'.
	 *
	 * @returns {String}
	 */
	function getMobileOperatingSystem() {
		var userAgent = navigator.userAgent || navigator.vendor || window.opera;

		// Windows Phone must come first because its UA also contains "Android"
		if (/windows phone/i.test(userAgent)) {
			return "windows";
		}

		if (/android/i.test(userAgent)) {
			return "android";
		}

		// iOS detection from: http://stackoverflow.com/a/9039885/177710
		if (/iPad|iPhone|iPod/.test(userAgent) && !window.MSStream) {
			return "ios";
		}

		return "";
	}

	function log(){
		if(debug){
			console.log.apply(console, arguments);
		}
	}

	class WpDxp extends HTMLElement {

		constructor() {
			super();
		}

		connectedCallback() {
			if(this.slots || this.post_id){

				if(undefined === delayed[this.delayed]){
					delayed[this.delayed] = {
						parsing: false,
						elements: [],
					};
				}

				delayed[this.delayed].elements.push(this);

				log('Tag:',this);
			}
		}

		get slots() {
			return this.getAttribute('slots');
		}

		get slot() {
			return this.getAttribute('slot');
		}

		get post_id() {
			return this.getAttribute('post-id');
		}

		get block_id() {
			return this.getAttribute('block-id');
		}

		get template() {
			return this.getAttribute('template');
		}

		get filters() {
			return this.getAttribute('filters');
		}

		get delayed() {
			let s = this.getAttribute('delayed');
			return s ? Number(s) : 0;
		}

		get lifetime() {
			let s = this.getAttribute('lifetime');
			return s ? Number(s) : 0;
		}
	}

	window.customElements.define('wp-dxp', WpDxp);

	$(window).on('load', function() {

		function parse(){

			Object.keys(delayed).forEach(function(delay){

				// a parse is underway for this delay
				if(delayed[delay].parsing)
					return;

				log('Parsing delayed:',delay);

				delayed[delay].parsing = true;

				const elements = delayed[delay].elements;

				const blocks = [];

				for(let el of elements){

					const block = {};

					if(el.slot)
						continue;

					if(el.post_id)
						block.post_id = el.post_id;

					if(el.block_id)
						block.block_id = el.block_id;

					if(el.slots){
						block.slots = JSON.parse(el.slots);
						block.template = el.template;

						if(el.filters){
							block.filters = el.filters;
						}
					}

					blocks.push(block);
				}

				if(!blocks.length){
					delayed[delay].parsing = false;
					log('No blocks to parse for delay:',delay);
					return;
				}

				wp.apiRequest({
					path: '/wp-dxp/v1/blocks',
					type: 'post',
					data: JSON.stringify({
						timeOfDay: timeOfDay,
						usersCurrentTime: usersCurrentTime,
						usersCurrentTimestamp: usersCurrentTimestamp,
						returningVisitor: returningVisitor,
						daysSinceLastVisit: daysSinceLastVisit,
						usersDeviceType: arrUsersDeviceType,
						urlQueryString: urlQueryString,
						referrerURL: referrerURL,
						blocks
				   	}),
					headers: {
						'Content-type' : 'application/json',
					},
				}).then(function(response){

					const batch = [];

					response.data.forEach(function(content){

						// process elements in order
						const el = elements.shift();

						// do we have respective element content?
						if(content){
							batch.push([el,content]);
						}

						// no content, remove the placeholder
						else{
							el.remove();
						}

					});

					if(batch.length){
						setTimeout(function(){

							batch.forEach(function(b){

								// replace placeholder element with real content
								let el = document.createElement('div');
								el.innerHTML = b[1];
								el = el.firstChild;

								if(Number(delay)){
									el.classList.add('wp-dxp');
									el.classList.add('wp-dxp-hide');
								}

								if(b[0].lifetime){
									setTimeout(function(){

										// 300ms fade transition
										el.classList.add('wp-dxp-fade-out');

										// remove after transition
										setTimeout(function(){
											el.remove();
										},350);

									},b[0].lifetime * 1000);
								}

								b[0].replaceWith(el);

								if(Number(delay)){
									setTimeout(function(){
										el.classList.remove('wp-dxp-hide');
									},100);
								}

							});

							// parse after adding elements
							parse();

						},delay * 1000);
					}

					delayed[delay].parsing = false;

				}).catch( err => {
					console.log(err);
				});

			});
		}

		parse();

	});

})( jQuery );
