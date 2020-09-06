import React, { useState, useEffect } from 'react';
import { Link } from 'react-router-dom';

import LoginService from '../../services/LoginService';
import UsuarioService from '../../services/UsuarioService';
import SpotifyLogin from '../../components/SpotifyLogin';
import ValidateAcountComponent from '../../components/Login/ValidateAcountComponent';
import AlertLoginComponent from '../../components/Login/AlertLoginComponent';
import logo from '../../assets/img/logo.png';

import './Login.scss';
import { useAuth } from '../../hooks/auth';

export default function Login() {
  const { signIn, user } = useAuth();

  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');

  const [logging, setLogging] = useState(false);
  const [alert, setAlert] = useState('');

  function loginBySpotifySuccess(code) {
    LoginService.loginBySpotify(code)
      .then(response => {
        localStorage.setItem('st_token', response.data.st_token);
        window.location.href = 'http://localhost:3000';
      })
      .catch(error => {
        setAlert(error.message);
      });
  }

  function activateAccount(code) {
    setLogging(true);
    UsuarioService.activate(code, user.id_usuario)
      .then(() => {
        // setCountNotActivate(false);
        setAlert('');
      })
      .catch(erro => {
        setAlert(erro.message);
      })
      .finally(() => {
        setLogging(false);
      });
  }

  function sendActivateEmail() {
    UsuarioService.resendEmailActivate(user.id_usuario)
      .then(() => {
        setAlert('Email reenviado!');
      })
      .catch(error => {
        setAlert(error.message);
      });
  }

  // Verifica a sessão e realiza o login
  useEffect(() => {
    const st_token = localStorage.getItem('st_token');
    if (st_token) {
      window.location.href = 'http://localhost:3000';
    }
  }, []);

  // lucas@lucasjunior.com.br

  const login = async e => {
    e.preventDefault();
    setLogging(true);
    try {
      await signIn(username, password);
    } catch (err) {
      setAlert(err.message);
      if (err.data) {
        setAlert('');
      }
    } finally {
      setLogging(false);
    }
  };

  return (
    <div className="container-center animate-up-opacity">
      {user.bl_ativo || !user.id_usuario ? (
        <div>
          <form className="w100" onSubmit={login}>
            <img className="w100" src={logo} alt="Group List" />

            <input
              className="input-primary"
              type="text"
              placeholder="E-mail"
              required
              onChange={e => setUsername(e.target.value)}
            />

            <input
              className="input-primary"
              type="password"
              placeholder="Senha"
              required
              onChange={e => setPassword(e.target.value)}
            />

            <button className="button-primary" type="submit">
              {!logging ? (
                <span>Entrar</span>
              ) : (
                <i className="fa fa-spinner loading-spinner fa-2x" />
              )}
            </button>
          </form>

          <div className="btn-connect-spotify">
            <SpotifyLogin
              clientId={process.env.REACT_APP_SPOTIFY_CLIENT_ID}
              redirectUri={process.env.REACT_APP_SPOTIFY_REDIRECT_URL}
              onSuccess={loginBySpotifySuccess}
              onFailure={() => {}}
              scope={process.env.REACT_APP_SPOTIFY_SCOPES}
            >
              <span>
                Entrar com o Spotify <i className="fab fa-spotify" />
              </span>
            </SpotifyLogin>
          </div>

          <AlertLoginComponent text={alert} />

          <div className="group-button-login mt-20">
            <Link to="/signup" className="link-button">
              Não tenho uma conta.
            </Link>
            <Link to="/recovery" className="link-button">
              Esqueci a minha senha.
            </Link>
          </div>
        </div>
      ) : (
        <div>
          <ValidateAcountComponent
            logging={logging}
            info="Enviamos um email de ativação para o seu email."
            buttonAction={code => activateAccount(code)}
          />

          <button
            type="button"
            onClick={() => sendActivateEmail()}
            className="button-secundary"
          >
            Receber novo código
          </button>

          <AlertLoginComponent text={alert} />

          <div className="group-button-login mt-20">
            <button
              type="button"
              onClick={() => {
                // setCountNotActivate(false);
                setAlert('');
              }}
              className=" link-button"
            >
              Cancelar
            </button>
          </div>
        </div>
      )}
    </div>
  );
}
