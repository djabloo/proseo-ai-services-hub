# Piano MVP per applicazione di disegno elettrico (stile EPLAN)

Questo documento propone un approccio **realistico e incrementale** per costruire un'app di disegno elettrico 2D con workflow simile a EPLAN P8, senza puntare subito a una compatibilità totale con formati proprietari.

## Obiettivo

Creare un MVP che copra le funzionalità più utili per ufficio tecnico:

- editor schemi elettrici 2D (simboli, conduttori, morsetti, riferimenti)
- librerie simboli/componenti
- numerazione fili e segnali
- cross-reference base
- esportazione PDF + formati aperti (SVG/JSON/DXF)
- distinta materiali (BOM)

## Cosa evitare in fase MVP

Per ridurre tempi e rischio:

- niente 3D
- niente piena compatibilità nativa con file proprietari EPLAN
- niente macro avanzate multi-variante nella prima release

## Open source da valutare come base

### 1) QElectroTech (desktop)

**Pro**
- già orientato a schemi elettrici
- librerie simboli e progetto mature
- ottimo punto di partenza se il target è desktop tecnico

**Contro**
- personalizzazione UX in stile EPLAN può richiedere lavoro
- stack C++/Qt (non sempre semplice per team web)

### 2) LibreCAD + estensioni

**Pro**
- buon motore CAD 2D
- ecosistema ampio

**Contro**
- non focalizzato su logica elettrotecnica out-of-the-box
- molte funzioni domain-specific andrebbero sviluppate

### 3) Web stack custom (Konva/Fabric.js + backend)

**Pro**
- UX moderna e distribuibile via browser
- integrazione semplice con servizi aziendali

**Contro**
- serve sviluppare da zero molte regole elettrotecniche
- tempi MVP più alti rispetto a base desktop già pronta

## Scenario di mercato (aggiornamento)

È emerso un player già operativo: **cirQit Cloud** (`https://cirqit.cloud/`).

Dal loro materiale pubblico, il focus sembra essere:

- gestione digitale della documentazione di quadro
- collaborazione e revisioni paperless
- editor e consultazione online/mobile
- workflow con CAD esterno (revisione nel CAD e re-import in piattaforma)

### Implicazione pratica

Per evitare un progetto troppo grande, conviene posizionarsi in modo chiaro:

1. **Build completo CAD** → alto sforzo, più anni, rischio elevato
2. **Build “documentation + workflow”** con editor leggero → più rapido e vicino a valore immediato
3. **Approccio ibrido**: integrazione con CAD esterni + valore su processi, audit trail e integrazioni aziendali

Per una prima release è raccomandato il punto **2 o 3**.

## Strategia compatibilità “stile EPLAN”

Invece di inseguire subito import/export nativo proprietario, usare un layer di interoperabilità:

1. **Formato interno canonico** (JSON versionato)
2. **Mappatura attributi** (tag dispositivo, funzione, locazione, produttore)
3. **Import da formati aperti** (DXF/SVG/CSV/BOM)
4. **Template numerazione e naming** configurabili per imitare convenzioni EPLAN

Questo consente di ottenere un'esperienza operativa simile, riducendo blocchi legali/tecnici.

## Build vs Buy (decisione rapida)

Prima di iniziare sviluppo massivo, fare un mini-assessment (1 settimana):

- validare se cirQit copre già il 60-80% dei bisogni reali
- quantificare gap critici (template, naming, permessi, export, integrazioni)
- stimare costo customizzazione vs costo sviluppo interno

Decisione consigliata:

- **se gap basso**: usare piattaforma esistente + estensioni
- **se gap medio/alto su IP di processo**: MVP proprietario focalizzato su differenziazione
- **se gap tecnico CAD puro**: evitare full-CAD iniziale, puntare su interoperabilità

## Modello dati minimo consigliato

Entità principali:

- `Project`
- `Page`
- `Symbol`
- `Connection`
- `Terminal`
- `Device`
- `Cable`
- `PLCPoint`
- `BOMItem`

Attributi essenziali:

- codice componente
- funzione
- locazione
- articolo produttore
- revisione
- metadati utente

## Roadmap suggerita (8 settimane)

### Settimane 1-2
- scelta base tecnica (fork QElectroTech **oppure** web MVP)
- definizione schema dati
- prototipo canvas e palette simboli

### Settimane 3-4
- wiring + snapping + griglia
- proprietà simboli
- salvataggio progetto

### Settimane 5-6
- numerazione fili
- cross-reference base
- BOM v1

### Settimane 7-8
- export PDF/SVG/JSON
- import CSV componenti
- test con 2-3 progetti reali

## Librerie simboli aperte: approccio pratico

Le librerie pubbliche spesso hanno qualità eterogenea. Conviene:

1. creare un **catalogo interno validato**
2. introdurre regole QA su simboli (ancore, pin, naming)
3. mantenere mapping tra simboli equivalenti di vendor diversi

## Decisione raccomandata

Se l'obiettivo è andare live presto con sforzo contenuto:

- partire da una base open source elettrica (es. QElectroTech)
- aggiungere un layer UI/processo “simile EPLAN”
- rinviare macro avanzate e compatibilità proprietaria completa a fase 2
