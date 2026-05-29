<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Revenu;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RevenuController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Créer les catégories par défaut si l'utilisateur n'en a aucune (fallback)
        if (Category::where('user_id', $userId)->doesntExist()) {
            Category::createDefaultsForUser($userId);
        }

        $revenus = Revenu::with('category')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $categories = Category::where('user_id', $userId)
            ->where('type', 'revenu')
            ->orderBy('name')
            ->get();

        $chartData = [];

        return view('revenus.index', compact('revenus', 'chartData', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
        ]);

        Revenu::create([
            'user_id'     => auth()->id(),
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()
            ->route('revenus.index')
            ->with('success', 'Revenu ajouté avec succès');
    }

    public function update(Request $request, Revenu $revenu)
    {
        $request->validate([
            'amount'      => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'date'        => 'required|date',
        ]);

        $revenu->update([
            'category_id' => $request->category_id,
            'amount'      => $request->amount,
            'description' => $request->description,
            'date'        => $request->date,
        ]);

        return redirect()
            ->route('revenus.index')
            ->with('success', 'Revenu modifié');
    }

    public function destroy(Revenu $revenu)
    {
        $revenu->delete();

        return redirect()
            ->route('revenus.index')
            ->with('success', 'Revenu supprimé');
    }

    public function show(Revenu $revenu)
    {
        return redirect()->route('revenus.index');
    }

    public function edit(Revenu $revenu)
    {
        $categories = Category::where('user_id', auth()->id())
            ->where('type', 'revenu')
            ->orderBy('name')
            ->get();

        return view('revenus.edit', compact('revenu', 'categories'));
    }
}
