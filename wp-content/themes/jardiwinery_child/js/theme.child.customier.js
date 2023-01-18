jQuery(function() {
    var INTERVAL_PRIX = [ 75, 300 ], MIN_PRIX = 0, MAX_PRIX = 500;

    jQuery(".menu-item.menu-item-type-custom a").removeAttr('target')
    jQuery(".dokan-store-tabs .dokan-list-inline li a").removeAttr('target')
    jQuery("#img-home-experience").removeAttr('target')

    var vmc_search_select = jQuery("#vmc-search-select");

    if(vmc_search_select) {
        vmc_search_select.on('change', on_search_select_change);
        if(vmc_search_select.val() == 'prix') {
            jQuery("#prix-interval").css( "display", "block" );
            jQuery("#input-vmc-search").css( "display", "none" );
            var price_values = jQuery( "#input-vmc-search" ).val().split('-');
            INTERVAL_PRIX = [parseInt(price_values[0]), parseInt(price_values[1])];
        }
    }

    // interval de prix
    jQuery( "#slider-range" ).slider({
        range: true,
        min: MIN_PRIX,
        max: MAX_PRIX,
        values: INTERVAL_PRIX,
        slide: function( event, ui ) {
            jQuery( "#amount" ).val( "$" + ui.values[ 0 ] + " - $" + ui.values[ 1 ] );
            jQuery( "#input-vmc-search" ).val( ui.values[ 0 ] + "-" + ui.values[ 1 ] );
        }
    });

    jQuery( "#amount" ).val( "$" + jQuery( "#slider-range" ).slider( "values", 0 ) + " - $" + jQuery( "#slider-range" ).slider( "values", 1 ) );
});

function on_search_select_change(e) {
    if(this.value == 'prix') {
        jQuery("#prix-interval").css( "display", "block" );
        jQuery("#input-vmc-search").css( "display", "none" );
	jQuery( "#input-vmc-search" ).val(jQuery( "#slider-range" ).slider( "values", 0 ) + " - " + jQuery( "#slider-range" ).slider( "values", 1 ));
    } else {
        jQuery("#prix-interval").css( "display", "none" );
        jQuery("#input-vmc-search").css( "display", "block" );
	jQuery("#input-vmc-search").val(undefined);
    }
}

