@extends('layouts.app')

@section('content')

<div class="">
    <div class=" grid  grid-cols-5">
        <div class="col-auto p-0 text-xl min-h-screen menu">
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
        @php
            $already_friends = false;
        @endphp
        <div id="app" class=" col-span-4  position-ref ">
            @foreach ($all_users as $user)

                @foreach ($friends as $friend)
                    @if ($user->id == $friend->friend_id)
                        @php
                            $already_friends = true;
                        @endphp
                    @endif
                @endforeach
                @if ($already_friends == false)
                <div class="flex justify-center">
                    <div class=" flex flex-row justify-center gap-5 text-center p-3 m-3 w-1/2 rounded-2xl border-2 border-black bg-gray-400">
                        <p class="my-auto text-xl">{{$user->name}}</p>
                        <a href="{{route('voegvriend_toe', $user->id)}}" class=" p-3 bg-green-500 rounded-lg">Voeg vriend toe</a>
                    </div>
                </div>
                   
                @endif
                @php
                $already_friends = false;  
                @endphp
            @endforeach
       </div>
        
    </div>
</div>
@endsection
