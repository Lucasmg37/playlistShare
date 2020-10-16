import React, { useEffect, useState, useCallback } from 'react';
import { useHistory, useParams } from 'react-router-dom';

import {
  FiTrash,
  FiPlay,
  FiEdit,
  FiPlusCircle,
  FiHeart,
  BsArrowRepeat,
  FaSpotify,
  FaHeart,
  FiSettings,
} from 'react-icons/all';

import Loading from '../../components/Loading';
import ListMedia from '../../components/ListMedia';

import api from '../../services/api';
import { useAuth } from '../../hooks/auth';

import './styles.scss';
import ApiService from '../../services/ApiService';
import { usePlaylist } from '../../contexts/playlist';
import CopyTrack from '../../components/Modal/CopyTrack';
import PlaylistEdit from '../../components/Modal/PlaylistEdit';
import SearchTrack from '../../components/Modal/SearchTrack';
import { usePlayerSpotify } from '../../contexts/playerSpotify';

function Playlist() {
  const [playlist, setPlaylist] = useState({});
  const [musicsPlaylist, setMusicsPlaylist] = useState([]);

  const [loading, setLoading] = useState(false);
  const [addNewTrackModal, setAddNewTrackModal] = useState(false);
  const [editing, setEditing] = useState(false);
  const [synchronizing, setSynchronizing] = useState(false);

  const [trackToCopy, setTrackToCopy] = useState({});
  const { playTrack, playPlaylist } = usePlayerSpotify();

  const history = useHistory();

  const { id_playlist } = useParams();
  const { user } = useAuth();
  const { setPlayerYoutube } = usePlaylist();

  const handleEdit = useCallback(() => {
    setEditing(!editing);
  }, [editing]);

  const handleNewTrack = useCallback(() => {
    setAddNewTrackModal(!addNewTrackModal);
  }, [addNewTrackModal]);

  function desativaPlaylist() {
    api.delete(`/Playlist/${id_playlist}`).then(() => {
      history.push('/');
    });
  }

  // async function clonarPlaylist(id_musicplaylist) {
  //   await api.post(`/Playlist/makeCopy/${id_musicplaylist}`).then(response => {
  //     history.push(`/playlist/${response.data.data.id_playlist}`);
  //   });
  // }

  function removeMusicPlaylist(id_musicplaylist) {
    setMusicsPlaylist(musicsPlaylist.filter(item => item.id_musicplaylist !== id_musicplaylist));
    api.delete(`/Playlist/removeMusic/${id_musicplaylist}`);
  }

  const likePlaylist = useCallback(
    async playlist_id => {
      const unLike = playlist.has_user_like === true;
      setPlaylist({ ...playlist, has_user_like: !unLike });

      try {
        await api.post('/Like', { playlist_id, unLike });
      } catch (err) {
        setPlaylist({ ...playlist, has_user_like: unLike });
      }
    },
    [playlist],
  );

  const getPlaylist = useCallback(async () => {
    const response = await ApiService.get(`/Playlist/${id_playlist}`);
    setPlaylist(response.data);
  }, [id_playlist]);

  const getTracks = useCallback(async () => {
    const response = await ApiService.get(`/Playlist/getMusics/${id_playlist}`);
    setMusicsPlaylist(response.data);
  }, [id_playlist]);

  const openYoutube = useCallback(
    id_youtube => {
      setPlayerYoutube({ id: id_youtube });
    },
    [setPlayerYoutube],
  );

  useEffect(() => {
    async function init() {
      setLoading(true);
      await getPlaylist();
      await getTracks();
      setLoading(false);
    }

    init();
  }, [id_playlist, getPlaylist, getTracks]);

  async function sincronizar() {
    setSynchronizing(true);
    await api.post(`/Playlist/sinc/${id_playlist}`);
    await getTracks();
    await getTracks();
    setSynchronizing(false);
  }

  const handleCopyTrack = useCallback(track => {
    setTrackToCopy(track);
  }, []);

  return (
    <>
      <CopyTrack isOpen={!!trackToCopy.id_spotify} track={trackToCopy} onRequestClose={() => setTrackToCopy({})} />
      <PlaylistEdit
        playlist={playlist}
        isOpen={editing}
        onRequestClose={handleEdit}
        id_playlist={id_playlist}
        onFinish={data => {
          setPlaylist(data);
        }}
      />
      <SearchTrack isOpen={addNewTrackModal} onRequestClose={handleNewTrack} onAddNewTrack={getTracks} />

      <div className="PlaylistContainer">
        {loading && !playlist.id_playlist && !musicsPlaylist.length ? (
          <Loading />
        ) : (
          <>
            <div className="tracksContent">
              {loading ? (
                <Loading />
              ) : (
                <>
                  {musicsPlaylist.length ? (
                    <>
                      <header>
                        <h1>Músicas desta playlist</h1>
                      </header>

                      <ul>
                        {musicsPlaylist.map(music => {
                          return (
                            <ListMedia
                              key={music.id_musicplaylist}
                              onCopy={handleCopyTrack}
                              music={music}
                              onPlay={playTrack}
                              onYoutube={music.yt_id ? () => openYoutube(music.yt_id) : null}
                              onRemove={
                                +user.id_usuario === +playlist.id_usuario
                                  ? () => removeMusicPlaylist(music.id_musicplaylist)
                                  : false
                              }
                            />
                          );
                        })}
                      </ul>
                    </>
                  ) : (
                    <div>
                      <h1>Ops! Sem músicas por aqui.</h1>
                      <h2>Adicione músicas para começar a personalizar!</h2>
                    </div>
                  )}
                </>
              )}
            </div>
            <div className="playlistContent">
              <div className="optionsPlaylist">
                <button type="button" onClick={() => playPlaylist(playlist.st_idspotify)}>
                  <FiPlay />
                </button>

                {+user.id_usuario === +playlist.id_usuario && (
                  <>
                    <button type="button" onClick={handleEdit}>
                      <FiEdit />
                    </button>
                    <button type="button" onClick={handleNewTrack}>
                      <FiPlusCircle />
                    </button>
                    <button type="button" onClick={desativaPlaylist}>
                      <FiTrash />
                    </button>
                    <button type="button" onClick={() => likePlaylist(playlist.id_playlist)}>
                      <FiSettings />
                    </button>
                  </>
                )}

                {!!playlist.bl_sincronizar && +user.id_usuario === +playlist.id_usuario && (
                  <button
                    className={synchronizing ? 'synchronizing' : 'notSynchronizing'}
                    type="button"
                    onClick={sincronizar}
                  >
                    <BsArrowRepeat />
                  </button>
                )}

                {+user.id_usuario !== +playlist.id_usuario && (
                  <button type="button" onClick={() => likePlaylist(playlist.id_playlist)}>
                    {playlist.has_user_like ? <FaHeart className="liked" /> : <FiHeart />}
                  </button>
                )}

                {!!playlist.st_idspotify && (
                  <button
                    type="button"
                    onClick={() => {
                      window.open(`spotify:playlist:${playlist.st_idspotify}`);
                    }}
                  >
                    <FaSpotify />
                  </button>
                )}
              </div>

              <div className="background">
                <img src={playlist.st_capa} alt="" />
                <div className="overlay" />
              </div>

              <header>
                <img src={playlist.st_capa} alt="" />
                <h1>{playlist.st_nome}</h1>
                <h2>{playlist.st_nomeusuario}</h2>
                <p>{playlist.st_descricao}</p>
              </header>
              <footer>{playlist.nu_music} faixas.</footer>
            </div>
          </>
        )}
      </div>
    </>
  );
}

export default Playlist;
