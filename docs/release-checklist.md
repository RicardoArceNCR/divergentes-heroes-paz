# Release Checklist - Divergentes Héroes de la Paz

## Antes de empaquetar

### Limpieza
- [ ] Eliminar .DS_Store
- [ ] Eliminar __MACOSX
- [ ] Eliminar archivos .backup
- [ ] Eliminar archivos .old
- [ ] Eliminar carpeta .git del ZIP

### Estructura
- [ ] Verificar estructura de carpetas correcta
- [ ] Confirmar que no haya archivos duplicados
- [ ] Validar que todas las imágenes estén en assets/images/
- [ ] Confirmar que las fuentes estén en fonts/

### Código
- [ ] Versión correcta en divergentes-heroes-paz.php
- [ ] JSON válido (validador online)
- [ ] Shortcode probado en WordPress
- [ ] Fallback SEO probado sin JS
- [ ] Mobile responsive probado

### Assets
- [ ] CSS minificado si aplica
- [ ] JS sin console.log
- [ ] Imágenes optimizadas (webp)
- [ ] Fuentes cargando correctamente

### Documentación
- [ ] README actualizado
- [ ] Schema de datos documentado
- [ ] Cambios registrados en changelog

## Testing

### WordPress
- [ ] Instalación limpia
- [ ] Activación sin errores
- [ ] Shortcode renderiza
- [ ] Assets cargan correctamente
- [ ] Multi-instancia funciona

### Navegadores
- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari
- [ ] Edge
- [ ] Mobile responsive

### Contenido
- [ ] Todos los perfiles cargan
- [ ] Timeline funciona
- [ ] Modal abre correctamente
- [ ] Navegación por mes activa
- [ ] Fallback SEO muestra contenido

## Empaquetado

### ZIP structure
```
divergentes-heroes-paz/
├── divergentes-heroes-paz.php
├── readme.txt
├── inc/
├── templates/
├── assets/
├── data/
├── fonts/
├── languages/
└── docs/ (opcional)
```

### Nombre de archivo
- Formato: `divergentes-heroes-paz-vX.Y.Z.zip`
- Ejemplo: `divergentes-heroes-paz-v1.0.0.zip`

## Post-lanzamiento

### WordPress.org
- [ ] Subir a SVN
- [ ] Crear tag en GitHub
- [ ] Actualizar readme.txt para wordpress.org

### Comunicación
- [ ] Nota de lanzamiento
- [ ] Documentación actualizada
- [ ] Ejemplo de uso actualizado
