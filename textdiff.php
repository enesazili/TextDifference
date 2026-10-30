<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Text Diff — metin karşılaştırma</title>
    <style>
:root {
    --bg: #fbf7ed;
    --surface: #f2ead8;
    --surface-raised: #f6efe0;
    --ink: #282828;
    --ink-soft: #46433d;
    --muted: #6b665c;
    --line: #ded5c3;
    --line-strong: #c8bca6;
    --accent: #a4481f;
    --accent-soft: #f0dccb;
    --olive: #5b6a3d;
    --focus: #8d3a16;
    --selection: #e9d3bf;
    --font-sans: "Segoe UI", system-ui, -apple-system, "Helvetica Neue", Arial, sans-serif;
    --font-serif: "Iowan Old Style", "Palatino Linotype", Palatino, "Book Antiqua", Georgia, serif;
    --font-mono: "Cascadia Mono", ui-monospace, SFMono-Regular, Consolas, "Liberation Mono", monospace;
    --container: 1160px;
    --gutter: clamp(1.25rem, 4vw, 2.5rem);
    --radius: 2px;
    --ease: cubic-bezier(.2, .6, .2, 1);
    --speed: 180ms;
    color-scheme: light;
}

@media (prefers-color-scheme: dark) {
    :root {
        --bg: #0d1117;
        --surface: #161b22;
        --surface-raised: #1b222c;
        --ink: #e6edf3;
        --ink-soft: #c9d1d9;
        --muted: #9aa4af;
        --line: #30363d;
        --line-strong: #3d444d;
        --accent: #79b8ff;
        --accent-soft: #16283c;
        --olive: #8fbf7f;
        --focus: #a5d6ff;
        --selection: #1f3a56;
        color-scheme: dark;
    }
}

*, *::before, *::after { box-sizing: border-box; }
html { -webkit-text-size-adjust: 100%; }
body {
    margin: 0;
    background: var(--bg);
    color: var(--ink);
    font-family: var(--font-sans);
    font-size: 1rem;
    line-height: 1.65;
    -webkit-font-smoothing: antialiased;
}
main { display: block; }
h1, h2, h3 { color: var(--ink); text-wrap: balance; }
p { margin-top: 0; }
a { color: var(--accent); text-decoration-thickness: 1px; text-underline-offset: .18em; }
[hidden] { display: none !important; }
::selection { background: var(--selection); color: var(--ink); }

.container {
    width: 100%;
    max-width: var(--container);
    margin-inline: auto;
    padding-inline: var(--gutter);
}

.section { padding-block: clamp(2.25rem, 5vw, 3.75rem); }

.row { display: flex; flex-wrap: wrap; margin-inline: -.75rem; }
.row > * { width: 100%; padding-inline: .75rem; }

@media (min-width: 992px) {
    .col-lg-4 { width: 33.3333%; }
    .col-lg-5 { width: 41.6667%; }
    .col-lg-7 { width: 58.3333%; }
    .col-lg-8 { width: 66.6667%; }
    .offset-lg-1 { margin-left: 8.3333%; }
}

.section__label {
    margin-bottom: 1.15rem;
    color: var(--muted);
    font-family: var(--font-mono);
    font-size: .67rem;
    letter-spacing: .14em;
    line-height: 1.5;
    text-transform: uppercase;
}

.section__num { margin-right: .45rem; color: var(--accent); }

.section__title {
    margin: 0 0 1.25rem;
    font-family: var(--font-serif);
    font-size: clamp(2rem, 3.8vw, 3rem);
    font-weight: 400;
    line-height: 1.06;
    letter-spacing: -.03em;
}

.section__intro {
    max-width: 36rem;
    margin-bottom: 1.5rem;
    color: var(--ink-soft);
    font-size: .98rem;
    line-height: 1.7;
}

.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}

:where(a, button, [tabindex]):focus-visible {
    outline: 2px solid var(--focus);
    outline-offset: 3px;
}

@media (prefers-reduced-motion: reduce) {
    *, *::before, *::after {
        animation-duration: .01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: .01ms !important;
    }
}
/* ==========================================================================
   Tarayıcı araçları — Image Toolkit, Dev Toolbox, Text Diff, CSV, QR Studio
   Renkler site.css tokenlarından gelir; iki tema da desteklenir.
   ========================================================================== */

:root {
    --tool-add-bg: #e6efdb;
    --tool-add-ink: #2f4a1f;
    --tool-del-bg: #f7e2d8;
    --tool-del-ink: #7a2c10;
    --tool-change-bg: #f4e8c8;
    --tool-change-ink: #6b4a0f;
    --tool-warn-bg: #f6ecd2;
    --tool-warn-line: #a56a14;
}

[data-theme="dark"] {
    --tool-add-bg: #14261a;
    --tool-add-ink: #b8e6b0;
    --tool-del-bg: #2b1512;
    --tool-del-ink: #ffc2ad;
    --tool-change-bg: #2a2311;
    --tool-change-ink: #f0d491;
    --tool-warn-bg: #2a2311;
    --tool-warn-line: #d4a24a;
}

.site-nav--tool .site-nav__list {
    gap: 1.5rem;
}

.site-nav__label {
    display: inline-flex;
    align-items: center;
    min-height: 2.75rem;
    color: var(--muted);
    font-family: var(--font-mono);
    font-size: .72rem;
    letter-spacing: .08em;
    text-transform: uppercase;
}

.tool .section__intro {
    max-width: 34rem;
}

.tool [hidden] {
    display: none !important;
}

/* Bilgi blokları --------------------------------------------------------- */

