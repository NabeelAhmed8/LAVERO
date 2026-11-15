import { motion } from 'framer-motion';
import { fadeIn } from './variants';

export default function FadeIn({
    children,
    direction = 'up',
    delay = 0,
    duration = 0.8,
    className = '',
    viewport = { once: true, margin: '-50px' },
    ...props
}) {
    return (
        <motion.div
            variants={fadeIn(direction, delay, duration)}
            initial="hidden"
            whileInView="visible"
            viewport={viewport}
            className={className}
            {...props}
        >
            {children}
        </motion.div>
    );
}
