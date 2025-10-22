import './bootstrap';
import '../css/app.css';
import axios from 'axios';

// Contoh tes axios
axios.get('/').then(response => console.log('Axios OK!', response.status));

// Optional: jika ingin pakai zero-md (render markdown)
import 'zero-md';
