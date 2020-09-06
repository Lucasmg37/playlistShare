import React from 'react';
import './Toggle.css';

export default function Toggle({ checked, name, onChangeFunction }) {
  function changeFunction() {
    onChangeFunction();
  }

  return (
    <div>
      <label htmlFor className="switch">
        <input
          id={name}
          type="checkbox"
          onChange={changeFunction}
          checked={checked}
        />
        <span className="slider round" />
      </label>
    </div>
  );
}
