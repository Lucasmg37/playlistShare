import React, { createContext, useState, useContext } from 'react';

const PlaylistContext = createContext({ playlistActive: {} });

export const PlaylistProvider = ({ children }) => {
  const [playlistActive, setPlaylistActive] = useState({});

  return (
    <PlaylistContext.Provider
      value={{
        playlistActive,
        setPlaylistActive,
      }}
    >
      {children}
    </PlaylistContext.Provider>
  );
};

export default PlaylistContext;

export function usePlaylist() {
  const context = useContext(PlaylistContext);
  if (!context) {
    throw new Error('usePlaylist must be used within an PlaylistContext');
  }
  return context;
}
