<?php

namespace App\Http\Controllers;

use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sortBy')[0] ? $request->get('sortBy')[0]: 'id';
        $order = $request->get('sortDesc')[0] ? 'desc' : 'asc';

        return Auth::user()->expenses()
            ->select('date', DB::raw('sum(amount) as amount'))
            ->groupBy('date')
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

        $request->user()->expenses()->create(
            $request->only(['date', 'subject', 'amount', 'details'])
        );

        return ['message' => 'Expense successfully recorded!'];
    }

    public function daily($date)
    {
        return Auth::user()->expenses()
            ->where('date', $date)
            ->get();
    }

    public function show(Expense $expense)
    {
        return $expense;
    }

    public function search(Request $request)
    {
        $sort = $request->get('sortBy')[0];
        $order = $request->get('sortDesc')[0] ? 'desc' : 'asc';

        return Auth::user()->expenses()
            ->where(function ($query) use ($request) {
                $query->where('subject', 'like', '%' . $request->get('search') . '%')
                    ->orWhere('details', 'like', '%' . $request->get('search') . '%');
            })
            ->orderBy($sort, $order)
            ->paginate(10);
    }
}
