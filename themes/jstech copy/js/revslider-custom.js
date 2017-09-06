jQuery(document).ready(function () {
	'use strict'; // use strict mode
    var revapi;
    revapi = jQuery('#revolution-slider').revolution({
        delay: 7000,
        startwidth: 1170,
        startheight: 500,
        hideThumbs: 10,
        fullWidth: "off",
        fullScreen: "on",
        fullScreenOffsetContainer: "",
        touchenabled: "on",
        navigationType: "none",
        onHoverStop: "off",
    });
});