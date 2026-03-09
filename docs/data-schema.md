# Data Schema - Divergentes Héroes de la Paz

## Overview

El plugin utiliza un archivo JSON como fuente única de datos editorial. Este schema define la estructura esperada para asegurar compatibilidad y estabilidad.

## Schema Structure

```json
{
  "meta": {},
  "intro": {},
  "months": [],
  "events": []
}
```

## Fields

### meta

Información editorial principal.

- `title` (string, required): Título del especial
- `subtitle` (string): Subtítulo o dek
- `dek` (string): Descripción corta para SEO
- `author` (string): Autor o medio
- `updated_at` (string): Fecha de última actualización (YYYY-MM-DD)

### intro

Contenido introductorio del especial.

- `paragraphs` (array, required): Array de párrafos en orden
- `byline_label` (string): Firma del autor (ej: "Por Divergentes")
- `hero_image` (string): Nombre del archivo de imagen principal
- `byline_image` (string): Nombre del archivo de imagen de firma

### months

Mesos del timeline.

- `id` (string, required): Identificador único (ej: "2018-04")
- `label` (string, required): Etiqueta visible (ej: "Abril 2018")
- `chapter` (string): Nombre del capítulo editorial

### events

Perfiles individuales.

- `id` (string, required): Identificador único
- `name` (string, required): Nombre completo del perfil
- `role` (string): Rol o categoría (ej: "Militante sandinista")
- `date` (string, required): Fecha en formato YYYY-MM-DD
- `monthId` (string, required): Referencia a month.id
- `location` (string): Ubicación del evento
- `image` (string): Nombre del archivo de imagen
- `kicker` (string): Etiqueta corta de ubicación
- `summary` (string, required): Resumen editorial breve
- `official_version` (string): Versión oficial del régimen
- `body` (array): Párrafos del perfil completo
- `sources` (array): Fuentes y referencias

#### sources structure

```json
{
  "label": "CIDH",
  "type": "organismo",
  "url": "https://ejemplo.com",
  "note": "Nota opcional"
}
```

## Rules

1. **Required fields**: `meta.title`, `intro.paragraphs`, `months[].id`, `months[].label`, `events[].id`, `events[].name`, `events[].date`, `events[].monthId`, `events[].summary`
2. **Date format**: Always YYYY-MM-DD for events
3. **Month references**: Every event.monthId must exist in months[].id
4. **Image naming**: Use webp format when possible
5. **Field naming**: Use camelCase for consistency

## Validation

El plugin valida automáticamente:
- Estructura JSON válida
- Presencia de campos requeridos
- Consistencia entre months y events
- Formato de fechas

## Migration

Para actualizar desde versiones anteriores:

1. Mover contenido duro del template a `meta` e `intro`
2. Convertir arrays planos a objetos con estructura definida
3. Unificar nombres de campos (ej: `display_date` → `displayDate`)
4. Agregar `sources` cuando sea posible
