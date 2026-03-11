# Divergentes – Héroes de la Paz Timeline

Plugin editorial desarrollado para **Divergentes** que presenta una línea de tiempo interactiva sobre los llamados *“héroes de la paz”* del régimen Ortega-Murillo en Nicaragua.

El proyecto está diseñado como una **pieza narrativa digital optimizada para periodismo investigativo**, con foco en:

- lectura **mobile first**
- navegación cronológica clara
- arquitectura CSS estable
- integración limpia con WordPress

---

# Estado del proyecto

**Versión actual:** `stable-mobile-v1`

Esta versión representa la **base visual estable del proyecto**, después de varias iteraciones de optimización del layout.

Objetivos logrados:

- eliminar desbordamientos horizontales
- estabilizar el timeline en mobile
- asegurar consistencia entre hero, intro y cards
- simplificar la arquitectura CSS

A partir de esta versión, los cambios deben enfocarse en **polish visual y mejoras progresivas**, no en reconstrucción del layout.

---

# Instalación

## 1. Copiar el plugin en WordPress

Coloca la carpeta del plugin dentro de:


wp-content/plugins/


La estructura debe quedar así:


wp-content/
plugins/
divergentes-heroes-paz/


---

## 2. Activar el plugin

En el panel de WordPress:


Plugins → Plugins instalados


Activa:


Divergentes – Héroes de la Paz Timeline


---

## 3. Crear una página para el especial

Crea una nueva página en WordPress, por ejemplo:


/heroes-de-la-paz


---

## 4. Insertar el shortcode del timeline

En el contenido de la página agrega:


[divergentes_heroes_paz]


Esto cargará el layout completo del especial.

---

## 5. Verificar carga de datos

El timeline se alimenta desde:


data/heroes.json


Si agregas o modificas eventos, solo debes editar ese archivo.

---

# Características principales

## Hero editorial

Sección de apertura con:

- título principal
- badge editorial **“SIN PASAMONTAÑAS”**
- subtítulo contextual
- byline
- imagen de portada

El hero está optimizado para **mobile first**.

---

## Introducción contextual

Sección narrativa que explica el contexto del especial.

Incluye:

- fondo oscuro
- tipografía editorial
- párrafos largos optimizados para lectura
- transición hacia el timeline

---

## Timeline interactivo

El timeline es el núcleo del proyecto.

Características:

- navegación por meses
- chips horizontales scrollables
- cards cronológicas
- conexión visual mediante línea temporal

Cada evento incluye:

- categoría
- fecha
- resumen
- versión oficial del régimen
- enlace a perfil completo
- imagen contextual

---

# Arquitectura del plugin

Estructura general:


divergentes-heroes-paz/

assets/
css/
app.css
page.css
tokens.css

js/
app.js

images/

data/
heroes.json

templates/
app-shell.php

divergentes-heroes-paz.php


---

# Flujo de render

El flujo de render funciona así:

1. WordPress carga el plugin
2. el plugin inyecta `app-shell.php`
3. el JSON `heroes.json` alimenta el timeline
4. `app.js` construye la interfaz
5. `app.css` controla el layout editorial

---

# Arquitectura CSS

El CSS está dividido en capas claras.

## page.css

Responsable de:

- integración con WordPress
- neutralizar padding del theme
- controlar el fullbleed
- evitar overflow horizontal

No contiene estilos de componentes.

---

## app.css

Responsable de:

- layout editorial
- hero
- timeline
- cards
- navegación horizontal
- responsive

---

## tokens.css

Define variables globales como:

- colores
- tipografía
- spacing
- breakpoints

Permite mantener consistencia visual.

---

# Sistema de layout

El layout utiliza un contenedor central:


.hp-container


Este contenedor controla:

- ancho máximo
- respiración lateral
- consistencia entre secciones

En mobile se usa cálculo dinámico:


width: min(100% - gutter, max-width)


Esto evita desbordamientos.

---

# Mobile first

El proyecto está optimizado para:

- iPhone
- Android estándar
- tablet vertical

Reglas clave del layout:


max-width: 100%
min-width: 0
overflow-wrap: break-word


Esto previene que textos o imágenes rompan el layout.

---

# Timeline navigation

La navegación del timeline usa scroll horizontal:


.hp-sticky-nav-inner


Características:

- scroll horizontal
- soporte táctil
- chips con `flex: 0 0 auto`
- scrollbar oculto

Esto evita que los chips se compriman.

---

# JSON de datos

Los eventos se almacenan en:


data/heroes.json


Cada registro contiene:

- nombre
- fecha
- categoría
- resumen
- versión oficial
- imagen
- enlace a perfil

Esto permite mantener el contenido separado del layout.

---

# Principios de diseño

El proyecto sigue principios de **diseño editorial digital**:

1. claridad narrativa  
2. lectura cómoda en mobile  
3. jerarquía visual fuerte  
4. cronología clara  
5. ritmo visual entre texto e imagen  

---

# Buenas prácticas del proyecto

Evitar:

- usar `max-width:none` en contenedores
- añadir padding lateral al container base
- introducir márgenes negativos nuevos
- alterar el sistema fullbleed sin pruebas

Cambios futuros deben concentrarse en:

- tipografía
- espaciado
- accesibilidad
- performance

---

# Compatibilidad WordPress

El plugin está preparado para convivir con:

- `.wp-site-blocks`
- `.has-global-padding`
- `.is-layout-constrained`
- admin bar

Se aplican neutralizaciones mínimas desde `page.css`.

---

# Testing recomendado

Antes de publicar cambios revisar:

- iPhone pequeño
- Android estándar
- tablet vertical
- desktop

Checklist clave:

- no overflow horizontal
- cards dentro del viewport
- chips navegables
- imágenes responsivas

---

# Próximas mejoras posibles

- animación de progreso del timeline
- lazy loading de imágenes
- accesibilidad ARIA en navegación
- deep linking por evento
- modo oscuro opcional

---

# Requisitos


WordPress 6+
PHP 7.4+
Navegador moderno


---

# Créditos

Investigación y contenido: **Divergentes**  
Diseño y desarrollo: **Atom Studio**