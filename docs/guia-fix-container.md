# Guía Quirúrgica: Fix Container - Eliminar Doble Contención

## Hallazgo Clave

El problema no está en el fullbleed de page.css, sino en la **doble lógica de container**:

- **page.css** ya aplica gutters a `.hp-container`
- **app.css** vuelve a recalcular el ancho con `min(100% - 32px, ...)`

**Resultado:** Doble contención, doble resta, comportamiento raro en mobile.

## Problema Identificado

**Versión actual problemática (app.css):**
```css
.hp-shell .hp-container {
  width: min(100% - 32px, var(--hp-container-max));
  margin-inline: auto;
}

@media (max-width: 767px) {
  .hp-shell .hp-container {
    width: min(100% - 24px, var(--hp-container-max));
  }
}
```

**Versión estable anterior:**
```css
.hp-shell .hp-container {
  width: 100%;
  max-width: var(--hp-container-max);
  margin-left: auto;
  margin-right: auto;
  padding-left: 24px;
  padding-right: 24px;
}
```

## Solución Quirúrgica

### PASO 1: Revertir .hp-container a lógica estable

**Archivo:** `assets/css/app.css`

**Buscar y reemplazar:**
```css
.hp-shell .hp-container {
  width: min(100% - 32px, var(--hp-container-max));
  margin-inline: auto;
}
```

**Por esto:**
```css
.hp-shell .hp-container {
  width: 100%;
  max-width: var(--hp-container-max);
  margin-left: auto;
  margin-right: auto;
  padding-left: 24px;
  padding-right: 24px;
}
```

### PASO 2: Corregir media query mobile

**Buscar y reemplazar:**
```css
@media (max-width: 767px) {
  .hp-shell .hp-container {
    width: min(100% - 24px, var(--hp-container-max));
  }

  .hp-shell .hp-reading {
    width: 100%;
  }
}
```

**Por esto:**
```css
@media (max-width: 767px) {
  .hp-shell .hp-container {
    width: 100%;
    padding-left: 16px;
    padding-right: 16px;
  }

  .hp-shell .hp-reading {
    width: 100%;
  }
}
```

### PASO 3: Probar antes de seguir tocando

**Anchos críticos a probar:**
- 390px (iPhone SE)
- 430px (iPhone 14 Pro)
- 768px (iPad mini)

**Qué revisar:**
- [ ] Contenido deja de verse corrido
- [ ] Card entra limpia en viewport
- [ ] Botón negro no se siente desplazado
- [ ] Imagen se alinea con la tarjeta

### PASO 4: Opcional - Evitar duplicar gutters

**Si mejora mucho**, entonces limpiar page.css para evitar doble padding:

**Reemplazar en page.css:**
```css
body.hp-has-fullbleed .hp-container {
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
  padding-left: 24px;
  padding-right: 24px;
  box-sizing: border-box;
}
```

**Por esto:**
```css
body.hp-has-fullbleed .hp-container {
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
  box-sizing: border-box;
}
```

## Arquitectura Profesional Final

### Responsabilidad clara por archivo:

**page.css:**
- Integración con WordPress
- Neutralización de wrappers
- Fullbleed global
- **NO** control de gutters internos

**app.css:**
- Container interno con padding
- Layout de componentes
- Responsive del especial
- **SÍ** control de gutters

## Código Final Recomendado

### app.css (versión estable):
```css
.hp-shell .hp-container {
  width: 100%;
  max-width: var(--hp-container-max);
  margin-left: auto;
  margin-right: auto;
  padding-left: 24px;
  padding-right: 24px;
}

@media (max-width: 767px) {
  .hp-shell .hp-container {
    width: 100%;
    padding-left: 16px;
    padding-right: 16px;
  }

  .hp-shell .hp-reading {
    width: 100%;
  }
}
```

### page.css (versión limpia):
```css
body.hp-has-fullbleed .hp-container {
  width: 100%;
  max-width: 1200px;
  margin-left: auto;
  margin-right: auto;
  box-sizing: border-box;
}
```

## Orden de Ejecución

1. **Revertir app.css** a lógica estable
2. **Probar** en anchos críticos
3. **Si mejora**, limpiar page.css
4. **Validar** comportamiento final

## Por Qué Esto Funciona

- **Elimina doble contención:** Solo un sistema controla el ancho
- **Simplifica cálculos:** `width: 100%` + `max-width` + `padding`
- **Comportamiento predecible:** Sin `min()` complejo que compite con page.css
- **Responsabilidad clara:** Cada archivo con su rol específico

Esto debería eliminar el contenido corrido y hacer que las cards entren limpias en mobile.
