<?php
/**
 * Génère les images SVG de démonstration dans storage/app/public/
 * (portrait du héro + 6 visuels de galerie).
 */

$base = __DIR__ . '/../storage/app/public';
@mkdir("$base/content", 0775, true);
@mkdir("$base/galleries", 0775, true);

function svg(string $title, string $subtitle, string $c1, string $c2, int $w = 800, int $h = 600): string
{
    return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="$w" height="$h" viewBox="0 0 $w $h">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="$c1"/>
      <stop offset="100%" stop-color="$c2"/>
    </linearGradient>
    <radialGradient id="glow" cx="30%" cy="20%" r="80%">
      <stop offset="0%" stop-color="#F5A76B" stop-opacity="0.55"/>
      <stop offset="100%" stop-color="#00000000" stop-opacity="0"/>
    </radialGradient>
  </defs>
  <rect width="$w" height="$h" fill="url(#g)"/>
  <rect width="$w" height="$h" fill="url(#glow)"/>
  <circle cx="640" cy="140" r="180" fill="#E67E22" opacity="0.18"/>
  <circle cx="120" cy="480" r="220" fill="#B8740F" opacity="0.15"/>
  <!-- silhouette mentor -->
  <g fill="#FFFFFF" opacity="0.92">
    <circle cx="$w/2" cy="215" r="62"/>
    <path d="M {$w}/2 295 c -95 0 -150 60 -150 130 l 0 175 l 300 0 l 0 -175 c 0 -70 -55 -130 -150 -130 z"/>
  </g>
  <text x="50%" y="525" text-anchor="middle" font-family="Georgia, serif" font-size="34" font-weight="bold" fill="#FFFDF8">$title</text>
  <text x="50%" y="562" text-anchor="middle" font-family="Arial, sans-serif" font-size="19" fill="#F5A76B">$subtitle</text>
</svg>
SVG;
}

$files = [
    ['content/hero-portrait.svg', 'Stéphane OUATTARA', 'Coach en Développement Personnel', '#3E2C17', '#7C5A2F'],
    ['galleries/coaching-individuel.svg', 'Coaching Individuel', 'Développement personnel et professionnel', '#4A3419', '#8B6530'],
    ['galleries/ateliers-groupe.svg', 'Ateliers de Groupe', 'Formation et développement collectif', '#59401D', '#966F35'],
    ['galleries/teambuilding.svg', 'Teambuilding', "Cohésion d'équipe et performance collective", '#67491F', '#A57A38'],
    ['galleries/conferences.svg', 'Conférences', "Partage d'expertise et inspiration", '#755222', '#B4853C'],
    ['galleries/coaching-jeunes.svg', 'Coaching Jeunes', 'Accompagnement spécial adolescents', '#845C26', '#C28F41'],
    ['galleries/leadership.svg', 'Formation Leadership', 'Développement des leaders de demain', '#8B652C', '#CE9946'],
];

foreach ($files as [$path, $title, $subtitle, $c1, $c2]) {
    file_put_contents("$base/$path", svg($title, $subtitle, $c1, $c2));
    echo "+ $path\n";
}
