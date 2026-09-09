<?php
/**
 * Editorial content that isn't the collection: navigation, the current
 * exhibition, visitor services, booking options and FAQ copy.
 */

declare(strict_types=1);

const NAV = [
    ['label' => 'Gallery',  'file' => 'gallery.php',  'index' => '01'],
    ['label' => 'Artists',  'file' => 'artists.php',  'index' => '02'],
    ['label' => 'Visit',    'file' => 'booking.php',  'index' => '03'],
    ['label' => 'Contact',  'file' => 'contact.php',  'index' => '04'],
];

const EXHIBITION_CURRENT = [
    'title'    => 'Warm Ground',
    'subtitle' => 'Sana Farooqi — nine new paintings',
    'from'     => '14 Aug',
    'to'       => '30 Oct 2026',
    'room'     => 'Main room & stairwell',
    'blurb'    => 'Nine canvases built flat on the floor, where pigment was allowed to pool, dry and leave its own drawing. The largest work in the show is the largest Farooqi has ever finished.',
    'img'      => 'hero/hero-02.jpg',
];

const EXHIBITION_NEXT = [
    'title'    => 'Eleven Fields, Eleven Skies',
    'subtitle' => 'Talha Bin Yousaf & Faraz Junaid',
    'from'     => '12 Nov',
    'to'       => '18 Jan 2027',
    'blurb'    => 'A painter who works before sunrise and a printmaker who cuts skies eleven blocks deep, hung in the same room for the first time.',
];

const STATS = [
    ['value' => 15,  'suffix' => '',  'label' => 'Years on Kohsar Block'],
    ['value' => 8,   'suffix' => '',  'label' => 'Artists represented'],
    ['value' => 62,  'suffix' => '',  'label' => 'Exhibitions mounted'],
    ['value' => 940, 'suffix' => '+', 'label' => 'Works placed in collections'],
];

const SERVICES = [
    [
        'index' => '01',
        'title' => 'Private viewing',
        'body'  => 'The gallery closed to everyone but you, a curator in the room, and any work in storage brought up on request. Ninety minutes, no obligation to buy.',
        'meta'  => 'By appointment · Tue–Sat',
    ],
    [
        'index' => '02',
        'title' => 'Advisory & collection building',
        'body'  => 'We work with fourteen collections on a continuing basis — sourcing, condition reporting, valuation for insurance, and the occasional talking-out-of a bad decision.',
        'meta'  => 'Retained or per-acquisition',
    ],
    [
        'index' => '03',
        'title' => 'Conservation framing',
        'body'  => 'Museum-grade glazing, acid-free mounts and hand-finished hardwood profiles, cut in our own workshop. Every framed work leaves with a condition record.',
        'meta'  => 'Included on framed works',
    ],
    [
        'index' => '04',
        'title' => 'Delivery & installation',
        'body'  => 'Crated and couriered anywhere in Pakistan, hung by our own installer within Islamabad and Rawalpindi. International shipping arranged with full documentation.',
        'meta'  => 'Nationwide · International on request',
    ],
];

const BOOKING_TYPES = [
    ['value' => 'private-viewing', 'label' => 'Private viewing',      'note' => '90 minutes · gallery closed to others'],
    ['value' => 'guided-walk',     'label' => 'Guided walk-through',  'note' => '45 minutes · with a curator'],
    ['value' => 'advisory',        'label' => 'Collection advisory',  'note' => '60 minutes · in person or online'],
    ['value' => 'single-work',     'label' => 'View a single work',   'note' => '30 minutes · brought up from storage'],
];

const TIME_SLOTS = ['11:00', '12:00', '13:00', '15:00', '16:00', '17:00', '18:00'];

const TESTIMONIALS = [
    [
        'quote'  => 'I came in to look at a print and left having reconsidered the whole wall. Nobody hurried me, and nobody mentioned a price until I asked.',
        'name'   => 'Hamza Qureshi',
        'role'   => 'Collector, Islamabad',
    ],
    [
        'quote'  => 'They talked me out of a purchase once. That is the reason I have bought six works from them since.',
        'name'   => 'Dr. Ayesha Mahmood',
        'role'   => 'Private collection, Lahore',
    ],
    [
        'quote'  => 'The framing workshop alone is worth the visit. I have seen museums do worse work with more money.',
        'name'   => 'Rehan Sikandar',
        'role'   => 'Architect, Rawalpindi',
    ],
    [
        'quote'  => 'Condition reports, provenance, insurance valuation — all of it arrived before I had to ask twice. Rare here, rare anywhere.',
        'name'   => 'Farida Jalal',
        'role'   => 'Corporate collection advisor',
    ],
];

const FAQ = [
    [
        'q' => 'Can I reserve a work before visiting?',
        'a' => 'Yes. A work can be held for seven days without payment. Add it to your enquiry list and send it through, or message us on WhatsApp and we will place the hold the same day.',
    ],
    [
        'q' => 'Are the prices on the site final?',
        'a' => 'They are the gallery price and they are what you pay. We do not run a two-tier system. Framing is included wherever a work is listed as framed, and delivery within Islamabad and Rawalpindi is free.',
    ],
    [
        'q' => 'Do you ship outside Pakistan?',
        'a' => 'Regularly. Works are crated to museum standard and shipped with an export licence, condition report and insurance certificate. We will quote freight before you commit to anything.',
    ],
    [
        'q' => 'What if the work does not suit the room?',
        'a' => 'Any work may be returned within fourteen days of delivery for a full refund, provided it is undamaged. We would rather have the work back than have it in the wrong house.',
    ],
    [
        'q' => 'Can I pay in instalments?',
        'a' => 'On works above five lakh, over three or six months, interest free. The work stays with us until the final payment clears unless you would rather live with it in the meantime — most people would.',
    ],
    [
        'q' => 'Do you look at work by new artists?',
        'a' => 'Twice a year, in March and September. Send twelve images and a paragraph. We reply to everyone, which is why it takes a few weeks.',
    ],
];

const FRAMING_NOTE = [
    'title' => 'Everything leaves the building properly',
    'body'  => 'Framing is not an upsell here — it is a workshop at the back of the gallery with two full-time framers, museum glazing on the shelf and a hardwood stock we cut ourselves. If a work is listed as framed, that is the frame you will receive, and it will arrive with a condition record you can hand to an insurer.',
    'img'   => 'hero/hero-03.jpg',
];
