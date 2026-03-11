(function () {
  'use strict';

  const dataCache = new Map(); // url -> Promise<data>
  const instanceState = new WeakMap(); // root -> { destroyFns: [] }

  function registerDestroy(root, fn) {
    if (!root || typeof fn !== 'function') return;
    const s = instanceState.get(root) || { destroyFns: [] };
    s.destroyFns.push(fn);
    instanceState.set(root, s);
  }

  function destroyInstance(root) {
    const s = instanceState.get(root);
    if (!s) return;
    for (const fn of s.destroyFns) {
      try { fn(); } catch (_) { }
    }
    instanceState.delete(root);
  }

  function safeJsonParse(raw) {
    try {
      return JSON.parse(raw);
    } catch (_e) {
      return null;
    }
  }

  function escapeHtml(str) {
    return String(str)
      .replaceAll('&', '&amp;')
      .replaceAll('<', '&lt;')
      .replaceAll('>', '&gt;')
      .replaceAll('"', '&quot;')
      .replaceAll("'", '&#039;');
  }

  function cssEscape(value) {
    const str = String(value == null ? '' : value);
    if (window.CSS && typeof window.CSS.escape === 'function') return window.CSS.escape(str);
    return str.replace(/[^a-zA-Z0-9_\-]/g, '\\$&');
  }

  function resolveAssetUrl(src, imagesBaseUrl) {
    const raw = String(src == null ? '' : src).trim();
    if (!raw) return '';
    if (/^https?:\/\//i.test(raw)) return raw;
    if (/^data:/i.test(raw)) return raw;
    if (/^\//.test(raw)) return raw;
    if (!imagesBaseUrl) return raw;
    if (/^\.\/?images\//i.test(raw)) {
      const rel = raw.replace(/^\.\/?images\//i, '');
      return String(imagesBaseUrl).replace(/\/+$/, '') + '/' + rel;
    }
    // Bare filename (no slashes) — resolve against imagesBaseUrl
    if (!/\//.test(raw)) {
      return String(imagesBaseUrl).replace(/\/+$/, '') + '/' + raw;
    }
    return raw;
  }

  /* ---- Spanish date formatter (e.g. "16 de mayo") ---- */
  const MONTH_NAMES_ES = [
    'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
    'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
  ];
  function spanishDisplayDate(isoDate) {
    if (!isoDate) return '';
    const parts = String(isoDate).split('-');
    if (parts.length < 3) return String(isoDate);
    const day = parseInt(parts[2], 10);
    const monthIdx = parseInt(parts[1], 10) - 1;
    const monthName = MONTH_NAMES_ES[monthIdx] || '';
    return day + ' de ' + monthName;
  }

  /**
   * Normalize an event from the new JSON schema to the format
   * that the renderer expects (legacy schema).
   */
  function normalizeEvent(e, imagesBaseUrl) {
    if (!e) return e;
    // month_id: support both monthId and month_id
    if (!e.month_id && e.monthId) e.month_id = e.monthId;
    // display_date: derive from date if missing
    if (!e.display_date && e.date) e.display_date = spanishDisplayDate(e.date);
    // place: support location fallback
    if (!e.place && e.location) e.place = e.location;
    // context: map summary -> context
    if (!e.context && e.summary) e.context = e.summary;
    // contrast: map description -> contrast
    if (!e.contrast && e.description) e.contrast = e.description;
    // photo: resolve from flat image string
    if (!e.photo || (!e.photo.src && e.image)) {
      const imgSrc = e.image || 'img-1.webp';
      const resolvedSrc = resolveAssetUrl(imgSrc, imagesBaseUrl);
      e.photo = {
        src: resolvedSrc,
        alt: e.name ? 'Retrato de ' + e.name : ''
      };
    }
    // profile: build from body[] array
    if (!e.profile && Array.isArray(e.body) && e.body.length) {
      e.profile = {
        mode: 'modal',
        body: e.body.join('\n\n'),
        sources: []
      };
    }
    // category: map role to category if missing
    if ((!e.category || !e.category.length) && e.role) {
      e.category = [e.role];
    }
    return e;
  }

  function groupByMonth(events) {
    const map = new Map();
    for (const e of events) {
      const key = String(e.month_id || e.monthId || '');
      if (!map.has(key)) map.set(key, []);
      map.get(key).push(e);
    }
    return map;
  }

  function formatMeta(e) {
    const parts = [];
    if (e.display_date) parts.push(e.display_date);
    if (e.place) parts.push(e.place);
    return parts.join(' — ');
  }

  function getInstanceCtx(root) {
    const shell = root && root.closest ? root.closest('.hp-shell') : null;
    if (!shell) return null;

    const track = shell.querySelector('[data-hp-track]');
    const trackInner = track ? track.querySelector('.hp-track-inner') : null;

    return {
      root,
      shell,
      track,
      trackInner,
      fill: shell.querySelector('[data-hp-track-fill]'),
      startEl: shell.querySelector('[data-hp-line-start]'),
    };
  }

  function getFocusable(container) {
    return Array.from(
      container.querySelectorAll(
        'a[href], button:not([disabled]), textarea, input, select, [tabindex]:not([tabindex="-1"])'
      )
    );
  }

  function createModal(mountEl, instanceId) {
    const uid = 'hp-' + String(instanceId || 'x');
    const titleId = uid + '-modal-title';
    const metaId = uid + '-modal-meta';
    const bodyId = uid + '-modal-body';

    const backdrop = document.createElement('div');
    backdrop.className = 'hp-modal-backdrop';
    backdrop.setAttribute('role', 'dialog');
    backdrop.setAttribute('aria-modal', 'true');
    backdrop.setAttribute('aria-label', 'Perfil');

    backdrop.innerHTML =
      '<div class="hp-modal" role="document">' +
      '  <div class="hp-modal-header">' +
      '    <div>' +
      '      <h2 class="hp-modal-title" id="' + titleId + '"></h2>' +
      '      <p class="hp-kv" id="' + metaId + '"></p>' +
      '    </div>' +
      '    <button type="button" class="hp-modal-close" data-hp-close>Salir</button>' +
      '  </div>' +
      '  <div class="hp-modal-body" id="' + bodyId + '"></div>' +
      '</div>';

    const mount = mountEl && mountEl.appendChild ? mountEl : document.body;
    mount.appendChild(backdrop);

    const modal = backdrop.querySelector('.hp-modal');
    const btnClose = backdrop.querySelector('[data-hp-close]');
    const titleEl = backdrop.querySelector('#' + cssEscape(titleId));
    const metaEl = backdrop.querySelector('#' + cssEscape(metaId));
    const bodyEl = backdrop.querySelector('#' + cssEscape(bodyId));

    if (modal) {
      modal.setAttribute('aria-labelledby', titleId);
      modal.setAttribute('aria-describedby', bodyId);
    }

    let lastActive = null;
    let onDocKeydown = null;

    function close() {
      backdrop.classList.remove('is-open');
      backdrop.style.display = '';
      bodyEl.innerHTML = '';
      if (lastActive && typeof lastActive.focus === 'function') lastActive.focus();
      lastActive = null;
      document.documentElement.style.overflow = '';

      if (onDocKeydown) {
        document.removeEventListener('keydown', onDocKeydown);
        onDocKeydown = null;
      }
    }

    function open(payload, triggerEl) {
      lastActive = triggerEl || document.activeElement;
      titleEl.textContent = payload.title || '';
      metaEl.textContent = payload.meta || '';
      bodyEl.innerHTML = payload.html || '';

      backdrop.classList.add('is-open');
      document.documentElement.style.overflow = 'hidden';

      if (!onDocKeydown) {
        onDocKeydown = onKeydown;
        document.addEventListener('keydown', onDocKeydown);
      }

      const focusable = getFocusable(backdrop);
      const first = focusable[0] || btnClose || titleEl;
      if (first && typeof first.focus === 'function') first.focus();
    }

    function onKeydown(e) {
      if (!backdrop.classList.contains('is-open')) return;

      if (e.key === 'Escape') {
        e.preventDefault();
        close();
        // Clear hash on escape if it was set
        if (window.location.hash) {
          history.replaceState(null, '', window.location.pathname + window.location.search);
        }
        return;
      }

      if (e.key !== 'Tab') return;

      const focusable = getFocusable(backdrop);
      if (focusable.length === 0) return;

      const first = focusable[0];
      const last = focusable[focusable.length - 1];

      if (e.shiftKey && document.activeElement === first) {
        e.preventDefault();
        last.focus();
      } else if (!e.shiftKey && document.activeElement === last) {
        e.preventDefault();
        first.focus();
      }
    }

    backdrop.addEventListener('click', function (e) {
      if (e.target === backdrop) {
        close();
        if (window.location.hash) {
          history.replaceState(null, '', window.location.pathname + window.location.search);
        }
      }
    });

    btnClose.addEventListener('click', function () {
      close();
      if (window.location.hash) {
        history.replaceState(null, '', window.location.pathname + window.location.search);
      }
    });

    return { open, close };
  }

  function renderApp(root, data, config) {
    const months = Array.isArray(data.months) ? data.months : [];
    const imagesBaseUrl = config && config.imagesBaseUrl ? String(config.imagesBaseUrl) : '';

    // Normalize events from new schema to legacy format
    const rawEvents = Array.isArray(data.events) ? data.events : [];
    const events = rawEvents.map(function (e) { return normalizeEvent(e, imagesBaseUrl); });

    // Sort chronologically by date (ascending)
    events.sort(function (a, b) {
      const da = a.date || '';
      const db = b.date || '';
      return da < db ? -1 : da > db ? 1 : 0;
    });

    const monthsById = new Map();
    for (const m of months) {
      if (m && m.id) monthsById.set(String(m.id), m);
    }

    const grouped = groupByMonth(events);
    const orderedMonthIds = months.map((m) => String(m.id));

    const navHtml =
      '<nav class="hp-sticky-nav" aria-label="Meses">' +
      '  <div class="hp-sticky-nav-inner">' +
      orderedMonthIds
        .map((mid, idx) => {
          const m = monthsById.get(mid) || {};
          const label = m.label || mid;
          return (
            '<button type="button" class="hp-pill" data-hp-month="' +
            escapeHtml(mid) +
            '" aria-current="' +
            (idx === 0 ? 'true' : 'false') +
            '">' +
            escapeHtml(label) +
            '</button>'
          );
        })
        .join('') +
      '  </div>' +
      '</nav>';

    const timelineHtml =
      '<div class="hp-timeline" data-hp-timeline>' +
      orderedMonthIds
        .map((mid) => {
          const m = monthsById.get(mid) || {};
          const label = m.label || mid;
          const chapter = m.chapter || '';
          const monthAnchorId = 'm-' + mid;

          const evts = grouped.get(mid) || [];

          const eventsHtml = evts
            .map((e) => {
              const logicalId = e.id ? String(e.id) : '';
              const domId = (config && config.instanceId ? ('hp-' + config.instanceId + '-') : '') + logicalId;

              // --- Datos limpios ---
              const name = e.name || '';
              const meta = formatMeta(e);
              const summary = e.summary || e.context || '';
              const official = e.official_version || '';
              const photoSrc = e.photo && e.photo.src ? resolveAssetUrl(String(e.photo.src), imagesBaseUrl) : '';
              const photoAlt = e.photo && e.photo.alt ? String(e.photo.alt) : (name ? 'Retrato de ' + name : '');

              const hasProfile = e.profile && (e.profile.mode === 'modal' || e.profile.mode === 'link');
              const btn = hasProfile
                ? '<button type="button" class="hp-button" data-hp-open-profile="' +
                escapeHtml(logicalId) + '">Ver perfil completo</button>'
                : '';

              const img = photoSrc
                ? '<img class="hp-photo" loading="lazy" src="' +
                escapeHtml(photoSrc) + '" alt="' + escapeHtml(photoAlt) + '">'
                : '';

              const noPhotoClass = img ? '' : ' hp-event--no-photo';

              // Chip: rol aparece UNA SOLA VEZ (chip negro)
              const chipHtml = e.role
                ? '<div class="hp-event-chip"><span class="hp-chip">' + escapeHtml(e.role) + '</span></div>'
                : '';

              // Caja oficial: usa official_version, NO repite el summary
              const officialHtml = official
                ? '<div class="hp-event-official">' +
                '<span class="hp-event-official-label">Versión oficial</span>' +
                '<p class="hp-event-official-text">' + escapeHtml(official) + '</p>' +
                '</div>'
                : '';

              return (
                '<article class="hp-event hp-reveal' + noPhotoClass +
                '" id="' + escapeHtml(domId) +
                '" data-hp-event="true" data-hp-event-id="' + escapeHtml(logicalId) + '">' +
                '  <span class="hp-marker" aria-hidden="true"></span>' +
                '  <div class="hp-event-text">' +
                chipHtml +
                '    <h3 class="hp-event-name">' + escapeHtml(name) + '</h3>' +
                (meta ? '<p class="hp-meta">' + escapeHtml(meta) + '</p>' : '') +
                (summary ? '<p class="hp-event-summary">' + escapeHtml(summary) + '</p>' : '') +
                officialHtml +
                (btn ? '<div class="hp-event-cta">' + btn + '</div>' : '') +
                '  </div>' +
                (img ? '<div class="hp-event-photo">' + img + '</div>' : '') +
                '</article>'
              );
            })
            .join('');


          return (
            '<section class="hp-month hp-reveal" id="' +
            escapeHtml(monthAnchorId) +
            '" data-hp-month-section="' +
            escapeHtml(mid) +
            '">' +
            '  <aside class="hp-rail">' +
            '    <div class="hp-rail-inner">' +
            '      <h2 class="hp-month-title">' +
            escapeHtml(label) +
            '</h2>' +
            (chapter ? '<p class="hp-month-chapter">' + escapeHtml(chapter) + '</p>' : '') +
            '      <span class="hp-rail-dot" aria-hidden="true"></span>' +
            '    </div>' +
            '  </aside>' +
            '  <div class="hp-events">' +
            eventsHtml +
            '  </div>' +
            '</section>'
          );
        })
        .join('') +
      '</div>';

    root.innerHTML = navHtml + timelineHtml;

    return {
      monthIds: orderedMonthIds,
      monthsById,
      events,
    };
  }

  function setupStickyNav(root) {
    const pills = Array.from(root.querySelectorAll('[data-hp-month]'));
    pills.forEach((btn) => {
      btn.addEventListener('click', function () {
        const mid = btn.getAttribute('data-hp-month');
        if (!mid) return;
        const section = root.querySelector('[data-hp-month-section="' + cssEscape(mid) + '"]');
        if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });

    function setActive(mid) {
      for (const b of pills) {
        const isActive = b.getAttribute('data-hp-month') === mid;
        b.setAttribute('aria-current', isActive ? 'true' : 'false');
      }
    }

    return { setActive };
  }

  function setupActiveStep(root, navApi) {
    const events = Array.from(root.querySelectorAll('[data-hp-event="true"]'));
    const monthSections = Array.from(root.querySelectorAll('[data-hp-month-section]'));
    const revealEls = Array.from(root.querySelectorAll('.hp-reveal'));

    let activeEl = null;

    const io = new IntersectionObserver(
      (entries) => {
        const viewportCenter = window.innerHeight * 0.5;
        let best = null;
        let bestDistance = Infinity;

        for (const e of entries) {
          if (e.isIntersecting && e.target.classList.contains('hp-reveal')) {
            e.target.classList.add('is-inview');
          }

          if (!e.isIntersecting) continue;

          if (e.target.hasAttribute('data-hp-event')) {
            const rect = e.target.getBoundingClientRect();
            const eventCenter = rect.top + (rect.height / 2);
            const distance = Math.abs(eventCenter - viewportCenter);

            if (distance < bestDistance) {
              best = e;
              bestDistance = distance;
            }
          }
        }

        if (best && best.target && best.target.hasAttribute('data-hp-event')) {
          const nextActive = best.target;

          if (nextActive !== activeEl) {
            activeEl = nextActive;

            for (const el of events) {
              el.classList.toggle('is-active', el === activeEl);
            }

            window.requestAnimationFrame(() => updateProfessionalTimeline());
          }
        }

        for (const me of entries) {
          const sec = me.target && me.target.closest
            ? me.target.closest('[data-hp-month-section]')
            : null;

          if (!sec) continue;

          const mid = sec.getAttribute('data-hp-month-section');
          if (mid) navApi.setActive(mid);
        }
      },
      {
        threshold: 0,
        rootMargin: '-45% 0px -45% 0px'
      }
    );

    revealEls.forEach((el) => io.observe(el));
    monthSections.forEach((el) => io.observe(el));

    return io;
  }

  function setLineToMarker(root) {
    if (!root) return;

    const shell = root.closest('.hp-shell');
    const track = shell ? shell.querySelector('[data-hp-track]') : null;
    const inner = track ? track.querySelector('.hp-track-inner') : null;
    if (!shell || !track) return;

    // Marker del evento activo (si existe)
    const activeMarker = root.querySelector('.hp-event.is-active .hp-marker');

    // Fallback: primer marker disponible
    const marker = activeMarker || root.querySelector('.hp-event .hp-marker');
    if (!marker) return;

    const base = (inner || track).getBoundingClientRect();
    const m = marker.getBoundingClientRect();

    // Centro del punto
    const x = (m.left + (m.width / 2)) - base.left;

    shell.style.setProperty('--hp-line-x', x.toFixed(2) + 'px');
  }

  // =========================================================
  // OLD LINE FILL SYSTEM - DISABLED
  // Replaced by professional scanner system
  // =========================================================
  /*
  function setupLineFill(ctx) {
    if (!ctx || !ctx.track || !ctx.fill) return () => { };

    let rafId = 0;

    function update() {
      if (!ctx.fill || !ctx.track || !ctx.shell) return;

      const trackRect = ctx.track.getBoundingClientRect();
      const viewportH = window.innerHeight || document.documentElement.clientHeight;

      // Draw point (50% of viewport - center exact)
      const referenceY = viewportH * 0.5;

      // Start: if exists startEl, use it. Otherwise track top.
      const startRect = ctx.startEl ? ctx.startEl.getBoundingClientRect() : trackRect;
      const startY = startRect.top;

      // End: bottom of the track
      const endY = trackRect.bottom;

      const total = Math.max(1, endY - startY);
      const progressPx = referenceY - startY;

      const progress = Math.max(0, Math.min(1, progressPx / total));

      ctx.fill.style.transform = 'scaleY(' + progress.toFixed(4) + ')';
    }

    function onScroll() {
      if (rafId) return;
      rafId = window.requestAnimationFrame(function () {
        rafId = 0;
        update();
      });
    }

    update();
    window.addEventListener('scroll', onScroll, { passive: true });
    window.addEventListener('resize', onScroll);

    return () => {
      window.removeEventListener('scroll', onScroll);
      window.removeEventListener('resize', onScroll);
    };
  }
  */

  // =========================================================
  // TIMELINE SCANNER SYSTEM - Professional implementation
  // =========================================================

  function updateTrackScannerVisibility(trackEl) {
    if (!trackEl) return;

    const timelineSection = trackEl.querySelector('.hp-timeline');
    if (!timelineSection) {
      trackEl.classList.remove('is-in-view');
      return;
    }

    const rect = timelineSection.getBoundingClientRect();
    const triggerTop = window.innerHeight * 0.20;
    const triggerBottom = window.innerHeight * 0.85;

    const isVisible = rect.top < triggerBottom && rect.bottom > triggerTop;
    trackEl.classList.toggle('is-in-view', isVisible);
  }

  function syncTrackFillToLine(trackEl) {
    if (!trackEl) return;

    const line = trackEl.querySelector('.hp-track-line');
    const fill = trackEl.querySelector('.hp-track-fill');

    if (!line || !fill) return;

    const lineRect = line.getBoundingClientRect();
    const lineCenterX = lineRect.left + (lineRect.width / 2);

    fill.style.left = `${lineCenterX}px`;
  }

  function updateTimelineMarkers() {
    const markers = document.querySelectorAll('.hp-marker');
    if (!markers.length) return;

    const triggerY = (window.innerHeight / 2) - 42;
    const tolerance = 18;

    markers.forEach((marker) => {
      const rect = marker.getBoundingClientRect();
      const center = rect.top + rect.height / 2;
      const isActive = Math.abs(center - triggerY) <= tolerance;

      marker.classList.toggle('is-active', isActive);
    });
  }

  function updateProfessionalTimeline() {
    const trackEl = document.querySelector('[data-hp-track]');
    if (!trackEl) return;

    updateTrackScannerVisibility(trackEl);
    syncTrackFillToLine(trackEl);
    updateTimelineMarkers();
  }

  function openEventModal(id, byId, modal, root, config) {
    const e = byId.get(String(id));
    if (!e || !e.profile) return;

    if (e.profile.mode === 'link' && e.profile.url) {
      window.location.href = String(e.profile.url);
      return;
    }

    const bodyText = e.profile.body ? String(e.profile.body) : '';
    const sources = Array.isArray(e.profile.sources) ? e.profile.sources : [];

    const sourcesHtml = sources.length
      ? '<div class="hp-section"><h3>Fuentes</h3><ul class="hp-sources">' +
      sources
        .map((s) => {
          const label = s && s.label ? String(s.label) : 'Fuente';
          const url = s && s.url ? String(s.url) : '';
          if (!url) return '<li>' + escapeHtml(label) + '</li>';
          return '<li><a href="' + escapeHtml(url) + '" target="_blank" rel="noopener noreferrer">' + escapeHtml(label) + '</a></li>';
        })
        .join('') +
      '</ul></div>'
      : '';

    // Render body as separate paragraphs (supports body[] array via normalize)
    let bodyHtml = '';
    if (bodyText) {
      const paragraphs = bodyText.split('\n\n');
      bodyHtml = '<div class="hp-section">' +
        paragraphs.map(function (p) { return '<p>' + escapeHtml(p.trim()) + '</p>'; }).join('') +
        '</div>';
    }

    // Role subtitle for modal header
    const roleHtml = e.role ? '<p class="hp-modal-role">' + escapeHtml(e.role) + '</p>' : '';

    // Summary (editorial brief) — separate from official_version
    const summaryText = e.summary || e.context || '';
    const summaryHtml = summaryText
      ? '<div class="hp-section"><h3>Resumen</h3><p>' + escapeHtml(summaryText) + '</p></div>'
      : '';

    // Official version — voice of the regime, visually differentiated
    const officialText = e.official_version || '';
    const officialHtml = officialText
      ? '<div class="hp-section hp-section--official"><h3>Versión oficial</h3><p>' + escapeHtml(officialText) + '</p></div>'
      : '';

    const html =
      roleHtml +
      summaryHtml +
      officialHtml +
      bodyHtml +
      sourcesHtml;

    const triggerEl = root.querySelector('[data-hp-event-id="' + cssEscape(String(id)) + '"]');

    modal.open(
      {
        title: e.name || 'Perfil',
        meta: formatMeta(e),
        html: html || '<p>Sin información.</p>',
      },
      triggerEl
    );

    // Update hash with unique DOM id
    const domId = (config && config.instanceId ? ('hp-' + config.instanceId + '-') : '') + String(id);
    history.replaceState(null, '', '#' + domId);

    window.dispatchEvent(
      new CustomEvent('profile_open', {
        detail: {
          eventId: String(id),
          monthId: String(e.month_id || ''),
        },
      })
    );
  }

  function setupProfiles(root, data, config) {
    const shell = root && root.closest ? root.closest('.hp-shell') : null;
    const modal = createModal(shell, config && config.instanceId ? config.instanceId : '');
    const byId = new Map();
    for (const e of data.events) {
      if (e && e.id) byId.set(String(e.id), e);
    }

    root.addEventListener('click', function (ev) {
      const t = ev.target;
      if (!(t instanceof Element)) return;
      const btn = t.closest('[data-hp-open-profile]');
      if (!btn) return;

      const id = btn.getAttribute('data-hp-open-profile') || '';
      openEventModal(id, byId, modal, root, config);
    });

    root.addEventListener('click', function (ev) {
      const t = ev.target;
      if (!(t instanceof Element)) return;
      const a = t.closest('.hp-sources a');
      if (!a) return;
      window.dispatchEvent(
        new CustomEvent('source_click', {
          detail: {
            href: String(a.getAttribute('href') || ''),
          },
        })
      );
    });

    return {
      openById: (id) => openEventModal(id, byId, modal, root, config)
    };
  }

  function hideFallback(root) {
    const wrap = root.parentElement ? root.parentElement.querySelector('[data-hp-fallback]') : null;
    if (!wrap) return;
    wrap.hidden = true;
  }

  async function boot() {
    const roots = Array.from(document.querySelectorAll('.hp-root[data-config]'));
    for (const root of roots) {
      const rawConfig = root.getAttribute('data-config');
      if (!rawConfig) continue;
      const config = safeJsonParse(rawConfig);
      if (!config || !config.dataUrl) continue;

      try {
        destroyInstance(root); // Cleanup if re-running

        let dataPromise = dataCache.get(config.dataUrl);
        if (!dataPromise) {
          dataPromise = fetch(config.dataUrl, { credentials: 'same-origin' })
            .then(res => {
              if (!res.ok) throw new Error('HTTP ' + res.status);
              return res.json();
            });
          dataCache.set(config.dataUrl, dataPromise);
        }

        const data = await dataPromise;

        const renderState = renderApp(root, data, config);
        hideFallback(root);

        const ctx = getInstanceCtx(root);
        if (!ctx) continue;

        const navApi = setupStickyNav(root);
        const io = setupActiveStep(root, navApi);
        registerDestroy(root, () => io && typeof io.disconnect === 'function' && io.disconnect());

        // OLD LINE FILL SYSTEM - DISABLED
        // const stopLine = setupLineFill(ctx);
        // registerDestroy(root, stopLine);

        const profilesApi = setupProfiles(root, renderState, config);

        // Dynamic line calibration
        setLineToMarker(root);
        const onResizeLine = () => window.requestAnimationFrame(() => setLineToMarker(root));
        window.addEventListener('resize', onResizeLine);
        registerDestroy(root, () => window.removeEventListener('resize', onResizeLine));

        // Deep-linking check
        const initialHash = window.location.hash.replace(/^#/, '');
        if (initialHash) {
          const prefix = config.instanceId ? ('hp-' + config.instanceId + '-') : '';
          if (!prefix || initialHash.startsWith(prefix)) {
            const targetEvent = root.querySelector('#' + cssEscape(initialHash));
            if (targetEvent) {
              targetEvent.scrollIntoView({ behavior: 'smooth', block: 'center' });
              const logicalId = targetEvent.getAttribute('data-hp-event-id') || '';
              if (logicalId) profilesApi.openById(logicalId);
            }
          }
        }

        // Professional timeline scanner system
        updateProfessionalTimeline();
        window.addEventListener('scroll', updateProfessionalTimeline, { passive: true });
        window.addEventListener('resize', updateProfessionalTimeline);

        if (renderState.monthIds && renderState.monthIds[0]) {
          window.dispatchEvent(
            new CustomEvent('timeline_month_view', {
              detail: {
                monthId: String(renderState.monthIds[0]),
              },
            })
          );
        }
      } catch (e) {
        root.innerHTML =
          '<div class="hp-loading" role="alert">No se pudo cargar el contenido. ' +
          escapeHtml(e && e.message ? e.message : 'Error') +
          '</div>';
      }
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
      updateProfessionalTimeline();
      boot();
    });
  } else {
    updateProfessionalTimeline();
    boot();
  }
})();

