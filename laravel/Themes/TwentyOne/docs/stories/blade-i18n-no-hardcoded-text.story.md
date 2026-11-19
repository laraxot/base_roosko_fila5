---
title: "Niente testi fissi nelle Blade del tema TwentyOne (multilingua it/en/de/es)"
type: story
status: implemented
theme: TwentyOne
updated: 2026-10-30
tags: [i18n, blade, multilingua]
---

# Niente testi fissi nelle Blade del tema TwentyOne

## Scopo

Il sito risponde in italiano, inglese, tedesco e spagnolo: un testo scritto dentro una Blade esce uguale in ogni lingua. Il `fallback_locale` e' `it`, quindi una chiave mancante in `en`/`de`/`es` non da' errore e mostra l'italiano.

## Regola operativa

- Ogni testo visibile passa da `{{ __('pub_theme::contesto.collezione.chiave.tipo') }}`: cinque elementi, dove `contesto` e' il nome del file lingua e `tipo` il genere di testo (`label`, `title`, `description`, `placeholder`, `tooltip`, `helper_text`, `message`, `aria_label`, `alt`; [regola 008](../../../../../docs/wiki/rules/008-translation-5-level-protocol.md)). Mai la frase come chiave, e il traduttore ignora `['default' => ...]`.
- Namespace: solo `pub_theme::`, mai `twentyone::` ne' `two::`. Il tema non registra un namespace proprio: il CMS mappa `pub_theme` su `Themes/<tema attivo>/lang`, quindi le chiavi si risolvono quando il tema attivo e' TwentyOne.
- La stringa va in `Themes/TwentyOne/lang/{it,en,de,es}/<contesto>.php`, con le stesse chiavi e gli stessi `:parametri` nelle quattro lingue. `it` e' l'originale; `en`, `de`, `es` sono traduzioni vere. Se l'originale era inglese (quasi tutte le viste di articolo e di trading), `en` lo conserva e `it` e' la traduzione.
- Vale anche per `aria-label`, `placeholder`, `title`, `alt`. Frasi con variabili: `:param` e `trans_choice`; il markup resta nella Blade.
- Link a pagine CMS: niente `href="/it/..."`, niente `url('/...')`, niente nomi di rotta inventati per pagina. Si usano le rotte generiche della shell Folio, `route('container0.index', ['container0' => 'tickets'])` e `route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche'])`: il prefisso lingua lo aggiunge Folio. I nomi Folio veri (`home`, `login`, `logout`) restano validi.
- Non sono errori: marchi e nomi propri, unita' di valuta, numeri, valori che arrivano dal modello o dal CMS.

## Cosa e' stato fatto

- Rilevatore `bashscripts/tools/i18n/find-hardcoded-blade-text.py` sul tema: da 1.022 occorrenze in 149 file a 650 in 105 file (stesso strumento, commit di partenza contro stato attuale). La riduzione viene dalla prima meta' del tema.
- 56 Blade convertite: pagina dell'articolo e relativi blocchi, elenco articoli, schede e filtri dei mercati, dettaglio mercato, modulo di trading, footer, hero, navigazione, condivisione social, blocchi di marketing.
- Creati 12 file lingua per ognuna delle quattro lingue, circa 290 chiavi per lingua: `article_list`, `article_show`, `blocks_footer`, `blocks_hero`, `blocks_marketing`, `blocks_markets`, `blocks_nav`, `blocks_social`, `blocks_trading`, `market_common`, `market_detail`, `market_filters`. Creati in `de` ed `es` anche `404` e `headernav`, che esistevano solo in `it`/`en`.

## Residui dichiarati (650 occorrenze)

| Categoria | N |
|---|---:|
| Seconda meta' del tema, non convertita (pagine, accesso e registrazione, widget Filament dei mercati, home, footer crypto, intestazioni) | 596 |
| Prima meta' del tema: dati di esempio di mercato (commenti, tag, importi, date), direttive Blade, marchi | 54 |

Testi in PHP: non cercati in questa campagna sul tema, quindi non toccati.

## Limiti

- Il tema e' un clone di un altro prodotto (mercati di previsione): le viste che dipendono dal suo modulo, assente in questo repo, non sono state toccate. La seconda meta' resta da convertire se il tema verra' usato.
- Nessuna verifica a video, e il tema non e' quello servito oggi: le viste convertite non hanno effetto a video finche' TwentyOne non diventa il tema attivo, e questo non e' stato provato.
- Verifica del 2026-10-30: compilazione Blade dei file convertiti senza errori, nessuna chiave usata dalla campagna manca in `it`, `en`, `de`, `es`.

