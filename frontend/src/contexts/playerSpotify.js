import Axios from 'axios';
import React, { createContext, useState, useContext, useCallback } from 'react';

const PlayerSpotifyContext = createContext({ signed: false, user: {} });

export const PlayerSpotifyProvider = ({ children }) => {
  const [current, setCurrent] = useState({});
  const [deviceId, setDeviceId] = useState();
  const [token, setToken] = useState();

  const handleRequestPlayerSpotify = useCallback(
    async (route, data = {}) => {
      await Axios.put(
        `https://api.spotify.com/v1/me/player/${route}?device_id=${deviceId}`,
        {
          ...data,
        },
        {
          headers: { Authorization: `Bearer ${token}` },
        },
      );
    },
    [deviceId, token],
  );

  const playTrack = useCallback(
    track => {
      handleRequestPlayerSpotify('play', {
        uris: [`spotify:track:${track}`],
      });
    },
    [handleRequestPlayerSpotify],
  );

  const playPlaylist = useCallback(
    playlist => {
      handleRequestPlayerSpotify('play', {
        context_uri: `spotify:playlist:${playlist}`,
      });
    },
    [handleRequestPlayerSpotify],
  );

  const play = useCallback(() => {
    handleRequestPlayerSpotify('play');
  }, [handleRequestPlayerSpotify]);

  const pause = useCallback(() => {
    handleRequestPlayerSpotify('pause');
  }, [handleRequestPlayerSpotify]);

  return (
    <PlayerSpotifyContext.Provider
      value={{ current, setCurrent, setDeviceId, setToken, playTrack, play, pause, playPlaylist }}
    >
      {children}
    </PlayerSpotifyContext.Provider>
  );
};

export default PlayerSpotifyContext;

export function usePlayerSpotify() {
  const context = useContext(PlayerSpotifyContext);
  if (!context) {
    throw new Error('usePlayerSpotify must be used within an PlayerSpotifyProvider');
  }
  return context;
}
