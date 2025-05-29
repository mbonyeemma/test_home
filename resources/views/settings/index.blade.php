@extends('layouts.app')

    @section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2"> 
            <h2>Test settings</h2>           
                <passport-authorized-clients></passport-authorized-clients>
                <passport-clients></passport-clients>
                <passport-personal-access-tokens></passport-personal-access-tokens>
            </div>
        </div>
    </div>
    @endsection