<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use Illuminate\Http\Request;

class JobVacancyController extends Controller
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => JobVacancy::orderBy('created_at', 'desc')->get()
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'requirements' => 'required|array',
            'documents' => 'required|array',
            'deadline' => 'required|date',
            'is_active' => 'boolean'
        ]);

        $job = JobVacancy::create([
            'title' => $request->title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'documents' => $request->documents,
            'deadline' => $request->deadline,
            'is_active' => $request->is_active ?? true,
        ]);

        return response()->json([
            'success' => true,
            'data' => $job
        ]);
    }

    public function update(Request $request, $id)
    {
        $job = JobVacancy::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'requirements' => 'required|array',
            'documents' => 'required|array',
            'deadline' => 'required|date',
             'is_active' => 'boolean',
        ]);

        $job->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $job
        ]);
    }

    public function destroy($id)
    {
        JobVacancy::findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil dihapus'
        ]);
    }

    // ========================ROUTE PUBLIC=================================================
    public function publicJob()
    {
        return response()->json([
            'success' => true,
            'data' => JobVacancy::where('is_active', true)
                ->whereDate('deadline', '>=', now())
                ->orderBy('created_at', 'desc')
                ->get()
        ]);
    }


}
