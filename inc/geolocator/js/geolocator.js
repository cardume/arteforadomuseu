var afdmGeolocator = {};

jQuery(function($) {

	afdmGeolocator = function() {

		var geocoder = new google.maps.Geocoder();
		var g = {};

		$.extend(g, {

			codeLatLng: function(lat, lng) {
				var latLng = new google.maps.LatLng(lat, lng);

				geocoder.geocode({'latLng': latLng}, function(results, status) {

					if(status == google.maps.GeocoderStatus.OK) {

						g.result = results[0];
						$.each(results, function(i, result) {
							if(result.types[0] == 'locality') {
								g.locality = result;
								return false;
							}
						});
						$.each(results, function(i, result) {
							if(result.types[0] == 'country') {
								g.country = result;
								return false;
							}
						});

						runCallbacks('geolocated', [g]);

					}

				});

				return g;

			},

			get: {

				component: function(c, nameType) {
					var val;
					$.each(g.result.address_components, function(i, component) {
						if(component.types[0] == c) {
							val = component[nameType];
							return false;
						}
					});
					return val;
				},

				city: function() {
					return g.get.component('locality', 'long_name');
				},

				country: function() {
					return g.get.component('country', 'long_name');
				},

				viewport: {

					city: function() {
						return g.locality.geometry.viewport;
					},

					country: function() {
						return g.country.geometry.viewport;
					}

				},

				center: {

					city: function() {
						return g.locality.geometry.location;
					},

					country: function() {
						return g.locality.geometry.location;
					}

				}
			},

			convertBounds: function(bounds) {

				var NE = g.convertLocation(bounds.getNorthEast());
				var SW = g.convertLocation(bounds.getSouthWest());

				return new MM.Extent(NE, SW);

			},

			convertLocation: function(location) {

				return new MM.Location(location.lat(), location.lng());

			},

			centerMap: function(component, map) {
				var extent = g.convertBounds(g.get.viewport[component]());
				var location = g.convertLocation(g.get.center[component]());
				map.setExtent(extent);
				map.zoom(map.zoom() + 2);
				map.center(location);
			}
		});

		if(navigator.geolocation)
			navigator.geolocation.getCurrentPosition(_success, _error);
		else
			alert('HTML5 geolocation not avaiable');

		function _success(position) {
			g.lat = position.coords.latitude;
			g.lng = position.coords.longitude;
			g.codeLatLng(g.lat, g.lng);
		}

		function _error(error) {
			g.codeLatLng(geolocator_confs.latlng[0], geolocator_confs.latlng[1]);
			console.log(error);
		}

		/*
		 * Callback manager
		 */

		var callbacks = {};

		var createCallback = function(name) {
			callbacks[name] = [];
			g[name] = function(callback) {
				callbacks[name].push(callback);
			}
		}

		var runCallbacks = function(name, args) {
			if(!callbacks[name])
				return false;
			if(!callbacks[name].length)
				return false;

			var _run = function(callbacks) {
				if(callbacks) {
					_.each(callbacks, function(c, i) {
						if(c instanceof Function)
							c.apply(this, args);
					});
				}
			}
			_run(callbacks[name]);
		}

		createCallback('geolocated');

		return g;

	};

	$(document).ready(function() {

		if(geolocator_confs.enable) {
			jeo.mapReady(function(map) {
				var geolocator = afdmGeolocator();
				geolocator.geolocated(function() {
					geolocator.centerMap('city', map);
				});
			});
		}

	});

});