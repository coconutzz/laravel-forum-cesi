<?php

namespace App\Http\Controllers;
use Illuminate\Notifications\DatabaseNotification;
use App\Topic;
use App\Category;
use Illuminate\Http\Request;

class TopicController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth')->except(['index' , 'show']);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
       $topics = Topic::latest()->paginate(4);
       return view('topics.index' , compact('topics'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function create()
    {
        $categories = Category::all();
       return view('topics.create')->withCategories($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */


    public function store(Request $request)
    {
       $data = $request->validate([
            'title' => 'required|min:5' ,
            'content' => 'required|min:10'
        ]);

      $topic = new Topic;
      $topic->category_id = $request->input('category_id');
      $topic->title = $request->input('title');
      $topic->content = $request->input('content');
      $topic->user_id = auth()->user()->id;
      $topic->save();
    //   $topic =  auth()->user()->topics()->create($data);
            return redirect()->route('topics.show' , $topic->id );

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */


    public function show(Topic $topic)
    {
        return view('topics.show' , compact('topic'));
    }

    public function showFromNotification(Topic $topic ,DatabaseNotification $notification){

        $notification->markAsRead();
        
        return view('topics.show' ,compact('topic'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */


    public function edit(Topic $topic)
    {
        $this->authorize('update' , $topic) ;
        return view('topics.edit' , compact('topic'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */


    public function update(Request $request, Topic $topic)
    {
        $this->authorize('update' , $topic) ;
        $data = $request->validate([
            'title' => 'required|min:5' ,
            'content' => 'required|min:10'
        ]);

        $topic->update($data);

        return redirect()->route('topics.show' , $topic->id);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Topic  $topic
     * @return \Illuminate\Http\Response
     */



    public function destroy(Topic $topic)
    {
        $this->authorize('delete' , $topic) ;
        Topic::destroy($topic->id);
        return  redirect('/');
    }

   
    public function SpecificTopics($id)
    {
        $topics = Topic::where('category_id' , $id)->paginate(6); 
         return view('topics.index')->with( 'topics' , $topics);
    }
}
