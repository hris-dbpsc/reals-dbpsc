<script>
    (function() {
        const form = document.getElementById('clockin-form');
        if (!form) return;

        const submitButton = form.querySelector('button[type="submit"]');
        const latEl = document.getElementById('clockin-latitude');
        const lngEl = document.getElementById('clockin-longitude');
        const accEl = document.getElementById('clockin-accuracy');
        const locEl = document.getElementById('clockin-location');

        // Helper to show a temporary message to user
        function notify(msg) {
            try {
                // simple alert for now; can be replaced with toast
                alert(msg);
            } catch (e) {
                console.warn(msg);
            }
        }

        // Check permission status on load; if denied, notify user to enable location services
        if (navigator.permissions && navigator.permissions.query) {
            navigator.permissions.query({
                name: 'geolocation'
            }).then(function(permissionStatus) {
                if (permissionStatus.state === 'denied') {
                    // Let the user know location is blocked
                    console.warn('Geolocation permission is denied');
                    // Do not spam alerts, only log. UI can show a hint if needed.
                }
                // listen for changes (optional)
                permissionStatus.onchange = function() {
                    console.log('Geolocation permission changed to', this.state);
                };
            }).catch(function() {
                // ignore permission API errors
            });
        }

        async function ipFallback() {
            try {
                const res = await fetch('https://ipapi.co/json/');
                if (!res.ok) throw new Error('ipapi failed');
                const data = await res.json();
                if (data && data.latitude && data.longitude) {
                    latEl.value = data.latitude;
                    lngEl.value = data.longitude;
                    accEl.value = '';
                    locEl.value = (data.city || '') + (data.region ? ', ' + data.region : '') + (data.country ? ', ' + data.country : '');
                    return true;
                }
            } catch (err) {
                console.warn('IP fallback failed', err);
            }
            return false;
        }

        // Explicit click handler that uses device location services
        async function handleClockInClick(e) {
            // If lat/long already filled, allow default submit to go through
            if (latEl.value && lngEl.value) return;

            e.preventDefault();

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.classList.add('opacity-75');
            }

            const GEO_TIMEOUT = 15000; // longer timeout for mobile devices
            let didSubmit = false;

            function submitNow() {
                if (didSubmit) return;
                didSubmit = true;
                form.submit();
            }

            function fillAndSubmitFromPosition(position) {
                try {
                    latEl.value = position.coords.latitude;
                    lngEl.value = position.coords.longitude;
                    accEl.value = position.coords.accuracy || '';
                    locEl.value = position.coords.latitude + ',' + position.coords.longitude;
                } catch (err) {
                    console.warn(err);
                }
                submitNow();
            }

            async function handleGeoError(err) {
                console.warn('Geolocation error', err);
                // If permission denied explicitly, notify user to enable device location services
                if (err && err.code === 1) { // PERMISSION_DENIED
                    notify('Location permission denied. Please enable location services for this site and try again.');
                }
                // Try IP fallback then submit
                const ok = await ipFallback();
                if (ok) {
                    return submitNow();
                }
                submitNow();
            }

            if (navigator.geolocation) {
                const timer = setTimeout(function() {
                    console.warn('Geolocation timeout, using fallback');
                    handleGeoError(new Error('timeout'));
                }, GEO_TIMEOUT);

                // Request high-accuracy position from device location services
                navigator.geolocation.getCurrentPosition(function(position) {
                    clearTimeout(timer);
                    fillAndSubmitFromPosition(position);
                }, function(err) {
                    clearTimeout(timer);
                    handleGeoError(err);
                }, {
                    enableHighAccuracy: true,
                    maximumAge: 0,
                    timeout: GEO_TIMEOUT
                });
            } else {
                // Not supported; fallback to IP
                const ok = await ipFallback();
                if (ok) return submitNow();
                submitNow();
            }
        }

        // Attach click handler to the submit button to ensure action originates from user gesture
        if (submitButton) {
            submitButton.addEventListener('click', handleClockInClick);
        } else {
            // Fallback to form submit handler if button not found
            form.addEventListener('submit', function(e) {
                handleClockInClick(e);
            });
        }
    })();
</script>