import axios from 'axios';

const api = axios.create({
  baseURL: 'http://playlistShare.localhost:8000/Api/',
  headers: { Authorization: `Bearer ${localStorage.getItem('@GROUPLISTAuth:token')}` },
});

export default api;
