# Image Upload & Integration Guide

## How JavaScript Communicates with React Editor

### The Problem
- React MD Editor is a **React component** mounted in `#article-editor-root` div
- Its state (editor content) is **internal to React** and not accessible from vanilla JavaScript
- Vanilla JS image uploader needs to **inject markdown** into the React editor
- Static HTML output shown in browser is just a **preview**, not the actual editor

### The Solution: Global API Exposure

#### 1. **React Component Exposes Global Methods** (`resources/react/ArticleEditor.jsx`)

```javascript
useEffect(() => {
  // Expose insertMarkdown method globally
  window.insertMarkdownToEditor = (markdown) => {
    setValue(prev => {
      const newValue = prev + '\n' + markdown + '\n';
      return newValue;
    });
  };
  
  // Also expose getter for current value
  window.getEditorValue = () => value;
  
  return () => {
    delete window.insertMarkdownToEditor;
    delete window.getEditorValue;
  };
}, [value]);
```

**What happens:**
- React component creates `window.insertMarkdownToEditor()` function
- This function updates React state directly using `setValue()`
- The hidden input `<input type="hidden" name="content" value={value} />` syncs React state to form

#### 2. **Image Uploader Detects React API** (Blade JavaScript)

```javascript
// In setupPasteListener() or upload flow:
if (window.insertMarkdownToEditor && typeof window.insertMarkdownToEditor === 'function') {
    console.log('🚀 Using React editor API...');
    window.insertMarkdownToEditor(markdown);
} else {
    // Fallback to plain textarea
    // ...
}
```

#### 3. **Initialization Waits for React to Mount** (DOMContentLoaded)

```javascript
document.addEventListener('DOMContentLoaded', () => {
    let retries = 0;
    const maxRetries = 20;
    
    const tryInitialize = () => {
        retries++;
        
        if (window.insertMarkdownToEditor && typeof window.insertMarkdownToEditor === 'function') {
            console.log('✅ React editor API found!');
            new ArticleImageUploader(...);
        } else if (retries < maxRetries) {
            console.log('⏳ Waiting for React...');
            setTimeout(tryInitialize, 200);
        }
    };
    
    tryInitialize();
});
```

**Why this matters:**
- React component takes ~500-1000ms to mount and expose API
- Without waiting, `window.insertMarkdownToEditor` would be undefined
- Retry logic ensures initialization happens once API is ready

---

## Workflow: Image Upload Process

### Create New Article (Temp Upload)

1. **User pastes image (Ctrl+V) in editor or clicks "Add Image" button**
   - `setupPasteListener()` detects clipboard image
   - OR file input dialog triggered

2. **Image starts uploading**
   ```
   📤 Uploading image: screenshot.png image/png Size: 245.32 KB
   ```

3. **FormData includes article_id**
   ```javascript
   formData.append('article_id', 'new'); // Create mode
   ```

4. **Backend routes to temp folder** (`ArticleImageController.php`)
   - Path: `/storage/articles/temp-upload/randomString.png`
   - Returns markdown with temp URL

5. **Markdown inserted to React editor**
   ```javascript
   window.insertMarkdownToEditor('![image](http://localhost:8000/storage/articles/temp-upload/...)')
   ```

6. **React state updates** ✨
   - `setValue()` adds markdown to current content
   - Hidden input syncs to form: `<input name="content" value={newValue}>`
   - Preview shows image immediately

7. **Admin publishes article**
   - Form submitted to `ArticleController@store()`
   - Images in markdown detected and moved from temp → article folder
   - URLs in markdown updated automatically
   - Temp files deleted

### Edit Existing Article (Direct Upload)

1. **User uploads image in existing article**
   - `setupPasteListener()` or button click

2. **FormData includes article_id**
   ```javascript
   formData.append('article_id', '42'); // Edit mode
   ```

3. **Backend routes to article folder** (`ArticleImageController.php`)
   - Path: `/storage/articles/{slug}_{date}/randomString.png`
   - Returns markdown with direct URL (no temp needed)

4. **Markdown inserted immediately** ✨
   ```javascript
   window.insertMarkdownToEditor('![image](http://localhost:8000/storage/articles/...)')
   ```

5. **Admin updates article**
   - Images already in correct folder
   - No migration needed
   - Just URL updates if any temp images exist

---

## Console Logging Guide

**Watch these console messages to debug:**

```
🔄 Attempting to initialize image uploader (attempt 1/20)...
⏳ Waiting for React component to mount...
✅ React editor API found! Initializing image uploader...

📋 Paste event detected. Editor focused? true
🖼️ Image detected in clipboard: image/png
📤 Uploading image: screenshot.png image/png Size: 245.32 KB

✅ Upload successful!
📎 Image URL: http://localhost:8000/storage/articles/temp-upload/...
📝 Markdown: ![image](http://localhost:8000/storage/articles/temp-upload/...)

🚀 Using React editor API...
✅ Markdown inserted via React editor API
📊 Current editor value: 2847 chars
```

---

## File Locations

- **React Component**: `resources/react/ArticleEditor.jsx`
- **Create Article Blade**: `resources/views/admin/articles/create.blade.php`
- **Edit Article Blade**: `resources/views/admin/articles/edit.blade.php`
- **Upload Controller**: `app/Http/Controllers/Admin/ArticleImageController.php`
- **Article Controller**: `app/Http/Controllers/Admin/ArticleController.php`

---

## Key Technical Points

### Why This Works

1. **React exposes methods** → External JS can call them
2. **External JS waits for React** → Reliable initialization
3. **React manages state** → Content always in sync
4. **Hidden input syncs** → Form gets updated content
5. **Fallback to textarea** → Works without React (graceful degradation)

### Security

- CSRF token included in fetch headers
- File validation on backend (image type, size)
- Storage disk permissions restrict access
- Input validation in controller

### Performance

- Images stored in `/storage/public` (fast HTTP access)
- Lazy migration on publish (not on every upload)
- Async upload doesn't block UI
- Auto-cleanup of temp files after 24 hours (optional, can add cron job)

