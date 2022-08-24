<?php

use yii\db\Migration;
use common\models\State;
use common\enums\Region;

/**
 * Class m201119_132708_fill_state
 */
class m201119_132708_fill_state extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $USStates = [
            'AL' => 'Alabama',
            'AK' => 'Alaska',
            'AZ' => 'Arizona',
            'AR' => 'Arkansas',
            'CA' => 'California',
            'CO' => 'Colorado',
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'HI' => 'Hawaii',
            'ID' => 'Idaho',
            'IL' => 'Illinois',
            'IN' => 'Indiana',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'KY' => 'Kentucky',
            'LA' => 'Louisiana',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'MT' => 'Montana',
            'NE' => 'Nebraska',
            'NV' => 'Nevada',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NM' => 'New Mexico',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'ND' => 'North Dakota',
            'OH' => 'Ohio',
            'OK' => 'Oklahoma',
            'OR' => 'Oregon',
            'PA' => 'Pennsylvania',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'UT' => 'Utah',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WA' => 'Washington',
            'WV' => 'West Virginia',
            'WI' => 'Wisconsin',
            'WY' => 'Wyoming',
        ];
        foreach ($USStates as $stateCode => $state) {
            $model = new State();
            $model->state_code = $stateCode;
            $model->state = $state;
            $model->country_code = 'US';
            $model->country = 'United States of America';
            $model->region = Region::US;
            $model->save();
        }

        $CAProvincesAndTerritories = [
            // provinces
            'ON' => 'Ontario',
            'QC' => 'Quebec',
            'NS' => 'Nova Scotia',
            'NB' => 'New Brunswick',
            'MB' => 'Manitoba',
            'BC' => 'British Columbia',
            'PE' => 'Prince Edward Island',
            'SK' => 'Saskatchewan',
            'AB' => 'Alberta',
            'NL' => 'Newfoundland and Labrador',
            // territories
            'NT' => 'Northwest Territories',
            'YT' => 'Yukon',
            'NU' => 'Nunavut',
        ];
        foreach ($CAProvincesAndTerritories as $code => $name) {
            $model = new State();
            $model->state_code = $code;
            $model->state = $name;
            $model->country_code = 'CA';
            $model->country = 'Canada';
            $model->region = Region::CANADA;
            $model->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        State::deleteAll(['region' => [Region::US, Region::CANADA]]);
    }

}
