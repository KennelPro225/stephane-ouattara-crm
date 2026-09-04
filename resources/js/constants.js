export const HUES = ['var(--c1)', 'var(--c2)', 'var(--c3)', 'var(--c4)']
export const HUES_SOFT = ['var(--c1-soft)', 'var(--c2-soft)', 'var(--c3-soft)', 'var(--c4-soft)']

export const AUDIENCE_HUE_INDEX = { adolescents: 2, adultes: 1, entreprises: 3 }
export const AUDIENCE_LABELS = { adolescents: 'Adolescents', adultes: 'Adultes', entreprises: 'Entreprises' }
export const TYPE_LABELS = { individual: 'Individuel', group: 'Groupe', corporate: 'Entreprise' }

export const BOOKING_STATUS_LABELS = { pending: 'En attente', confirmed: 'Confirmée', completed: 'Terminée', cancelled: 'Annulée' }
export const BOOKING_STATUS_TAGS = { pending: 'tag-accent', confirmed: 'tag-success', completed: 'tag-neutral', cancelled: 'tag-outline' }

export const CUSTOMER_STATUS_LABELS = { nouveau: 'Nouveau', contacte: 'Contacté', converti: 'Converti' }

export const SERVICE_TYPE_LABELS = {
  individual: 'Coaching individuel',
  club_des_champions: 'Club des Champions',
  group: 'Coaching de groupe',
  corporate_wellness: 'Bien-être entreprise',
  teambuilding: 'Teambuilding',
  other: 'Autre',
}

export function audienceHue (audience) {
  return HUES[AUDIENCE_HUE_INDEX[audience] ?? 0]
}
export function audienceSoft (audience) {
  return HUES_SOFT[AUDIENCE_HUE_INDEX[audience] ?? 0]
}
export function hueAt (i) {
  return HUES[i % HUES.length]
}

export function formatDate (value) {
  if (!value) return '—'
  return new Date(value).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' })
}
