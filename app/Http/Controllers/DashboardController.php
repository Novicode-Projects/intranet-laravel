<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->authorisation === '1' || auth()->user()->authorisation === '2') 
            return view('dashboard');
        
        else if (auth()->user()->authorisation === '3') {

            $sessions = DB::table('learners')
                ->select('training_sessions.id', 'date_start', 'date_end', 'wording')
                ->where('id_learner', auth()->user()->id)
                ->join('training_sessions', 'training_sessions.id', '=', 'learners.id_session')
                ->join('formations', 'formations.id', '=', 'training_sessions.id_formation')
                ->orderByDesc('training_sessions.id')
                ->get();

            $pdfs = DB::table('pdfs')
                ->select('pdfs.id', 'url_pdf', 'pdfs.id_session')
                ->join('training_sessions', 'training_sessions.id', '=', 'pdfs.id_session')
                ->join('learners', 'learners.id_session', '=', 'training_sessions.id')
                ->where('id_learner', auth()->user()->id)
                ->get();
            
            /*
            $sessions = DB::table('training_sessions')
                ->select('training_sessions.id','date_start', 'date_end', 'training_sessions.disable', 'formations.wording AS nameFormation', 'companies.wording AS nameCompany')
                ->join('formations', 'formations.id', '=', 'training_sessions.id_formation')
                ->join('companies', 'companies.id', '=', 'training_sessions.id_company')
                ->where('training_sessions.id', 1)
                ->paginate(5);*/
            
            /*

            $users = DB::table('users')
                ->select('*')
                ->where('authorisation', 3)
                ->get();

            $learners = DB::table('learners')
                ->select('learners.id', 'first_name', 'last_name')
                ->join('users', 'users.id', '=', 'learners.id_learner')
                ->where('id_session', $id)
                ->where('authorisation', 3)
                ->get(); */
            
            return view('./learners/dashboard', compact("sessions", "pdfs"));

            //return view('./learners/dashboard');
        }
            
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    // Grade 2
    public function createTeam()
    {
        $users = DB::table('users')
            ->select('*')
            ->where('authorisation', 2)
            ->paginate(5);

        return view('team')->with('users', $users);
    }

    // Grade 3
    public function createLearner()
    {
        $users = DB::table('users')
            ->select('*')
            ->where('authorisation', 3)
            ->paginate(5);

        return view('learner')->with('users', $users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // Grade 2
    public function storeTeam(Request $request)
    {
        $request->validate([
            'prénom'=>'required|alpha|max:255',
            'nom'=>'required|alpha|max:255',
            'email'=>'required|email|unique:users|max:255',
            'mdp'=>'required|max:255',
        ]);

        User::create([
            'first_name'=>$request->prénom,
            'last_name'=>$request->nom,
            'email'=>$request->email,
            'password'=>Hash::make($request->mdp),
            'authorisation'=> 2
        ]);

        return redirect()->route('dashboard.createTeam');
    }

    // Grade 3
    public function storeLearner(Request $request)
    {
        $request->validate([
            'prénom'=>'required|alpha|max:255',
            'nom'=>'required|alpha|max:255',
            'email'=>'required|email|unique:users|max:255',
            'mdp'=>'required|max:255',
        ]);

        User::create([
            'first_name'=>$request->prénom,
            'last_name'=>$request->nom,
            'email'=>$request->email,
            'password'=>Hash::make($request->mdp),
            'authorisation'=> 3
        ]);

        return redirect()->route('dashboard.createLearner');
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

    // Grade 2
    public function editTeam($id)
    {
        $user = DB::table('users')
            ->select('*')
            ->where('id', $id)
            ->first();
        
        if ($user === NULL)
            return redirect()->route('dashboard.createTeam');

        if ($user->disable !== NULL || $user->authorisation === '1' || $user->authorisation === '3') 
            return redirect()->route('dashboard.createTeam');
        
        return view('team-update')->with('user', $user);
    }

    // Grade 3
    public function editLearner($id)
    {
        $user = DB::table('users')
            ->select('*')
            ->where('id', $id)
            ->first();

        if ($user === NULL)
            return redirect()->route('dashboard.createLearner');

        if ($user->disable !== NULL || $user->authorisation === '1' || $user->authorisation === '2') 
            return redirect()->route('dashboard.createLearner');
        
        return view('learner-update')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    // Grade 2
    public function updateTeam(Request $request, $id)
    {
        $request->validate([
            'prénom'=>'required|alpha|max:255',
            'nom'=>'required|alpha|max:255',
            'email'=>'required|email|max:255|unique:users,email,'. $id,
        ]);

        User::where('id', $request->id)
            ->update([
            'first_name'=>$request->prénom,
            'last_name'=>$request->nom,
            'email'=>$request->email,
        ]);

        return redirect()->route('dashboard.createTeam');
    }

    // Grade 3
    public function updateLearner(Request $request, $id)
    {
        $request->validate([
            'prénom'=>'required|alpha|max:255',
            'nom'=>'required|alpha|max:255',
            'email'=>'required|email|max:255|unique:users,email,'. $id,
        ]);

        User::where('id', $request->id)
            ->update([
            'first_name'=>$request->prénom,
            'last_name'=>$request->nom,
            'email'=>$request->email,
        ]);

        return redirect()->route('dashboard.createLearner');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    
    // Grade 2
    public function destroyTeam($id)
    {
        User::where('id', $id)
            ->update([
            'disable'=>TRUE,
        ]);

        return redirect()->route('dashboard.createTeam');
    }

    // Grade 3
    public function destroyLearner($id)
    {
        User::where('id', $id)
            ->update([
            'disable'=>TRUE,
        ]);

        return redirect()->route('dashboard.createLearner');
    }
}
