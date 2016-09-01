<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class OperatorController extends Controller {
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

    public function show($id) {
        $operator = User::find($id);

        return view()->make(
            'pages.operator.show',
            [
                'operator' => $operator,
            ]
        );
    }

    public function edit(Request $request, $id) {
        $operator = User::find($id);

        return view()->make(
            'pages.operator.edit',
            [
                'operator' => $operator,
            ]
        );
    }

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