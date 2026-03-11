# Guía de Implementación: Corrección de Sincronización del Timeline

## Diagnóstico del Problema

El sistema actual tiene **3 referencias distintas** cuando debería tener **1 sola**:

1. **Scanner rojo**: `top: calc(50vh - 92px)` (CSS)
2. **Marker activo**: `(window.innerHeight / 2) - 92` (JS)
3. **Tarjeta activa**: `window.innerHeight * 0.5` (JS)

Esta desincronización causa que:
- La tarjeta se revele tarde
- El `is-active` no coincida perfecto con el scanner
- El punto y la tarjeta se sientan "atrasados"

---

## 🔧 Corrección Quirúrgica - Paso a Paso

### Paso 1: Unificar el punto de activación del timeline

**Archivo**: `assets/js/app.js`
**Función**: `setupActiveStep(root, navApi)`

**Cambia esto** (línea 461):
```javascript
const viewportCenter = window.innerHeight * 0.5;
```

**Por esto**:
```javascript
const viewportCenter = (window.innerHeight * 0.5) - 92;
```

**Resultado**: La tarjeta activa empezará a alinearse con el scanner rojo.

---

### Paso 2: Hacer que el reveal ocurra antes

**Archivo**: `assets/js/app.js`
**Función**: `setupActiveStep(root, navApi)`

**Cambia esto** (líneas 509-512):
```javascript
{
  threshold: 0,
  rootMargin: '-45% 0px -45% 0px'
}
```

**Por esto**:
```javascript
{
  threshold: 0,
  rootMargin: '-22% 0px -38% 0px'
}
```

**Alternativa más agresiva** (si仍然 se siente tarde):
```javascript
{
  threshold: 0,
  rootMargin: '-18% 0px -42% 0px'
}
```

**Resultado**: Las tarjetas entrarán antes y seguirán activándose cerca del scanner.

---

### Paso 3: Bajar la animación de entrada

**Archivo**: `assets/css/app.css`
**Clase**: `.hp-shell`

**Agrega o modifica esto**:
```css
.hp-shell {
  --hp-reveal-y: 18px;
}
```

**Nota**: Si la variable ya existe con un valor mayor (30px, 40px+), bájala a 18px.

**Resultado**: La entrada se sentirá más natural y menos tardía.

---

### Paso 4: Ajustar tolerancia de los markers

**Archivo**: `assets/js/app.js`
**Función**: `updateTimelineMarkers()`

**Cambia esto** (línea 638):
```javascript
const tolerance = 22;
```

**Por esto**:
```javascript
const tolerance = 30;
```

**Resultado**: El punto rojo "enganchará" mejor cuando la tarjeta ya está entrando.

---

## 📋 Resumen de Cambios Exactos

### En `assets/js/app.js`:

1. **Línea 461** - Corregir viewportCenter:
```javascript
// ANTES:
const viewportCenter = window.innerHeight * 0.5;

// DESPUÉS:
const viewportCenter = (window.innerHeight * 0.5) - 92;
```

2. **Líneas 511-512** - Ajustar rootMargin:
```javascript
// ANTES:
rootMargin: '-45% 0px -45% 0px'

// DESPUÉS:
rootMargin: '-22% 0px -38% 0px'
```

3. **Línea 638** - Aumentar tolerancia:
```javascript
// ANTES:
const tolerance = 22;

// DESPUÉS:
const tolerance = 30;
```

### En `assets/css/app.css`:

4. **Agregar/modificar en .hp-shell**:
```css
.hp-shell {
  --hp-reveal-y: 18px;
}
```

---

## 🎯 Orden Exacto para Implementar

1. **Primero**: Cambia `viewportCenter` a `50vh - 92px`
2. **Segundo**: Cambia `rootMargin` a `-22% 0px -38% 0px`
3. **Tercero**: Cambia `tolerance` a `30`
4. **Cuarto**: Baja `--hp-reveal-y` a `18px`
5. **Final**: Recarga y prueba scroll lento entre Abril y Mayo

---

## 🧪 Qué Deberías Ver Después del Cambio

- ✅ La tarjeta empieza a aparecer antes
- ✅ El punto rojo y el scanner coinciden mejor
- ✅ El estado activo se siente más sincronizado
- ✅ El timeline se ve más editorial y menos "tardío"

---

## 🔍 Ajuste Fino Adicional (si es necesario)

Si después de probarlo todavía lo sientes apenas tarde, prueba este microajuste:

**En `assets/js/app.js`, línea 461**:
```javascript
// En lugar de:
const viewportCenter = (window.innerHeight * 0.5) - 92;

// Prueba:
const viewportCenter = (window.innerHeight * 0.5) - 80;
```

---

## 🎖️ Lectura Profesional

Tu base ya está bastante bien. El problema no es de layout grande, sino de **timing visual**. La mejora clave es:

1. **Usar un solo punto de referencia vertical**
2. **Abrir antes el reveal window**
3. **Dar más tolerancia al marcador**

Estos cambios quirúrgicos resolverán la desincronización sin necesidad de reestructurar todo el sistema.

---

## 🚀 Implementación Automática

Para aplicar estos cambios, puedes ejecutar los siguientes comandos:

```bash
# Backup de archivos originales
cp assets/js/app.js assets/js/app.js.backup
cp assets/css/app.css assets/css/app.css.backup

# Aplicar cambios a JavaScript
sed -i '' 's/const viewportCenter = window\.innerHeight \* 0\.5;/const viewportCenter = (window.innerHeight * 0.5) - 92;/' assets/js/app.js
sed -i '' "s/rootMargin: '-45% 0px -45% 0px'/rootMargin: '-22% 0px -38% 0px'/" assets/js/app.js
sed -i '' 's/const tolerance = 22;/const tolerance = 30;/' assets/js/app.js

# Aplicar cambios a CSS (si no existe la variable)
grep -q "hp-reveal-y" assets/css/app.css || echo "  --hp-reveal-y: 18px;" >> assets/css/app.css
```

---

## ✅ Checklist de Verificación

- [ ] Scanner rojo: `50vh - 92px` ✓
- [ ] Marker activo: `50vh - 92px` ✓  
- [ ] Tarjeta activa: `50vh - 92px` ✓
- [ ] RootMargin: `-22% 0px -38% 0px` ✓
- [ ] Tolerancia: `30px` ✓
- [ ] Reveal Y: `18px` ✓
- [ ] Test: Scroll entre Abril y Mayo ✓

Con estos cambios, el timeline debería sentirse perfectamente sincronizado y mucho más profesional.
