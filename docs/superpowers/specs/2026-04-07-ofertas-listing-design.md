# Design: Ofertas Listing Page Redesign

**Date:** 2026-04-07
**Status:** Approved

## Goal

Redesign `admin.ofertas.listar.blade.php` to match the visual style of `admin.conceptos.listar.blade.php` and eliminate horizontal scroll by consolidating table columns.

## Problem

The current ofertas listing table has 10 columns with `min-width: 1100px`, requiring horizontal scroll. The visual style uses generic Bootstrap cards instead of the polished design used in conceptos.

## Design Decisions

### Column Structure: 9 columns (from 10)

| # | Column | Content | Width |
|---|--------|---------|-------|
| 1 | N° | Row number | 35px |
| 2 | Oferta | Código + Programa + Sede + Gestión + Versión + Grupo | Auto |
| 3 | Mód. | Number of modules (badge) | 45px |
| 4 | Convenio | Image + Sigla + Nombre | 90px |
| 5 | Modalidad | Badge | 70px |
| 6 | Fechas | Start/End + Inscription date | 75px |
| 7 | Inscr. | Enrolled + Pre-enrolled | 55px |
| 8 | Fase | Phase badge with color | 55px |
| 9 | Acciones | Action buttons (28x28px) | 130px |

### Consolidation

- **Código** merged into Oferta column (displayed as monospace badge)
- **Programa** is the main text in Oferta column
- **Sede/Sucursal** shown as secondary text in Oferta column
- **Gestión/Versión/Grupo** shown as tertiary text in Oferta column
- **Convenio** keeps its own column with image + sigla + name

### Visual Style

- Gradient teal header (`#0f766e` → `#0d5f59`) replacing breadcrumb
- Compact filter bar with rounded inputs
- Table card with rounded corners, subtle border, shadow
- Hover animation on rows
- Color-coded action buttons (28x28px squares with rounded corners)
- Styled pagination footer
- Fonts: Outfit (headings), DM Sans (body)

### Files Modified

1. `admin.ofertas.listar.blade.php` — Main layout with inline styles
2. `admin.ofertas/partials/filtros.blade.php` — Compact filter bar
3. `admin.ofertas/partials/tabla-resultados.blade.php` — New table structure
4. `admin.ofertas/partials/fila-oferta.blade.php` — Consolidated row layout
5. `admin.ofertas/partials/estilos.blade.php` — Updated CSS

### No Functional Changes

All existing functionality (filters, modals, AJAX, pagination, actions) remains unchanged. Only visual design and column layout are modified.
