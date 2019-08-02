<?php

namespace App\Http\Controllers;

use App\Income;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sortBy')[0] ? $request->get('sortBy')[0]: 'id';
        $order = $request->get('sortDesc')[0] ? 'desc' : 'asc';

        return $incomes = Auth::user()->incomes()
            ->where(function ($query) use ($request) {
                $query->where('subject', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('details', 'like', '%' . $request->get('search') . '%');
            })
            ->orderBy($sort, $order)
            ->paginate($request->get('itemsPerPage'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'subject' => 'required|min:3',
            'amount' => 'required|numeric',
        ]);

        $request->user()->incomes()->create(
            $request->only(['date', 'subject', 'amount', 'details'])
        );

        return ['message' => 'Income successfully recorded!'];
    }

    public function show(Income $income)
    {
        return $income;
    }
}
