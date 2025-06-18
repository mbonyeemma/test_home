@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-3">Form: {{ $form->name }}</h2>
    <p><strong>Form ID:</strong> {{ $form->form_id }}</p>

    <form>
        @csrf

        <fieldset disabled>
            @foreach($form->fields as $field)
                <div class="form-group mb-4">
                    <label for="{{ $field->field_name }}" class="form-label">
                        {{ ucfirst(str_replace('_', ' ', $field->field_name)) }}
                        @if($field->option === 'mandatory')
                            <span class="text-danger">*</span>
                        @endif
                    </label>

                    {{-- Render Input --}}
                    @if($field->field_type === 'input')
                        <input 
                            type="text" 
                            class="form-control"
                            id="{{ $field->field_name }}"
                            name="{{ $field->field_name }}"
                            value="{{ $field->field_value }}"
                            {{ $field->option === 'mandatory' ? 'required' : '' }}
                        >

                    {{-- Render Dropdown --}}
                    @elseif($field->field_type === 'dropdown')
                        @php
                            $options = is_array($field->dropdown_options)
                                ? $field->dropdown_options
                                : explode(',', $field->dropdown_options);
                        @endphp
                        <select 
                            class="form-control"
                            id="{{ $field->field_name }}"
                            name="{{ $field->field_name }}"
                            {{ $field->option === 'mandatory' ? 'required' : '' }}
                        >
                            <option value="">-- Select --</option>
                            @foreach($options as $option)
                                <option value="{{ trim($option) }}"
                                    {{ $field->field_value == trim($option) ? 'selected' : '' }}>
                                    {{ trim($option) }}
                                </option>
                            @endforeach
                        </select>

                    {{-- Render Checkbox --}}
                    @elseif($field->field_type === 'checkbox')
                        <div class="form-check">
                            <input 
                                type="checkbox"
                                class="form-check-input"
                                id="{{ $field->field_name }}"
                                name="{{ $field->field_name }}"
                                value="1"
                                {{ $field->field_value ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="{{ $field->field_name }}">
                                {{ ucfirst(str_replace('_', ' ', $field->field_name)) }}
                            </label>
                        </div>
                    @endif

                    {{-- Option & Status badges --}}
                    <div class="mt-1">
                        <span class="badge bg-{{ $field->option === 'mandatory' ? 'danger' : 'info' }}">
                            {{ ucfirst($field->option) }}
                        </span>
                        <span class="badge bg-{{ $field->status === 'enabled' ? 'success' : 'secondary' }}">
                            {{ ucfirst($field->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </fieldset>

        <button type="submit" class="btn btn-primary" disabled>Submit (View Only)</button>
    </form>
</div>
@endsection
