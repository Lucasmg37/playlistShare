import React, { useCallback, useRef, useState } from 'react';
import { FiArrowRight, FiX } from 'react-icons/fi';

import Modal from '..';
import ListMedia from '../../ListMedia';

import ApiService from '../../../services/ApiService';
import { playTrack } from '../../../services/PlayerSpotify';

import './styles.scss';

function SearchTrack({ onRequestClose, isOpen, onAddNewTrack, idPlaylist }) {
  const timeSearchMusic = useRef(null);
  const inputRef = useRef(null);

  const [tracks, setTracks] = useState([]);

  const getTracksByName = useCallback(async source => {
    if (timeSearchMusic) {
      clearTimeout(timeSearchMusic.current);
    }
    timeSearchMusic.current = setTimeout(async () => {
      const response = await ApiService.get(`/Music/${source}`);
      setTracks(response.data);
    }, 1000);
  }, []);

  async function addNewTrack(idTrackSpotify) {
    ApiService.post('/Playlist/newMusic/', {
      id_spotify: idTrackSpotify,
      id_playlist: idPlaylist,
    });

    setTracks(tracks.filter(track => track.id_spotify !== idTrackSpotify));

    if (onAddNewTrack) {
      onAddNewTrack();
    }
  }

  return (
    <Modal onRequestClose={onRequestClose} isOpen={isOpen}>
      <div className="SearchTrackContainer">
        <header>
          <div className="input">
            <button
              onClick={() => {
                inputRef.current.value = '';
              }}
              type="button"
            >
              <FiX />
            </button>
            <input
              type="text"
              placeholder="Escreva o nome de uma música"
              onKeyUp={e => getTracksByName(e.target.value)}
              ref={inputRef}
            />

            <button type="button">
              <FiArrowRight />
            </button>
          </div>
        </header>
        <div>
          {tracks.map(track => {
            return <ListMedia onPlus={() => addNewTrack(track.id_spotify)} music={track} onPlay={playTrack} />;
          })}
        </div>
      </div>
    </Modal>
  );
}

export default SearchTrack;