.tool-notice {
    margin-top: 2rem;
    padding: 1.35rem 1.4rem;
    border: 1px solid var(--line);
    border-left: 2px solid var(--accent);
    background: var(--surface-raised);
}

.tool-notice__title {
    margin: 0 0 .75rem;
    font-family: var(--font-mono);
    font-size: .74rem;
    font-weight: 600;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: var(--muted);
}

.tool-notice__list {
    margin: 0;
    padding-left: 1.1rem;
    color: var(--ink-soft);
    font-size: .88rem;
    line-height: 1.6;
}

.tool-notice__list li + li {
    margin-top: .3rem;
}

.tool-meta {
    margin-top: 2rem;
}

.tool-meta__title {
    margin: 0 0 .6rem;
    font-family: var(--font-mono);
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .09em;
    text-transform: uppercase;
    color: var(--muted);
}

.tool-meta__line {
    margin: 0 0 .35rem;
    color: var(--ink-soft);
    font-size: .86rem;
    line-height: 1.55;
}

.tool-meta__key {
    display: inline-block;
    min-width: 7.5rem;
    color: var(--muted);
    font-family: var(--font-mono);
    font-size: .72rem;
    letter-spacing: .05em;
    text-transform: uppercase;
}

.tool-notes {
    margin: 0;
    padding-left: 1.1rem;
    color: var(--muted);
    font-size: .82rem;
    line-height: 1.6;
}

.tool-notes li + li {
    margin-top: .25rem;
}

.tool-links {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem 1.75rem;
    margin-top: 1.6rem;
}

.tool-noscript {
    margin-top: 1rem;
    color: var(--muted);
    font-size: .82rem;
    line-height: 1.5;
}

/* Paneller, alanlar ve düğmeler ----------------------------------------- */

.tool-panel {
    padding: clamp(1.15rem, 3vw, 1.9rem);
    border: 1px solid var(--line);
    background: var(--surface-raised);
}

.tool-panel + .tool-panel {
    margin-top: 1.25rem;
}

.tool-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem 1.25rem;
}

.tool-grid--three {
    grid-template-columns: repeat(3, minmax(0, 1fr));
}

.tool-field {
    min-width: 0;
    margin-bottom: 1.1rem;
}

.tool-grid .tool-field {
    margin-bottom: 0;
}

.tool-label,
.tool-legend {
    display: block;
    margin-bottom: .4rem;
    font-family: var(--font-mono);
    font-size: .72rem;
    font-weight: 600;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--muted);
}

.tool-field__hint,
.tool-hint {
    margin: .45rem 0 0;
    color: var(--muted);
    font-size: .8rem;
    line-height: 1.5;
}

.tool-input,
.tool-select,
.tool-textarea {
    width: 100%;
    min-height: 2.75rem;
    padding: .55rem .7rem;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius);
    background: var(--bg);
    color: var(--ink);
    font: inherit;
    font-size: .92rem;
}

.tool-textarea {
    min-height: 10rem;
    resize: vertical;
    font-family: var(--font-mono);
    font-size: .84rem;
    line-height: 1.55;
}

.tool-textarea--short {
    min-height: 6rem;
}

.tool-input:focus-visible,
.tool-select:focus-visible,
.tool-textarea:focus-visible,
.column-toggle input:focus-visible {
    outline: 2px solid var(--focus);
    outline-offset: 2px;
}

.tool-input:disabled,
.tool-select:disabled,
.tool-textarea:disabled {
    opacity: .6;
}

.tool-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .6rem 1rem;
}

.tool-check {
    display: inline-flex;
    align-items: center;
    gap: .55rem;
    min-height: 2.75rem;
    color: var(--ink-soft);
    font-size: .86rem;
}

.tool-check input {
    width: 1.1rem;
    height: 1.1rem;
    accent-color: var(--accent);
}

.tool-actions {
    display: flex;
    flex-wrap: wrap;
    gap: .75rem;
    margin-top: 1.3rem;
}

.button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-height: 2.75rem;
    padding: .55rem 1.15rem;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius);
    background: transparent;
    color: var(--ink);
    font: inherit;
    font-size: .86rem;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: background var(--speed) var(--ease), border-color var(--speed) var(--ease), color var(--speed) var(--ease);
}

.button--primary {
    border-color: var(--accent);
    background: var(--accent);
    color: var(--bg);
}

.button--quiet {
    color: var(--ink-soft);
}

.button:disabled {
    cursor: not-allowed;
    opacity: .55;
}

@media (hover: hover) {
    .button:not(:disabled):hover {
        border-color: var(--accent);
        background: var(--surface);
        color: var(--accent);
    }

    .button--primary:not(:disabled):hover {
        background: var(--ink);
        border-color: var(--ink);
        color: var(--bg);
    }
}

.button:focus-visible,
.dev-tab:focus-visible {
    outline: 2px solid var(--focus);
    outline-offset: 2px;
}

.tool-status {
    min-height: 1.4rem;
    margin: 1rem 0 0;
    color: var(--ink-soft);
    font-size: .86rem;
    line-height: 1.5;
}

.tool-status.is-error {
    color: var(--accent);
}

.tool-empty {
    margin: 1rem 0 0;
    padding: 1.25rem;
    border: 1px dashed var(--line-strong);
    color: var(--muted);
    font-size: .88rem;
    line-height: 1.55;
    text-align: center;
}

.tool-warning {
    margin: 1rem 0 0;
    padding: .8rem 1rem;
    border-left: 2px solid var(--tool-warn-line);
    background: var(--tool-warn-bg);
    color: var(--ink-soft);
    font-size: .84rem;
    line-height: 1.55;
}

