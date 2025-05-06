<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function viewHistory()
    {
        $courses = Course::with(['passager', 'conducteur'])->get();
        return response()->json($courses);
    }
    
}
