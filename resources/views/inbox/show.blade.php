@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="container">
            <div class="card card-default">
                <div class="card-header">
                    {{ $subject->title }}
                    <a href="/inbox" class="btn btn-primary btn-sm btn btn-outline-primary float-right">Back</a>
                </div>
                <div class="card-body">
                    @if(count($errors))
                        @foreach($errors->all() as $error)
                            <div class="alert alert-danger text-center">
                                {{ $error }}
                            </div>
                        @endforeach
                    @endif

                    @foreach($subject->messages as $message)
                        @if( $message->user_id === Auth::id() )
                            <b>{{ $message->user_name }}</b> <div class="float-right">{{ Carbon\Carbon::parse($message->created_at)->format('d.m.Y (H:m)') }}</div>
                            <div class="alert alert-info" style="border: dotted 1px">
                                {{ $message->message }}
                                @if ($message->file)
                                    <br/>Attached: <a target="_blank" href="/uploads/images/{{ $message->file }}">{{ $message->file }}</a>
                                @endif
                            </div>
                        @else
                            <b>{{ $message->name }}</b>  <div class="pull-right">{{ $message->created_at }}</div>
                            <div class="alert alert-warning" style="border: dotted 1px">
                                {{ $message->message  }}
                                @if ($message->file !== '')
                                    <br/>Attached: <a target="_blank" href="/uploads/images/{{ $message->file }}">{{ $message->file }}</a>
                                @endif
                            </div>
                        @endif
                    @endforeach

                    {!! Form::open(array('url' => array('/inbox', $subject->id), 'method' => 'post', 'files' => 'true')) !!}
                    {{ csrf_field() }}
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Write your message here" rows="5" id="message" name="message" required></textarea>
                        {!! Form::file('image', array('class' => 'image')) !!}
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    </div>
                    <div class="text-center">
                        <input type="submit" class="btn btn-outline-primary" value="Send">
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>




    </div>
    <br>
@endsection
