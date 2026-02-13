<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;

class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return response()->json(
            Student::with('course')->get(),
            200
        );
    }

    // POST /api/students
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
            'course_id' => 'required|exists:courses,id'
        ]);

        $student = Student::create($validated);

        return response()->json($student->load('course'), 201);
    }

    // GET /api/students/{id}
    public function show(string $id)
    {
        $student = Student::with('course')->findOrFail($id);
        return response()->json($student, 200);
    }

    // PUT /api/students/{id}
    public function update(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $id,
            'course_id' => 'required|exists:courses,id'
        ]);

        $student->update($validated);

        return response()->json($student->load('course'), 200);
    }

    // DELETE /api/students/{id}
    public function destroy(string $id)
    {
        Student::destroy($id);
        return response()->json(null, 204);
    }
}
