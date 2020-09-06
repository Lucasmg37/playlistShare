import React from 'react';
import api from '../services/api'
import './ListMedia.scss'
import ButtonAction from './ButtonAction';

export default function ListMedia(props) {

    return (

        <div className="list-media">
            <div onClick={props.hoverCapa && props.clickCapa} className="img-playlist">

                {props.hoverCapa && (
                    <div className="hover-img">
                        <i className="fa fa-play"/>
                    </div>
                )}

                <img src={props.music.st_urlimagem} />
            </div>

            <div className="info-music">
                <strong>{props.music.st_nome}</strong>
                <p>Artista: {props.music.st_artista}</p>
                <p>Album: {props.music.st_album}</p>
            </div>

            <div className="actions-buttons">

                {
                    props.buttons.map(button => (
                        <ButtonAction text={button.text} show={button.show} action={button.action} />
                    ))
                }

            </div>
        </div>
    );

}