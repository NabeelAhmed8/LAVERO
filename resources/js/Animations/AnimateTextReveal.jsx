import { motion } from 'framer-motion';
import { wordReveal } from './variants';

export default function AnimateTextReveal({ text, className = '', el: Component = 'h1' }) {
    const words = text.split(' ');

    return (
        <Component className={`flex flex-wrap overflow-hidden ${className}`}>
            {words.map((word, i) => (
                <span key={i} className="inline-block overflow-hidden mr-[0.28em] last:mr-0">
                    <motion.span
                        variants={wordReveal}
                        initial="hidden"
                        whileInView="visible"
                        viewport={{ once: true }}
                        custom={i}
                        className="inline-block"
                    >
                        {word}
                    </motion.span>
                </span>
            ))}
        </Component>
    );
}