.tool-output {
    min-height: 7rem;
    max-height: 24rem;
    margin: 0;
    padding: .9rem 1rem;
    overflow: auto;
    border: 1px solid var(--line);
    background: var(--bg);
    color: var(--ink);
    font-family: var(--font-mono);
    font-size: .82rem;
    line-height: 1.55;
    white-space: pre-wrap;
    overflow-wrap: anywhere;
}

.tool-output:empty::before {
    content: "Sonuç burada görünecek.";
    color: var(--muted);
}

/* Dosya bırakma ---------------------------------------------------------- */

.dropzone {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: .35rem;
    min-height: 10rem;
    padding: 1.6rem 1.25rem;
    border: 1px dashed var(--line-strong);
    border-radius: var(--radius);
    background: var(--bg);
    text-align: center;
    cursor: pointer;
    transition: border-color var(--speed) var(--ease), background var(--speed) var(--ease);
}

.dropzone:hover,
.dropzone:focus-within {
    border-color: var(--accent);
    background: var(--surface);
}

.dropzone.is-dragover {
    border-style: solid;
    border-color: var(--accent);
    background: var(--accent-soft);
}

.dropzone__icon {
    font-family: var(--font-mono);
    font-size: 1.4rem;
    color: var(--accent);
}

.dropzone__title {
    font-size: .98rem;
    font-weight: 600;
    color: var(--ink);
}

.dropzone__hint {
    max-width: 26rem;
    color: var(--muted);
    font-size: .82rem;
    line-height: 1.5;
}

.dropzone:focus-within {
    outline: 2px solid var(--focus);
    outline-offset: 2px;
}

/* Image Toolkit ---------------------------------------------------------- */

.image-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr);
    gap: 1.25rem;
}

.image-preview-wrap {
    padding: 1rem;
    border: 1px solid var(--line);
    background: var(--bg);
}

.image-preview {
    display: block;
    width: 100%;
    max-width: 34rem;
    height: auto;
    min-height: 9rem;
    margin: 0 auto;
    border: 1px solid var(--line);
    background: var(--surface);
}

.image-preview-note {
    margin: .7rem 0 0;
    color: var(--muted);
    font-size: .8rem;
    line-height: 1.45;
    text-align: center;
}

.image-list {
    margin: 1rem 0 0;
    padding: 0;
    list-style: none;
    border-top: 1px solid var(--line);
}

.image-list:empty {
    border-top: 0;
}

.image-item {
    padding: .9rem .1rem;
    border-bottom: 1px solid var(--line);
}

.image-item__main {
    display: flex;
    align-items: flex-start;
    gap: .9rem;
}

.image-item__text {
    flex: 1 1 auto;
    min-width: 0;
}

.image-thumb {
    flex: 0 0 auto;
    max-width: 104px;
    max-height: 104px;
    border: 1px solid var(--line);
    background: var(--surface);
}

.image-item__name {
    margin: 0;
    overflow-wrap: anywhere;
    font-size: .92rem;
    font-weight: 600;
}

.image-item__meta {
    margin: .25rem 0 0;
    color: var(--muted);
    font-family: var(--font-mono);
    font-size: .72rem;
    line-height: 1.45;
}

.image-item__state {
    margin: .4rem 0 0;
    color: var(--ink-soft);
    font-size: .82rem;
    line-height: 1.45;
}

.image-item__state.is-error {
    color: var(--accent);
}

.image-item__actions {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin-top: .5rem;
}

.image-item__remove {
    min-height: 2.75rem;
    padding: .3rem .65rem;
    border: 1px solid transparent;
    border-radius: var(--radius);
    background: transparent;
    color: var(--muted);
    font: inherit;
    font-size: .78rem;
    cursor: pointer;
}

.image-item__remove:hover,
.image-item__remove:focus-visible {
    border-color: var(--line-strong);
    color: var(--ink);
}

.image-item__remove:focus-visible {
    outline: 2px solid var(--focus);
    outline-offset: 2px;
}

.image-item__result {
    margin: .55rem 0 0;
}

.image-download {
    display: inline-flex;
    align-items: center;
    min-height: 2.75rem;
    color: var(--ink);
    font-size: .86rem;
    font-weight: 600;
    text-decoration: underline;
    text-decoration-color: var(--line-strong);
    text-underline-offset: .25em;
}

.image-download:focus-visible {
    outline: 2px solid var(--focus);
    outline-offset: 2px;
}

/* Dev Toolbox ------------------------------------------------------------ */

.dev-tabs {
    display: flex;
    flex-wrap: wrap;
    gap: .35rem;
    margin: 0 0 1.1rem;
    padding: 0;
    list-style: none;
    border-bottom: 1px solid var(--line);
}

.dev-tab {
    min-height: 2.75rem;
    padding: .5rem .85rem;
    border: 0;
    border-bottom: 2px solid transparent;
    background: transparent;
    color: var(--muted);
    font: inherit;
    font-size: .82rem;
    font-weight: 600;
    cursor: pointer;
}

.dev-tab[aria-selected="true"] {
    border-bottom-color: var(--accent);
    color: var(--ink);
}

.dev-panel {
    min-width: 0;
}

.dev-panel__title {
    margin: 0 0 .5rem;
    font-family: var(--font-serif);
    font-size: 1.3rem;
}

.dev-panel__intro {
    margin: 0 0 1rem;
    color: var(--ink-soft);
    font-size: .86rem;
    line-height: 1.55;
}

