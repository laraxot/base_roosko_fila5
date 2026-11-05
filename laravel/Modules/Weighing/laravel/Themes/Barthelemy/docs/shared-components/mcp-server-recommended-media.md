---
title: "MCP Server Recommended Media"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
qmd: "mcp server recommended media"
issues: []
discussions: []
status: superseded
superseded_by: mcp-server-consigliati.md
---

# Server MCP consigliati per il modulo Media

## Scopo del modulo
Gestione, manipolazione e automazione di file e media.

## Server MCP consigliati
- **filesystem**: Per leggere/scrivere file, gestire asset e media.
- **fetch**: Per recuperare dati o file da fonti esterne.
- **memory**: Per mantenere stato tra operazioni di manipolazione file/media.
- **everything**: Per avere tutte le funzionalità MCP disponibili.

## Esempio di configurazione MCP
```json
{
  "mcpServers": {
    "filesystem": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-filesystem"] },
    "fetch": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-fetch"] },
    "memory": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-memory"] },
    "everything": { "command": "npx", "args": ["-y", "@modelcontextprotocol/server-everything"] }
  }
}
```

**Nota:**
Aggiungi solo i server che realmente ti servono per il tuo workflow. 