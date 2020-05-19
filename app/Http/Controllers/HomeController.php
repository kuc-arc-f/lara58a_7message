<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;
use App\User;
//
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
        $message_display_mode = true;
        $user = Auth::user();
        $user_id = Auth::id();
        //messages
        $messages = Message::orderBy('messages.id', 'desc')
        ->select([
            'messages.id',
            'messages.created_at',
            'messages.title',
            'users.name',
        ])        
        ->leftJoin('users','users.id','=','messages.from_id')
        ->where('messages.to_id', $user_id )
        ->where('messages.status', 1 )
        ->get();
        /*
        $messages = Message::orderBy('messages.id', 'desc')
        ->select([
            'messages.id',
            'messages.created_at',
            'messages.title',
        ])        
        ->where('messages.to_id', $user_id )
        ->where('messages.status', 1 )
        ->get();
        */
//debug_dump( $messages->toArray() );
//exit();
        return view('home')->with(compact(
            'user', 'messages', 'message_display_mode'
        ));
    }
}