## Riferimenti

Metodo e regola completa: [story del tema Two](../../../Two/docs/stories/blade-i18n-no-hardcoded-text.story.md); protocollo a cinque elementi: [regola 008](../../../../../docs/wiki/rules/008-translation-5-level-protocol.md). Il dettaglio per file e' nel repo privato di audit.

## Seguito: forma delle chiavi

Le chiavi a 2-3 segmenti create da questa campagna sono state in parte portate a `ns::file.collezione.chiave.tipo`: **11 chiavi spostate** in questo proprietario (stesso testo, tutte le lingue, usi riscritti, nessun uso della vecchia chiave rimasto). Una chiave non e' stata spostata (`404.no_article`, usata anche dal modulo User). La frase sui «tre o quattro elementi» qui sopra descrive lo stato precedente: le chiavi rimaste a 3-4 elementi non sono state verificate una per una; la misura assoluta, lo strumento e i residui sono in [la story di Lang](../../../../Modules/Lang/docs/stories/translation-key-shape-rekey.story.md).


### Passata 2026-10-30 (sessione 97f16a39, TwentyOne)

Misura con `find-hardcoded-blade-text.py Themes/TwentyOne`. Convenzione di lingua: come nella story, dove l'originale della Blade era inglese `en` lo conserva e `it` e' la traduzione; dove era italiano (pagine predict, widget, 404, privacy, home) `it` e' identico all'originale; `Seleccione` (originale spagnolo) resta in `es`.

