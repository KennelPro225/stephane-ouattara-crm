const prefersReducedMotion = () =>
  typeof window !== 'undefined' && window.matchMedia?.('(prefers-reduced-motion: reduce)').matches

const observer = typeof IntersectionObserver !== 'undefined'
  ? new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add('is-visible')
          observer.unobserve(entry.target)
        }
      })
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' })
  : null

/**
 * v-reveal="{ delay: 80 }" — fades/slides an element in once it scrolls into
 * view. No-ops entirely under prefers-reduced-motion.
 */
export default {
  mounted (el, binding) {
    if (prefersReducedMotion() || !observer) {
      el.classList.add('is-visible')
      return
    }
    el.setAttribute('data-reveal', '')
    const delay = binding.value?.delay ?? 0
    if (delay) el.style.transitionDelay = `${delay}ms`
    observer.observe(el)
  },
  unmounted (el) {
    observer?.unobserve(el)
  },
}
