@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">

            {{-- Online Users --}}
            <div class="col-md-3">
                <h3>Online Users</h3>
                <hr>
                <h5 id="no-online">No Online Users</h5>
                <ul class="list-group" id="online_users"></ul>
            </div>

            {{-- Message Area --}}

            <div class="col-md-9 d-flex flex-column" style="height: 80vh;">
                <div class="h-100 bg-white mb-4 p-5 overflow-auto" id="chat">
                    @foreach ($messages as $message)
                        <div
                            class="mt-4 w-50 p-2 rounded {{ Auth::id() === $message->user_id ? 'float-right bg-primary text-white' : 'float-left bg-light' }}">
                            @if (Auth::id() !== $message->user_id)
                                <span class="d-block pb-2" style="font-size: 12px">{{ $message->user->name }}</span>
                            @endif
                            {{ $message->body }}
                        </div>
                        <div class="clearfix"></div>
                    @endforeach
                </div>

                <form id="chat-text">
                    <input type="text" name="body" class="form-control d-inline" style="width: 91%;"
                        data-url="{{ route('messages.store') }}">
                    <button type="submit" class="btn btn-primary">Send</button>
                </form>
            </div>
        </div>
    </div>
@endsection
