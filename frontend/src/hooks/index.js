import React from 'react';

import { AuthProvider } from './auth';
import { PlaylistProvider } from './playlist';

const AppProvider = ({ children }) => (
  <AuthProvider>
    <PlaylistProvider>{children}</PlaylistProvider>
  </AuthProvider>
);
export default AppProvider;
