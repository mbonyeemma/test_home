<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Assessment;
use App\AssessmentAnswer;
use Auth;
use Session;

class MeetingReportController extends Controller {

    public function __construct() {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {
       
       // return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {		
        return view('meetingreport.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) { 
		
		if ($request->hasFile('input_img')) {
			if($request->file('input_img')->isValid()) {
				try {
					$file = $request->file('input_img');
					$name = rand(11111, 99999) . '.' . $file->getClientOriginalExtension();
	
					$request->file('input_img')->move("fotoupload", $name);
				} catch (Illuminate\Filesystem\FileNotFoundException $e) {
	
				}
			}
		}
		
		/*
			$this->validate($request, [
		'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
	  ]);

	  if ($request->hasFile('file')) {
		$image = $request->file('file');
		$name = time().'.'.$image->getClientOriginalExtension();
		$destinationPath = public_path('/storage/galeryImages/');
		$image->move($destinationPath, $name);
	
		$this->save();
			*/
			
			/*
				 $this->validate(request(), [
			'title' => 'required',
			'slug' => 'required',
			'file' => 'required|image|mimes:jpg,jpeg,png,gif'
		]);
	
		$fileName = null;
		if (request()->hasFile('file')) {
			$file = request()->file('file');
			$fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
			$file->move('./uploads/categories/', $fileName);    
		}
	
		Category::create([
			'title' => request()->get('title'),
			'slug' => str_slug(request()->get('slug')),
			'description' => request()->get('description'),
			'category_img' => $fileName,
			'category_status' => 'DEACTIVE'
		]);
			*/
			//https://stackoverflow.com/questions/42755302/laravel-5-4-upload-image
    	
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