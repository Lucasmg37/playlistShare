import React, { useEffect, useState } from "react";

import "./PopUp.css";

export default function PopUp(props) {

    function close() {
        props.closePopUp();
    }

    function actionButton(action){
        action();
    }

    return (
        <div>
            {props.opened && (
                <div className="PopUp animate-opacity ">
                    <div className="fundo-pop-up"></div>
                    <div className="conteudo-pop" onClick={close}>
                        <div className="box-options animate-scale">

                        {props.buttons.map(button => (
                            <button onClick={() => actionButton(button.action)} className="btn-primary">{button.name}</button>
                        ))}

                        </div>
                    </div>
                </div>
            )}
        </div>
    );

}