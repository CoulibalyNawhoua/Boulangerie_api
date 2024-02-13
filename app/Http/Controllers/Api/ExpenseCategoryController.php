<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ExpenseCategoryRepository;
use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{

    private $expenseCategoryRepository;

    public function __construct(ExpenseCategoryRepository $expenseCategoryRepository) {

        $this->expenseCategoryRepository = $expenseCategoryRepository;
    }
    public function index()
    {
        $resp = $this->expenseCategoryRepository->list_expense_category();

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
            'name' => 'required',
        ]);

        $resp = $this->expenseCategoryRepository->create($request->all());

        return response()->json(['data' => $resp]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $uuid)
    {
        $resp = $this->expenseCategoryRepository->findByUuid($uuid);

        return response()->json(['data' => $resp]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $resp = $this->expenseCategoryRepository->edit($id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $resp = $this->expenseCategoryRepository->update($request->all(), $id);

        return response()->json(['data' => $resp]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $resp = $this->expenseCategoryRepository->delete($id);

        return response()->json(['data' => $resp]);

    }
}
