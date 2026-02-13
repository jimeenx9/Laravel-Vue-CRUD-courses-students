<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // GET /api/courses
    public function index()
    {
        return response()->json(Course::all(), 200);
    }

    // POST /api/courses
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course = Course::create($validated);

        return response()->json($course, 201);
    }

    // GET /api/courses/{id}
    public function show(string $id)
    {
        $course = Course::findOrFail($id);
        return response()->json($course, 200);
    }

    // PUT /api/courses/{id}
    public function update(Request $request, string $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        $course->update($validated);

        return response()->json($course, 200);
    }

    // DELETE /api/courses/{id}
    public function destroy(string $id)
    {
        Course::destroy($id);
        return response()->json(null, 204);
    }
}
