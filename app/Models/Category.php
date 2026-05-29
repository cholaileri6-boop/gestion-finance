<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['user_id', 'name', 'type'];

    // ─── Relations ────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function revenus()
    {
        return $this->hasMany(Revenu::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Crée les catégories par défaut pour un utilisateur.
     * Appelé à l'inscription et en fallback si l'utilisateur n'en a aucune.
     */
    public static function createDefaultsForUser(int $userId): void
    {
        $defaults = [
            // Revenus
            ['name' => 'Salaire',         'type' => 'revenu'],
            ['name' => 'Freelance',       'type' => 'revenu'],
            ['name' => 'Investissements', 'type' => 'revenu'],
            ['name' => 'Autres revenus',  'type' => 'revenu'],
            // Dépenses
            ['name' => 'Alimentation',    'type' => 'depense'],
            ['name' => 'Transport',       'type' => 'depense'],
            ['name' => 'Logement',        'type' => 'depense'],
            ['name' => 'Loisirs',         'type' => 'depense'],
            ['name' => 'Santé',           'type' => 'depense'],
            ['name' => 'Autres',          'type' => 'depense'],
        ];

        foreach ($defaults as $cat) {
            self::create([
                'user_id' => $userId,
                'name'    => $cat['name'],
                'type'    => $cat['type'],
            ]);
        }
    }
}
