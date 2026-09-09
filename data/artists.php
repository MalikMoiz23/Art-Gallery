<?php
/**
 * Represented artists. `initials` is rendered as the card's typographic mark;
 * the gallery publishes no artist portraits.
 */

declare(strict_types=1);

const ARTISTS = [
    [
        'slug'       => 'zohra-baig',
        'name'       => 'Zohra Baig',
        'initials'   => 'ZB',
        'city'       => 'Lahore',
        'born'       => 1979,
        'discipline' => 'Figurative painting',
        'represented'=> 2013,
        'statement'  => 'I paint the pause before someone speaks — the room holding its breath.',
        'bio'        => [
            'Zohra Baig trained at the National College of Arts and spent eight years painting almost nothing but interiors: doorways, half-drawn curtains, a chair pushed back from a table. Her figures are rarely doing anything. They stand at thresholds, caught in the second before a decision.',
            'She works in thin oil glazes over a warm ground, building light in fifteen or twenty passes so the surface keeps a faint inner glow. Her canvases have entered collections in Lahore, Dubai and London, and she has taught painting at NCA since 2016.',
        ],
        'exhibitions' => [
            [2024, 'The Long Afternoon', 'Nuqta, Islamabad'],
            [2022, 'Interior Weather', 'Canvas Gallery, Karachi'],
            [2019, 'Six Rooms', 'Alhamra Art Centre, Lahore'],
        ],
        'press' => ['Baig paints silence better than most painters paint noise.', 'The Friday Times'],
    ],
    [
        'slug'       => 'talha-bin-yousaf',
        'name'       => 'Talha Bin Yousaf',
        'initials'   => 'TY',
        'city'       => 'Lahore',
        'born'       => 1972,
        'discipline' => 'Landscape painting',
        'represented'=> 2011,
        'statement'  => 'A landscape is a portrait of weather. I am only ever painting air.',
        'bio'        => [
            'Talha Bin Yousaf paints outdoors, often before sunrise, in the belt of farmland between Lahore and Sheikhupura. He returns to the same eleven sites year after year, and the repetition is the point: the fields are a fixed instrument he plays in different light.',
            'His larger canvases are finished in the studio from small oil panels made on site, never from photographs. He has shown continuously since 1998 and was one of the four artists Nuqta opened with in 2011.',
        ],
        'exhibitions' => [
            [2025, 'Eleven Fields', 'Nuqta, Islamabad'],
            [2023, 'Before Light', 'Full Circle Gallery, Karachi'],
            [2018, 'Ravi, Twice', 'Ejaz Art Gallery, Lahore'],
        ],
        'press' => ['Yousaf is the most patient painter working in Pakistan today.', 'Dawn Images'],
    ],
    [
        'slug'       => 'sana-farooqi',
        'name'       => 'Sana Farooqi',
        'initials'   => 'SF',
        'city'       => 'Karachi',
        'born'       => 1986,
        'discipline' => 'Colour and light',
        'represented'=> 2017,
        'statement'  => 'Colour is a temperature before it is a hue. I work until the canvas feels warm.',
        'bio'        => [
            'Sana Farooqi builds paintings out of stacked translucent fields, working flat on the floor so pigment pools and dries in tidelines. Nothing is drawn first. The composition arrives through the fifth or sixth layer and she stops the moment it does.',
            'She studied at the Indus Valley School of Art and Architecture, then spent two years assisting a fresco conservator in Bologna — an apprenticeship that still shows in her palette and in her appetite for very large surfaces.',
        ],
        'exhibitions' => [
            [2025, 'Warm Ground', 'Nuqta, Islamabad'],
            [2024, 'Tideline', 'Sanat Initiative, Karachi'],
            [2021, 'Bologna Notebooks', 'IVS Gallery, Karachi'],
        ],
        'press' => ['Farooqi has found a way to make scale feel intimate.', 'ArtNow Pakistan'],
    ],
    [
        'slug'       => 'nashra-kamal',
        'name'       => 'Nashra Kamal',
        'initials'   => 'NK',
        'city'       => 'Islamabad',
        'born'       => 1991,
        'discipline' => 'Contemporary miniature',
        'represented'=> 2019,
        'statement'  => 'The miniature was never small. It was dense. I am keeping the density.',
        'bio'        => [
            'Nashra Kamal grinds her own pigment and makes her own squirrel-hair brushes, some of which carry a single hair. A finished work takes between four and nine months. Within that inherited discipline she smuggles in modern subject matter — border queues, waiting rooms, satellite dishes on a Rajput rooftop.',
            'She completed the miniature programme at NCA in 2015 and has since been included in survey shows of contemporary South Asian painting in Sharjah, Delhi and Berlin.',
        ],
        'exhibitions' => [
            [2025, 'Waiting Rooms', 'Nuqta, Islamabad'],
            [2023, 'Dense / Small', 'Aicon, New York'],
            [2022, 'New Miniature Now', 'Sharjah Art Foundation'],
        ],
        'press' => ['Kamal treats tradition as a live technology, not a museum.', 'The Herald'],
    ],
    [
        'slug'       => 'kabir-rehmat',
        'name'       => 'Kabir Rehmat',
        'initials'   => 'KR',
        'city'       => 'Peshawar',
        'born'       => 1968,
        'discipline' => 'Calligraphy and illumination',
        'represented'=> 2014,
        'statement'  => 'A letter is a building. You can walk around it. Most people only read the front door.',
        'bio'        => [
            'Kabir Rehmat served a nine-year traditional apprenticeship in Peshawar before he was permitted to sign his own work. He writes in Kufic and Nastaliq on hand-sized panels of gesso and gold leaf, and increasingly on sheets large enough that a single letter stands taller than a person.',
            'His illuminated double pages are held by two university collections and the Lok Virsa archive. He accepts one private commission a year and no more.',
        ],
        'exhibitions' => [
            [2024, 'One Letter, Standing', 'Nuqta, Islamabad'],
            [2021, 'Gold, Gesso, Ground', 'Koel Gallery, Karachi'],
            [2017, 'The Ninth Year', 'Peshawar Museum'],
        ],
        'press' => ['Rehmat has enlarged calligraphy without inflating it.', 'The News on Sunday'],
    ],
    [
        'slug'       => 'faraz-junaid',
        'name'       => 'Faraz Junaid',
        'initials'   => 'FJ',
        'city'       => 'Sialkot',
        'born'       => 1984,
        'discipline' => 'Woodblock and relief print',
        'represented'=> 2016,
        'statement'  => 'Carving is subtraction. By the time the block is right, the image has been decided for me.',
        'bio'        => [
            'Faraz Junaid cuts cherry-wood blocks by hand and prints on dampened kozo paper with a baren, one colour at a time — some of his skies take eleven separate impressions. He keeps editions deliberately small and destroys the key block when an edition closes.',
            'Trained in Sialkot and later in Kyoto on a Japan Foundation fellowship, he is one of very few printmakers in Pakistan working entirely without a press.',
        ],
        'exhibitions' => [
            [2025, 'Eleven Skies', 'Nuqta, Islamabad'],
            [2022, 'No Press', 'Rohtas 2, Lahore'],
            [2019, 'Kyoto Blocks', 'Japan Foundation, Karachi'],
        ],
        'press' => ['Junaid prints skies you can feel the humidity in.', 'Youlin Magazine'],
    ],
    [
        'slug'       => 'meher-tabassum',
        'name'       => 'Meher Tabassum',
        'initials'   => 'MT',
        'city'       => 'Multan',
        'born'       => 1976,
        'discipline' => 'Textile and fibre',
        'represented'=> 2015,
        'statement'  => 'Cloth remembers every hand that touched it. I am adding mine to a very long list.',
        'bio'        => [
            'Meher Tabassum works with weavers in Multan and Bahawalpur on hand-dyed silk and metallic-thread panels, reviving damask structures that had fallen out of production by the 1970s. Each panel is woven to her drawing but finished by the weaver, and both names go on the label.',
            'Her practice sits deliberately between authorship and collaboration. Panels from her 2020 series are in the permanent collection of the Lahore Museum.',
        ],
        'exhibitions' => [
            [2024, 'Two Names on the Label', 'Nuqta, Islamabad'],
            [2022, 'Damask, Revived', 'COMO Museum, Lahore'],
            [2020, 'Warp / Weft / Hand', 'Multan Arts Council'],
        ],
        'press' => ['Tabassum has made collaboration visible without making it sentimental.', 'Nukta Art'],
    ],
    [
        'slug'       => 'danyal-rustam',
        'name'       => 'Danyal Rustam',
        'initials'   => 'DR',
        'city'       => 'Quetta',
        'born'       => 1981,
        'discipline' => 'Sculpture and ceramics',
        'represented'=> 2018,
        'statement'  => 'Stone gives you one chance. Clay gives you a hundred. I need both to stay honest.',
        'bio'        => [
            'Danyal Rustam carves Ziarat marble and throws large earthenware forms he finishes with slip and a wood firing. The two halves of the practice check each other — the marble teaches restraint, the clay allows argument.',
            'He built his own kiln outside Quetta in 2014 and fires it three times a year. Collectors typically wait a season.',
        ],
        'exhibitions' => [
            [2025, 'One Chance / A Hundred', 'Nuqta, Islamabad'],
            [2023, 'Ziarat', 'Taseer Art Gallery, Lahore'],
            [2020, 'Three Firings', 'Quetta Arts Council'],
        ],
        'press' => ['Rustam is that rare sculptor equally credible in stone and in clay.', 'Dawn'],
    ],
];
