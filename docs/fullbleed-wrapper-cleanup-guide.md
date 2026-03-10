# 🎯 Fullbleed Wrapper Cleanup - Detailed Implementation Guide

## ✅ **Paso 0: Backup Checkpoint - COMPLETED**

```bash
git commit --allow-empty -m "Checkpoint before fullbleed wrapper cleanup"
# Commit: ca234b0 - Checkpoint before fullbleed wrapper cleanup
```

---

## 🔄 **Paso 1: Remove Gutenberg Wrapper from Template**

### **Current Structure Problem:**
```html
<div class="entry-content alignfull wp-block-post-content has-global-padding is-layout-constrained wp-block-post-content-is-layout-constrained">
    <div class="wp-block-group has-global-padding is-layout-constrained wp-block-group-is-layout-constrained">  <!-- ❌ REMOVE THIS -->
        <div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">
```

### **Target Structure:**
```html
<div class="entry-content alignfull wp-block-post-content has-global-padding is-layout-constrained wp-block-post-content-is-layout-constrained">
    <div class="hp-wp-wrap hp-wp-wrap--fullbleed alignfull">
```

### **Action Required:**
**File:** `templates/app-shell.php`

**Current template is already clean** - no extra wrapper to remove. The template structure is correct.

---

## 🔄 **Paso 2: Replace page.css with Fullbleed Integration**

### **Current page.css Status:** Already contains professional integration layer

### **Complete page.css Implementation:**
```css
/* ==========================================================================
   PAGE / WORDPRESS INTEGRATION LAYER
   ========================================================================== */

/* ----------------------------------
   1. Admin bar offset
---------------------------------- */

html {
    scroll-padding-top: 32px;
}

@media (max-width: 782px) {
    html {
        scroll-padding-top: 46px;
    }
}

/* ----------------------------------
   2. Body safety
---------------------------------- */

body.hp-has-fullbleed {
    overflow-x: clip;
}

/* ----------------------------------
   3. Root wrapper
---------------------------------- */

.hp-wp-wrap {
    position: relative;
    margin: 0;
    padding: 0;
}

.hp-wp-wrap--fullbleed {
    position: relative;
    width: 100vw;
    max-width: 100vw !important;
    margin-left: calc(50% - 50vw) !important;
    margin-right: calc(50% - 50vw) !important;
    padding: 0;
}

/* ----------------------------------
   4. Entry point protection
---------------------------------- */

.entry-content > .hp-wp-wrap,
.wp-block-post-content > .hp-wp-wrap,
.is-layout-constrained > .hp-wp-wrap {
    max-width: none !important;
}

.entry-content > .hp-wp-wrap.alignfull,
.wp-block-post-content > .hp-wp-wrap.alignfull,
.is-layout-constrained > .hp-wp-wrap.alignfull {
    max-width: none !important;
    width: 100vw !important;
}

/* ----------------------------------
   5. Neutralize page template spacing
---------------------------------- */

body.page-id-102 main.wp-block-group.has-global-padding.is-layout-constrained {
    margin-top: 0 !important;
}

body.page-id-102 .wp-block-group.alignfull.has-global-padding.is-layout-constrained {
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}

/* ----------------------------------
   6. Neutralize content padding around the special
---------------------------------- */

body.page-id-102 .entry-content.has-global-padding {
    padding-left: 0 !important;
    padding-right: 0 !important;
}

body.page-id-102 .entry-content > .alignfull {
    margin-left: 0 !important;
    margin-right: 0 !important;
}

/* ----------------------------------
   7. Box sizing safety
---------------------------------- */

.hp-wp-wrap,
.hp-wp-wrap *,
.hp-wp-wrap *::before,
.hp-wp-wrap *::after {
    box-sizing: border-box;
}
```

### **What to Remove from page.css:**
- Any weak `width: 100%` rules
- Overly aggressive sandbox neutralization
- Duplicate WordPress selectors
- Excessive block gap resets

---

## 🔄 **Paso 3: Fix app.css Container and Hero Layout**

### **Step 3.1: Fix .hp-container**
```css
.hp-container {
    width: 100%;
    max-width: 1200px;
    margin-left: auto;
    margin-right: auto;
    padding-left: 24px;
    padding-right: 24px;
}

@media (max-width: 767px) {
    .hp-container {
        padding-left: 16px;
        padding-right: 16px;
    }
}
```

### **Step 3.2: Fix .hp-hero**
```css
.hp-hero {
    position: relative;
    width: 100%;
    min-height: 72vh;
    display: flex;
    align-items: center;
}
```

