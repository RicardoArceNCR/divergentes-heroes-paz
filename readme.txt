Divergentes — Héroes de la Paz

Instalación

1) Comprime esta carpeta como ZIP.
2) En WordPress: Plugins -> Añadir nuevo -> Subir plugin -> Activar.
3) Crea una página y agrega el shortcode:

[heroes_paz slug="heroes-de-la-paz"]

Datos

- Edita: data/heroes.json
- Mantén consistentes: months[].id y events[].month_id

Reglas (enterprise)

- events[].month_id debe existir en months[].id
- events[].id debe ser único y estable (sirve para anclas y deep-link)
- profile.mode:
  - modal: requiere profile.body
  - link: requiere profile.url
- photo.src debe ser URL (ideal: WordPress Media Library) y recomendado en WebP
- Evita meter HTML en el JSON (si se necesita, hay que definir sanitización)

Plantilla

- El archivo data/heroes.json incluye meses y 3 eventos de ejemplo para pruebas visuales.

Notas

- CSS/JS se cargan únicamente cuando se usa el shortcode.
- El fallback SEO se renderiza como HTML indexable y se oculta cuando el JS hidrata la UI.

Analytics (opcional)

- El frontend emite CustomEvent en window:
  - timeline_month_view (detail: { monthId })
  - profile_open (detail: { eventId, monthId })
  - source_click (detail: { href })
