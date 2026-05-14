<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('Transactions/Index', [
            'transactions' => $transactions
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'type'        => 'required|in:income,expense',
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date'        => 'required|date',
        ]);

        Transaction::create([
            'user_id'     => auth()->id(),
            'type'        => $request->type,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()->route('transactions.index');
    }

    public function update(Request $request, Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        $request->validate([
            'type'        => 'required|in:income,expense',
            'amount'      => 'required|numeric|min:0.01',
            'description' => 'nullable|string|max:255',
            'date'        => 'required|date',
        ]);

        $transaction->update($request->only('type', 'amount', 'description', 'date'));

        return redirect()->route('transactions.index');
    }

    public function destroy(Transaction $transaction)
    {
        abort_if($transaction->user_id !== auth()->id(), 403);

        $transaction->delete();

        return redirect()->route('transactions.index');
    }
}
