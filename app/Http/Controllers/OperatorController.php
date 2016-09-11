<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class OperatorController extends Controller {
    /**
     * Displays the operator listing page
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request) {
        $filterValue = $request->input('filter', '');
        if (!empty($filterValue)) {
            $operatorList = User::where('email', 'LIKE', '%' . $filterValue . '%')
                ->paginate();
        } else {
            $operatorList = User::paginate();
        }

        return view()->make(
            'pages.operator.index',
            [
                'operatorList' => $operatorList,
                'filterValue'  => $filterValue,
            ]
        );
    }

    /**
     * Displays a single operator
     *
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function show($id) {
        $operator = User::find($id);

        return view()->make(
            'pages.operator.show',
            [
                'operator' => $operator,
            ]
        );
    }

    /**
     * Displays the operator edit form
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Request $request, $id) {
        $operator = User::find($id);

        return view()->make(
            'pages.operator.edit',
            [
                'operator' => $operator,
            ]
        );
    }


    /**
     * Displays the create new operator form
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create() {
        $operator = new User();

        return view()->make(
            'pages.operator.edit',
            [
                'new'      => true,
                'operator' => $operator,
            ]
        );
    }

    /**
     * Creates a new operator for the system
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function save(Request $request) {
        $operator = new User();
        $operator->name = $request->input('name');
        $operator->email = $request->input('email');
        $operator->company = $request->input('company', '');
        $operator->department = $request->input('department', '');
        $operator->home_phone = $request->input('home_phone', '');
        $operator->work_phone = $request->input('work_phone', '');
        $operator->mobile_phone = $request->input('mobile_phone', '');
        $operator->address = $request->input('address', '');
        $operator->notes = $request->input('notes', '');

        $enable_weekly_summary = $request->input('enable_weekly_summary', null);
        $operator->enable_weekly_summary = ($enable_weekly_summary !== null) ? true : false;

        $enable_monthly_summary = $request->input('enable_monthly_summary', null);
        $operator->enable_monthly_summary = ($enable_monthly_summary !== null) ? true : false;

        if ($request->input('password', '') !== '') {
            $operator->password = bcrypt($request->input('password'));
        }

        $operator->save();

        return redirect(route('operator::show', $operator->id));
    }

    /**
     * Updates an existing operator with the submitted information
     *
     * @param Request $request
     * @param null $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $id = null) {
        $operator = User::find($id);
        $operator->name = $request->input('name');
        $operator->email = $request->input('email');
        $operator->company = $request->input('company', '');
        $operator->department = $request->input('department', '');
        $operator->home_phone = $request->input('home_phone', '');
        $operator->work_phone = $request->input('work_phone', '');
        $operator->mobile_phone = $request->input('mobile_phone', '');
        $operator->address = $request->input('address', '');
        $operator->notes = $request->input('notes', '');

        $enable_weekly_summary = $request->input('enable_weekly_summary', null);
        $operator->enable_weekly_summary = ($enable_weekly_summary !== null) ? true : false;

        $enable_monthly_summary = $request->input('enable_monthly_summary', null);
        $operator->enable_monthly_summary = ($enable_monthly_summary !== null) ? true : false;

        if ($request->input('password', '') !== '') {
            $operator->password = bcrypt($request->input('password'));
        }

        $operator->save();

        return redirect(route('operator::show', $id));
    }
}