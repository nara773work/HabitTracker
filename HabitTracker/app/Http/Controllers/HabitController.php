<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Habit;
use App\Models\TreeType;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $habits = Habit::with('runs.treeType')->paginate(4);

        return view('index',compact('habits'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $treeTypes = TreeType::orderBy('sort_order')->get();

        return view('create', compact('treeTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Habit $habit)
    {
        $run = $habit->runs()
            ->with(['treeType','habitlogs'])
            ->latest('id')
            ->first();

        return view('show',compact('run','habit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Habit $habit)
    {
        $treeTypes = TreeType::orderBy('sort_order')->get();
        $run = $habit->runs()->latest('id')->first();

        return view('edit', compact('habit', 'treeTypes', 'run'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Habit $habit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit)
    {
        //
    }
}
