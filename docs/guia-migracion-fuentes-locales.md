# 🎯 Guía Quirúrgica: Migración a Fuentes Locales

## Estrategia Segura: Preparar → Probar → Limpiar

Esta guía implementa la migración de Google Fonts a fuentes locales sin cambios destructivos.

---

## 📋 FASE 0 — PUNTO DE SEGURIDAD

### Crear Savepoint
```bash
git status
git add .
git commit -m "chore(fonts): savepoint before local fonts migration"
git tag savepoint/before-local-fonts
```

---

## 🔍 FASE 1 — AUDITAR LO EXISTENTE

### 1. Estructura Actual ✅
Tu estructura ya está correcta:
```
fonts/
├ lacquer/
│   └── Lacquer-Regular.woff2
│   └── Lacquer-Regular.ttf
└ red-hat-mono/
    ├── RedHatMono-Regular.woff2
    ├── RedHatMono-Medium.woff2
    └── RedHatMono-Bold.woff2
```

### 2. Formatos Actuales ✅
- `.woff2` ✅ (moderno)
- `.ttf` ✅ (será eliminado después)

### 3. Nombres de Familia Usados
Busca en tus archivos CSS estos nombres exactos:
```css
font-family: "Lacquer"
font-family: "Red Hat Mono"
```

---

## 📦 FASE 2 — PREPARAR FUENTES NUEVAS

### 1. Estructura Final Objetivo
```
assets/
├ css/
│  └── fonts.css    # ← NUEVO
├ js/
├ images/
└ fonts/              # ← NUEVA UBICACIÓN ÚNICA
    ├── inter-regular.woff2
    ├── inter-700.woff2
    ├── archivo-condensed-400.woff2
    └── staatliches-400.woff2
```

### 2. Nombres Profesionales
Usa esta convención:
- **minúsculas**
- **guiones**
- **sin espacios**
- **sin acentos**
- **sin paréntesis**

Ejemplos:
```
✅ Correcto: inter-regular.woff2
❌ Incorrecto: Inter Regular FINAL (1).ttf
```

---

## 🎨 FASE 3 — CREAR fonts.css

### Crear archivo: `assets/css/fonts.css`
```css
/* Divergentes Heroes Paz local fonts */

@font-face {
  font-family: "Lacquer";
  src: url("../../fonts/lacquer/Lacquer-Regular.woff2") format("woff2");
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "Red Hat Mono";
  src: url("../../fonts/red-hat-mono/RedHatMono-Regular.woff2") format("woff2");
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "Red Hat Mono";
  src: url("../../fonts/red-hat-mono/RedHatMono-Medium.woff2") format("woff2");
  font-weight: 500;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "Red Hat Mono";
  src: url("../../fonts/red-hat-mono/RedHatMono-Bold.woff2") format("woff2");
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}
```

**⚠️ IMPORTANTE:** El `font-family` debe coincidir EXACTAMENTE con el nombre usado en tu CSS actual.

---

## ⚙️ FASE 4 — MODIFICAR class-assets.php

### Reemplazar SOLO el bloque de Google Fonts

**ANTES:**
```php
// Google Fonts
wp_register_style(
    'dhp-fonts',
    'https://fonts.googleapis.com/css2?family=Lacquer&family=Red+Hat+Mono:wght@400;500;700&display=swap',
    [],
    null
);
```

**DESPUÉS:**
```php
// Local fonts
self::register_style('dhp-fonts', 'assets/css/fonts.css');
```

### Función completa modificada:
```php
public static function register() {
    // Local fonts
    self::register_style('dhp-fonts', 'assets/css/fonts.css');

    // CSS tokens and theme
    self::register_style('dhp-tokens', 'assets/css/tokens.css', ['dhp-fonts']);
    self::register_style('dhp-theme', 'assets/css/theme-editorial.css', ['dhp-tokens']);
    self::register_style('dhp-app', 'assets/css/app.css', ['dhp-theme']);
    self::register_style('dhp-page', 'assets/css/page.css', ['dhp-app']);

    // JS
    self::register_script('dhp-app', 'assets/js/app.js', [], true);
}
```

---

## 🧪 FASE 5 — PRUEBA SIN RIESGO

### 1. Verificar Carga Local
Abre DevTools → Network → filtra por `font`

