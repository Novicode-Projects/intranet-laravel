<?php

namespace App\Http\Controllers;

use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FormationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $formations = DB::table('formations')
            ->select('*')
            ->paginate(5);

        return view('formation')->with('formations', $formations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'wording'=>'required|max:255|unique:formations,wording',
        ]);

        Formation::create([
            'wording'=>$request->wording,
        ]);

        return redirect()->route('formation.createFormation');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $formation = DB::table('formations')
            ->select('*')
            ->where('id', $id)
            ->first();

        if ($formation === NULL)
            return redirect()->route('formation.createFormation');
        
        if ($formation->disable !== NULL) 
            return redirect()->route('dashboard.createTeam');
        
        return view('formation-update')->with('formation', $formation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'wording'=>'required|max:255',
        ]);

        Formation::where('id', $request->id)
            ->update([
            'wording'=>$request->wording,
        ]);

        return redirect()->route('formation.createFormation');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Formation::where('id', $id)
            ->update([
            'disable'=>TRUE,
        ]);

        return redirect()->route('formation.createFormation');
    }
}
