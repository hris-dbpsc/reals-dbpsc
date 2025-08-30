<!DOCTYPE html>
<html lang="en">
@include('superadmin.header')

<body class="nav-fixed">
    @include('superadmin.topnav')
    <div id="layoutSidenav">
        @include('superadmin.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-2">
                    <div class="container-fluid px-4">
                        <div class="page-header-content">
                            <div class="row align-items-center justify-content-between pt-3">
                                <div class="col-auto mb-3">
                                    <h1 class="page-header-title text-body d-flex align-items-center">
                                        <a href="{{ route('superadmin_clients') }}" class="btn rounded-circle shadow-sm d-flex align-items-center justify-content-center me-2" style="width:50px; height:50px; padding:0;">
                                            <i data-feather="arrow-left-circle" class="text-primary" style="width:40px; height:40px;"></i>
                                        </a>
                                        <div class="page-header-icon"><i data-feather="users" style="width:25px; height:25px;"></i></div>
                                        Edit Client
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <!-- Main page content-->
                <div class="container-fluid px-4 mt-2">
                    <div class="row">
                        <div class="col-xl-4">
                            <!-- Client profile picture card-->
                            <div class="card mb-2 mb-xl-0">
                                <div class="card-header text-body">Client Profile Picture</div>
                                <div class="card-body text-center">
                                    <form action="{{ route('superadmin_editclient_uploadprofilepicture', $client->id) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <!-- Profile picture image-->
                                        <img id="profilePhotoPreview" class="img-account-profile mb-2" src="{{ $client->clientphoto ? asset('assets/clients/' . $client->clientphoto) : asset('assets/assets/img/demo/user-placeholder.svg') }}" alt="Client Profile Photo" />
                                        <!-- Profile picture help block-->
                                        <div class="small font-italic text-muted mb-4">JPG or PNG no larger than 5 MB</div>
                                        <!-- Profile picture upload button-->
                                        <div class="d-flex flex-row align-items-center justify-content-center">
                                            <label class="btn btn-outline-success me-2">
                                                <input type="file" name="photo" accept="image/png, image/jpeg" style="display:none;" onchange="previewPhoto(this)">
                                                <i data-feather="upload" class="me-1"></i>
                                                Upload
                                            </label>
                                            <button type="button" class="btn btn-outline-primary" id="confirmPhotoBtn">
                                                <i data-feather="bookmark" class="me-1"></i>
                                                Save
                                            </button>
                                            <!-- Confirmation Modal for Profile Picture -->
                                            <div class="modal fade" id="confirmPhotoModal" tabindex="-1" aria-labelledby="confirmPhotoModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="confirmPhotoModalLabel">Confirm Profile Picture</h5>
                                                            <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to save this profile picture?
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                                <i data-feather="x" class="me-1"></i>
                                                                Cancel
                                                            </button>
                                                            <button type="button" class="btn btn-outline-primary" id="modalPhotoSaveBtn">
                                                                <i data-feather="bookmark" class="me-1"></i>
                                                                Save
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    var confirmPhotoBtn = document.getElementById('confirmPhotoBtn');
                                                    var modalPhotoSaveBtn = document.getElementById('modalPhotoSaveBtn');
                                                    var confirmPhotoModal = new bootstrap.Modal(document.getElementById('confirmPhotoModal'));
                                                    var photoForm = confirmPhotoBtn.closest('form');

                                                    confirmPhotoBtn.addEventListener('click', function(e) {
                                                        confirmPhotoModal.show();
                                                    });

                                                    modalPhotoSaveBtn.addEventListener('click', function() {
                                                        photoForm.submit();
                                                    });
                                                });
                                            </script>
                                        </div>
                                    </form>
                                    <script>
                                        function previewPhoto(input) {
                                            if (input.files && input.files[0]) {
                                                var reader = new FileReader();
                                                reader.onload = function(e) {
                                                    document.getElementById('profilePhotoPreview').src = e.target.result;
                                                }
                                                reader.readAsDataURL(input.files[0]);
                                            }
                                        }
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-8">
                            <!-- Account details card-->
                            <div class="card mb-4">
                                <div class="card-header text-body">Client Information</div>
                                <div class="card-body">
                                    <form action="{{ route('superadmin_editclient_submit', $client->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <!-- Form Row-->
                                        <div class="row gx-3 mb-2">
                                            <div class="col-md-12">
                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientname" name="clientname" type="text" value="{{ $client->clientname }}" placeholder="Client Name" />
                                                    <label for="clientname">Client Name</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientshortname" name="clientshortname" type="text" value="{{ $client->clientshortname }}" placeholder="Short Name" />
                                                    <label for="clientshortname">Short Name</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <select class="form-control" id="clienttype" name="clienttype" aria-label="Client Type">
                                                        <option value="Government" {{ $client->clienttype == 'Government' ? 'selected' : '' }}>Government</option>
                                                        <option value="Private" {{ $client->clienttype == 'Private' ? 'selected' : '' }}>Private</option>
                                                    </select>
                                                    <label for="clienttype">Client Type</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientcontact" name="clientcontact" type="text" value="{{ $client->clientcontact }}" placeholder="Contact" />
                                                    <label for="clientcontact">Contact</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientcontactperson" name="clientcontactperson" type="text" value="{{ $client->clientcontactperson }}" placeholder="Contact Person" />
                                                    <label for="clientcontactperson">Contact Person</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientemail" name="clientemail" type="email" value="{{ $client->clientemail }}" placeholder="Email" />
                                                    <label for="clientemail">Email</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientaddress" name="clientaddress" type="text" value="{{ $client->clientaddress }}" placeholder="Address" />
                                                    <label for="clientaddress">Address</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="clientregion" id="clientregion" required>
                                                        @php
                                                        $regions = [
                                                        'I',
                                                        'II',
                                                        'III',
                                                        'IV-A',
                                                        'IV-B',
                                                        'V',
                                                        'VI',
                                                        'VII',
                                                        'VIII',
                                                        'IX',
                                                        'X',
                                                        'XI',
                                                        'XII',
                                                        'XIII',
                                                        'NCR',
                                                        'CAR',
                                                        'BARMM'
                                                        ];
                                                        @endphp
                                                        <option value="" disabled {{ !$client->clientregion ? 'selected' : '' }}>Select Region</option>
                                                        @foreach($regions as $region)
                                                        <option value="{{ $region }}" {{ $client->clientregion == $region ? 'selected' : '' }}>{{ $region }}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="clientregion">Region</label>
                                                </div>
                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="clientprovince" id="clientprovince" required>
                                                        <option value="" disabled {{ !$client->clientprovince ? 'selected' : '' }}>Select Province</option>
                                                    </select>
                                                    <label for="clientprovince">Province</label>
                                                </div>
                                                <script>
                                                    // Provinces by region
                                                    const provincesByRegion = {
                                                        'I': ['Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan'],
                                                        'II': ['Batanes', 'Cagayan', 'Isabela', 'Nueva Vizcaya', 'Quirino'],
                                                        'III': ['Aurora', 'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales'],
                                                        'IV-A': ['Batangas', 'Cavite', 'Laguna', 'Quezon', 'Rizal'],
                                                        'IV-B': ['Marinduque', 'Occidental Mindoro', 'Oriental Mindoro', 'Palawan', 'Romblon'],
                                                        'V': ['Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon'],
                                                        'VI': ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental'],
                                                        'VII': ['Bohol', 'Cebu', 'Negros Oriental', 'Siquijor'],
                                                        'VIII': ['Biliran', 'Eastern Samar', 'Leyte', 'Northern Samar', 'Samar', 'Southern Leyte'],
                                                        'IX': ['Zamboanga del Norte', 'Zamboanga del Sur', 'Zamboanga Sibugay'],
                                                        'X': ['Bukidnon', 'Camiguin', 'Lanao del Norte', 'Misamis Occidental', 'Misamis Oriental'],
                                                        'XI': ['Davao de Oro', 'Davao del Norte', 'Davao del Sur', 'Davao Occidental', 'Davao Oriental'],
                                                        'XII': ['Cotabato', 'Sarangani', 'South Cotabato', 'Sultan Kudarat'],
                                                        'XIII': ['Agusan del Norte', 'Agusan del Sur', 'Dinagat Islands', 'Surigao del Norte', 'Surigao del Sur'],
                                                        'NCR': ['Metro Manila'],
                                                        'CAR': ['Abra', 'Apayao', 'Benguet', 'Ifugao', 'Kalinga', 'Mountain Province'],
                                                        'BARMM': ['Basilan', 'Lanao del Sur', 'Maguindanao del Norte', 'Maguindanao del Sur', 'Sulu', 'Tawi-Tawi']
                                                    };

                                                    function populateProvinces(region, selectedProvince = '') {
                                                        const provinceSelect = document.getElementById('clientprovince');
                                                        provinceSelect.innerHTML = '<option value="" disabled>Select Province</option>';
                                                        if (provincesByRegion[region]) {
                                                            provincesByRegion[region].forEach(function(province) {
                                                                const selected = province === selectedProvince ? 'selected' : '';
                                                                provinceSelect.innerHTML += `<option value="${province}" ${selected}>${province}</option>`;
                                                            });
                                                        }
                                                    }

                                                    // On page load, populate provinces if region is selected
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const regionSelect = document.getElementById('clientregion');
                                                        const selectedRegion = regionSelect.value;
                                                        const selectedProvince = "{{ $client->clientprovince }}";
                                                        if (selectedRegion) {
                                                            populateProvinces(selectedRegion, selectedProvince);
                                                        }
                                                        regionSelect.addEventListener('change', function() {
                                                            populateProvinces(this.value);
                                                        });
                                                    });
                                                </script>
                                                <script>
                                                    // Handle NCR region to auto-select Metro Manila province
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const regionSelect = document.getElementById('clientregion');
                                                        const provinceSelect = document.getElementById('clientprovince');
                                                        regionSelect.addEventListener('change', function() {
                                                            if (this.value === 'NCR') {
                                                                // Set province to Metro Manila and trigger change
                                                                populateProvinces('NCR', 'Metro Manila');
                                                                provinceSelect.value = 'Metro Manila';
                                                                provinceSelect.dispatchEvent(new Event('change'));
                                                            }
                                                        });
                                                        // On page load, if NCR is selected, set Metro Manila
                                                        if (regionSelect.value === 'NCR') {
                                                            populateProvinces('NCR', 'Metro Manila');
                                                            provinceSelect.value = 'Metro Manila';
                                                            provinceSelect.dispatchEvent(new Event('change'));
                                                        }
                                                    });
                                                </script>
                                                <div class="form-floating mb-1">
                                                    <select class="form-control" name="clientcity" id="clientcity" required>
                                                    </select>
                                                    <label for="clientcity">City</label>
                                                </div>
                                                </select>
                                                <script>
                                                    // Cities by province (expand as needed)
                                                    const citiesByProvince = {
                                                        'Ilocos Norte': ['Laoag City', 'Batac City', 'Dingras', 'Pasuquin', 'Piddig', 'Solsona'],
                                                        'Ilocos Sur': ['Vigan City', 'Candon City', 'Tagudin', 'Narvacan', 'Santa Cruz', 'Sinait'],
                                                        'La Union': ['San Fernando City', 'Agoo', 'Bauang', 'Rosario', 'Naguilian'],
                                                        'Pangasinan': ['Dagupan City', 'San Carlos City', 'Urdaneta City', 'Alaminos City', 'Lingayen', 'Binmaley', 'Mangaldan', 'Bayambang'],
                                                        'Batanes': ['Basco', 'Itbayat', 'Ivana', 'Mahatao', 'Sabtang', 'Uyugan'],
                                                        'Cagayan': ['Tuguegarao City', 'Aparri', 'Gattaran', 'Solana', 'Peñablanca', 'Sanchez-Mira'],
                                                        'Isabela': ['Ilagan City', 'Cauayan City', 'Santiago City', 'Roxas', 'Echague', 'Cabagan', 'Tumauini'],
                                                        'Nueva Vizcaya': ['Bayombong', 'Solano', 'Kasibu', 'Dupax del Norte', 'Aritao'],
                                                        'Quirino': ['Cabarroguis', 'Diffun', 'Saguday', 'Maddela'],
                                                        'Aurora': ['Baler', 'Casiguran', 'Dingalan', 'Maria Aurora'],
                                                        'Bataan': ['Balanga City', 'Orion', 'Dinalupihan', 'Mariveles', 'Orani', 'Abucay'],
                                                        'Bulacan': ['Malolos City', 'Meycauayan City', 'San Jose del Monte City', 'Angat', 'Balagtas', 'Baliuag', 'Bocaue', 'Bulakan', 'Calumpit', 'Doña Remedios Trinidad', 'Guiguinto', 'Hagonoy', 'Marilao', 'Norzagaray', 'Obando', 'Pandi', 'Paombong', 'Plaridel', 'Pulilan', 'San Ildefonso', 'San Miguel', 'San Rafael', 'Santa Maria'],
                                                        'Nueva Ecija': ['Cabanatuan City', 'San Jose City', 'Gapan City', 'Palayan City', 'Aliaga', 'Guimba', 'San Antonio', 'San Isidro'],
                                                        'Pampanga': ['San Fernando City', 'Angeles City', 'Mabalacat City', 'Apalit', 'Arayat', 'Bacolor', 'Candaba', 'Floridablanca', 'Guagua', 'Lubao', 'Macabebe', 'Magalang', 'Masantol', 'Mexico', 'Minalin', 'Porac', 'San Luis', 'San Simon', 'Santa Ana', 'Santa Rita', 'Santo Tomas', 'Sasmuan'],
                                                        'Tarlac': ['Tarlac City', 'Capas', 'Concepcion', 'Gerona', 'Paniqui', 'Ramos', 'San Manuel', 'Victoria'],
                                                        'Zambales': ['Olongapo City', 'Iba', 'Subic', 'Castillejos', 'San Antonio', 'San Felipe', 'San Marcelino'],
                                                        'Batangas': ['Batangas City', 'Lipa City', 'Tanauan City', 'Balayan', 'Bauan', 'Calaca', 'Calatagan', 'Lemery', 'Nasugbu', 'San Juan', 'San Jose', 'Taal'],
                                                        'Cavite': ['Tagaytay City', 'Dasmariñas City', 'Imus City', 'Bacoor City', 'Cavite City', 'General Trias City', 'Trece Martires City', 'Alfonso', 'Amadeo', 'Carmona', 'General Emilio Aguinaldo', 'GMA', 'Indang', 'Kawit', 'Magallanes', 'Maragondon', 'Mendez', 'Naic', 'Noveleta', 'Rosario', 'Silang', 'Tanza', 'Ternate'],
                                                        'Laguna': ['San Pablo City', 'Santa Rosa City', 'Calamba City', 'Biñan City', 'Cabuyao City', 'Alaminos', 'Bay', 'Calauan', 'Famy', 'Kalayaan', 'Liliw', 'Los Baños', 'Luisiana', 'Mabitac', 'Magdalena', 'Majayjay', 'Nagcarlan', 'Paete', 'Pagsanjan', 'Pakil', 'Pangil', 'Pila', 'Rizal', 'San Pedro City', 'Siniloan', 'Sta. Cruz', 'Victoria'],
                                                        'Quezon': ['Lucena City', 'Tayabas City', 'Candelaria', 'Dolores', 'Infanta', 'Mauban', 'Polillo', 'Sariaya', 'Tiaong', 'Unisan'],
                                                        'Rizal': ['Antipolo City', 'Angono', 'Binangonan', 'Cainta', 'Cardona', 'Jalajala', 'Morong', 'Pililla', 'Rodriguez', 'San Mateo', 'Tanay', 'Taytay', 'Teresa'],
                                                        'Marinduque': ['Boac', 'Gasan', 'Mogpog', 'Santa Cruz', 'Torrijos', 'Buenavista'],
                                                        'Occidental Mindoro': ['Mamburao', 'Abra de Ilog', 'Calintaan', 'Looc', 'Lubang', 'Paluan', 'Rizal', 'Sablayan', 'San Jose', 'Santa Cruz'],
                                                        'Oriental Mindoro': ['Calapan City', 'Baco', 'Bansud', 'Bongabong', 'Bulalacao', 'Gloria', 'Mansalay', 'Naujan', 'Pinamalayan', 'Pola', 'Puerto Galera', 'Roxas', 'San Teodoro', 'Socorro', 'Victoria'],
                                                        'Palawan': ['Puerto Princesa City', 'Aborlan', 'Agutaya', 'Araceli', 'Balabac', 'Bataraza', 'Brooke\'s Point', 'Busuanga', 'Cagayancillo', 'Coron', 'Culion', 'Cuyo', 'Dumaran', 'El Nido', 'Kalayaan', 'Linapacan', 'Magsaysay', 'Narra', 'Quezon', 'Rizal', 'Roxas', 'San Vicente', 'Sofronio Española', 'Taytay'],
                                                        'Romblon': ['Romblon', 'Alcantara', 'Banton', 'Cajidiocan', 'Calatrava', 'Concepcion', 'Corcuera', 'Ferrol', 'Looc', 'Magdiwang', 'Odiongan', 'San Agustin', 'San Andres', 'San Fernando', 'Santa Fe', 'Santa Maria'],
                                                        'Albay': ['Legazpi City', 'Tabaco City', 'Ligao City', 'Bacacay', 'Camalig', 'Daraga', 'Guinobatan', 'Jovellar', 'Libon', 'Malilipot', 'Malinao', 'Manito', 'Oas', 'Pio Duran', 'Polangui', 'Rapu-Rapu', 'Santo Domingo', 'Tiwi'],
                                                        'Camarines Norte': ['Daet', 'Basud', 'Capalonga', 'Jose Panganiban', 'Labo', 'Mercedes', 'Paracale', 'San Lorenzo Ruiz', 'San Vicente', 'Santa Elena', 'Talisay', 'Vinzons'],
                                                        'Camarines Sur': ['Naga City', 'Iriga City', 'Baao', 'Balatan', 'Bato', 'Bombon', 'Buhi', 'Bula', 'Cabusao', 'Calabanga', 'Camaligan', 'Canaman', 'Caramoan', 'Del Gallego', 'Gainza', 'Garchitorena', 'Goa', 'Lagonoy', 'Libmanan', 'Lupi', 'Magarao', 'Milaor', 'Minalabac', 'Nabua', 'Ocampo', 'Pamplona', 'Pasacao', 'Pili', 'Presentacion', 'Ragay', 'Sagñay', 'San Fernando', 'San Jose', 'Sipocot', 'Siruma', 'Tigaon', 'Tinambac'],
                                                        'Catanduanes': ['Virac', 'Bagamanoc', 'Baras', 'Bato', 'Caramoran', 'Gigmoto', 'Pandan', 'Panganiban', 'San Andres', 'San Miguel', 'Viga'],
                                                        'Masbate': ['Masbate City', 'Aroroy', 'Baleno', 'Balud', 'Batuan', 'Cataingan', 'Cawayan', 'Claveria', 'Dimasalang', 'Esperanza', 'Mandaon', 'Milagros', 'Monreal', 'Palanas', 'Pio V. Corpuz', 'Placer', 'San Fernando', 'San Jacinto', 'San Pascual', 'Uson'],
                                                        'Sorsogon': ['Sorsogon City', 'Barcelona', 'Bulan', 'Bulusan', 'Casiguran', 'Castilla', 'Donsol', 'Gubat', 'Irosin', 'Juban', 'Magallanes', 'Matnog', 'Pilar', 'Prieto Diaz', 'Santa Magdalena'],
                                                        'Aklan': ['Kalibo', 'Altavas', 'Balete', 'Banga', 'Batan', 'Buruanga', 'Ibajay', 'Lezo', 'Libacao', 'Madalag', 'Makato', 'Malay', 'Malinao', 'Nabas', 'New Washington', 'Numancia', 'Tangalan'],
                                                        'Antique': ['San Jose', 'Anini-y', 'Barbaza', 'Belison', 'Bugasong', 'Caluya', 'Culasi', 'Hamtic', 'Laua-an', 'Libertad', 'Pandan', 'Patnongon', 'San Remigio', 'Sebaste', 'Sibalom', 'Tibiao', 'Tobias Fornier', 'Valderrama'],
                                                        'Capiz': ['Roxas City', 'Cuartero', 'Dao', 'Dumalag', 'Dumarao', 'Ivisan', 'Jamindan', 'Ma-ayon', 'Mambusao', 'Panay', 'Panitan', 'Pilar', 'Pontevedra', 'President Roxas', 'Sapian', 'Sigma', 'Tapaz'],
                                                        'Guimaras': ['Jordan', 'Buenavista', 'Nueva Valencia', 'San Lorenzo', 'Sibunag'],
                                                        'Iloilo': ['Iloilo City', 'Passi City', 'Ajuy', 'Alimodian', 'Anilao', 'Badiangan', 'Balasan', 'Banate', 'Barotac Nuevo', 'Barotac Viejo', 'Batad', 'Bingawan', 'Cabatuan', 'Calinog', 'Carles', 'Concepcion', 'Dingle', 'Dueñas', 'Dumangas', 'Estancia', 'Guimbal', 'Igbaras', 'Janiuay', 'Lambunao', 'Leganes', 'Lemery', 'Leon', 'Maasin', 'Miagao', 'Mina', 'New Lucena', 'Oton', 'Pavia', 'Pototan', 'San Dionisio', 'San Enrique', 'San Joaquin', 'San Miguel', 'San Rafael', 'Santa Barbara', 'Sara', 'Tigbauan', 'Tubungan', 'Zarraga'],
                                                        'Negros Occidental': ['Bacolod City', 'Sipalay City', 'Bago City', 'Cadiz City', 'Escalante City', 'Himamaylan City', 'Kabankalan City', 'La Carlota City', 'Sagay City', 'San Carlos City', 'Silay City', 'Talisay City', 'Victorias City', 'Binalbagan', 'Calatrava', 'Cauayan', 'Enrique B. Magalona', 'Hinigaran', 'Hinoba-an', 'Ilog', 'Isabela', 'La Castellana', 'Manapla', 'Moises Padilla', 'Murcia', 'Pontevedra', 'Pulupandan', 'Salvador Benedicto', 'San Enrique', 'Toboso', 'Valladolid'],
                                                        'Bohol': ['Tagbilaran City', 'Alburquerque', 'Alicia', 'Anda', 'Antequera', 'Baclayon', 'Balilihan', 'Batuan', 'Bien Unido', 'Bilar', 'Buenavista', 'Calape', 'Candijay', 'Carmen', 'Catigbian', 'Clarin', 'Corella', 'Cortes', 'Dagohoy', 'Danao', 'Dauis', 'Dimiao', 'Duero', 'Garcia Hernandez', 'Guindulman', 'Inabanga', 'Jagna', 'Lila', 'Loay', 'Loboc', 'Loon', 'Mabini', 'Maribojoc', 'Panglao', 'Pilar', 'President Carlos P. Garcia', 'Sagbayan', 'San Isidro', 'San Miguel', 'Sevilla', 'Sierra Bullones', 'Sikatuna', 'Talibon', 'Trinidad', 'Tubigon', 'Ubay', 'Valencia'],
                                                        'Cebu': ['Cebu City', 'Mandaue City', 'Lapu-Lapu City', 'Toledo City', 'Talisay City', 'Naga City', 'Carcar City', 'Danao City', 'Bogo City', 'Alcantara', 'Alcoy', 'Alegria', 'Aloguinsan', 'Argao', 'Asturias', 'Badian', 'Balamban', 'Bantayan', 'Barili', 'Boljoon', 'Borbon', 'Carmen', 'Catmon', 'Compostela', 'Consolacion', 'Cordova', 'Daanbantayan', 'Dalaguete', 'Dumanjug', 'Ginatilan', 'Liloan', 'Madridejos', 'Malabuyoc', 'Medellin', 'Minglanilla', 'Moalboal', 'Oslob', 'Pilar', 'Pinamungajan', 'Poro', 'Ronda', 'Samboan', 'San Fernando', 'San Francisco', 'San Remigio', 'Santa Fe', 'Santander', 'Sibonga', 'Sogod', 'Tabogon', 'Tabuelan', 'Tuburan', 'Tudela'],
                                                        'Negros Oriental': ['Dumaguete City', 'Bais City', 'Bayawan City', 'Canlaon City', 'Guihulngan City', 'Tanjay City', 'Amlan', 'Ayungon', 'Bacong', 'Basay', 'Bindoy', 'Dauin', 'Jimalalud', 'La Libertad', 'Mabinay', 'Manjuyod', 'Pamplona', 'San Jose', 'Santa Catalina', 'Siaton', 'Sibulan', 'Tayasan', 'Valencia', 'Vallehermoso', 'Zamboanguita'],
                                                        'Siquijor': ['Siquijor', 'Enrique Villanueva', 'Larena', 'Lazi', 'Maria', 'San Juan'],
                                                        'Biliran': ['Naval', 'Almeria', 'Biliran', 'Cabucgayan', 'Caibiran', 'Culaba', 'Kawayan', 'Maripipi'],
                                                        'Eastern Samar': ['Borongan City', 'Arteche', 'Balangiga', 'Balangkayan', 'Can-avid', 'Dolores', 'General MacArthur', 'Giporlos', 'Guiuan', 'Hernani', 'Jipapad', 'Lawaan', 'Llorente', 'Maslog', 'Maydolong', 'Mercedes', 'Oras', 'Quinapondan', 'Salcedo', 'San Julian', 'San Policarpo', 'Sulat', 'Taft'],
                                                        'Leyte': ['Tacloban City', 'Ormoc City', 'Baybay City', 'Abuyog', 'Alangalang', 'Albuera', 'Babatngon', 'Barugo', 'Bato', 'Burauen', 'Calubian', 'Capoocan', 'Carigara', 'Dagami', 'Dulag', 'Hilongos', 'Hindang', 'Inopacan', 'Isabel', 'Jaro', 'Julita', 'Kananga', 'La Paz', 'Leyte', 'MacArthur', 'Mahaplag', 'Matag-ob', 'Matalom', 'Mayorga', 'Merida', 'Palo', 'Palompon', 'Pastrana', 'San Isidro', 'San Miguel', 'Santa Fe', 'Tabango', 'Tabontabon', 'Tanauan', 'Tolosa', 'Tunga', 'Villaba'],
                                                        'Northern Samar': ['Catarman', 'Allen', 'Biri', 'Bobon', 'Capul', 'Catubig', 'Gamay', 'Laoang', 'Lapinig', 'Las Navas', 'Lavezares', 'Mapanas', 'Mondragon', 'Palapag', 'Pambujan', 'Rosario', 'San Antonio', 'San Isidro', 'San Jose', 'San Roque', 'San Vicente', 'Silvino Lobos', 'Victoria'],
                                                        'Samar': ['Catbalogan City', 'Calbayog City', 'Almagro', 'Basey', 'Calbiga', 'Daram', 'Gandara', 'Hinabangan', 'Jiabong', 'Marabut', 'Matuguinao', 'Motiong', 'Pagsanghan', 'Paranas', 'Pinabacdao', 'San Jorge', 'San Jose de Buan', 'San Sebastian', 'Santa Margarita', 'Santa Rita', 'Santo Niño', 'Tagapul-an', 'Talalora', 'Tarangnan', 'Villareal', 'Zumarraga'],
                                                        'Southern Leyte': ['Maasin City', 'Anahawan', 'Bontoc', 'Hinunangan', 'Hinundayan', 'Libagon', 'Liloan', 'Macrohon', 'Malitbog', 'Padre Burgos', 'Pintuyan', 'Saint Bernard', 'San Francisco', 'San Juan', 'San Ricardo', 'Silago', 'Sogod', 'Tomas Oppus'],
                                                        'Zamboanga del Norte': ['Dipolog City', 'Dapitan City', 'Sindangan', 'Katipunan', 'Manukan', 'Polanco', 'Rizal', 'Siayan', 'Sibutad', 'Labason', 'Liloy', 'Salug', 'Tampilisan'],
                                                        'Zamboanga del Sur': ['Pagadian City', 'Zamboanga City', 'Aurora', 'Bayog', 'Dimataling', 'Dinas', 'Dumalinao', 'Dumingag', 'Guipos', 'Josefina', 'Kumalarang', 'Labangan', 'Lakewood', 'Lapuyan', 'Mahayag', 'Margosatubig', 'Midsalip', 'Molave', 'Pitogo', 'Ramon Magsaysay', 'San Miguel', 'San Pablo', 'Sominot', 'Tabina', 'Tambulig', 'Tigbao', 'Tukuran', 'Vincenzo A. Sagun'],
                                                        'Zamboanga Sibugay': ['Ipil', 'Alicia', 'Buug', 'Diplahan', 'Imelda', 'Kabasalan', 'Mabuhay', 'Malangas', 'Naga', 'Olutanga', 'Payao', 'Roseller Lim', 'Siay', 'Talusan', 'Titay'],
                                                        'Bukidnon': ['Malaybalay City', 'Valencia City', 'Baungon', 'Cabanglasan', 'Damulog', 'Dangcagan', 'Don Carlos', 'Impasugong', 'Kadingilan', 'Kalilangan', 'Kibawe', 'Kitaotao', 'Lantapan', 'Libona', 'Malitbog', 'Manolo Fortich', 'Maramag', 'Pangantucan', 'Quezon', 'San Fernando', 'Sumilao', 'Talakag'],
                                                        'Camiguin': ['Mambajao', 'Catarman', 'Guinsiliban', 'Mahinog', 'Sagay'],
                                                        'Lanao del Norte': ['Iligan City', 'Bacolod', 'Baloi', 'Baroy', 'Kapatagan', 'Kauswagan', 'Kolambugan', 'Lala', 'Linamon', 'Magsaysay', 'Maigo', 'Matungao', 'Munai', 'Nunungan', 'Pantao Ragat', 'Pantar', 'Poona Piagapo', 'Salvador', 'Sapad', 'Sultan Naga Dimaporo', 'Tagoloan', 'Tangcal', 'Tubod'],
                                                        'Misamis Occidental': ['Oroquieta City', 'Ozamiz City', 'Tangub City', 'Aloran', 'Baliangao', 'Bonifacio', 'Calamba', 'Clarin', 'Concepcion', 'Don Victoriano Chiongbian', 'Jimenez', 'Lopez Jaena', 'Panaon', 'Plaridel', 'Sapang Dalaga', 'Sinacaban', 'Tudela'],
                                                        'Misamis Oriental': ['Cagayan de Oro City', 'Gingoog City', 'Alubijid', 'Balingasag', 'Balingoan', 'Binuangan', 'Claveria', 'Gitagum', 'Initao', 'Jasaan', 'Kinoguitan', 'Lagonglong', 'Laguindingan', 'Libertad', 'Lugait', 'Magsaysay', 'Manticao', 'Medina', 'Naawan', 'Opol', 'Salay', 'Sugbongcogon', 'Tagoloan', 'Talisayan', 'Villanueva'],
                                                        'Davao de Oro': ['Nabunturan', 'Compostela', 'Laak', 'Mabini', 'Maco', 'Maragusan', 'Monkayo', 'Montevista', 'New Bataan', 'Pantukan'],
                                                        'Davao del Norte': ['Tagum City', 'Panabo City', 'Samal City', 'Asuncion', 'Braulio E. Dujali', 'Carmen', 'Kapalong', 'New Corella', 'San Isidro', 'Santo Tomas', 'Talaingod'],
                                                        'Davao del Sur': ['Digos City', 'Bansalan', 'Davao City', 'Hagonoy', 'Kiblawan', 'Magsaysay', 'Malalag', 'Matanao', 'Padada', 'Santa Cruz', 'Sulop'],
                                                        'Davao Occidental': ['Malita', 'Don Marcelino', 'Jose Abad Santos', 'Santa Maria', 'Sarangani'],
                                                        'Davao Oriental': ['Mati City', 'Baganga', 'Banaybanay', 'Boston', 'Caraga', 'Cateel', 'Governor Generoso', 'Lupon', 'Manay', 'San Isidro', 'Tarragona'],
                                                        'Cotabato': ['Kidapawan City', 'Alamada', 'Aleosan', 'Antipas', 'Arakan', 'Banisilan', 'Carmen', 'Kabacan', 'Libungan', 'M\'lang', 'Magpet', 'Makilala', 'Matalam', 'Pigcawayan', 'Pikit', 'President Roxas', 'Tulunan'],
                                                        'Sarangani': ['Alabel', 'Glan', 'Kiamba', 'Maasim', 'Maitum', 'Malapatan', 'Malungon'],
                                                        'South Cotabato': ['Koronadal City', 'General Santos City', 'Banga', 'Lake Sebu', 'Norala', 'Polomolok', 'Santo Niño', 'Surallah', 'Tampakan', 'Tantangan', 'T\'boli'],
                                                        'Sultan Kudarat': ['Isulan', 'Bagumbayan', 'Columbio', 'Esperanza', 'Kalamansig', 'Lambayong', 'Lebak', 'Lutayan', 'Palimbang', 'President Quirino', 'Sen. Ninoy Aquino', 'Tacurong City'],
                                                        'Agusan del Norte': ['Butuan City', 'Cabadbaran City', 'Buenavista', 'Carmen', 'Jabonga', 'Kitcharao', 'Las Nieves', 'Magallanes', 'Nasipit', 'Remedios T. Romualdez', 'Santiago', 'Tubay'],
                                                        'Agusan del Sur': ['Bayugan City', 'Bunawan', 'Esperanza', 'La Paz', 'Loreto', 'Prosperidad', 'Rosario', 'San Francisco', 'San Luis', 'Santa Josefa', 'Sibagat', 'Talacogon', 'Trento', 'Veruela'],
                                                        'Dinagat Islands': ['San Jose', 'Basilisa', 'Cagdianao', 'Dinagat', 'Libjo', 'Loreto', 'Tubajon'],
                                                        'Surigao del Norte': ['Surigao City', 'Alegria', 'Bacuag', 'Burgos', 'Claver', 'Dapa', 'Del Carmen', 'General Luna', 'Gigaquit', 'Mainit', 'Malimono', 'Pilar', 'Placer', 'San Benito', 'San Isidro', 'Santa Monica', 'Sison', 'Socorro', 'Tagana-an', 'Tubod'],
                                                        'Surigao del Sur': ['Bislig City', 'Tandag City', 'Barobo', 'Bayabas', 'Cagwait', 'Cantilan', 'Carmen', 'Carrascal', 'Cortes', 'Hinatuan', 'Lanuza', 'Lianga', 'Lingig', 'Madrid', 'Marihatag', 'San Agustin', 'San Miguel', 'Tagbina', 'Tago'],
                                                        'Metro Manila': ['Caloocan', 'Las Piñas', 'Makati', 'Malabon', 'Mandaluyong', 'Manila', 'Marikina', 'Muntinlupa', 'Navotas', 'Parañaque', 'Pasay', 'Pasig', 'Pateros', 'Quezon City', 'San Juan', 'Taguig', 'Valenzuela'],
                                                        'Abra': ['Bangued', 'Boliney', 'Bucay', 'Bucloc', 'Daguioman', 'Danglas', 'Dolores', 'La Paz', 'Lacub', 'Lagangilang', 'Lagayan', 'Langiden', 'Licuan-Baay', 'Luba', 'Malibcong', 'Manabo', 'Peñarrubia', 'Pidigan', 'Pilar', 'Sallapadan', 'San Isidro', 'San Juan', 'San Quintin', 'Tayum', 'Tineg', 'Tubo', 'Villaviciosa'],
                                                        'Apayao': ['Conner', 'Calanasan', 'Flora', 'Kabugao', 'Luna', 'Pudtol', 'Santa Marcela'],
                                                        'Benguet': ['La Trinidad', 'Baguio City', 'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 'Kabayan', 'Kapangan', 'Kibungan', 'Mankayan', 'Sablan', 'Tuba', 'Tublay'],
                                                        'Ifugao': ['Lagawe', 'Aguinaldo', 'Asipulo', 'Banaue', 'Hingyon', 'Hungduan', 'Kiangan', 'Mayoyao', 'Tinoc'],
                                                        'Kalinga': ['Tabuk City', 'Balbalan', 'Lubuagan', 'Pasil', 'Pinukpuk', 'Rizal', 'Tanudan', 'Tinglayan'],
                                                        'Mountain Province': ['Bontoc', 'Barlig', 'Besao', 'Natonin', 'Paracelis', 'Sabangan', 'Sadanga', 'Sagada', 'Tadian'],
                                                        'Basilan': ['Isabela City', 'Lamitan City', 'Akbar', 'Al-Barka', 'Hadji Mohammad Ajul', 'Hadji Muhtamad', 'Lantawan', 'Maluso', 'Sumisip', 'Tabuan-Lasa', 'Tipo-Tipo', 'Tuburan', 'Ungkaya Pukan'],
                                                        'Lanao del Sur': ['Marawi City', 'Bacolod-Kalawi', 'Balabagan', 'Balindong', 'Bayang', 'Binidayan', 'Buadiposo-Buntong', 'Bubong', 'Butig', 'Calanogas', 'Ditsaan-Ramain', 'Ganassi', 'Kapai', 'Kapatagan', 'Lumba-Bayabao', 'Lumbaca-Unayan', 'Lumbatan', 'Lumbayanague', 'Madalum', 'Madamba', 'Maguing', 'Malabang', 'Marantao', 'Marogong', 'Masiu', 'Mulondo', 'Pagayawan', 'Piagapo', 'Poona Bayabao', 'Pualas', 'Saguiaran', 'Sultan Dumalondong', 'Tagoloan II', 'Tamparan', 'Taraka', 'Tubaran', 'Tugaya', 'Wao'],
                                                        'Maguindanao del Norte': ['Datu Odin Sinsuat', 'Barira', 'Buldon', 'Cotabato City', 'Kabuntalan', 'Matanog', 'Northern Kabuntalan', 'Parang', 'Sultan Kudarat', 'Sultan Mastura', 'Talitay'],
                                                        'Maguindanao del Sur': ['Buluan', 'Datu Paglas', 'Datu Piang', 'Datu Salibo', 'Datu Saudi-Ampatuan', 'Datu Unsay', 'Gen. S.K. Pendatun', 'Guindulungan', 'Mamasapano', 'Mangudadatu', 'Paglat', 'Pagalungan', 'Pagagawan', 'Rajah Buayan', 'Shariff Aguak', 'Shariff Saydona Mustapha', 'South Upi', 'Sultan sa Barongis'],
                                                        'Sulu': ['Jolo', 'Banguingui', 'Hadji Panglima Tahil', 'Indanan', 'Kalingalan Caluang', 'Lugus', 'Luuk', 'Maimbung', 'Old Panamao', 'Omar', 'Pandami', 'Panglima Estino', 'Pangutaran', 'Parang', 'Patikul', 'Pata', 'Siasi', 'Talipao', 'Tapul'],
                                                        'Tawi-Tawi': ['Bongao', 'Mapun', 'Simunul', 'Sitangkai', 'South Ubian', 'Tandubas', 'Turtle Islands'],
                                                    };

                                                    function populateCities(province, selectedCity = '') {
                                                        const citySelect = document.getElementById('clientcity');
                                                        citySelect.innerHTML = '<option value="" disabled>Select City</option>';
                                                        if (citiesByProvince[province]) {
                                                            citiesByProvince[province].forEach(function(city) {
                                                                const selected = city === selectedCity ? 'selected' : '';
                                                                citySelect.innerHTML += `<option value="${city}" ${selected}>${city}</option>`;
                                                            });
                                                        }
                                                    }

                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        const provinceSelect = document.getElementById('clientprovince');
                                                        const selectedProvince = provinceSelect.value;
                                                        const selectedCity = "{{ $client->clientcity }}";
                                                        if (selectedProvince) {
                                                            populateCities(selectedProvince, selectedCity);
                                                        }
                                                        provinceSelect.addEventListener('change', function() {
                                                            populateCities(this.value);
                                                        });
                                                    });
                                                </script>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-1">
                                                            <input class="form-control" id="clientcontractstart" name="clientcontractstart" type="date" value="{{ $client->clientcontractstart }}" placeholder="Contract Start" />
                                                            <label for="clientcontractstart">Contract Start</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-floating mb-1">
                                                            <input class="form-control" id="clientcontractend" name="clientcontractend" type="date" value="{{ $client->clientcontractend }}" placeholder="Contract End" />
                                                            <label for="clientcontractend">Contract End</label>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientgeolocation" name="clientgeolocation" type="text" value="{{ $client->clientgeolocation }}" placeholder="Geolocation" />
                                                    <label for="clientgeolocation">Geolocation</label>
                                                </div>

                                                <div class="form-floating mb-1">
                                                    <input class="form-control" id="clientstreetview" name="clientstreetview" type="text" value="{{ $client->clientstreetview }}" placeholder="Streetview" />
                                                    <label for="clientstreetview">Streetview</label>
                                                </div>

                                                <label class="small mb-1 d-block">Client Status</label>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_active" value="1" {{ $client->isactive == 1 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_active">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="check-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Active
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="radio" name="status" id="status_inactive" value="0" {{ $client->isactive == 0 ? 'checked' : '' }}>
                                                    <label class="form-check-label text-body" for="status_inactive">
                                                        <span class="d-inline-flex align-items-center">
                                                            <i data-feather="x-circle" style="width: 1em; height: 1em; margin-right: 0.3em;"></i>
                                                            Inactive
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-between mt-2 mb-0">
                                            <button class="btn btn-outline-primary" type="button" data-bs-toggle="modal" data-bs-target="#confirmSaveModal">
                                                <i data-feather="bookmark" class="me-1"></i>
                                                Save Changes
                                            </button>
                                        </div>

                                        <!-- Confirmation Modal -->
                                        <div class="modal fade" id="confirmSaveModal" tabindex="-1" aria-labelledby="confirmSaveModalLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="confirmSaveModalLabel">Confirm Update</h5>
                                                        <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to save these changes?
                                                    </div>
                                                    <div class="modal-footer justify-content-center">
                                                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                                            <i data-feather="x-circle" class="me-1"></i>
                                                            Cancel
                                                        </button>
                                                        <button type="button" class="btn btn-outline-primary" id="modalSaveBtn">
                                                            <i data-feather="bookmark" class="me-1"></i>
                                                            Save
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <script>
                                            document.getElementById('modalSaveBtn').addEventListener('click', function() {
                                                this.closest('.modal').classList.remove('show');
                                                document.querySelector('.modal-backdrop').remove();
                                                this.closest('form').submit();
                                            });
                                        </script>


                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            @include('superadmin.footer')
</body>

</html>