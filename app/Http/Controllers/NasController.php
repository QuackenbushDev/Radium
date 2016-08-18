<?php namespace App\Http\Controllers;

use App\Nas;
use App\RadiusAccount;
use Illuminate\Http\Request;

class NasController extends Controller {
    private $types = [
        ''           => '',
        'other'      => 'Other',
        'cisco'      => 'Cisco',
        'livingston' => 'Livingston',
        'computon'   => 'Computon',
        'max40xx'    => 'Max40xx',
        'multitech'  => 'Multitech',
        'natserver'  => 'Natserver',
        'pathras'    => 'Pathras',
        'patton'     => 'Patton',
        'portslave'  => 'Portslave',
        'tc'         => 'tc',
        'usrhiper'   => 'Usrhiper',
        'mikrotik'   => 'Mikrotik',
    ];

    public function index() {
        $nasList = Nas::paginate();

        return view()->make('pages.nas.index', ['nasList' => $nasList]);
    }

    public function show($id) {
        $nas = Nas::find($id);
        $latestActivity = RadiusAccount::getLatestNasActivity($nas->nasname, 15)->get();
        $bandwidthUsage = RadiusAccount::getMonthlyBandwidthUsage(null, $nas->nasname);

        return view()->make(
            'pages.nas.show',
            [
                'nas' => $nas,
                'latestActivity' => $latestActivity,
                'bandwidthUsage' => $bandwidthUsage,
            ]
        );
    }

    public function edit($id) {
        $nas = Nas::find($id);

        return view()->make(
            'pages.nas.edit',
            [
                'nas'   => $nas,
                'types' => $this->types,
            ]
        );
    }

    public function create() {
        $nas = new Nas();

        return view()->make(
            'pages.nas.edit',
            [
                'new'   => true,
                'nas'   => $nas,
                'types' => $this->types,
            ]
        );
    }

    public function save(Request $request) {
        $nas = new Nas();
        $nas->nasname = $request->input('nas_name', '');
        $nas->shortname = $request->input('short_name', '');
        $nas->type = $request->input('type', '');
        $nas->ports = $request->input('ports', '');
        $nas->secret = $request->input('secret', '');
        $nas->server = $request->input('server', '');
        $nas->community = $request->input('community', '');
        $nas->description = $request->input('description', '');
        $nas->save();

        $request->session()->flash('message', 'Successfully created NAS. Please restart FreeRadius for it to take effect.');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('nas::show', $nas->id));
    }

    public function store(Request $request, $id) {
        Nas::find($id)
            ->update([
                'nasname'     => $request->input('nas_name', ''),
                'shortname'   => $request->input('short_name', ''),
                'type'        => $request->input('type', ''),
                'ports'       => $request->input('ports', ''),
                'secret'      => $request->input('secret', ''),
                'server'      => $request->input('server', ''),
                'community'   => $request->input('community', ''),
                'description' => $request->input('description', ''),
            ]);

        $request->session()->flash('message', 'Successfully updated NAS. Please restart your radius server');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('nas::show', $id));
    }
}