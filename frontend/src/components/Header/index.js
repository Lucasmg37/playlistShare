import React from 'react';

import './styles.scss';
import { usePlaylist } from '../../hooks/playlist';

const Header = () => {
  const { playlistActive } = usePlaylist();

  return (
    <div className="header-component-container">
      <img
        src={
          playlistActive.st_capa
            ? playlistActive.st_capa
            : 'https://i.scdn.co/image/714480ee06a31bdf82b27d7c319b4e250d492a2b'
        }
        alt="Foto de Capa"
      />
      <div className="overlay" />
      <div className="content">
        <div className="info">
          <h2>{playlistActive.title}</h2>
          <h1>{playlistActive.st_nome}</h1>
          <h3>{playlistActive.subtitle}</h3>
          {playlistActive.nu_music && (
            <h4>
              <strong>{playlistActive.nu_music}</strong> {playlistActive.nu_music > 1 ? 'músicas' : 'música'}
            </h4>
          )}
        </div>
      </div>
    </div>
  );
};

export default Header;
