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
        
        // Add default icons to forms that don't have icons
        $forms->each(function ($form) {
            if (empty($form->icon)) {
                $form->icon = getRandomFormIcon();
                $form->save(); // Save the new icon to database
            }
        });
        
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

        // Add default icons to forms that don't have icons
        $forms->each(function ($form) {
            if (empty($form->icon)) {
                $form->icon = getRandomFormIcon();
                $form->save(); // Save the new icon to database
            }
        });

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

    // Save form data submission
    public function saveData(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'facility_id' => 'required|string',
                'form_id' => 'required|string',
                'request_id' => 'required|string',
                'created_at' => 'required|date',
                'data' => 'required|string',
                'user_id' => 'required|string',
            ]);

            // Log the received data for debugging
            \Log::info('Form data received:', $request->all());

            // Parse the JSON data field
            $formData = json_decode($request->data, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid JSON in data field: ' . json_last_error_msg()
                ], 400);
            }

            // Here you can process the form data as needed
            // For example, save to a form_submissions table or process the data
            
            // For now, we'll just return a success response
            return response()->json([
                'status' => 'success',
                'message' => 'Form data saved successfully',
                'data' => [
                    'facility_id' => $request->facility_id,
                    'form_id' => $request->form_id,
                    'request_id' => $request->request_id,
                    'submitted_at' => now()->toISOString(),
                    'processed_data' => $formData
                ]
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Form data save error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save form data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function colors()
    {
        $forms = Forms::select('form_id', 'name', 'color', 'icon', 'publish_status')->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $forms
        ]);
    }

    /**
     * Get all available form icons
     */
    public function icons()
    {
        return response()->json([
            'status' => 'success',
            'data' => [
                'all_icons' => getFormIcons(),
                'categories' => [
                    'medical' => getIconsByCategory('medical'),
                    'data' => getIconsByCategory('data'),
                    'document' => getIconsByCategory('document'),
                    'communication' => getIconsByCategory('communication'),
                    'security' => getIconsByCategory('security'),
                    'technology' => getIconsByCategory('technology'),
                    'business' => getIconsByCategory('business'),
                    'education' => getIconsByCategory('education'),
                    'time' => getIconsByCategory('time'),
                    'location' => getIconsByCategory('location'),
                    'general' => getIconsByCategory('general')
                ]
            ]
        ]);
    }
}