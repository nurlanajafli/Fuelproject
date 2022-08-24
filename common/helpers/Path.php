<?php

namespace common\helpers;

class Path
{
    public static function join()
    {
        return preg_replace('/[\/]{2,}/', '/', implode(DIRECTORY_SEPARATOR, func_get_args()));
    }
}
