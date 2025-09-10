$(document).ready(() => {
    /* Submit disable after 1 click */
    $('[type=submit][name=submit]').on('click', (event) => {
        $(event.currentTarget).addClass('disabled');

        let text = $(event.currentTarget).text();
        let loader = '<div class="spinner-grow spinner-grow-sm"><span class="sr-only">Loading...</span></div>';
        $(event.currentTarget).html(loader);

        setTimeout(() => {
            $(event.currentTarget).removeClass('disabled');
            $(event.currentTarget).text(text);
        }, 3000);

    });

    /* Confirm delete handler */
    $('body').on('click', '[data-confirm]', (event) => {
        let message = $(event.currentTarget).attr('data-confirm');

        if(!confirm(message)) return false;
    });

    /* Custom links */
    $('[data-href]').on('click', event => {
        let url = $(event.currentTarget).data('href');

        fade_out_redirect({ url, full: true });
    });

    /* Enable tooltips everywhere */
    $('[data-toggle="tooltip"]').tooltip();

    /* Popovers */
    $('.popover-dismiss').popover({
        trigger: 'focus'
    })
    
    String.prototype.ucwords = function() {
		str = this.trim();
		return str.replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
			function($1){
				return $1.toUpperCase();
			});
	}
	String.prototype.ucfirst = function() {
		str = this.trim();
		return str.charAt(0).toUpperCase() + str.slice(1);
	}
	String.prototype.money = function() {
		str = this.toString().toLowerCase();
		return str.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
	}
});

function buttonSpinner(el,html,cond) {
	if(typeof(cond) == 'undefined')
		cond = true;
		
	let loader = '<div class="spinner-grow spinner-grow-sm"></div> <small class="text-white">Please Wait...</small>';
	if(cond) {
		el.find('button[type=submit][name=submit]').html(loader);
		el.find('button[type=submit][name=submit]').addClass('disabled');
	} else {
		el.find('button[type=submit][name=submit]').html(html);
		el.find('button[type=submit][name=submit]').removeClass('disabled');
	}
}

function ucwords(text) {
	return text.split(' ').map((txt) => (txt.substring(0, 1).toUpperCase() + txt.substring(1, txt.length))).join(' ');
}

function wa_url() {
	var url = 'https://api.whatsapp.com/send';
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
	  url = 'whatsapp://send/';
	}
	return url;
}