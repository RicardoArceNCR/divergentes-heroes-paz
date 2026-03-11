# Plan de Implementación: Fix Responsive Completo

## Problemas Identificados
- Wrapper fullbleed con 100vw causing overflow
- Mezcla con alignfull de Gutenberg  
- Falta de blindaje de overflow en varios niveles
- Falta de capa defensiva para mobile en timeline

## FASE 1: Arreglar Wrapper Fullbleed (Priority: HIGH)

### 1.1 Reemplazar bloque 100vw en page.css
**Archivo:** `assets/css/page.css`
**Acción:** Reemplazar bloque completo que usa 100vw

### 1.2 Blindar overflow horizontal
**Archivo:** `assets/css/page.css` 
**Acción:** Agregar overflow-x: clip en múltiples niveles

### 1.3 Mantener container con tope desktop
**Archivo:** `assets/css/page.css`
**Acción:** Mantener max-width: 1200px, no cambiar a 100%

### 1.4 Ajustar gutter móvil
**Archivo:** `assets/css/page.css`
**Acción:** Reducir padding a 14px en mobile

## FASE 2: Proteger Timeline en Mobile (Priority: HIGH)

### 2.1 Agregar capa defensiva responsive
**Archivo:** `assets/css/app.css`
**Acción:** Forzar anchos 100% y min-width: 0 en mobile

### 2.2 Proteger contra texto largo
**Archivo:** `assets/css/app.css`
**Acción:** overflow-wrap y word-break para contenido textual

### 2.3 Estabilizar navegación de meses
**Archivo:** `assets/css/app.css`
**Acción:** flex: 0 0 auto para pills

## FASE 3: Opcional - Quitar doble lógica (Priority: MEDIUM)

### 3.1 Revisar HTML wrapper
**Archivo:** `templates/app-shell.php`
**Acción:** Remover alignfull si persiste problema

## FASE 4: Testing (Priority: HIGH)

### 4.1 Anchos críticos a probar
- 390px (iPhone SE)
- 430px (iPhone 14 Pro)
- 768px (iPad mini)
- 820px (iPad)
- 1024px (iPad Pro)

### 4.2 Checklist de validación
- [ ] No scroll horizontal
- [ ] No empuje hacia derecha
- [ ] Tarjeta ocupa ancho disponible
- [ ] Contenido rompe línea bien
- [ ] Pills scrollean sin overflow
- [ ] Desktop centrado

## Código Final Esperado

### page.css (bloques clave)
```css
body.hp-has-fullbleed {
  overflow-x: clip;
}

body.hp-has-fullbleed .wp-site-blocks,
body.hp-has-fullbleed main,
body.hp-has-fullbleed .entry-content,
body.hp-has-fullbleed .hp-wp-wrap--fullbleed,
body.hp-has-fullbleed .hp-shell,
body.hp-has-fullbleed .hp-track,
body.hp-has-fullbleed .hp-app,
body.hp-has-fullbleed .hp-root {
  overflow-x: clip;
}

body.hp-has-fullbleed .hp-wp-wrap--fullbleed {
  width: auto;
  max-width: none !important;
  margin-left: calc(var(--wp--style--root--padding-left, 0px) * -1) !important;
  margin-right: calc(var(--wp--style--root--padding-right, 0px) * -1) !important;
  padding-left: 0 !important;
  padding-right: 0 !important;
  box-sizing: border-box;
}

body.hp-has-fullbleed .hp-container {
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 24px;
  padding-right: 24px;
  box-sizing: border-box;
}

@media (max-width: 782px) {
  body.hp-has-fullbleed .hp-container {
    padding-left: 14px;
    padding-right: 14px;
  }
}
```

### app.css (bloques finales)
```css
@media (max-width: 860px) {
  .hp-shell .hp-month,
  .hp-shell .hp-month-content,
  .hp-shell .hp-events,
  .hp-shell .hp-event,
  .hp-shell .hp-event-text,
  .hp-shell .hp-event-photo,
  .hp-shell .hp-event-photo-wrap,
  .hp-shell .hp-event-body,
  .hp-shell .hp-event-footer,
  .hp-shell .hp-profile-card,
  .hp-shell .hp-detail-card,
  .hp-shell .hp-modal-card {
    min-width: 0 !important;
    max-width: 100% !important;
    width: 100%;
    box-sizing: border-box;
  }

  .hp-shell .hp-event *,
  .hp-shell .hp-month *,
  .hp-shell .hp-profile-card *,
  .hp-shell .hp-detail-card * {
    min-width: 0;
  }

  .hp-shell .hp-event-title,
  .hp-shell .hp-event-role,
  .hp-shell .hp-event-date,
  .hp-shell .hp-event-copy,
  .hp-shell .hp-event p,
  .hp-shell .hp-event span,
  .hp-shell .hp-event small,
  .hp-shell .hp-month-title,
  .hp-shell .hp-month-subtitle {
    overflow-wrap: anywhere;
    word-break: break-word;
  }
}

.hp-shell .hp-sticky-nav-inner > * {
  flex: 0 0 auto;
}
```

## Orden de Ejecución
1. page.css - wrapper fullbleed
2. page.css - overflow blindaje  
3. page.css - container adjustments
4. app.css - mobile defensive layer
5. app.css - sticky nav fix
6. Testing en anchos críticos
7. Opcional: HTML alignfull removal
