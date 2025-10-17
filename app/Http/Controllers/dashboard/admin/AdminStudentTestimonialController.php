<?php


namespace App\Http\Controllers\dashboard\admin;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentTestimonial;
use Illuminate\Http\Request;

class AdminStudentTestimonialController extends Controller
{
    public function index(Request $request)
    {
        $testimonials = StudentTestimonial::with('student')->get();
    
        if ($request->ajax()) {
            $search = $request->get('search');
            $students = Student::where('first_name', 'like', '%' . $search . '%')
                               ->orWhere('last_name', 'like', '%' . $search . '%')
                               ->get();
            return response()->json($students);
        }
    
        $students = Student::all(); // Fetch all students for initial load
        return view('dashboard.testimonials.index', compact('testimonials', 'students'));
    }

    public function create()
    {   $students = Student::all();
        return view('dashboard.testimonials.add', compact('students'));
    }
    public function store(Request $request)
    {
    // Validate the request
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'content' => 'required|string|max:1000',
        'rating' => 'required|integer|min:1|max:5',
    ]);

    // Create the testimonial
    StudentTestimonial::create([
        'student_id' => $request->student_id,
        'content' => $request->content,
        'rating' => $request->rating,
    ]);

    // Redirect with success message
    return redirect()->route('admin.testimonials.index')->with('message', 'Testimonial added successfully!');
    }
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'content' => 'required|string|max:1000',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        // Find the testimonial by ID
        $testimonial = StudentTestimonial::findOrFail($id);

        // Update the testimonial
        $testimonial->update([
            'student_id' => $request->student_id,
            'content' => $request->content,
            'rating' => $request->rating,
        ]);

        // Redirect with success message
        return redirect()->route('admin.testimonials.index')->with('message', 'Testimonial updated successfully!');
    }
    public function destroy($id)
    {
        // Find the testimonial by ID
        $testimonial = StudentTestimonial::findOrFail($id);

        // Delete the testimonial
        $testimonial->delete();

        // Redirect with success message
        return redirect()->route('admin.testimonials.index')->with('message', 'Testimonial deleted successfully!');
    }
    
}
