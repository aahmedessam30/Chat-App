<?php

namespace App\Http\Controllers;

use App\Events\MessageDilvered;
use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();
        return view('messages.index', ['messages' => $messages]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $message = Auth::user()->messages()->create($request->except('_token'));
        broadcast(new MessageDilvered($message->load('user')))->toOthers();
        return response()->json([
            'body' => $message->body,
            'name' => $message->user->name
        ]);
    }
}
