import React, { useCallback, useState, useEffect } from 'react';
import Toggle from '../../Toggle';
import { useAuth } from '../../../hooks/auth';

import './styles.scss';
import api from '../../../services/api';
import Modal from '..';

function PlaylistEdit({ isOpen, onFinish, onRequestClose, id_playlist, playlist = {} }) {
  const [playlistObject, setPlaylistObject] = useState({});
  const { user } = useAuth();

  useEffect(() => {
    setPlaylistObject(playlist);
  }, [playlist]);

  const handleSavePlay = useCallback(
    async e => {
      e.preventDefault();
      await api.post('/Playlist', playlistObject).then(response => {
        if (onFinish) {
          onFinish(response.data.data);
        }
        if (onRequestClose) {
          onRequestClose();
        }
      });
    },
    [onFinish, playlistObject, onRequestClose],
  );

  return (
    <Modal onRequestClose={onRequestClose} isOpen={isOpen}>
      <div className="PlaylistEditContainer">
        <header>{id_playlist ? <h1>Mude o que quiser.</h1> : <h1>Crie a sua.</h1>}</header>

        <form onSubmit={handleSavePlay}>
          <input
            onChange={e => setPlaylistObject({ ...playlistObject, st_nome: e.target.value })}
            value={playlistObject.st_nome}
            placeholder="Nome da playlist"
          />

          <textarea
            onChange={e => setPlaylistObject({ ...playlistObject, st_descricao: e.target.value })}
            value={playlistObject.st_descricao}
            className="text-area-input"
            placeholder="Descreva a sua playlist!"
          />

          <Toggle
            onChange={() => setPlaylistObject({ ...playlistObject, bl_privada: !playlistObject.bl_privada })}
            checked={!!playlistObject.bl_privada}
          >
            Playlist privada. (Outros usuários não têm acesso a sua playlist.)
          </Toggle>

          <Toggle
            onChange={() => setPlaylistObject({ ...playlistObject, bl_publicedit: !playlistObject.bl_publicedit })}
            checked={!!playlistObject.bl_publicedit}
          >
            Playlist editável. (Outros usuários podem adicionar e remover músicas desta playlist.)
          </Toggle>

          {!!user.bl_integracao && (
            <div className="input-toggle">
              <Toggle
                onChange={() =>
                  setPlaylistObject({ ...playlistObject, bl_sincronizar: !playlistObject.bl_sincronizar })
                }
                checked={!!playlistObject.bl_sincronizar}
              >
                Sincronizar com o Spotify
              </Toggle>
            </div>
          )}

          <button type="submit" className="button-primary">
            Salvar
          </button>
        </form>
      </div>
    </Modal>
  );
}

export default PlaylistEdit;
