import React from 'react';
import { Switch, Route } from 'react-router-dom';

import Home from '../screens/Home';

const PrivateRoutes = () => {
  return (
    <Switch>
      <Route path="/" exact component={Home} />
      {/* 
      <Route
        path="/new/"
        exact
        render={({ match, history }) => (
          <NewPlaylist
            id_playlist={match.params.id_playlist}
            usuario={usuario}
            history={history}
          />
        )}
      />

      <Route
        path="/edit/:id_playlist"
        exact
        render={({ match, history }) => (
          <NewPlaylist
            id_playlist={match.params.id_playlist}
            usuario={usuario}
            history={history}
          />
        )}
      />

      <Route path="/playlist/" exact component={PlaylistSource} />

      <Route
        path="/playlist/:id_playlist"
        exact
        render={({ match, history }) => (
          <Playlist
            history={history}
            id_playlist={match.params.id_playlist}
            usuario={usuario}
          />
        )}
      />

      <Route
        path="/playlist/:id_playlist/new"
        exact
        render={({ match, history }) => (
          <NewMusic history={history} params={match.params} usuario={usuario} />
        )}
      />

      <Route
        path="/perfil/"
        exact
        render={() => <Perfil usuario={usuario} setUsuario={getUser} />}
      />
      <Route path="/spotify" exact component={Spotify} />
      <Route path="/library/" exact component={Library} /> */}
    </Switch>
  );
};

export default PrivateRoutes;
