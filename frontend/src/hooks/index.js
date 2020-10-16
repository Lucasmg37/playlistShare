import React from 'react';
import { PlayerSpotifyProvider } from '../contexts/playerSpotify';
import { PlaylistProvider } from '../contexts/playlist';

import { AuthProvider } from './auth';

const AppProvider = ({ children }) => (
  <AuthProvider>
    <PlayerSpotifyProvider>
      <PlaylistProvider>{children}</PlaylistProvider>
    </PlayerSpotifyProvider>
  </AuthProvider>
);
export default AppProvider;
