<?php

declare(strict_types=1);

namespace Modules\Blog\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Blog\Models\Article;
use Modules\Blog\Models\Category;

class DemoInvestorSeeder extends Seeder
{
    public function run(): void
    {
        // Demo per investitore — contenuto reale comunale
        $categories = [
            ['title' => 'Servizi Pubblici', 'slug' => 'servizi-pubblici'],
            ['title' => 'Ambiente', 'slug' => 'ambiente'],
            ['title' => 'Eventi', 'slug' => 'eventi'],
            ['title' => 'Novità', 'slug' => 'novita'],
        ];
        foreach ($categories as $c) {
            Category::firstOrCreate(['slug' => $c['slug']], $c);
        }

        $articles = [
            ['title' => 'Fixcity: la piattaforma per le segnalazioni del Comune', 'content' => '<h2>Obiettivo</h2><p>Fixcity permette ai cittadini di segnalare problemi sul territorio in modo semplice, trasparente e tracciabile. Per l\'investitore: modello SaaS per PA con ricavi ricorrenti.</p>', 'category_slug' => 'servizi-pubblici', 'is_featured' => 1, 'type' => 'post', 'published_at' => now()],
            ['title' => 'Evento: Open Day del Comune — 15 novembre', 'content' => '<h2>Dettagli</h2><p>Partecipazione aperta a cittadini e imprese. Presentazione della piattaforma Fixcity e del portale design-comuni.</p>', 'category_slug' => 'eventi', 'is_featured' => 1, 'type' => 'event', 'published_at' => now(), 'event_start_date' => now()->addDays(5)],
            ['title' => 'Nuovo regolamento per la raccolta differenziata', 'content' => '<p>Il Comune aggiorna le linee guida ambientali. Fixcity supporta la trasparenza con dati in tempo reale.</p>', 'category_slug' => 'ambiente', 'type' => 'post', 'published_at' => now()],
            ['title' => 'Novità: integrazione con il portale PA', 'content' => '<p>Fixcity si integra con il sistema di identità digitale SPID e CIE per una maggiore sicurezza.</p>', 'category_slug' => 'novita', 'type' => 'post', 'published_at' => now()],
            ['title' => 'Report annuale: 642 segnalazioni gestite', 'content' => '<h2>Performance</h2><p>Nel 2026 sono state gestite oltre 642 segnalazioni con un tempo medio di risposta di 3 giorni.</p>', 'category_slug' => 'servizi-pubblici', 'is_featured' => 1, 'type' => 'post', 'published_at' => now()],
        ];

        foreach ($articles as $a) {
            $cat = Category::where('slug', $a['category_slug'])->first();
            Article::create([
                'title' => $a['title'],
                'content' => $a['content'],
                'category_id' => $cat?->id,
                'is_featured' => $a['is_featured'] ?? 0,
                'type' => $a['type'],
                'published_at' => $a['published_at'],
                'event_start_date' => $a['event_start_date'] ?? null,
                'show_on_homepage' => 1,
            ]);
        }
    }
}
