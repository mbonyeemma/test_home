@extends('layouts.app')
@section('content')

    <div class="card mb-3">
      <div class="card-header"> <i class="fa fa-table"></i> All Posts </div>
      <div class="card-body"> 
      		 @foreach ($posts as $post)
                        <div class="panel-body">
                            <li style="list-style-type:disc">
                                <a href="{{ route('posts.show', $post->id ) }}"><b>{{ $post->title }}</b><br>
                                    <p class="teaser">
                                       {{  str_limit($post->body, 100) }} {{-- Limit teaser to 100 characters --}}
                                    </p>
                                </a>
                            </li>
                        </div>
                    @endforeach
                    <div class="text-center">
                        {!! $posts->links() !!}
                    </div>
     </div>
    </div>
    
    
    
@endsection