<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SavingsGoalController;
use App\Models\Transaction;
use App\Models\SavingsGoal;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin'       => Route::has('login'),
        'canRegister'    => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion'     => PHP_VERSION,
    ]);
});

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $userId = auth()->id();

        $transactions = Transaction::where('user_id', $userId)->get();
        $income       = $transactions->where('type', 'income')->sum('amount');
        $expense      = $transactions->where('type', 'expense')->sum('amount');
        $balance      = $income - $expense;
        $goals        = SavingsGoal::where('user_id', $userId)->get();
        $recent       = Transaction::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();

        return Inertia::render('Dashboard', [
            'income'  => $income,
            'expense' => $expense,
            'balance' => $balance,
            'goals'   => $goals,
            'recent'  => $recent,
        ]);
    })->name('dashboard');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/transactions/{transaction}', [TransactionController::class, 'update'])->name('transactions.update');
    Route::delete('/transactions/{transaction}', [TransactionController::class, 'destroy'])->name('transactions.destroy');

    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/savings', [SavingsGoalController::class, 'index'])->name('savings.index');
    Route::post('/savings', [SavingsGoalController::class, 'store'])->name('savings.store');
    Route::put('/savings/{savingsGoal}', [SavingsGoalController::class, 'update'])->name('savings.update');
    Route::delete('/savings/{savingsGoal}', [SavingsGoalController::class, 'destroy'])->name('savings.destroy');


});

require __DIR__.'/auth.php';
