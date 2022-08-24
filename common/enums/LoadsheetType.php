<?php


namespace common\enums;
use TRS\Enum\Enum;

class LoadsheetType extends Enum
{
    //Revenue
    const SHOW_ALL_REVENUE = 'Show All Revenue';
    const HIDE_ALL_REVENUE = 'Hide All Revenue';
    const HIDE_ACROSSORIAL_REVENUE = 'Hide Acrossorial Revenue';
    //Directions
    const SHOW_DIRECTIONS = 'Show Directions';
    const HIDE_DIRECTIONS = 'Hide Directions';
    //Stop notes
    const SHOW_STOP_NOTES = 'Show Stop Notes';
    const HIDE_STOP_NOTES = 'Hide Stop Notes';
}