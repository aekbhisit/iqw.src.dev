;
(function ( $, navigator, google ){
	if( typeof google !== 'object' || typeof google.maps !== 'object' ) {
		$.error('The Gogle Maps Javascript API is not loaded!');
		return;
	}
	var gmaps = google.maps,
	geocoder = new gmaps.Geocoder(),
	namespace = 'setmap',
	f = false,
	defaultOptions = {
		map: {
			zoom: 12,
			type: 'hybrid',
			draggable: true,
			streetView: f,
			disabled: false
		},
		marker: {
			position: {
				latitude: 13.704698631091055,
				longitude: 100.5523681640625
			},
			animation: 'drop',
			draggable: true,
			title: '',
			current: f
		},
		components: false
	};
	
	function handleNoGeolocation ( errorFlag ) {
		if ( errorFlag ) {
			$.error('Error: The Geolocation service failed.');
		} else {
			$.error('Error: Your browser doesn\'t support geolocation.');
		}
	}
	
	function setPosition ( options, latitude, longitude ) {
		
		var loc;
		if( !longitude ) {
			loc = latitude;
		} else {
			loc = new gmaps.LatLng(latitude, longitude);
		}
		options.mapObj.setCenter(loc);
		options.markerObj.setPosition(loc);
		return loc;
	}

	function initialize ( mapDOMElement, options, initCallback ) {
		
		function callback () {
			try {
				options.mapObj = new gmaps.Map(mapDOMElement, {
					zoom: options.map.zoom,
					center: options.latlng,
					mapTypeId: options.map.type,
					zoomControlOptions: {
						style: options.map.zoomControls
					},
					draggable: options.map.draggable,
					disableDefaultUI: options.map.disabled === true,
					scrollwheel: options.map.disabled === false,
					keyboardShortcuts: options.map.disabled === false,
					disableDoubleClickZoom: options.map.disabled === true,
					streetViewControl: options.map.streetView
				});
				options.markerObj = new gmaps.Marker({
					map: options.mapObj, 
					animation: options.marker.animation,
					draggable: options.marker.draggable,
					position: options.latlng,
					title: options.marker.title
				});
				initCallback(true);
			} catch ( e ) {
				$.error("Error: " + e.message);
				initCallback(f);
			}
		}
		
		if( options.marker.current ) {
			if ( navigator.geolocation ) {
				navigator.geolocation.getCurrentPosition(function ( position ) {
					options.marker.position = {};
					options.marker.position.latitude = position.coords.latitude;
					options.marker.position.longitude = position.coords.longitude;
					options.latlng = new gmaps.LatLng(options.marker.position.latitude, options.marker.position.longitude);
					callback();
				}, function () {
					handleNoGeolocation(true);
					callback();
				});
			} else {
				// Browser doesn't support Geolocation
				handleNoGeolocation(f);
				callback();
			}
		} else {
			options.latlng = new gmaps.LatLng(options.marker.position.latitude, options.marker.position.longitude);
			callback();
		}
	}

	function decodeAddress ( latlong, options, callback ) {
		geocoder.geocode({
			'latLng': latlong
		}, function ( results, status ) {
			var val = '';
			if ( status === gmaps.GeocoderStatus.OK ) {
				if ( options.components ) {
					val = results[0].address_components;
				} else {
					$.each(results[0].address_components, function (key, value ) {
						if( key > 0 ) {
							val = val + ', ';
						}
						val = val + value.long_name;
					});
				}
				callback(val);
			} else {
				$.error("Geocode was not successful for the following reason: " + status);
				callback(f);
			}
		});
	}

	function codeAddress ( address, options, callback) {
		geocoder.geocode({
			'address': address
		}, function ( results, status ) {
			var loc;
			if ( status === gmaps.GeocoderStatus.OK ) {
				loc = results[0].geometry.location;
				setPosition(options, loc);
				callback(loc);
			} else {
				$.error("Geocode was not successful for the following reason: " + status);
				callback(f);
			}
		});
	}
	
	$.fn.setmap = function ( method ) {
		var mainArguments = arguments;

		return this.each( function () {
			var element = $(this),
			options = element.data('object.' + namespace) || {},
			methods = {
				init: function ( settings ) {
					if( typeof options === 'object' && ! $.isEmptyObject(options) ) {
						$.error("You should destroy the setmap instance first!");
						return element;
					}
					$.extend(true, options, defaultOptions, settings);
					if( element.attr('style') !== undefined ) {
						element.data('style.' + namespace, element.attr('style') + '');
					}
					options.map.type = (function ( type ) {
						switch( type ) {
							case 'hybrid':return gmaps.MapTypeId.HYBRID;
							case 'roadmap':return gmaps.MapTypeId.ROADMAP;
							case 'satellite':return gmaps.MapTypeId.SATELLITE;
							case 'terrain':return gmaps.MapTypeId.TERRAIN;
							default:return gmaps.MapTypeId.HYBRID;
						}
					}(options.map.type.toLowerCase()));
					options.marker.animation = (function ( animation ) {
						switch ( animation ) {
							case 'bounce':return gmaps.Animation.BOUNCE;
							default:return gmaps.Animation.DROP;
						}
					}(options.map.type.toLowerCase()));
					if( typeof options.marker.title !== 'string' ) {
						options.marker.title = '';
					}
					if( options.map.disabled !== false ) {
						options.map.disabled = true;
					}
					if( options.map.zoomControls === 'large' ) {
						options.map.zoomControls = gmaps.ZoomControlStyle.LARGE;
					} else {
						options.map.zoomControls = gmaps.ZoomControlStyle.SMALL;
					}
					initialize(this, options, function ( response ) {
						if( response ) {
							if( typeof options.drop === 'function' ) {
								options.listener = gmaps.event.addListener(options.markerObj, 'dragend', function ( event ) {
									decodeAddress(event.latLng, options, function ( address ) {
										options.drop(event.latLng.lat(), event.latLng.lng(), address);
									});
								});
							}
							element.data('object.' + namespace, options);
						}
					});
					return element;
				},
				setAddress: function ( address, fn ) {
					if( typeof address === 'string' ) {
						address = $.trim(address);
						codeAddress(address, options, function ( loc ) {
							decodeAddress(loc, options, function ( address ) {
								fn(loc.lat(), loc.lng(), address);
							});
						});
					}
					return element;
				},
				setCoords: function ( lat, lng, fn ) {
					var loc;
					if( typeof lat === 'number' && typeof lng === 'number' ) {
						loc = setPosition( options, lat, lng);
						if( typeof fn === 'function' ) {
							decodeAddress(loc, options, fn);
						}
					}
					return element;
				},
				destroy: function () {
					if( ! $.isEmptyObject(options) ) {
						if( element.data('style.' + namespace) !== undefined ) {
							element.attr('style', element.data('style.' + namespace));
						} else {
							element.removeAttr('style');
						}
						element.removeData('object.' + namespace);
						element.removeData('style.' + namespace);
						element.unbind('.' + namespace);
						element.empty();
						if( options.listener ) {
							gmaps.event.removeListener(options.listener);
						}
						return element;
					} else {
						return element;
					}
				}
			};
			
			if ( methods[method] && method !== 'init' ) {
				return methods[ method ].apply( this, Array.prototype.slice.call( mainArguments, 1 ) );
			} else {
				if ( typeof method === 'object' || ! method ) {
					return methods.init.apply( this, mainArguments );
				} else {
					$.error( 'Method ' +  method + ' does not exist on the url helper' );
					return;
				}
			}
		});
	};
}(jQuery, navigator, google));
