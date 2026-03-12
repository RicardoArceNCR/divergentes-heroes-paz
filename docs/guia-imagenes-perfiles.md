# Guía Quirúrgica: Corrección de Imágenes y Perfiles

## Objetivo
Asignar correctamente las fotos individuales a cada perfil dentro de `data/heroes.json` sin romper el sistema actual.

**IMPORTANTE:** No modificar lógica JS ni estructura del timeline. Solo trabajar en:
- Nombres de imágenes  
- Campo "image" del JSON
- Separación de perfiles duplicados

---

## PASO 1 — RESPALDO SEGURIDAD

Antes de tocar nada, crear respaldos:

```bash
# Respaldar JSON
cp data/heroes.json data/heroes-backup.json

# Respaldar carpeta de imágenes
cp -r assets/images/ assets/images-backup/
```

---

## PASO 2 — NORMALIZAR NOMBRES DE IMÁGENES

Renombrar todas las imágenes dentro de: `assets/images/`

### Reglas de naming:
- **minúsculas**
- **sin tildes**
- **guiones** en lugar de espacios
- formato **.webp**
- nombre igual al **id** del perfil

### Ejemplos de conversión:
```
Holman Eliezer Zeledón.webp     → holman-eliezer-zeledon.webp
Francisco Aráuz Pineda.webp     → francisco-arauz-pineda.webp
Carlos Alberto Miranda.webp     → carlos-alberto-miranda.webp
José Alfredo Urroz Jirón.webp   → jose-alfredo-urroz-jiron.webp
```

### Lista completa de imágenes esperadas:
```
cristhiam-emilio-cadenas.webp
carlos-alberto-miranda.webp
holman-eliezer-zeledon.webp
jose-alfredo-urroz-jiron.webp
jorge-gaston-palacios-vargas.webp
jose-abraham-martinez.webp
kevin-cruz-ruiz.webp
dixon-bismarck-soza-enrique.webp
carlos-jose-zamora-martinez.webp
ariel-ignacio-vivas.webp
francisco-arauz-pineda.webp
bismarck-martinez.webp
miguel-ramos.webp
martin-exequiel-sanchez-gutierrez.webp
faber-antonio-lopez-vivas.webp
lenin-ernesto-olivas-alaniz.webp
pablo-ramos-chavarria.webp
luis-david-lopez-hurtado.webp
jorge-isaac-cruz-centeno.webp
carlos-alberto-suce-ortiz.webp
heriberto-maudiel-perez.webp
kevin-coffin-reyes.webp
```

---

## PASO 3 — CORREGIR heroes.json

Abrir: `data/heroes.json`

Cambiar todos los campos `"image"`.

Actualmente muchos usan: `"image": "img-1.webp"`

Debe cambiarse a su archivo correspondiente.

### Ejemplo correcto:
```json
{
  "id": "carlos-alberto-miranda",
  "name": "Carlos Alberto Miranda",
  "image": "carlos-alberto-miranda.webp"
}
```

**Regla:** `image = id + ".webp"`

---

## PASO 4 — CASO ESPECIAL PERFIL DOBLE

Actualmente existe un perfil combinado:
`Heriberto Maudiel Pérez y Kevin Coffin Reyes`

**Esto debe separarse en dos perfiles independientes.**

1. Eliminar el perfil combinado actual
2. Crear dos objetos separados:

```json
{
  "id": "heriberto-maudiel-perez",
  "name": "Heriberto Maudiel Pérez",
  "image": "heriberto-maudiel-perez.webp"
},
{
  "id": "kevin-coffin-reyes",
  "name": "Kevin Coffin Reyes",
  "image": "kevin-coffin-reyes.webp"
}
```

---

## PASO 5 — CASO MIGUEL RAMOS

Mantener nombre editorial completo pero imagen simple.

**Correcto:**
```json
{
  "id": "miguel-ramos",
  "name": "Miguel Ramos, conocido como \"Franklin\"",
  "image": "miguel-ramos.webp"
}
```

---

## PASO 6 — MAPEO COMPLETO DE CAMBIOS

Aplicar estos cambios en todo el JSON:

| ID Actual | Image Nuevo |
|-----------|-------------|
| cristhiam-emilio-cadenas | cristhiam-emilio-cadenas.webp |
| carlos-alberto-miranda | carlos-alberto-miranda.webp |
| holman-eliezer-zeledon | holman-eliezer-zeledon.webp |
| jose-alfredo-urroz-jiron | jose-alfredo-urroz-jiron.webp |
| jorge-gaston-palacios-vargas | jorge-gaston-palacios-vargas.webp |
| jose-abraham-martinez | jose-abraham-martinez.webp |
| kevin-cruz-ruiz | kevin-cruz-ruiz.webp |
| dixon-bismarck-soza-enrique | dixon-bismarck-soza-enrique.webp |
| carlos-jose-zamora-martinez | carlos-jose-zamora-martinez.webp |
| ariel-ignacio-vivas | ariel-ignacio-vivas.webp |
| francisco-arauz-pineda | francisco-arauz-pineda.webp |
| bismarck-martinez | bismarck-martinez.webp |
| miguel-ramos | miguel-ramos.webp |
| martin-exequiel-sanchez-gutierrez | martin-exequiel-sanchez-gutierrez.webp |
| faber-antonio-lopez-vivas | faber-antonio-lopez-vivas.webp |
| lenin-ernesto-olivas-alaniz | lenin-ernesto-olivas-alaniz.webp |
| pablo-ramos-chavarria | pablo-ramos-chavarria.webp |
| luis-david-lopez-hurtado | luis-david-lopez-hurtado.webp |
| jorge-isaac-cruz-centeno | jorge-isaac-cruz-centeno.webp |
| carlos-alberto-suce-ortiz | carlos-alberto-suce-ortiz.webp |

**Nuevos perfiles separados:**
| ID | Nombre | Image |
|----|--------|-------|
| heriberto-maudiel-perez | Heriberto Maudiel Pérez | heriberto-maudiel-perez.webp |
| kevin-coffin-reyes | Kevin Coffin Reyes | kevin-coffin-reyes.webp |

---

## PASO 7 — NO MODIFICAR

**No cambiar:**
- Lógica JS
- Render del timeline  
- Estructura del plugin
- Componentes

---

## PASO 8 — VERIFICACIÓN FINAL

Después de aplicar cambios:

1. Abrir el sitio local
2. Abrir **DevTools → Console**
3. Ir a **Network → Images**
4. Recargar la página

**Todas las imágenes deben responder:** `200 OK`

**No debe haber:** `404` en imágenes

---

## PASO 9 — REVISIÓN VISUAL

Verificar manualmente:

- [ ] Foto correcta corresponda al nombre correcto
- [ ] Ninguna tarjeta use `img-1.webp`
- [ ] Recorte visual de fotos sea consistente  
- [ ] Layout no se rompa en mobile
- [ ] Perfiles separados no generen confusión

---

## PASO 10 — COMMIT FINAL

Cuando todo funcione:

```bash
git add .
git commit -m "feat(profiles): assign hero images and normalize filenames"
git push
```

---

## RESULTADO FINAL

✅ Cada perfil con su foto correcta  
✅ JSON limpio y consistente  
✅ Naming profesional y escalable  
✅ Plugin listo para demo  
✅ Sistema mantenible a futuro

---

## EXTRA — MEJORA DE PERFORMANCE

Opcional: Agregar `loading="lazy"` a las imágenes de perfiles:

```html
<img 
  src="assets/images/${hero.image}" 
  alt="${hero.name}"
  loading="lazy"
  width="120"
  height="120"
>
```

Esto mejora performance y evita layout shift.
