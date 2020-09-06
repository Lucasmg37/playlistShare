import React, {useState, useEffect} from "react";
import Moment from "react-moment";
import api from "../services/api";
import Loading from "../components/Loading";

export default function PlaylistSource({history}) {

    const [playlists, setPlaylists] = useState({});
    const [loaded, setLoaded] = useState(false);

    function openPlaylist(id_playlist) {
        history.push("/playlist/" + id_playlist);
    }

    useEffect(() => {
        async function buscaTopPlaylists() {
            const response = await api.get("Playlist/getTopPlaylists");
            setPlaylists(response.data.data);
            setLoaded(true);
        }

        buscaTopPlaylists();

    }, []);

    async function buscaPlaylist(search) {
        if (search) {
            const response = await api.get("Playlist/getByName?search=" + search);
            setPlaylists(response.data.data);
        }
    }

    return (
        <div className="container-area">
            <div>
                <div className="area-busca">
                    <h1>Descubra novas playlists</h1>
                    <input
                        placeholder="Digite aqui um nome de playlist..."
                        type="text"
                        onKeyUp={e => buscaPlaylist(e.target.value)}/>
                </div>
            </div>

            {loaded ? (
                <div className="list-playlist">
                    {playlists !== undefined && playlists.length > 0 ? (
                        <ul>
                            {playlists.map(playlist => (
                                <li onClick={() => openPlaylist(playlist.id_playlist)} key={playlist.id_playlist}>
                                    <img src={playlist.st_capa}/>
                                    <div className="info-music">
                                        <strong>{playlist.st_nome}</strong>
                                        <p>Data de criação: <Moment
                                            format="DD/MM/YYYY">{playlist.dt_create}</Moment><br/>
                                            Criada por: {playlist.st_nomeusuario} <br/>
                                            Número de músicas: {playlist.nu_music} <br/>
                                            {playlist.bl_sincronizado === 1 && (
                                                <span>Disponível no Spotify <i className="fab fa-spotify"/></span>)}

                                        </p>
                                    </div>
                                </li>
                            ))}
                        </ul>
                    ) : (<div className="empty-response">Nenhuma playlist encontrada!</div>)}

                </div>
            ) : (
                <Loading/>
            )}
        </div>
    );

}