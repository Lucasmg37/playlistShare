import React, { useEffect, useState } from 'react';

import './Perfil.css';
import Toggle from '../components/Toggle';
import SpotifyLogin from '../components/SpotifyLogin';
import api from '../services/api';

export default function Perfil(props) {
  const [bl_sincronizaclone, setBl_sincronizaclone] = useState(false);
  const [id_config, setId_config] = useState('');
  const [bl_atualizaspotify, setBl_atualizaspotify] = useState(false);
  const [bl_buscamudancasspotify, setBl_buscamudancasspotify] = useState(false);
  const [bl_deleteplaylistspotify, setBl_deleteplaylistspotify] = useState(
    false,
  );

  useEffect(() => {
    async function verificaIntegracao() {
      await api.get('/Usuario/getConfig').then(response => {
        setBl_sincronizaclone(response.data.data.bl_sincronizaclone);
        setBl_atualizaspotify(response.data.data.bl_atualizaspotify);
        setId_config(response.data.data.id_config);
        setBl_buscamudancasspotify(response.data.data.bl_buscamudancasspotify);
        setBl_deleteplaylistspotify(
          response.data.data.bl_deleteplaylistspotify,
        );
      });
    }

    verificaIntegracao();
  }, []);

  const onSuccess = async response => {
    await api.post('/Integracao', response);
    props.setUsuario();
  };

  const onFailure = () => {};

  const saveConfigsIntegracao = () => {
    api.post('/Usuario/saveConfig', {
      id_config,
      bl_atualizaspotify,
      bl_buscamudancasspotify,
      bl_sincronizaclone,
      bl_deleteplaylistspotify,
    });
  };

  async function desconectSpotify() {
    await api.delete('/Integracao');
    props.setUsuario();
  }

  return (
    <div>
      {+props.usuario.bl_integracao === 0 ? (
        <div className="connect-spotify">
          <SpotifyLogin
            clientId={process.env.REACT_APP_SPOTIFY_CLIENT_ID}
            redirectUri={process.env.REACT_APP_SPOTIFY_REDIRECT_URL}
            scope={process.env.REACT_APP_SPOTIFY_SCOPES}
            onSuccess={onSuccess}
            onFailure={onFailure}
            buttonText="Conectar ao Spotify"
          />
        </div>
      ) : (
        <div className="connect-spotify">
          <button type="button" className="btn-connected" disabled>
            Você está conectado ao Spotify
          </button>
        </div>
      )}

      {props.usuario.bl_integracao && (
        <div className="perfil-area">
          <h2>Integração com Spotify</h2>

          <div className="input-toggle">
            <Toggle
              name="bl_sincronizaclone"
              onChangeFunction={() =>
                setBl_sincronizaclone(!bl_sincronizaclone)
              }
              checked={bl_sincronizaclone}
            />
            <label htmlFor="bl_sincronizaclone">
              Sincronizar playlists clonadas automáticamente.
            </label>
          </div>

          <div className="input-toggle">
            <Toggle
              name="bl_atualizaspotify"
              onChangeFunction={() =>
                setBl_atualizaspotify(!bl_atualizaspotify)
              }
              checked={bl_atualizaspotify}
            />
            <label htmlFor="bl_atualizaspotify">
              Atualizar playlists sincronizadas do spotify automáticamente
            </label>
          </div>

          <div className="input-toggle">
            <Toggle
              name="bl_buscamudancasspotify"
              onChangeFunction={() =>
                setBl_buscamudancasspotify(!bl_buscamudancasspotify)
              }
              checked={bl_buscamudancasspotify}
            />
            <label htmlFor="bl_buscamudancasspotify">
              Buscar mudanças em playlists do spotify automáticamente
            </label>
          </div>

          {/* <div className="input-toggle"> */}
          {/* <Toggle */}
          {/* name="bl_deleteplaylistspotify" */}
          {/* onChangeFunction={() => setBl_deleteplaylistspotify(!bl_deleteplaylistspotify)} */}
          {/* checked={bl_deleteplaylistspotify} */}
          {/* /> */}
          {/* <label for="bl_buscamudancasspotify">Deletar playlist do Spotify.</label> */}
          {/* </div> */}

          <div>
            <button
              onClick={desconectSpotify}
              type="button"
              className="desconect-spotify"
            >
              Desconectar Spotify
            </button>
          </div>
          <div>
            <button
              type="button"
              onClick={saveConfigsIntegracao}
              className="button-primary"
            >
              Salvar configurações
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
