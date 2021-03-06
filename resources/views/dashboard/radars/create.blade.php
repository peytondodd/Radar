@extends('dashboard.app')
@section('title','Create a radar')
@section('description','')
@section('content')
<div class="col-md-12">
    <div class="panel panel-default">
        <div class="panel-body">
            {{ Form::open(['route' => 'dashboard.radar.store']) }}
            <div class="col-md-12" style="margin-bottom:20px;">
                <div id="map">
                </div>
                <input type="hidden" required name="latitude" value="">
                <input type="hidden" required name="longitude" value="">
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    <button class="btn btn-primary">Save</button>
                </div>
            </div>
            {{ Form::close() }}
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
    var uluru = {lat: -25.363, lng: 131.044};
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 4,
        center: uluru
    });
    addMarker(uluru);
    google.maps.event.addListener(map, 'click', function( event ){
        var point = {lat: event.latLng.lat(), lng: event.latLng.lng()};
        clearMarkers();
        addMarker(point);
        $('input[name=latitude]').val(event.latLng.lat());
        $('input[name=longitude]').val(event.latLng.lng());
        // console.log("Latitude: "+event.latLng.lat()+" "+", longitude: "+event.latLng.lng());
    });
}
</script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBAB-0Nw1JMhcdgdkXZgs2K_riwryazO0&callback=initMap">
</script>
@stop

@section('stylesheets')
<style media="screen">
#map {
    height: 400px;
    width: 100%;
}
</style>
@stop
