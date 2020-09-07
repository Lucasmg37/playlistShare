import React from 'react';
import { motion } from 'framer-motion';

import './styles.scss';

const item = {
  hidden: { y: 100, opacity: 0 },
  visible: {
    y: 0,
    opacity: 1,
  },
};

function CardLarge({ playlist = {}, onClick }) {
  const { id_playlist, st_capa, st_nome, nu_music, st_nomeusuario } = playlist;

  return (
    <motion.li className="CardLargeContainer" variants={item} onClick={() => onClick(id_playlist)}>
      <div className="capa">
        <img draggable="false" src={st_capa} alt="Capa da Playlist" />
      </div>

      <footer>
        <div>{st_nome}</div>
        <div>
          <span>{nu_music}</span>|<span>{st_nomeusuario}</span>
        </div>
      </footer>
    </motion.li>
  );
}

export default CardLarge;
