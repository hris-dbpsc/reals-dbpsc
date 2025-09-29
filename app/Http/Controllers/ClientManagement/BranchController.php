<?php

namespace App\Http\Controllers\ClientManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Branches;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;
use App\Imports\BranchesImport;
use App\Exports\BranchesExport;

class BranchController extends Controller
{
    // =========================================
    // Branch Management
    //

    /**
     *  View Branches route
     *  Provides sorted active/inactive branches for the Blade view
     */
    public function branches(Request $request)
    {
        $branches = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select('branches.*', 'clients.clientname', 'clients.clientshortname')
            ->get();

        $clients = Client::orderBy('clientshortname')->get();
        $activeBranches = $branches->where('isactive', 1)->sortBy('clientname');
        $inactiveBranches = $branches->where('isactive', 0)->sortBy('clientname');

        // For Clientadmin, filter branches by their clientid
        if (Auth::guard('clientadmin')->check()) {
            $clientId = Auth::guard('clientadmin')->user()->clientid ?? Auth::guard('clientadmin')->user()->id;
            $clientactiveBranches = $branches->where('clientid', $clientId)->where('isactive', 1);
            $clientinactiveBranches = $branches->where('clientid', $clientId)->where('isactive', 0);
        }

        // Use guard to determine which view to return
        if (Auth::guard('superadmin')->check()) {
            return view('superadmin.clientmanagement.branches', compact('branches', 'clients', 'activeBranches', 'inactiveBranches'));
        } elseif (Auth::guard('admin')->check()) {
            return view('admin.clientmanagement.branches', compact('branches', 'clients', 'activeBranches', 'inactiveBranches'));
        } elseif (Auth::guard('clientadmin')->check()) {
            return view('clientadmin.branchmanagement.branches', compact('branches', 'clients', 'clientactiveBranches', 'clientinactiveBranches'));
        }
    }

    /**
     *  Add Branch route
     */
    public function addbranch()
    {
        $clients = Client::orderBy('clientname')->get();
        return view('superadmin.clientmanagement.addbranch', compact('clients'));
    }

    /**
     *  Add Branch submit
     */
    public function addbranch_submit(Request $request)
    {
        $request->validate([
            'clientname' => 'required',
            'branchname' => 'required',
            'clienttype' => 'required',
        ]);

        $branch = new Branches();
        $branch->clientid = $request->clientname;
        $branch->branchname = $request->branchname;
        $branch->clienttype = $request->clienttype;
        $branch->save();


        return redirect()->route('superadmin_branches')->with('success', 'Branch added successfully.');
    }

    /**
     *  View Branch details route
     */
    public function viewbranch(Request $request, $id)
    {
        $branch = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select('branches.*', 'clients.clientname', 'clients.clientphoto')
            ->where('branches.id', $id)
            ->first();
        // Use guard to determine which view to return
        if (Auth::guard('superadmin')->check()) {
            return view('superadmin.clientmanagement.viewbranch', compact('branch'));
        } elseif (Auth::guard('admin')->check()) {
            return view('admin.clientmanagement.viewbranch', compact('branch'));
        } elseif (Auth::guard('clientadmin')->check()) {
            return view('clientadmin.branchmanagement.viewbranch', compact('branch'));
        }
    }

