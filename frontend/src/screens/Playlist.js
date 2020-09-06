import React, { useEffect, useState } from 'react';

import api from '../services/api';
import './Playlist.css';

import ListMedia from '../components/ListMedia';
import HeaderPlaylist from '../components/HeaderPlaylist';

import PopUp from './PopUp';

export default function Playlist(props) {
  const [playlist, setPlaylist] = useState({});
  const [musicsPlaylist, setMusicsPlaylist] = useState([]);
  const [loadmusics, setLoadmusics] = useState(false);
  const [loadplaylist, setLoadPlaylist] = useState(false);

  function desativaPlaylist(id_playlist) {
    api.delete(`/Playlist/${id_playlist}`).then(response => {
      props.history.push('/');
    });
  }

  async function clonarPlaylist(id_musicplaylist) {
    await api.post(`/Playlist/makeCopy/${id_musicplaylist}`).then(response => {
      props.history.push(`/playlist/${response.data.data.id_playlist}`);
    });
  }

  function playPlaylist(id_playlis) {
    api.put(`/Player/playPlaylist/${id_playlis}`);
  }

  function selectMusic(id_musicplaylist) {
    setMusicsPlaylist(
      musicsPlaylist.map(music => {
        if (+music.id_musicplaylist === +id_musicplaylist) {
          music.bl_selected = !music.bl_selected;
        }

        return music;
      }, id_musicplaylist),
    );
  }

  async function sincronizar(id_playlist) {
    setLoadPlaylist(false);
    await api.post(`/Playlist/${id_playlist}/sincronizar`).finally(() => {
      loadPlaylistMusics();
      setLoadPlaylist(true);
    });
  }

  function removeMusicPlaylist(id_musicplaylist) {
    setMusicsPlaylist(
      musicsPlaylist.filter(
        musicsPlaylist => musicsPlaylist.id_musicplaylist !== id_musicplaylist,
      ),
    );
    api.delete(`/Playlist/removeMusic/${id_musicplaylist}`);
  }

  async function playPlaylistMusic(id_musicplaylist) {
    api.put(`/Player/playTrack/${id_musicplaylist}`);
  }

  useEffect(() => {
    async function loadPlaylist() {
      loadPlaylistMusics();

      const response = await api.get(`/Playlist/${props.id_playlist}`);
      setPlaylist(response.data.data);

      if (!response.data.data.id_playlist) {
        props.history.push('/');
        return;
      }

      setLoadPlaylist(true);
    }

    loadPlaylist();
  }, [props.id_playlist]);

  async function loadPlaylistMusics() {
    const response = await api.get(`/Playlist/getMusics/${props.id_playlist}`);
    setMusicsPlaylist(response.data.data);
    setLoadmusics(true);
  }

  return (
    <div className="container-area-full">
      <PopUp />

      <div
        className={
          loadplaylist
            ? 'header-playlist header-playlist-show'
            : 'header-playlist'
        }
      >
        <HeaderPlaylist
          playlist={playlist}
          buttons={[
            {
              text: 'Curtir',
              show: +props.usuario.id_usuario !== +playlist.id_usuario,
              icon: 'fa-heart',
            },
            {
              text: 'Copiar',
              show: true,
              icon: 'fa-copy',
              action: () => clonarPlaylist(playlist.id_playlist),
            },
            {
              text: 'Nova Música',
              show:
                (+props.usuario.id_usuario === +playlist.id_usuario &&
                  +playlist.bl_publicedit === 0) ||
                +playlist.bl_publicedit === 1,
              icon: 'fa-plus',
              action: () =>
                props.history.push(`/playlist/${playlist.id_playlist}/new`),
            },
            {
              text: 'Compartilhar',
              show: true,
              icon: 'fa-link',
            },
            {
              text: 'Editar',
              show: +props.usuario.id_usuario === +playlist.id_usuario,
              icon: 'fa-edit',
              action: () => props.history.push(`/edit/${playlist.id_playlist}`),
            },
            {
              text: 'Excluir',
              show: +props.usuario.id_usuario === +playlist.id_usuario,
              icon: 'fa-trash',
              action: () => desativaPlaylist(playlist.id_playlist),
            },
            {
              text: 'Reproduzir',
              show:
                +props.usuario.bl_premium === 1 &&
                +playlist.bl_sincronizado === 1,
              icon: 'fa-play',
              action: () => playPlaylist(playlist.st_idspotify),
            },
            {
              text: 'Sincronizar',
              show:
                +props.usuario.bl_integracao === 1 &&
                +playlist.bl_sincronizado === 1 &&
                +props.usuario.id_usuario === +playlist.id_usuario,
              icon: 'fa-exchange-alt',
              action: () => sincronizar(playlist.id_playlist),
            },
          ]}
        />
      </div>

      <div className="container-area">
        <div>
          <ul>
            {musicsPlaylist.map(music => (
              <li
                key={music.id_musicplaylist}
                className={
                  +music.bl_selected === 1 ? 'list-playlist-select' : ''
                }
              >
                <ListMedia
                  hoverCapa={props.usuario.bl_premium}
                  clickCapa={() => playPlaylistMusic(music.id_spotify)}
                  playlist={playlist}
                  usuario={props.usuario.id_usuario}
                  music={music}
                  buttons={[
                    // {
                    //     text: 'Copiar para...',
                    //     show: true,
                    //
                    // },
                    {
                      text: 'Remover',
                      show:
                        (+props.usuario.id_usuario === +playlist.id_usuario &&
                          +playlist.bl_publicedit === 0) ||
                        +playlist.bl_publicedit === 1,
                      action: () => removeMusicPlaylist(music.id_musicplaylist),
                    },
                  ]}
                />
              </li>
            ))}
          </ul>
        </div>
      </div>
    </div>
  );
}
