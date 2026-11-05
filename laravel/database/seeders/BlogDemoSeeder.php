<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BlogDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('posts')->insert([
            ['slug' => 'fiscalita-2025', 'title' => 'Fiscalità 2025: cosa cambia per le imprese', 'body' => 'Analisi delle nuove misure fiscali per le PMI e i professionisti. Riepilogo delle detrazioni, crediti d’imposta e scadenze principali.', 'category' => 'Fisco', 'published_at' => now(), 'created_at' => now()],
            ['slug' => 'design-comuni', 'title' => 'Design dei Comuni: visibilità e accessibilità', 'body' => 'Come il design pubblico migliora la partecipazione civica. Riferimenti ai modelli design-comuni-pagine-statiche e alle linee guida WCAG 2.1 AA.', 'category' => 'Design', 'published_at' => now(), 'created_at' => now()],
            ['slug' => 'eventi-locali', 'title' => 'Eventi locali: calendarizzazione e promozione', 'body' => 'Strumenti per la gestione degli eventi comunali con integrazione database MariaDB, filtri per categoria e condivisione sociale.', 'category' => 'Eventi', 'published_at' => now(), 'created_at' => now()],
        ]);
    }
}
