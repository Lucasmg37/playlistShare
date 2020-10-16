import React, { createContext, useState, useEffect, useCallback, useContext } from 'react';

import LoginService from '../services/LoginService';

const AuthContext = createContext({ signed: false, user: {} });

export const AuthProvider = ({ children }) => {
  const [user, setUser] = useState({});
  const [signed, setSigned] = useState(false);

  useEffect(() => {
    async function loadStorageData() {
      const storageUser = localStorage.getItem('@GROUPLISTAuth:user');
      const storageToken = localStorage.getItem('@GROUPLISTAuth:token');

      if (storageUser && storageToken) {
        setUser(JSON.parse(storageUser));
        setSigned(true);
      }
    }

    loadStorageData();
  }, []);

  const signIn = useCallback(async (username, password) => {
    try {
      const { data } = await LoginService.login(username, password);
      localStorage.setItem('@GROUPLISTAuth:token', data.st_token);
      localStorage.setItem('@GROUPLISTAuth:user', JSON.stringify(data));
      setUser(data);
      setSigned(true);
    } catch (err) {
      const { data } = err;
      if (data) {
        setUser(data);
      }
      throw err;
    }
  }, []);

  const signInSpotify = useCallback(async code => {
    try {
      const { data } = await LoginService.loginBySpotify(code);
      localStorage.setItem('@GROUPLISTAuth:token', data.st_token);
      localStorage.setItem('@GROUPLISTAuth:user', JSON.stringify(data));
      setUser(data);
      setSigned(true);
    } catch (err) {
      const { data } = err;
      if (data) {
        setUser(data);
      }
      throw err;
    }
  }, []);

  const signOut = useCallback(() => {
    localStorage.clear();
    setUser({});
    setSigned(false);
  }, []);

  return (
    <AuthContext.Provider
      value={{
        signed,
        user,
        signIn,
        signOut,
        signInSpotify,
      }}
    >
      {children}
    </AuthContext.Provider>
  );
};

export default AuthContext;

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
}
