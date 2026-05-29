<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * La table depenses a été créée avec une colonne `categorie` (string libre)
 * au lieu de `category_id` (foreign key vers categories).
 * Cette migration corrige ce bug de schéma.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            // Supprimer l'ancienne colonne string
            $table->dropColumn('categorie');

            // Ajouter la vraie foreign key (nullable pour ne pas casser les lignes existantes)
            $table->foreignId('category_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained()
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('depenses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
            $table->string('categorie')->after('user_id');
        });
    }
};
