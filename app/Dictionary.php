<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends Model {
    protected $table = 'radium_dictionary';
    public $timestamps = false;

    public $fillable = [
        'vendor',
        'attribute',
        'attribute_type',
        'values',
    ];

    public function getValueAttribute(){
        return json_decode($this->attributes['value']);
    }

    public function setValueAttribute($value) {
        $this->attributes['value'] = json_encode($value);
    }

    /**
     * Get a list of vendors and return them as an array
     *
     * @return array
     */
    public static function vendorList() {
        return array_flatten(
            self::select(['vendor'])
                ->groupBY('vendor')
                ->get()
                ->toArray()
        );
    }

    /**
     * Get a list of vendor attributes and return them as an array.
     *
     * @param $vendor
     * @return array
     */
    public static function vendorAttributes($vendor) {
        return array_flatten(
            self::select(['attribute'])
                ->where('vendor', $vendor)
                ->get()
                ->toArray()
        );
    }
}
