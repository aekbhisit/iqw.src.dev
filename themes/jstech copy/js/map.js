                    // When the window has finished loading create our google map below
                    google.maps.event.addDomListener(window, 'load', init);

                    function init() {
						'use strict'; // use strict mode
						
                        // Basic options for a simple Google Map
                        // For more options see: https://developers.google.com/maps/documentation/javascript/reference#MapOptions
                        var myLatlng = new google.maps.LatLng(12.698579, 101.191960);

                        var mapOptions = {
                            // How zoomed in you want the map to start at (always required)
                            zoom: 12,
                            disableDefaultUI: false,
							scrollwheel: false,

                            // The latitude and longitude to center the map (always required)

                            center: myLatlng, // New York

                            // How you would like to style the map. 
                            // This is where you would paste any style found on Snazzy Maps.
                            styles: [
    {
        "stylers": [
            {
                "hue": "#ff1a00"
            },
            {
                "invert_lightness": true
            },
            {
                "saturation": -100
            },
            {
                "lightness": 33
            },
            {
                "gamma": 0.5
            }
        ]
    },
    {
        "featureType": "water",
        "elementType": "geometry",
        "stylers": [
            {
                "color": "#2D333C"
            }
        ]
    }
]
                        };

                        // Get the HTML DOM element that will contain your map 
                        // We are using a div with id="map" seen below in the <body>
                        var mapElement = document.getElementById('map');

                        // Create the Google Map using out element and options defined above
                        var map = new google.maps.Map(mapElement, mapOptions);

                        var marker = new google.maps.Marker({
                            position: myLatlng,
                            map: map,
							icon: 'img/map-marker.png',
                            title: 'Your item position'
                        });

                    }
