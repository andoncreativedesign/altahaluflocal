(function($) {
    "use strict";

    var map;
    var markerCluster;
    var styles;
    var resizeCenter;
    var markers               = [];
    var placesIDs             = [];
    var transportationMarkers = [];
    var restaurantsMarkers    = [];
    var shoppingMarkers       = [];
    var cafesMarkers          = [];
    var artsMarkers           = [];
    var fitnessMarkers        = [];

    var options = {
        zoom : 14,
        mapTypeId : 'Styled',
        panControl: false,
        zoomControl: true,
        mapTypeControl: false,
        scaleControl: false,
        streetViewControl: false,
        overviewMapControl: false,
        scrollwheel: false,
        zoomControlOptions: {
            position: google.maps.ControlPosition.RIGHT_BOTTOM,
        },
        fullscreenControl: false
    };

    if (map_vars.gmaps_style != '') {
        styles = jQuery.parseJSON(decodeURIComponent(map_vars.gmaps_style));
    }

    var transportationMarkerImage = {
        url: services_vars.theme_url + '/images/transportation-marker.png',
        size: new google.maps.Size(47, 47),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(24, 21),
        scaledSize: { width: 47, height: 47 }
    };
    var restaurantsMarkerImage = {
        url: services_vars.theme_url + '/images/restaurants-marker.png',
        size: new google.maps.Size(47, 47),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(24, 21),
        scaledSize: { width: 47, height: 47 }
    };
    var shoppingMarkerImage = {
        url: services_vars.theme_url + '/images/shopping-marker.png',
        size: new google.maps.Size(47, 47),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(24, 21),
        scaledSize: { width: 47, height: 47 }
    };
    var cafesMarkerImage = {
        url: services_vars.theme_url + '/images/cafes-marker.png',
        size: new google.maps.Size(47, 47),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(24, 21),
        scaledSize: { width: 47, height: 47 }
    };
    var artsMarkerImage = {
        url: services_vars.theme_url + '/images/arts-marker.png',
        size: new google.maps.Size(47, 47),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(24, 21),
        scaledSize: { width: 47, height: 47 }
    };
    var fitnessMarkerImage = {
        url: services_vars.theme_url + '/images/fitness-marker.png',
        size: new google.maps.Size(47, 47),
        origin: new google.maps.Point(0, 0),
        anchor: new google.maps.Point(24, 21),
        scaledSize: { width: 47, height: 47 }
    };

    var info = new InfoBox({
        disableAutoPan: false,
        maxWidth: 200,
        pixelOffset: new google.maps.Size(-70, -44),
        zIndex: null,
        boxClass: 'poi-box',
        boxStyle: {
            'background' : '#fff',
            'opacity'    : 1,
            'padding'    : '5px',
            'box-shadow' : '0 1px 2px 0 rgba(0, 0, 0, 0.13)',
            'width'      : '140px',
            'text-align' : 'center',
            'border-radius' : '3px'
        },
        closeBoxMargin: "28px 26px 0px 0px",
        closeBoxURL: "",
        infoBoxClearance: new google.maps.Size(1, 1),
        pane: "floatPane",
        enableEventPropagation: false
    });

    function CustomMarker(id, latlng, map, classname, html) {
        this.id        = id;
        this.latlng_   = latlng;
        this.classname = classname;
        this.html      = html;

        this.setMap(map);
    }

    CustomMarker.prototype = new google.maps.OverlayView();

    CustomMarker.prototype.draw = function() {
        var me = this;
        var div = this.div_;

        if (!div) {
            div = this.div_ = document.createElement('div');
            div.classList.add(this.classname);
            div.innerHTML = this.html;

            google.maps.event.addDomListener(div, 'click', function(event) {
                google.maps.event.trigger(me, 'click');
            });

            var panes = this.getPanes();
            panes.overlayImage.appendChild(div);
        }

        var point = this.getProjection().fromLatLngToDivPixel(this.latlng_);

        if (point) {
            div.style.left = point.x + 'px';
            div.style.top = point.y + 'px';
        }
    };

    CustomMarker.prototype.remove = function() {
        if (this.div_) {
            this.div_.parentNode.removeChild(this.div_);
            this.div_ = null;
        }
    };

    CustomMarker.prototype.getPosition = function() {
        return this.latlng_;
    };

    CustomMarker.prototype.addActive = function() {
        if (this.div_) {
            $('.pxp-price-marker').removeClass('active');
            this.div_.classList.add('active');
        }
    };

    CustomMarker.prototype.removeActive = function() {
        if (this.div_) {
            this.div_.classList.remove('active');
        }
    };

    function formatPrice(nStr) {
        nStr += '';
        var x = nStr.split('.');
        var x1 = x[0];
        var x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + ' ' + '$2');
        }
        return x1 + x2;
    }

    function addMarkers(props, map) {
        $.each(props, function(i, prop) {
            var latlng = new google.maps.LatLng(prop.lat, prop.lng);

            var price = '';
            var numeralPrice = '';
            var priceFormat = '';
            var priceFormat_ = '';
            var priceCurrency = '';
            var priceLabel = '';
            var finalPrice = '';
            var feat = '';

            if (prop.price_raw != '') {
                if ($.isNumeric(prop.price_raw)) {
                    if (prop.price_raw > 999999) {
                        priceFormat = numeral(prop.price_raw).format('0.0a');
                    } else {
                        if (prop.price_raw.slice(-3) == '000') {
                            priceFormat = numeral(prop.price_raw).format('0a');
                        } else {
                            priceFormat = numeral(prop.price_raw).format('0.0a');
                        }
                    }

                    priceFormat_ = formatPrice(prop.price_raw);
                    priceCurrency = prop.currency;
                    priceLabel = prop.price_label;
                } else {
                    priceFormat = prop.price_raw;
                    priceFormat_ = prop.price_raw;
                    priceCurrency = '';
                    priceLabel = '';
                }

                if (prop.currency_pos == 'before') {
                    numeralPrice = priceCurrency + priceFormat;
                    price = priceCurrency + priceFormat_ + ' ' + priceLabel;
                } else {
                    numeralPrice = priceFormat + ' ' + priceCurrency;
                    price = priceFormat_ + priceCurrency + ' ' + priceLabel;
                }

                if (map_vars.marker_price_format == 'long') {
                    finalPrice = price;
                } else {
                    finalPrice = numeralPrice;
                }
            } else {
                finalPrice = prop.title;
            }

            if (prop.beds != '') {
                feat += prop.beds + ' ' + prop.beds_label + '<span>|</span>';
            }
            if (prop.baths != '') {
                feat += prop.baths + ' ' + prop.baths_label + '<span>|</span>';
            }
            if (prop.size != '') {
                feat += prop.size + ' ' + prop.unit;
            }

            var html = '<div class="pxp-marker-short-price">' + finalPrice + '</div>' + 
                        '<a href="' + prop.link + '" class="pxp-marker-details">' + 
                            '<div class="pxp-marker-details-fig pxp-cover" style="background-image: url(' + prop.photo + ');"></div>' + 
                            '<div class="pxp-marker-details-info">' + 
                                '<div class="pxp-marker-details-info-title">' + prop.title + '</div>' + 
                                '<div class="pxp-marker-details-info-price">' + prop.price + '</div>' + 
                                '<div class="pxp-marker-details-info-feat">' + feat + '</div>' + 
                            '</div>' + 
                        '</a>';

            var marker = new CustomMarker(prop.id, latlng, map, 'pxp-price-marker', html);

            marker.id = prop.id;
            markers.push(marker);
        });
    }

    function getPOIs(pos, map, type) {
        var service   = new google.maps.places.PlacesService(map);
        var bounds    = map.getBounds();
        var types     = new Array();

        switch(type) {
            case 'transportation':
                types = ['bus_station', 'subway_station', 'train_station', 'transit_station', 'airport'];
                break;
            case 'restaurants':
                types = ['restaurant'];
                break;
            case 'shopping':
                types = ['bicycle_store', 'book_store', 'clothing_store', 'convenience_store', 'department_store', 'electronics_store', 'florist', 'furniture_store', 'hardware_store', 'home_goods_store', 'jewelry_store', 'liquor_store', 'shoe_store', 'shopping_mall', 'store', 'supermarket'];
                break;
            case 'cafes':
                types = ['bar', 'cafe'];
                break;
            case 'arts':
                types = ['amusement_park', 'aquarium', 'art_gallery', 'bowling_alley', 'casino', 'movie_rental', 'movie_theater', 'museum', 'stadium', 'zoo'];
                break;
            case 'fitness':
                types = ['gym'];
                break;
        }

        $.each(types, function(i, t) {
            service.nearbySearch({
                location: pos,
                bounds: bounds,
                radius: 2000,
                types: [t]
            }, function poiCallback(results, status) {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    for (var i = 0; i < results.length; i++) {
                        if (jQuery.inArray(results[i].place_id, placesIDs) == -1) {
                            createPOI(results[i], map, type);
                            placesIDs.push(results[i].place_id);
                        }
                    }
                }
            });
        });
    }

    function createPOI(place, map, type) {
        var placeLoc = place.geometry.location;
        var poiMarker;

        switch (type) {
            case 'transportation':
                poiMarker = new google.maps.Marker({
                    map: map,
                    position: placeLoc,
                    icon: transportationMarkerImage,
                });
                transportationMarkers.push(poiMarker);
                break;
            case 'restaurants':
                poiMarker = new google.maps.Marker({
                    map: map,
                    position: placeLoc,
                    icon: restaurantsMarkerImage,
                });
                restaurantsMarkers.push(poiMarker);
                break;
            case 'shopping':
                poiMarker = new google.maps.Marker({
                    map: map,
                    position: placeLoc,
                    icon: shoppingMarkerImage,
                });
                shoppingMarkers.push(poiMarker);
                break;
            case 'cafes':
                poiMarker = new google.maps.Marker({
                    map: map,
                    position: placeLoc,
                    icon: cafesMarkerImage,
                });
                cafesMarkers.push(poiMarker);
                break;
            case 'arts':
                poiMarker = new google.maps.Marker({
                    map: map,
                    position: placeLoc,
                    icon: artsMarkerImage,
                });
                artsMarkers.push(poiMarker);
                break;
            case 'fitness':
                poiMarker = new google.maps.Marker({
                    map: map,
                    position: placeLoc,
                    icon: fitnessMarkerImage,
                });
                fitnessMarkers.push(poiMarker);
                break;
        }

        google.maps.event.addListener(poiMarker, 'mouseover', function() {
            info.setContent(place.name);
            info.open(map, this);
        });
        google.maps.event.addListener(poiMarker, 'mouseout', function() {
            info.open(null,null);
        });
    }

    function tooglePOIs(pmap, type) {
        for (var i = 0; i < type.length; i++) {
            if (type[i].getMap() != null) {
                type[i].setMap(null);
            } else {
                type[i].setMap(pmap);
            }
        }
    }

    function PoiControls(controlDiv, pmap, center) {
        controlDiv.style.clear = 'both';

        // Set CSS for transportation POI
        var transportationUI = document.createElement('div');
        transportationUI.id = 'transportationUI';
        transportationUI.title = map_vars.transportation_title;
        controlDiv.appendChild(transportationUI);
        var transportationIcon = document.createElement('div');
        transportationIcon.id = 'transportationIcon';
        transportationIcon.innerHTML = '<span class="fa fa-subway"></span>';
        transportationUI.appendChild(transportationIcon);

        // Set CSS for restaurants POI
        var restaurantsUI = document.createElement('div');
        restaurantsUI.id = 'restaurantsUI';
        restaurantsUI.title = map_vars.restaurants_title;
        controlDiv.appendChild(restaurantsUI);
        var restaurantsIcon = document.createElement('div');
        restaurantsIcon.id = 'restaurantsIcon';
        restaurantsIcon.innerHTML = '<span class="fa fa-cutlery"></span>';
        restaurantsUI.appendChild(restaurantsIcon);

        // Set CSS for shopping POI
        var shoppingUI = document.createElement('div');
        shoppingUI.id = 'shoppingUI';
        shoppingUI.title = map_vars.shopping_title;
        controlDiv.appendChild(shoppingUI);
        var shoppingIcon = document.createElement('div');
        shoppingIcon.id = 'shoppingIcon';
        shoppingIcon.innerHTML = '<span class="fa fa-shopping-basket"></span>';
        shoppingUI.appendChild(shoppingIcon);

        // Set CSS for cafes & bars POI
        var cafesUI = document.createElement('div');
        cafesUI.id = 'cafesUI';
        cafesUI.title = map_vars.cafes_title;
        controlDiv.appendChild(cafesUI);
        var cafesIcon = document.createElement('div');
        cafesIcon.id = 'cafesIcon';
        cafesIcon.innerHTML = '<span class="fa fa-coffee"></span>';
        cafesUI.appendChild(cafesIcon);

        // Set CSS for arts & entertainment POI
        var artsUI = document.createElement('div');
        artsUI.id = 'artsUI';
        artsUI.title = map_vars.arts_title;
        controlDiv.appendChild(artsUI);
        var artsIcon = document.createElement('div');
        artsIcon.id = 'artsIcon';
        artsIcon.innerHTML = '<span class="fa fa-ticket"></span>';
        artsUI.appendChild(artsIcon);

        // Set CSS for fitness POI
        var fitnessUI = document.createElement('div');
        fitnessUI.id = 'fitnessUI';
        fitnessUI.title = map_vars.fitness_title;
        controlDiv.appendChild(fitnessUI);
        var fitnessIcon = document.createElement('div');
        fitnessIcon.id = 'fitnessIcon';
        fitnessIcon.innerHTML = '<span class="fa fa-heartbeat"></span>';
        fitnessUI.appendChild(fitnessIcon);

        transportationUI.addEventListener('click', function() {
            var transportationUI_ = this;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');

                tooglePOIs(pmap, transportationMarkers);
            } else {
                $(this).addClass('active');

                getPOIs(center, pmap, 'transportation');
                tooglePOIs(pmap, transportationMarkers);
            }
            google.maps.event.addListener(pmap, 'bounds_changed', function() {
                if ($(transportationUI_).hasClass('active')) {
                    var newCenter = pmap.getCenter();
                    getPOIs(newCenter, pmap, 'transportation');
                }
            });
        });
        restaurantsUI.addEventListener('click', function() {
            var restaurantsUI_ = this;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');

                tooglePOIs(pmap, restaurantsMarkers);
            } else {
                $(this).addClass('active');

                getPOIs(center, pmap, 'restaurants');
                tooglePOIs(pmap, restaurantsMarkers);
            }
            google.maps.event.addListener(pmap, 'bounds_changed', function() {
                if ($(restaurantsUI_).hasClass('active')) {
                    var newCenter = pmap.getCenter();
                    getPOIs(newCenter, pmap, 'restaurants');
                }
            });
        });
        shoppingUI.addEventListener('click', function() {
            var shoppingUI_ = this;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');

                tooglePOIs(pmap, shoppingMarkers);
            } else {
                $(this).addClass('active');

                getPOIs(center, pmap, 'shopping');
                tooglePOIs(pmap, shoppingMarkers);
            }
            google.maps.event.addListener(pmap, 'bounds_changed', function() {
                if ($(shoppingUI_).hasClass('active')) {
                    var newCenter = pmap.getCenter();
                    getPOIs(newCenter, pmap, 'shopping');
                }
            });
        });
        cafesUI.addEventListener('click', function() {
            var cafesUI_ = this;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');

                tooglePOIs(pmap, cafesMarkers);
            } else {
                $(this).addClass('active');

                getPOIs(center, pmap, 'cafes');
                tooglePOIs(pmap, cafesMarkers);
            }
            google.maps.event.addListener(pmap, 'bounds_changed', function() {
                if ($(cafesUI_).hasClass('active')) {
                    var newCenter = pmap.getCenter();
                    getPOIs(newCenter, pmap, 'cafes');
                }
            });
        });
        artsUI.addEventListener('click', function() {
            var artsUI_ = this;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');

                tooglePOIs(pmap, artsMarkers);
            } else {
                $(this).addClass('active');

                getPOIs(center, pmap, 'arts');
                tooglePOIs(pmap, artsMarkers);
            }
            google.maps.event.addListener(pmap, 'bounds_changed', function() {
                if ($(artsUI_).hasClass('active')) {
                    var newCenter = pmap.getCenter();
                    getPOIs(newCenter, pmap, 'arts');
                }
            });
        });
        fitnessUI.addEventListener('click', function() {
            var fitnessUI_ = this;
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');

                tooglePOIs(pmap, fitnessMarkers);
            } else {
                $(this).addClass('active');

                getPOIs(center, pmap, 'fitness');
                tooglePOIs(pmap, fitnessMarkers);
            }
            google.maps.event.addListener(pmap, 'bounds_changed', function() {
                if ($(fitnessUI_).hasClass('active')) {
                    var newCenter = pmap.getCenter();
                    getPOIs(newCenter, pmap, 'fitness');
                }
            });
        });
    }

    function setPOIControls(pmap, center) {
        var poiControlDiv = document.createElement('div');
        var poiControl = new PoiControls(poiControlDiv, pmap, center);

        poiControlDiv.index = 1;
        poiControlDiv.id = 'poiContainer';
        pmap.controls[google.maps.ControlPosition.LEFT_TOP].push(poiControlDiv);
    }

    if ($('#results-map').length > 0) {
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: map_vars.ajaxurl,
            data: {
                'action'               : 'resideo_get_searched_properties',
                'security'             : $('#resultsMapSecurity').val(),
                'default_lat'          : map_vars.gmaps_lat,
                'default_lng'          : map_vars.gmaps_lng,
                'default_zoom'         : map_vars.gmaps_zoom,
                'search_status'        : map_vars.search_status,
                'search_address'       : map_vars.search_address,
                'search_street_no'     : map_vars.search_street_no,
                'search_street'        : map_vars.search_street,
                'search_neighborhood'  : map_vars.search_neighborhood,
                'search_city'          : map_vars.search_city,
                'search_state'         : map_vars.search_state,
                'search_zip'           : map_vars.search_zip,
                'search_type'          : map_vars.search_type,
                'search_price_min'     : map_vars.search_price_min,
                'search_price_max'     : map_vars.search_price_max,
                'search_beds'          : map_vars.search_beds,
                'search_baths'         : map_vars.search_baths,
                'search_size_min'      : map_vars.search_size_min,
                'search_size_max'      : map_vars.search_size_max,
                'search_keywords'      : map_vars.search_keywords,
                'search_id'            : map_vars.search_id,
                'search_amenities'     : map_vars.search_amenities,
                'search_custom_fields' : map_vars.search_custom_fields,
                'featured'             : map_vars.featured,
                'sort'                 : map_vars.sort,
                'page'                 : map_vars.page,
            },
            success: function(data) {
                var center = new google.maps.LatLng(map_vars.default_lat, map_vars.default_lng);
                map = new google.maps.Map(document.getElementById('results-map'), options);
                var styledMapType = new google.maps.StyledMapType(styles, {
                    name : 'Styled',
                });
                map.mapTypes.set('Styled', styledMapType);
                map.setCenter(center);
                map.setZoom(parseInt(map_vars.default_zoom));

                if (data.getprops === true) {
                    addMarkers(data.props, map);

                    map.fitBounds(markers.reduce(function(bounds, marker) {
                        return bounds.extend(marker.getPosition());
                    }, new google.maps.LatLngBounds()));

                    markerCluster = new MarkerClusterer(map, markers, {
                        maxZoom: 18,
                        gridSize: 60,
                        styles: [
                            {
                                width: 40,
                                height: 40,
                            },
                            {
                                width: 60,
                                height: 60,
                            },
                            {
                                width: 80,
                                height: 80,
                            },
                        ]
                    });

                    google.maps.event.trigger(map, 'resize');
                    resizeCenter = map.getCenter();

                    $('.pxp-results-card').each(function(i) {
                        var propID = $(this).attr('data-prop');

                        $(this).on('mouseenter', function() {
                            if (map) {
                                var targetMarker = $.grep(markers, function(e) {
                                    return e.id == propID;
                                });
        
                                if(targetMarker.length > 0) {
                                    targetMarker[0].addActive();
                                    map.setCenter(targetMarker[0].latlng_);
                                }
                            }
                        });
                        $(this).on('mouseleave', function() {
                            var targetMarker = $.grep(markers, function(e) {
                                return e.id == propID;
                            });
        
                            if(targetMarker.length > 0) {
                                targetMarker[0].removeActive();
                            }
                        });
                    });
                }

                if (map_vars.gmaps_poi != '') {
                    setPOIControls(map, map.getCenter());
                }
            },
            error: function(errorThrown) {}
        });
    }
})(jQuery);