.dev-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.dev-output-label {
    display: block;
    margin: 1rem 0 .4rem;
    font-family: var(--font-mono);
    font-size: .7rem;
    font-weight: 600;
    letter-spacing: .07em;
    text-transform: uppercase;
    color: var(--muted);
}

.dev-jwt-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

/* Text Diff -------------------------------------------------------------- */

.diff-layout {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.diff-summary {
    margin: 1rem 0 0;
    padding: .75rem 1rem;
    border-left: 2px solid var(--accent);
    background: var(--surface);
    color: var(--ink-soft);
    font-size: .86rem;
    line-height: 1.5;
}

.diff-summary.is-error {
    color: var(--accent);
}

.diff-scroll {
    max-height: 34rem;
    margin-top: 1rem;
    overflow: auto;
    border: 1px solid var(--line);
    background: var(--bg);
}

.diff-table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-mono);
    font-size: .78rem;
    line-height: 1.5;
}

.diff-table caption {
    padding: .55rem .7rem;
    border-bottom: 1px solid var(--line);
    color: var(--muted);
    font-family: var(--font-sans);
    font-size: .78rem;
    text-align: left;
}

.diff-table th {
    position: sticky;
    top: 0;
    z-index: 1;
    padding: .45rem .55rem;
    border-bottom: 1px solid var(--line-strong);
    background: var(--surface);
    color: var(--muted);
    font-family: var(--font-mono);
    font-size: .68rem;
    font-weight: 600;
    letter-spacing: .05em;
    text-align: left;
    text-transform: uppercase;
    white-space: nowrap;
}

.diff-table td {
    padding: .38rem .55rem;
    border-bottom: 1px solid var(--line);
    vertical-align: top;
}

.diff-table__number {
    width: 4.2rem;
    color: var(--muted);
    text-align: right;
    white-space: nowrap;
}

.diff-table__status {
    width: 3.2rem;
    font-weight: 700;
    text-align: center;
}

.diff-table__text {
    overflow-wrap: anywhere;
    white-space: pre-wrap;
}

.diff-row--insert {
    background: var(--tool-add-bg);
    color: var(--tool-add-ink);
}

.diff-row--delete {
    background: var(--tool-del-bg);
    color: var(--tool-del-ink);
}

.diff-row--change {
    background: var(--tool-change-bg);
    color: var(--tool-change-ink);
}

.diff-row--equal {
    color: var(--ink-soft);
}

.diff-line {
    display: block;
}

.diff-line--removed::before {
    content: "− ";
    font-weight: 700;
}

.diff-line--added::before {
    content: "+ ";
    font-weight: 700;
}

.diff-line--removed {
    text-decoration: line-through;
    text-decoration-thickness: 1px;
}

.diff-empty {
    padding: 1.25rem !important;
    color: var(--muted);
    text-align: center;
}

/* CSV Toolkit ------------------------------------------------------------ */

.csv-controls {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 1rem;
    margin-top: 1.1rem;
}

.csv-columns {
    display: flex;
    flex-wrap: wrap;
    gap: .2rem .9rem;
    max-height: 10rem;
    margin: .2rem 0 0;
    padding: .5rem .65rem;
    overflow: auto;
    border: 1px solid var(--line);
    background: var(--bg);
}

.column-toggle {
    display: inline-flex;
    align-items: center;
    gap: .45rem;
    min-height: 2.75rem;
    color: var(--ink-soft);
    font-size: .8rem;
    overflow-wrap: anywhere;
}

.column-toggle input {
    flex: 0 0 auto;
    width: 1rem;
    height: 1rem;
    accent-color: var(--accent);
}

.csv-scroll {
    max-height: 32rem;
    margin-top: 1rem;
    overflow: auto;
    border: 1px solid var(--line);
    background: var(--bg);
}

.csv-table {
    width: 100%;
    border-collapse: collapse;
    font-family: var(--font-mono);
    font-size: .76rem;
    line-height: 1.45;
}

.csv-table caption {
    padding: .55rem .7rem;
    border-bottom: 1px solid var(--line);
    color: var(--muted);
    font-family: var(--font-sans);
    font-size: .78rem;
    text-align: left;
}

.csv-table th {
    position: sticky;
    top: 0;
    z-index: 1;
    padding: .5rem .55rem;
    border-bottom: 1px solid var(--line-strong);
    background: var(--surface);
    color: var(--ink-soft);
    font-family: var(--font-mono);
    font-size: .7rem;
    font-weight: 600;
    text-align: left;
    white-space: nowrap;
}

.csv-table td {
    max-width: 24rem;
    padding: .42rem .55rem;
    border-bottom: 1px solid var(--line);
    overflow-wrap: anywhere;
    vertical-align: top;
    white-space: pre-wrap;
}

.csv-table__line {
    width: 7rem;
    color: var(--muted);
    white-space: nowrap;
}

.csv-row.is-duplicate {
    background: var(--tool-change-bg);
    color: var(--tool-change-ink);
}

.csv-duplicate-badge {
    display: inline-block;
    padding: .05rem .3rem;
    border: 1px solid currentColor;
    border-radius: var(--radius);
    font-size: .62rem;
    font-weight: 700;
    letter-spacing: .03em;
    text-transform: uppercase;
}

.csv-empty {
    padding: 1.25rem !important;
    color: var(--muted);
    text-align: center;
}

.csv-note {
    margin: .7rem 0 0;
    color: var(--muted);
    font-size: .78rem;
    line-height: 1.5;
}

/* QR Studio -------------------------------------------------------------- */

