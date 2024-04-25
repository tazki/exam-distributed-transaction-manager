<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Controllers\TransactionController;

class TransactionTest extends TestCase
{
    public function testStartTransaction()
    {
        $request = new Request();
        $dtm = new TransactionController();
        $dtm->startTransaction($request);

        $this->assertTrue(true);
    }

    public function testCommitTransaction()
    {
        $id = 1;
        $request = new Request();
        $dtm = new TransactionController();
        $dtm->commitTransaction($request, $id);

        $this->assertTrue(true);
    }

    public function testRollbackTransaction()
    {
        $id = 1;
        $request = new Request();
        $dtm = new TransactionController();
        $dtm->rollbackTransaction($request, $id);

        $this->assertTrue(true);
    }
}