### **Step 3.3: Fix .hp-hero-container**
```css
.hp-hero-container {
    position: relative;
    width: 100%;
}
```

### **Step 3.4: Fix .hp-hero-copy**
```css
.hp-hero-copy {
    width: 100%;
    max-width: 880px;
    margin-left: auto;
    margin-right: auto;
    text-align: center;
}
```

### **Step 3.5: Fix .hp-title**
```css
.hp-title {
    margin-left: auto;
    margin-right: auto;
    max-width: 12ch;  /* Start with 12ch, increase to 14ch if needed */
}
```

### **Step 3.6: Remove WordPress Selectors from app.css**
**Remove any selectors starting with:**
- `.wp-site-blocks`
- `.has-global-padding`
- `.is-layout-constrained`
- `.entry-content`
- `.wp-block-group`
- `.alignfull`

---

## 🔍 **Paso 4: DevTools Verification**

### **A. Check .hp-wp-wrap**
**Should show:**
- ✅ `width: 100vw`
- ✅ `max-width: 100vw`
- ✅ `margin-left: calc(50% - 50vw)`
- ✅ `margin-right: calc(50% - 50vw)`

**Should NOT show:**
- ❌ `max-width: 645px`
- ❌ `margin-left: auto`
- ❌ `margin-right: auto`

### **B. Check .hp-container**
**Should show:**
- ✅ `max-width: 1200px`
- ✅ `margin-left: auto`
- ✅ `margin-right: auto`
- ✅ `padding-left: 24px`
- ✅ `padding-right: 24px`

### **C. Check main.wp-block-group**
**Should show:**
- ✅ `margin-top: 0 !important` (overridden)

### **D. Check .wp-block-group.alignfull**
**Should show:**
- ✅ `padding-top: 0 !important`
- ✅ `padding-bottom: 0 !important`

---

## 🛠️ **Paso 5: Troubleshooting Guide**

### **Case 1: Still Appears Narrow**
**Causes:**
- Gutenberg wrapper not removed
- page.css missing 100vw + calc() technique

**Solutions:**
- Verify template structure
- Check page.css implementation

### **Case 2: Full Width but Text Disorganized**
**Causes:**
- `.hp-container` or `.hp-hero-copy` issues

**Solutions:**
- Verify `max-width: 1200px; margin: 0 auto`
- Verify `max-width: 880px; margin: 0 auto; text-align: center`

### **Case 3: Horizontal Scroll Appears**
**Causes:**
- Internal elements overflowing
- Absolute positioned elements

**Solutions:**
```css
.hp-shell {
    position: relative;
    overflow-x: clip;
}

.hp-track {
    overflow-x: clip;
}
```

---

## 📋 **Paso 6: Execution Order**

### **Exact Order:**
1. ✅ **Paso 0:** Create backup (COMPLETED)
2. 🔄 **Paso 1:** Edit template (ALREADY CLEAN)
3. 🔄 **Paso 2:** Fix page.css (NEEDS VERIFICATION)
4. 🔄 **Paso 3:** Fix app.css (NEEDS IMPLEMENTATION)
5. ⏳ **Paso 4:** Hard refresh + DevTools check
6. ⏳ **Paso 5:** Troubleshoot if needed
7. ⏳ **Paso 6:** Final validation

---

## 🎯 **Expected Final Result**

### **Visual Results:**
- ✅ Special occupies full viewport width
- ✅ Hero no longer compressed in narrow column
- ✅ Editorial content centered and clean
- ✅ Artificial spacing above/below removed
- ✅ WordPress keeps header/footer but doesn't control special layout

### **Technical Results:**
- ✅ Fullbleed technique working
- ✅ Container system centering content
- ✅ WordPress integration layer functional
- ✅ No horizontal scroll
- ✅ Responsive behavior intact

---

## 📊 **Summary of Changes**

### **Template:**
- ✅ Already clean (no wrapper to remove)

### **page.css:**
- ✅ Professional integration layer
- ✅ True fullbleed technique
- ✅ Template spacing neutralization

### **app.css:**
- 🔄 Container system needs verification
- 🔄 Hero layout needs adjustment
- 🔄 WordPress selectors need removal

---

## 🚀 **Ready for Implementation**

**Current Status:** **Phase 1 Complete, Phases 2-3 Ready**
**Risk Level:** LOW (backup created)
**Impact:** HIGH (fixes 645px constraint)

**Next Action:** Implement page.css and app.css changes, then validate with DevTools.
