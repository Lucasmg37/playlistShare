import React from 'react';

export default function ButtonAction({ show, action, text }) {
  return (
    <span>
      {show && (
        <button type="button" onClick={action}>
          {text}
        </button>
      )}
    </span>
  );
}
