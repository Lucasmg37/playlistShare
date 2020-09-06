import React, {useState, useEffect} from 'react';
import './NewMusic.css';
import api from '../services/api';
import Loading from '../components/Loading';
import ListMedia from '../components/ListMedia';

export default function NewMusic(props) {

    const [musics, setMusics] = useState([]);
    const [loaded, setloaded] = useState(true);

    useEffect(() => {

        if (props.usuario.id_usuario) {

            async function getTopMusics() {
                // setloaded(false);
                // const response = await api.get("/Usuario/null/getTopTracks");
                // setMusics(response.data.data);
                // setloaded(true);
            }

            if (!props.usuario.bl_integracao) {
                setloaded(true);
                return;
            }

            getTopMusics();

        }

    }, [props.usuario]);

    async function addMusic(id_music) {
        api.post('/Playlist/newMusic/', {
            'id_spotify': id_music,
            'id_playlist': props.params.id_playlist
        });

        setMusics(musics.filter(music => music.id_spotify !== id_music));
    }

    async function buscaMusica(source) {
        setloaded(false);
        const response = await api.get('/Music/' + source);
        setMusics(response.data.data);
        setloaded(true);
    }

    async function playMusicDirectSpotify(id_spotify) {
        await api.put("/Player/playTrack/" + id_spotify);
    }

    return (
        <div className="container-area">
            <div className="area-busca animate-opacity-down">
                <h1>Adicione músicas</h1>

                <div>
                    <button onClick={() => props.history.push('/playlist/' + props.params.id_playlist)}
                            className='btn-secundary'><i className='fa fa-arrow-left'/> Voltar para playlist
                    </button>
                </div>

                <input
                    type="text"
                    placeholder="Escreva o nome de sua música"
                    onKeyUp={e => buscaMusica(e.target.value)}/>
            </div>
            <div>
                {loaded ? (
                    <div>
                        {musics ? (
                            <div>

                                {musics.map(music => (
                                    <ListMedia key={music.id_spotify} music={music}
                                               hoverCapa={props.usuario.bl_premium}
                                               clickCapa={() => playMusicDirectSpotify(music.id_spotify)}
                                               buttons={[
                                                   {
                                                       text: 'Adicionar',
                                                       show: true,
                                                       action: () => addMusic(music.id_spotify)
                                                   }
                                               ]}/>
                                ))}

                            </div>

                        ) : (
                            <div className="empty-response">Sem resultados!</div>
                        )}
                    </div>
                ) : (
                    <Loading/>
                )}
            </div>
        </div>
    );

}