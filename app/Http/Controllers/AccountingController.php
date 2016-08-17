<?php namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use App\RadiusAccount;

class AccountingController extends Controller {
    public function index(Request $request) {
        $columns = [
            'radacctid',
            'radacct.username',
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
            'username'        => $request->input('username', ''),
            'framedipaddress' => $request->input('framedipaddress', ''),
            'nasipaddress'    => $request->input('nasipaddress', ''),
            'acctstarttime'   => $request->input('acctstarttime', ''),
            'acctstoptime'    => $request->input('acctstoptime', '')
        ];
        $radiusAccounting = RadiusAccount::select($columns);

        foreach($filter as $key => $value) {
            if (!empty($value)) {
                if ($key === 'acctstarttime' || $key === 'acctstoptime') {
                    $dateTimeObject = new DateTime($value);
                    $queryOperator = ($key === 'acctstarttime') ? '>=' : '<=';

                    $radiusAccounting->where($key, $queryOperator, $dateTimeObject);
                } else {
                    $radiusAccounting->where($key, 'LIKE', '%' . $value . '%');
                }
            }
        }

        $dataSet = $radiusAccounting->paginate()->appends($filter);
        return view()->make('pages.accounting.index', ['accountingList' => $dataSet, 'filter' => $filter]);
    }
}