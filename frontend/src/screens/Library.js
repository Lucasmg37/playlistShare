import React, { useState, useEffect } from "react";
import Moment from "react-moment";
import api from "../services/api";
import Loading from "../components/Loading";
import PopUp from "./PopUp";

export default function Library({ history }) {

    const [playlists, setPlaylists] = useState([]);
    const [loaded, setLoaded] = useState(false);
    const [opened, setOpened] = useState(false);
    const [playlistactiveid, setPlaylistactiveid] = useState(0);
    const [buttons, setButtons] = useState([]);

    const desativaPlaylist = async (id_playlist) => {
        await api.delete("/Playlist/" + id_playlist).then(response => {
            setPlaylists(playlists.filter(playlist => playlist.id_playlist !== id_playlist));
        })
    }

    const actionButtonEdit = (id) => {
        history.push("/edit/" + id);
    }

    const actionButtonList = (id) => {
        history.push("/playlist/" + id);
    }

    const actionButtonNewMusic = (id) => {
        history.push("/playlist/" + id + "/new");
    }

    useEffect(() => {
        function updateAction() {
            buttons.forEach(button => {

                switch (button.id) {
                    case 1: button.action = () => actionButtonEdit(playlistactiveid); break;
                    case 2: button.action = () => actionButtonEdit(playlistactiveid); break;
                    case 3: button.action = () => desativaPlaylist(playlistactiveid); break;
                    case 4: button.action = () => actionButtonList(playlistactiveid); break;
                    case 5: button.action = () => actionButtonNewMusic(playlistactiveid); break;
                }
            });
        }

        updateAction();

    }, [playlistactiveid])

    async function openPopUp(id) {
        await setPlaylistactiveid(id);
        setOpened(!opened);
    }

    useEffect(() => {

        const dataButtons =
            [
                {
                    name: "Editar",
                    action: ""
                },
                {
                    name: "Compartilhar",
                    action: ""
                },
                {
                    name: "Excluir",
                    action: ""
                },
                {
                    name: "Listar Músicas",
                    action: ""
                },
                {
                    name: "Nova música",
                    action: ""
                }
            ]

        function criaIdButtons() {
            var indice = 1;
            dataButtons.forEach(data => {
                data.id = indice;
                indice++;
            });
        }

        criaIdButtons();
        setButtons(dataButtons);

        async function loadPlaylist() {
            const response = await api.get("/Playlist/myPlaylists");
            setPlaylists(response.data.data);
            setLoaded(true);
        }

        loadPlaylist();

    }, [])



    return (
        <div className="container-area">
            <PopUp opened={opened}
                closePopUp={() => setOpened(!opened)}
                buttons={buttons}></PopUp>

            <div className="area-busca">
                <h1>Suas Playlists</h1>
            </div>

            {loaded ? (
                <div className="list-playlist">
                    {playlists !== undefined && playlists.length > 0 ? (
                        <ul>
                            {playlists.map(playlist => (
                                <li key={playlist.id_playlist}>
                                    <img src={playlist.st_capa} />
                                    <div className="info-music">
                                        <strong>{playlist.st_nome}</strong>
                                        <p>Data de criação: <Moment format="DD/MM/YYYY">{playlist.dt_create}</Moment><br />
                                            Número de músicas: {playlist.nu_music} <br />
                                            {playlist.bl_sincronizado ? (<i className="fab fa-spotify"></i>) : (<i></i>)}
                                            {playlist.bl_privada ? (<i className="fa fa-lock"></i>) : (<i className="fa fa-unlock "></i>)}

                                        </p>
                                    </div>

                                    <div className="info-icons">
                                        <i onClick={() => openPopUp(playlist.id_playlist)} className="fa fa-ellipsis-v fa-2x"></i>
                                    </div>

                                </li>
                            ))}
                        </ul>
                    ) : (<div className="empty-response">Você não tem playlists!</div>)}

                </div>

            ) : (
                    <Loading></Loading>
                )}


        </div>
    );

}