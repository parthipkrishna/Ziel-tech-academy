<?php

namespace App\Http\Controllers\lms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Lms\Exports\UsersExport;
use App\Lms\Imports\UsersImport;
use App\Lms\Imports\StudentImport;
use App\Lms\Exports\LmsStudentExport;
class ExcelController extends Controller
{
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    public function import(Request $request)
    {
         
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,csv',
        ]);
    
        $file = $request->file('import_file');
    
        // Explicitly specify the file type
        $readerType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($file->getPathname());
    
        if (!$readerType) {
            return back()->with('error', 'Unsupported file format. Please upload a valid Excel or CSV file.');
        }
    
        // Perform import
        Excel::import(new UsersImport, $file);
    
        return back()->with('success', 'Users imported successfully!');
    }

    public function studentImport(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv',
        ]);

        try {
            Excel::import(new StudentImport, $request->file('file'));

            if ($request->ajax()) {
                return response()->json(['message' => 'Excel data imported successfully.'], 200);
            }

            return redirect()->back()->with('message', 'Excel data imported successfully.');

        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 422);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function studentExport()
    {
        return Excel::download(new LmsStudentExport, 'Students.xlsx');
    }
}
