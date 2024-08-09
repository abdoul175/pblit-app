<?php

namespace App\Http\Controllers;

use App\Models\Envoie;
use App\Models\Retour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ImportRequest;
use App\Models\Prime;
use Rap2hpoutre\FastExcel\FastExcel;

class ImportDataController extends Controller
{
    public function index()
    {
        $envoies = Envoie::all();
        $retours = Retour::all();

        return view('app', compact('envoies', 'retours'));
    }

    public function import(ImportRequest $request)
    {
        $request->validated();

        $file = $request->file('file');
        $file_2 = $request->file('file_2');

        fastexcel()->import($file, function ($row) {
            return Envoie::create([
                'compte' => trim($row['compte']),
                'nom' => trim($row['nom']),
                'montant' => trim($row['montant']),
                'police' => trim($row['police']),
            ]);
        });

        fastexcel()->import($file_2, function ($row) {
            return Retour::create([
                'compte' => trim($row['compte']),
                'nom' => trim($row['nom']),
                'montant' => trim($row['montant']),
                'police' => trim($row['police']),
            ]);
        });

        // Créer la table primes
        DB::statement('CREATE TABLE primes AS SELECT e.compte, e.nom, e.montant, e.police
            FROM envoies AS e
            WHERE e.nom = (SELECT nom FROM retours WHERE nom = e.nom LIMIT 1)
            AND e.montant = (SELECT montant FROM retours WHERE montant = e.montant LIMIT 1)');
        // Ajouter la colonne 'anomalie' à la table primes
        DB::statement('ALTER TABLE primes
            ADD COLUMN anomalie VARCHAR(3) NOT NULL');
        // Créer la table 'nom_retours'
        DB::statement('CREATE TABLE nom_retours AS
            SELECT COUNT(nom) AS nom_appar, nom
            FROM retours
            GROUP BY nom');
        // Créer la table 'nom_primes'
        DB::statement('CREATE TABLE nom_primes AS
            SELECT COUNT(nom) AS nom_appar, nom
            FROM primes
            GROUP BY nom');
        // Créer la table 'nom_retours_primes'
        DB::statement('CREATE TABLE nom_retours_primes AS
            SELECT nom, nom_appar
            FROM nom_retours AS nr
            WHERE nom_appar > ALL (SELECT nom_appar FROM nom_primes WHERE nr.nom = nom_primes.nom)');
        // Modifier la colonne 'anomalie' dans la table 'primes'
        DB::statement("UPDATE primes
            SET anomalie = 'oui'
            WHERE primes.nom = (SELECT nom FROM nom_retours_primes WHERE nom = primes.nom LIMIT 1)");

        return redirect()->to('/export');
    }

    public function export_form()
    {
        return view('export');
    }

    public function exporting(Request $request)
    {
        try {
            $request->validate([
                'name' => ['string', 'max:55']
            ]);

            $name = $request->input('name');

            $filename = $name . '.xlsx';

            $data = Prime::select('police', 'compte', 'nom', 'montant', 'anomalie')->get();
            // Supprimer les lignes de la table "envoies" dans la BD
            DB::statement('DELETE FROM envoies');
            // Supprimer les lignes de la table "retours" dans la BD
            DB::statement('DELETE FROM retours');
            // Supprimer la table "primes" dans la BD
            DB::statement('DROP TABLE primes');
            // Supprimer la table "nom_retours" dans la BD
            DB::statement('DROP TABLE nom_retours');
            // Supprimer la table "nom_primes" dans la BD
            DB::statement('DROP TABLE nom_primes');
            // Supprimer la table "nom_retours_primes" dans la BD
            DB::statement('DROP TABLE nom_retours_primes');

            // Exporter les données dans un fichier Excel
            return (new FastExcel($data))->download($filename);
        } catch (\Exception $err) {
            return redirect('/export')->with('error', "Aucune donnée à exporter. Cliquez sur 'Importer les données' pour exporter à nouveau les données");
        }
    }

    public function delete_export_data()
    {
        $envoies = Envoie::all();
        $retours = Retour::all();

        if (count($envoies) > 0 && count($retours) > 0) {
            DB::statement('DELETE FROM envoies');
            DB::statement('DELETE FROM retours');
            DB::statement('DROP TABLE primes');
            DB::statement('DROP TABLE nom_retours');
            DB::statement('DROP TABLE nom_primes');
            DB::statement('DROP TABLE nom_retours_primes');
            return redirect('/')->with('success', 'Données importées récemment supprimées avec succès!');
        } else {
            return redirect('/');
        }
    }
}
