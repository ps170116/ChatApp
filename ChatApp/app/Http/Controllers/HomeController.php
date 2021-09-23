<?php

namespace App\Http\Controllers;

use App\Models\friend;
use App\Models\Project;
use App\Models\project_participant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   
        $projects = project_participant::where([['user_id','=',Auth::user()->id]])->get();
        $groeps = Project::where([['name', '!=', 'prive']])->get();
        $mygroups = [];
        foreach ($groeps as $groep ) {
            foreach ($projects as $project ) {
                if ($groep->id == $project->project_id) {
                    array_push($mygroups, $groep);
                }
            }
        }
       
        return view('home', ['groeps' => $mygroups]);
    }
    public function friends()
    {
        $all_users = User::where([['id', '!=', Auth::user()->id]])->get();
        $friends = friend::where([['user_id', '=', Auth::user()->id]])->get();
        return view('friends', ['all_users' => $all_users, 'friends' => $friends]);
    }
    public function groep()
    {
        $all_users = User::where([['id', '!=', Auth::user()->id]])->get();
        return view('group', ['all_users' => $all_users]);
    }
    public function addfriend($id)
    {
        $project = Project::create([
            'name' => 'prive'
        ]);
        project_participant::create([
            'user_id' => Auth::user()->id,
            'project_id' => $project->id,
        ]);
        project_participant::create([
            'user_id' => $id,
            'project_id' => $project->id,
        ]);
        friend::create([
            'user_id' => Auth::user()->id,
            'friend_id' => $id,
            'chat_id' => $project->id,
        ]);
        friend::create([
            'user_id' => $id,
            'friend_id' => Auth::user()->id,
            'chat_id' => $project->id,
        ]);

        return  redirect()->route('chat', $project->id);
    }
    public function chat(Project $project)
    {
        $project->load('tasks'); 
       $users =  project_participant::where([['project_id', '=', $project->id]])->get();
    foreach ($users as $user) {
        if ($user->user_id == Auth::user()->id) {
               
            return view('chat', compact('project'));
        }
    }
    return redirect()->route('home');
    
    }
    public function chatg(Project $project)
    {
        $project->load('tasks'); 
       $users =  project_participant::where([['project_id', '=', $project->id]])->get();
    foreach ($users as $user) {
        if ($user->user_id == Auth::user()->id) {
               
            return view('groupchat', compact('project'));
        }
    }
    return redirect()->route('home');
    
    }
    public function addgroup(Request $request)
    {
        $project = Project::create([
            'name' => $request->input('groepnaam'),
        ]);
        $users = $request->input('user');
        project_participant::create([
            'user_id' => Auth::user()->id,
            'project_id' => $project->id,
        ]);
        foreach ($users as $user ) {
            project_participant::create([
                'user_id' => $user,
                'project_id' => $project->id,
            ]);
        }
        
    }
}
