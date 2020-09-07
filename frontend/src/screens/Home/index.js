import React, { useEffect, useState } from 'react';

import api from '../../services/api';
import Loading from '../../components/Loading';
import { usePlaylist } from '../../hooks/playlist';
import './styles.scss';
import CardLarge from '../../components/CardLarge';
import Carousel from '../../components/Carousel';
import Card from '../../components/Card';

export default function Home({ history }) {
  const [playlists, setPlaylists] = useState([]);
  const [loaded, setLoaded] = useState(false);
  const { setPlaylistActive } = usePlaylist();

  useEffect(() => {
    async function loadTopPlaylists() {
      const response = await api.get('/Playlist');
      setPlaylists(response.data.data);
      setLoaded(true);
    }

    loadTopPlaylists();
  }, [setPlaylistActive]);

  function openPlaylist(id_playlist) {
    history.push(`/playlist/${id_playlist}`);
  }

  return (
    <div className="container-screen-home">
      {loaded ? (
        <>
          <Carousel title="As mais acessadas">
            {playlists !== undefined &&
              playlists.length > 0 &&
              playlists.map(playlist => (
                <CardLarge
                  key={playlist.id_playlist}
                  playlist={playlist}
                  onClick={() => openPlaylist(playlist.id_playlist)}
                />
              ))}
          </Carousel>

          <Carousel title="Atalhos">
            {playlists !== undefined &&
              playlists.length > 0 &&
              playlists.map(playlist => (
                <Card
                  key={playlist.id_playlist}
                  playlist={playlist}
                  onClick={() => openPlaylist(playlist.id_playlist)}
                />
              ))}
          </Carousel>

          <Carousel title="Recentemente vizualizadas">
            {playlists !== undefined &&
              playlists.length > 0 &&
              playlists.map(playlist => (
                <Card
                  key={playlist.id_playlist}
                  playlist={playlist}
                  onClick={() => openPlaylist(playlist.id_playlist)}
                />
              ))}
          </Carousel>

          <Carousel title="Novas que você pode gostar">
            {playlists !== undefined &&
              playlists.length > 0 &&
              playlists.map(playlist => (
                <Card
                  key={playlist.id_playlist}
                  playlist={playlist}
                  onClick={() => openPlaylist(playlist.id_playlist)}
                />
              ))}
          </Carousel>
        </>
      ) : (
        <Loading />
      )}
    </div>
  );
}
