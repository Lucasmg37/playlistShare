import React from 'react';
import { motion } from 'framer-motion';

import './styles.scss';
import { FaPlayCircle, FaHeart } from 'react-icons/fa';
import { BsMusicNoteList } from 'react-icons/bs';

const item = {
  hidden: { y: 100, opacity: 0 },
  visible: {
    y: 0,
    opacity: 1,
  },
};

function Card({ playlist = {} }) {
  const { st_capa, st_nome, st_nomeusuario } = playlist;

  return (
    <motion.li className="CardContainer" variants={item}>
      <img draggable="false" src={st_capa} alt="Capa da Playlist" />

      <footer>
        <div>{st_nome}</div>
        <div>
          <span>{st_nomeusuario}</span>
        </div>
      </footer>

      <div className="actions">
        <div>
          <FaHeart />
          <FaPlayCircle />
          <BsMusicNoteList />
        </div>
      </div>
    </motion.li>
  );
}

export default Card;
