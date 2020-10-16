import React from 'react';

import '../../css/Main.scss';

import './styles.scss';
import Menu from '../../components/Menu';
import PrivateRoutes from '../../routes/private';
import PlayerYoutube from '../../components/PlayerYoutube';
import PlayerSpotify from '../../components/PlayerSpotify';

const Painel = () => {
  // async function getCurrentPlayer() {
  //   await api.get('/Player/getCurrent').then(response => {
  //     setCurrentPlayerArtists(response.data.data.artists);
  //     setCurrentPlayerName(response.data.data.name);
  //     setDuration_ms(response.data.data.progress_ms);
  //     setProgress_ms(response.data.data.duration_ms);
  //     setIsCurrentPlayer(response.data.data.is_playing);
  //     setProgressForCent(
  //       (+response.data.data.progress_ms * 100) /
  //         response.data.data.duration_ms,
  //     );
  //   });
  // }

  return (
    <div className="PainelContainer">
      <PlayerYoutube />
      <PlayerSpotify />
      <Menu />
      <div className="wrapper">
        <PrivateRoutes />
      </div>
    </div>
  );
};

export default Painel;
