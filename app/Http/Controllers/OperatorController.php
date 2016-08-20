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
        return view()->make(
            'pages.operator.show',
            [

            ]
        );
    }

    public function edit($id) {
        return view()->make(
            'pages.operator.edit',
            [

            ]
        );
    }

    public function create() {
        return view()->make(
            'pages.operator.edit',
            [

            ]
        );
    }

    public function save(Request $request) {
    }

    public function store(Request $request, $id = null) {
    }
}