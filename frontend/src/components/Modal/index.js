import React from 'react';
import { motion, AnimatePresence } from 'framer-motion';

import './styles.scss';
import { FiX } from 'react-icons/fi';

function Modal({ children, onRequestClose, isOpen }) {
  return (
    <AnimatePresence>
      {isOpen && (
        <div isOpen={isOpen} className="ModalContainer" overlayClassName="ModalContainerOverlay">
          <motion.div
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            onClick={onRequestClose}
            className="Overlay"
          />
          <motion.div
            initial={{ opacity: 0, transform: 'scale(0.1)' }}
            animate={{ opacity: 1, transform: 'scale(1)' }}
            exit={{ opacity: 0, transform: 'scale(0.1)' }}
            className="Content"
          >
            {children}
            <button onClick={onRequestClose} className="buttonCloseModal" type="button">
              <FiX />
            </button>
          </motion.div>
        </div>
      )}
    </AnimatePresence>
  );
}

export default Modal;
