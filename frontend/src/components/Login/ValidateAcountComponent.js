import React, {useState} from "react";

export default function ValidateAcountComponent(props) {

    const [code, setCode] = useState("");

    return (
        <div className="w100">
            <h1>Informe o código de verificação</h1>

            <p className="p-login">{props.info}</p>

            <input
                type="text"
                className="input-primary"
                required
                placeholder="Código de verificação"
                value={code}
                onChange={e => setCode(e.target.value)}
            />

            <button
                onClick={() => props.buttonAction(code)}
                className="button-primary"
            >
                {!props.logging ? (<span>Confirmar</span>) : (
                    <i className="fa fa-spinner loading-spinner fa-2x"/>)}
            </button>
        </div>
    );

}