import React, { useState } from 'react';
import { Link } from 'react-router-dom';

// Componentes
import SuccesComponent from '../../components/Login/SuccessComponent';
import ValidateAcountComponent from '../../components/Login/ValidateAcountComponent';
import AlertLoginComponent from '../../components/Login/AlertLoginComponent';
// Services
import SignUpService from '../../services/SignUpService';
import UsuarioService from '../../services/UsuarioService';

export default function Cadastro({ history }) {
  const [email, setEmail] = useState('');
  const [nome, setNome] = useState('');
  const [senha, setSenha] = useState('');
  const [repeatSenha, setRepeatSenha] = useState('');

  const [id_usuario, setIdUsuario] = useState('');

  const [logging, setLogging] = useState(false);
  const [signUpStatus, setSignUpStatus] = useState(false);
  const [finish, setFinish] = useState(false);
  const [alert, setAlert] = useState('');

  function activateAccount(code) {
    setLogging(true);
    UsuarioService.activate(code, id_usuario)
      .then(() => {
        setFinish(true);
        setAlert('');
      })
      .catch(erro => {
        setAlert(erro.message);
      })
      .finally(() => {
        setLogging(false);
      });
  }

  function signUp(e) {
    e.preventDefault();

    if (senha !== repeatSenha) {
      setAlert('As senhas se diferem entre si.');
      return false;
    }

    setLogging(true);

    SignUpService.signUp(email, senha, nome)
      .then(response => {
        setIdUsuario(response.data.id_usuario);
        setSignUpStatus(true);
        setAlert('');
      })
      .catch(erro => {
        setAlert(erro.message);
      })
      .finally(() => {
        setLogging(false);
      });
    return true;
  }

  return (
    <div className="container-center animate-up-opacity">
      {signUpStatus && !finish && (
        <ValidateAcountComponent
          logging={logging}
          buttonAction={code => activateAccount(code)}
        />
      )}

      {finish && (
        <SuccesComponent
          titulo="Cadastro ativado com sucesso!"
          buttonText="Acessar"
          buttonAction={() => history.push('/login')}
        />
      )}

      {!signUpStatus && !finish && (
        <div className="w100">
          <form onSubmit={signUp}>
            <h1>Crie a sua conta!</h1>

            <input
              className="input-primary"
              type="email"
              required
              placeholder="Seu e-mail"
              value={email}
              onChange={e => setEmail(e.target.value)}
            />

            <input
              className="input-primary"
              type="text"
              required
              placeholder="Seu nome"
              value={nome}
              onChange={e => setNome(e.target.value)}
            />

            <input
              className="input-primary"
              type="password"
              required
              placeholder="Uma senha"
              value={senha}
              onChange={e => setSenha(e.target.value)}
            />

            <input
              className="input-primary"
              type="password"
              required
              placeholder="Repita a senha"
              value={repeatSenha}
              onChange={e => setRepeatSenha(e.target.value)}
            />

            <button type="submit" className="button-primary">
              {!logging ? (
                <span>Cadastrar</span>
              ) : (
                <i className="fa fa-spinner loading-spinner fa-2x" />
              )}
            </button>
          </form>
        </div>
      )}

      <div className="w100">
        <AlertLoginComponent text={alert} />
      </div>

      <div className="group-button-login mt-20">
        <Link to="/login" className="link-button">
          Já tenho uma conta.
        </Link>
      </div>
    </div>
  );
}
