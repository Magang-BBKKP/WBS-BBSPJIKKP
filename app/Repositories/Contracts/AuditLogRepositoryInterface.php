<?php

namespace App\Repositories\Contracts;

interface AuditLogRepositoryInterface
{
    public function log(array $data): void;
}
