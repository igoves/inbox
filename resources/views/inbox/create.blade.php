@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card card-default">
        <div class="card-header">
            New Message
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
            {!! Form::open(array('url' => '/inbox/new', 'method' => 'post', 'files' => 'true')) !!}
            {{ csrf_field() }}
            <div class="form-group">
                {!! Form::text('subject', '', array('class' => 'form-control', 'placeholder' => 'Subject', 'id' => 'formGroupExampleInput', 'required' => 'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::select('to_users[]', $users, '', array('multiple'=>'multiple', 'class' => 'form-control', 'required' => 'required')) !!}
            </div>
            <div class="form-group">
                {!! Form::textarea('message', '', array('class' => 'form-control', 'placeholder' => 'Write your message here', 'id' => 'message', 'rows' => 5)) !!}
            </div>
            {!! Form::file('image', array('class' => 'image')) !!}
            <div class="text-center">
                {!! Form::submit('Send Message', array('class' => 'btn btn-outline-primary')) !!}
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection
