@php
  $latVal = old('latitude', $lat ?? null);
  $lngVal = old('longitude', $lng ?? null);
@endphp
@push('styles')<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">@endpush
<div class="mb-3">
  <p class="section-sub mb-2">{{ __('soko.picker.help') }}</p>
  <div id="picker-map" class="map-box mb-2"></div>
  <div class="row g-2 mb-2">
    <div class="col-6"><label class="form-label" for="latitude">{{ __('soko.picker.lat') }}</label><input class="form-control" id="latitude" name="latitude" value="{{ $latVal }}" inputmode="decimal"></div>
    <div class="col-6"><label class="form-label" for="longitude">{{ __('soko.picker.lng') }}</label><input class="form-control" id="longitude" name="longitude" value="{{ $lngVal }}" inputmode="decimal"></div>
  </div>
  <label class="form-label" for="gmaps-link">{{ __('soko.picker.link_label') }}</label>
  <input class="form-control mb-1" id="gmaps-link" placeholder="{{ __('soko.picker.link_placeholder') }}" autocomplete="off">
  <div class="form-text mb-2" id="gmaps-msg">{{ __('soko.picker.link_hint') }}</div>
  <button type="button" class="btn btn-outline-dark btn-sm" id="use-my-location">{{ __('soko.picker.use_location') }}</button>
</div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
  var latI = document.getElementById('latitude'), lngI = document.getElementById('longitude');
  var msg = document.getElementById('gmaps-msg'), hint = msg.textContent;
  var has = latI.value !== '' && lngI.value !== '' && !isNaN(parseFloat(latI.value)) && !isNaN(parseFloat(lngI.value));
  var map = L.map('picker-map').setView(has ? [parseFloat(latI.value), parseFloat(lngI.value)] : [-6.8184, 39.2745], has ? 16 : 12);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
  var marker = null;
  function put(lat, lng, zoom) {
    lat = parseFloat(lat); lng = parseFloat(lng);
    if (isNaN(lat) || isNaN(lng) || lat < -90 || lat > 90 || lng < -180 || lng > 180) return false;
    latI.value = lat.toFixed(7); lngI.value = lng.toFixed(7);
    if (marker) { marker.setLatLng([lat, lng]); }
    else {
      marker = L.marker([lat, lng], { draggable: true }).addTo(map);
      marker.on('dragend', function (e) { var p = e.target.getLatLng(); latI.value = p.lat.toFixed(7); lngI.value = p.lng.toFixed(7); });
    }
    map.setView([lat, lng], zoom || map.getZoom());
    return true;
  }
  if (has) put(latI.value, lngI.value, 16);
  map.on('click', function (e) { put(e.latlng.lat, e.latlng.lng); });
  function sync() { if (latI.value !== '' && lngI.value !== '') put(latI.value, lngI.value); }
  latI.addEventListener('change', sync); lngI.addEventListener('change', sync);

  var AT = '\\x40(-?\\d+\\.\\d+),(-?\\d+\\.\\d+)';
  document.getElementById('gmaps-link').addEventListener('input', function () {
    var v = this.value.trim();
    if (!v) { msg.textContent = hint; return; }
    var m = v.match(new RegExp(AT)) || v.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/) || v.match(/[?&](?:q|query|ll|destination)=(-?\d+\.\d+),\s*(-?\d+\.\d+)/) || v.match(/^(-?\d+\.\d+)\s*,\s*(-?\d+\.\d+)$/);
    msg.textContent = (m && put(m[1], m[2], 17)) ? @json(__('soko.picker.ok')) : @json(__('soko.picker.fail'));
  });
  document.getElementById('use-my-location').addEventListener('click', function () {
    if (!navigator.geolocation) { msg.textContent = @json(__('soko.picker.no_geo')); return; }
    navigator.geolocation.getCurrentPosition(function (p) { put(p.coords.latitude, p.coords.longitude, 17); });
  });
})();
</script>
@endpush