.qr-layout {
    display: grid;
    grid-template-columns: minmax(0, 1fr) minmax(15rem, .85fr);
    gap: 1.25rem;
    align-items: start;
}

.qr-preview-wrap {
    padding: 1rem;
    border: 1px solid var(--line);
    background: var(--bg);
    text-align: center;
}

.qr-preview {
    display: block;
    width: 100%;
    max-width: 26rem;
    aspect-ratio: 1 / 1;
    height: auto;
    margin: 0 auto;
    image-rendering: pixelated;
    border: 1px solid var(--line);
    background: var(--surface);
}

.qr-divider {
    margin: 1.2rem 0;
    border: 0;
    border-top: 1px solid var(--line);
}

.qr-size-note {
    margin: .7rem 0 0;
    color: var(--muted);
    font-family: var(--font-mono);
    font-size: .7rem;
    line-height: 1.45;
}

.qr-color-row {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 1rem;
}

.qr-color {
    width: 100%;
    min-height: 2.75rem;
    padding: .2rem;
    border: 1px solid var(--line-strong);
    border-radius: var(--radius);
    background: var(--bg);
    cursor: pointer;
}

.qr-color:focus-visible {
    outline: 2px solid var(--focus);
    outline-offset: 2px;
}

/* Duyarlı ---------------------------------------------------------------- */

