import { motion } from 'framer-motion';
import { useRef, useState } from 'react';

export default function MagneticButton({ children, className = '', onClick, strength = 0.35, ...props }) {
    const ref = useRef(null);
    const [position, setPosition] = useState({ x: 0, y: 0 });

    const handleMouseMove = (e) => {
        if (!ref.current) return;
        const { left, top, width, height } = ref.current.getBoundingClientRect();
        const middleX = left + width / 2;
        const middleY = top + height / 2;
        setPosition({
            x: (e.clientX - middleX) * strength,
            y: (e.clientY - middleY) * strength,
        });
    };

    const handleMouseLeave = () => {
        setPosition({ x: 0, y: 0 });
    };

    return (
        <motion.button
            ref={ref}
            onMouseMove={handleMouseMove}
            onMouseLeave={handleMouseLeave}
            animate={{ x: position.x, y: position.y }}
            transition={{ type: 'spring', damping: 15, stiffness: 150, mass: 0.1 }}
            onClick={onClick}
            className={`relative inline-flex items-center justify-center cursor-pointer select-none ${className}`}
            {...props}
        >
            {children}
        </motion.button>
    );
}
