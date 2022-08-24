<?php
/**
 * Date: 8/24/16
 * Time: 4:04 PM
 */

namespace common\enums;


use TRS\Enum\Enum;

class GrantType extends Enum
{
    const CLIENT_CREDENTIALS = 'client_credentials';
    const PASSWORD           = 'password';
    const REFRESH_TOKEN      = 'refresh_token';
}