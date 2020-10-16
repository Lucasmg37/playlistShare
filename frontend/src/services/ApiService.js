import axios from 'axios';

import { api } from '../config/config';

const ApiService = axios.create({
  baseURL: api.endpoint,
});

ApiService.interceptors.request.use(async config => {
  const userToken = await localStorage.getItem('@GROUPLISTAuth:token');

  if (userToken) {
    config.headers = { Authorization: `Bearer ${userToken}` };
  }

  return config;
});

ApiService.interceptors.response.use(
  response => response.data,
  error => {
    if (error.response && error.response.status === 401) {
      localStorage.clear();
      window.location.pathname = '/login';
      return;
    }
    throw (error.response || error.request).data;
  },
);

export default ApiService;
