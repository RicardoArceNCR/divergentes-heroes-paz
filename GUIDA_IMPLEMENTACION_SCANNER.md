# Guía de Implementación - Timeline con Scanner Fijo

## Objetivo
Transformar el timeline actual de fill acumulado a un scanner rojo fijo en media pantalla que activa los marcadores al cruzar.

## Estructura HTML (NO CAMBIAR)
```html
<div class="hp-track" data-hp-track>
    <div class="hp-track-inner" aria-hidden="true">
        <div class="hp-track-line"></div>
        <div class="hp-track-fill" data-hp-track-fill></div>
    </div>

    <section class="hp-app hp-timeline">
        ...
    </section>
</div>
```

---

## PARTE 1 - CSS (assets/css/app.css)

### Paso 1: Neutralizar lógica vieja del fill

**BUSCAR Y ELIMINAR/SOBRESCRIBIR:**
- Líneas 264-270: Bloque `.hp-shell .hp-track-fill`
- Cualquier `transform: scaleY()`
- Cualquier `height` dinámico
- Cualquier `transform-origin: top`

### Paso 2: Reemplazar bloque timeline completo

**REEMPLAZAR LÍNEAS 234-270 con:**

```css
/* =========================================================
   TIMELINE TRACK — versión profesional con scanner fijo
   ========================================================= */

.hp-shell .hp-track {
  position: relative;
}

.hp-shell .hp-track-inner {
  position: absolute;
  inset: 0;
  pointer-events: none;
  z-index: 1;
  max-width: var(--hp-container-max);
  margin: 0 auto;
  padding-inline: clamp(16px, 5vw, 48px);
}

/* Línea base completa, suave */
.hp-shell .hp-track-line {
  position: absolute;
  left: var(--hp-line-x);
  top: var(--hp-line-top);
  bottom: var(--hp-line-bottom);
  width: 2px;
  transform: translateX(-50%);
  background: rgba(225, 6, 0, 0.12);
  border-radius: 999px;
  pointer-events: none;
}

/* Scanner rojo fijo al centro del viewport */
.hp-shell .hp-track-fill {
  position: fixed;
  left: 50%;
  top: 50vh;
  width: 4px;
  height: 34vh;
  transform: translate(-50%, -50%);
  background: #e10600;
  border-radius: 999px;
  box-shadow: 0 0 14px rgba(225, 6, 0, 0.18);
  pointer-events: none;
  z-index: 20;
  opacity: 0;
  transition: opacity 180ms ease;
}

/* Solo visible cuando el timeline está en viewport */
.hp-shell .hp-track.is-in-view .hp-track-fill {
  opacity: 1;
}

.hp-shell .hp-track:not(.is-in-view) .hp-track-fill {
  opacity: 0;
}
```

### Paso 3: Actualizar estilos del marcador

**BUSCAR LÍNEAS 366-388 y REEMPLAZAR CON:**

```css
.hp-shell .hp-marker {
  position: absolute;
  left: -36px;
  top: 18px;
  width: 18px;
  height: 18px;
  border-radius: 999px;
  border: 2px solid var(--hp-black);
  background: var(--hp-white);
  transition: all var(--hp-dur-2) var(--hp-ease);
  z-index: 3;
}

.hp-shell .hp-marker.is-active {
  border-color: var(--hp-red) !important;
  transform: scale(1.08);
  box-shadow:
    0 0 0 4px rgba(225, 6, 0, 0.12),
    0 0 0 9px rgba(225, 6, 0, 0.05);
}

.hp-shell .hp-marker.is-active::after {
  content: "";
  position: absolute;
  inset: 4px;
  border-radius: 50%;
  background: #e10600;
}

/* Active State del evento (mantener para consistencia) */
.hp-shell .hp-event.is-active {
  border-color: rgba(11, 11, 12, .35);
  box-shadow: var(--hp-shadow-active);
}
```

### Paso 4: Responsive del scanner

**AGREGAR AL FINAL DEL ARCHIVO:**

```css
@media (max-width: 768px) {
  .hp-shell .hp-track-fill {
    width: 3px;
    height: 24vh;
  }

  .hp-shell .hp-track-line {
    width: 2px;
  }
}
```

---

## PARTE 2 - JS (assets/js/app.js)

### Paso 5: Eliminar lógica vieja del fill

**BUSCAR Y COMENTAR/ELIMINAR:**
- Líneas 545-590: Función `setupLineFill()` completa
- Línea 749: `const stopLine = setupLineFill(ctx);`
- Línea 750: `registerDestroy(root, stopLine);`
- Línea 494: `window.requestAnimationFrame(() => setLineToMarker(root));`

### Paso 6: Agregar nuevas funciones

**PEGAR DESPUÉS DE LÍNEA 590:**

