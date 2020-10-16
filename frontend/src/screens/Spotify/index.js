import React, { useState } from 'react';
import { FiHeart, FiUser } from 'react-icons/fi';
import { motion } from 'framer-motion';

import Loading from '../../components/Loading';
import Card from '../../components/Card';

import { useFetch } from '../../hooks/useFetch';
import useToastify from '../../hooks/toastify';

import ApiService from '../../services/ApiService';

import './styles.scss';

import SpotifyPlaylistInfo from '../../components/Modal/SpotifyPlaylistInfo';

const ulAnimation = {
  hidden: { opacity: 1 },
  visible: {
    opacity: 1,
    transition: {
      when: 'beforeChildren',
      staggerChildren: 0.1,
    },
  },
};

export default function Spotify() {
  const [currentPlaylist, setCurrentPlaylist] = useState({});
  const { success } = useToastify();

  const { data, error, mutate } = useFetch('/Playlist/getSpotify');

  if (!data && !error) {
    return (
      <div className="SpotifyContainer">
        <Loading />
      </div>
    );
  }

  if (error) {
    return null;
  }

  const { liked_playlists, user_playlists } = data.data;

  async function getPlaylistFromSpotifyModal() {
    setCurrentPlaylist({
      ...currentPlaylist,
      bl_sincronizing: true,
    });

    await ApiService.post(`/Playlist/getPlaylistSpotify/${currentPlaylist.id}`);
    success('Playlist sincronizada!');
    setCurrentPlaylist({
      ...currentPlaylist,
      bl_sincronizing: false,
      bl_sincronizado: true,
    });
  }

  async function getPlaylistFromSpotify(id) {
    mutate(
      {
        ...data,
        liked_playlists: liked_playlists.map(playlist => {
          if (playlist.id === id) {
            playlist.bl_sincronizing = true;
          }
          return playlist;
        }, id),
        user_playlists: user_playlists.map(playlist => {
          if (playlist.id === id) {
            playlist.bl_sincronizing = true;
          }
          return playlist;
        }, id),
      },
      false,
    );

    await ApiService.post(`/Playlist/getPlaylistSpotify/${id}`);
    success('Playlist sincronizada!');

    mutate({
      ...data,
      liked_playlists: liked_playlists.map(playlist => {
        if (playlist.id === id) {
          playlist.bl_sincronizing = false;
          playlist.bl_sincronizado = true;
        }
        return playlist;
      }),
      user_playlists: user_playlists.map(playlist => {
        if (playlist.id === id) {
          playlist.bl_sincronizing = false;
          playlist.bl_sincronizado = true;
        }
        return playlist;
      }),
    });
  }

  return (
    <>
      <SpotifyPlaylistInfo
        playlist={currentPlaylist}
        onRequestClose={() => setCurrentPlaylist({})}
        isOpen={currentPlaylist.id}
        migratePlaylist={getPlaylistFromSpotifyModal}
      />

      <div className="SpotifyContainer">
        <div>
          <h2>
            Suas Playlists <FiUser />
          </h2>
          <motion.ul variants={ulAnimation} initial="hidden" animate="visible">
            {user_playlists.map(playlist => (
              <Card
                cardSync
                key={playlist.id}
                playlist={{ ...playlist, st_nome: playlist.name, st_capa: playlist.image }}
                onSyncClick={() => getPlaylistFromSpotify(playlist.id)}
                isSync={playlist.bl_sincronizado}
                isSynchronizing={playlist.bl_sincronizing}
                onEyeClick={() => setCurrentPlaylist(playlist)}
              />
            ))}
          </motion.ul>

          <h2>
            Playlists Curtidas <FiHeart />
          </h2>
          <motion.ul variants={ulAnimation} initial="hidden" animate="visible">
            {liked_playlists.map(playlist => (
              <Card
                cardSync
                isSync={playlist.bl_sincronizado}
                key={playlist.id}
                playlist={{ ...playlist, st_nome: playlist.name, st_capa: playlist.image }}
                onSyncClick={() => getPlaylistFromSpotify(playlist.id)}
                isSynchronizing={playlist.bl_sincronizing}
                onEyeClick={() => setCurrentPlaylist(playlist)}
              />
            ))}
          </motion.ul>
        </div>
      </div>
    </>
  );
}
