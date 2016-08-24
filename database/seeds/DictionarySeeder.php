<?php

use Illuminate\Database\Seeder;
use App\Dictionary;

class DictionarySeeder extends Seeder
{
    private $dictionary;
    private $vendor;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (Dictionary::count() > 0) {
            echo "Existing dictionary detected. Truncating radium_dictionary.\r\n";
            DB::statement("TRUNCATE TABLE  `radium_dictionary`");
        }

        Storage::put('dictionaryVersion.txt', md5(date('Y-m-d_g-i-s')));

        $exclude = explode("|", config('radium.exclude_dictionaries'));
        $files = Storage::disk('dictionary')->files();
        $dictionaries = [];

        foreach ($files as $file) {
            if (in_array($file, $exclude)) {
                echo "Excluding " . $file . "\r\n";
                continue;
            }

            if (strpos($file, "dictionary") === false) {
                echo "Skipping non dictionary file " . $file . ". If this is a mistake"
                    ." please make sure the filename has dictionary in it.\r\n";
                continue;
            }

            $this->dictionary = $file;
            $dictionary = $this->processDictionary($file);

            foreach($dictionary['vendors'] as $vendor) {
                if (array_key_exists($vendor, $dictionaries)) {
                    $dictionaries[$vendor] = array_merge($dictionary['attributes'][$vendor], $dictionaries[$vendor]);
                } else {
                    $dictionaries[$vendor] = $dictionary['attributes'][$vendor];
                }
            }
        }

        $count = 0;
        $fakeAttributes = 0;
        foreach ($dictionaries as $vendor => $dictionary) {
            foreach ($dictionary as $attribute => $values) {
                // @TODO Figure out a better solution....
                if (!array_key_exists('length', $values)) {
                    $values['length'] = 0;
                    $values['type'] = 'string';

                    $fakeAttributes++;
                }

                Dictionary::create([
                    'vendor'         => $vendor,
                    'attribute'      => $attribute,
                    'attribute_type' => $values['type'],
                    'length'         => $values['length'],
                    'values'         => $values['values'],
                ]);
                $count++;
            }
        }

        echo "Number of dictionaries: " . count($dictionaries) . "\r\n";
        echo "Number of created attributes: " . $count . "\r\n";
        echo "Number of value only attributes: " . $fakeAttributes . "\r\n";
        echo "Dictionary compilation finished.\r\n";
    }

    /**
     * Processes a dictionary value to return an array.
     * Response format:
     * [
     *     'vendors' => [],
     *     'attributes' => [
     *         'vendor' => [
     *             'attribute-name' => [
     *                 'length' => '',
     *                 'type'   => '',
     *                 'values' => [['value' => '', 'integer' => 0]],
     *             ]
     *         ]
     *     ]
     * ]
     *
     * @param $fileName
     * @return array
     */
    public function processDictionary($fileName) {
        $file = Storage::disk('dictionary')->get($fileName);
        $lines = explode("\n", $file);

        $patterns = [
            '^#',
            '^ #',
            '^\$INCLUDE',
            '^VENDOR',
            '^BEGIN-TLV',
            '^END-TLV',
        ];
        $match = '/' . implode("|", $patterns) . '/s';
        $defaultVendor = $this->getDictionaryVendorName($fileName);
        $this->vendor = $vendor = $defaultVendor;
        $vendors = [$vendor];
        $attributes = [
            $vendor => []
        ];

        foreach($lines as $line) {
            if (preg_match($match, $line) || empty($line)) {
                continue;
            }

            if (preg_match('/^BEGIN-VENDOR/s', $line)) {
                $splitLine = $this->splitLine($line);
                $vendor = strtolower(end($splitLine));

                if ($vendor !== $defaultVendor) {
                    $vendors[] = $vendor;
                    $attribute[$vendor] = [];
                }
                continue;
            }

            if (preg_match('/^END-VENDOR/s', $line)) {
                $this->vendor = $vendor = $defaultVendor;
                continue;
            }

            if (preg_match('/^ATTRIBUTE/s', $line)) {
                $attribute = $this->parseAttributeLine($line);
                $attributes[$vendor][$attribute['attribute']] = [
                    'length' => $attribute['length'],
                    'type'   => $attribute['type'],
                    'values' => [],
                ];
                continue;
            }

            if (preg_match('/^VALUE/s', $line)) {
                $value = $this->parseValueLine($line);
                $attributes[$vendor][$value['attribute']]['values'][] = [
                    'value'   => $value['value'],
                    'integer' => $value['integer']
                ];
            }
        }

        return [
            'vendors' => $vendors,
            'attributes' => $attributes,
        ];
    }

    private function getDictionaryVendorName($file) {
        $position = strpos($file, '.');

        if ($position !== false) {
            return substr($file, $position + 1);
        }

        return $file;
    }

    /**
     * Returns an array with attribute, length, type for the given attribute line
     *
     * @param $line
     * @return array
     */
    private function parseAttributeLine($line) {
        $values = $this->splitLine($line);
        $count = 0;
        $response = [
            'attribute' => '',
            'length'    => 0,
            'type'      => '',
        ];

        foreach($values as $value) {
            if ($value === 'ATTRIBUTE' || empty($value) && $value != '0') {
                continue;
            }

            switch($count) {
                case 0:
                    $response['attribute'] = $value;
                    $count++;
                    break;

                case 1:
                    $response['length'] = (int) $value;
                    $count++;
                    break;

                case 2:
                    $response['type'] = $value;
                    $count++;
                    break;
            }
        }

        if ($count >= 2 && empty($response['type'])) {
            echo "ERROR: Could not successfully parse attribute line!\r\n";
            echo "Dictionary: " . $this->dictionary . "\r\n";
            echo "Vendor: " . $this->vendor . "\r\n";
        }

        return $response;
    }

    /**
     * Returns an array with attribute, value, integer for the given value line
     *
     * @param $line
     * @return array
     */
    private function parseValueLine($line) {
        $values = $this->splitLine($line);
        $count = 0;
        $response = [
            'attribute' => '',
            'value'     => '',
            'integer'   => 0,
        ];

        foreach($values as $value) {
            if ($value === 'VALUE' || empty($value) && $value != '0') {
                continue;
            }

            switch($count) {
                case 0:
                    $response['attribute'] = $value;
                    $count++;
                    break;

                case 1:
                    $response['value'] = $value;
                    $count++;
                    break;

                case 2:
                    $response['integer'] = $value;
                    $count++;
                    break;
            }
        }

        if ($count >= 2 && empty($response['value'])) {
            echo "ERROR: Could not successfully parse value line!\r\n";
            echo "Dictionary: " . $this->dictionary . "\r\n";
            echo "Vendor: " . $this->vendor . "\r\n";
        }

        return $response;
    }

    /**
     * Splits a given line into an array
     *
     * @param $line
     * @return array
     */
    private function splitLine($line) {
        $line = str_replace(" ", "\t", $line);
        return explode("\t", $line);
    }
}
