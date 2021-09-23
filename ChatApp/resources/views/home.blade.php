@extends('layouts.app')

@section('content')

<div class=" max-h-screen">
    <div class=" grid max-h-screen  grid-cols-5">
        <div class="col-auto p-0 text-xl min-h-screen max-h-screen overflow-y-auto menu">
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
            @endphp
            <div class="  overflow-y-auto ">

         
            @foreach ($friends as $friend)
                @php
                $user = \App\Models\User::where([['id', '=', $friend->friend_id]])->first();
                @endphp
          
            <a href="{{route('chat', $friend->chat_id)}}" class="block p-3 darklist no-underline mt-2"><div class="no-underline">
            {{$user->name}}<br>
            <span class="no-underline text-sm">online</span>    
            </div></a>  @endforeach
            @foreach ($groeps as $groep)
            <a href="{{route('chatg', $groep->id)}}" class="block p-3 darklist no-underline mt-2"><div class="no-underline">
                {{$groep->name}}<br>
                  
                </div></a>
            @endforeach

        </div>   
        </div>
    </div>
</div>
@endsection
