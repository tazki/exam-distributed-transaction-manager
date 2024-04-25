<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function startTransaction(Request $request)
    {
        Log::info('Transaction started');
        // Create a new transaction record
        $transaction = Transaction::create();
        // Return the transaction ID to the client
        return response()->json(['transaction_id' => $transaction->id]);
    }

    public function commitTransaction(Request $request, $transactionId)
    {
        // Retrieve the transaction record
        $transaction = Transaction::findOrFail($transactionId);
        try {
            Cache::lock('lock:'.$transactionId, 10)->block(3, function () {
                $transaction->update(['status' => 'Committed']);
                // Commit the transaction
                DB::commit();
                Log::info('This '.$transactionId.' transaction committed successfully');
                return response()->json(['message' => 'Transaction committed successfully']);
            });
        } catch (\Exception $e) {
            // Roll back the transaction if an exception occurs to ensure data consistency
            DB::rollBack();
            Log::error('This '.$transactionId.' failed to commit transaction');
            return response()->json(['error' => 'Failed to commit transaction', 'details' => $e->getMessage()], 500);
        }
    }

    public function rollbackTransaction(Request $request, $transactionId)
    {
        // Retrieve the transaction record
        $transaction = Transaction::findOrFail($transactionId);
        try {
            Cache::lock('lock:'.$transactionId, 10)->block(3, function () {
                $transaction->update(['status' => 'RolledBack']);
                // Rollback the transaction
                DB::rollback();
                Log::info('This '.$transactionId.' transaction rolled back successfully');
                return response()->json(['message' => 'Transaction rolled back successfully']);
            });
        } catch (\Exception $e) {
            // Handle rollback failure
            Log::error('This '.$transactionId.' failed to rollback transaction');
            return response()->json(['error' => 'Failed to rollback transaction', 'details' => $e->getMessage()], 500);
        }
    }
}

