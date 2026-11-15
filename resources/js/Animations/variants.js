// Luxury easing and animation presets for LAVÉRO
export const luxuryEase = [0.16, 1, 0.3, 1];

export const fadeIn = (direction = 'up', delay = 0, duration = 0.8) => ({
    hidden: {
        opacity: 0,
        y: direction === 'up' ? 40 : direction === 'down' ? -40 : 0,
        x: direction === 'left' ? 40 : direction === 'right' ? -40 : 0,
    },
    visible: {
        opacity: 1,
        y: 0,
        x: 0,
        transition: {
            duration,
            delay,
            ease: luxuryEase,
        },
    },
});

export const staggerContainer = (staggerChildren = 0.1, delayChildren = 0.1) => ({
    hidden: {},
    visible: {
        transition: {
            staggerChildren,
            delayChildren,
        },
    },
});

export const wordReveal = {
    hidden: { y: '100%', opacity: 0 },
    visible: (i = 0) => ({
        y: '0%',
        opacity: 1,
        transition: {
            duration: 0.9,
            delay: i * 0.05,
            ease: luxuryEase,
        },
    }),
};

export const drawerVariants = {
    closed: {
        x: '100%',
        transition: { type: 'spring', damping: 30, stiffness: 300 },
    },
    open: {
        x: 0,
        transition: { type: 'spring', damping: 30, stiffness: 300 },
    },
};

export const backdropVariants = {
    closed: { opacity: 0 },
    open: { opacity: 1, transition: { duration: 0.4 } },
};
