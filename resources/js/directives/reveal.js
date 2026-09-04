/**
 * Directive v-reveal : révèle l'élément avec une animation d'entrée
 * lorsqu'il entre dans le viewport (IntersectionObserver).
 *
 * Usage :
 *   <div v-reveal>…</div>
 *   <div v-reveal="{ delay: 150 }">…</div>
 */
const observer =
    typeof IntersectionObserver !== 'undefined'
        ? new IntersectionObserver(
              (entries) => {
                  entries.forEach((entry) => {
                      if (entry.isIntersecting) {
                          entry.target.classList.add('reveal-visible');
                          observer.unobserve(entry.target);
                      }
                  });
              },
              { threshold: 0.12, rootMargin: '0px 0px -48px 0px' },
          )
        : null;

export default {
    mounted(el, binding) {
        if (!observer) {
            return; // Environnement sans IO : contenu toujours visible
        }

        const { delay = 0, from = 'up' } = binding.value ?? {};

        el.classList.add('reveal', `reveal-from-${from}`);

        if (delay) {
            el.style.transitionDelay = `${delay}ms`;
        }

        // Si l'élément est déjà visible au chargement, le révéler immédiatement
        requestAnimationFrame(() => observer.observe(el));
    },
};
