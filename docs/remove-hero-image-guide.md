# Guía Accionable: Eliminar hero_image del Flujo Estable

## Objetivo
Dejar claro que el hero estable es tipográfico, y que no depende de una imagen en el JSON.

## Cambios Realizados

### 1. ✅ templates/app-shell.php
**Eliminado:**
```php
$hero_image = $intro['hero_image'] ?? '';
```

**Estado:** El hero ahora es puramente tipográfico con título, subtítulo y byline.

### 2. ✅ data/heroes.json
**Eliminado:**
```json
"hero_image": "portada.webp"
```

**Resultado:** El JSON activo ya no contiene propiedades "muertas".

### 3. ✅ docs/data-schema.md
**Eliminado:**
```markdown
- `hero_image` (string): Nombre del archivo de imagen principal
```

**Resultado:** La documentación refleja la estructura actual del schema.

### 4. ✅ CSS y JavaScript
**Verificación:** No se encontraron reglas CSS ni código JavaScript relacionado con `hero_image`, `.hp-hero-image`, `.hp-hero-media` o `.hp-hero-visual`.

## Estructura Final del Hero

```html
<section class="hp-hero" aria-label="Héroes de la Paz">
    <div class="hp-container hp-hero-container">
        <div class="hp-hero-copy">
            <?php if ($title): ?>
                <h1 class="hp-title"><?php echo dhp_esc_html($title); ?></h1>
            <?php endif; ?>

            <?php if ($subtitle): ?>
                <p class="hp-subtitle"><?php echo dhp_esc_html($subtitle); ?></p>
            <?php endif; ?>

            <?php if ($byline_label): ?>
                <div class="hp-hero-byline-text">
                    <span><?php echo dhp_esc_html($byline_label); ?></span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
```

## Validación

Para verificar que los cambios fueron exitosos:

1. **Verificar PHP:** El template no debe contener referencias a `$hero_image`
2. **Verificar JSON:** El archivo `heroes.json` no debe contener `hero_image`
3. **Verificar Documentación:** El schema debe estar actualizado
4. **Probar Visualmente:** El hero debe mostrar solo contenido tipográfico

## Comandos de Verificación

```bash
# Buscar hero_image en el código
grep -r "hero_image" templates/ data/ docs/

# Verificar estructura del JSON
cat data/heroes.json | jq '.intro'

# Probar visualmente (si tienes servidor local)
# Visita la página y verifica que el hero sea tipográfico
```

## Resumen

✅ **Completado:** El flujo estable ahora es puramente tipográfico
✅ **Limpio:** No quedan propiedades "muertas" en el código
✅ **Documentado:** El schema refleja la estructura actual
✅ **Probado:** No hay CSS/JS relacionado que limpiar

El hero estable depende ahora exclusivamente de contenido tipográfico: título, subtítulo y byline.