@media (max-width: 991.98px) {
    .qr-layout {
        grid-template-columns: minmax(0, 1fr);
    }

    .csv-controls {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 767.98px) {
    .tool-grid,
    .tool-grid--three,
    .dev-grid,
    .dev-jwt-grid,
    .diff-layout {
        grid-template-columns: minmax(0, 1fr);
    }

    .csv-controls {
        grid-template-columns: minmax(0, 1fr);
    }

    .tool-meta__key {
        display: block;
        min-width: 0;
    }

    .image-item__main {
        align-items: stretch;
    }

    .image-thumb {
        max-width: 72px;
        max-height: 72px;
    }
}

@media (max-width: 575.98px) {
    .tool-panel {
        padding: 1rem;
    }

    .tool-actions .button {
        flex: 1 1 auto;
    }

    .diff-table {
        font-size: .72rem;
    }

    .diff-table__number {
        width: 2.6rem;
    }

    .diff-table__status {
        width: 2.2rem;
    }
}

@media (prefers-reduced-motion: reduce) {
    .dropzone,
    .button {
        transition: none;
    }
}

    </style>
</head>
<body>
<main>
<section class="section tool" aria-labelledby="tool-title">
    <div class="container">
        <div class="row g-5">
            <div class="col-lg-4">
                <p class="section__label"><span class="section__num">Araç</span> Text Diff</p>
                <h1 class="section__title" id="tool-title">İki metni satır satır karşılaştır.</h1>
                <p class="section__intro">
                    Eski ve yeni metni yan yana girin. Eklenen, silinen ve değiştirilen satırlar
                    numaralarıyla işaretlenir; sonucu düz metin olarak kopyalayabilirsiniz.
                </p>

                <div class="tool-notice">
                    <h2 class="tool-notice__title">Karşılaştırma</h2>
                    <ul class="tool-notice__list">
                        <li>Karşılaştırma tarayıcıda çalışan JavaScript ile yapılır.</li>
                        <li>CRLF ve LF satır sonları aynı kabul edilir; Unicode karakterler korunur.</li>
                        <li>Karşılaştırma karakter sayısına değil, LCS tabanlı satır eşleştirmesine dayanır.</li>
                        <li>En fazla 1500 satır ve 4 milyon karşılaştırma hücresi işlenir; sınır aşılırsa sonuç üretilmez.</li>
                    </ul>
                </div>

                <div class="tool-meta">
                    <h2 class="tool-meta__title">Renkler ve erişilebilirlik</h2>
                    <p class="tool-meta__line">
                        Satır durumu yalnızca renkle değil; +, −, ~, = işaretleri, “Eklendi / Silindi /
                        Değiştirildi / Aynı” metni ve satır numaralarıyla da bildirilir. Tablo klavyeyle kaydırılabilir.
                    </p>
                </div>

                <div class="tool-meta">
                    <h2 class="tool-meta__title">Boş girdiler</h2>
                    <p class="tool-meta__line">
                        İki alan da boşsa karşılaştırma yapılmaz. Yalnızca bir alan doluysa tüm satırlar
                        eklenen veya silinen olarak gösterilir.
                    </p>
                </div>
            </div>

            <div class="col-lg-7 offset-lg-1">
                <div class="tool-panel">
                    <form data-diff-form>
                        <div class="diff-layout">
                            <div class="tool-field">
                                <label class="tool-label" for="diff-old">Eski metin</label>
                                <textarea class="tool-textarea" id="diff-old" spellcheck="false" aria-describedby="diff-old-hint"></textarea>
                                <p class="tool-field__hint" id="diff-old-hint">Karşılaştırmanın sol tarafı.</p>
                            </div>
                            <div class="tool-field">
                                <label class="tool-label" for="diff-new">Yeni metin</label>
                                <textarea class="tool-textarea" id="diff-new" spellcheck="false" aria-describedby="diff-new-hint"></textarea>
                                <p class="tool-field__hint" id="diff-new-hint">Karşılaştırmanın sağ tarafı.</p>
                            </div>
                        </div>

                        <div class="tool-actions">
                            <button class="button button--primary" type="submit">Karşılaştır</button>
                            <button class="button" type="button" data-diff-copy>Sonucu kopyala</button>
                            <button class="button button--quiet" type="button" data-diff-clear>Alanları temizle</button>
                        </div>
                    </form>

                    <p class="diff-summary" role="status" aria-live="polite" data-diff-summary>Henüz karşılaştırma yapılmadı.</p>

                    <div class="diff-scroll" tabindex="0" role="region" aria-label="Satır bazında fark sonuçları">
                        <table class="diff-table">
                            <caption>Satır bazında farklar: eski satır numarası, yeni satır numarası, durum ve metin.</caption>
                            <thead>
                                <tr>
                                    <th scope="col">Eski</th>
                                    <th scope="col">Yeni</th>
                                    <th scope="col">Durum</th>
                                    <th scope="col">Satır</th>
                                </tr>
                            </thead>
                            <tbody data-diff-body>
                                <tr>
                                    <td class="diff-empty" colspan="4">Karşılaştırmak için iki metin girip “Karşılaştır” düğmesini kullanın.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <p class="tool-status" role="status" aria-live="polite" data-diff-status></p>

                    <noscript>
                        <p class="tool-noscript">Karşılaştırma JavaScript ile yapılır. JavaScript kapalıyken bu araç çalışmaz.</p>
                    </noscript>
                </div>
</div>
        </div>
    </div>
</section>
</main>
<script>
/*
 * Tarayıcı araçları için ortak yardımcılar.
 *
 * Bu dosya sunucuya istek göndermez; tüm yardımcılar dosyanın içinde
 * tanımlıdır ve başka bir dosyaya bağımlılık yoktur.
 */
(function (root, factory) {
    'use strict';

    root.PortfolioTools = root.PortfolioTools || {};
    root.PortfolioTools.common = factory();
}(typeof globalThis !== 'undefined' ? globalThis : this, function () {
    'use strict';

    function formatBytes(bytes) {
        var value = Number(bytes);

        if (!isFinite(value) || value < 0) {
            return '0 B';
        }

        if (value < 1024) {
            return Math.round(value) + ' B';
        }

        if (value < 1024 * 1024) {
            return (value / 1024).toFixed(1).replace('.', ',') + ' KB';
        }

        if (value < 1024 * 1024 * 1024) {
            return (value / (1024 * 1024)).toFixed(1).replace('.', ',') + ' MB';
        }

        return (value / (1024 * 1024 * 1024)).toFixed(2).replace('.', ',') + ' GB';
    }

    function safeFilename(name, fallback) {
        var value = typeof name === 'string' ? name : '';
        value = value.replace(/[\\/:*?"<>|\u0000-\u001f\u007f]/g, '-');
        value = value.replace(/\s+/g, ' ').trim();
        value = value.replace(/^\.+/, '');

        if (value === '') {
            value = typeof fallback === 'string' && fallback !== '' ? fallback : 'dosya';
        }

        if (value.length > 120) {
            value = value.slice(0, 120);
        }

        return value;
    }

    function debounce(callback, wait) {
        var timer = null;

        return function () {
            var args = arguments;
            var context = this;

            if (timer !== null) {
                clearTimeout(timer);
            }

            timer = setTimeout(function () {
                timer = null;
                callback.apply(context, args);
            }, wait);
        };
    }

    function setStatus(element, message, isError) {
        if (!element) {
            return;
        }

        element.textContent = message || '';
        element.classList.toggle('is-error', Boolean(isError));
    }

    function downloadBlob(blob, filename) {
        if (!blob || typeof document === 'undefined') {
            return false;
        }

        var url = URL.createObjectURL(blob);
        var link = document.createElement('a');

        link.href = url;
        link.download = safeFilename(filename, 'indirme');
        link.rel = 'noopener';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        setTimeout(function () {
            URL.revokeObjectURL(url);
        }, 30000);

        return true;
    }

    function downloadText(text, mime, filename) {
        return downloadBlob(new Blob([text], { type: mime + ';charset=utf-8' }), filename);
    }

    function copyText(text) {
        if (typeof navigator !== 'undefined' && navigator.clipboard && typeof navigator.clipboard.writeText === 'function') {
            return navigator.clipboard.writeText(text);
        }

        return new Promise(function (resolve, reject) {
            if (typeof document === 'undefined') {
                reject(new Error('Panoya kopyalama bu ortamda kullanılamıyor.'));
                return;
            }

            var area = document.createElement('textarea');
            area.value = text;
            area.setAttribute('readonly', 'readonly');
            area.className = 'visually-hidden';
            document.body.appendChild(area);
            area.select();

            try {
                var copied = document.execCommand('copy');
                document.body.removeChild(area);

                if (copied) {
                    resolve();
                } else {
                    reject(new Error('Panoya kopyalama tarayıcı tarafından engellendi.'));
                }
            } catch (error) {
                document.body.removeChild(area);
                reject(new Error('Panoya kopyalama bu tarayıcıda desteklenmiyor.'));
            }
        });
    }

    function extensionOf(name) {
        var value = typeof name === 'string' ? name : '';
        var dot = value.lastIndexOf('.');

        return dot > 0 ? value.slice(dot + 1).toLowerCase() : '';
    }

    function baseName(name) {
        var value = typeof name === 'string' ? name : '';
        var dot = value.lastIndexOf('.');

        return dot > 0 ? value.slice(0, dot) : value;
    }

    return {
        formatBytes: formatBytes,
        safeFilename: safeFilename,
        debounce: debounce,
        setStatus: setStatus,
        downloadBlob: downloadBlob,
        downloadText: downloadText,
        copyText: copyText,
        extensionOf: extensionOf,
        baseName: baseName
    };
}));

/*
 * Text Diff — satır bazlı, tamamen yerel karşılaştırma.
 *
 * Karşılaştırma karakter sayısına değil, normalize edilmiş satırlara dayanır.
 * Büyük girdilerde hesaplama sınırı aşılırsa sonuç uydurulmaz; açık hata verilir.
 */
(function (root, factory) {
    'use strict';

    root.PortfolioTools = root.PortfolioTools || {};
    root.PortfolioTools.textDiff = factory(root.PortfolioTools.common);
}(typeof globalThis !== 'undefined' ? globalThis : this, function (common) {
    'use strict';

    var MAX_LINES = 1500;
    var MAX_CELLS = 4000000;

    function normalizeNewlines(text) {
        return String(text).replace(/\r\n?/g, '\n');
    }

    function splitLines(text) {
        var normalized = normalizeNewlines(text);

        if (normalized === '') {
            return [];
        }

        var lines = normalized.split('\n');

        if (lines.length > 0 && lines[lines.length - 1] === '') {
            lines.pop();
        }

        return lines;
    }

    function limitError() {
        return new Error('Girdi karşılaştırma sınırını aşıyor. Metinleri ' + MAX_LINES + ' satıra kadar küçültün.');
    }

    function diffLines(oldText, newText) {
        var oldLines = splitLines(oldText);
        var newLines = splitLines(newText);
        var oldCount = oldLines.length;
        var newCount = newLines.length;

        if (oldCount > MAX_LINES || newCount > MAX_LINES) {
            throw limitError();
        }

        if ((oldCount + 1) * (newCount + 1) > MAX_CELLS) {
            throw limitError();
        }

        var width = newCount + 1;
        var table = new Uint16Array((oldCount + 1) * width);
        var i;
        var j;

        for (i = oldCount - 1; i >= 0; i -= 1) {
            for (j = newCount - 1; j >= 0; j -= 1) {
                if (oldLines[i] === newLines[j]) {
                    table[i * width + j] = table[(i + 1) * width + j + 1] + 1;
                } else {
                    var down = table[(i + 1) * width + j];
                    var right = table[i * width + j + 1];
                    table[i * width + j] = down >= right ? down : right;
                }
            }
        }

        var operations = [];
        i = 0;
        j = 0;

        while (i < oldCount || j < newCount) {
            if (i < oldCount && j < newCount && oldLines[i] === newLines[j]) {
                operations.push({ type: 'equal', oldIndex: i, newIndex: j });
                i += 1;
                j += 1;
            } else if (j < newCount && (i >= oldCount || table[i * width + j + 1] >= table[(i + 1) * width + j])) {
                operations.push({ type: 'insert', newIndex: j });
                j += 1;
            } else {
                operations.push({ type: 'delete', oldIndex: i });
                i += 1;
            }
        }

        var rows = [];
        var cursor = 0;

        while (cursor < operations.length) {
            var operation = operations[cursor];

            if (operation.type === 'equal') {
                rows.push({
                    type: 'equal',
                    oldNumber: operation.oldIndex + 1,
                    newNumber: operation.newIndex + 1,
                    oldText: oldLines[operation.oldIndex],
                    newText: newLines[operation.newIndex]
                });
                cursor += 1;
                continue;
            }

            var deleted = [];
            var inserted = [];

            while (cursor < operations.length && operations[cursor].type !== 'equal') {
                if (operations[cursor].type === 'delete') {
                    deleted.push(operations[cursor].oldIndex);
                } else {
                    inserted.push(operations[cursor].newIndex);
                }

                cursor += 1;
            }

            var paired = Math.min(deleted.length, inserted.length);

            for (var pair = 0; pair < paired; pair += 1) {
                rows.push({
                    type: 'change',
                    oldNumber: deleted[pair] + 1,
                    newNumber: inserted[pair] + 1,
                    oldText: oldLines[deleted[pair]],
                    newText: newLines[inserted[pair]]
                });
            }

            for (var deleteIndex = paired; deleteIndex < deleted.length; deleteIndex += 1) {
                rows.push({
                    type: 'delete',
                    oldNumber: deleted[deleteIndex] + 1,
                    newNumber: null,
                    oldText: oldLines[deleted[deleteIndex]],
                    newText: null
                });
            }

            for (var insertIndex = paired; insertIndex < inserted.length; insertIndex += 1) {
                rows.push({
                    type: 'insert',
                    oldNumber: null,
                    newNumber: inserted[insertIndex] + 1,
                    oldText: null,
                    newText: newLines[inserted[insertIndex]]
                });
            }
        }

        var stats = { added: 0, removed: 0, changed: 0, total: 0 };

        rows.forEach(function (row) {
            if (row.type === 'insert') {
                stats.added += 1;
            } else if (row.type === 'delete') {
                stats.removed += 1;
            } else if (row.type === 'change') {
                stats.changed += 1;
            }
        });

        stats.total = stats.added + stats.removed + stats.changed;

        return {
            rows: rows,
            stats: stats,
            oldLineCount: oldCount,
            newLineCount: newCount,
            identical: stats.total === 0
        };
    }

    function toUnifiedText(result) {
        if (!result || !Array.isArray(result.rows)) {
            return '';
        }

        var lines = ['--- eski', '+++ yeni'];

        result.rows.forEach(function (row) {
            if (row.type === 'equal') {
                lines.push('  ' + row.oldText);
            } else if (row.type === 'delete') {
                lines.push('- ' + row.oldText);
            } else if (row.type === 'insert') {
                lines.push('+ ' + row.newText);
            } else {
                lines.push('- ' + row.oldText);
                lines.push('+ ' + row.newText);
            }
        });

        return lines.join('\n');
    }

    function init() {
        if (typeof document === 'undefined') {
            return;
        }

        var form = document.querySelector('[data-diff-form]');

        if (!form) {
            return;
        }

        var oldInput = document.getElementById('diff-old');
        var newInput = document.getElementById('diff-new');
        var body = document.querySelector('[data-diff-body]');
        var summary = document.querySelector('[data-diff-summary]');
        var status = document.querySelector('[data-diff-status]');
        var copyButton = document.querySelector('[data-diff-copy]');
        var clearButton = document.querySelector('[data-diff-clear]');
        var result = null;

        if (!oldInput || !newInput || !body || !summary || !status) {
            return;
        }

        function setStatus(message, isError) {
            status.textContent = message || '';
            status.classList.toggle('is-error', Boolean(isError));
        }

        function emptyRow(message) {
            var row = document.createElement('tr');
            var cell = document.createElement('td');

            cell.colSpan = 4;
            cell.className = 'diff-empty';
            cell.textContent = message;
            row.appendChild(cell);
            body.appendChild(row);
        }

        function resetOutput(message) {
            result = null;
            body.textContent = '';
            emptyRow(message || 'Karşılaştırmak için iki metin girip “Karşılaştır” düğmesini kullanın.');
            summary.textContent = 'Henüz karşılaştırma yapılmadı.';
            summary.classList.remove('is-error');
        }

        function lineCell(number) {
            var cell = document.createElement('td');
            cell.className = 'diff-table__number';
            cell.textContent = number === null ? '—' : String(number);
            return cell;
        }

        function textLine(text, type) {
            var line = document.createElement('span');
            line.className = 'diff-line diff-line--' + type;
            line.textContent = text;
            return line;
        }

        function render(resultValue) {
            body.textContent = '';

            resultValue.rows.forEach(function (row) {
                var tr = document.createElement('tr');
                var statusCell = document.createElement('td');
                var textCell = document.createElement('td');
                var label = row.type === 'insert' ? 'Eklendi'
                    : row.type === 'delete' ? 'Silindi'
                        : row.type === 'change' ? 'Değiştirildi' : 'Aynı';
                var sign = row.type === 'insert' ? '+'
                    : row.type === 'delete' ? '−'
                        : row.type === 'change' ? '~' : '=';
                var hidden = document.createElement('span');

                tr.className = 'diff-row diff-row--' + row.type;
                tr.appendChild(lineCell(row.oldNumber));
                tr.appendChild(lineCell(row.newNumber));

                statusCell.className = 'diff-table__status';
                statusCell.setAttribute('aria-label', label);
                statusCell.textContent = sign;
                hidden.className = 'visually-hidden';
                hidden.textContent = ' ' + label;
                statusCell.appendChild(hidden);

                textCell.className = 'diff-table__text';

                if (row.type === 'change') {
                    textCell.appendChild(textLine(row.oldText, 'removed'));
                    textCell.appendChild(textLine(row.newText, 'added'));
                } else if (row.type === 'delete') {
                    textCell.appendChild(textLine(row.oldText, 'removed'));
                } else if (row.type === 'insert') {
                    textCell.appendChild(textLine(row.newText, 'added'));
                } else {
                    textCell.appendChild(textLine(row.oldText, 'equal'));
                }

                tr.appendChild(statusCell);
                tr.appendChild(textCell);
                body.appendChild(tr);
            });

            if (resultValue.rows.length === 0) {
                emptyRow('İki metinde de satır yok.');
            }

            summary.classList.toggle('is-error', false);
            summary.textContent = resultValue.identical
                ? 'Metinler satır bazında aynı. Fark yok.'
                : resultValue.stats.total + ' fark: ' + resultValue.stats.added + ' eklendi, '
                    + resultValue.stats.removed + ' silindi, ' + resultValue.stats.changed + ' değiştirildi.';
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (oldInput.value === '' && newInput.value === '') {
                resetOutput('Her iki alan da boş. Karşılaştırılacak metin yok.');
                setStatus('İki alan da boş.', true);
                return;
            }

            try {
                result = diffLines(oldInput.value, newInput.value);
                render(result);
                setStatus('Karşılaştırma tamamlandı.');
            } catch (error) {
                result = null;
                body.textContent = '';
                emptyRow('Girdi sınırı aşıldığı için karşılaştırma yapılmadı.');
                summary.textContent = 'Sonuç üretilmedi.';
                summary.classList.add('is-error');
                setStatus(error.message, true);
            }
        });

        if (copyButton) {
            copyButton.addEventListener('click', function () {
                if (!result) {
                    setStatus('Kopyalanacak bir sonuç yok. Önce karşılaştırın.', true);
                    return;
                }

                var text = toUnifiedText(result);

                common.copyText(text).then(function () {
                    setStatus('Fark sonucu panoya kopyalandı.');
                }).catch(function () {
                    setStatus('Panoya kopyalama izni verilmedi.', true);
                });
            });
        }

        if (clearButton) {
            clearButton.addEventListener('click', function () {
                oldInput.value = '';
                newInput.value = '';
                resetOutput();
                setStatus('Alanlar temizlendi.');
                oldInput.focus();
            });
        }

        resetOutput();
    }

    if (typeof document !== 'undefined') {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    }

    return {
        MAX_LINES: MAX_LINES,
        MAX_CELLS: MAX_CELLS,
        normalizeNewlines: normalizeNewlines,
        splitLines: splitLines,
        diffLines: diffLines,
        toUnifiedText: toUnifiedText
    };
}));

</script>
</body>
</html>