    /**
     *  Edit Branch route
     */
    public function editbranch(Request $request, $id)
    {
        $branch = Branches::leftJoin('clients', 'branches.clientid', '=', 'clients.id')
            ->select('branches.*', 'clients.clientphoto')
            ->where('branches.id', $id)
            ->first();

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
        $provincesByRegion = [
            'I' => ['Ilocos Norte', 'Ilocos Sur', 'La Union', 'Pangasinan'],
            'II' => ['Batanes', 'Cagayan', 'Isabela', 'Nueva Vizcaya', 'Quirino'],
            'III' => ['Aurora', 'Bataan', 'Bulacan', 'Nueva Ecija', 'Pampanga', 'Tarlac', 'Zambales'],
            'IV-A' => ['Batangas', 'Cavite', 'Laguna', 'Quezon', 'Rizal'],
            'IV-B' => ['Marinduque', 'Occidental Mindoro', 'Oriental Mindoro', 'Palawan', 'Romblon'],
            'V' => ['Albay', 'Camarines Norte', 'Camarines Sur', 'Catanduanes', 'Masbate', 'Sorsogon'],
            'VI' => ['Aklan', 'Antique', 'Capiz', 'Guimaras', 'Iloilo', 'Negros Occidental'],
            'VII' => ['Bohol', 'Cebu', 'Negros Oriental', 'Siquijor'],
            'VIII' => ['Biliran', 'Eastern Samar', 'Leyte', 'Northern Samar', 'Samar', 'Southern Leyte'],
            'IX' => ['Zamboanga del Norte', 'Zamboanga del Sur', 'Zamboanga Sibugay'],
            'X' => ['Bukidnon', 'Camiguin', 'Lanao del Norte', 'Misamis Occidental', 'Misamis Oriental'],
            'XI' => ['Davao de Oro', 'Davao del Norte', 'Davao del Sur', 'Davao Occidental', 'Davao Oriental'],
            'XII' => ['Cotabato', 'Sarangani', 'South Cotabato', 'Sultan Kudarat'],
            'XIII' => ['Agusan del Norte', 'Agusan del Sur', 'Dinagat Islands', 'Surigao del Norte', 'Surigao del Sur'],
            'NCR' => ['Metro Manila'],
            'CAR' => ['Abra', 'Apayao', 'Benguet', 'Ifugao', 'Kalinga', 'Mountain Province'],
            'BARMM' => ['Basilan', 'Lanao del Sur', 'Maguindanao del Norte', 'Maguindanao del Sur', 'Sulu', 'Tawi-Tawi']
        ];
        $citiesByProvince = [
            'Ilocos Norte' => ['Laoag City', 'Batac City', 'Dingras', 'Pasuquin', 'Piddig', 'Solsona'],
            'Ilocos Sur' => ['Vigan City', 'Candon City', 'Tagudin', 'Narvacan', 'Santa Cruz', 'Sinait'],
            'La Union' => ['San Fernando City', 'Agoo', 'Bauang', 'Rosario', 'Naguilian'],
            'Pangasinan' => ['Dagupan City', 'San Carlos City', 'Urdaneta City', 'Alaminos City', 'Lingayen', 'Binmaley', 'Mangaldan', 'Bayambang'],
            'Batanes' => ['Basco', 'Itbayat', 'Ivana', 'Mahatao', 'Sabtang', 'Uyugan'],
            'Cagayan' => ['Tuguegarao City', 'Aparri', 'Gattaran', 'Solana', 'Peñablanca', 'Sanchez-Mira'],
            'Isabela' => ['Ilagan City', 'Cauayan City', 'Santiago City', 'Roxas', 'Echague', 'Cabagan', 'Tumauini'],
            'Nueva Vizcaya' => ['Bayombong', 'Solano', 'Kasibu', 'Dupax del Norte', 'Aritao'],
            'Quirino' => ['Cabarroguis', 'Diffun', 'Saguday', 'Maddela'],
            'Aurora' => ['Baler', 'Casiguran', 'Dingalan', 'Maria Aurora'],
            'Bataan' => ['Balanga City', 'Orion', 'Dinalupihan', 'Mariveles', 'Orani', 'Abucay'],
            'Bulacan' => ['Malolos City', 'Meycauayan City', 'San Jose del Monte City', 'Angat', 'Balagtas', 'Baliuag', 'Bocaue', 'Bulakan', 'Calumpit', 'Doña Remedios Trinidad', 'Guiguinto', 'Hagonoy', 'Marilao', 'Norzagaray', 'Obando', 'Pandi', 'Paombong', 'Plaridel', 'Pulilan', 'San Ildefonso', 'San Miguel', 'San Rafael', 'Santa Maria'],
            'Nueva Ecija' => ['Cabanatuan City', 'San Jose City', 'Gapan City', 'Palayan City', 'Aliaga', 'Guimba', 'San Antonio', 'San Isidro'],
            'Pampanga' => ['San Fernando City', 'Angeles City', 'Mabalacat City', 'Apalit', 'Arayat', 'Bacolor', 'Candaba', 'Floridablanca', 'Guagua', 'Lubao', 'Macabebe', 'Magalang', 'Masantol', 'Mexico', 'Minalin', 'Porac', 'San Luis', 'San Simon', 'Santa Ana', 'Santa Rita', 'Santo Tomas', 'Sasmuan'],
            'Tarlac' => ['Tarlac City', 'Capas', 'Concepcion', 'Gerona', 'Paniqui', 'Ramos', 'San Manuel', 'Victoria'],
            'Zambales' => ['Olongapo City', 'Iba', 'Subic', 'Castillejos', 'San Antonio', 'San Felipe', 'San Marcelino'],
            'Batangas' => ['Batangas City', 'Lipa City', 'Tanauan City', 'Balayan', 'Bauan', 'Calaca', 'Calatagan', 'Lemery', 'Nasugbu', 'San Juan', 'San Jose', 'Taal'],
            'Cavite' => ['Tagaytay City', 'Dasmariñas City', 'Imus City', 'Bacoor City', 'Cavite City', 'General Trias City', 'Trece Martires City', 'Alfonso', 'Amadeo', 'Carmona', 'General Emilio Aguinaldo', 'GMA', 'Indang', 'Kawit', 'Magallanes', 'Maragondon', 'Mendez', 'Naic', 'Noveleta', 'Rosario', 'Silang', 'Tanza', 'Ternate'],
            'Laguna' => ['San Pablo City', 'Santa Rosa City', 'Calamba City', 'Biñan City', 'Cabuyao City', 'Alaminos', 'Bay', 'Calauan', 'Famy', 'Kalayaan', 'Liliw', 'Los Baños', 'Luisiana', 'Mabitac', 'Magdalena', 'Majayjay', 'Nagcarlan', 'Paete', 'Pagsanjan', 'Pakil', 'Pangil', 'Pila', 'Rizal', 'San Pedro City', 'Siniloan', 'Sta. Cruz', 'Victoria'],
            'Quezon' => ['Lucena City', 'Tayabas City', 'Candelaria', 'Dolores', 'Infanta', 'Mauban', 'Polillo', 'Sariaya', 'Tiaong', 'Unisan'],
            'Rizal' => ['Antipolo City', 'Angono', 'Binangonan', 'Cainta', 'Cardona', 'Jalajala', 'Morong', 'Pililla', 'Rodriguez', 'San Mateo', 'Tanay', 'Taytay', 'Teresa'],
            'Marinduque' => ['Boac', 'Gasan', 'Mogpog', 'Santa Cruz', 'Torrijos', 'Buenavista'],
            'Occidental Mindoro' => ['Mamburao', 'Abra de Ilog', 'Calintaan', 'Looc', 'Lubang', 'Paluan', 'Rizal', 'Sablayan', 'San Jose', 'Santa Cruz'],
            'Oriental Mindoro' => ['Calapan City', 'Baco', 'Bansud', 'Bongabong', 'Bulalacao', 'Gloria', 'Mansalay', 'Naujan', 'Pinamalayan', 'Pola', 'Puerto Galera', 'Roxas', 'San Teodoro', 'Socorro', 'Victoria'],
            'Palawan' => ['Puerto Princesa City', 'Aborlan', 'Agutaya', 'Araceli', 'Balabac', 'Bataraza', 'Brooke\'s Point', 'Busuanga', 'Cagayancillo', 'Coron', 'Culion', 'Cuyo', 'Dumaran', 'El Nido', 'Kalayaan', 'Linapacan', 'Magsaysay', 'Narra', 'Quezon', 'Rizal', 'Roxas', 'San Vicente', 'Sofronio Española', 'Taytay'],
            'Romblon' => ['Romblon', 'Alcantara', 'Banton', 'Cajidiocan', 'Calatrava', 'Concepcion', 'Corcuera', 'Ferrol', 'Looc', 'Magdiwang', 'Odiongan', 'San Agustin', 'San Andres', 'San Fernando', 'Santa Fe', 'Santa Maria'],
            'Albay' => ['Legazpi City', 'Tabaco City', 'Ligao City', 'Bacacay', 'Camalig', 'Daraga', 'Guinobatan', 'Jovellar', 'Libon', 'Malilipot', 'Malinao', 'Manito', 'Oas', 'Pio Duran', 'Polangui', 'Rapu-Rapu', 'Santo Domingo', 'Tiwi'],
            'Camarines Norte' => ['Daet', 'Basud', 'Capalonga', 'Jose Panganiban', 'Labo', 'Mercedes', 'Paracale', 'San Lorenzo Ruiz', 'San Vicente', 'Santa Elena', 'Talisay', 'Vinzons'],
            'Camarines Sur' => ['Naga City', 'Iriga City', 'Baao', 'Balatan', 'Bato', 'Bombon', 'Buhi', 'Bula', 'Cabusao', 'Calabanga', 'Camaligan', 'Canaman', 'Caramoan', 'Del Gallego', 'Gainza', 'Garchitorena', 'Goa', 'Lagonoy', 'Libmanan', 'Lupi', 'Magarao', 'Milaor', 'Minalabac', 'Nabua', 'Ocampo', 'Pamplona', 'Pasacao', 'Pili', 'Presentacion', 'Ragay', 'Sagñay', 'San Fernando', 'San Jose', 'Sipocot', 'Siruma', 'Tigaon', 'Tinambac'],
            'Catanduanes' => ['Virac', 'Bagamanoc', 'Baras', 'Bato', 'Caramoran', 'Gigmoto', 'Pandan', 'Panganiban', 'San Andres', 'San Miguel', 'Viga'],
            'Masbate' => ['Masbate City', 'Aroroy', 'Baleno', 'Balud', 'Batuan', 'Cataingan', 'Cawayan', 'Claveria', 'Dimasalang', 'Esperanza', 'Mandaon', 'Milagros', 'Monreal', 'Palanas', 'Pio V. Corpuz', 'Placer', 'San Fernando', 'San Jacinto', 'San Pascual', 'Uson'],
            'Sorsogon' => ['Sorsogon City', 'Barcelona', 'Bulan', 'Bulusan', 'Casiguran', 'Castilla', 'Donsol', 'Gubat', 'Irosin', 'Juban', 'Magallanes', 'Matnog', 'Pilar', 'Prieto Diaz', 'Santa Magdalena'],
            'Aklan' => ['Kalibo', 'Altavas', 'Balete', 'Banga', 'Batan', 'Buruanga', 'Ibajay', 'Lezo', 'Libacao', 'Madalag', 'Makato', 'Malay', 'Malinao', 'Nabas', 'New Washington', 'Numancia', 'Tangalan'],
            'Antique' => ['San Jose', 'Anini-y', 'Barbaza', 'Belison', 'Bugasong', 'Caluya', 'Culasi', 'Hamtic', 'Laua-an', 'Libertad', 'Pandan', 'Patnongon', 'San Remigio', 'Sebaste', 'Sibalom', 'Tibiao', 'Tobias Fornier', 'Valderrama'],
            'Capiz' => ['Roxas City', 'Cuartero', 'Dao', 'Dumalag', 'Dumarao', 'Ivisan', 'Jamindan', 'Ma-ayon', 'Mambusao', 'Panay', 'Panitan', 'Pilar', 'Pontevedra', 'President Roxas', 'Sapian', 'Sigma', 'Tapaz'],
            'Guimaras' => ['Jordan', 'Buenavista', 'Nueva Valencia', 'San Lorenzo', 'Sibunag'],
            'Iloilo' => ['Iloilo City', 'Passi City', 'Ajuy', 'Alimodian', 'Anilao', 'Badiangan', 'Balasan', 'Banate', 'Barotac Nuevo', 'Barotac Viejo', 'Batad', 'Bingawan', 'Cabatuan', 'Calinog', 'Carles', 'Concepcion', 'Dingle', 'Dueñas', 'Dumangas', 'Estancia', 'Guimbal', 'Igbaras', 'Janiuay', 'Lambunao', 'Leganes', 'Lemery', 'Leon', 'Maasin', 'Miagao', 'Mina', 'New Lucena', 'Oton', 'Pavia', 'Pototan', 'San Dionisio', 'San Enrique', 'San Joaquin', 'San Miguel', 'San Rafael', 'Santa Barbara', 'Sara', 'Tigbauan', 'Tubungan', 'Zarraga'],
            'Negros Occidental' => ['Bacolod City', 'Sipalay City', 'Bago City', 'Cadiz City', 'Escalante City', 'Himamaylan City', 'Kabankalan City', 'La Carlota City', 'Sagay City', 'San Carlos City', 'Silay City', 'Talisay City', 'Victorias City', 'Binalbagan', 'Calatrava', 'Cauayan', 'Enrique B. Magalona', 'Hinigaran', 'Hinoba-an', 'Ilog', 'Isabela', 'La Castellana', 'Manapla', 'Moises Padilla', 'Murcia', 'Pontevedra', 'Pulupandan', 'Salvador Benedicto', 'San Enrique', 'Toboso', 'Valladolid'],
            'Bohol' => ['Tagbilaran City', 'Alburquerque', 'Alicia', 'Anda', 'Antequera', 'Baclayon', 'Balilihan', 'Batuan', 'Bien Unido', 'Bilar', 'Buenavista', 'Calape', 'Candijay', 'Carmen', 'Catigbian', 'Clarin', 'Corella', 'Cortes', 'Dagohoy', 'Danao', 'Dauis', 'Dimiao', 'Duero', 'Garcia Hernandez', 'Guindulman', 'Inabanga', 'Jagna', 'Lila', 'Loay', 'Loboc', 'Loon', 'Mabini', 'Maribojoc', 'Panglao', 'Pilar', 'President Carlos P. Garcia', 'Sagbayan', 'San Isidro', 'San Miguel', 'Sevilla', 'Sierra Bullones', 'Sikatuna', 'Talibon', 'Trinidad', 'Tubigon', 'Ubay', 'Valencia'],
            'Cebu' => ['Cebu City', 'Mandaue City', 'Lapu-Lapu City', 'Toledo City', 'Talisay City', 'Naga City', 'Carcar City', 'Danao City', 'Bogo City', 'Alcantara', 'Alcoy', 'Alegria', 'Aloguinsan', 'Argao', 'Asturias', 'Badian', 'Balamban', 'Bantayan', 'Barili', 'Boljoon', 'Borbon', 'Carmen', 'Catmon', 'Compostela', 'Consolacion', 'Cordova', 'Daanbantayan', 'Dalaguete', 'Dumanjug', 'Ginatilan', 'Liloan', 'Madridejos', 'Malabuyoc', 'Medellin', 'Minglanilla', 'Moalboal', 'Oslob', 'Pilar', 'Pinamungajan', 'Poro', 'Ronda', 'Samboan', 'San Fernando', 'San Francisco', 'San Remigio', 'Santa Fe', 'Santander', 'Sibonga', 'Sogod', 'Tabogon', 'Tabuelan', 'Tuburan', 'Tudela'],
            'Negros Oriental' => ['Dumaguete City', 'Bais City', 'Bayawan City', 'Canlaon City', 'Guihulngan City', 'Tanjay City', 'Amlan', 'Ayungon', 'Bacong', 'Basay', 'Bindoy', 'Dauin', 'Jimalalud', 'La Libertad', 'Mabinay', 'Manjuyod', 'Pamplona', 'San Jose', 'Santa Catalina', 'Siaton', 'Sibulan', 'Tayasan', 'Valencia', 'Vallehermoso', 'Zamboanguita'],
            'Siquijor' => ['Siquijor', 'Enrique Villanueva', 'Larena', 'Lazi', 'Maria', 'San Juan'],
            'Biliran' => ['Naval', 'Almeria', 'Biliran', 'Cabucgayan', 'Caibiran', 'Culaba', 'Kawayan', 'Maripipi'],
            'Eastern Samar' => ['Borongan City', 'Arteche', 'Balangiga', 'Balangkayan', 'Can-avid', 'Dolores', 'General MacArthur', 'Giporlos', 'Guiuan', 'Hernani', 'Jipapad', 'Lawaan', 'Llorente', 'Maslog', 'Maydolong', 'Mercedes', 'Oras', 'Quinapondan', 'Salcedo', 'San Julian', 'San Policarpo', 'Sulat', 'Taft'],
            'Leyte' => ['Tacloban City', 'Ormoc City', 'Baybay City', 'Abuyog', 'Alangalang', 'Albuera', 'Babatngon', 'Barugo', 'Bato', 'Burauen', 'Calubian', 'Capoocan', 'Carigara', 'Dagami', 'Dulag', 'Hilongos', 'Hindang', 'Inopacan', 'Isabel', 'Jaro', 'Julita', 'Kananga', 'La Paz', 'Leyte', 'MacArthur', 'Mahaplag', 'Matag-ob', 'Matalom', 'Mayorga', 'Merida', 'Palo', 'Palompon', 'Pastrana', 'San Isidro', 'San Miguel', 'Santa Fe', 'Tabango', 'Tabontabon', 'Tanauan', 'Tolosa', 'Tunga', 'Villaba'],
            'Northern Samar' => ['Catarman', 'Allen', 'Biri', 'Bobon', 'Capul', 'Catubig', 'Gamay', 'Laoang', 'Lapinig', 'Las Navas', 'Lavezares', 'Mapanas', 'Mondragon', 'Palapag', 'Pambujan', 'Rosario', 'San Antonio', 'San Isidro', 'San Jose', 'San Roque', 'San Vicente', 'Silvino Lobos', 'Victoria'],
            'Samar' => ['Catbalogan City', 'Calbayog City', 'Almagro', 'Basey', 'Calbiga', 'Daram', 'Gandara', 'Hinabangan', 'Jiabong', 'Marabut', 'Matuguinao', 'Motiong', 'Pagsanghan', 'Paranas', 'Pinabacdao', 'San Jorge', 'San Jose de Buan', 'San Sebastian', 'Santa Margarita', 'Santa Rita', 'Santo Niño', 'Tagapul-an', 'Talalora', 'Tarangnan', 'Villareal', 'Zumarraga'],
            'Southern Leyte' => ['Maasin City', 'Anahawan', 'Bontoc', 'Hinunangan', 'Hinundayan', 'Libagon', 'Liloan', 'Macrohon', 'Malitbog', 'Padre Burgos', 'Pintuyan', 'Saint Bernard', 'San Francisco', 'San Juan', 'San Ricardo', 'Silago', 'Sogod', 'Tomas Oppus'],
            'Zamboanga del Norte' => ['Dipolog City', 'Dapitan City', 'Sindangan', 'Katipunan', 'Manukan', 'Polanco', 'Rizal', 'Siayan', 'Sibutad', 'Labason', 'Liloy', 'Salug', 'Tampilisan'],
            'Zamboanga del Sur' => ['Pagadian City', 'Zamboanga City', 'Aurora', 'Bayog', 'Dimataling', 'Dinas', 'Dumalinao', 'Dumingag', 'Guipos', 'Josefina', 'Kumalarang', 'Labangan', 'Lakewood', 'Lapuyan', 'Mahayag', 'Margosatubig', 'Midsalip', 'Molave', 'Pitogo', 'Ramon Magsaysay', 'San Miguel', 'San Pablo', 'Sominot', 'Tabina', 'Tambulig', 'Tigbao', 'Tukuran', 'Vincenzo A. Sagun'],
            'Zamboanga Sibugay' => ['Ipil', 'Alicia', 'Buug', 'Diplahan', 'Imelda', 'Kabasalan', 'Mabuhay', 'Malangas', 'Naga', 'Olutanga', 'Payao', 'Roseller Lim', 'Siay', 'Talusan', 'Titay'],
            'Bukidnon' => ['Malaybalay City', 'Valencia City', 'Baungon', 'Cabanglasan', 'Damulog', 'Dangcagan', 'Don Carlos', 'Impasugong', 'Kadingilan', 'Kalilangan', 'Kibawe', 'Kitaotao', 'Lantapan', 'Libona', 'Malitbog', 'Manolo Fortich', 'Maramag', 'Pangantucan', 'Quezon', 'San Fernando', 'Sumilao', 'Talakag'],
            'Camiguin' => ['Mambajao', 'Catarman', 'Guinsiliban', 'Mahinog', 'Sagay'],
            'Lanao del Norte' => ['Iligan City', 'Bacolod', 'Baloi', 'Baroy', 'Kapatagan', 'Kauswagan', 'Kolambugan', 'Lala', 'Linamon', 'Magsaysay', 'Maigo', 'Matungao', 'Munai', 'Nunungan', 'Pantao Ragat', 'Pantar', 'Poona Piagapo', 'Salvador', 'Sapad', 'Sultan Naga Dimaporo', 'Tagoloan', 'Tangcal', 'Tubod'],
            'Misamis Occidental' => ['Oroquieta City', 'Ozamiz City', 'Tangub City', 'Aloran', 'Baliangao', 'Bonifacio', 'Calamba', 'Clarin', 'Concepcion', 'Don Victoriano Chiongbian', 'Jimenez', 'Lopez Jaena', 'Panaon', 'Plaridel', 'Sapang Dalaga', 'Sinacaban', 'Tudela'],
            'Misamis Oriental' => ['Cagayan de Oro City', 'Gingoog City', 'Alubijid', 'Balingasag', 'Balingoan', 'Binuangan', 'Claveria', 'Gitagum', 'Initao', 'Jasaan', 'Kinoguitan', 'Lagonglong', 'Laguindingan', 'Libertad', 'Lugait', 'Magsaysay', 'Manticao', 'Medina', 'Naawan', 'Opol', 'Salay', 'Sugbongcogon', 'Tagoloan', 'Talisayan', 'Villanueva'],
            'Davao de Oro' => ['Nabunturan', 'Compostela', 'Laak', 'Mabini', 'Maco', 'Maragusan', 'Monkayo', 'Montevista', 'New Bataan', 'Pantukan'],
            'Davao del Norte' => ['Tagum City', 'Panabo City', 'Samal City', 'Asuncion', 'Braulio E. Dujali', 'Carmen', 'Kapalong', 'New Corella', 'San Isidro', 'Santo Tomas', 'Talaingod'],
            'Davao del Sur' => ['Digos City', 'Bansalan', 'Davao City', 'Hagonoy', 'Kiblawan', 'Magsaysay', 'Malalag', 'Matanao', 'Padada', 'Santa Cruz', 'Sulop'],
            'Davao Occidental' => ['Malita', 'Don Marcelino', 'Jose Abad Santos', 'Santa Maria', 'Sarangani'],
            'Davao Oriental' => ['Mati City', 'Baganga', 'Banaybanay', 'Boston', 'Caraga', 'Cateel', 'Governor Generoso', 'Lupon', 'Manay', 'San Isidro', 'Tarragona'],
            'Cotabato' => ['Kidapawan City', 'Alamada', 'Aleosan', 'Antipas', 'Arakan', 'Banisilan', 'Carmen', 'Kabacan', 'Libungan', 'M\'lang', 'Magpet', 'Makilala', 'Matalam', 'Pigcawayan', 'Pikit', 'President Roxas', 'Tulunan'],
            'Sarangani' => ['Alabel', 'Glan', 'Kiamba', 'Maasim', 'Maitum', 'Malapatan', 'Malungon'],
            'South Cotabato' => ['Koronadal City', 'General Santos City', 'Banga', 'Lake Sebu', 'Norala', 'Polomolok', 'Santo Niño', 'Surallah', 'Tampakan', 'Tantangan', 'T\'boli'],
            'Sultan Kudarat' => ['Isulan', 'Bagumbayan', 'Columbio', 'Esperanza', 'Kalamansig', 'Lambayong', 'Lebak', 'Lutayan', 'Palimbang', 'President Quirino', 'Sen. Ninoy Aquino', 'Tacurong City'],
            'Agusan del Norte' => ['Butuan City', 'Cabadbaran City', 'Buenavista', 'Carmen', 'Jabonga', 'Kitcharao', 'Las Nieves', 'Magallanes', 'Nasipit', 'Remedios T. Romualdez', 'Santiago', 'Tubay'],
            'Agusan del Sur' => ['Bayugan City', 'Bunawan', 'Esperanza', 'La Paz', 'Loreto', 'Prosperidad', 'Rosario', 'San Francisco', 'San Luis', 'Santa Josefa', 'Sibagat', 'Talacogon', 'Trento', 'Veruela'],
            'Dinagat Islands' => ['San Jose', 'Basilisa', 'Cagdianao', 'Dinagat', 'Libjo', 'Loreto', 'Tubajon'],
            'Surigao del Norte' => ['Surigao City', 'Alegria', 'Bacuag', 'Burgos', 'Claver', 'Dapa', 'Del Carmen', 'General Luna', 'Gigaquit', 'Mainit', 'Malimono', 'Pilar', 'Placer', 'San Benito', 'San Isidro', 'Santa Monica', 'Sison', 'Socorro', 'Tagana-an', 'Tubod'],
            'Surigao del Sur' => ['Bislig City', 'Tandag City', 'Barobo', 'Bayabas', 'Cagwait', 'Cantilan', 'Carmen', 'Carrascal', 'Cortes', 'Hinatuan', 'Lanuza', 'Lianga', 'Lingig', 'Madrid', 'Marihatag', 'San Agustin', 'San Miguel', 'Tagbina', 'Tago'],
            'Metro Manila' => ['Caloocan', 'Las Piñas', 'Makati', 'Malabon', 'Mandaluyong', 'Manila', 'Marikina', 'Muntinlupa', 'Navotas', 'Parañaque', 'Pasay', 'Pasig', 'Pateros', 'Quezon City', 'San Juan', 'Taguig', 'Valenzuela'],
            'Abra' => ['Bangued', 'Boliney', 'Bucay', 'Bucloc', 'Daguioman', 'Danglas', 'Dolores', 'La Paz', 'Lacub', 'Lagangilang', 'Lagayan', 'Langiden', 'Licuan-Baay', 'Luba', 'Malibcong', 'Manabo', 'Peñarrubia', 'Pidigan', 'Pilar', 'Sallapadan', 'San Isidro', 'San Juan', 'San Quintin', 'Tayum', 'Tineg', 'Tubo', 'Villaviciosa'],
            'Apayao' => ['Conner', 'Calanasan', 'Flora', 'Kabugao', 'Luna', 'Pudtol', 'Santa Marcela'],
            'Benguet' => ['La Trinidad', 'Baguio City', 'Atok', 'Bakun', 'Bokod', 'Buguias', 'Itogon', 'Kabayan', 'Kapangan', 'Kibungan', 'Mankayan', 'Sablan', 'Tuba', 'Tublay'],
            'Ifugao' => ['Lagawe', 'Aguinaldo', 'Asipulo', 'Banaue', 'Hingyon', 'Hungduan', 'Kiangan', 'Mayoyao', 'Tinoc'],
            'Kalinga' => ['Tabuk City', 'Balbalan', 'Lubuagan', 'Pasil', 'Pinukpuk', 'Rizal', 'Tanudan', 'Tinglayan'],
            'Mountain Province' => ['Bontoc', 'Barlig', 'Besao', 'Natonin', 'Paracelis', 'Sabangan', 'Sadanga', 'Sagada', 'Tadian'],
            'Basilan' => ['Isabela City', 'Lamitan City', 'Akbar', 'Al-Barka', 'Hadji Mohammad Ajul', 'Hadji Muhtamad', 'Lantawan', 'Maluso', 'Sumisip', 'Tabuan-Lasa', 'Tipo-Tipo', 'Tuburan', 'Ungkaya Pukan'],
            'Lanao del Sur' => ['Marawi City', 'Bacolod-Kalawi', 'Balabagan', 'Balindong', 'Bayang', 'Binidayan', 'Buadiposo-Buntong', 'Bubong', 'Butig', 'Calanogas', 'Ditsaan-Ramain', 'Ganassi', 'Kapai', 'Kapatagan', 'Lumba-Bayabao', 'Lumbaca-Unayan', 'Lumbatan', 'Lumbayanague', 'Madalum', 'Madamba', 'Maguing', 'Malabang', 'Marantao', 'Marogong', 'Masiu', 'Mulondo', 'Pagayawan', 'Piagapo', 'Poona Bayabao', 'Pualas', 'Saguiaran', 'Sultan Dumalondong', 'Tagoloan II', 'Tamparan', 'Taraka', 'Tubaran', 'Tugaya', 'Wao'],
            'Maguindanao del Norte' => ['Datu Odin Sinsuat', 'Barira', 'Buldon', 'Cotabato City', 'Kabuntalan', 'Matanog', 'Northern Kabuntalan', 'Parang', 'Sultan Kudarat', 'Sultan Mastura', 'Talitay'],
            'Maguindanao del Sur' => ['Buluan', 'Datu Paglas', 'Datu Piang', 'Datu Salibo', 'Datu Saudi-Ampatuan', 'Datu Unsay', 'Gen. S.K. Pendatun', 'Guindulungan', 'Mamasapano', 'Mangudadatu', 'Paglat', 'Pagalungan', 'Pagagawan', 'Rajah Buayan', 'Shariff Aguak', 'Shariff Saydona Mustapha', 'South Upi', 'Sultan sa Barongis'],
            'Sulu' => ['Jolo', 'Banguingui', 'Hadji Panglima Tahil', 'Indanan', 'Kalingalan Caluang', 'Lugus', 'Luuk', 'Maimbung', 'Old Panamao', 'Omar', 'Pandami', 'Panglima Estino', 'Pangutaran', 'Parang', 'Patikul', 'Pata', 'Siasi', 'Talipao', 'Tapul'],
            'Tawi-Tawi' => ['Bongao', 'Mapun', 'Simunul', 'Sitangkai', 'South Ubian', 'Tandubas', 'Turtle Islands'],
        ];

        $selectedRegion = $branch->branchregion ?? null;
        $selectedProvince = $branch->branchprovince ?? null;
        $selectedCity = $branch->branchcity ?? null;

        return view('superadmin.clientmanagement.editbranch', compact(
            'branch',
            'regions',
            'provincesByRegion',
            'citiesByProvince',
            'selectedRegion',
            'selectedProvince',
            'selectedCity'
        ));
    }

