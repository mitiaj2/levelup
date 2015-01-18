<?php

namespace Levelup\Framework\Persistence\DB;

abstract class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = ConnectionProvider::getConnection();
    }
}
