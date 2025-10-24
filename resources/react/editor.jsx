import React from "react";
import ReactDOM from "react-dom/client";
import MDEditor from "@uiw/react-md-editor";
import "@uiw/react-md-editor/dist/md-editor.css";
import "@uiw/react-markdown-preview/dist/markdown.css";

function App() {
  const [value, setValue] = React.useState("**Hello world!!!**");
  return (
    <div className="p-4">
      <MDEditor
        value={value}
        onChange={setValue}
        height={400}
      />
      <div className="mt-4 prose max-w-none">
        <MDEditor.Markdown source={value} style={{ whiteSpace: 'pre-wrap' }} />
      </div>
    </div>
  );
}

// 🔥 Render ke elemen <div id="react-editor"></div>
const rootEl = document.getElementById("react-editor");
if (rootEl) {
  ReactDOM.createRoot(rootEl).render(<App />);
}
