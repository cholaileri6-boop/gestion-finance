<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Créer les catégories par défaut si l'utilisateur n'en a aucune (fallback)
        if (Category::where('user_id', $userId)->doesntExist()) {
            Category::createDefaultsForUser($userId);
        }

        $depenses = Depense::with('category')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        $depensesMois = Depense::where('user_id', $userId)
            ->whereMonth('created_at', now()->month)
            ->sum('amount');

        $categories = Category::where('user_id', $userId)
            ->where('type', 'depense')
            ->orderBy('name')
            ->get();

        return view('depenses.index', compact('depenses', 'depensesMois', 'categories'));
    }

    public function create()
    {
        return view('depenses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'montant'     => 'required|numeric|min:0',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        Depense::create([
            'user_id'     => Auth::id(),
            'amount'      => $request->montant,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'date'        => now(),
        ]);

        return back()->with('success', 'Dépense ajoutée');
    }

    public function update(Request $request, Depense $depense)
    {
        $request->validate([
            'montant'     => 'required|numeric|min:0',
            'description' => 'required',
            'category_id' => 'required|exists:categories,id',
        ]);

        $depense->update([
            'amount'      => $request->montant,
            'description' => $request->description,
            'category_id' => $request->category_id,
        ]);

        return back()->with('success', 'Dépense modifiée');
    }

    public function destroy(Depense $depense)
    {
        $depense->delete();
        return back()->with('success', 'Dépense supprimée');
    }

    public function show(Depense $depense)
    {
        return redirect()->back();
    }
}
