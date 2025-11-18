import React, { useState, useRef, useEffect } from 'react';
import { createRoot } from 'react-dom/client';
import MDEditor from '@uiw/react-md-editor';
import rehypeSanitize from 'rehype-sanitize';
import '@uiw/react-md-editor/markdown-editor.css';
import '@uiw/react-markdown-preview/markdown.css';

console.log('🚀 ArticleEditor.jsx loaded successfully!');

export default function ArticleEditor({ initialContent = '', contentFieldName = 'content' }) {
  console.log('📝 ArticleEditor component initialized with:', { initialContent, contentFieldName });
  const [value, setValue] = useState(initialContent);
  const [shouldScroll, setShouldScroll] = useState(false);
  const editorRef = useRef();
  const isScrollingPreview = useRef(false);

  // Auto-scroll smooth when user starts typing (only from editor, not preview)
  useEffect(() => {
    if (shouldScroll && editorRef.current && !isScrollingPreview.current) {
      editorRef.current.scrollIntoView({ behavior: "smooth", block: "start" });
      setShouldScroll(false);
    }
  }, [shouldScroll]);

  // Detect scroll on preview panel to prevent auto-scroll
  useEffect(() => {
    const handleScroll = (e) => {
      const target = e.target;
      // Check if scroll is from preview panel
      if (target.classList.contains('w-md-editor-preview') || 
          target.closest('.w-md-editor-preview')) {
        isScrollingPreview.current = true;
        setTimeout(() => {
          isScrollingPreview.current = false;
        }, 100);
      }
    };

    document.addEventListener('scroll', handleScroll, true);
    return () => document.removeEventListener('scroll', handleScroll, true);
  }, []);

  const handleChange = (newValue) => {
    setValue(newValue);
    // Trigger scroll on first edit (only if not scrolling preview)
    if (!shouldScroll && newValue !== initialContent && !isScrollingPreview.current) {
      setShouldScroll(true);
    }
  };

  return (
    <div data-color-mode="light" ref={editorRef}>
      <style>{`
        /* Hide scrollbar on editor panel, keep on preview */
        .w-md-editor-text-pre,
        .w-md-editor-text-input {
          overflow: hidden !important;
        }
        
        /* Keep scrollbar only on preview panel */
        .w-md-editor-preview {
          overflow-y: auto !important;
        }
        
        /* Sync scroll between editor and preview */
        .w-md-editor-content {
          overflow: hidden;
        }
      `}</style>
      <MDEditor
        value={value}
        onChange={handleChange}
        height={600}
        preview="live"
        highlightEnable={true}
        enableScroll={true}
        visibleDragbar={true}
        textareaProps={{
          placeholder: 'Please enter Markdown text for your article...',
          autoFocus: false,
        }}
        previewOptions={{
          rehypePlugins: [[rehypeSanitize]], // Security: Prevent XSS attacks
        }}
      />
      <input type="hidden" name={contentFieldName} value={value} />
    </div>
  );
}

// Auto-mount when DOM is ready
if (typeof document !== 'undefined') {
  console.log('🔍 Waiting for DOM to load...');
  
  document.addEventListener('DOMContentLoaded', () => {
    console.log('✅ DOM loaded, searching for #article-editor-root...');
    const editorRoot = document.getElementById('article-editor-root');
    
    if (editorRoot) {
      console.log('✅ Found #article-editor-root element!');
      const initialContent = editorRoot.dataset.initialContent || '';
      const fieldName = editorRoot.dataset.fieldName || 'content';
      
      console.log('🎯 Mounting React component with data:', { initialContent, fieldName });
      
      const root = createRoot(editorRoot);
      root.render(
        <React.StrictMode>
          <ArticleEditor initialContent={initialContent} contentFieldName={fieldName} />
        </React.StrictMode>
      );
      
      console.log('✨ React MD Editor mounted successfully!');
    } else {
      console.error('❌ Element #article-editor-root NOT FOUND in DOM!');
    }
  });
}
