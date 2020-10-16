import React, { useState } from 'react';
import { FiEdit3, FiEye, FiHeart, FiLock, FiRefreshCw, FiStar, FiUnlock, FiUserPlus } from 'react-icons/fi';
import { FaSpotify } from 'react-icons/fa';
import Loading from '../../components/Loading';
import Card from '../../components/Card';

import './styles.scss';
import Modal from '../../components/Modal';
import { useFetch } from '../../hooks/useFetch';
import CardLarge from '../../components/CardLarge';
import Carousel from '../../components/Carousel';

export default function Library({ history }) {
  const [currentPlaylist, setCurrentPlaylist] = useState({});

  const { data: playlists } = useFetch(`/Playlist/myPlaylists`);
  const { data: likePlaylists } = useFetch(`/Like`);
  const { data: topMyPlaylists } = useFetch(`/Playlist/getMoreAccess`);

  const { data: tracksCurrentPlaylist, error: errorTracksCurrentPlaylist } = useFetch(
    currentPlaylist.id_playlist ? `/Playlist/getMusics/${currentPlaylist.id_playlist}` : null,
  );

  return (
    <div className="LibraryContainer">
      <Modal onRequestClose={() => setCurrentPlaylist({})} isOpen={currentPlaylist.id_playlist}>
        <div className="ModalDetailsPlaylist">
          <aside>
            <header>
              <img src={currentPlaylist.st_capa} alt="" />
              <h1>{currentPlaylist.st_nome}</h1>
              <p>{currentPlaylist.st_descricao}</p>
              <h3> {currentPlaylist.nu_music} faixas</h3>
              <h2>
                {currentPlaylist.bl_privada ? (
                  <>
                    <FiLock /> Playlist Privada
                  </>
                ) : (
                  <>
                    <FiUnlock /> Playlist Pública
                  </>
                )}
              </h2>
            </header>

            <footer>
              <button onClick={() => history.push(`/playlist/${currentPlaylist.id_playlist}`)} type="button">
                <FiEye />
              </button>

              <button type="button">
                <FiEdit3 />
              </button>

              {!!currentPlaylist.st_idspotify && (
                <>
                  <button onClick={() => {}} type="button">
                    <FiRefreshCw />
                  </button>
                  <button type="button">
                    <FaSpotify />
                  </button>
                </>
              )}
            </footer>
          </aside>
          <div>
            {!tracksCurrentPlaylist && !errorTracksCurrentPlaylist ? (
              <Loading />
            ) : (
              <ul className="listTracksSpotify">
                {tracksCurrentPlaylist.data.map((track, index) => (
                  <li>
                    <img src={track.st_urlimagem} alt="" />
                    <div>
                      <h1>
                        {index + 1}. {track.st_nome}
                      </h1>
                      <h2>{track.st_artista}</h2>
                    </div>
                  </li>
                ))}
              </ul>
            )}
          </div>
        </div>
      </Modal>

      <div className="navBarFloat">
        <button type="button">
          <FiStar /> <span>Mais acessadas</span>
        </button>
        <button type="button">
          <FiUserPlus /> <span>Criadas por você</span>
        </button>
        <button type="button">
          <FiHeart /> <span>Que você curtiu</span>
        </button>
      </div>

      {topMyPlaylists !== undefined && (
        <>
          <Carousel title="Playlists que você mais acessa">
            {!!topMyPlaylists.data.length &&
              topMyPlaylists.data.map(playlist => (
                <CardLarge
                  key={playlist.id_playlist}
                  playlist={playlist}
                  onClick={() => setCurrentPlaylist(playlist)}
                />
              ))}
          </Carousel>
        </>
      )}

      {likePlaylists && !!likePlaylists.data.length && (
        <>
          <h1>
            <FiHeart /> Playlists que você curtiu
          </h1>
          <div className="gridContent">
            <ul>
              {likePlaylists.data.map(playlist => (
                <Card
                  showScope
                  isPrivate={!!playlist.bl_privada}
                  inSpotify={!!playlist.st_idspotify}
                  playlist={{ ...playlist, st_nomeusuario: '' }}
                  isEdit
                  onEyeClick={() => setCurrentPlaylist(playlist)}
                />
              ))}
            </ul>
          </div>
        </>
      )}

      {playlists && !!playlists.data.length && (
        <>
          <h1>
            <FiUserPlus /> Todas as suas playlists
          </h1>
          <div className="gridContent">
            <ul>
              {playlists.data.map(playlist => (
                <Card
                  showScope
                  isPrivate={!!playlist.bl_privada}
                  inSpotify={!!playlist.st_idspotify}
                  playlist={{ ...playlist, st_nomeusuario: '' }}
                  isEdit
                  onEyeClick={() => setCurrentPlaylist(playlist)}
                />
              ))}
            </ul>
          </div>
        </>
      )}
    </div>
  );
}
