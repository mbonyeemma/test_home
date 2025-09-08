@extends('layouts.app')

@section('title', 'Edit Form')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Edit Form: {{ $form->name }}</strong>
                    </div>
                    <div class="panel-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <form action="{{ route('forms.update', $form->form_id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label for="name">Form Name:</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    value="{{ old('name', $form->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="facility_id">Facility</label>
                                <select name="facility_id" id="facility_id" class="form-control">
                                    <option value="">-- Select Facility --</option>
                                    @foreach ($facilities as $id => $name)
                                        <option value="{{ $id }}" {{ $form->facility_id == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="formSubmissionUrl">Form Submission URL:</label>
                                <input type="url" name="formSubmissionUrl" id="formSubmissionUrl" class="form-control"
                                    value="{{ old('formSubmissionUrl', $form->form_submission_url) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="color">Form Color:</label>
                                <div class="color-picker">
                                    @foreach ($colors as $color)
                                        <div class="color-option">
                                            <input type="radio" name="color" id="color_{{ $loop->index }}" 
                                                   value="{{ $color }}" 
                                                   {{ $form->color == $color ? 'checked' : '' }}>
                                            <label for="color_{{ $loop->index }}" 
                                                   style="background-color: {{ $color }}; border: 2px solid {{ $color == '#ffffff' ? '#ccc' : $color }};">
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="form_id">Form ID:</label>
                                <input type="text" name="form_id" id="form_id" class="form-control"
                                    value="{{ $form->form_id }}" readonly>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-warning">Update Form</button>
                                <a href="{{ route('forms.create') }}" class="btn btn-default">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .color-picker {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 5px;
        }
        
        .color-option {
            position: relative;
        }
        
        .color-option input[type="radio"] {
            display: none;
        }
        
        .color-option label {
            display: block;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .color-option input[type="radio"]:checked + label {
            transform: scale(1.2);
            box-shadow: 0 0 0 3px rgba(0,0,0,0.2);
        }
    </style>
@endsection
