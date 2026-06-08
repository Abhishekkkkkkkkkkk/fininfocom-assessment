<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Logger extends BaseConfig
{
    public $threshold = 4;
    public $path      = WRITEPATH . 'logs/';
    public $handlers  = [
        'CodeIgniter\Log\Handlers\FileHandler' => [
            'handles' => ['critical', 'alert', 'emergency', 'error', 'debug', 'info', 'notice'],
        ],
    ];
}
