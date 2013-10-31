var afdmInstagramLayer;

(function($) {

	var data,
		map,
		markerLayer,
		markers = [],
		icon = L.Icon.extend({}),
		markerIcon;

	$.get(instagram_settings.ajaxurl + '?action=instagram_data', function(data) {

		if($.isReady)
			instagramMap(data);
		else {
			$(document).ready(function() {
				instagramMap(data);
			});
		}

	},'json');

	function instagramMap(data) {

		map = L.map('instagram_map');
		markerLayer = afdmInstagramLayer = L.featureGroup();
		L.tileLayer('http://{s}.tiles.mapbox.com/v3/andredeak.map-pg19cc4b/{z}/{x}/{y}.png').addTo(map);

		markerIcon = new icon({
			iconUrl: instagram_settings.iconUrl,
			iconSize: [30,30],
			iconAnchor: [15,30],
			popupAnchor: [0,-33]
		});

		$.each(data, function(i, item) {

			if(item.location && item.id !== '223294677306317009_30601285') {

				var marker = L.marker([item.location.latitude, item.location.longitude]);

				marker.setIcon(markerIcon);

				var popup = '<img src="' + item.images.low_resolution.url + '" />';

				if(item.caption) {
					popup += '<p>' + item.caption.text + '<span class="author">' + item.caption.from.full_name + '</span></p>';
				}

				marker.bindPopup(popup);

				marker.on('mouseover', function(e) {
					e.target.openPopup();
				});
				marker.on('mouseout', function(e) {
					e.target.closePopup();
				});
				marker.on('click', function(e) {
					window.open(item.link, '_blank');
					return false;
				});

				marker.addTo(markerLayer);
				markers.push(marker);

			}

		});

		markerLayer.addTo(map);

		map.fitBounds(markerLayer.getBounds());
		map.setZoom(map.getZoom() -1);

	}

})(jQuery);