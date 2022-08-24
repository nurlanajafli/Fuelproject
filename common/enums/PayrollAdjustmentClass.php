<?php


namespace common\enums;


use TRS\Enum\Enum;
use yii\base\InvalidArgumentException;

class PayrollAdjustmentClass extends Enum
{
    // LOCAL_INCOME_TAX
    const LOCAL_TAX = "Local Tax";
    const OTHER_TAX = "Other Tax";
    // NON_TAX_ADJUSTMENT
    const C_401K_EE = "401K EE";
    const C_401K_ER = "401K ER";
    const SECTION_125 = "Section 125";
    const HSA_EE = "HSA EE";
    const HSA_ER = "HSA ER";
    const NON_TAX = "Non TAX";

    public static function getByType($type)
    {
        if ($type == PayrollAdjustmentType::LOCAL_INCOME_TAX) {
            return [
                self::LOCAL_TAX => self::LOCAL_TAX,
                self::OTHER_TAX => self::OTHER_TAX
            ];
        }

        if ($type == PayrollAdjustmentType::NON_TAX_ADJUSTMENT) {
            return [
                self::C_401K_EE => self::C_401K_EE,
                self::C_401K_ER => self::C_401K_ER,
                self::SECTION_125 => self::SECTION_125,
                self::HSA_EE => self::HSA_EE,
                self::HSA_ER => self::HSA_ER,
                self::NON_TAX => self::NON_TAX,
            ];
        }

        throw new InvalidArgumentException();
    }
}