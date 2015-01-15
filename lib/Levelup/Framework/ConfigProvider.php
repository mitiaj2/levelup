<?php

namespace Levelup\Framework;

class ConfigProvider
{
    protected $configFile;
    protected $devSuffix;
    protected $config;

    public function __construct($configFile, $devSuffix = '_dev')
    {
        $this->configFile = $configFile;
        $this->devSuffix = $devSuffix;
        $this->loadConfig();
    }

    public function getConfig()
    {
        return $this->config;
    }

    protected function loadConfig()
    {
        if (getenv('APP_ENV') == 'dev') {
            $aFile = explode('.', $this->configFile);
            $devFile = '';

            for ($i = 0; $i < count($aFile); $i++) {
                if ($i == (count($aFile) - 1)) {
                    $devFile .= $this->devSuffix . '.' . $aFile[$i];

                } elseif ( 0 == $i) {
                    $devFile .= $aFile[$i];

                } else {
                    $devFile .= '.' . $aFile[$i];
                }
            }

            $this->config = include $devFile;

        } else {
            $this->config = include $this->configFile;
        }
    }
}
