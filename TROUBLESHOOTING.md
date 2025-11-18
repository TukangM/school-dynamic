# Troubleshooting React MD Editor

## Masalah: Editor MD tidak muncul di halaman create article

### Langkah 1: Pastikan npm run dev berjalan
```powershell
npm run dev
```

**Output yang benar:**
```
VITE v5.x.x ready in xxx ms

➜  Local:   http://localhost:5173/
➜  Network: use --host to expose

LARAVEL v11.x.x plugin v1.x.x

➜  APP_URL: http://localhost
```

Jika ada error, catat error messagenya.

### Langkah 2: Cek di browser
1. Buka http://localhost/admin/articles/create
2. Tekan F12 untuk buka Developer Tools
3. Lihat tab **Console** - apakah ada error merah?
4. Lihat tab **Network** - cari request ke `ArticleEditor.jsx`

### Langkah 3: Cek HTML Source
1. Di halaman create article, klik kanan → View Page Source
2. Cari `article-editor-root`
3. Lihat apakah ada script tag yang load dari `http://localhost:5173/@vite/client`

**Yang benar:**
```html
<script type="module" src="http://localhost:5173/@vite/client"></script>
<script type="module" src="http://localhost:5173/resources/react/ArticleEditor.jsx"></script>
```

**Yang salah (berarti vite dev tidak jalan):**
```html
<script type="module" src="/build/assets/ArticleEditor-xxx.js"></script>
```

### Langkah 4: Hard Refresh Browser
- Windows: Ctrl + Shift + R
- Mac: Cmd + Shift + R

### Langkah 5: Cek Element Inspector
1. Di halaman create article, klik kanan pada area kosong → Inspect
2. Cari element dengan id `article-editor-root`
3. Lihat apakah ada konten React di dalamnya

**Yang benar:**
```html
<div id="article-editor-root" data-initial-content="..." data-field-name="content">
  <div data-color-mode="light">
    <div class="w-md-editor w-md-editor-show-live">
      <!-- React MD Editor content -->
    </div>
  </div>
</div>
```

**Yang salah:**
```html
<div id="article-editor-root" data-initial-content="..." data-field-name="content">
  <!-- Kosong, tidak ada apa-apa -->
</div>
```

### Langkah 6: Cek React Component
Buka: `resources/react/ArticleEditor.jsx`

Pastikan ada kode auto-mounting di bagian bawah:
```javascript
if (typeof document !== 'undefined') {
  document.addEventListener('DOMContentLoaded', () => {
    const editorRoot = document.getElementById('article-editor-root');
    
    if (editorRoot) {
      const initialContent = editorRoot.dataset.initialContent || '';
      const fieldName = editorRoot.dataset.fieldName || 'content';
      
      const root = createRoot(editorRoot);
      root.render(
        <React.StrictMode>
          <ArticleEditor initialContent={initialContent} contentFieldName={fieldName} />
        </React.StrictMode>
      );
    }
  });
}
```

### Common Errors & Solutions

#### Error: "Failed to fetch dynamically imported module"
**Solusi:** Stop npm run dev, lalu jalankan lagi

#### Error: "Cannot find module 'rehype-sanitize'"
**Solusi:** 
```powershell
npm install rehype-sanitize
```

#### Error: "Uncaught ReferenceError: React is not defined"
**Solusi:** Cek import statement di ArticleEditor.jsx

#### Editor muncul tapi kosong/crash
**Solusi:** Buka Console, lihat error message lengkapnya

### Langkah 7: Test Minimal
Coba test apakah React berfungsi dengan component sederhana:

Edit `resources/react/ArticleEditor.jsx`, tambahkan di atas:
```javascript
console.log('ArticleEditor.jsx loaded!');
```

Refresh browser, cek Console. Jika muncul "ArticleEditor.jsx loaded!", berarti file ter-load.

### Langkah 8: Restart Everything
Jika masih tidak jalan:
1. Stop npm run dev (Ctrl+C)
2. Stop php artisan serve (Ctrl+C)
3. Clear browser cache
4. Jalankan lagi:
```powershell
npm run dev
php artisan serve
```
5. Hard refresh browser (Ctrl+Shift+R)

---

## Screenshot Console Error
Jika masih error, screenshot:
1. Browser Console (F12 → Console tab)
2. Network tab (filter: ArticleEditor)
3. Terminal output dari `npm run dev`

Lalu share screenshot untuk analisis lebih lanjut.
