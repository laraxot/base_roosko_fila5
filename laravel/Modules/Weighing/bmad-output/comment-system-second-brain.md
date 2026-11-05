---
type: second-brain-initiative
tags: [comment, spatie-removal, second-brain, reference-sites]
status: open
created: 2026-10-16
updated: 2026-10-16
initiative_id: kp-spatie-removal-comment
title: Internalizzare Spatie per Comment – rimuovere packages/ e adottare pattern pubblico dai siti di riferimento
summary: |
  Eliminare il fork vendor `packages/spatie/` dal modulo Comment, sostituendo la dipendenza da `spatie/laravel-comments` con il motore nativo Laraxot (symfony/html-sanitizer, Livewire, Filament). Questo permette di rispettare il principio Ponytail (nessuna dipendenza esterna venduta dentro il repo) e di avere un comment system pienamente controllato.
  Parallelamente, studiare i pattern di commenti pubblici dai siti di riferimento per allineare l'esperienza utente FO (Front Office) del modulo Comment a quella di:
  - https://www.afcformazione.it/blog/
  - https://www.aferrari.it/articoli/
  - https://www.assodellavendita.it/blog/
  Questi siti mostrano commenti threadati, moderazione leggera, integrazione con media e notifiche via email – tutti aspetti già presenti nel motore Comment da internalizzare.
links:
  - afcformazione_blog: https://www.afcformazione.it/blog/
  - aferrari_articoli: https://www.aferrari.it/articoli/
  - assodellavendita_blog: https://www.assodellavendita.it/blog/
  - comment_module_readme: ./laravel/Modules/Comment/README.md
  - native_comments_engine: ./laravel/Modules/Comment/docs/native-comments-engine.md
  - spatie_removal_bmad: ./bmad-output/comment-spatie-removal-bmad.md
  - filament_blueprint: ./bmad-output/comment-filament-blueprint.md