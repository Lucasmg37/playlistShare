import React, { useEffect, useState } from 'react';

import Loading from '../../components/Loading';
import CardLarge from '../../components/CardLarge';
import Carousel from '../../components/Carousel';
import Card from '../../components/Card';

import api from '../../services/api';

import './styles.scss';
import { useFetch } from '../../hooks/useFetch';

export default function Home({ history }) {
  const [playlists, setPlaylists] = useState([]);
  const [loaded, setLoaded] = useState(false);

  const { data: topMyPlaylists, error: errorTopMyPlaylists } = useFetch(`/Playlist/getMoreAccess`);

  useEffect(() => {
    async function loadTopPlaylists() {
      const response = await api.get('/Playlist');
      setPlaylists(response.data.data);
      setLoaded(true);
    }

    loadTopPlaylists();
  }, []);

  function openPlaylist(id_playlist) {
    history.push(`/playlist/${id_playlist}`);
  }

  return (
    <div className="HomeContainer">
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

          {topMyPlaylists && (
            <Carousel title="Atalhos">
              {!!topMyPlaylists.data.length &&
                topMyPlaylists.data.map(playlist => (
                  <Card
                    key={playlist.id_playlist}
                    playlist={playlist}
                    onClick={() => openPlaylist(playlist.id_playlist)}
                  />
                ))}
            </Carousel>
          )}

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
