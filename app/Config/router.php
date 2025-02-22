<?php

use App\Modules\Auth\AuthConfig;
use App\Modules\Users\UserConfig;

return [
    UserConfig::router(),
    AuthConfig::router(),
];