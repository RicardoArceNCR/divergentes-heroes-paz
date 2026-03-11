# Guía: Corrección Editorial Definitiva

## 🎯 Objetivo

Reestructurar el contenido para seguir la jerarquía editorial correcta:

```
Hero
├── título
├── subtítulo

Intro editorial
├── lede principal
├── párrafos de contexto

Timeline
```

## 🚨 Problema Actual

**Duplicación de firma editorial:**
- Hero tiene "Por Divergentes" (byline)
- Intro tiene "Por Divergentes" (imagen)

**Resultado:** Jerarquía ensuciada y responsive roto.

## 🔧 Solución: Eliminar 2 Bloques

### A. Eliminar byline del Hero
**Bloque a eliminar:**
```html
<div class="hp-hero-byline-text hp-hero__byline">
    <span>Por Divergentes</span>
</div>
```

### B. Eliminar imagen byline del Intro  
**Bloque a eliminar:**
```html
<div class="hp-byline hp-intro__byline">
    <img src="..." alt="Por Divergentes" />
</div>
```

## 📝 Nueva Intro Editorial

**Estructura correcta dentro de `hp-intro-copy`:**
```html
<div class="hp-intro-copy hp-intro__copy">
    <p class="hp-lede hp-intro__lede" data-hp-line-start>
        Tras la crisis sociopolítica de abril de 2018 en Nicaragua, el régimen Ortega-Murillo elevó a ciertos civiles armados y policías fallecidos durante las protestas a la categoría de "héroes de la paz".
    </p>

    <p class="hp-intro-paragraph hp-intro__paragraph">
        Entre ellos figuran militantes sandinistas, trabajadores del Estado y miembros de la Policía Nacional que participaron en operativos de represión contra manifestantes y en el desmantelamiento de tranques.
    </p>

    <p class="hp-intro-paragraph hp-intro__paragraph">
        El relato oficial presenta esas muertes como actos de lealtad y defensa de la paz, mientras documentos de organismos internacionales como la CIDH y el GIEI señalan responsabilidades en hechos de violencia letal, tortura y represión sistemática contra manifestantes.
    </p>
</div>
```

## 🎨 Versión Extendida (Opcional)

Si quieres más densidad editorial:
```html
<div class="hp-intro-copy hp-intro__copy">
    <p class="hp-lede hp-intro__lede" data-hp-line-start>
        Tras la crisis sociopolítica de abril de 2018 en Nicaragua, el régimen Ortega-Murillo elevó a ciertos civiles armados y policías fallecidos durante las protestas a la categoría de "héroes de la paz".
    </p>

    <p class="hp-intro-paragraph hp-intro__paragraph">
        Entre ellos figuran militantes sandinistas, trabajadores del Estado y miembros de la Policía Nacional que participaron en operativos de represión contra manifestantes y en el desmantelamiento de tranques.
    </p>

    <p class="hp-intro-paragraph hp-intro__paragraph">
        El relato oficial presenta esas muertes como actos de lealtad y defensa de la paz, mientras informes de organismos internacionales como la CIDH y el GIEI documentan un contexto de violencia letal, tortura y represión sistemática contra manifestantes.
    </p>

    <p class="hp-intro-paragraph hp-intro__paragraph">
        Esta línea de tiempo revisa quiénes fueron, cómo fueron incorporados a la narrativa oficial y qué revela esa construcción simbólica sobre la justificación de la violencia estatal en Nicaragua.
    </p>
</div>
```

## 🏗️ Sección Intro Completa

