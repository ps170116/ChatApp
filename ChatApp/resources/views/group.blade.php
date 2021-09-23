@extends('layouts.app')

@section('content')

<div class="">
    <div class=" grid  grid-cols-5">
        <div class="col-auto p-0 text-xl max-h-screen overflow-y-auto min-h-screen menu">
            <a class="dropdown-item text-sm text-right" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();"> 
                                    {{ __('Uitloggen') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
            <div class="p-2"><a href="{{route('friends')}}">+ Voeg vrienden toe</a> </div>

           <div class="p-2"><a href="{{route('groep')}}" >+ Maak Groep aan</a></div>

           <div class="p-2 border-b-2 border-gray-500"><p>Berichten</p></div> 
            @php
                $friends = \App\Models\friend::where([['user_id', '=', Auth::user()->id]])->get();

                $projects1 = \App\Models\project_participant::where([['user_id','=',Auth::user()->id]])->get();
        $groeps1 = \App\Models\Project::where([['name', '!=', 'prive']])->get();
        $mygroups1 = [];
        foreach ($groeps1 as $groep ) {
            foreach ($projects1 as $project1 ) {
                if ($groep->id == $project1->project_id) {
                    array_push($mygroups1, $groep);
                }
            }
        }


            @endphp
            @foreach ($friends as $friend)
                @php
                $user = \App\Models\User::where([['id', '=', $friend->friend_id]])->first();
                @endphp
          
            <a href="{{route('chat', $friend->chat_id)}}" class="block p-3 darklist no-underline mt-2"><div class="no-underline">
            {{$user->name}}<br>
            <span class="no-underline text-sm">online</span>    
            </div></a>  @endforeach
            @foreach ($mygroups1 as $groep)
            <a href="{{route('chatg', $groep->id)}}" class="block p-3 darklist no-underline mt-2"><div class="no-underline">
                {{$groep->name}}<br>
                  
                </div></a>
            @endforeach

        </div>   
        <div id="app" class=" col-span-4  position-ref ">
            <form action="{{route('addgroup')}}" method="post">
             @csrf
            <div class="grid grid-cols-4 gap-4 justify-center">
                <input type="text" required placeholder="Voer hier de Groepsnaam in" id="groepnaam" name="groepnaam" class=" col-span-2 col-start-2 p-3  text-lg rounded-xl  m-3">
                <input type="submit" class="m-3 bg-green-600 text-white rounded-xl" value="Maak groep aan">
            </div>
            

            @foreach ($all_users as $user)
                <div class="flex justify-center p-2">
                    <div class=" flex grid-cols-2 justify-center gap-5 text-center  w-1/2 rounded-2xl border-2 border-black bg-gray-400">
                        <div class="my-auto ">
                            <input type="checkbox" class=" h-4 w-4" value="{{$user->id}}" name="user[]" >
                        </div>
                        <div class=" p-3">
                            <p class="m-3 text-xl font ">{{$user->name}}</p>
                            
                        </div>
                        
                    </div>
                </div>
            @endforeach
        </form>
       </div>
        
    </div>
</div>
@endsection
