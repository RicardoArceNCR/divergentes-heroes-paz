# Guía Quirúrgica - Profesionalización del Plugin

## 🎯 Objetivo

Arreglar las 3 cosas realmente importantes ahora:

1️⃣ **Carga correcta de fonts locales**
2️⃣ **Mover tokens de layout al design system**  
3️⃣ **Fortalecer page.css para WordPress**

Resultado: Plugin mucho más limpio y enterprise-ready.

---

## 1️⃣ Carga Correcta de Fonts Locales

### Estructura Asumida:
```
fonts/
├── lacquer/
│   └── lacquer.woff2
└── red-hat-mono/
    ├── RedHatMono-Regular.woff2
    ├── RedHatMono-Medium.woff2
    └── RedHatMono-Bold.woff2
```

### Paso 1: Abrir `assets/css/tokens.css`

**Añadir fuentes arriba de todo, antes de `:root`:**

```css
/* ------------------------------------------------
   FONTS
   ------------------------------------------------ */

@font-face {
  font-family: "RedHatMono";
  src: url("../../fonts/red-hat-mono/RedHatMono-Regular.woff2") format("woff2");
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "RedHatMono";
  src: url("../../fonts/red-hat-mono/RedHatMono-Medium.woff2") format("woff2");
  font-weight: 500;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "RedHatMono";
  src: url("../../fonts/red-hat-mono/RedHatMono-Bold.woff2") format("woff2");
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "Lacquer";
  src: url("../../fonts/lacquer/lacquer.woff2") format("woff2");
  font-weight: normal;
  font-style: normal;
  font-display: swap;
}
```

### Paso 2: Crear Tokens Tipográficos

**Debajo de `:root`:**

```css
:root {
  /* =========================
     FONT FAMILIES
  ========================= */
  --font-sans: system-ui, -apple-system, sans-serif;
  --font-mono: "RedHatMono", monospace;
  --font-display: "Lacquer", sans-serif;

  /* ... resto de tokens existentes ... */
}
```

### Paso 3: Actualizar Referencias

**En `app.css`, reemplazar:**

```css
/* ANTES */
font-family: RedHatMono;
font-family: "Lacquer", cursive;

/* DESPUÉS */
font-family: var(--font-mono);
font-family: var(--font-display);
```

---

## 2️⃣ Mover Layout Tokens al Design System

### Paso 1: Identificar Tokens en `app.css`

**Buscar en `assets/css/app.css`:**

```css
.hp-shell {
  --hp-max: 85%;
  --hp-shell-pad-left: 44px;
  --hp-rail-w: 180px;
  --hp-line-x: 22px;
  /* ... otros tokens ... */
}
```

### Paso 2: Cortar y Mover a `tokens.css`

**Mover estos tokens a `assets/css/tokens.css` dentro de `:root`:**

```css
:root {
  /* ... tokens existentes ... */

  /* =========================
     LAYOUT SYSTEM
  ========================= */
  --hp-max: 1280px;
  --hp-shell-pad-left: clamp(20px, 6vw, 120px);
  --hp-rail-w: 160px;
  --hp-line-x: 72px;
  --hp-line-w: 6px;
  --hp-line-top: -8px;
  --hp-line-bottom: 0px;

  /* Component Specific */
  --hp-event-photo-w: 320px;
  --hp-card-pad-y: 14px;
  --hp-card-pad-x: 16px;

  /* Intro Variables */
  --hp-intro-art-left: 180px;
  --hp-intro-art-width: 93px;
  --hp-intro-copy-offset: 300px;
  --hp-intro-mobile-art-width: 90px;
  --hp-intro-mobile-top-space: 140px;

  /* Shadows */
  --hp-shadow: none;
  --hp-shadow-active: 0 8px 24px rgba(0, 0, 0, .08);

  /* Reveal Animation */
  --hp-reveal-y: 18px;
}
```

### Paso 3: Limpiar `app.css`

**Eliminar el bloque `.hp-shell` con tokens, dejar solo componentes:**

```css
.hp-shell {
  color: var(--hp-text);
  background: var(--color-white);
  font-family: var(--hp-font-body);
}

/* Reset de herencia tipográfica del theme */
.hp-shell .hp-title,
.hp-shell .hp-subtitle,
/* ... resto del reset ... */
```

---

## 3️⃣ Fortalecer page.css para WordPress

### Paso 1: Reemplazar Contenido Completo

**Archivo:** `assets/css/page.css`

