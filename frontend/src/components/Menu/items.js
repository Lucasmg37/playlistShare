import { MdHome, MdSearch, MdSettings, MdPlaylistPlay } from 'react-icons/md';
import { FaSpotify } from 'react-icons/fa';

export const itemsMenu = [
  {
    route: '/',
    name: 'Home',
    icon: MdHome,
  },
  {
    route: '/playlist',
    name: 'Pesquisar',
    icon: MdSearch,
  },
  {
    route: '/library',
    name: 'Biblioteca',
    icon: MdPlaylistPlay,
  },
  {
    route: '/spotify',
    name: 'Spotify',
    icon: FaSpotify,
    needSpotify: true,
  },
  {
    route: '/perfil',
    name: 'Perfil',
    icon: MdSettings,
  },
];