**HTML final para copiar y pegar:**
```html
<section class="hp-intro hp-intro--dark">
    <div class="hp-container hp-intro-container">
        <img class="hp-intro-rail-art"
             src="http://divergentes.local/wp-content/plugins/divergentes-heroes-paz/assets/images/hero-connector.png"
             alt=""
             aria-hidden="true"
             decoding="async"
             loading="lazy" />

        <div class="hp-intro-copy hp-intro__copy">
            <p class="hp-lede hp-intro__lede" data-hp-line-start">
                Tras la crisis sociopolítica de abril de 2018 en Nicaragua, el régimen Ortega-Murillo elevó a ciertos civiles armados y policías fallecidos durante las protestas a la categoría de "héroes de la paz".
            </p>

            <p class="hp-intro-paragraph hp-intro__paragraph">
                Entre ellos figuran militantes sandinistas, trabajadores del Estado y miembros de la Policía Nacional que participaron en operativos de represión contra manifestantes y en el desmantelamiento de tranques.
            </p>

            <p class="hp-intro-paragraph hp-intro__paragraph">
                El relato oficial presenta esas muertes como actos de lealtad y defensa de la paz, mientras documentos de organismos internacionales como la CIDH y el GIEI señalan responsabilidades en hechos de violencia letal, tortura y represión sistemática contra manifestantes.
            </p>
        </div>
    </div>
</section>
```

## ✅ Hero Final Limpio

**HTML esperado después de la corrección:**
```html
<section class="hp-hero" aria-label="Los &quot;héroes de la paz&quot; sin pasamontañas">
    <div class="hp-container hp-hero-container">
        <div class="hp-hero-copy hp-hero__copy">
            <h1 class="hp-title hp-hero__title">
                <span class="hp-hero-title-main">Los «héroes de la paz»</span>
                <span class="hp-hero-highlight" aria-label="sin pasamontañas">
                    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--tl" aria-hidden="true"></span>
                    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--tr" aria-hidden="true"></span>
                    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--bl" aria-hidden="true"></span>
                    <span class="hp-hero-highlight__corner hp-hero-highlight__corner--br" aria-hidden="true"></span>
                    <span class="hp-hero-highlight__lead">SIN</span>
                    <span class="hp-hero-highlight__text">PASAMONTAÑAS</span>
                </span>
            </h1>

            <p class="hp-subtitle hp-hero__subtitle">
                Quiénes son en realidad los sandinistas que el régimen Ortega-Murillo glorifica
            </p>
        </div>
    </div>
</section>
```

## 🎯 Por Qué Esta Es la Forma Correcta

### 1. **Jerarquía Visual Respetada**
- Hero limpio, sin sobrecarga
- Intro editorial donde debe vivir

### 2. **Semántica Correcta**
- Byline ≠ Intro
- Firma no debe cargar párrafos largos

### 3. **Responsive Preservado**
- Clases de byline diseñadas para texto corto
- Intro usa clases para párrafos largos

### 4. **Producto Editorial Profesional**
- Más intencional y vendible
- Estructura enterprise/editorial

## 🚫 Qué NO Hacer

**No mezclar:**
```html
<!-- INCORRECTO -->
<div class="hp-hero-byline-text hp-hero__byline">
    <span>Tras la crisis sociopolítica...</span>
</div>

<!-- INCORRECTO -->
<div class="hp-byline hp-intro__byline">
    <p>Texto largo...</p>
</div>
```

**No poner texto largo en clases de byline.**

## 📋 Checklist Definitivo

Antes de cerrar, verifica:

- [ ] Eliminado `hp-hero-byline-text hp-hero__byline`
- [ ] Eliminado `hp-byline hp-intro__byline`
- [ ] Solo un `hp-lede` en la intro
- [ ] 2-3 `hp-intro-paragraph` en la intro
- [ ] Sin texto largo en clases de byline
- [ ] Probado desktop y mobile
- [ ] Rail art alineado con inicio de intro

## 🎯 Resumen Ultraconcentrado

**La forma correcta:**
1. Eliminar "Por Divergentes" del hero
2. Eliminar "Por Divergentes" del intro  
3. Usar nueva intro dentro de `hp-intro-copy`
4. Dejar hero solo con título y subtítulo

**Resultado:** Apertura editorial sólida y profesional.
