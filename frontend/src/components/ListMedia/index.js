import React from 'react';

import './styles.scss';
import { FiPlayCircle, FiTrash, FiCopy, FiPlusCircle, FiYoutube } from 'react-icons/fi';

function ListMedia({ music, onPlay, onPlus, onRemove, onYoutube, onCopy }) {
  const { st_urlimagem, st_nome, st_album, st_artista, id_spotify } = music;

  return (
    <li className="ListMediaContainer">
      <span>
        <img src={st_urlimagem} alt="" />
      </span>
      <span>{st_nome}</span>
      <span>{st_artista}</span>
      <span>{st_album}</span>
      {/* <span>5:34</span> */}

      <div className="actions">
        {onPlus && (
          <button type="button" onClick={() => onPlus(id_spotify)}>
            <FiPlusCircle />
          </button>
        )}

        <button type="button" onClick={() => onPlay(id_spotify)}>
          <FiPlayCircle />
        </button>

        {onRemove && (
          <button type="button" onClick={() => onRemove(id_spotify)}>
            <FiTrash />
          </button>
        )}

        {onYoutube && (
          <button type="button" onClick={() => onYoutube(id_spotify)}>
            <FiYoutube />
          </button>
        )}

        {onCopy && (
          <button type="button" onClick={() => onCopy(music)}>
            <FiCopy />
          </button>
        )}
      </div>
    </li>
  );
}

export default ListMedia;
