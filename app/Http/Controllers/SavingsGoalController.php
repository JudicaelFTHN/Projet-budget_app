<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SavingsGoalController extends Controller
{
    public function index()
    {
        $goals = SavingsGoal::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('SavingsGoals/Index', [
            'goals' => $goals
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'target_amount'  => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline'       => 'nullable|date',
        ]);

        SavingsGoal::create([
            'user_id'        => auth()->id(),
            'name'           => $request->name,
            'target_amount'  => $request->target_amount,
            'current_amount' => $request->current_amount ?? 0,
            'deadline'       => $request->deadline,
        ]);

        return redirect()->route('savings.index');
    }

    public function update(Request $request, SavingsGoal $savingsGoal)
    {
        abort_if($savingsGoal->user_id !== auth()->id(), 403);

        $request->validate([
            'name'           => 'required|string|max:255',
            'target_amount'  => 'required|numeric|min:0.01',
            'current_amount' => 'nullable|numeric|min:0',
            'deadline'       => 'nullable|date',
        ]);

        $savingsGoal->update($request->only(
            'name', 'target_amount', 'current_amount', 'deadline'
        ));

        return redirect()->route('savings.index');
    }

    public function destroy(SavingsGoal $savingsGoal)
    {
        abort_if($savingsGoal->user_id !== auth()->id(), 403);

        $savingsGoal->delete();

        return redirect()->route('savings.index');
    }
}
