import React, { useCallback, useState } from 'react';

import './styles.scss';
import { NavLink, useHistory } from 'react-router-dom';
import { FiPlusCircle, FiSettings } from 'react-icons/fi';
import { itemsMenu } from './items';
import PlaylistEdit from '../Modal/PlaylistEdit';
import SoundBars from '../SoundBars';
import { usePlayerSpotify } from '../../contexts/playerSpotify';

const Menu = () => {
  const [modalNewPlaylist, setModalNewPlaylist] = useState(false);

  const history = useHistory();
  const { current } = usePlayerSpotify();

  const handleModalNewPlaylist = useCallback(() => {
    setModalNewPlaylist(!modalNewPlaylist);
  }, [modalNewPlaylist]);

  return (
    <>
      <PlaylistEdit
        playlist={{}}
        isOpen={modalNewPlaylist}
        onRequestClose={handleModalNewPlaylist}
        id_playlist=""
        onFinish={data => {
          history.push(`/playlist/${data.id_playlist}`);
        }}
      />
      <div className="MenuContainer">
        <ul>
          {itemsMenu.map(item => {
            return (
              <NavLink key={item.route} exact to={item.route}>
                <li>{item.icon()}</li>
              </NavLink>
            );
          })}
          {!!current.track_window && (
            <li>
              <SoundBars />
            </li>
          )}
        </ul>

        <footer>
          <button type="button" onClick={handleModalNewPlaylist}>
            <li>
              <FiPlusCircle />
            </li>
          </button>

          <NavLink to="/settings">
            <li>
              <FiSettings />
            </li>
          </NavLink>

          <NavLink className="noHover" to="/perfil">
            <li className="noHover">
              <img
                src="https://avatars3.githubusercontent.com/u/25160385?s=460&u=210ad7ca8bdee219f7f3eccf50768477e5abf6e9&v=4"
                alt=""
              />
            </li>
          </NavLink>
        </footer>
      </div>
    </>
  );
};

export default Menu;
