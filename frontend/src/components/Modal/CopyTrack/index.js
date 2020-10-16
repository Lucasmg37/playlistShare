import React, { useCallback, useEffect, useState } from 'react';
import { FiArrowRight, FiCheckCircle, FiSend } from 'react-icons/fi';
import Modal from '..';
import useToastify from '../../../hooks/toastify';
import ApiService from '../../../services/ApiService';

import './styles.scss';

function CopyTrack({ track, isOpen, onRequestClose }) {
  const { id_spotify, st_urlimagem, st_artista, st_nome } = track;
  const { error } = useToastify();

  const [playlists, setPlaylists] = useState([]);

  useEffect(() => {
    async function init() {
      const { data } = await ApiService.get('/Playlist/myPlaylists');
      setPlaylists(data);
    }

    init();
  }, [id_spotify]);

  const handleaddTrack = useCallback(
    id_playlist => {
      try {
        ApiService.post('/Playlist/newMusic/', {
          id_spotify,
          id_playlist,
        });

        setPlaylists(
          playlists.map(item => {
            if (+item.id_playlist === +id_playlist) {
              item.present = true;
            }
            return item;
          }),
        );
      } catch (err) {
        error('Ops, algo deu errado aou enviar essa faixa.');
      }
    },
    [id_spotify, playlists, error],
  );

  if (!isOpen) {
    return null;
  }

  return (
    <Modal isOpen={isOpen} onRequestClose={onRequestClose}>
      <div className="CopyTrackContainer">
        <header>
          <img src={st_urlimagem} alt="Capa Track" />
          <div>
            <h2>{st_nome}</h2>
            <h3>{st_artista}</h3>
          </div>
        </header>
        <h1>
          Copiar para <FiArrowRight />
        </h1>

        <ul>
          {!!playlists &&
            playlists.map(playlist => {
              return (
                <li>
                  <button onClick={() => handleaddTrack(playlist.id_playlist)} type="button">
                    <img src={playlist.st_capa} alt="Capa da Playlist" />
                    <span>{playlist.st_nome}</span>
                    {playlist.present ? <FiCheckCircle /> : <FiSend />}
                  </button>
                </li>
              );
            })}
        </ul>

        <button onClick={onRequestClose} type="submit" className="button-primary">
          Concluir
        </button>
      </div>
    </Modal>
  );
}

export default CopyTrack;
