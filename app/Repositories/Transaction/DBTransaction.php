<?php


namespace App\Repositories\Transaction;


use Core\UseCase\Interface\TransactionInterface;
use Illuminate\Support\Facades\DB;

class DBTransaction implements TransactionInterface
{

    public function __construct()
    {
        DB::beginTransaction();
    }
    public function commit(): void
    {
        DB::commit();
    }

    public function rollback(): void
    {
        DB::rollBack();
    }
}
