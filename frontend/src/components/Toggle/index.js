import React from 'react';
import './Toggle.scss';

export default function Toggle({ children, checked = false, onChange }) {
  return (
    <div className="toggleContainer">
      {/* eslint-disable-next-line jsx-a11y/control-has-associated-label */}
      <button type="button" className={!!checked && 'active'} onClick={onChange} />
      <span>{children}</span>
    </div>
  );
}
