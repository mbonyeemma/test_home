<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Forms;
use Illuminate\Http\Request;

class FormApiController extends Controller
{
    // Return all forms with their fields
    public function index()
    {
        $forms = Forms::with('fields')->get();
        return response()->json([
            'status' => 'success',
            'data' => $forms
        ]);
    }

    // Return a specific form and its fields
    public function show($form_id)
    {
        $form = Forms::with('fields')->where('form_id', $form_id)->first();

        if (!$form) {
            return response()->json([
                'status' => 'error',
                'message' => 'Form not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $form
        ]);
    }

     // Return all approved forms with their fields
    public function index_status()
    {
        $forms = Forms::with('fields')
                      ->where('publish_status', 'approved')
                      ->get();

        return response()->json([
            'status' => 'success',
            'data' => $forms
        ]);
    }

    // Return a specific approved form and its fields
    public function show_status($form_id)
    {
        $form = Forms::with('fields')
                     ->where('form_id', $form_id)
                     ->where('publish_status', 'approved')
                     ->first();

        if (!$form) {
            return response()->json([
                'status' => 'error',
                'message' => 'Form not found or not approved'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $form
        ]);
    }
}