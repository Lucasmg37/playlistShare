import React from 'react';
import { motion } from 'framer-motion';

import './styles.scss';
import { FaPlayCircle, FaHeart, FaSpotify } from 'react-icons/fa';
import { BsMusicNoteList } from 'react-icons/bs';
import { FiCheckCircle, FiEdit3, FiEye, FiLock, FiRefreshCw, FiUnlock } from 'react-icons/fi';

const item = {
  hidden: { y: 100, opacity: 0 },
  visible: {
    y: 0,
    opacity: 1,
  },
};

function Card({
  playlist = {},
  cardSync,
  isSync,
  isSynchronizing,
  onSyncClick,
  onEyeClick,
  isEdit,
  inSpotify,
  isPrivate,
  showScope,
}) {
  const { st_capa, st_nome, st_nomeusuario } = playlist;

  return (
    <motion.li className="CardContainer" variants={item}>
      <img draggable="false" src={st_capa} alt="Capa da Playlist" />

      {!!inSpotify && (
        <button type="button" className="sincButton success">
          <FaSpotify />
        </button>
      )}

      {!!cardSync && (
        <button
          onClick={!isSync && !isSynchronizing ? onSyncClick : () => {}}
          type="button"
          className={`sincButton ${isSynchronizing && 'synchronizing'} ${isSync && 'success'}`}
        >
          {isSync ? <FiCheckCircle /> : <FiRefreshCw />}
        </button>
      )}

      <footer>
        <div>{st_nome}</div>
        <div>
          <span>{st_nomeusuario}</span>
          {!!showScope && (
            <>
              {isPrivate ? (
                <>
                  <FiLock /> Privada
                </>
              ) : (
                <>
                  <FiUnlock /> Pública
                </>
              )}
            </>
          )}
        </div>
      </footer>

      <div className="actions">
        <div>
          {(cardSync || isEdit) && <FiEye onClick={onEyeClick} />}

          {!cardSync && !isEdit && (
            <>
              <FaHeart />
              <FaPlayCircle />
              <BsMusicNoteList />
            </>
          )}
        </div>
      </div>
    </motion.li>
  );
}

export default Card;
