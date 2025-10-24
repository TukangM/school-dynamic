import './bootstrap';
import '../css/app.css';
import axios from 'axios';
import { ZeroMd } from 'zero-md';

// Contoh tes axios
axios.get('/').then(response => console.log('Axios OK!', response.status));

// Register zero-md component
customElements.define('zero-md', ZeroMd);