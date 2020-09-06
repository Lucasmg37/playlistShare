import React from 'react';
import Moment from 'react-moment';

export default function HeaderPlaylist(props) {


    return (
        <div>
            <div className='capa-playlist'>
                <img src={props.playlist.st_capa}/>
            </div>

            <div className='capa-playlist-sobre'/>

            <div className='playlist-info'>
                <h1>{props.playlist.st_nome}</h1>
                <p>Criada em <Moment format="DD/MM/YYYY">{props.playlist.dt_create}</Moment></p>
                <p className='info-by'>By {props.playlist.st_nomeusuario}</p>

                <div className='button-actions'>


                    {props.buttons.map(button => (
                        <div>
                            {button.show && (
                                <button onClick={button.action}>
                                    <i className={'fa ' + button.icon}/> {button.text}
                                </button>
                            )}
                        </div>
                    ))}

                </div>
            </div>
        </div>

    )

}

HeaderPlaylist.defaultProps = {
    playlist: {
        st_capa: '',
        st_nome: ''
    }

}