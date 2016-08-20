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

        if ($request->input('password', '') !== '') {
            $operator->password = bcrypt($request->input('password'));
        }

        $operator->save();

        return redirect(route('operator::show', $id));
    }
}