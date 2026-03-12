# PROMPT LISTO PARA PEGAR EN TU IA (Cursor/Windsurf/Copilot)

```
Vamos a ordenar correctamente las imágenes de los perfiles del proyecto "divergentes-heroes-paz".

OBJETIVO:
Asignar correctamente las fotos individuales a cada perfil dentro de data/heroes.json sin romper el sistema actual.

IMPORTANTE:
No modificar lógica JS ni estructura del timeline.
Solo trabajar en:
- nombres de imágenes
- campo "image" del JSON  
- separación de perfiles duplicados

--------------------------------------------------

PASO 1 — RESPALDO DE SEGURIDAD
Antes de tocar nada, ejecutar:

cp data/heroes.json data/heroes-backup.json
cp -r assets/images/ assets/images-backup/

--------------------------------------------------

PASO 2 — NORMALIZAR NOMBRES DE IMÁGENES

Renombrar todas las imágenes dentro de: assets/images/

REGLAS DE NAMING:
- minúsculas
- sin tildes  
- guiones en lugar de espacios
- formato .webp
- nombre igual al id del perfil

EJEMPLOS:
Holman Eliezer Zeledón.webp → holman-eliezer-zeledon.webp
Francisco Aráuz Pineda.webp → francisco-arauz-pineda.webp
Carlos Alberto Miranda.webp → carlos-alberto-miranda.webp

LISTA COMPLETA DE IMÁGENES ESPERADAS:
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

--------------------------------------------------

PASO 3 — CORREGIR heroes.json

Abrir: data/heroes.json

Cambiar todos los campos "image".
Actualmente muchos usan: "image": "img-1.webp"

Debe cambiarse a su archivo correspondiente.

EJEMPLO CORRECTO:
{
"id": "carlos-alberto-miranda",
"name": "Carlos Alberto Miranda", 
"image": "carlos-alberto-miranda.webp"
}

REGLA: image = id + ".webp"

--------------------------------------------------

PASO 4 — CASO ESPECIAL PERFIL DOBLE

Actualmente existe un perfil combinado:
Heriberto Maudiel Pérez y Kevin Coffin Reyes

ESTO DEBE SEPARARSE EN DOS PERFILES INDEPENDIENTES.

Eliminar el perfil combinado.
Crear dos objetos:

{
"id": "heriberto-maudiel-perez",
"name": "Heriberto Maudiel Pérez",
"image": "heriberto-maudiel-perez.webp"
}

{
"id": "kevin-coffin-reyes", 
"name": "Kevin Coffin Reyes",
"image": "kevin-coffin-reyes.webp"
}

--------------------------------------------------

PASO 5 — CASO MIGUEL RAMOS

Mantener nombre editorial completo pero imagen simple.

CORRECTO:
{
"id": "miguel-ramos",
"name": "Miguel Ramos, conocido como \"Franklin\"",
"image": "miguel-ramos.webp"
}

--------------------------------------------------

PASO 6 — MAPEO COMPLETO APLICAR

cristhiam-emilio-cadenas → cristhiam-emilio-cadenas.webp
carlos-alberto-miranda → carlos-alberto-miranda.webp  
holman-eliezer-zeledon → holman-eliezer-zeledon.webp
jose-alfredo-urroz-jiron → jose-alfredo-urroz-jiron.webp
jorge-gaston-palacios-vargas → jorge-gaston-palacios-vargas.webp
jose-abraham-martinez → jose-abraham-martinez.webp
kevin-cruz-ruiz → kevin-cruz-ruiz.webp
dixon-bismarck-soza-enrique → dixon-bismarck-soza-enrique.webp
carlos-jose-zamora-martinez → carlos-jose-zamora-martinez.webp
ariel-ignacio-vivas → ariel-ignacio-vivas.webp
francisco-arauz-pineda → francisco-arauz-pineda.webp
bismarck-martinez → bismarck-martinez.webp
miguel-ramos → miguel-ramos.webp
martin-exequiel-sanchez-gutierrez → martin-exequiel-sanchez-gutierrez.webp
faber-antonio-lopez-vivas → faber-antonio-lopez-vivas.webp
lenin-ernesto-olivas-alaniz → lenin-ernesto-olivas-alaniz.webp
pablo-ramos-chavarria → pablo-ramos-chavarria.webp
luis-david-lopez-hurtado → luis-david-lopez-hurtado.webp
jorge-isaac-cruz-centeno → jorge-isaac-cruz-centeno.webp
carlos-alberto-suce-ortiz → carlos-alberto-suce-ortiz.webp

NUEVOS PERFILES SEPARADOS:
heriberto-maudiel-perez → heriberto-maudiel-perez.webp
kevin-coffin-reyes → kevin-coffin-reyes.webp

--------------------------------------------------

PASO 7 — NO MODIFICAR

No cambiar:
- JS
- render del timeline
- estructura del plugin  
- componentes

--------------------------------------------------

PASO 8 — VERIFICACIÓN FINAL

Después de aplicar cambios:

1. Abrir el sitio local
2. Abrir DevTools → Console
3. Ir a Network → Images
4. Recargar página

TODAS LAS IMÁGENES DEBEN RESPONDER: 200 OK
NO DEBE HABER: 404

--------------------------------------------------

PASO 9 — REVISIÓN VISUAL

Verificar manualmente:
✔ foto correcta corresponda al nombre correcto
✔ ninguna tarjeta use img-1.webp  
✔ recorte visual de fotos sea consistente
✔ layout no se rompa en mobile
✔ perfiles separados no generen confusión

--------------------------------------------------

PASO 10 — COMMIT

Cuando todo funcione:

git add .
git commit -m "feat(profiles): assign hero images and normalize filenames"
git push

--------------------------------------------------

RESULTADO FINAL
• cada perfil con su foto correcta
• JSON limpio
• naming profesional  
• sistema escalable
• plugin listo para demo

--------------------------------------------------

EXTRA — MEJORA PERFORMANCE

Agregar loading="lazy" a las imágenes de perfiles para mejorar performance:

<img 
  src="assets/images/${hero.image}" 
  alt="${hero.name}"
  loading="lazy"
  width="120" 
  height="120"
>
```
