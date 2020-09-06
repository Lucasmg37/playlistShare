import React from 'react';

import { toQuery } from '../../utils/urlUtils';

import PopupWindow from './PopupWindow';

const SpotifyLogin = ({
  buttonText = 'Sign in with Spotify',
  scope = 'user-read-private',
  children,
  className,
  clientId,
  onRequest = () => {},
  onSuccess = () => {},
  onFailure = () => {},
  redirectUri,
}) => {
  const onRequestAction = () => {
    onRequest();
  };

  const onFailureAction = error => {
    onFailure(error);
  };

  const onSuccessAction = data => {
    if (!data.access_token && !data.code) {
      return onFailureAction(new Error("'access_token' or 'code'  not found"));
    }
    onSuccess(data);
    return true;
  };

  const onBtnClick = () => {
    const search = toQuery({
      client_id: clientId,
      scope,
      redirect_uri: redirectUri,
      response_type: 'code',
    });
    const popup = PopupWindow.open(
      'spotify-authorization',
      `https://accounts.spotify.com/authorize?${search}`,
      { height: 700, width: 600 },
    );

    onRequestAction();
    popup.then(
      data => onSuccessAction(data),
      error => onFailureAction(error),
    );
  };

  const attrs = { onClick: onBtnClick };

  if (className) {
    attrs.className = className;
  }

  return (
    <button type="button" {...attrs}>
      {children || buttonText}
    </button>
  );
};

export default SpotifyLogin;
