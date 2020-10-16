import React from 'react';
import { useHistory } from 'react-router-dom';
import { FaSpotify } from 'react-icons/fa';
import { FiEdit, FiHeart, FiLink2, FiPlay, FiRefreshCw } from 'react-icons/fi';

import Modal from '..';
import Loading from '../../Loading';

import './styles.scss';
import { useFetch } from '../../../hooks/useFetch';
import { useAuth } from '../../../hooks/auth';
import { playPlaylist } from '../../../services/PlayerSpotify';

function SpotifyPlaylistInfo({ onRequestClose, isOpen, playlist, migratePlaylist }) {
  const history = useHistory();
  const { user } = useAuth();

  const { data: tracksCurrentPlaylist, error: errorTracksCurrentPlaylist } = useFetch(
    playlist.id ? `/Playlist/getTracksPlaylistSpotify/${playlist.id}` : null,
  );

  return (
    <Modal onRequestClose={onRequestClose} isOpen={isOpen}>
      <div className="SpotifyPlaylistInfoContainer">
        <aside>
          <header>
            <div>
              <img src={playlist.image} alt="" />
              <button
                onClick={() => {
                  playPlaylist(playlist.id);
                }}
                type="button"
              >
                <FiPlay />
              </button>
            </div>
            <h1>{playlist.name}</h1>
            <h2>{playlist.owner && playlist.owner.display_name}</h2>
            <h3> {playlist.tracks && playlist.tracks.total} faixas</h3>
          </header>

          <footer>
            {playlist.bl_sincronizado ? (
              <>
                <button onClick={() => history.push(`/playlist/${playlist.playlist.id_playlist}`)} type="button">
                  <FiLink2 />
                </button>

                <button type="button">
                  <FiHeart />
                </button>

                {playlist.playlist &&
                  playlist.playlist.id_usuario === user.id_usuario &&
                  !playlist.playlist.st_idownerspotify && (
                    <button type="button">
                      <FiEdit />
                    </button>
                  )}
              </>
            ) : (
              <button onClick={migratePlaylist} type="button">
                <FiRefreshCw />
              </button>
            )}

            <button type="button">
              <FaSpotify />
            </button>
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
  );
}

export default SpotifyPlaylistInfo;