```javascript
// =========================================================
// TIMELINE SCANNER SYSTEM - Professional implementation
// =========================================================

function updateTrackScannerVisibility(trackEl) {
  if (!trackEl) return;

  const rect = trackEl.getBoundingClientRect();
  const viewportH = window.innerHeight;

  const isVisible = rect.top < viewportH && rect.bottom > 0;
  trackEl.classList.toggle('is-in-view', isVisible);
}

function updateTimelineMarkers() {
  const markers = document.querySelectorAll('.hp-marker');
  if (!markers.length) return;

  const triggerY = window.innerHeight * 0.5;
  const tolerance = 22;

  markers.forEach((marker) => {
    const rect = marker.getBoundingClientRect();
    const markerCenter = rect.top + rect.height / 2;
    const isActive = Math.abs(markerCenter - triggerY) <= tolerance;

    marker.classList.toggle('is-active', isActive);
  });
}

function updateProfessionalTimeline() {
  const trackEl = document.querySelector('[data-hp-track]');
  updateTrackScannerVisibility(trackEl);
  updateTimelineMarkers();
}
```

### Paso 7: Modificar setupActiveStep

**BUSCAR FUNCIÓN `setupActiveStep()` (LÍNEAS 452-519):**

**REEMPLAZAR LÍNEA 494:**
```javascript
// ANTES:
window.requestAnimationFrame(() => setLineToMarker(root));

// DESPUÉS:
window.requestAnimationFrame(() => updateProfessionalTimeline());
```

**REEMPLAZAR LÍNEAS 494-496:**
```javascript
// ANTES:
if (nextActive !== activeEl) {
  activeEl = nextActive;

  for (const el of events) {
    el.classList.toggle('is-active', el === activeEl);
  }

  window.requestAnimationFrame(() => setLineToMarker(root));
}

// DESPUÉS:
if (nextActive !== activeEl) {
  activeEl = nextActive;

  for (const el of events) {
    el.classList.toggle('is-active', el === activeEl);
  }

  window.requestAnimationFrame(() => updateProfessionalTimeline());
}
```

### Paso 8: Enganchar eventos globales

**BUSCAR LÍNEA 758 (después del resize listener):**

**AGREGAR:**
```javascript
// Professional timeline scanner system
updateProfessionalTimeline();
window.addEventListener('scroll', updateProfessionalTimeline, { passive: true });
window.addEventListener('resize', updateProfessionalTimeline);
```

### Paso 9: Actualizar boot function

**BUSCAR LÍNEA 773 (después del deep-linking):**

**AGREGAR:**
```javascript
// Actualizar timeline después de renderizar
updateProfessionalTimeline();
```

---

## PARTE 3 - Validación Final

### Checklist de validación:

**✅ DEBE PASAR:**
- Línea roja corta aparece solo cuando timeline entra en viewport
- Línea roja se queda fija al centro de pantalla  
- Línea completa de fondo sigue visible pero suave
- Puntos se activan cuando cruzan la zona roja
- Al salir del timeline, el scanner desaparece

**❌ NO DEBE PASAR:**
- Línea roja crece
- Línea roja queda visible arriba del hero o abajo del footer
- Dos puntos se activan al mismo tiempo durante mucho tramo
- El punto se enciende demasiado antes o demasiado después

### Debugging si no funciona:

1. **Verificar clase del marcador:** En inspector, click derecho en círculo → inspeccionar → confirmar que es `.hp-marker`
2. **Verificar data attributes:** Confirmar que `<div class="hp-track" data-hp-track>` existe
3. **Verificar console:** Buscar errores en consola relacionados con las nuevas funciones
4. **Verificar z-index:** Confirmar que scanner no está detrás de otros elementos

### Ajustes finos recomendados:

**Si el scanner es muy grande:**
```css
.hp-shell .hp-track-fill {
  height: 30vh; /* en lugar de 34vh */
}
```

**Si la activación es muy precisa:**
```javascript
const tolerance = 16; /* en lugar de 22 */
```

**Si la activación es muy amplia:**
```javascript
const tolerance = 32; /* en lugar de 22 */
```

---

## PARTE 4 - Orden Exacto de Implementación

1. **Abrir assets/css/app.css**
2. **Buscar líneas 234-270**
3. **Reemplazar con nuevo bloque de track**
4. **Buscar líneas 366-388**
5. **Reemplazar con nuevos estilos de marcador**
6. **Agregar responsive al final**
7. **Abrir assets/js/app.js**
8. **Comentar/eliminar función setupLineFill (líneas 545-590)**
9. **Eliminar llamada a setupLineFill (líneas 749-750)**
10. **Agregar nuevas funciones después de línea 590**
11. **Modificar setupActiveStep (línea 494)**
12. **Agregar eventos globales (línea 758)**
13. **Agregar actualización en boot (línea 773)**
14. **Hacer hard refresh y validar**

---

## Notas Importantes

- **NO MODIFICAR** la estructura HTML existente
- **NO ELIMINAR** otras funciones del timeline (nav, modals, etc.)
- **MANTENER** sistema de deep-linking
- **PRESERVAR** responsive existente
- **TESTAR** en diferentes viewports

Esta implementación mantiene toda la funcionalidad existente mientras reemplaza solo el sistema de fill progresivo por el scanner fijo profesional.
