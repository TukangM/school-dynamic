# Image Upload Testing Checklist ✅

## Pre-Testing Setup

- [ ] Clear browser cache (F12 → Application → Clear Storage)
- [ ] Open browser console (F12 → Console tab)
- [ ] Navigate to article create or edit page
- [ ] Wait for console messages showing initialization

---

## Test 1: React Editor API Detection

**Expected Console Output:**
```
🔄 Attempting to initialize image uploader (attempt 1/20)...
⏳ Waiting for React component to mount...
✅ React editor API found! Initializing image uploader...
```

**What to check:**
- [ ] Initial message shows "Attempting" with attempt counter
- [ ] After a few retries, see "React editor API found!"
- [ ] No errors in console
- [ ] "Add Image (or Ctrl+V)" button appears below editor

**If it fails:**
- Check if React script is loading: Look for `ArticleEditor.jsx loaded successfully!`
- Check network tab for JavaScript errors
- Clear browser cache and refresh

---

## Test 2: Paste Image (Ctrl+V)

**Setup:**
- Have an image in clipboard (Ctrl+C from file)
- Click inside the editor (left/code panel)

**Steps:**
1. Press Ctrl+V to paste image
2. Watch console for messages
3. Image markdown should appear in editor

**Expected Console Output:**
```
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

**Visual Check:**
- [ ] Button shows "Uploading..." state with spinner
- [ ] Image markdown appears in editor code panel: `![image](url)`
- [ ] Preview panel shows the uploaded image
- [ ] Button returns to "Add Image" state after upload

**If paste doesn't trigger upload:**
- Make sure you clicked **inside the editor** before pasting
- Try pasting in the code panel (left side), not preview
- Check if image was actually in clipboard

---

## Test 3: Button Upload

**Setup:**
- Click "Add Image (or Ctrl+V)" button

**Steps:**
1. Click button
2. File dialog appears
3. Select an image file
4. Watch for upload

**Expected Console Output:**
```
📤 Uploading image: myimage.jpg image/jpeg Size: 512.45 KB
✅ Upload successful!
📎 Image URL: ...
📝 Markdown: ![image](...)
✅ Markdown inserted via React editor API
```

**Visual Check:**
- [ ] File dialog opens (browser native dialog)
- [ ] After selection, button shows "Uploading..."
- [ ] Markdown appears in editor
- [ ] Preview shows image
- [ ] Button returns to normal state

---

## Test 4: Create Article with Images (Full Flow)

**Steps:**
1. Go to Create Article page
2. Fill in title, excerpt
3. Add multiple images (paste or button)
4. Check preview shows all images
5. Click "Publish Article" button
6. Wait for redirect

**Expected Results:**
- [ ] Images uploaded to `/storage/articles/temp-upload/`
- [ ] After publish, images moved to `/storage/articles/{slug}_{date}/`
- [ ] Article saved successfully
- [ ] All images display in published article

**Check Backend:**
```bash
# Images should be in article folder, not temp
ls storage/app/public/articles/*/
# Should see: cover.jpg, image1.png, image2.jpg, index.md
```

---

## Test 5: Edit Article (Add New Images)

**Steps:**
1. Go to Edit Article page for existing article
2. Scroll to editor
3. Paste or upload new image
4. Click "Update & Publish"
5. Verify image appears

**Expected:**
- [ ] New image uploaded directly to article folder (not temp!)
- [ ] Markdown inserted correctly
- [ ] Article updates successfully
- [ ] No temp folder created

---

## Test 6: Error Handling

**Test Upload Error (no connection):**
- Open DevTools Network tab
- Disable network (Offline mode)
- Try to upload image
- Expected: Error alert shows

**Test Large File:**
- Try uploading file > 15MB
- Expected: Validation error from backend

**Test Non-Image File:**
- Try uploading .txt or .pdf file
- Expected: Validation error

---

## Browser Console Red Flags ❌

If you see these, something is wrong:

```
❌ React editor API not found after waiting
// Solution: React component didn't mount, check Vite build

Undefined function 'insertMarkdownToEditor'
// Solution: React API not exposed, check ArticleEditor.jsx

Upload failed with status 403
// Solution: CSRF token issue or permissions

Cannot read property 'files' of undefined
// Solution: File input element not found, check DOM

ArticleEditor not rendering
// Solution: Check if React script loaded, check browser console for JS errors
```

---

## Performance Metrics

**What to expect:**
- React component mount time: 500-1000ms
- Image upload time (5MB): 1-3 seconds (depends on network)
- Markdown insertion: Instant (< 50ms)
- Editor update on paste: < 500ms total

---

## Success Indicators ✨

All tests pass when:

1. ✅ "Add Image" button appears
2. ✅ Ctrl+V in editor detects images
3. ✅ Button upload works
4. ✅ Markdown appears in editor preview
5. ✅ Images persist after publish
6. ✅ No console errors
7. ✅ Images in correct storage folder
8. ✅ Multiple images work
9. ✅ Both create and edit flows work

---

## Debugging Commands (Browser Console)

```javascript
// Check if React API exists
typeof window.insertMarkdownToEditor // Should be 'function'

// Check current editor value
window.getEditorValue() // Should return markdown string

// Manually insert markdown (for testing)
window.insertMarkdownToEditor('![test](http://example.com/image.jpg)')

// Check article form value
document.querySelector('input[name="content"]').value
```

---

## Common Issues & Solutions

| Issue | Cause | Solution |
|-------|-------|----------|
| Button doesn't appear | React didn't mount | Wait longer, check JS console |
| Ctrl+V doesn't work | Editor not focused | Click inside editor first |
| Image not appearing | React API not called | Check console for errors |
| Upload fails 403 | CSRF token missing | Check meta tag in HTML |
| Upload fails 422 | Validation error | Check file type/size |
| Images in temp folder | Article not published | Need to publish/update article |

