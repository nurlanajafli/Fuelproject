<?php

namespace common\models;

use common\enums\FuelCardDataProvider as FCP;
use Yii;
use \common\models\base\FuelcardAccountConfig as BaseFuelcardAccountConfig;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "fuelcard_account_config".
 */
class FuelcardAccountConfig extends BaseFuelcardAccountConfig
{
    const ACCOUNT = 'Account_ID';
    const USERNAME = 'Username';
    const PASSWORD = 'Password';

    const NT_SIGNON = 'NT_Signon';
    const NT_PASSWORD = 'NT_Password';

    const RT_ACCOUNT_CODE = 'Account_Code';
    const RT_CUSTOMER_ID = 'Customer_ID';
    const RT_PASSWORD = 'Customer_Password';
    const RT_SECURITY_INFO = 'Security_Info';
    const RT_SIGNON_NAME = 'Sign-On_Name';

    private $configTypes = [FCP::TCH, FCP::COMDATA, FCP::COMDATA_MC, FCP::TCH_CHECK];
    private $basicConfigFields = [
        self::ACCOUNT, self::USERNAME, self::PASSWORD
    ];
    private $comdataConfigFields = [
        self::ACCOUNT, self::USERNAME, self::PASSWORD,
        self::NT_SIGNON, self::NT_PASSWORD,
        self::RT_ACCOUNT_CODE, self::RT_CUSTOMER_ID, self::RT_PASSWORD, self::RT_SECURITY_INFO, self::RT_SIGNON_NAME
    ];

    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['type', 'in', 'range' => $this->configTypes],
                ['config', 'validateConfigAttributes'],
            ]
        );
    }

    public function loadConfig($type, $array) {
        $configFields = $this->getConfigFields($type);
        $configData = [];
        foreach ($configFields as $field) {
            $configData[$field] = $array[$field];
        }
        $this->config = $configData;
    }

    public function validateConfigAttributes($attribute, $params, $validator)
    {
        $configFields = $this->getConfigFields($this->type);

        foreach ($configFields as $field) {
            if (empty($this->$attribute[$field])) {
                $this->addError($attribute, Yii::t('app', 'Field {field} is required', ['field' => $field]));
            }
        }
    }

    private function getConfigFields($type)
    {
        switch ($type) {
            case FCP::TCH:
            case FCP::COMDATA_MC:
            case FCP::TCH_CHECK:
                return $this->basicConfigFields;
            case FCP::COMDATA:
                return $this->comdataConfigFields;
        }
    }
}
