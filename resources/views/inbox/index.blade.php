@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">
                    Inbox
                    <a href="/inbox/new" class="btn btn-sm btn-outline-primary float-right">New message</a>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        @if(count($subjects))
                        <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Author</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Date</th>
                            <th scope="col">Msg</th>
                            <th scope="col" style="width: 80px;"></th>
                            <th scope="col" style="width: 80px;"></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subjects as $subject)
                            <tr>
                                <th scope="row">{{ $subject->id}}</th>
                                <td>{{ $subject->user_id === Auth::id() ? 'I am' : $subject->user_name }}</td>
                                <td>{{$subject->title}}</td>
                                <td>{{$subject->created_at}}</td>
                                <td>{{\count($subject->messages)}}</td>
                                <td class="text-right"><a class="btn btn-outline-info btn-sm" href="/inbox/{{ $subject->id}}">Read</a></td>
                                <td class="text-right">
                                    @if ( $subject->user_id === Auth::id() )
                                    <form action="/inbox/{{ $subject->id }}" method="post">
                                        {{ method_field('DELETE') }}
                                        {{ csrf_field() }}
                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}" />
                                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        @else
                            <tr>
                                <td colspan="6">
                                    <div class="text-center"> There is no message yet</div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
