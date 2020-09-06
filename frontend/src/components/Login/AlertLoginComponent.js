import React from "react";

export default function AlertLoginComponent(props) {
    return (

        <div className="w100">
            {
                props.text && (
                    <div className="alert-login">
                        <p>{props.text}</p>
                    </div>
                )
            }
        </div>

    )
}