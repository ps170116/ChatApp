@extends('layouts.app')

@section('content')

<div class="">
    <div class=" grid  grid-cols-5">
        <div class="col-auto p-0 text-xl min-h-screen menu">
           <div class="p-2"><a href="{{route('friends')}}">+ Voeg vrienden toe</a> </div>

           <div class="p-2"><a href="" >+ Maak Groep aan</a></div>

           <div class="p-2 border-b-2 border-gray-500"><p>Berichten</p></div> 
            @php
                $friends = \App\Models\friend::where([['user_id', '=', Auth::user()->id]])->get();
            @endphp
            @foreach ($friends as $friend)
                @php
                $user = \App\Models\User::where([['id', '=', $friend->friend_id]])->first();
                @endphp
          
            <a href="{{route('chat', $friend->chat_id)}}" class="block p-3 darklist no-underline mt-2"><div class="no-underline">
            {{$user->name}}<br>
            <span class="no-underline text-sm">online</span>    
            </div></a>  @endforeach

        </div>   
        
    </div>
</div>
@endsection
