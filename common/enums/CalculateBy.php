<?php


namespace common\enums;

use TRS\Enum\Enum;

class CalculateBy extends Enum {
	const MILES = 'Miles';
	const PERCENT = 'Percent';
	const PER_DAY = 'Per Day';
	const PER_HOUR = 'Per Hour';
	const FUEL_MATRIX = 'Fuel Matrix';
}