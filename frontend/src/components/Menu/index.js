import React from 'react';

import './styles.scss';
import { NavLink } from 'react-router-dom';

import { itemsMenu } from './items';

const Menu = () => {
  return (
    <div className="menu-component-container">
      <ul>
        {itemsMenu.map(item => {
          return (
            <NavLink to={item.route}>
              <li>
                {item.icon()} <span>{item.name}</span>
              </li>
            </NavLink>
          );
        })}
      </ul>
    </div>
  );
};

export default Menu;
