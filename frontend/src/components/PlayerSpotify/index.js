import React, { useCallback } from 'react';
import Script from 'react-load-script';
import { usePlayerSpotify } from '../../contexts/playerSpotify';
import ApiService from '../../services/ApiService';

function PlayerSpotify() {
  const { setCurrent, setToken, setDeviceId } = usePlayerSpotify();

  const playerInit = useCallback(async () => {
    const { data: token } = await ApiService.get('Usuario/getAccessTokenSpotify');

    setToken(token);

    const player = new window.Spotify.Player({
      name: 'Playlist Share',
      getOAuthToken: cb => {
        cb(token);
      },
    });

    // Error handling
    // player.on('initialization_error', e => console.error(e));
    // player.on('authentication_error', e => console.error(e));
    // player.on('account_error', e => console.error(e));
    // player.on('playback_error', e => console.error(e));

    // Playback status updates
    player.on('player_state_changed', state => {
      setCurrent(state);
    });

    player.on('ready', data => {
      setDeviceId(data.device_id);
    });

    player.connect();
  }, [setCurrent, setDeviceId, setToken]);

  const handleLoadPlayer = useCallback(() => {
    return new Promise(() => {
      if (window.Spotify) {
        playerInit();
      } else {
        window.onSpotifyWebPlaybackSDKReady = playerInit;
      }
    });
  }, [playerInit]);

  return <Script url="https://sdk.scdn.co/spotify-player.js" onLoad={handleLoadPlayer} />;
}

export default PlayerSpotify;
