# Plan de Acción: Organizar app.css por Bloques Reales

## 🎯 Objetivo
Evitar que el CSS quede como parche acumulado. Reorganizar y hacerlo legible sin reescribir todo.

## 📊 Estado Actual (Análisis Completado)

### ❌ Problemas Identificados:
1. **Duplicados críticos:**
   - `.hp-title`: 4 apariciones
   - `.hp-subtitle`: 3 apariciones  
   - `.hp-hero`: 2 apariciones
   - `.hp-container`: 2 apariciones
   - `.hp-marker`: 2 apariciones
   - `.hp-intro-container`: 3 apariciones
   - `.hp-intro-rail-art`: 3 apariciones

2. **Offsets fijos que necesitan variables:**
   - `left: 179px` (hp-intro-rail-art)
   - `padding-left: 299px` (hp-intro-copy)
   - `left: -36px` (hp-marker, hp-rail-dot)

3. **Estructura desorganizada:**
   - Temas mezclados con estilos base
   - Media queries dispersas
   - Sin bloques lógicos claros

## 🏗️ Estructura Recomendada

```
/* =========================================================
   01. RESET / SHELL
========================================================= */
/* =========================================================
   02. TOKENS / VARIABLES  
========================================================= */
/* =========================================================
   03. LAYOUT GLOBAL
========================================================= */
/* =========================================================
   04. HERO TIPOGRÁFICO
========================================================= */
/* =========================================================
   05. INTRODUCCIÓN
========================================================= */
/* =========================================================
   06. TIMELINE
========================================================= */
/* =========================================================
   07. TARJETAS / EVENTOS
========================================================= */
/* =========================================================
   08. MODAL / OVERLAY
========================================================= */
/* =========================================================
   09. UTILIDADES
========================================================= */
/* =========================================================
   10. RESPONSIVE
========================================================= */
```

## 📋 Acciones Específicas

### ✅ Fase 1: Limpieza de Duplicados
**Prioridad: ALTA**

1. **Consolidar `.hp-title`:**
   - Mantener regla base en sección HERO
   - Mover variante theme-divergentes a sección THEME
   - Eliminar duplicados en responsive

2. **Consolidar `.hp-subtitle`:**
   - Unificar propiedades comunes
   - Separar responsive a bloque 10

3. **Consolidar `.hp-hero`:**
   - Base en sección HERO
   - Theme overrides en sección THEME
   - Responsive en bloque 10

### ✅ Fase 2: Variables CSS  
**Prioridad: MEDIA**

1. **Crear variables para offsets:**
```css
.hp-shell {
  /* Intro offsets */
  --hp-intro-rail-left: 179px;
  --hp-intro-copy-offset: 299px;
  
  /* Marker positions */
  --hp-marker-left: -36px;
  --hp-marker-left-mobile: -24px;
  
  /* Container spacing */
  --hp-hero-padding-x: clamp(20px, 4vw, 40px);
}
```

2. **Aplicar variables:**
```css
.hp-intro-rail-art {
  left: var(--hp-intro-rail-left);
}

.hp-intro-copy {
  padding-left: var(--hp-intro-copy-offset);
}

.hp-marker {
  left: var(--hp-marker-left);
}
```

### ✅ Fase 3: Timeline Line Alignment
**Prioridad: MEDIA**

1. **Anclar línea a markers:**
```css
.hp-shell {
  --hp-marker-axis: 22px; /* mismo valor para línea y markers */
}

.hp-track-line,
.hp-track-fill {
  left: var(--hp-marker-axis);
}

.hp-marker {
  left: var(--hp-marker-axis);
  transform: translateX(-50%);
}
```

### ✅ Fase 4: Eliminar Código Muerto
**Prioridad: MEDIA**

**Buscar y eliminar:**
- Referencias a `hero_image` (ya limpiado)
- `intro_image` (si existe)
- `floating`, `fullbleed` legacy
- `debug`, `console.log`
- Estilos para elementos que ya no existen

### ✅ Fase 5: Optimización de Assets
**Prioridad: BAJA**

1. **Revisar imágenes:**
   - Verificar PNG vs WebP duplicados
   - Eliminar imágenes de prueba
   - Conservar solo formatos activos

## 🔄 Proceso de Reorganización

### Paso 1: Backup
```bash
cp assets/css/app.css assets/css/app.css.backup
```

### Paso 2: Extracción por Bloques
1. **Crear nuevo archivo temporal**
2. **Copiar bloques en orden correcto:**
   - Extraer TOKENS (líneas 1-52)
   - Extraer RESET (líneas 54-98)
   - Extraer HERO base (líneas 114-210)
   - Extraer INTRO (líneas 212-223, 931-1004)
   - Extraer TIMELINE (líneas 272-364)
   - Extraer EVENTOS (líneas 365-562)
   - Extraer MODAL (líneas 563-657)
   - Extraer THEMES (líneas 685-864)
   - Extraer RESPONSIVE (líneas 866-1112)

### Paso 3: Consolidación
1. **Eliminar duplicados**
2. **Aplicar variables**
3. **Verificar funcionalidad**

### Paso 4: Validación
```bash
# Verificar sintaxis CSS
npx stylelint assets/css/app.css

# Probar visualmente
# Abrir sitio y verificar todas las secciones
```

## 🎯 Resultado Esperado

✅ **CSS organizado en 10 bloques lógicos**
✅ **Sin reglas duplicadas**
✅ **Offsets manejados por variables**
✅ **Línea de timeline alineada con markers**
✅ **Código muerto eliminado**
✅ **Assets optimizados**

## 🚀 Comandos de Ejecución

```bash
# 1. Backup
cp assets/css/app.css assets/css/app.css.backup-$(date +%Y%m%d)

# 2. Verificar duplicados
grep -n "\.hp-title" assets/css/app.css
grep -n "\.hp-subtitle" assets/css/app.css
grep -n "\.hp-hero" assets/css/app.css

# 3. Buscar código muerto
grep -n "hero_image\|intro_image\|floating\|debug" assets/css/app.css

# 4. Validar sintaxis
npx stylelint assets/css/app.css --fix
```

## ⚠️ Precauciones

1. **Hacer backup antes de empezar**
2. **Probar cada bloque después de reorganizar**
3. **Verificar responsive en múltiples tamaños**
4. **Confirmar que todos los temas funcionen**
5. **Validar que la línea roja siga a los markers**

## 📈 Métricas de Éxito

- **Menos de 1 aparición por selector**
- **0 offsets fijos sin variable**
- **CSS organizado en 10 bloques claros**
- **Sin errores de sintaxis**
- **Funcionalidad visual intacta**

---

**Tiempo estimado:** 2-3 horas
**Complejidad:** Media
**Riesgo:** Bajo (con backup)
