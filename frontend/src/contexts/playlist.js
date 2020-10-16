import React, { createContext, useState, useCallback, useContext } from 'react';

const PlaylistContext = createContext({ signed: false, user: {} });

export const PlaylistProvider = ({ children }) => {
  const [playerYoutube, setPlayerYoutube] = useState({});

  return <PlaylistContext.Provider value={{ playerYoutube, setPlayerYoutube }}>{children}</PlaylistContext.Provider>;
};

export default PlaylistContext;

export function usePlaylist() {
  const context = useContext(PlaylistContext);
  if (!context) {
    throw new Error('usePlaylist must be used within an PlaylistProvider');
  }
  return context;
}
