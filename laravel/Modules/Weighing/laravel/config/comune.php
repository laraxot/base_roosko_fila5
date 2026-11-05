<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurazione Comune
    |--------------------------------------------------------------------------
    |
    | Configurazione per il tema comunale e le informazioni del comune
    |
    */

    'nome' => env('COMUNE_NOME', 'Nome Comune'),
    'codice_istat' => env('COMUNE_CODICE_ISTAT', '000000'),
    'cap' => env('COMUNE_CAP', '00000'),
    'provincia' => env('COMUNE_PROVINCIA', 'Provincia'),
    'regione' => env('COMUNE_REGIONE', 'Regione'),
    'sindaco' => env('COMUNE_SINDACO', 'Nome Sindaco'),
    'indirizzo' => env('COMUNE_INDIRIZZO', 'Via, 1'),
    'telefono' => env('COMUNE_TELEFONO', '000-0000000'),
    'email' => env('COMUNE_EMAIL', 'info@comune.it'),
    'pec' => env('COMUNE_PEC', 'comune@pec.it'),
    'piva' => env('COMUNE_PIVA', '00000000000'),
    'cf' => env('COMUNE_CF', '00000000000'),
    'lat' => env('COMUNE_LAT', '45.4642'),
    'lng' => env('COMUNE_LNG', '9.1900'),
    'logo' => env('COMUNE_LOGO', '/images/logo-comune.png'),

    'colori' => [
        'primario' => env('COMUNE_COLORE_PRIMARIO', '#0066cc'),
        'secondario' => env('COMUNE_COLORE_SECONDARIO', '#00cc66'),
        'accento' => env('COMUNE_COLORE_ACCENTO', '#ff6600'),
    ],

    'sponsor' => [
        'area_enabled' => env('COMUNE_SPONSOR_AREA_ENABLED', true),
        'area_title' => env('COMUNE_SPONSOR_AREA_TITLE', 'Partner e Sostenitori'),
        'area_description' => env('COMUNE_SPONSOR_AREA_DESCRIPTION', 'Grazie ai nostri partner e sostenitori che rendono possibile questo servizio.'),
        'logos_max' => env('COMUNE_SPONSOR_LOGOS_MAX', 8),
        'logos' => [
            'logo1' => env('COMUNE_SPONSOR_LOGO_1', ''),
            'logo2' => env('COMUNE_SPONSOR_LOGO_2', ''),
            'logo3' => env('COMUNE_SPONSOR_LOGO_3', ''),
            'logo4' => env('COMUNE_SPONSOR_LOGO_4', ''),
            'logo5' => env('COMUNE_SPONSOR_LOGO_5', ''),
            'logo6' => env('COMUNE_SPONSOR_LOGO_6', ''),
            'logo7' => env('COMUNE_SPONSOR_LOGO_7', ''),
            'logo8' => env('COMUNE_SPONSOR_LOGO_8', ''),
        ],
    ],

    'adsense' => [
        'footer_enabled' => env('COMUNE_ADSENSE_FOOTER_ENABLED', true),
        'slots' => [
            'spazio_in_vendita_desk' => env('COMUNE_ADSENSE_SLOT_DESK', 'footer-sponsor-desk'),
            'spazio_in_vendita_mobile' => env('COMUNE_ADSENSE_SLOT_MOBILE', 'footer-sponsor-mobile'),
            'spazio_in_vendita_tablet' => env('COMUNE_ADSENSE_SLOT_TABLET', 'footer-sponsor-tablet'),
        ],
        'labels' => [
            'desk' => env('COMUNE_ADSENSE_LABEL_DESK', 'Spazio in vendita - Desktop'),
            'mobile' => env('COMUNE_ADSENSE_LABEL_MOBILE', 'Spazio in vendita - Mobile'),
            'tablet' => env('COMUNE_ADSENSE_LABEL_TABLET', 'Spazio in vendita - Tablet'),
        ],
    ],

    'social' => [
        'facebook' => env('COMUNE_FACEBOOK', ''),
        'twitter' => env('COMUNE_TWITTER', ''),
        'instagram' => env('COMUNE_INSTAGRAM', ''),
        'youtube' => env('COMUNE_YOUTUBE', ''),
    ],

    'orari' => [
        'lunedi_venerdi' => '8:30 - 12:30',
        'martedi_giovedi' => '14:30 - 16:30',
        'sabato' => '8:30 - 12:30',
        'domenica' => 'Chiuso',
    ],

    'servizi' => [
        'anagrafe' => [
            'nome' => 'Anagrafe',
            'descrizione' => 'Servizi anagrafici e stato civile',
            'icona' => 'user',
            'attivo' => true,
        ],
        'tributi' => [
            'nome' => 'Tributi',
            'descrizione' => 'Pagamento tasse e tributi comunali',
            'icona' => 'credit-card',
            'attivo' => true,
        ],
        'urbanistica' => [
            'nome' => 'Urbanistica',
            'descrizione' => 'Pratiche edilizie e urbanistiche',
            'icona' => 'building',
            'attivo' => true,
        ],
        'sociale' => [
            'nome' => 'Sociale',
            'descrizione' => 'Servizi sociali e assistenziali',
            'icona' => 'heart',
            'attivo' => true,
        ],
        'cultura' => [
            'nome' => 'Cultura',
            'descrizione' => 'Eventi culturali e biblioteca',
            'icona' => 'book',
            'attivo' => true,
        ],
        'ambiente' => [
            'nome' => 'Ambiente',
            'descrizione' => 'Servizi ambientali e rifiuti',
            'icona' => 'leaf',
            'attivo' => true,
        ],
    ],
];
