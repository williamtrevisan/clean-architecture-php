<?php

declare(strict_types=1);

namespace App\Repositories\Transaction;

use Core\UseCase\Interface\DatabaseTransactionInterface;
use Illuminate\Support\Facades\DB;

class DatabaseTransaction implements DatabaseTransactionInterface
{
    public function __construct()
    {
        DB::beginTransaction();
    }

    public function commit(): void
    {
        DB::commit();
    }

    public function rollBack(): void
    {
        DB::rollBack();
    }
}
