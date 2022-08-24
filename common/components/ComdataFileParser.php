<?php


namespace common\components;


class ComdataFileParser
{
    public static function getParsedData($filePath) {
        $result = [];
        $data = self::parse($filePath);
        foreach ($data as $row) {
            if ($row[1] == '01') {
                $result['items'][] = [
                    'card' => $row[195],
                    'date' => $row[12],
                    'time' => $row[74],
                    'trip' => trim($row[152]),
                    'driver' => trim($row[140]),
                    'driver_license' => $row[261],
//                'truck' => '',
                    'trailer' => trim($row[291]),
                    'unit' => trim($row[26]),
                    'fs_city' => $row[52],
                    'fs_st' => $row[64],
                    'transaction_id' => $row[18] . $row[21],
                    'fs_vendor' => $row[37],

                    'fuel_qty' => $row[345] / 100,
                    'fuel_ppg' => $row[350] / 1000,
                    'fuel_cost' => $row[355] / 100,

                    'tractor_fuel_qty' => $row[90] / 100,
                    'tractor_fuel_ppg' => $row[95] / 1000,
                    'tractor_fuel_cost' => $row[100] / 100,
                    'tractor_fuel_billing_flag' => self::getBillingFlag($row[126]),

                    'reefer_fuel_qty' => $row[105] / 100,
                    'reefer_fuel_ppg' => $row[110] / 1000,
                    'reefer_fuel_cost' => $row[115] / 100,
                    'reefer_fuel_billing_flag' => self::getBillingFlag($row[127]),

                    'other_fuel_qty' => $row[360] / 100,
                    'other_fuel_ppg' => $row[365] / 1000,
                    'other_fuel_cost' => $row[370] / 100,

                    'oil_qty' => (int) $row[120],
                    'oil_cost' => $row[122] / 100,
                    'oil_billing_flag' => self::getBillingFlag($row[128]),

                    'total_amount_due' => $row[78] / 100,

                    'prod_code_group_1' => $row[130] . $row[257],
                    'reefer_code' => $row[186],
                    'diesel_2_code' => $row[189],
                    'prod_code_group_2' => $row[192] . $row[322],
                    'prod_code_group_3' => $row[193] . $row[332],
                    'prod_group_code' => $row[223],
                    'prod_group_code_val' => self::getProductByCode($row[223]),
                    'prod_group_cost' => $row[224] / 100,
                    'prod_group_code_' => $row[231],
                    'prod_group_cost_' => $row[232] / 100,
                    'prod_group_code__' => $row[239],
                    'prod_group_cost__' => $row[240] / 100,
                ];

                $result['total_counted']['fuel_cost'] += $row[355] / 100;
                $result['total_counted']['tractor_fuel_cost'] += $row[100] / 100;
                $result['total_counted']['reefer_fuel_cost'] += $row[115] / 100;
                $result['total_counted']['other_fuel_cost'] += $row[370] / 100;
                $result['total_counted']['oil_cost'] += $row[122] / 100;
                $result['total_counted']['total_amount_due'] += $row[78] / 100;
            }
            if ($row[1] == '90') {
                $result['total_funded'] = [
                    'total_amount_due' => $row[18] / 100,
                    'total_fuel_charges' => $row[26] / 100,
                    'total_tractor_fuel_cost' => $row[44] / 100,
                    'total_reefer_fuel_cost' => $row[63] / 100,
                    'total_oil_cost' => $row[74] / 100,
                ];
                $result['total_non_funded'] = [
                    'total_amount_due' => $row[179] / 100,
                    'total_fuel_charges' => $row[187] / 100,
                    'total_tractor_fuel_cost' => $row[205] / 100,
                    'total_reefer_fuel_cost' => $row[224] / 100,
                    'total_oil_cost' => $row[235] / 100,
                ];
            }
        }

        return $result;
    }

