import React from 'react';

export default function SuccesComponent({ titulo, buttonAction, buttonText }) {
  return (
    <div>
      <h1>{titulo}</h1>
      <button type="button" onClick={buttonAction} className="button-primary">
        {buttonText}
      </button>
    </div>
  );
}
