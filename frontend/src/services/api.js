import axios from 'axios';

const api = axios.create({
  baseURL: 'http://groupmusicapi.localhost:8000/Api/',
  headers: { Authorization: `Bearer ${localStorage.getItem('st_token')}` },
});

export default api;
