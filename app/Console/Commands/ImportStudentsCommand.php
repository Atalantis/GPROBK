<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ImportStudentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-students';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import students from a predefined list into the database.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $studentsData = [
            ['COSTA', 'Vanessa', 'MME', 'vanessa.costa@campus.esbanque.fr', 'v.costa78@outlook.com'],
            ['CUNY', 'Jean-philippe', 'M.', 'jeanphilippe.cuny@campus.esbanque.fr', 'cuny.jeanphilippe@outlook.fr'],
            ['ELMALKI', 'Bettina', 'MME', 'bettina.elmalki@campus.esbanque.fr', 'elmalkibettina@hotmail.fr'],
            ['GARNIER', 'Adrien', 'M.', 'adrien.garnier1@campus.esbanque.fr', 'adrien.garnier78@gmail.com'],
            ['GRANDIN', 'Carole', 'MME', 'carole.grandin@campus.esbanque.fr', 'carole.grandin@hotmail.fr'],
            ['HADDADOU', 'Fateh', 'M.', 'fateh.haddadou@campus.esbanque.fr', 'haddadouf@gmail.com'],
            ['KABRE', 'Audrey', 'MME', 'audrey.kabre@campus.esbanque.fr', 'nganmogneaudrey@gmail.com'],
            ['KODESSA', 'Josaphat', 'M.', 'josaphat.kodessa@campus.esbanque.fr', 'jkodessa@outlook.fr'],
            ['ODET', 'Florent', 'M.', 'florent.odet@campus.esbanque.fr', 'florent.odet@gmail.com'],
            ['RAFINON', 'Jennifer', 'MME', 'jennifer.rafinon@campus.esbanque.fr', 'jennifer.dayan.jd@gmail.com'],
            ['REYNOSO AVALOS', 'Emma', 'MME', 'emma.reynosoavalos@campus.esbanque.fr', 'emmareynosoavls@gmail.com'],
            ['ROKIA', 'Eléonore', 'MME', 'eleonore.rokia@campus.esbanque.fr', 'eleonore.rokia@hotmail.fr'],
            ['SOUDAY', 'Lydia', 'MME', 'lydia.souday@campus.esbanque.fr', 'lydia.souday@gmail.com'],
            ['TRAN', 'Hoa', 'MME', 'hoa.tran@campus.esbanque.fr', 'hoa2014nam@gmail.com'],
        ];

        $this->info('Importation des étudiants...');

        foreach ($studentsData as $student) {
            $lastName = $student[0];
            $firstName = $student[1];
            $civility = $student[2];
            $campusEmail = $student[3];
            $personalEmail = $student[4];

            // Check if user already exists by campus email
            if (User::where('email', $campusEmail)->exists()) {
                $this->warn("L'étudiant {$firstName} {$lastName} ({$campusEmail}) existe déjà. Ignoré.");
                continue;
            }

            User::create([
                'name' => "{$firstName} {$lastName}",
                'email' => $campusEmail,
                'password' => Hash::make('password'), // Mot de passe par défaut
                'role' => 'etudiant',
            ]);
            $this->info("L'étudiant {$firstName} {$lastName} ({$campusEmail}) a été créé.");
        }

        $this->info('Importation terminée.');
    }
}
