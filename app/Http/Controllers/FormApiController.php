<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Forms;
use Illuminate\Http\Request;

class FormApiController extends Controller
{
    /**
     * Get a random icon from available icons
     */
    private function getRandomFormIcon()
    {
        $icons = [
            'stethoscope', 'heartbeat', 'user-md', 'hospital-o', 'medkit', 'ambulance',
            'heart', 'plus-square', 'user-plus', 'wheelchair', 'bed', 'thermometer-half',
            'database', 'bar-chart', 'pie-chart', 'line-chart', 'area-chart', 'table',
            'calculator', 'percent', 'trending-up', 'trending-down', 'signal',
            'file-text-o', 'clipboard', 'list-alt', 'check-square-o', 'file-o', 'file-pdf-o',
            'file-word-o', 'file-excel-o', 'edit', 'pencil', 'pencil-square-o',
            'envelope', 'envelope-o', 'phone', 'mobile', 'comment', 'comments', 'bullhorn',
            'megaphone', 'volume-up', 'volume-down', 'microphone',
            'lock', 'unlock', 'key', 'shield', 'user-secret', 'eye', 'eye-slash',
            'fingerprint', 'id-card', 'id-card-o', 'vcard', 'vcard-o',
            'laptop', 'desktop', 'tablet', 'mobile', 'wifi', 'bluetooth', 'usb',
            'plug', 'power-off', 'cog', 'cogs', 'wrench', 'screwdriver',
            'briefcase', 'building', 'building-o', 'bank', 'money', 'dollar', 'credit-card',
            'shopping-cart', 'shopping-bag', 'truck', 'shipping-fast',
            'graduation-cap', 'book', 'bookmark', 'bookmark-o', 'lightbulb-o', 'mortar-board',
            'pencil-square', 'eraser', 'magic', 'star', 'star-o',
            'clock-o', 'calendar', 'calendar-o', 'calendar-check-o', 'calendar-times-o',
            'hourglass-start', 'hourglass-half', 'hourglass-end', 'history',
            'map-marker', 'map', 'globe', 'compass', 'road', 'car', 'plane', 'ship',
            'bicycle', 'motorcycle', 'walking', 'running',
            'home', 'search', 'filter', 'sort', 'refresh', 'undo', 'redo', 'save',
            'download', 'upload', 'share', 'link', 'external-link', 'expand', 'compress',
            'plus', 'minus', 'times', 'check', 'question', 'exclamation', 'info',
            'warning', 'ban', 'trash', 'recycle', 'leaf', 'tree', 'sun-o', 'moon-o'
        ];
        return $icons[array_rand($icons)];
    }

