<footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        // Feather icons
        if (window.feather) feather.replace();

        // Navigation logic for Home, Apps, Profile
        function showPage(page) {
            document.querySelectorAll('.main-content [id^="page-"]').forEach(el => el.style.display = 'none');
            var pageEl = document.getElementById('page-' + page);
            if (pageEl) pageEl.style.display = '';
            document.querySelectorAll('.sidebar .nav-link, .bottom-bar .nav-link').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('#nav-' + page + ', #bottom-' + page).forEach(el => el.classList.add('active'));
        }
        document.getElementById('nav-home').onclick = document.getElementById('bottom-home').onclick = function() { showPage('home'); };
        document.getElementById('nav-apps').onclick = document.getElementById('bottom-apps').onclick = function() { showPage('apps'); };
        document.getElementById('nav-profile').onclick = document.getElementById('bottom-profile').onclick = function() { showPage('profile'); };

        // Sidebar dark/light mode logic (single source of truth)
        function setDarkModeState(isDark) {
            document.body.classList.toggle('dark-mode', isDark);
            var sidebarLabel = document.getElementById('darkModeSwitchLabelDesktop');
            if (sidebarLabel) sidebarLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
        }
        function syncSidebarSwitch() {
            var isDark = document.body.classList.contains('dark-mode');
            var sidebarSwitch = document.getElementById('darkModeSwitchDesktop');
            if (sidebarSwitch) sidebarSwitch.checked = isDark;
        }
        document.addEventListener('DOMContentLoaded', function() {
            // Set Home as default landing page
            showPage('home');
            var sidebarSwitch = document.getElementById('darkModeSwitchDesktop');
            setDarkModeState(localStorage.getItem('darkMode') === 'true');
            syncSidebarSwitch();
            if (sidebarSwitch) {
                sidebarSwitch.addEventListener('change', function() {
                    localStorage.setItem('darkMode', sidebarSwitch.checked);
                    setDarkModeState(sidebarSwitch.checked);
                    // Sync with profile toggle if present
                    var profileSwitch = document.getElementById('profileDarkModeSwitch');
                    if (profileSwitch) profileSwitch.checked = sidebarSwitch.checked;
                });
            }
            window.addEventListener('storage', function() {
                setDarkModeState(localStorage.getItem('darkMode') === 'true');
                syncSidebarSwitch();
            });

            // Logout modal logic (global)
            var logoutBtn = document.getElementById('logoutConfirmBtn');
            var logoutForm = document.getElementById('sidebarLogoutForm');
            if (logoutBtn && logoutForm) {
                logoutBtn.addEventListener('click', function() {
                    logoutForm.submit();
                });
            }
        });

        // Inject dark mode toggle into profile page top-right only on mobile
        function handleProfileToggle() {
            function isMobile() {
                return window.matchMedia('(max-width: 767.98px)').matches;
            }
            var profileHeader = document.querySelector('#page-profile > .d-flex.align-items-center.mb-3');
            var existingToggle = document.getElementById('profileDarkModeSwitch');
            if (profileHeader) {
                // Remove toggle if present and not on mobile
                if (existingToggle && !isMobile()) {
                    var toggleDiv = existingToggle.closest('.form-check.form-switch');
                    if (toggleDiv) toggleDiv.remove();
                }
                // Add toggle if on mobile and not present
                if (!existingToggle && isMobile()) {
                    var toggleDiv = document.createElement('div');
                    toggleDiv.className = 'form-check form-switch d-flex align-items-center ms-auto';
                    toggleDiv.style.gap = '0.5rem';
                    toggleDiv.innerHTML = `
                        <input class="form-check-input" type="checkbox" id="profileDarkModeSwitch" style="cursor:pointer;">
                        <label class="form-check-label" for="profileDarkModeSwitch" id="profileDarkModeLabel">Dark Mode</label>
                    `;
                    profileHeader.appendChild(toggleDiv);
                    // Sync logic for profile toggle
                    var profileSwitch = document.getElementById('profileDarkModeSwitch');
                    var profileLabel = document.getElementById('profileDarkModeLabel');
                    function setProfileSwitchState() {
                        var isDark = document.body.classList.contains('dark-mode');
                        profileSwitch.checked = isDark;
                        profileLabel.textContent = isDark ? 'Light Mode' : 'Dark Mode';
                    }
                    setProfileSwitchState();
                    profileSwitch.addEventListener('change', function() {
                        localStorage.setItem('darkMode', profileSwitch.checked);
                        setDarkModeState(profileSwitch.checked);
                        // Sync with sidebar toggle if present
                        var sidebarSwitch = document.getElementById('darkModeSwitchDesktop');
                        if (sidebarSwitch) sidebarSwitch.checked = profileSwitch.checked;
                    });
                    window.addEventListener('storage', setProfileSwitchState);
                }
            }
        }
        document.addEventListener('DOMContentLoaded', handleProfileToggle);
        window.addEventListener('resize', handleProfileToggle);

        // Profile photo preview and modal logic
        document.addEventListener('DOMContentLoaded', function() {
            var photoInput = document.getElementById('profilePhotoInput');
            var photoPreview = document.getElementById('profilePhotoPreview');
            if (photoInput && photoPreview) {
                photoInput.addEventListener('change', function() {
                    if (photoInput.files && photoInput.files[0]) {
                        var reader = new FileReader();
                        reader.onload = function(e) {
                            photoPreview.src = e.target.result;
                        };
                        reader.readAsDataURL(photoInput.files[0]);
                    }
                });
            }
            var confirmPhotoBtn = document.getElementById('confirmPhotoBtn');
            var modalPhotoSaveBtn = document.getElementById('modalPhotoSaveBtn');
            var confirmPhotoModalEl = document.getElementById('confirmPhotoModal');
            if (confirmPhotoBtn && modalPhotoSaveBtn && confirmPhotoModalEl) {
                var confirmPhotoModal = new bootstrap.Modal(confirmPhotoModalEl);
                confirmPhotoBtn.addEventListener('click', function() {
                    confirmPhotoModal.show();
                });
                modalPhotoSaveBtn.addEventListener('click', function() {
                    // Simulate form submit
                    confirmPhotoModal.hide();
                });
            }
        });
        // Profile info save modal logic
        document.addEventListener('DOMContentLoaded', function() {
            var confirmBtn = document.getElementById('confirmSaveBtn');
            var modalSaveBtn = document.getElementById('modalSaveBtn');
            var confirmModalEl = document.getElementById('confirmSaveModal');
            if (confirmBtn && modalSaveBtn && confirmModalEl) {
                var confirmModal = new bootstrap.Modal(confirmModalEl);
                confirmBtn.addEventListener('click', function() {
                    confirmModal.show();
                });
                modalSaveBtn.addEventListener('click', function() {
                    // Simulate form submit
                    confirmModal.hide();
                });
            }
        });
    </script>
</footer>