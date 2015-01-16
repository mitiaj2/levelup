<?php

namespace Levelup\Framework\Console;

use Symfony\Component\Console\Shell as BaseShell;


class Shell extends BaseShell
{
    /**
     * Returns the shell header.
     *
     * @return string The header string
     */
    protected function getHeader()
    {
        return <<<EOF
<info>
    __
    | |
    | |  ___  __  _
    | | / _ \| | | |
    | || (_) | |_| |
    |_| \___/ \__, |
               __/ |
              |___/
</info>
EOF
        .parent::getHeader();
    }
}