    public static function parse($filePath)
    {
        $filesize = filesize($filePath);
        $fp = fopen($filePath, 'rb');
        $binary = fread($fp, $filesize);
        fclose($fp);

        $parseArr = self::comDataArr();
        $stringBroke = explode(PHP_EOL, $binary);

        $parsedData = [];
        foreach ($stringBroke as $k=>$item) {
            $beginOfString = substr($item,0,2);
            if($beginOfString == '00')
                $beginOfString = 0;
            if($beginOfString == '01')
                $beginOfString = 1;

            if(is_array($parseArr[$beginOfString]))
                $parsedData[] = self::parseData($parseArr[$beginOfString], $item);
        }

        // Download CSV file
        $savedRows = [];
        $delimiter = ";";
        $filename = "Parse_Data_" . date('Y-m-d') . ".csv";
        $f = fopen('php://memory', 'w');

        $currentRowType = null;
        foreach ($parsedData as $dataRow) {
            if ($currentRowType !== $dataRow[0]['VALUE']) {
                $currentRowType = $dataRow[0]['VALUE'];
                $csvHeaderRow = [];
                foreach($dataRow as $row) {
                    $csvHeaderRow[] = $row['DESCRIPTION']
                        . " (" . $row['COMMENT'] . ") FMT: " . $row['FMT']
                        . " From-To-Len: " . $row['FROM'] . "-" . $row['TO'] . "-" . $row['LEN'];
                }

                fputcsv($f, [], $delimiter);
                fputcsv($f, $csvHeaderRow, $delimiter);
            }

            $csvRow = [];
            foreach($dataRow as $row) {
                $csvRow[$row['FROM']] = $row['VALUE'];
            }
            fputcsv($f, $csvRow, $delimiter);
            $savedRows[] = $csvRow;
        }

        return $savedRows;

//        fseek($f, 0);
//        header('Content-Type: text/csv');
//        header('Content-Disposition: attachment; filename="' . $filename . '";');
//        fpassthru($f);
//        exit;
    }
    
    private static function parseData($parseArr, $data) {

        foreach($parseArr as $key => $parse) {
            $itemSub = substr($data,$parse['FROM']-1, $parse['LEN']);
            $parseArr[$key]['VALUE'] = $itemSub;
        }
        return $parseArr;
    }

    private static function getBillingFlag($str)
    {
        switch ($str) {
            case "F":
                return "Funded";
            case "D":
                return "Direct Bill";
            case "T":
                return "Terminal";
            default:
                return $str;
        }
    }

