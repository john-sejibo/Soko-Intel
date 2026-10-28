@php($shops = $shops ?? [])
@push('styles')<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css">@endpush
<div id="{{ $mapId }}" class="map-box" role="region" aria-label="{{ __('soko.map.title') }}"></div>
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
(function () {
  var markets = @json($markets);
  var shops = @json($shops);
  var T = { directions: @json(__('soko.map.directions')), price: @json(__('soko.map.price_here')), verified: @json(__('soko.map.verified')), none: @json(__('soko.map.none')) };
  function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) { return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }
  var map = L.map(@json($mapId)).setView([-6.80, 39.27], 11);
  L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '&copy; OpenStreetMap' }).addTo(map);
  var bounds = [];
  function link(url) { return url ? '<br><a href="' + esc(url) + '" target="_blank" rel="noopener">' + esc(T.directions) + '</a>' : ''; }
  markets.forEach(function (m) {
    var html = '<strong>' + esc(m.name) + '</strong>' + (m.area ? '<br>' + esc(m.area) : '');
    if (m.price) html += '<br>' + esc(T.price) + ': <strong>TSh ' + Number(m.price).toLocaleString() + '</strong>';
    var c = L.circleMarker([m.lat, m.lng], { radius: 9, color: '#086460', weight: 2, fillColor: '#0b7a75', fillOpacity: .85 }).addTo(map).bindPopup(html + link(m.url));
    if (m.price) c.bindTooltip('TSh ' + Number(m.price).toLocaleString(), { permanent: true, direction: 'top', offset: [0, -8] });
    bounds.push([m.lat, m.lng]);
  });
  shops.forEach(function (s) {
    var html = '<strong>' + esc(s.name) + '</strong>' + (s.verified ? ' <small>(' + esc(T.verified) + ')</small>' : '') + (s.market ? '<br>' + esc(s.market) : '') + (s.phone ? '<br>' + esc(s.phone) : '');
    L.circleMarker([s.lat, s.lng], { radius: 6, color: '#8a6d00', weight: 2, fillColor: '#e8b931', fillOpacity: .95 }).addTo(map).bindPopup(html + link(s.url));
    bounds.push([s.lat, s.lng]);
  });
  if (bounds.length > 1) map.fitBounds(bounds, { padding: [30, 30], maxZoom: 15 });
  else if (bounds.length === 1) map.setView(bounds[0], 15);
})();
</script>
@endpush
