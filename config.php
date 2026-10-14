<?php
/**
 * RiTiVerse - Luxury Indian Real Estate Configuration & Data Store
 * India's Most Exclusive Coastal & Metropolitan Landmark Residences
 */

return [
    'site' => [
        'name'        => 'RiTiVerse',
        'tagline'     => 'Exceptional Living In India',
        'description' => 'A curated collection of remarkable residences across India\'s most sought-after addresses — from Mumbai\'s iconic coastline to Goa\'s private estates, selected for their design, location and way of life.',
        'phone'       => '+91 (022) 6985 4100',
        'phone_raw'   => '+912269854100',
        'email'       => 'concierge@ritiverse.com',
        'address'     => 'Worli Sea Face, Worli, Mumbai, Maharashtra 400030, India',
        'year'        => date('Y'),
        'currency'    => '₹',
    ],
    'nav' => [
        ['label' => 'About', 'url' => '#about'],
        ['label' => 'Features', 'url' => '#lumera-features'],
        ['label' => 'Properties', 'url' => '#project'],
        ['label' => 'Locations', 'url' => '#highlights'],
    ],
    'stats' => [
        ['value' => '12.5', 'unit' => ' Cr+ / ₹', 'label' => 'Starting Price'],
        ['value' => '75', 'unit' => '+', 'label' => 'Stories'],
        ['value' => '1,050', 'unit' => ' FT+', 'label' => 'Sky Elevation'],
        ['value' => 'Q4', 'unit' => ' 2028', 'label' => 'Handover Target'],
    ],
    'residences' => [
        [
            'id' => 'worli-seaface',
            'title' => 'The Arabian Seafront Residence',
            'type' => 'Worli Sea Face, Mumbai',
            'description' => 'A contemporary beachfront villa shaped around open spaces, uninterrupted Arabian Sea views and effortless indoor-outdoor coastal living.',
            'price' => '₹ 48,00,00,000',
            'specs' => [
                'bedrooms' => '5 Bedrooms',
                'bathrooms' => '6 Bathrooms',
                'area' => '11,400 Sq. Ft.',
                'view' => 'Full Arabian Sea & Bandra-Worli Sea Link',
            ],
            'primary_image' => 'assets/project-1-new.0u9fesgwq9skw.webp',
            'gallery' => [
                'assets/project-1.1.21wo08edq9jq7.webp',
                'assets/project-1.2.21k1kt3dkict_.webp',
                'assets/project-1.3.3_wrhlpce9-tl.webp',
                'assets/project-1.4.22gdyyx_nlxmj.webp',
            ]
        ],
        [
            'id' => 'malabar-crest',
            'title' => 'Malabar Crest Penthouse',
            'type' => 'Malabar Hill, Mumbai',
            'description' => 'An expansive penthouse retreat with wraparound terraces, double-height entertaining spaces and panoramic city views of Queen\'s Necklace.',
            'price' => '₹ 65,00,00,000',
            'specs' => [
                'bedrooms' => '5 Bedrooms',
                'bathrooms' => '7 Bathrooms',
                'area' => '9,850 Sq. Ft.',
                'view' => 'Marine Drive & Arabian Sea Horizon 360°',
            ],
            'primary_image' => 'assets/project-2.0pf4-s2q1w0m3.webp',
            'gallery' => [
                'assets/project-2.1.1vw2y5tubmey_.webp',
                'assets/project-2.2.14tsc4sqavahh.webp',
                'assets/project-2.3.1aji03tnwxy_s.webp',
                'assets/project-2.4.0-xw3j0pmljz9.webp',
            ]
        ],
        [
            'id' => 'goa-courtyard',
            'title' => 'Goa Coastal Villa',
            'type' => 'Assagao, North Goa',
            'description' => 'A tranquil private villa collection centered around lush tropical gardens, reflection pools and artisan Portuguese-modern interiors.',
            'price' => '₹ 24,50,00,000',
            'specs' => [
                'bedrooms' => '5 Bedrooms',
                'bathrooms' => '6 Bathrooms',
                'area' => '10,200 Sq. Ft.',
                'view' => 'Lush Green Valley & Private Forest Sanctuary',
            ],
            'primary_image' => 'assets/project-3-new.1fr3t03j3hm3-.webp',
            'gallery' => [
                'assets/project-3.1.0eu2b8jfet59o.webp',
                'assets/project-3.2.1_b9ln-xt6650.webp',
                'assets/project-3.3.2u_h5ill-wmmr.webp',
                'assets/project-3.4.27awdgq7i-imo.webp',
            ]
        ],
        [
            'id' => 'golf-estate',
            'title' => 'The Camellias Reserve',
            'type' => 'Golf Course Road, Delhi NCR',
            'description' => 'A statement sky mansion overlooking championship greens, pairing private wellness zones, grand banquet salons and a 7-star resort atmosphere.',
            'price' => '₹ 52,00,00,000',
            'specs' => [
                'bedrooms' => '6 Bedrooms',
                'bathrooms' => '8 Bathrooms',
                'area' => '13,500 Sq. Ft.',
                'view' => 'Aravalli Range & Championship Golf Greens',
            ],
            'primary_image' => 'assets/project-4-new.3svcowi2h1ysr.webp',
            'gallery' => [
                'assets/project-4.1.0wywf8j8wp-7u.webp',
                'assets/project-4.2.2gwfdmcnvnqm-.webp',
                'assets/project-4.3.0mrx5t7ctnat9.webp',
                'assets/project-4.4.2b-cyrddiu4n4.webp',
            ]
        ],
    ],
    'amenities' => [
        ['title' => 'Grand Porte-Cochère Lobby', 'icon' => 'assets/entrance-lobby.43yt5qvv2dl46.svg'],
        ['title' => 'Sculptural Cantilever Staircase', 'icon' => 'assets/feature-staircase.2mon55ttfkvmi.svg'],
        ['title' => 'Private Seafront Master Suite', 'icon' => 'assets/master-living-room.1jpx4femrmxgy.svg'],
        ['title' => 'Private Library & Cigar Salon', 'icon' => 'assets/private-study.3-zxjyz-10kk0.svg'],
        ['title' => 'Reflective Lotus Water Pavilion', 'icon' => 'assets/water-pavilion.1kpc9kd6yvjgt.svg'],
        ['title' => 'Ayurvedic Wellness Spa Suite', 'icon' => 'assets/spa.3cpkkrga4d7n1.svg'],
        ['title' => 'Infinity Sky Sundeck', 'icon' => 'assets/sunken-sun.1i-qts-w26xjm.svg'],
        ['title' => 'Coastal Lounge & Fireplace', 'icon' => 'assets/beach-lounge.3z469eb-p1wtu.svg'],
        ['title' => 'Temperature-Controlled Lap Pool', 'icon' => 'assets/pool-and-beach.3z054-ag1pi9b.svg'],
        ['title' => 'Private Landscaped Beachfront', 'icon' => 'assets/private-beach.27ri96kcqgx85.svg'],
    ],
    'faqs' => [
        [
            'q' => 'Where are RiTiVerse residences located?',
            'a' => 'RiTiVerse showcases an ultra-prime portfolio spanning Mumbai\'s prestigious Worli Sea Face and Malabar Hill, luxury private villas in Assagao, Goa, and landmark sky estates along Golf Course Road, Delhi NCR.'
        ],
        [
            'q' => 'What is the anticipated completion and handover timeline?',
            'a' => 'Construction and architectural detailing are advancing on schedule, with targeted completion across phases in Q4 2028. RERA-approved milestone inspections and private owner previews will precede handover.'
        ],
        [
            'q' => 'Are these properties RERA registered with clear titles?',
            'a' => 'Yes, 100%. Every residence curated by RiTiVerse undergoes stringent title due diligence, carries full RERA registration with statutory escrow accounts, and offers clear, marketable freehold ownership.'
        ],
        [
            'q' => 'What payment structures and milestones are available?',
            'a' => 'We provide bespoke construction-linked payment plans aligned with MahaRERA / RERA norms: 10% on booking, staggered milestone disbursements, and final balance upon occupancy certificate and handover.'
        ],
        [
            'q' => 'Can NRI and global Indian buyers invest remotely?',
            'a' => 'Yes, seamlessly. Our dedicated NRI Advisory Desk assists with NRE/NRO banking channels, RBI/FEMA guidelines, digital documentation, and virtual 3D walkthroughs for clients across the US, UK, Gulf, and Singapore.'
        ],
    ],
    'socials' => [
        ['name' => 'Facebook', 'url' => '#', 'icon' => 'assets/facebook.3rvk6c12nuvo6.svg'],
        ['name' => 'X / Twitter', 'url' => '#', 'icon' => 'assets/x.1r34ohx23d2is.svg'],
        ['name' => 'Instagram', 'url' => '#', 'icon' => 'assets/instagram.3q4vb5gjm6944.svg'],
        ['name' => 'LinkedIn', 'url' => '#', 'icon' => 'assets/linkedin.3r_ty6i-5rokn.svg'],
    ]
];
