import React from 'react';
import { BrowserRouter, Route, Switch } from 'react-router-dom';

import './App.css';
import Login from './screens/Login/Login';
import Cadastro from './screens/SignUp/Cadastro';
import SignUpSuccess from './screens/SignUpSuccess';
import Recovery from './screens/Recovery/Recovery';
import Painel from './screens/Painel';
import AppProvider from './hooks';

export default function App() {
  return (
    <AppProvider>
      <BrowserRouter>
        <Switch>
          <Route path="/login" exact component={Login} />
          <Route path="/signup" exact component={Cadastro} />
          <Route path="/User/:hash/activate" exact component={SignUpSuccess} />
          <Route path="/signup/success" exact component={SignUpSuccess} />
          <Route path="/recovery" exact component={Recovery} />
          <Route path="/" component={Painel} />
        </Switch>
      </BrowserRouter>
    </AppProvider>
  );
}
