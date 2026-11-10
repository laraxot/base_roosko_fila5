# Modulo Weighing

Pesature generiche: anagrafica delle bilance (pesa a ponte, pianale, gru, carrello) e ticket di pesata collegabili a qualunque modello tramite il morph `weighable`. Per le bilance a pianale c'è anche la pesata singola. Il modulo non conosce FIR né conferimenti, e Roosko collega i ticket ai propri modelli.

Dipende solo da Xot (e User per le policy). Non aggiunge dipendenze composer.

## Modelli (connessione `weighing`)

| Modello | Tabella | Note |
|---|---|---|
| `Scale` | `scales` | `type` (`ScaleTypeEnum`), `driver` (`ScaleDriverEnum`: `manual`, `tcp_ascii`, `fake`), `host`/`port`, `max_capacity_kg`, `resolution_kg`, `verified_until`, `is_active` |
| `Weighing` | `weighings` | `ticket_number` `W-{anno}-{progr. 6 cifre}`; `direction`, `status`, targhe, `gross_kg`/`tare_kg`/`net_kg`, timestamp per fase, `voided_at`/`void_reason`, `raw_readings` (json), `notes` |
| `WeighingSequence` | `weighing_sequences` | contatore annuale dei ticket (`year` unique, `last_number`) |

I pesi sono in kg `decimal(14,3)` con cast `decimal:3`. Si leggono come float con `grossKg()`, `tareKg()` e `netKg()`, e si scrivono con `Weighing::kgToStorage()`.

Un ticket chiuso o annullato non si modifica: la guardia è in `Weighing::booted()` e lancia `WeighingLockedException`. Su un ticket bloccato restano modificabili solo stato e dati di annullamento, il collegamento `weighable` e le note. I ticket chiusi o annullati non si cancellano.

## Azioni

| Azione | Firma |
|---|---|
| `OpenWeighingAction` | `execute(WeighingDirectionEnum $direction, float $firstKg, ?Scale $scale = null, ?string $vehiclePlate = null, ?Model $weighable = null, ?string $rawReading = null): Weighing` |
| `CloseWeighingAction` | `execute(Weighing $weighing, float $secondKg, ?string $rawReading = null): Weighing` |
| `RecordSingleWeighingAction` | `execute(float $netKg, ?Scale $scale = null, ?Model $weighable = null, ?string $rawReading = null): Weighing` |
| `ReadScaleAction` | `execute(Scale $scale): ScaleReadingData` |
| `AttachWeighingAction` | `execute(Weighing $weighing, Model $weighable): Weighing` |
| `VoidWeighingAction` | `execute(Weighing $weighing, string $reason): Weighing` |
| `CalculateNetWeightAction` | `execute(float $grossKg, float $tareKg): float` (pura, rifiuta un netto negativo) |
| `ParseScaleReadingAction` | `execute(string $raw, ?CarbonImmutable $readAt = null): ScaleReadingData` (pura) |
| `GenerateWeighingTicketNumberAction` | `execute(?CarbonImmutable $at = null): string` |
| `EnsureScaleUsableAction` | `execute(Scale $scale, ?float $kg = null): void` |
| `NormalizeVehiclePlateAction` | `execute(?string $plate): ?string` |
| `EnsureScaleAddressAllowedAction` | `execute(?string $host, int $port): string` (IP a cui collegarsi; form bilancia e driver TCP) |

Il parametro finale `?string $rawReading` è un'aggiunta compatibile con le firme della SPEC. Contiene la riga grezza ricevuta dall'indicatore e viene salvato in `raw_readings`, insieme a fase, kg, bilancia e data.

Regole delle pesate:
- `inbound`: prima pesata = lordo, seconda = tara.
- `outbound`: prima pesata = tara, seconda = lordo.
- `internal`: come `inbound`.
- Pesata singola: direzione `internal`, ticket già chiuso, lordo = netto, tara = 0.

Il numero di ticket si genera nella stessa transazione che salva la pesata. Il contatore dell'anno si crea o si incrementa con **una sola istruzione** (`upsert`: `INSERT … ON DUPLICATE KEY UPDATE last_number = last_number + 1`), che prende subito il lock esclusivo sulla riga; il valore si rilegge nella stessa transazione. Due pesate concorrenti non hanno lo stesso numero e un rollback non lascia buchi. Il vecchio `insertOrIgnore` + `SELECT … FOR UPDATE` andava in deadlock con pesate concorrenti (lock condiviso da convertire in esclusivo). `OpenWeighingAction` e `RecordSingleWeighingAction` ripetono la transazione fino a 3 volte sugli errori di concorrenza quando sono la transazione esterna; dentro una transazione aperta da un altro modulo (es. `RunInPlantTransactionAction` di Roosko) il retry spetta a chi la apre.

Le pesate si registrano solo su bilance attive, con verificazione periodica valida (`verified_until`, disattivabile con `weighing.require_valid_verification`) ed entro la portata massima. Con bilancia `null` il peso è digitato e non c'è nessun controllo sullo strumento.

## Driver

Il driver si sceglie con `ScaleDriverEnum::driverClass()` e implementa `ScaleDriverContract::read(Scale): ScaleReadingData`.

