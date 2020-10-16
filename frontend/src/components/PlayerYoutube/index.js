import React, { useCallback } from 'react';
import { FiX } from 'react-icons/fi';
import { usePlaylist } from '../../contexts/playlist';

import './styles.scss';

function PlayerYoutube() {
  const { playerYoutube, setPlayerYoutube } = usePlaylist();

  const closePlayer = useCallback(() => {
    setPlayerYoutube({});
  }, [setPlayerYoutube]);

  if (!playerYoutube || !playerYoutube.id) {
    return null;
  }

  return (
    <div className="PlayerYoutubeContainer">
      <div>
        <button onClick={closePlayer} type="button">
          <FiX />
        </button>
      </div>

      <iframe
        src={`https://www.youtube.com/embed/${playerYoutube.id}`}
        frameBorder="0"
        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
        allowFullScreen
        title="Youtube Player"
      />
    </div>
  );
}

export default PlayerYoutube;