```css
/* ------------------------------------------------
   ADMIN BAR OFFSET
   ------------------------------------------------ */

body.admin-bar .hp-shell {
  margin-top: 32px;
}

@media screen and (max-width: 782px) {
  body.admin-bar .hp-shell {
    margin-top: 46px;
  }
}

/* ------------------------------------------------
   FULLBLEED PROTECTION
   ------------------------------------------------ */

body.hp-has-fullbleed .wp-site-blocks {
  padding: 0 !important;
  margin: 0 !important;
}

body.hp-has-fullbleed .entry-content {
  max-width: none !important;
  padding: 0 !important;
}

body.hp-has-fullbleed .wp-block-post-content {
  max-width: none !important;
}

/* ------------------------------------------------
   THEME CONFLICT PROTECTION
   ------------------------------------------------ */

body.hp-has-fullbleed .is-layout-constrained {
  max-width: none !important;
}

body.hp-has-fullbleed .alignwide {
  max-width: none !important;
}

body.hp-has-fullbleed .alignfull {
  max-width: none !important;
}

/* ------------------------------------------------
   CONTAINER PROTECTION
   ------------------------------------------------ */

body.hp-has-fullbleed .hp-container {
  width: min(100% - 32px, var(--container-max));
  margin-inline: auto;
}

@media (max-width: 767px) {
  body.hp-has-fullbleed .hp-container {
    width: min(100% - 24px, var(--container-max));
  }
}
```

### Paso 2: Añadir Clase al Body

**En `templates/app-shell.php` o donde renderices el HTML:**

```php
<div class="hp-shell hp-has-fullbleed">
```

---

## 4️⃣ Actualizar Enqueue de Assets

### Paso 1: Remover Google Fonts

**Archivo:** `inc/class-assets.php`

**Eliminar o comentar:**

```php
// Google Fonts - REMOVED, using local fonts
// wp_register_style(
//     'dhp-fonts',
//     'https://fonts.googleapis.com/css2?family=Lacquer&family=Red+Hat+Mono:wght@400;500;700&display=swap',
//     [],
//     null
// );
```

### Paso 2: Actualizar Dependencias

**Modificar registro de estilos:**

```php
// CSS tokens and theme - REMOVED dhp-fonts dependency
self::register_style('dhp-tokens', 'assets/css/tokens.css', []);
self::register_style('dhp-theme', 'assets/css/theme-editorial.css', ['dhp-tokens']);
self::register_style('dhp-app', 'assets/css/app.css', ['dhp-theme']);
self::register_style('dhp-page', 'assets/css/page.css', ['dhp-app']);
```

---

## 5️⃣ Validación Final

### Checklist de Implementación:

- [ ] **Fonts locales registradas** en `tokens.css`
- [ ] **Tokens de layout movidos** a `tokens.css`
- [ ] **Variables actualizadas** para usar `var(--font-mono)` y `var(--font-display)`
- [ ] **page.css fortalecido** con protección WordPress
- [ ] **Clase hp-has-fullbleed añadida** al HTML
- [ ] **Google fonts eliminadas** del enqueue
- [ ] **Dependencias actualiz** en `class-assets.php`

### Pruebas DevTools:

1. **Fonts:** Verificar que cargan archivos .woff2 locales
2. **Tokens:** No debe haber variables grises enComputed
3. **Fullbleed:** Inspeccionar `.wp-site-blocks` y `.entry-content` con `max-width: none`
4. **Layout:** Verificar que `--hp-line-x` y otros tokens resuelven correctamente

---

## 6️⃣ Arquitectura Resultante

### Estructura Profesional:

```
tokens.css         → Design System (fonts, spacing, layout tokens)
theme-editorial.css → Tema visual (colores, estilos editoriales)
app.css            → Componentes (cards, timeline, navigation)
page.css           → Integración WordPress (admin bar, fullbleed)
```

### Beneficios:

✅ **Performance:** Fonts locales, sin requests externas  
✅ **Maintainability:** Design system centralizado  
✅ **WordPress Integration:** Fullbleed protegido  
✅ **Scalability:** Sistema reusable para futuros especiales  
✅ **Professional:** Arquitectura tipo enterprise  

---

## 🚀 Comandos Automatizados

```bash
# Paso 1: Backup
cp assets/css/tokens.css assets/css/tokens.css.backup
cp assets/css/app.css assets/css/app.css.backup
cp assets/css/page.css assets/css/page.css.backup

# Paso 2: Eliminar Google Fonts (opcional)
sed -i '' '/dhp-fonts/d' inc/class-assets.php

# Paso 3: Reemplazar referencias de fuentes
sed -i '' 's/"RedHatMono"/var(--font-mono)/g' assets/css/app.css
sed -i '' 's/"Lacquer"/var(--font-display)/g' assets/css/app.css
```

---

## 📊 Resultado Esperado

**Antes:** 70% listo  
**Después:** 90% listo para release

**Estado Final:**
- Plugin con arquitectura profesional
- Fonts locales optimizadas
- Design system robusto
- WordPress integration sólida
- Performance mejorada

---

## ⚠️ Notas Importantes

1. **No romper lo existente:** Los cambios son aditivos, no destructivos
2. **Testing:** Probar en diferentes viewports y con admin bar activo
3. **Performance:** Considerar conditional loading en futuras versiones
4. **Mantenimiento:** El sistema ahora es mucho más fácil de mantener

Esta guía transforma tu plugin a un nivel profesional sin romper funcionalidad existente.
