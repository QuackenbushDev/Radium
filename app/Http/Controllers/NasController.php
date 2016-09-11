<?php namespace App\Http\Controllers;

use App\Nas;
use App\RadiusAccount;
use Illuminate\Http\Request;

class NasController extends Controller {
    /**
     * Array of router manufactures
     *
     * @var array
     */
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

    /**
     * Nas listing page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request) {
        $filterValue = $request->input('filter', '');
        $nasList = Nas::select('*');

        if (!empty($filterValue)) {
            $nasList->where("nasname", 'LIKE', '%' . $filterValue . '%')
                ->orWhere('shortname', 'LIKE', '%' . $filterValue . '%');
        }

        $nasList = $nasList->paginate();

        return view()->make(
            'pages.nas.index',
            [
                'nasList' => $nasList,
                'filter'  => $request->input('filter', ''),
            ]
        );
    }

    /**
     * Nas detail view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $nas = Nas::find($id);
        $latestActivity = RadiusAccount::getLatestNasActivity($nas->nasname, 15)->get();

        return view()->make(
            'pages.nas.show',
            [
                'nas' => $nas,
                'latestActivity' => $latestActivity,
            ]
        );
    }

    /**
     * Nas edit view
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
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

    /**
     * Nas create view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create() {
        $nas = new Nas();
        $nas->type = config('radium.nas_default_type');

        return view()->make(
            'pages.nas.edit',
            [
                'new'   => true,
                'nas'   => $nas,
                'types' => $this->types,
            ]
        );
    }

    /**
     * Creates a new nas
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request) {
        $nas = Nas::create([
            'nasname'     => $request->input('nas_name', ''),
            'shortname'   => $request->input('short_name', ''),
            'type'        => $request->input('type', ''),
            'ports'       => $request->input('ports', ''),
            'secret'      => $request->input('secret', ''),
            'server'      => $request->input('server', ''),
            'community'   => $request->input('community', ''),
            'description' => $request->input('description', '')
        ]);

        $request->session()->flash('message', 'Successfully created NAS. Please restart FreeRadius for it to take effect.');
        $request->session()->flash('alert-class', 'alert-success');

        return redirect(route('nas::show', $nas->id));
    }

    /**
     * Updates an existing nas with the submitted information.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
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