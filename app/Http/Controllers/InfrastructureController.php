<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Assessment;
use App\AssessmentAnswer;
use Auth;
use Session;

class InfrastructureController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'clearance'])->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
       // $posts = Post::orderby('id', 'desc')->paginate(5); //show only 5 items at a time in descending order

       // return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
		//get physical infrastructure qns
		
		$query = "SELECT id, question, inputype 
		FROM assessment
		WHERE category = 1 
		ORDER BY question ASC";
		$physical_inf_qns = \DB::select($query);
		//get BB infrastructure qns
		$query = "SELECT id, question, inputype  
		FROM assessment 
		WHERE category = 2
		ORDER BY question ASC";
		$bb_inf_qns = \DB::select($query);
		//get ICT infrastructure qns
		$query = "SELECT id, question, inputype 
		FROM assessment  
		WHERE category = 3
		ORDER BY question ASC";
		$ict_inf_qns = \DB::select($query);
        return view('infrastructure.create', compact('physical_inf_qns','bb_inf_qns','ict_inf_qns'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 

    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        $post = Post::findOrFail($id); //Find post of id = $id

        return view ('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $post = Post::findOrFail($id);

        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $this->validate($request, [
            'title'=>'required|max:100',
            'body'=>'required',
        ]);

        $post = Post::findOrFail($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->save();

        return redirect()->route('posts.show', 
            $post->id)->with('flash_message', 
            'Article, '. $post->title.' updated');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')
            ->with('flash_message',
             'Article successfully deleted');

    }
}