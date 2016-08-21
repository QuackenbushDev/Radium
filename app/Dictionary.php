<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model {
    protected $table = 'dictionary';
    public $timestamps = false;

    public $fillable = [
        'Type',
        'Attribute',
        'Value',
        'Format',
        'Vendor',
        'RecommendedOP',
        'RecommendedTable',
        'RecommendedHelper',
        'RecommendedTooltip'
    ];

    public static function vendorList() {
        return array_flatten(
            self::select(['vendor'])
                ->groupBY('Vendor')
                ->get()
                ->toArray()
        );
    }

    public static function vendorAttributes($vendor) {
        return array_flatten(
            self::select(['Attribute'])
                ->where('Vendor', $vendor)
                ->get()
                ->toArray()
        );
    }
}