    /**
     *  Edit Branch Submit route
     */
    public function editbranch_submit(Request $request, $id)
    {
        $request->validate([
            'clientname' => 'required',
            'branchname' => 'required',
            'branchcontact' => 'required',
            'branchcontactperson' => 'required',
            'branchaddress' => 'required',
            'branchregion' => 'required',
            'branchcity' => 'required',
            'branchprovince' => 'required',
            'branchgeolocation' => 'required',
            'branchstreetview' => 'required',
            'status' => 'required',
        ]);

        $branch = Branches::find($id);

        $branch->update([
            'clientname' => $request->clientname,
            'branchname' => $request->branchname,
            'branchcontact' => $request->branchcontact,
            'branchcontactperson' => $request->branchcontactperson,
            'branchaddress' => $request->branchaddress,
            'branchregion' => $request->branchregion,
            'branchcity' => $request->branchcity,
            'branchprovince' => $request->branchprovince,
            'branchgeolocation' => $request->branchgeolocation,
            'branchstreetview' => $request->branchstreetview,
            'isactive' => $request->status,
        ]);

        return redirect()->route('superadmin_branches')->with('success', 'Branch updated successfully.');
    }

    /**
     *  Soft delete Branch route (set isactive to 0)
     */
    public function softdeletebranch(Request $request, $id)
    {
        $branch = Branches::find($id);
        if (!$branch) {
            return redirect()->route('superadmin_branches')->with('error', 'Branch not found.');
        }
        $branch->isactive = 0;
        $branch->save();
        return redirect()->route('superadmin_branches')->with('success', 'Branch deactivated successfully.');
    }

    /**
     *  Import Branches from CSV
     */
    public function importbranches(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|max:10000',
        ]);

        Excel::import(new BranchesImport, $request->file('csv_file'));
        return redirect()->route('superadmin_branches')->with('success', 'Branches imported successfully.');
    }
    /**
     *  Export Branches
     */
    public function exportbranches(Request $request)
    {
        $clientshortname = $request->input('clientname');
        if ($clientshortname === 'ALL CLIENTS' || $clientshortname === null || $clientshortname === '') {
            $clientshortname = null; // Pass null to export all branches
        }
        $filename = ($clientshortname ? $clientshortname . '_branches_' : 'ALL_branches_') . date('YmdHis') . '.xlsx';
        return Excel::download(new BranchesExport($clientshortname), $filename);
    }

    // 
    // End Branch Management
    // =========================================


}