    // Return all forms with their fields
    public function index()
    {
        $forms = Forms::with('fields')->get();
        
        // Add default icons to forms that don't have icons
        $forms->each(function ($form) {
            if (empty($form->icon)) {
                // Use random icon
                $form->icon = $this->getRandomFormIcon();
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
                // Use random icon
                $form->icon = $this->getRandomFormIcon();
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
        $allIcons = [
            'stethoscope', 'heartbeat', 'user-md', 'hospital-o', 'medkit', 'ambulance',
            'heart', 'plus-square', 'user-plus', 'wheelchair', 'bed', 'thermometer-half',
            'database', 'bar-chart', 'pie-chart', 'line-chart', 'area-chart', 'table',
            'calculator', 'percent', 'trending-up', 'trending-down', 'signal',
            'file-text-o', 'clipboard', 'list-alt', 'check-square-o', 'file-o', 'file-pdf-o',
            'file-word-o', 'file-excel-o', 'edit', 'pencil', 'pencil-square-o',
            'envelope', 'envelope-o', 'phone', 'mobile', 'comment', 'comments', 'bullhorn',
            'megaphone', 'volume-up', 'volume-down', 'microphone',
            'lock', 'unlock', 'key', 'shield', 'user-secret', 'eye', 'eye-slash',
            'fingerprint', 'id-card', 'id-card-o', 'vcard', 'vcard-o',
            'laptop', 'desktop', 'tablet', 'mobile', 'wifi', 'bluetooth', 'usb',
            'plug', 'power-off', 'cog', 'cogs', 'wrench', 'screwdriver',
            'briefcase', 'building', 'building-o', 'bank', 'money', 'dollar', 'credit-card',
            'shopping-cart', 'shopping-bag', 'truck', 'shipping-fast',
            'graduation-cap', 'book', 'bookmark', 'bookmark-o', 'lightbulb-o', 'mortar-board',
            'pencil-square', 'eraser', 'magic', 'star', 'star-o',
            'clock-o', 'calendar', 'calendar-o', 'calendar-check-o', 'calendar-times-o',
            'hourglass-start', 'hourglass-half', 'hourglass-end', 'history',
            'map-marker', 'map', 'globe', 'compass', 'road', 'car', 'plane', 'ship',
            'bicycle', 'motorcycle', 'walking', 'running',
            'home', 'search', 'filter', 'sort', 'refresh', 'undo', 'redo', 'save',
            'download', 'upload', 'share', 'link', 'external-link', 'expand', 'compress',
            'plus', 'minus', 'times', 'check', 'question', 'exclamation', 'info',
            'warning', 'ban', 'trash', 'recycle', 'leaf', 'tree', 'sun-o', 'moon-o'
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'all_icons' => $allIcons,
                'categories' => [
                    'medical' => ['stethoscope', 'heartbeat', 'user-md', 'hospital-o', 'medkit', 'ambulance', 'heart', 'plus-square', 'user-plus', 'wheelchair', 'bed', 'thermometer-half'],
                    'data' => ['database', 'bar-chart', 'pie-chart', 'line-chart', 'area-chart', 'table', 'calculator', 'percent', 'trending-up', 'trending-down', 'signal'],
                    'document' => ['file-text-o', 'clipboard', 'list-alt', 'check-square-o', 'file-o', 'file-pdf-o', 'file-word-o', 'file-excel-o', 'edit', 'pencil', 'pencil-square-o'],
                    'communication' => ['envelope', 'envelope-o', 'phone', 'mobile', 'comment', 'comments', 'bullhorn', 'megaphone', 'volume-up', 'volume-down', 'microphone'],
                    'security' => ['lock', 'unlock', 'key', 'shield', 'user-secret', 'eye', 'eye-slash', 'fingerprint', 'id-card', 'id-card-o', 'vcard', 'vcard-o'],
                    'technology' => ['laptop', 'desktop', 'tablet', 'mobile', 'wifi', 'bluetooth', 'usb', 'plug', 'power-off', 'cog', 'cogs', 'wrench', 'screwdriver'],
                    'business' => ['briefcase', 'building', 'building-o', 'bank', 'money', 'dollar', 'credit-card', 'shopping-cart', 'shopping-bag', 'truck', 'shipping-fast'],
                    'education' => ['graduation-cap', 'book', 'bookmark', 'bookmark-o', 'lightbulb-o', 'mortar-board', 'pencil-square', 'eraser', 'magic', 'star', 'star-o'],
                    'time' => ['clock-o', 'calendar', 'calendar-o', 'calendar-check-o', 'calendar-times-o', 'hourglass-start', 'hourglass-half', 'hourglass-end', 'history'],
                    'location' => ['map-marker', 'map', 'globe', 'compass', 'road', 'car', 'plane', 'ship', 'bicycle', 'motorcycle', 'walking', 'running'],
                    'general' => ['home', 'search', 'filter', 'sort', 'refresh', 'undo', 'redo', 'save', 'download', 'upload', 'share', 'link', 'external-link', 'expand', 'compress', 'plus', 'minus', 'times', 'check', 'question', 'exclamation', 'info', 'warning', 'ban', 'trash', 'recycle', 'leaf', 'tree', 'sun-o', 'moon-o']
                ]
            ]
        ]);
    }
}