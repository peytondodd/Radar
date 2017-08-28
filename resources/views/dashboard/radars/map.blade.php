@extends('dashboard.app')
@section('title','Radars all on map')
@section('content')
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="col-md-12" style="margin-bottom:20px;">
                <div id="map">
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('scripts')
<script>
var map;
var markers = [];

function addMarker(location) {
    var marker = new google.maps.Marker({
        position: location,
        map: map
    });
    markers.push(marker);
}
// Sets the map on all markers in the array.
function setMapOnAll(map) {
    for (var i = 0; i < markers.length; i++) {
        markers[i].setMap(map);
    }
}
function clearMarkers() {
    setMapOnAll(null);
}
// Deletes all markers in the array by removing references to them.
function deleteMarkers() {
    clearMarkers();
    markers = [];
}

function initMap() {
    var uluru = {
        lat: {{ $resources->first() && $resources->first()->location ? $resources->first()->location->latitude : '' }},
        lng: {{ $resources->first() && $resources->first()->location ? $resources->first()->location->longitude : '' }}
    };
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 2,
        center: uluru
    });

    @foreach($resources as $resource)
        addMarker({
            lat: {{ $resource->location ? $resource->location->latitude : '' }},
            lng: {{ $resource->location ? $resource->location->longitude : '' }}
        });
    @endforeach
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBAB-0Nw1JMhcdgdkXZgs2K_riwryazO0&callback=initMap">
</script>
@stop

@section('stylesheets')
<style media="screen">
#map {
    height: 500px;
    width: 100%;
}
</style>
@stop
