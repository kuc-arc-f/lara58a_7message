<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
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

	/**************************************
	*
	 **************************************/
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
        $post_items = [];
        foreach($messages as $item ){
            $item["title"] = mb_substr( $item->title , 0, 10 );
            $dt = new Carbon($item["created_at"]);
            $item["date_str"] = $dt->format('m-d H:i');
            $post_items[] = $item;
        }
        $messages = $post_items;
//debug_dump( $post_items );
//exit();
        return view('home')->with(compact(
            'user', 'messages', 'message_display_mode'
        ));
    }
    
}
