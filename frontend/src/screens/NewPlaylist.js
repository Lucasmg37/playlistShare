import React, {useState, useEffect} from "react";
import "./Inputs.css";
import Toggle from "../components/Toggle";

import api from "../services/api";
import Loading from "../components/Loading";

export default function NewPlaylist(props) {

    const [id_playlist, setId_playlist] = useState('');
    const [st_nome, setSt_nome] = useState('');
    const [st_descricao, setSt_descricao] = useState('');
    const [bl_privada, setBl_privada] = useState(0);
    const [bl_publicedit, setBl_publicedit] = useState('');
    const [bl_sincronizar, setBl_sincronizar] = useState('');
    const [st_capa, setSt_capa] = useState("");
    const [loaded, setLoaded] = useState(false);

    useEffect(() => {
        const limpaCampos = () => {
            setId_playlist("");
            setSt_nome("");
            setSt_descricao("");
            setBl_privada("");
            setBl_publicedit("");
            setBl_sincronizar("");
            setSt_capa("");
        };


        limpaCampos();
    }, [props.history.location.pathname]);

    useEffect(() => {

        const id_usuario = localStorage.getItem("id_usuario");

        async function sourcePlaylist() {

            if (props.id_playlist) {

                const response = await api.get("/Playlist/" + props.id_playlist);
                const playlist = response.data.data;

                if (+response.data.data.id_usuario !== +id_usuario) {
                    props.history.push("/");
                    return;
                }

                setId_playlist(playlist.id_playlist);
                setSt_nome(playlist.st_nome);
                setSt_descricao(playlist.st_descricao);
                setBl_privada(playlist.bl_privada);
                setBl_publicedit(playlist.bl_publicedit);
                setBl_sincronizar(playlist.bl_sincronizar);
                setSt_capa(playlist.st_capa);
            }
            setLoaded(true);
        }

        sourcePlaylist();

    }, [props.id_playlist]);


    async function savePlaylist(e) {
        e.preventDefault();

        await api.post("/Playlist", {
            id_playlist,
            st_nome,
            st_descricao,
            bl_privada,
            bl_publicedit,
            bl_sincronizar
        }).then(response => {
            props.history.push("/playlist/" + response.data.data.id_playlist);
        })

    }

    return (

        <div className="container-area">
            {loaded ? (
                <div className='animate-opacity-down'>
                    <div className="area-busca">
                        {props.id_playlist ? (<h1>Mude o que quiser.</h1>) : (<h1>Crie a sua.</h1>)}
                    </div>

                    {props.id_playlist && (
                        <div>
                            <button onClick={() => props.history.push('/playlist/' + props.id_playlist)}
                                    className='btn-secundary'><i className='fa fa-arrow-left'/> Voltar para playlist
                            </button>
                        </div>
                    )}


                    <form>
                        <input
                            onChange={e => setSt_nome(e.target.value)}
                            value={st_nome}
                            className="text-input"
                            placeholder="Nome da playlist"/>

                        <textarea
                            onChange={e => setSt_descricao(e.target.value)}
                            value={st_descricao}
                            className="text-area-input"
                            placeholder="Descreva a sua playlist!"/>


                        <div className="input-toggle">
                            <Toggle
                                name="bl_privada"
                                checked={bl_privada}
                                onChangeFunction={() => setBl_privada(!bl_privada)}
                            />
                            <label for="bl_privada">Playlist privada. (Outros usuários não têm acesso a sua
                                playlist.)</label>
                        </div>

                        <div className="input-toggle">
                            <Toggle
                                name="bl_publicedit"
                                checked={bl_publicedit}
                                onChangeFunction={() => setBl_publicedit(!bl_publicedit)}
                            />
                            <label for="bl_publicedit">Playlist editável. (Outros usuários podem adicionar e remover
                                músicas desta playlist.)</label>
                        </div>

                        {+props.usuario.bl_integracao === 1 && (
                            <div className="input-toggle">
                                <Toggle
                                    name="bl_sincronizar"
                                    checked={bl_sincronizar}
                                    onChangeFunction={() => setBl_sincronizar(!bl_sincronizar)}
                                />
                                <label htmlFor="bl_sincronizar">Sincronizar com o Spotify
                                    <i className="fab fa-spotify"/>.</label>

                            </div>
                        )}

                        <button className="button-primary" onClick={savePlaylist}>Salvar</button>
                    </form>
                </div>
            ): (<Loading/>)}
        </div>
    );

}