    private static function comDataArr() {

        $parseArr[00] = [
            [
                'FROM' => 1,
                'TO' => 2,
                'LEN' => 2,
                'FMT' => "C",
                'DESCRIPTION' => 'Record Identifier',
                'COMMENT' => 'Constant "00"'
            ],
            [
                'FROM' => 3,
                'TO' => 6,
                'LEN' => 4,
                'FMT' => 'C',
                'DESCRIPTION' => 'Reserved',
                'COMMENT' => ''
            ],
            [
                'FROM' => 7,
                'TO' => 11,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'Account Code',
                'COMMENT' => ''
            ],
            [
                'FROM' => 12,
                'TO' => 15,
                'LEN' => 4,
                'FMT' => 'C',
                'DESCRIPTION' => 'Reserved',
                'COMMENT' => ''
            ],
            [
                'FROM' => 16,
                'TO' => 21,
                'LEN' => 6,
                'FMT' => 'C',
                'DESCRIPTION' => 'YYMMDD',
                'COMMENT' => ''
            ],
        ];

        $parseArr[01] = [
            [
                'FROM' => 1,
                'TO' => 2,
                'LEN' => 2,
                'FMT' => "C",
                'DESCRIPTION' => 'Record Identifier',
                'COMMENT' => 'Constant "01"'
            ],
            [
                'FROM' => 3,
                'TO' => 7,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'CDN Company Accounting Code',
                'COMMENT' => 'CCNNN'
            ],
            [
                'FROM' => 8,
                'TO' => 11,
                'LEN' => 4,
                'FMT' => 'C',
                'DESCRIPTION' => 'Reserved',
                'COMMENT' => ''
            ],
            [
                'FROM' => 12,
                'TO' => 17,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Transaction Date ',
                'COMMENT' => 'YYMMDD'
            ],
            [
                'FROM' => 18,
                'TO' => 18,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Transaction Number Indicator',
                'COMMENT' => '0=less than 100,000 / 1=100,000 / 2 = 200,000'
            ],
            [
                'FROM' => 19,
                'TO' => 20,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Transaction Date',
                'COMMENT' => 'DD'
            ],
            [
                'FROM' => 21,
                'TO' => 25,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'Transaction Number',
                'COMMENT' => 'Right most 5 digits of transaction #'
            ],
            [
                'FROM' => 26,
                'TO' => 31,
                'LEN' => 6,
                'FMT' => 'C',
                'DESCRIPTION' => 'Unit Number',
                'COMMENT' => 'Right Justified'
            ],
            [
                'FROM' => 32,
                'TO' => 36,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'Truck Stop Code',
                'COMMENT' => 'ST###'
            ],
            [
                'FROM' => 37,
                'TO' => 51,
                'LEN' => 15,
                'FMT' => 'C',
                'DESCRIPTION' => 'Truck Stop Name',
                'COMMENT' => 'Left Justified'
            ],

            [
                'FROM' => 52,
                'TO' => 63,
                'LEN' => 12,
                'FMT' => 'C',
                'DESCRIPTION' => 'Truck Stop City',
                'COMMENT' => ''
            ],
            [
                'FROM' => 64,
                'TO' => 65,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Truck Stop State',
                'COMMENT' => ''
            ],
            [
                'FROM' => 66,
                'TO' => 73,
                'LEN' => 8,
                'FMT' => 'C',
                'DESCRIPTION' => 'Truck Stop Invoice Number',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 74,
                'TO' => 77,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Transaction Time',
                'COMMENT' => 'HHMM'
            ],
            [
                'FROM' => 78,
                'TO' => 83,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'TOTAL AMOUNT DUE',
                'COMMENT' => '9999v99'
            ],
            [
                'FROM' => 84,
                'TO' => 87,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'FEES FOR FUEL & OIL & PRODUCTS',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 88,
                'TO' => 88,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Cheaper Fuel Availability Flag',
                'COMMENT' => '*=Cheaper Fuel Available or blank'
            ],
            [
                'FROM' => 89,
                'TO' => 89,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Service Used',
                'COMMENT' => 'F=Full Service M=Mini Service S=Self Service B=Blended Fuel T=Terminal Fuel N=Not Applicable W=Wet Hose'
            ],
            [
                'FROM' => 90,
                'TO' => 94,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Number of Tractor Gallons',
                'COMMENT' => '999v99 Includes #2, #1 and other fuel'
            ],
            [
                'FROM' => 95,
                'TO' => 99,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Tractor Fuel Price Per Gallon',
                'COMMENT' => '99v999 Includes #2, #1 and other fuel'
            ],

            [
                'FROM' => 100,
                'TO' => 104,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Cost of Tractor Fuel',
                'COMMENT' => '999v99 Includes #2, #1 and other fuel'
            ],
            [
                'FROM' => 105,
                'TO' => 109,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Number of Reefer Gallons',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 110,
                'TO' => 114,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Reefer Price Per Gallon',
                'COMMENT' => '99v999'
            ],
            [
                'FROM' => 115,
                'TO' => 119,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Cost of Reefer Fuel',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 120,
                'TO' => 121,
                'LEN' => 2,
                'FMT' => 'N',
                'DESCRIPTION' => 'Number of Quarts of Oil',
                'COMMENT' => '99'
            ],
            [
                'FROM' => 122,
                'TO' => 125,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Cost of Oil',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 126,
                'TO' => 126,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Tractor Fuel Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal'
            ],
            [
                'FROM' => 127,
                'TO' => 127,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Reefer Fuel Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal'
            ],
            [
                'FROM' => 128,
                'TO' => 128,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Oil Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal'
            ],
            [
                'FROM' => 129,
                'TO' => 129,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Filler',
                'COMMENT' => 'Filler'
            ],

            [
                'FROM' => 130,
                'TO' => 130,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'First byte of Product Code Group 1(future) ',
                'COMMENT' => 'Append with bytes 257-258 for complete code'
            ],
            [
                'FROM' => 131,
                'TO' => 135,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Cash Advance Amount',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 136,
                'TO' => 139,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Charges for Cash Advance',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 140,
                'TO' => 151,
                'LEN' => 12,
                'FMT' => 'C',
                'DESCRIPTION' => 'Driver\'s Name',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 152,
                'TO' => 161,
                'LEN' => 10,
                'FMT' => 'C',
                'DESCRIPTION' => 'Trip Number',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 162,
                'TO' => 171,
                'LEN' => 10,
                'FMT' => 'N',
                'DESCRIPTION' => 'Conversion Rate',
                'COMMENT' => '99v99999999 or Spaces'
            ],
            [
                'FROM' => 172,
                'TO' => 177,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Hubometer Reading',
                'COMMENT' => '999999'
            ],
            [
                'FROM' => 178,
                'TO' => 181,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Year To Date MPG',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 182,
                'TO' => 185,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'MPG for this Fill Up',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 186,
                'TO' => 188,
                'LEN' => 3,
                'FMT' => 'C',
                'DESCRIPTION' => 'Reefer Fuel Product Group Code',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],

            [
                'FROM' => 189,
                'TO' => 191,
                'LEN' => 3,
                'FMT' => 'C',
                'DESCRIPTION' => 'Diesel #2 Fuel Product Group Code',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],
            [
                'FROM' => 192,
                'TO' => 192,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'First byte of Product Group Code 2 (future)',
                'COMMENT' => 'Append with bytes 322-323 for complete code'
            ],
            [
                'FROM' => 193,
                'TO' => 193,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'First byte of Product Group Code 3 (future)',
                'COMMENT' => 'Append with bytes 332-333 for complete code '
            ],
            [
                'FROM' => 194,
                'TO' => 194,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Billable Currency',
                'COMMENT' => 'U=US C=Canadian'
            ],
            [
                'FROM' => 195,
                'TO' => 204,
                'LEN' => 10,
                'FMT' => 'N',
                'DESCRIPTION' => 'Comchek Card Number',
                'COMMENT' => ''
            ],
            [
                'FROM' => 205,
                'TO' => 220,
                'LEN' => 16,
                'FMT' => 'C',
                'DESCRIPTION' => 'Employee Number',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 221,
                'TO' => 221,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Non-Funded Item',
                'COMMENT' => '* = Direct Bill Transaction or blank T = Terminal Fuel Transaction'
            ],
            [
                'FROM' => 222,
                'TO' => 222,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Not Limited Ntwk Location Flag',
                'COMMENT' => '* = Not in Limited Network'
            ],
            [
                'FROM' => 223,
                'TO' => 223,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Product Group Code',
                'COMMENT' => 'SEE KEY BELOW FOR TYPES ( or blank )'
            ],
            [
                'FROM' => 224,
                'TO' => 230,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Product Amount',
                'COMMENT' => '99999v99'
            ],

            [
                'FROM' => 231,
                'TO' => 231,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Product Group Code',
                'COMMENT' => 'SEE KEY BELOW FOR TYPES'
            ],
            [
                'FROM' => 232,
                'TO' => 238,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Product Amount',
                'COMMENT' => '99999v99'
            ],
            [
                'FROM' => 239,
                'TO' => 239,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Product Group Code',
                'COMMENT' => 'SEE KEY BELOW FOR TYPES'
            ],
            [
                'FROM' => 240,
                'TO' => 246,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Product Amount',
                'COMMENT' => '99999v99'
            ],
            [
                'FROM' => 247,
                'TO' => 251,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Alliance Select or Focus',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 252,
                'TO' => 252,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Alliance Location Flag',
                'COMMENT' => 'Y = Alliance Network Location'
            ],
            [
                'FROM' => 253,
                'TO' => 253,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Cash Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal or Blank'
            ],
            [
                'FROM' => 254,
                'TO' => 254,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Product Group 1 Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal or Blank'
            ],
            [
                'FROM' => 255,
                'TO' => 255,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Product Group 2 Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal or Blank'
            ],
            [
                'FROM' => 256,
                'TO' => 256,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Product Group 3 Billing Flag',
                'COMMENT' => 'F=Funded D=Direct Bill T=Terminal or Blank'
            ],

            [
                'FROM' => 257,
                'TO' => 258,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Last 2 byte of Product Group Code 1 (future)',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],
            [
                'FROM' => 259,
                'TO' => 260,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Driver\'s License State',
                'COMMENT' => ''
            ],
            [
                'FROM' => 261,
                'TO' => 280,
                'LEN' => 20,
                'FMT' => 'C',
                'DESCRIPTION' => 'Driver\'s License Number',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 281,
                'TO' => 290,
                'LEN' => 10,
                'FMT' => 'C',
                'DESCRIPTION' => 'Purchase Order Number',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 291,
                'TO' => 300,
                'LEN' => 10,
                'FMT' => 'C',
                'DESCRIPTION' => 'Trailer Number',
                'COMMENT' => 'Left Justified'
            ],
            [
                'FROM' => 301,
                'TO' => 306,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Previous Hub Reading',
                'COMMENT' => ''
            ],
            [
                'FROM' => 307,
                'TO' => 307,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Cancel Flag',
                'COMMENT' => 'Y=Yes N=No if "yes" headers are C1,C2,C3'
            ],
            [
                'FROM' => 308,
                'TO' => 313,
                'LEN' => 6,
                'FMT' => 'C',
                'DESCRIPTION' => 'Date of Original Transaction',
                'COMMENT' => ''
            ],
            [
                'FROM' => 314,
                'TO' => 318,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'Service Center Chain Code',
                'COMMENT' => ''
            ],
            [
                'FROM' => 319,
                'TO' => 321,
                'LEN' => 3,
                'FMT' => 'C',
                'DESCRIPTION' => 'Diesel #1 Fuel Product Code',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],

            [
                'FROM' => 322,
                'TO' => 323,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Last 2 byte of Product Group Code 2 (future)',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],
            [
                'FROM' => 324,
                'TO' => 328,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'Fuel Code/Cust ID',
                'COMMENT' => '4 Digit Cust ID [Has Leading Zero]'
            ],
            [
                'FROM' => 329,
                'TO' => 331,
                'LEN' => 3,
                'FMT' => 'C',
                'DESCRIPTION' => 'Other Fuel Product Group Code',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],
            [
                'FROM' => 332,
                'TO' => 333,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Last 2 byte of Product Group Code 3 (future)',
                'COMMENT' => 'See Comdata three byte Product Code Table'
            ],
            [
                'FROM' => 334,
                'TO' => 334,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Rebate Indicator',
                'COMMENT' => 'R=Rebate N=Net'
            ],
            [
                'FROM' => 335,
                'TO' => 341,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Trailer Hub Reading',
                'COMMENT' => '999999.9'
            ],
            [
                'FROM' => 342,
                'TO' => 342,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Automated Transaction',
                'COMMENT' => 'Y=Yes N=No E=RFID with driver id prompting R=RFID with no driver id prompting'
            ],
            [
                'FROM' => 343,
                'TO' => 343,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Bulk Fuel Flag',
                'COMMENT' => 'Y=Yes N=No'
            ],
            [
                'FROM' => 344,
                'TO' => 344,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Service Center Bridge Transaction',
                'COMMENT' => 'Y=Yes N=No'
            ],
            [
                'FROM' => 345,
                'TO' => 349,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Number 1Fuel-Gallons',
                'COMMENT' => '999v99'
            ],

            [
                'FROM' => 350,
                'TO' => 354,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Number 1Fuel-PPG',
                'COMMENT' => '99v999'
            ],
            [
                'FROM' => 355,
                'TO' => 359,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Number 1 Fuel-Cost',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 360,
                'TO' => 364,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Other Fuel-Gallons',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 365,
                'TO' => 369,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Other Fuel-PPG',
                'COMMENT' => '99v999'
            ],
            [
                'FROM' => 370,
                'TO' => 374,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Other Fuel-Cost',
                'COMMENT' => '999v99'
            ],
            [
                'FROM' => 375,
                'TO' => 375,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Focus or Select Discount',
                'COMMENT' => 'F=Focus S=Select or Blank'
            ],
            [
                'FROM' => 376,
                'TO' => 379,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Canadian Tax Amount (Canadian Dollars)',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 380,
                'TO' => 383,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Canadian Tax Amount (US Dollars)',
                'COMMENT' => '99v99'
            ],
            [
                'FROM' => 384,
                'TO' => 384,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Canadian Tax Paid Flag',
                'COMMENT' => 'Y=Yes N=No'
            ],
        ];

        $parseArr[90] = [
            [
                'FROM' => 1,
                'TO' => 2,
                'LEN' => 2,
                'FMT' => "C",
                'DESCRIPTION' => 'Record Identifier',
                'COMMENT' => 'Constant "90"'
            ],
            [
                'FROM' => 3,
                'TO' => 7,
                'LEN' => 5,
                'FMT' => 'C',
                'DESCRIPTION' => 'CDN Company Accounting Code',
                'COMMENT' => 'CCNNN'
            ],
            [
                'FROM' => 8,
                'TO' => 11,
                'LEN' => 4,
                'FMT' => 'C',
                'DESCRIPTION' => 'Disregard - Old Cust Id',
                'COMMENT' => 'See 324-333 of Detail for complete number'
            ],
            [
                'FROM' => 12,
                'TO' => 17,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Data Creation Date',
                'COMMENT' => 'YYMMDD'
            ],
            [
                'FROM' => 18,
                'TO' => 25,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Amount Due ***',
                'COMMENT' => '999999v99 "Total Due Funded Items"'
            ],
            [
                'FROM' => 26,
                'TO' => 31,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Fuel Charges',
                'COMMENT' => '9999v99 "Funded Item"'
            ],
            [
                'FROM' => 32,
                'TO' => 38,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Number of Tractor Gallons',
                'COMMENT' => '99999v99 "Funded Item"'
            ],
            [
                'FROM' => 39,
                'TO' => 43,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Average Tractor Price Per Gallon',
                'COMMENT' => '99v999 "Funded Item"'
            ],
            [
                'FROM' => 44,
                'TO' => 50,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Tractor Fuel Cost',
                'COMMENT' => '99999v99 "Funded Item"'
            ],
            [
                'FROM' => 51,
                'TO' => 57,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Number of Reefer Gallons',
                'COMMENT' => '99999v99 "Funded Item"'
            ],
            [
                'FROM' => 58,
                'TO' => 62,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Average Reefer Price Per Gallon',
                'COMMENT' => '99v99 "Funded Item"'
            ],
            [
                'FROM' => 63,
                'TO' => 69,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Reefer Fuel Cost',
                'COMMENT' => '99999v99 "Funded Item"'
            ],
            [
                'FROM' => 70,
                'TO' => 73,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Number of Quarts of Oil',
                'COMMENT' => '9999 "Funded Item"'
            ],
            [
                'FROM' => 74,
                'TO' => 79,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Cost of Oil',
                'COMMENT' => '9999v99 "Funded Item"'
            ],
            [
                'FROM' => 80,
                'TO' => 86,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Cash Advance',
                'COMMENT' => '99999v99 "Funded Item"'
            ],
            [
                'FROM' => 87,
                'TO' => 92,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Charges for Cash Advance',
                'COMMENT' => '9999v99 "Funded Item"'
            ],
            [
                'FROM' => 93,
                'TO' => 100,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 0',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 101,
                'TO' => 108,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 1',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 109,
                'TO' => 116,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 2',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 117,
                'TO' => 124,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 3',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 125,
                'TO' => 125,
                'LEN' => 1,
                'FMT' => 'C',
                'DESCRIPTION' => 'Billing Currency',
                'COMMENT' => 'U=US C=Canadian'
            ],
            [
                'FROM' => 126,
                'TO' => 128,
                'LEN' => 3,
                'FMT' => 'C',
                'DESCRIPTION' => 'Filler',
                'COMMENT' => ''
            ],
            [
                'FROM' => 129,
                'TO' => 130,
                'LEN' => 2,
                'FMT' => 'N',
                'DESCRIPTION' => 'Header Identifer',
                'COMMENT' => 'Constant "91"'
            ],
            [
                'FROM' => 131,
                'TO' => 138,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 4',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 139,
                'TO' => 146,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 5',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 147,
                'TO' => 154,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 6',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 155,
                'TO' => 162,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 7',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 163,
                'TO' => 170,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 8',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 171,
                'TO' => 178,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 9',
                'COMMENT' => '999999v99 "Funded Item"'
            ],
            [
                'FROM' => 179,
                'TO' => 186,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Amount Due',
                'COMMENT' => '999999v99  "Total Due Non-Funded Items"'
            ],
            [
                'FROM' => 187,
                'TO' => 192,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Fuel Charges ***',
                'COMMENT' => '9999v99 "Total Non-Funded Fuel Charges"'
            ],
            [
                'FROM' => 193,
                'TO' => 199,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Number of Tractor Gallons',
                'COMMENT' => '99999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 200,
                'TO' => 204,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Average Tractor Price Per Gallon',
                'COMMENT' => '99v999 "Non-Funded Item"'
            ],
            [
                'FROM' => 205,
                'TO' => 211,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Tractor Fuel Cost',
                'COMMENT' => '99999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 212,
                'TO' => 218,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Number of Reefer Gallons',
                'COMMENT' => '99999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 219,
                'TO' => 223,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Average Reefer Price Per Gallon',
                'COMMENT' => '99v999 "Non-Funded Item"'
            ],
            [
                'FROM' => 224,
                'TO' => 230,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Reefer Fuel Cost',
                'COMMENT' => '99999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 224,
                'TO' => 234,
                'LEN' => 11,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Number of Quarts of Oil',
                'COMMENT' => '9999 "Non-Funded Item"'
            ],
            [
                'FROM' => 235,
                'TO' => 240,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Cost Of Oil',
                'COMMENT' => '9999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 241,
                'TO' => 247,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Cash Advance Amount',
                'COMMENT' => '99999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 248,
                'TO' => 253,
                'LEN' => 6,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total N/B Cash Charges ***',
                'COMMENT' => '9999v99 "Total Cash Chgs Non-Funded"'
            ],
            [
                'FROM' => 254,
                'TO' => 256,
                'LEN' => 3,
                'FMT' => 'C',
                'DESCRIPTION' => 'Filler',
                'COMMENT' => ''
            ],
            [
                'FROM' => 257,
                'TO' => 258,
                'LEN' => 2,
                'FMT' => 'C',
                'DESCRIPTION' => 'Header Identifier',
                'COMMENT' => 'Constant "92"'
            ],
            [
                'FROM' => 259,
                'TO' => 266,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 0',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 267,
                'TO' => 274,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 1',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 275,
                'TO' => 282,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 2',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 283,
                'TO' => 290,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 3',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 291,
                'TO' => 298,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 4',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 299,
                'TO' => 306,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 5',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 307,
                'TO' => 314,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 6',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 315,
                'TO' => 322,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 7',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 323,
                'TO' => 330,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 8',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 331,
                'TO' => 338,
                'LEN' => 8,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Product Code 0',
                'COMMENT' => '999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 339,
                'TO' => 347,
                'LEN' => 9,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Select Discount',
                'COMMENT' => '9999999v99 "Non-Funded Item"'
            ],
            [
                'FROM' => 348,
                'TO' => 354,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total #1 Gallons',
                'COMMENT' => '99999v99 Included in #2 Fuel'
            ],
            [
                'FROM' => 355,
                'TO' => 359,
                'LEN' => 5,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Average #1 PPG',
                'COMMENT' => '99v999'
            ],
            [
                'FROM' => 360,
                'TO' => 366,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total #1 Cost',
                'COMMENT' => '99999v99'
            ],
            [
                'FROM' => 367,
                'TO' => 373,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Other Gallons',
                'COMMENT' => '99999v99 Included in #2 Fuel'
            ],
            [
                'FROM' => 374,
                'TO' => 377,
                'LEN' => 4,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Average Other PPG',
                'COMMENT' => '9v999'
            ],
            [
                'FROM' => 378,
                'TO' => 384,
                'LEN' => 7,
                'FMT' => 'N',
                'DESCRIPTION' => 'Total Other Cost',
                'COMMENT' => '99999v99'
            ],
        ];

        return $parseArr;
    }

    private static function getProductByCode($str)
    {
        $res = "";
        if ($str)
            $res = self::oneByteProductCodes()[$str];
        return $res;
    }

    private static function oneByteProductCodes()
    {
        return [
            "0" => "Additives",
            "1" => "Tire Repair",
            "2" => "Emergency Repair",
            "3" => "Lubricants",
            "4" => "Tire Purchase",
            "5" => "Driver Expense",
            "6" => "Truck Repair",
            "7" => "Parts",
            "8" => "Trailer Expense",
            "9" => "Miscellaneous",
            "A" => "Truck Wash", // 10
            "B" => "Scales", // 11
            "C" => "Parking Service", // 12
            "D" => "Hotel", // 13
            "E" => "Regulatory", // 14
            "F" => "Labor", // 15
            "G" => "Groceries", // 16
            "H" => "Shower", // 17
            "I" => "Trip Scan", // 18
            "J" => "Tolls", // 19
            "K" => "Aviation", // 20
            "L" => "Vehicle Maintenance", // 21
            "M" => "Aviation Maintenance", // 22
            "N" => "Setup Fees", // 23
            "O" => "DSL Exhaust Fuel", // 24
            "P" => "Wiper Fluid", // 25
            "Q" => "Transaction Fee", // 26
            "R" => "Training", // 27
            "S" => "Trailer Parking", // 28
            "T" => "Retail Lubricants", // 29
            "U" => "Solvent", // 30
            "V" => "Merch Surcharge", // 31
            "W" => "Anti-Frz / Coolant", // 32
            "X" => "Bottled Propane", // 33
            "Y" => "Load Locks", // 34
        ];
    }

    public static function getProdDescByCode($code)
    {
        $map = [
            "0" => "Adtv",
            "1" => "TirRep",
            "2" => "EmrRep",
            "3" => "Lub",
            "4" => "TirPur",
            "5" => "DrvExp",
            "6" => "TrkRep",
            "7" => "Parts",
            "8" => "TrlExp",
            "9" => "Misc",
            "A" => "TrkWash", // 10
            "B" => "Scales",
            "C" => "PrkSvc", // 12
            "D" => "Hotel", // 13
            "E" => "Regulatory", // 14
            "F" => "Labor", // 15
            "G" => "Groceries", // 16
            "H" => "Shower", // 17
            "I" => "TripScan", // 18
            "J" => "Tolls", // 19
            "K" => "Aviation", // 20
            "L" => "VehMaint", // 21
            "M" => "AvMaint", // 22
            // "N" => "Setup Fees", // 23
            // "O" => "DSL Exhaust Fuel", // 24
            "P" => "WiperFl", // 25
            "Q" => "TranFee", // 26
            // "R" => "Training", // 27
            "S" => "PrkSvc", // 28
            // "T" => "Retail Lubricants", // 29
            // "U" => "Solvent", // 30
            // "V" => "Merch Surcharge", // 31
            "W" => "AntiFreeze", // 32
            // "X" => "Bottled Propane", // 33
            // "Y" => "Load Locks", // 34
        ];

        return $map[$code] ?? null;
    }
}