- **`TcpAsciiScaleDriver`**:
  1. controlla l'indirizzo con `EnsureScaleAddressAllowedAction` (rete locale o `allowed_scale_hosts`, porta in `scale_ports`) e apre un socket TCP verso l'IP controllato, con porta di default `tcp_ascii.default_port` e timeout di connessione da config;
  2. invia l'eventuale `request_command` (indicatori che rispondono solo a richiesta);
  3. legge fino a `max_lines` righe entro `read_timeout` e restituisce la prima lettura stabile, altrimenti l'ultima lettura valida.

  Gli errori diventano eccezioni di dominio:
  - host mancante o connessione rifiutata: `ScaleConnectionException`;
  - nessun dato: `ScaleTimeoutException`;
  - solo righe illeggibili: `ScaleReadingParseException`;
  - indirizzo o porta non consentiti: `ScaleAddressNotAllowedException`.

  I messaggi all'operatore non riportano mai il testo ricevuto dal dispositivo né l'errore del socket (vanno nel log): il gestionale non deve diventare uno strumento per leggere banner di servizi interni.
- **`ParseScaleReadingAction`** interpreta la riga:
  - prende l'ultimo numero (con segno e decimali, virgola o punto) che soddisfa `weight_pattern`, che ha il gruppo `weight`;
  - la lettura è stabile se la riga contiene `stable_marker` (default `ST`);
  - il valore viene convertito in kg con `unit_factor`.

  I separatori delle migliaia (`1.234,5`) non sono supportati.
- **`ManualScaleDriver`** lancia `ScaleUnavailableException`: il peso si digita.
- **`FakeScaleDriver`** restituisce `config('weighing.fake.weight_kg')` e `fake.stable`.

## Filament

Il pannello è `weighing::admin`.

- **`ScaleResource`** (CRUD): la pagina di dettaglio ha l'header action "Leggi bilancia" (`ReadScaleHeaderAction`).
- **`WeighingResource`**: solo lista e dettaglio. I ticket si aprono, chiudono e annullano solo con le header actions, che chiamano le Actions di dominio:
  - nella lista, "Nuova pesata" e "Pesata singola";
  - nel dettaglio, "Leggi bilancia", "Seconda pesata" e "Annulla ticket".
- **`ReadScaleFieldAction`** è la suffix action dei campi peso. Legge la bilancia scelta nel campo `scale_id`, compila il campo e scrive la riga grezza nel campo nascosto `raw_reading`. È riusabile in altri form (ad esempio i conferimenti di Roosko).

Le due azioni "Leggi bilancia" prendono i testi dal lang di Weighing (`read_scale_header.php`, `read_scale_field.php`), così si presentano uguali in ogni modulo che le usa. Le altre etichette arrivano dai file lang per classe (`{model}.php`, `{model}_form.php`, `{model}_infolist.php`, `{models}.php`, `*_enum.php`), in it ed en.

## Punti di estensione (config `weighing`)

- `tcp_ascii.*`: timeout, righe massime, comando di richiesta, porta di default, regex del peso, marcatore di stabilità, fattore di unità.
- `fake.weight_kg|stable`: la lettura simulata.
- `allowed_scale_hosts` (env `WEIGHING_ALLOWED_SCALE_HOSTS`, separati da virgola, letta via `config('env.*')`: la chiave va aggiunta a `config/env.php` della base): indirizzi a cui il server può collegarsi. Vuota = solo IP privati di LAN (10/8, 172.16/12, 192.168/16, fc00::/7) e hostname che si risolvono lì. Valorizzata = solo IP, CIDR, hostname esatti o `*.dominio` elencati. Metadata cloud, link-local, 0.0.0.0/8 e multicast sempre vietati.
- `scale_ports.min|max`: porte ammesse per gli indicatori (default 1024–65535).
- `require_valid_verification`: obbligo di verificazione periodica valida.
- `read_rate_limit`: letture "Leggi bilancia" al minuto (per IP e azione).

## Requisiti del progetto

- La connessione database `weighing` la crea il modulo Tenant, copiando la connessione predefinita per ogni modulo attivo. Va definita a mano in `config/{tenant}/database.php` solo se il modulo deve usare un database diverso.
- Test: `tests/TestCase.php` usa lo sqlite condiviso di Xot e crea le tabelle mancanti con `XotModuleSchema::ensure('Weighing')`. Il test di lettura TCP con dati reali richiede `pcntl` e altrimenti viene saltato.
- Permessi Spatie usati dalle policy: `scale.*` (`viewAny`, `view`, `create`, `update`, `delete`, `readScale`) e `weighing.*` (`viewAny`, `view`, `create`, `update`, `void`). I ticket non si cancellano. Li crea `WeighingPermissionSeeder` (idempotente, guard `web`, richiamato da `WeighingDatabaseSeeder`) insieme al ruolo `weighing::admin` (nome = id del pannello) con tutti i permessi del modulo.

## Autorizzazione

- `ScalePolicy` e `WeighingPolicy` usano `DeniesUndeclaredFilamentAbilities` (Xot): eliminazione massiva, replica, riordino, collegamenti… negati se non dichiarati.
- Le azioni custom controllano un'abilità, non solo lo stato del ticket:

| Azione | Abilità | Permesso |
|---|---|---|
| Leggi bilancia (`ReadScaleHeaderAction`, `ReadScaleFieldAction`, anche in altri moduli) | `readScale` su `Scale` (anche prima della lettura) | `scale.readScale` |
| Nuova pesata, pesata singola | `create` | `weighing.create` |
| Seconda pesata (chiusura) | `update` | `weighing.update` |
| Annulla ticket | `void` | `weighing.void` |

- "Leggi bilancia" ha `rateLimit` (`weighing.read_rate_limit`, default 30 al minuto per IP e azione).