| Misura | Prima | Dopo |
|---|---:|---:|
| Occorrenze, tutte le classi | 688 in 123 file | 270 in 96 file |
| `text` | 563 | 206 |
| `attr` | 58 | 23 |
| `phrase-key` | 26 | 0 |
| `href-locale`, `url-literal`, `route-page-name`, `route-unknown` | 3, 4, 32, 2 | invariati (esclusi per decisione dell'utente) |

Per cartella (`text` + `attr` + `phrase-key`): `components/predict` 74 -> 10, `components/social` 17 -> 0, `components/ui` 19 -> 1, `components/sections` 39 -> 19, `layouts/headernav` 32 -> 13, `layouts/home` 28 -> 1, `filament/widgets` 55 -> 10, `livewire` 60 -> 21, `pages/auth` 26 -> 7, `pages/profile` 15 -> 1, `pages` (privacy, show, search, markets, ...) 118 -> 34, radice/errors/foliopages 55 -> 5. Invariate: `article` 36, `components/blocks` 16, `layouts/footer` 6, `pages/genesis` 29, `pages/blog` 9, `pages/dashboard` 7, `pages/learn` 3 (motivi sotto).

- File toccati: 64 Blade, 454 sostituzioni, tutte con `blade-replace.py` (sostituzioni esatte, una volta per voce). `components/sections/headernav/*` e `layouts/headernav/*` sono copie dello stesso contenuto: tenute allineate (stesse chiavi).
- Chiavi aggiunte per lingua: 281 in `it` e `en`, 281 in `de` e `es` (prima 397/397/393/393, dopo 678/678/674/674). 19 file lingua nuovi in ogni lingua: `social_share`, `ui_components`, `predict_footer`, `predict_home`, `predict_cards`, `predict_trading_form`, `error_page`, `widgets_auth`, `widgets_ratings`, `widgets_predict`, `home_page`, `home_sections`, `sections_footer`, `auth_pages`, `profile_page`, `pages_show`, `pages_search`, `pages_misc`, `page_privacy`; `headernav` esteso (+13). Chiavi gia' esistenti riusate senza modifiche: `blocks_footer.social.rights_reserved.message`, `blocks_nav.notifications.activities.label`, `blocks_nav.notifications.followed_forecasters.label`. Tutte nella forma `pub_theme::contesto.collezione.chiave.tipo`.
- Oltre allo scanner: convertiti anche `meta-description` e `label=` di componenti (`<x-layouts.app>`, `<x-ui.input>`) con la forma legata `:title="__('...')"`, e stringhe italiane dentro espressioni PHP di `view-predict/detail` (`'Da definire'`, `'Binario'`, `'Multi-esito'`).

Residui (229 occorrenze nelle classi `text`/`attr`/`phrase-key`, piu' 41 di `href-locale`/`url-literal`/`route-*` lasciate per decisione dell'utente), con il motivo:
- Dati di esempio di una pagina catturata da un altro sito, non da tradurre: `article/show/content/*` e `article/show/sidebar/check` (36: mercato di esempio, tag, date, importi; i valori sono sovrascritti da Alpine con `x-text`), titoli di mercato di esempio in `blocks/markets/professional-cards`, notifica di esempio con data fissa (`blocks/notifications/simple`, `headernav/*/notifications`, `check_guest/check`), `2 minutes ago` in `position-summary`, nomi utente e commenti di esempio in `pages/show`, `home-improved` (nomi delle testimonianze).
- Starter kit Genesis e pagine demo, non tradotte (la pagina `power-ups` dice di rimuovere `pages/genesis` prima della produzione): `pages/genesis/*` (29), `pages/blog/*` (9), `pages/dashboard` (testo promozionale), `pages/learn`, `pages/hello`, `pages/todos`, `pages/cart`, `pages/index-simple`, `livewire/item`, `livewire/toast`, e `foliopages/[lang]/contact_us` (debug `Lang {{ $lang }}`). Nelle ultime due pagine di dashboard e learn sono state convertite solo le `phrase-key` (`Dashboard`, `Learn More`).
- Marchi, nomi e unita': `Twitter`, `Facebook`, `Google`, `Telegram`, `Predict`, `PredictFuture`, `WCAG 2.2 AA`, `Credits`, `CAPS`, `Ooms`, codici `BRL`/`EUR`/`USD` (testo e `alt`), nomi lingua nativi `English`/`Italiano`, `Predict S.r.l.`.
- Valori di esempio: `example@email.com`, indirizzo, e-mail e partita IVA del titolare in `pages/privacy` (`Via Example 123`, `12345678901`).
- Falsi positivi dello scanner: direttive Blade lette come testo (`@error`, `@volt`, `@svg`, `@teleport`, `@hasSection`), `:alt="activeOutcome.title"` (binding Alpine), e `:title="__('...')"` in `errors/404` e `pages/privacy` (gia' tradotto, lo scanner non riconosce la forma legata).
- Da correggere a mano, non toccati perche' cambierebbero il testo visibile: `livewire/auth/passwords/reset` mostra come label la stringa grezza `password_confirmation`; `filament/widgets/ratings-done-widget` mostra come testo la chiave grezza `predict::article.sell_for` (namespace `predict` assente in questo repo); `ratings-with-image-widget` ha un titolo di prova `Place bet for: sdfgsdgd`.
- Refusi dell'originale conservati identici: `Favorto` (it, per Favorito) e `Oblighi di legge` (it, per Obblighi) in `widgets_predict.detail.favorite.label` e `page_privacy.legal_basis.law_label.label`; `Dont have an account?` in `en`; nella FAQ la frase sull'API non ha il punto dopo `documentation` (come nell'originale).

Controlli eseguiti a fine passata: `blade-compile-check.php` sul tema, 258 file, 0 non compilano (all'inizio ne risultavano 3 che non compilavano gia' prima, `viral_challenge`, `rich-snippet`, `json-ld`: corretti da un altro agente, non da questa passata); `check-blade-keys.php laravel --only=Themes/TwentyOne`: 945 usi, 3 chiavi mancanti, tutte `user::auth.*` del modulo User (`sign-up` in `es`, `login-in` e `logout_success` in `it`/`en`/`es`), nessuna `pub_theme::`; `check-key-shape.py laravel/Themes/TwentyOne`: nessuna chiave nuova tra le storte (le 2 storte di `pub_theme` sono `404.no_article`, preesistenti); `php -l` su tutti i file lang del tema, nessun errore; confronto chiavi e `:parametri` it/en/de/es sui file nuovi: stesse chiavi e stessi parametri. Fuori da questa passata: `containers.php` esiste solo in `it`/`en` (preesistente, non usato dalle Blade).

Non verificato: nessuna resa a video e nessuna richiesta HTTP (TwentyOne non e' il tema attivo; le Blade sono solo compilate); le traduzioni `en`/`de`/`es` dell'informativa privacy e delle FAQ non sono state riviste da un madrelingua o da un legale; le Blade che usano `x-layouts.app` con `:title` / `:meta-description` e `x-ui.input` con `:label` sono solo compilate, non rese; la sezione `home-improved` ha commenti Blade `{{-- ... —}}` chiusi male (trattino lungo): non toccati.
