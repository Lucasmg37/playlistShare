import ApiService from './ApiService';

const playTrack = async idSpotify => {
  ApiService.put(`/Player/playTrack/${idSpotify}`);
};

const playPlaylist = async idSpotify => {
  ApiService.put(`/Player/playPlaylist/${idSpotify}`);
};

export { playTrack, playPlaylist };
