<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('q'));

        $students = Student::with(['user', 'courses'])
            ->whereHas('user')
            ->when($search, fn($q) => $q->where(function ($w) use ($search) {
                $w->where('first_name', 'like', "%$search%")->orWhere('last_name', 'like', "%$search%");
            }))
            ->orderBy('first_name')
            ->paginate(15)
            ->withQueryString();

        return view('teacher.students.index', compact('students', 'search'));
    }
}
