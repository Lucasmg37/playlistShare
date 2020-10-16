import React from 'react';
import { Switch, Route } from 'react-router-dom';

import Home from '../screens/Home';
import Playlist from '../screens/Playlist';
import Perfil from '../screens/Perfil';
import Spotify from '../screens/Spotify';
import Library from '../screens/Library';

const PrivateRoutes = () => {
  return (
    <Switch>
      <Route path="/" exact component={Home} />
      <Route path="/playlist/:id_playlist" exact component={Playlist} />
      <Route path="/perfil/" exact component={Perfil} />
      <Route path="/spotify" exact component={Spotify} />
      <Route path="/library/" exact component={Library} />
    </Switch>
  );
};

export default PrivateRoutes;
