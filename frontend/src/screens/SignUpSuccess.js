import React, { useState, useEffect } from 'react';

import Api from '../services/api';
import Loading from '../components/Loading';

export default function SignUpSuccess({ history, match }) {
  const [erro, setErro] = useState('');
  const [success, setSuccess] = useState(false);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    async function validaHash() {
      if (match.params.hash) {
        await Api.post(`Usuario/${match.params.hash}/validaHash`)
          .then(setSuccess(true))
          .catch(response => {
            setSuccess(false);
            setErro(response.response.data.message);
          });
      }

      setLoading(false);
    }

    validaHash();
  }, []);

  return (
    <div className="container-center animate-up-opacity">
      {!loading ? (
        <div>
          {success && match.params.hash && (
            <div>
              <h1>Conta ativada com sucesso! ✔</h1>
              <button
                onClick={() => history.push('/login')}
                type="submit"
                className="button-green"
              >
                Acessar o Group Playlist
              </button>
            </div>
          )}

          {!success && match.params.hash && (
            <div>
              <h1>Ops😥! Algo deu errado!</h1>
              <div
                className={erro !== '' ? 'erro-box erro-box-show' : 'erro-box'}
              >
                {erro}
              </div>
            </div>
          )}

          {!match.params.hash && (
            <div>
              <h1>Email de ativação Enviado! 📬</h1>
              <button
                onClick={() => history.push('/login')}
                type="submit"
                className="button-green"
              >
                Acessar o Group Playlist
              </button>
            </div>
          )}
        </div>
      ) : (
        <Loading />
      )}
    </div>
  );
}
