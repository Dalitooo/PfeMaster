<?php

namespace App\Http\Controllers;

use App\Models\Treatment;
use App\Models\TreatmentCategory;
use Illuminate\Http\Request;

class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $categories = TreatmentCategory::withCount('treatments')->orderBy('name')->get();
        $query = Treatment::with('category');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%$search%");
        }

        if ($category = $request->get('category')) {
            $query->where('category_id', $category);
        }

        $treatments = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('treatments.index', compact('treatments', 'categories'));
    }

    public function create()
    {
        $categories = TreatmentCategory::orderBy('name')->get();
        return view('treatments.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:treatment_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5',
            'price'            => 'required|numeric|min:0',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        Treatment::create($validated);

        return redirect()->route('treatments.index')
                         ->with('success', 'Treatment added successfully.');
    }

    public function show(Treatment $treatment)
    {
        $treatment->load(['category', 'records.patient', 'records.doctor']);
        return view('treatments.show', compact('treatment'));
    }

    public function edit(Treatment $treatment)
    {
        $categories = TreatmentCategory::orderBy('name')->get();
        return view('treatments.edit', compact('treatment', 'categories'));
    }

    public function update(Request $request, Treatment $treatment)
    {
        $validated = $request->validate([
            'category_id'      => 'required|exists:treatment_categories,id',
            'name'             => 'required|string|max:255',
            'description'      => 'nullable|string',
            'duration_minutes' => 'required|integer|min:5',
            'price'            => 'required|numeric|min:0',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);
        $treatment->update($validated);

        return redirect()->route('treatments.index')
                         ->with('success', 'Treatment updated successfully.');
    }

    public function destroy(Treatment $treatment)
    {
        $treatment->delete();
        return redirect()->route('treatments.index')
                         ->with('success', 'Treatment deleted.');
    }

    // --- Categories ---
    public function categoriesIndex()
    {
        $categories = TreatmentCategory::withCount('treatments')->orderBy('name')->get();
        return view('treatments.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'color' => 'nullable|string|max:7']);
        TreatmentCategory::create($request->only('name', 'description', 'color'));
        return back()->with('success', 'Category added.');
    }

    public function categoryDestroy(TreatmentCategory $category)
    {
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }
}
