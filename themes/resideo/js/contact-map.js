(function($) {
    "use strict";

    var map;
    var marker = [];
    var styles;

    var locationsElem = $('.pxp-contact-locations-select');
    var propLat, propLng;
    if (locationsElem.length > 0) {
        var selectedLocation = locationsElem.find('option:selected');

        propLat = selectedLocation.attr('data-lat');
        propLng = selectedLocation.attr('data-lng');

        locationsElem.change(function() {
            var selectedLocation = $(this).find('option:selected');

            propLat = selectedLocation.attr('data-lat');
            propLng = selectedLocation.attr('data-lng');

            var center = new google.maps.LatLng(propLat, propLng);
            map.setCenter(center);
        });
    }

    var options = {
        zoom : 14,
        mapTypeId : 'Styled',
        panControl: false,
        zoomControl: true,
        mapTypeControl: true,
        scaleControl: false,
        streetViewControl: true,
        overviewMapControl: false,
        scrollwheel: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM,
        },
        fullscreenControl: true,
    };

    if (map_contact_vars.gmaps_style != '') {
        styles = jQuery.parseJSON(decodeURIComponent(map_contact_vars.gmaps_style));
    }

    function CustomMarker(latlng, map, classname) {
        this.latlng_   = latlng;
        this.classname = classname;

        this.setMap(map);
    }

    CustomMarker.prototype = new google.maps.OverlayView();

    CustomMarker.prototype.draw = function() {
        var me = this;
        var div = this.div_;

        if (!div) {
            div = this.div_ = document.createElement('div');
            div.classList.add(this.classname);

            var panes = this.getPanes();
            panes.overlayImage.appendChild(div);
        }

        var point = this.getProjection().fromLatLngToDivPixel(this.latlng_);

        if (point) {
            div.style.left = point.x + 'px';
            div.style.top = point.y + 'px';
        }
    };

    function addContactMarker(propLat, propLng, map) {
        var latlng = new google.maps.LatLng(propLat, propLng);
        marker = new CustomMarker(latlng, map, 'pxp-single-marker');
    }

    setTimeout(function() {
        if ($('#pxp-contact-map').length > 0) {
            map = new google.maps.Map(document.getElementById('pxp-contact-map'), options);
            var styledMapType = new google.maps.StyledMapType(styles, {
                name : 'Styled',
            });
            var center = new google.maps.LatLng(propLat, propLng);

            map.mapTypes.set('Styled', styledMapType);
            map.setCenter(center);
            map.setZoom(15);

            locationsElem.find('option').each(function() {
                var lat = $(this).attr('data-lat');
                var lng = $(this).attr('data-lng');

                addContactMarker(lat, lng, map);
            });


            google.maps.event.trigger(map, 'resize');
        }

        if ($('#pxp-contact-office-map').length > 0) {
            map = new google.maps.Map(document.getElementById('pxp-contact-office-map'), options);
            var styledMapType = new google.maps.StyledMapType(styles, {
                name : 'Styled',
            });

            propLat = $('#pxp-contact-office-map').attr('data-lat');
            propLng = $('#pxp-contact-office-map').attr('data-lng');

            var center = new google.maps.LatLng(propLat, propLng);

            map.mapTypes.set('Styled', styledMapType);
            map.setCenter(center);
            map.setZoom(15);

            addContactMarker(propLat, propLng, map);

            google.maps.event.trigger(map, 'resize');
        }
    }, 300);
})(jQuery);