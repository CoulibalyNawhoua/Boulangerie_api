<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ExpenseRepository;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    private $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository) {
        $this->expenseRepository = $expenseRepository;
    }

    public function index()
    {
        $resp = $this->expenseRepository->list_expense();

        return response()->json(['data' => $resp]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'libelle' => 'required',
            'total_amount' => 'required',
            // 'comment' => 'required'
        ]);

        $resp = $this->expenseRepository->create($request->all());

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->expenseRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resp = $this->expenseRepository->edit($id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'libelle' => 'required',
            'total_amount' => 'required',
            'comment' => 'required'
        ]);

        $resp = $this->expenseRepository->update($request->all(), $id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->expenseRepository->delete($id);

        return response()->json(['data' => $resp]);
    }
}
