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
    $user = auth()->user();
    $transactions = Transaction::where('user_id', $user->id)
        ->with('category')
        ->get();

    $income  = $transactions->where('type', 'income')->sum('amount');
    $expense = $transactions->where('type', 'expense')->sum('amount');
    $balance = $income - $expense;
    $goals   = SavingsGoal::where('user_id', $user->id)->get();
    $recent  = $transactions->sortByDesc('date')->take(5)->values();

    $withCategory = $transactions->filter(function($t) {
        return $t->type === 'expense' && $t->category_id !== null;
    });

    $labels = [];
    $values = [];

    foreach ($withCategory->groupBy('category_id') as $catId => $group) {
        $labels[] = $group->first()->category->name;
        $values[] = (float) $group->sum('amount');
    }

    return Inertia::render('Dashboard', [
        'income'    => $income,
        'expense'   => $expense,
        'balance'   => $balance,
        'goals'     => $goals,
        'recent'    => $recent,
        'chartData' => [
            'labels' => $labels,
            'values' => $values,
        ],
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

    Route::get('/profile', function () {
        return Inertia::render('Profile');
    })->name('profile.edit');

    Route::get('/savings', [SavingsGoalController::class, 'index'])->name('savings.index');
Route::post('/savings', [SavingsGoalController::class, 'store'])->name('savings.store');
Route::put('/savings/{savingsGoal}', [SavingsGoalController::class, 'update'])->name('savings.update');
Route::delete('/savings/{savingsGoal}', [SavingsGoalController::class, 'destroy'])->name('savings.destroy');;

    Route::get('/analytics', function () {
        return Inertia::render('Analytics');
    })->name('analytics');

    Route::get('/support', function () {
        return Inertia::render('Support');
    })->name('support');

    Route::get('/referrals', function () {
        return Inertia::render('Referrals');
    })->name('referrals');

    Route::get('/settings', function () {
        return Inertia::render('Settings');
    })->name('settings');

});

require __DIR__.'/auth.php';
