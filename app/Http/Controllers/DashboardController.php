<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $transactions = $user->transactions()->with('category')->get();

        // Seulement les dépenses avec catégorie
        $withCategory = $transactions
            ->filter(function($t) {
                return $t->type === 'expense' && $t->category_id !== null;
            });

        $labels = [];
        $values = [];

        foreach ($withCategory->groupBy('category_id') as $catId => $group) {
            $labels[] = $group->first()->category->name;
            $values[] = $group->sum('amount');
        }

        return Inertia::render('Dashboard', [
            'balance'   => $transactions->where('type','income')->sum('amount')
                         - $transactions->where('type','expense')->sum('amount'),
            'income'    => $transactions->where('type','income')->sum('amount'),
            'expense'   => $transactions->where('type','expense')->sum('amount'),
            'recent'    => $transactions->sortByDesc('date')->take(5)->values(),
            'goals'     => $user->savingsGoals()->get(),
            'chartData' => [
                'labels' => $labels,
                'values' => $values,
            ],
        ]);
    }
}