import React from 'react';

import './styles.scss';
import { NavLink } from 'react-router-dom';
import { FaCog, FaPlusCircle } from 'react-icons/fa';

import { itemsMenu } from './items';

const Menu = () => {
  return (
    <div className="menu-component-container">
      <ul>
        {itemsMenu.map(item => {
          return (
            <NavLink exact to={item.route}>
              <li>{item.icon()}</li>
            </NavLink>
          );
        })}
      </ul>

      <footer>
        <NavLink to="/newPlaylist">
          <li>
            <FaPlusCircle />
          </li>
        </NavLink>

        <NavLink to="/settings">
          <li>
            <FaCog />
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
  );
};

export default Menu;
