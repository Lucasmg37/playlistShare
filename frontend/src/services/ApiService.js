import axios from 'axios';

import { api } from '../config/config';

const ApiService = axios.create({
  baseURL: api.endpoint,
});

ApiService.interceptors.request.use(async config => {
  const userToken = await localStorage.getItem('st_token');

  if (userToken) {
    config.headers = { Authorization: `Bearer ${userToken}` };
  }

  return config;
});

ApiService.interceptors.response.use(
  response => response.data,
  error => {
    throw (error.response || error.request).data;
  },
);

export default ApiService;
