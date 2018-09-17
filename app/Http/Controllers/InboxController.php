<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Subject;
use App\Message;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;


class InboxController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $subjects = Subject::whereHas('users', function ($query) {
            $query->where('user_id', Auth::id())->orderBy('created_at', 'desc');
        })->orWhere('user_id', Auth::id())->get();

        return view('inbox.index', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id() )->pluck('name', 'id');
        return view('inbox.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,
            [
                'message' => 'max:10000',
                'subject' => 'required',
                'to_users' => 'required',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]
        );

        $filename = '';
        if ($request->hasFile('image')) {
            $path = public_path() . '\uploads\images\\';
            $file = $request->file('image');
            $filename = (str_random(20) . '.' . $file->getClientOriginalExtension()) ?: 'png';
            $img = Image::make($file);
            $img->fit(80, 80)->save($path . $filename);
        }

        $subject = new Subject();
        $subject->title = $request->subject;
        $subject->user_id = Auth::id();
        $subject->user_name = Auth::user()->name;
        $subject->save();

        $message = new Message();
        $message->user_name = Auth::user()->name;
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->subject_id = $subject->id;
        $message->file = $filename;
        $message->save();

        $subject->users()->sync($request->to_users);

        return redirect('/inbox');
    }

    /**
     * Display the specified resource.
     *
     * @param conversation|int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Subject $id)
    {
        return view('inbox.show')->with('subject', $id);
    }

    /**
     * Add the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Subject|int $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function add(Request $request, Subject $id)
    {
        $this->validate($request,
            [
                'message' => 'required|max:10000'
            ]
        );

        $filename = '';
        if ($request->hasFile('image')) {
            $path = public_path() . '\uploads\images\\';
            $file = $request->file('image');
            $filename = (str_random(20) . '.' . $file->getClientOriginalExtension()) ?: 'png';
            $img = Image::make($file);
            $img->fit(80, 80)->save($path . $filename);
        }

        $message = new Message();
        $message->user_name = Auth::user()->name;
        $message->user_id = Auth::id();
        $message->message = $request->message;
        $message->subject_id = $request->subject_id;
        $message->file = $filename;
        $message->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Subject::where('id', $id)->with('messages')->first();
        if( Auth::id() === $data->user_id ) {
            foreach ($data->messages as $messages) {
                if ( $messages->file != '' ) {
                    File::delete(public_path().'/uploads/images/'.$messages->file);
                }
                $messages->forceDelete();
            }
            $data->forceDelete();
            return redirect()->back()->send();
        }
        return redirect()->back();
    }
}
