<?php

namespace Levelup\Framework\Persistence\DB;

abstract class Gateway
{
    protected $db;
    protected $qb;
    protected $table;

    public function __construct()
    {
        $this->db = ConnectionProvider::getConnection();
        $this->qb = $this->db->createQueryBuilder();
    }
}
