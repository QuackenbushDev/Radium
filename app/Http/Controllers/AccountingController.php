<?php namespace App\Http\Controllers;

use App\RadiusAccount;
use Illuminate\Http\Request;

class AccountingController extends Controller {
    public function index(Request $request) {
        $columns = [
            'radacctid',
            'username',
            'acctsessiontime',
            'acctstarttime',
            'acctstoptime',
            'acctinputoctets',
            'acctoutputoctets',
            'acctterminatecause',
            'nasipaddress',
            'framedipaddress'
        ];
        $filter = [
            'username'  => $request->input('username', ''),
            'clientIP'  => $request->input('clientIP', ''),
            'nasIP'     => $request->input('nasIP', ''),
            'timeStart' => $request->input('dateStart', ''),
            'timeStop'  => $request->input('dateStop')
        ];
        $radiusAccounting = RadiusAccount::select($columns);

        if (!empty($filter['username'])) {
            $radiusAccounting->where('username', 'LIKE', '%' . $filter['username'] . '%');
        }

        $dataSet = $radiusAccounting->paginate()->appends($filter);
        return view()->make('pages.accounting.index', ['accountingList' => $dataSet, 'filter' => $filter]);
    }
}