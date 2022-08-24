<?php

namespace common\models;

use common\enums\LoadRateMethod;
use common\enums\LoadRateType;
use common\models\base\LoadRatingMatrix as BaseLoadRatingMatrix;
use http\Exception\InvalidArgumentException;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "load_rating_matrix".
 */
class LoadRatingMatrix extends BaseLoadRatingMatrix
{
    public function getColumns()
    {
        switch ($this->rate_method) {
            case LoadRateMethod::ZIP_ZIP:
                return LoadRatingZipZip::getColumns($this->rate_type);
            case LoadRateMethod::ZONE_ZONE:
                return LoadRatingZoneZone::getColumns($this->rate_type);
            case LoadRateMethod::GEOGRAPH:
                return LoadRatingGeograph::getColumns($this->rate_type);
            case LoadRateMethod::DISTANCE:
                return LoadRatingDistance::getColumns($this->rate_type);
            default:
                throw new InvalidArgumentException();
        }
    }

    public function calculate(Load $load)
    {
        $result = [
            'rate' => 0,
            'bill_miles' => []
        ];
        switch ($this->rate_method) {
            case LoadRateMethod::ZIP_ZIP:
                $f = function ($model, $i) use ($load) {
                    return ($load->loadStops[$i]->zip >= $model->zip_1_start) && ($load->loadStops[$i]->zip <= $model->zip_1_end) &&
                        ($load->loadStops[$i + 1]->zip >= $model->zip_2_start) && ($load->loadStops[$i + 1]->zip <= $model->zip_2_end);
                };
                $q = $this->getLoadRatingZipzips();
                break;
            case LoadRateMethod::ZONE_ZONE:
                $f = function ($model, $i) use ($load) {
                    return ($load->loadStops[$i]->zone == $model->zone_1) && ($load->loadStops[$i + 1]->zone == $model->zone_2);
                };
                $q = $this->getLoadRatingZonezones();
                break;
            case LoadRateMethod::GEOGRAPH:
                $f = function ($model, $i) use ($load) {
                    return ($load->loadStops[$i]->state_id == $model->origin_state) && ($load->loadStops[$i + 1]->state_id == $model->dest_state)
                        && (empty($model->origin_city) || ($load->loadStops[$i]->city == $model->origin_city)) && (empty($model->dest_city) || ($load->loadStops[$i + 1]->city == $model->dest_city));
                };
                $q = $this->getLoadRatingGeographs();
                break;
            case LoadRateMethod::DISTANCE:
                $f = function ($model, $i) use ($load) {
                    return ($load->loadStops[$i]->miles_to_next >= $model->low_miles) && ($load->loadStops[$i]->miles_to_next <= $model->high_miles);
                };
                $q = $this->getLoadRatingDistances();
                break;
            default:
                return $result;
        }
        $rules = $q->orderBy(['created_at' => SORT_ASC])->all();
        for ($i = 0, $j = count($load->loadStops) - 1; $i < $j; $i++) {
            $matchedRules = array_filter($rules, function ($rule) use ($f, $i) {
                return $f($rule, $i);
            });
            if (!$matchedRules) {
                continue;
            }
            $cwt = $load->commodity_weight / 100;
            if ($this->rate_method == LoadRateMethod::DISTANCE) {
                $minimumRate = 0;
                $t = 0;
                foreach ($matchedRules as $rule) {
                    if ($rule->low_miles == 0) {
                        $minimumRate = $rule->rate;
                        continue;
                    }
                    if ($t == 0) {
                        $t = $load->loadStops[$i]->miles_to_next * $rule->rate;
                    }
                }
                $result['rate'] += max($t, $minimumRate);
                continue;
            }
            if ($this->rate_type == LoadRateType::FLAT) {
                $array = array_values($matchedRules);
                $firstElement = array_shift($array);
                $result['rate'] += $firstElement->rate;
                if ($firstElement->bill_miles) {
                    $result['bill_miles'][$load->loadStops[$i]->id] = $firstElement->bill_miles;
                }
                continue;
            }
            if ($this->rate_type == LoadRateType::MILES) {
                foreach ($matchedRules as $rule) {
                    if (ArrayHelper::isIn($rule->trl_type, [$load->trailer_type, null, 'NT'])) {
                        $billMiles = $load->loadStops[$i]->miles_to_next;
                        if ($rule->bill_miles) {
                            $billMiles = $rule->bill_miles;
                            $result['bill_miles'][$load->loadStops[$i]->id] = $rule->bill_miles;
                        }
                        if (!is_null($rule->base_rate)) {
                            $result['rate'] += max($rule->base_rate + $rule->rate * $billMiles, $rule->min + 0);
                        } elseif (!is_null($rule->base_miles)) {
                            $result['rate'] += max(max($billMiles - $rule->base_miles, 0) * $rule->rate, $rule->min + 0);
                        } else {
                            $result['rate'] += max($billMiles * $rule->rate, $rule->min + 0);
                        }
                        break;
                    }
                }
                continue;
            }
            if (($this->rate_type == LoadRateType::CWT) && ($this->rate_method == LoadRateMethod::ZIP_ZIP || $this->rate_method == LoadRateMethod::ZONE_ZONE)) {
                $minimumRate = $maximumRate = 0;
                $min = 99999;
                $max = 0;
                $f = false;
                foreach ($matchedRules as $rule) {
                    if (($rule->low_wgt == 0) && ($rule->high_wgt == 0)) {
                        $minimumRate = $rule->rate;
                        continue;
                    }
                    if ($rule->high_wgt == 99999) {
                        $maximumRate = $rule->rate;
                        continue;
                    }
                    $f = true;
                    if ($rule->low_wgt < $min) {
                        $min = $rule->low_wgt;
                    }
                    if ($rule->high_wgt > $max) {
                        $max = $rule->high_wgt;
                    }
                    if (($load->commodity_weight >= $rule->low_wgt) && ($load->commodity_weight <= $rule->high_wgt)) {
                        $result['rate'] += $cwt * $rule->rate;
                        continue 2;
                    }
                }
                if ($f) {
                    if ($load->commodity_weight < $min) {
                        $result['rate'] += $cwt * $minimumRate;
                    } elseif ($load->commodity_weight > $max) {
                        $result['rate'] += $cwt * $maximumRate;
                    }
                }
                continue;
            }
            if ($this->rate_type == LoadRateType::CWT && $this->rate_method == LoadRateMethod::GEOGRAPH) {
                $result['rate'] += max($cwt * $matchedRules[0]->rate, $matchedRules[0]->min);
                continue;
            }
            if ($this->rate_type == LoadRateType::PIECE) {
                $result['rate'] += $load->commodity_pieces * $matchedRules[0]->rate;
                continue;
            }
            if ($this->rate_type == LoadRateType::SPACE) {
                $result['rate'] += $load->commodity_space * $matchedRules[0]->rate;
                continue;
            }
            if ($this->rate_type == LoadRateType::POUND) {
                $result['rate'] += $load->commodity_weight * $matchedRules[0]->rate;
                continue;
            }
            if ($this->rate_type == LoadRateType::TON) {
                $result['rate'] += ($load->commodity_weight / 2000) * $matchedRules[0]->rate;
                continue;
            }
            if ($this->rate_type == LoadRateType::LOT && $matchedRules[0]->pieces != 0) {
                $result['rate'] += $load->commodity_pieces * $matchedRules[0]->rate / $matchedRules[0]->pieces;
                continue;
            }
            if ($this->rate_type == LoadRateType::STEP) {
                $rates = [0];
                foreach ($matchedRules as $rule) {
                    if (($load->commodity_pieces <= $rule->max_pcs) && ($load->commodity_space <= $rule->max_space) && ($load->commodity_weight <= $rule->max_wgt)) {
                        $rates[] = $rule->rate;
                    }
                }
                $result['rate'] += min($rates);
            }
        }
        return $result;
    }
}
