import React, { useEffect, useState, useRef, useCallback } from 'react';
import { MdChevronLeft, MdChevronRight } from 'react-icons/md';
import Hammer from 'hammerjs';

import api from '../../services/api';
import Loading from '../../components/Loading';
import { usePlaylist } from '../../hooks/playlist';
import './styles.scss';

export default function Home({ history }) {
  const [playlists, setPlaylists] = useState([]);
  const [loaded, setLoaded] = useState(false);
  const [pageCarrosel, setPageCarrosel] = useState(0);

  const { setPlaylistActive } = usePlaylist();
  const carrossel = useRef(null);
  const hammertime = useRef(null);
  const ulRef = useRef(null);
  const timeOutSelectPlaylist = useRef(null);

  const scrollCarrossel = useCallback(
    isNext => {
      if (!ulRef.current) {
        return;
      }

      let page = pageCarrosel - 1;

      if (isNext) {
        page = pageCarrosel + 1;
      }

      // Se a posição do scroll for a mesma do tamanho Não faça nada
      if (
        isNext &&
        ulRef.current &&
        ulRef.current.scrollLeft + carrossel.current.offsetWidth >= ulRef.current.scrollWidth
      ) {
        return;
      }

      let margin = carrossel.current && page ? carrossel.current.offsetWidth * page - 300 : 0;

      if (pageCarrosel < 0) {
        setPageCarrosel(0);
        margin = 0;
      } else if (ulRef.current && margin > ulRef.current.scrollWidth) {
        margin = ulRef.current.scrollWidth;
        setPageCarrosel(page);
      } else {
        setPageCarrosel(page);
      }

      ulRef.current.scroll({ left: margin, behavior: 'smooth' });
    },
    [pageCarrosel],
  );

  useEffect(() => {
    async function initHammer() {
      if (carrossel.current) {
        if (!hammertime.current) {
          hammertime.current = new Hammer(carrossel.current);
        }

        // Remove the last envent
        await hammertime.current.off('swipeleft');
        await hammertime.current.off('swiperight');

        // Add new event with atual state
        hammertime.current.on('swipeleft', () => scrollCarrossel(true));
        hammertime.current.on('swiperight', () => scrollCarrossel(false));
      }
    }

    initHammer();
  }, [carrossel.current, pageCarrosel, scrollCarrossel]);

  useEffect(() => {
    async function loadTopPlaylists() {
      const response = await api.get('/Playlist');
      response.data.data = response.data.data.map((item, index) => {
        item.selected = false;
        if (!index) {
          item.selected = true;
        }
        return item;
      });

      response.data.data[0].title = 'Top Global';
      response.data.data[0].subtitle = 'As mais visitadas bo GroupList';

      setPlaylistActive(response.data.data[0]);
      setPlaylists(response.data.data);
      setLoaded(true);
    }

    loadTopPlaylists();
  }, [setPlaylistActive]);

  function openPlaylist(id_playlist) {
    history.push(`/playlist/${id_playlist}`);
  }

  const handleSelectPlaylist = (id_playlist, section) => {
    const selected = playlists.filter(item => {
      return +item.id_playlist === +id_playlist;
    })[0];

    if (section === 'top') {
      selected.title = 'Top Global';
      selected.subtitle = 'As mais visitadas bo GroupList';
    }

    if (timeOutSelectPlaylist.current) {
      clearTimeout(timeOutSelectPlaylist.current);
    }

    timeOutSelectPlaylist.current = setTimeout(() => {
      setPlaylistActive(selected);
    }, 500);

    setPlaylists(
      playlists.map(item => {
        if (+item.id_playlist === +id_playlist) {
          item.selected = true;
        } else {
          item.selected = false;
        }
        return item;
      }),
    );
  };

  return (
    <div className="container-screen-home">
      {loaded ? (
        <div ref={carrossel} id="myElement">
          <div className="top-carrossel">
            <h1>Top Global</h1>
            <div className="icons">
              <MdChevronLeft onClick={() => scrollCarrossel(false)} />
              <MdChevronRight onClick={() => scrollCarrossel(true)} />
            </div>
          </div>
          {playlists !== undefined && playlists.length > 0 && (
            <ul ref={ulRef}>
              {playlists.map(playlist => (
                <li
                  className={playlist.selected ? 'selected' : ''}
                  key={playlist.id_playlist}
                  onClick={() => openPlaylist(playlist.id_playlist)}
                  onMouseEnter={() => handleSelectPlaylist(playlist.id_playlist, 'top')}
                >
                  <div className="capa">
                    <img draggable="false" src={playlist.st_capa} alt="Capa da Playlist" />
                  </div>
                </li>
              ))}
            </ul>
          )}
        </div>
      ) : (
        <Loading />
      )}
    </div>
  );
}
