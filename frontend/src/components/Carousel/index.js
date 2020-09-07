import React, { useState, useRef, useCallback, useEffect } from 'react';
import { motion } from 'framer-motion';
import Hammer from 'hammerjs';
import { MdChevronLeft, MdChevronRight, FiArrowRight } from 'react-icons/all';

import './styles.scss';

const container = {
  hidden: { opacity: 1 },
  visible: {
    opacity: 1,
    transition: {
      when: 'beforeChildren',
      staggerChildren: 0.1,
    },
  },
};

function Carousel({ children, title }) {
  const [pageCarrosel, setPageCarrosel] = useState(0);

  const carousel = useRef(null);
  const hammertime = useRef(null);
  const ulRef = useRef(null);

  const scrollCarrossel = useCallback(
    isNext => {
      if (!ulRef.current) {
        return;
      }

      let page = pageCarrosel - 1;

      if (isNext) {
        page = pageCarrosel + 1;
      }

      // Se a posição do scroll for a mesma do tamanho Não faça nada
      if (
        isNext &&
        ulRef.current &&
        ulRef.current.scrollLeft + carousel.current.offsetWidth >= ulRef.current.scrollWidth
      ) {
        return;
      }

      let margin = carousel.current && page ? carousel.current.offsetWidth * page - 300 : 0;

      if (pageCarrosel < 0) {
        setPageCarrosel(0);
        margin = 0;
      } else if (ulRef.current && margin > ulRef.current.scrollWidth) {
        margin = ulRef.current.scrollWidth;
        setPageCarrosel(page);
      } else {
        setPageCarrosel(page);
      }

      ulRef.current.scroll({ left: margin, behavior: 'smooth' });
    },
    [pageCarrosel],
  );

  useEffect(() => {
    async function initHammer() {
      if (carousel.current) {
        if (!hammertime.current) {
          hammertime.current = new Hammer(carousel.current);
        }

        // Remove the last envent
        await hammertime.current.off('swipeleft');
        await hammertime.current.off('swiperight');

        // Add new event with atual state
        hammertime.current.on('swipeleft', () => scrollCarrossel(true));
        hammertime.current.on('swiperight', () => scrollCarrossel(false));
      }
    }

    initHammer();
  }, [carousel, pageCarrosel, scrollCarrossel]);

  return (
    <div ref={carousel} className="CarouselContainer">
      <header>
        <h2>
          {title} <FiArrowRight />
        </h2>
        <div className="icons">
          <MdChevronLeft onClick={() => scrollCarrossel(false)} />
          <MdChevronRight onClick={() => scrollCarrossel(true)} />
        </div>
      </header>

      <motion.ul variants={container} initial="hidden" animate="visible" ref={ulRef}>
        {children}
      </motion.ul>
    </div>
  );
}

export default Carousel;
