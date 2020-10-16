import React, { useEffect, useRef, useState } from 'react';
import { FiChevronLeft, FiChevronRight, FiHeart, FiPause, FiPlay, FiRepeat, FiShuffle } from 'react-icons/fi';
import { usePlayerSpotify } from '../../contexts/playerSpotify';

import './styles.scss';

function SoundBars() {
  const { current, play, pause } = usePlayerSpotify();
  const [position, setPosition] = useState();

  const timeInterval = useRef({});

  console.log(current);

  useEffect(() => {
    if (timeInterval.current) {
      clearInterval(timeInterval.current);
    }

    setPosition(current.position);

    timeInterval.current = setInterval(() => {
      setPosition(state => state + 1000);
    }, 1000);
  }, [current.position]);

  return (
    <div className="SoundBarsContainer">
      <div className="bars">
        {current.paused ? (
          <FiPause />
        ) : (
          <>
            <div />
            <div />
            <div />
          </>
        )}
      </div>
      <div className="widget">
        <img src={current.track_window && current.track_window.current_track.album.images[0].url} alt="Capa do Album" />

        <h1>{current.track_window && current.track_window.current_track.name}</h1>
        <h2>{current.track_window && current.track_window.current_track.artists[0].name}</h2>
        <div className="timeline">
          <div className="percent" style={{ width: `${(position * 100) / current.duration}%` }} />
        </div>
        <div>
          <div className="controls">
            <button type="button">
              <FiChevronLeft />
            </button>
            {current.paused ? (
              <button onClick={play} type="button">
                <FiPlay />
              </button>
            ) : (
              <button onClick={pause} type="button">
                <FiPause />
              </button>
            )}

            <button type="button">
              <FiChevronRight />
            </button>
          </div>

          <button type="button">
            <FiHeart />
          </button>
          <button type="button">
            <FiRepeat />
          </button>
          <button type="button">
            <FiShuffle />
          </button>
        </div>
      </div>
    </div>
  );
}

export default SoundBars;
