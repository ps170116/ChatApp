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
        return view('home');
    }
    public function friends()
    {
        $all_users = User::where([['id', '!=', Auth::user()->id]])->get();
        $friends = friend::where([['user_id', '=', Auth::user()->id]])->get();
        return view('friends', ['all_users' => $all_users, 'friends' => $friends]);
    }
    public function addfriend($id)
    {
        $project = Project::create([
            'name' => 'test'
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
}