**DEBES VER:**
```
✅ fonts.css cargado
✅ Lacquer-Regular.woff2
✅ RedHatMono-Regular.woff2
✅ RedHatMono-Medium.woff2
✅ RedHatMono-Bold.woff2
❌ fonts.googleapis.com
❌ fonts.gstatic.com
```

### 2. Verificar Tokens
Revisa que `tokens.css` use las familias correctas:
```css
:root {
  --hp-font-display: "Staatliches", sans-serif;  # ← Debe existir
  --hp-font-body: "Inter", sans-serif;         # ← Si usas Inter
  --hp-font-heading: "Archivo Condensed", sans-serif; # ← Si usas Archivo
}
```

### 3. Prueba Visual
Revisa estos elementos:
- Títulos del hero
- Textos del intro
- Etiquetas del timeline
- Nombres de las tarjetas

---

## 🗑️ FASE 6 — LIMPIEZA GOOGLE FONTS

### CUANDO TODO FUNCIONE:
Busca y elimina:
```php
// Cualquier wp_register_style con fonts.googleapis.com
// Cualquier <link> a Google Fonts
// Cualquier preconnect a fonts.gstatic.com
```

### Eliminar archivos viejos:
```bash
# Eliminar .ttf (deja solo .woff2)
rm fonts/lacquer/Lacquer-Regular.ttf
rm fonts/red-hat-mono/*.ttf

# Eliminar backups y duplicados
rm -rf fonts-backup/
rm -f *.backup
```

---

## ✅ FASE 7 — VERIFICACIÓN FINAL

### Checklist de Éxito:
- [ ] No hay requests a Google Fonts
- [ ] Solo cargan .woff2 locales
- [ ] El diseño se ve idéntico
- [ ] No hay errores 404 de fuentes
- [ ] Los pesos (400/500/700) funcionan
- [ ] Mobile funciona igual
- [ ] No hay .ttf innecesarios

---

## 🚀 FASE 8 — COMMIT LIMPIO

### Primer commit (agregar fuentes locales):
```bash
git add .
git commit -m "feat(fonts): add local woff2 font assets"
```

### Segundo commit (limpiar Google Fonts):
```bash
git add .
git commit -m "refactor(fonts): remove remote font loading"
```

### Tag de estabilidad (opcional):
```bash
git tag stable/local-fonts-v1
```

---

## 🎯 RESULTADO ESPERADO

### Estructura Final:
```
divergentes-heroes-paz/
├ assets/
│  ├ css/
│  │   ├ fonts.css        # ← NUEVO
│  │   ├ tokens.css
│  │   ├ theme-editorial.css
│  │   ├ app.css
│  │   └ page.css
│  ├ js/
│  └ images/
├ fonts/               # ← LIMPIO Y MODERNO
│  ├ lacquer/
│  │   └── Lacquer-Regular.woff2
│  └ red-hat-mono/
│      ├── RedHatMono-Regular.woff2
│      ├── RedHatMono-Medium.woff2
│      └── RedHatMono-Bold.woff2
└ inc/
    └ class-assets.php   # ← MODIFICADO
```

### Beneficios:
- ✅ **Offline completo** - Funciona sin internet
- ✅ **Carga más rápida** - .woff2 locales
- ✅ **Privacidad total** - No tracking de Google
- ✅ **Portabilidad** - Autocontenido
- ✅ **Auditorías OK** - Fácil de certificar

---

## ⚠️ REGLAS DE ORO

### Qué NO hacer:
- ❌ No borrar Google Fonts primero
- ❌ No cambiar todos los font-family del proyecto
- ❌ No mezclar carpetas de fuentes
- ❌ No hacer cambios masivos en un solo commit

### Qué SÍ hacer:
- ✅ Agregar fonts.css antes que los demás CSS
- ✅ Mantener nombres exactos en @font-face
- ✅ Probar antes de limpiar
- ✅ Hacer commits pequeños y reversibles

---

## 🔧 SI ALGO FALLA

### Rollback inmediato:
```bash
git reset --hard savepoint/before-local-fonts
```

### Recuperar Google Fonts:
Si las fuentes locales no cargan, restaura temporalmente:
```php
// En class-assets.php
self::register_style('dhp-fonts', 'https://fonts.googleapis.com/css2?family=Lacquer&family=Red+Hat+Mono:wght@400;500;700&display=swap');
```

---

Esta guía garantiza una migración segura con rollback inmediato si algo falla.
