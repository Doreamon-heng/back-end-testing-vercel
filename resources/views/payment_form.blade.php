<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Payment</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIINfQsd1xQJtW8B6zD8Q3XhL7UqrqA+4vM=" crossorigin="">
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: Arial, sans-serif; background: #f4f7fb; color: #111827; }
        .page { min-height: 100vh; display: flex; justify-content: center; align-items: center; padding: 30px; }
        .card { width: 100%; max-width: 720px; background: #fff; border-radius: 24px; box-shadow: 0 24px 60px rgba(15, 23, 42, 0.08); padding: 32px; }
        h1 { margin-top: 0; font-size: 28px; }
        .row { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
        .field { margin-bottom: 18px; }
        label { display: block; margin-bottom: 8px; font-size: 14px; color: #374151; }
        select, input, textarea { width: 100%; padding: 12px 14px; border: 1px solid #d1d5db; border-radius: 12px; font-size: 15px; color: #111827; }
        select:focus, input:focus, textarea:focus { outline: none; border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12); }
        .button { width: 100%; padding: 14px 18px; border: none; border-radius: 12px; background: #2563eb; color: #fff; font-size: 16px; font-weight: 600; cursor: pointer; }
        .button:hover { background: #1d4ed8; }
        .location-field { grid-column: 1 / -1; }
        .location-controls { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
        .location-button { padding: 9px 13px; border: 1px solid #2563eb; border-radius: 10px; background: #eff6ff; color: #1d4ed8; font-size: 13px; font-weight: 600; cursor: pointer; }
        .location-status { display: block; margin-top: 6px; font-size: 12px; color: #6b7280; }
        .map-picker { display: none; margin-top: 12px; padding: 14px; border: 1px solid #bfdbfe; border-radius: 14px; background: #f8fbff; }
        .map-picker.is-open { display: block; }
        .map-heading { margin: 0 0 10px; font-size: 14px; font-weight: 600; color: #1e3a8a; }
        .location-map { height: 360px; border: 1px solid #dbeafe; border-radius: 10px; overflow: hidden; background: #e5e7eb; }
        .map-actions { display: flex; justify-content: flex-end; gap: 8px; margin-top: 12px; }
        .map-save-button { padding: 8px 12px; border: none; border-radius: 10px; background: #2563eb; color: #fff; font-size: 13px; font-weight: 600; cursor: pointer; }
        .notice { margin-top: 16px; padding: 16px; border-radius: 14px; background: #eff6ff; color: #1d4ed8; font-size: 14px; }
        .alert { margin-top: 16px; padding: 16px; border-radius: 14px; background: #f8fafc; border: 1px solid #cbd5e1; }
        .success { background: #ecfdf5; border-color: #a7f3d0; color: #166534; }
        .error { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
        .qr { margin-top: 24px; text-align: center; }
        .qr img { width: 280px; height: 280px; border-radius: 18px; border: 1px solid #e5e7eb; }
        .detail { margin-top: 18px; padding: 16px; background: #111827; color: #f8fafc; border-radius: 14px; overflow-x: auto; font-size: 13px; }
        @media (max-width: 760px) { .row { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="page">
    <div class="card">
        <h1>Process Payment</h1>
        <p class="notice">Select a product, enter your order details, and process the payment using Bakong.</p>

        @if(isset($result) && isset($result['error']) && $result['error'])
            <div class="alert error">{{ $result['message'] ?? ($result['bakong']['message'] ?? 'Payment creation failed.') }}</div>
            @if(!empty($result['debug']))
                <div class="detail" style="background:#fff;color:#111827;margin-top:12px;">
                    <strong>Bakong debug:</strong>
                    <pre style="white-space:pre-wrap;color:#111827;background:#f8fafc;padding:12px;border-radius:8px;border:1px solid #e5e7eb;">{{ json_encode($result['debug'], JSON_PRETTY_PRINT) }}</pre>
                </div>
            @endif
        @endif

        <form method="POST" action="{{ route('payment.web.submit') }}" id="paymentForm">
            @csrf
            <div class="row">
                <div class="field">
                    <label for="product_id">Product</label>
                    <select id="product_id" name="product_id" required onchange="setProductAmount()">
                        <option value="">Select a product</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->price }}" {{ old('product_id', $payload['product_id'] ?? '') == $product->id ? 'selected' : '' }}>
                                {{ $product->name }} ({{ number_format($product->price, 2) }} {{ strtoupper($payload['currency'] ?? 'USD') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="field">
                    <label for="currency">Currency</label>
                    <select id="currency" name="currency" required>
                        <option value="USD" {{ old('currency', $payload['currency'] ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="KHR" {{ old('currency', $payload['currency'] ?? 'USD') == 'KHR' ? 'selected' : '' }}>KHR</option>
                    </select>
                </div>
                <div class="field">
                    <label for="order_id">Order ID</label>
                    <input id="order_id" name="order_id" type="text" value="{{ old('order_id', $payload['order_id'] ?? 'order-' . time()) }}" required>
                </div>
                <div class="field">
                    <label for="amount">Amount</label>
                    <input id="amount" name="amount" type="number" step="0.01" value="{{ old('amount', $payload['amount'] ?? '') }}" placeholder="Leave blank to use product price">
                </div>
                <div class="field">
                    <label for="receiver_phone">Receiver Phone</label>
                    <input id="receiver_phone" name="receiver_phone" type="text" value="{{ old('receiver_phone', $payload['receiver_phone'] ?? '') }}">
                </div>
                <div class="field location-field">
                    <label for="receiver_location">Receiver Location</label>
                    <input id="receiver_location" name="receiver_location" type="text" value="{{ old('receiver_location', $payload['receiver_location'] ?? '') }}">
                    <div class="location-controls">
                        <button type="button" class="location-button" id="locationButton">Use live location</button>
                        <button type="button" class="location-button" id="mapButton">Choose on map</button>
                    </div>
                    <small class="location-status" id="locationStatus" aria-live="polite"></small>
                    <div class="map-picker" id="mapPicker">
                        <p class="map-heading">Click anywhere on the map to set the receiver location.</p>
                        <div class="location-map" id="locationMap" aria-label="Choose receiver location on map"></div>
                        <div class="map-actions">
                            <button type="button" class="map-save-button" id="mapSaveButton" disabled>Use selected location</button>
                        </div>
                    </div>
                </div>
                <div class="field">
                    <label for="customer_name">Customer Name</label>
                    <input id="customer_name" name="customer_name" type="text" value="{{ old('customer_name', $payload['customer_name'] ?? '') }}">
                </div>
                <div class="field">
                    <label for="customer_email">Customer Email</label>
                    <input id="customer_email" name="customer_email" type="email" value="{{ old('customer_email', $payload['customer_email'] ?? '') }}">
                </div>
                <div class="field">
                    <label for="customer_phone">Customer Phone</label>
                    <input id="customer_phone" name="customer_phone" type="text" value="{{ old('customer_phone', $payload['customer_phone'] ?? '') }}">
                </div>
                <div class="field">
                    <label for="customer_address">Customer Address</label>
                    <input id="customer_address" name="customer_address" type="text" value="{{ old('customer_address', $payload['customer_address'] ?? '') }}">
                </div>
                <div class="field">
                    <label for="account_name">Account Name</label>
                    <input id="account_name" name="account_name" type="text" value="{{ old('account_name', $payload['account_name'] ?? '') }}">
                </div>
            </div>

            <button type="submit" class="button">Process Payment</button>
        </form>

        @if(isset($qr_url) && $qr_url)
            <div class="qr">
                <h2>Scan this QR Code</h2>
                <img src="{{ $qr_url }}" alt="Payment QR Code">
                <p class="notice">Use your Bakong or mobile banking app to scan and complete payment.</p>
            </div>

            @if(!empty($qr_string))
                <div class="detail"><pre>{{ $qr_string }}</pre></div>
            @endif
        @endif

        @if(isset($result) && !empty($result['payment']))
            <div class="alert success">
                Payment record created successfully. Payment ID: {{ $result['payment']['id'] ?? 'N/A' }}
            </div>
        @endif
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    function setProductAmount() {
        const select = document.getElementById('product_id');
        const amount = document.getElementById('amount');
        const selected = select.options[select.selectedIndex];
        if (selected && selected.dataset.price) {
            amount.value = selected.dataset.price;
        }
    }

    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function () {
            const button = document.querySelector('.button');
            button.disabled = true;
            button.textContent = 'Processing payment...';
        });
    }

    const locationButton = document.getElementById('locationButton');
    const locationInput = document.getElementById('receiver_location');
    const locationStatus = document.getElementById('locationStatus');
    const mapButton = document.getElementById('mapButton');
    const mapPicker = document.getElementById('mapPicker');
    const mapSaveButton = document.getElementById('mapSaveButton');
    let locationWatchId = null;
    let locationMap = null;
    let mapMarker = null;
    let selectedMapLocation = null;

    function stopLocationTracking() {
        if (locationWatchId !== null) {
            navigator.geolocation.clearWatch(locationWatchId);
            locationWatchId = null;
        }
        locationButton.textContent = 'Use live location';
    }

    if (locationButton && locationInput && locationStatus) {
        locationButton.addEventListener('click', function () {
            if (!navigator.geolocation) {
                locationStatus.textContent = 'Location is not supported by this browser.';
                return;
            }

            if (locationWatchId !== null) {
                stopLocationTracking();
                locationStatus.textContent = 'Live location tracking stopped.';
                return;
            }

            locationStatus.textContent = 'Requesting your location…';
            locationWatchId = navigator.geolocation.watchPosition(
                function (position) {
                    const latitude = position.coords.latitude.toFixed(6);
                    const longitude = position.coords.longitude.toFixed(6);
                    const accuracy = Math.round(position.coords.accuracy);
                    locationInput.value = `${latitude}, ${longitude} (±${accuracy} m)`;
                    locationStatus.textContent = 'Live location is updating.';
                    locationButton.textContent = 'Stop live tracking';
                },
                function (error) {
                    const messages = {
                        1: 'Location permission was denied.',
                        2: 'Your location is currently unavailable.',
                        3: 'Location request timed out.',
                    };
                    locationStatus.textContent = messages[error.code] || 'Unable to get your location.';
                    stopLocationTracking();
                },
                {
                    enableHighAccuracy: true,
                    maximumAge: 5000,
                    timeout: 15000,
                }
            );
        });
    }

    function setMapLocation(latitude, longitude) {
        selectedMapLocation = { latitude, longitude };
        if (mapMarker) {
            mapMarker.setLatLng([latitude, longitude]);
        } else {
            mapMarker = L.marker([latitude, longitude]).addTo(locationMap);
        }
        mapSaveButton.disabled = false;
    }

    if (mapButton && mapPicker && mapSaveButton && locationInput && locationStatus) {
        mapButton.addEventListener('click', function () {
            mapPicker.classList.toggle('is-open');

            if (!mapPicker.classList.contains('is-open')) {
                mapButton.textContent = 'Choose on map';
                return;
            }

            if (!window.L) {
                locationStatus.textContent = 'The map could not be loaded. Check your internet connection.';
                return;
            }

            if (!locationMap) {
                locationMap = L.map('locationMap').setView([11.5564, 104.9282], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; OpenStreetMap contributors',
                }).addTo(locationMap);

                locationMap.on('click', function (event) {
                    setMapLocation(event.latlng.lat, event.latlng.lng);
                    locationStatus.textContent = 'Location selected. Click “Use selected location” to save it.';
                });
            }

            setTimeout(() => locationMap.invalidateSize(true), 150);
            mapButton.textContent = 'Close map';
        });

        mapSaveButton.addEventListener('click', function () {
            if (!selectedMapLocation) {
                return;
            }

            locationInput.value = `${selectedMapLocation.latitude.toFixed(6)}, ${selectedMapLocation.longitude.toFixed(6)}`;
            locationStatus.textContent = 'Map location saved.';
            mapPicker.classList.remove('is-open');
            mapButton.textContent = 'Choose on map';
        });
    }
</script>
</body>
</html>
