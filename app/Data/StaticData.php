<?php

namespace App\Data;

class StaticData
{
    public static function brand(): array
    {
        return [
            'name'    => 'Planetario Realty',
            'legal'   => 'Planetario Realty & Brokerage Services Inc.',
            'tagline' => 'Turning Property Dreams into Reality',
            'short'   => 'A Bohol-rooted realty house, brokering homes and investments across the Visayas with care and clarity.',
            'address' => '66 Remolador Ext., Brgy. Cogon, Tagbilaran City, Bohol',
            'phone'   => '0910 267 1424',
            'email'   => 'planetariorealtyandbrokerage@gmail.com',
            'founded' => '2018',
            'license' => 'PRC Licensed Brokerage',
        ];
    }

    public static function properties(): array
    {
        return [
            [
                'id'         => 'panglao-villa-alon',
                'name'       => 'Villa Alon',
                'location'   => 'Panglao, Bohol',
                'region'     => 'Bohol',
                'type'       => 'House & Lot',
                'status'     => 'For Sale',
                'price'      => 38500000,
                'priceLabel' => '₱38.5M',
                'beds'       => 4,
                'baths'      => 5,
                'area'       => 420,
                'lot'        => 850,
                'summary'    => 'A low-slung beachfront residence on Alona\'s quieter southern shoulder — coral-stone walls, deep eaves, and an outdoor lanai that opens to a 28m saltwater pool.',
                'features'   => ['Beachfront access', '28m saltwater pool', 'Staff quarters', 'Storm-rated build', 'Solar-ready roof'],
                'tags'       => ['Luxury', 'Beachfront'],
            ],
            [
                'id'         => 'tagbilaran-skyline-residences',
                'name'       => 'Skyline Residences — Unit 18F',
                'location'   => 'Tagbilaran City, Bohol',
                'region'     => 'Bohol',
                'type'       => 'Condominium',
                'status'     => 'For Sale',
                'price'      => 6800000,
                'priceLabel' => '₱6.8M',
                'beds'       => 2,
                'baths'      => 2,
                'area'       => 78,
                'lot'        => null,
                'summary'    => 'A corner two-bedroom on the 18th floor of Tagbilaran\'s first true highrise. North-facing balcony catches Cogon\'s evening light and a quiet line of sight to the bay.',
                'features'   => ['Corner unit', 'Bay view', 'Two parking slots', 'Pool & gym deck', '24/7 security'],
                'tags'       => ['Urban', 'Investment'],
            ],
            [
                'id'         => 'cebu-it-park-penthouse',
                'name'       => 'Helio Penthouse',
                'location'   => 'Cebu IT Park, Cebu City',
                'region'     => 'Cebu',
                'type'       => 'Condotel',
                'status'     => 'For Sale',
                'price'      => 24200000,
                'priceLabel' => '₱24.2M',
                'beds'       => 3,
                'baths'      => 3,
                'area'       => 184,
                'lot'        => null,
                'summary'    => 'Full-floor penthouse above the Cebu IT Park canopy. Two private terraces, a wrap-around kitchen, and built-in furnishings finished in oak and brushed brass.',
                'features'   => ['Two terraces', 'Smart-home wiring', 'Two parking + driver\'s room', 'Concierge', 'Skyline view'],
                'tags'       => ['Luxury', 'Urban'],
            ],
            [
                'id'         => 'loboc-river-house',
                'name'       => 'Loboc River House',
                'location'   => 'Loboc, Bohol',
                'region'     => 'Bohol',
                'type'       => 'House & Lot',
                'status'     => 'For Sale',
                'price'      => 14500000,
                'priceLabel' => '₱14.5M',
                'beds'       => 3,
                'baths'      => 3,
                'area'       => 240,
                'lot'        => 1100,
                'summary'    => 'A timber-and-stone retreat tucked into the bend of the Loboc River — wide screened verandas, a stone kitchen hearth, and a private dock for paddleboards.',
                'features'   => ['River frontage', 'Private dock', 'Detached studio', 'Stone hearth', 'Mature gardens'],
                'tags'       => ['Heritage', 'Waterfront'],
            ],
            [
                'id'         => 'mactan-bayfront-lot',
                'name'       => 'Mactan Bayfront Lot',
                'location'   => 'Punta Engaño, Mactan',
                'region'     => 'Cebu',
                'type'       => 'Lot Only',
                'status'     => 'For Sale',
                'price'      => 19800000,
                'priceLabel' => '₱19.8M',
                'beds'       => null,
                'baths'      => null,
                'area'       => null,
                'lot'        => 980,
                'summary'    => 'A 980 sqm titled beachfront parcel on Mactan\'s eastern bayline — ready to build, with mature talisay shade and approved zoning for a single residence or boutique villa cluster.',
                'features'   => ['Clean title', 'Beachfront', 'Approved zoning', 'Power & water lines onsite'],
                'tags'       => ['Investment', 'Beachfront'],
            ],
            [
                'id'         => 'talisay-hillside',
                'name'       => 'Talisay Hillside Residence',
                'location'   => 'Talisay City, Cebu',
                'region'     => 'Cebu',
                'type'       => 'House & Lot',
                'status'     => 'For Sale',
                'price'      => 9200000,
                'priceLabel' => '₱9.2M',
                'beds'       => 4,
                'baths'      => 3,
                'area'       => 220,
                'lot'        => 320,
                'summary'    => 'A four-bedroom hillside family home in a gated Talisay enclave. North-facing living areas catch the cross breeze; the kitchen opens to a covered grill court.',
                'features'   => ['Gated community', 'Covered grill court', 'Cross ventilation', 'School zone'],
                'tags'       => ['Family', 'Suburban'],
            ],
            [
                'id'         => 'chocolate-hills-estate',
                'name'       => 'Chocolate Hills View Estate',
                'location'   => 'Carmen, Bohol',
                'region'     => 'Bohol',
                'type'       => 'House & Lot',
                'status'     => 'For Sale',
                'price'      => 11500000,
                'priceLabel' => '₱11.5M',
                'beds'       => 3,
                'baths'      => 2,
                'area'       => 190,
                'lot'        => 1400,
                'summary'    => 'A long modern bungalow set on a 1,400 sqm rise above Carmen — uninterrupted views of the Chocolate Hills from the kitchen, living room, and primary suite.',
                'features'   => ['Hill view', 'Solar array', 'Rainwater catchment', 'Mature gardens'],
                'tags'       => ['Heritage', 'Family'],
            ],
            [
                'id'         => 'cebu-business-park-office',
                'name'       => 'Cebu Business Park Office Floor',
                'location'   => 'Cebu Business Park, Cebu City',
                'region'     => 'Cebu',
                'type'       => 'Commercial',
                'status'     => 'For Sale',
                'price'      => 64500000,
                'priceLabel' => '₱64.5M',
                'beds'       => null,
                'baths'      => 4,
                'area'       => 612,
                'lot'        => null,
                'summary'    => 'A full 612 sqm office floor in the central tower of Cebu Business Park. Raised flooring, fitted server room, and a private executive suite.',
                'features'   => ['Raised floor', 'Server room', 'Executive suite', 'Three lifts', '24/7 access'],
                'tags'       => ['Commercial', 'Urban'],
            ],
            [
                'id'         => 'anda-coastal-lot',
                'name'       => 'Anda Coastal Lot — Lot 4',
                'location'   => 'Anda, Bohol',
                'region'     => 'Bohol',
                'type'       => 'Lot Only',
                'status'     => 'For Sale',
                'price'      => 4200000,
                'priceLabel' => '₱4.2M',
                'beds'       => null,
                'baths'      => null,
                'area'       => null,
                'lot'        => 620,
                'summary'    => 'A titled 620 sqm coastal parcel in Anda\'s quiet eastern bay. Soft slope, mature coconuts, and an unobstructed view of the Mindanao Sea.',
                'features'   => ['Clean title', 'Coastal frontage', 'Coconut grove', 'Soft slope, easy build'],
                'tags'       => ['Investment', 'Beachfront'],
            ],
        ];
    }

    public static function team(): array
    {
        return [
            ['name' => 'Maria Lourdes A. Pajuyo',  'role' => 'President & Broker-in-Charge',  'bio' => 'PRC-licensed real estate broker with two decades helping Boholano families and OFW investors find a clear path to ownership.'],
            ['name' => 'Reynaldo G. Bernabe',       'role' => 'Chief Operating Officer',        'bio' => 'Oversees brokerage operations across Bohol and Cebu. Built our documentation and transaction-assistance practice from the ground up.'],
            ['name' => 'Anabelle T. Sorongon',      'role' => 'Head of Sales — Bohol',          'bio' => 'Leads our Tagbilaran salesfloor. Anabelle has personally closed over 280 transactions in Panglao, Loboc, and Carmen.'],
            ['name' => 'Jovenal R. Macachor',       'role' => 'Head of Sales — Cebu',           'bio' => 'Heads our Cebu City team and the commercial portfolio. Former developer-side sales director with deep knowledge of the metro market.'],
            ['name' => 'Claudine F. Ompad',         'role' => 'Investment Consultant',           'bio' => 'Works closely with returning OFWs and first-time investors to size opportunities, run yield projections, and structure financing.'],
            ['name' => 'Carlito S. Espera',         'role' => 'Documentation Lead',              'bio' => 'Carlito and his team handle every CAR, BIR, RD, and transfer filing — so our clients never have to chase a single paper themselves.'],
            ['name' => 'Mildred V. Tagaylo',        'role' => 'Senior Sales Associate',          'bio' => 'Specializes in the Panglao, Dauis, and Baclayon coastal corridor. Known among clients for patient, jargon-free property tripping.'],
            ['name' => 'Patrick N. Quirante',       'role' => 'Senior Sales Associate',          'bio' => 'Heads our condominium and condotel desk in Cebu — placing Filipino-American buyers and Singaporean families in serviced residences.'],
        ];
    }

    public static function developers(): array
    {
        return [
            ['name' => 'Costabella Land Corporation',  'locations' => ['Panglao', 'Dauis'],        'sigil' => 'C', 'portfolio' => 'Beachfront residences & boutique villa clusters', 'desc' => 'A Bohol-based developer working with traditional coral-stone craft and modern engineering. Our exclusive partner for the southern Panglao coastline.'],
            ['name' => 'Visayas Skyline Properties',   'locations' => ['Cebu City', 'Mactan'],      'sigil' => 'V', 'portfolio' => 'High-rise residential & condotel',                 'desc' => 'Cebu\'s reliable mid-luxury highrise builder. Skyline Residences (Tagbilaran) and Helio Tower (Cebu IT Park) are flagship Planetario inventories.'],
            ['name' => 'Tagbilaran Heritage Builders', 'locations' => ['Tagbilaran', 'Loboc'],      'sigil' => 'T', 'portfolio' => 'Heritage homes & restorations',                    'desc' => 'Family-owned, three generations deep in Bohol craftsmanship. They handle our restoration mandates along the Loboc and Loay river corridors.'],
            ['name' => 'Pacific Ridge Development',    'locations' => ['Talisay', 'Liloan'],        'sigil' => 'P', 'portfolio' => 'Gated family communities',                         'desc' => 'Quiet, well-priced gated subdivisions across metro Cebu\'s secondary belt. We co-launched their Talisay Hillside and Liloan Vista phases.'],
            ['name' => 'Anda Coast Holdings',          'locations' => ['Anda', 'Candijay'],         'sigil' => 'A', 'portfolio' => 'Coastal land & eco-resort lots',                   'desc' => 'Stewards of the Anda eastern bayline. Their titled coastal parcels go to Planetario buyers first under a long-standing referral arrangement.'],
            ['name' => 'Calmera Estates',              'locations' => ['Carmen', 'Sagbayan'],       'sigil' => 'K', 'portfolio' => 'Hillside estates & view properties',                'desc' => 'Specialists in the Chocolate Hills tourism corridor. Carmen ridgeline estates and Sagbayan farm-residences come through Calmera and Planetario.'],
        ];
    }

    public static function stories(): array
    {
        return [
            [
                'client'   => 'The Reyes Family',
                'quote'    => 'We walked in unsure if we could afford to come home. We walked out with a house, our parents\' name on the title, and no debt to chase.',
                'location' => 'Panglao, Bohol',
                'summary'  => 'An OFW family in Dubai had been saving for nine years to bring their parents back to Bohol from Manila. We sourced a turnkey two-bedroom near Panglao Town, negotiated a 12% reduction, and ran every BIR and transfer filing from our office.',
                'stats'    => [['v' => '9 yrs', 'l' => 'Savings goal'], ['v' => '12%', 'l' => 'Price reduction'], ['v' => '32 days', 'l' => 'To title transfer']],
            ],
            [
                'client'   => 'Marisol & Yolanda Cariaga',
                'quote'    => 'We\'re sisters, and we\'d never owned anything together. They treated our small first investment with the same care as their biggest deal.',
                'location' => 'Tagbilaran, Bohol',
                'summary'  => 'Two sisters — a nurse and a teacher — pooled their first investment into a one-bedroom condo on the Tagbilaran bay. We co-signed the rental management arrangement and helped them structure quarterly payouts.',
                'stats'    => [['v' => '₱3.2M', 'l' => 'First investment'], ['v' => '7.4%', 'l' => 'Year-one yield'], ['v' => '100%', 'l' => 'Occupancy']],
            ],
            [
                'client'   => 'Daniel Tan, Singapore',
                'quote'    => 'I had never set foot in Bohol. Three video calls, two notarized authorizations, and a flight later, I owned a 980 sqm parcel on Mactan Bay.',
                'location' => 'Mactan, Cebu',
                'summary'  => 'A Singaporean buyer wanted Visayan coastal land but couldn\'t travel during purchase. We ran the entire transaction remotely — title verification, surveyor, BIR filings, and a video walkthrough of every boundary marker.',
                'stats'    => [['v' => '100%', 'l' => 'Remote'], ['v' => '980 sqm', 'l' => 'Mactan bayfront'], ['v' => '48 hrs', 'l' => 'Title cleared']],
            ],
            [
                'client'   => 'The Velasco Heirs',
                'quote'    => 'Three generations, eight heirs, one family lot. They were the only ones who didn\'t make it feel like a lawsuit.',
                'location' => 'Loboc, Bohol',
                'summary'  => 'An inherited Loboc river property had been undivided for four decades. We facilitated an extrajudicial settlement, brokered a buy-out among the eight heirs, and listed the consolidated parcel — closing in 11 months.',
                'stats'    => [['v' => '8', 'l' => 'Heirs reconciled'], ['v' => '40 yrs', 'l' => 'Undivided'], ['v' => '11 mo', 'l' => 'To closing']],
            ],
        ];
    }

    public static function testimonials(): array
    {
        return [
            ['quote' => 'They picked up the phone on a Sunday morning when our buyer\'s bank delayed. By Tuesday, the deed was at the Registry. That\'s who they are.',             'name' => 'Engr. Felipe Y. Bacalla',   'role' => 'Repeat client · Cebu City'],
            ['quote' => 'I\'m an OFW. I bought a lot from a Dubai construction site, on a phone screen. Maria walked it with me three times before I said yes. I will never use anyone else.', 'name' => 'Joselle Mariano-Cruz',     'role' => 'Engineer · Dubai → Tagbilaran'],
            ['quote' => 'Most brokers want the easiest sale. Planetario asked me what I actually wanted my life to look like in five years, and built the search around that.',      'name' => 'Dr. Andres N. Lumbab',      'role' => 'Physician · Loboc'],
            ['quote' => 'I was a first-time buyer and a single mother. They explained every line of every document until I understood it. Not once did I feel rushed.',             'name' => 'Jenelyn G. Tampus',         'role' => 'Teacher · Talisay'],
            ['quote' => 'We\'ve worked with Planetario on three developer launches. They sell with integrity — they will tell a buyer to wait if the unit isn\'t right. That\'s why we partner.', 'name' => 'Atty. Ramon J. Costabella', 'role' => 'Developer · Panglao'],
            ['quote' => 'Five months of paperwork on an inherited property they untangled in eleven. They saved our family from selling at a loss to a broker who would have rushed us.', 'name' => 'Carmela R. Velasco',       'role' => 'Heir · Loboc'],
        ];
    }

    public static function services(): array
    {
        return [
            ['num' => '01', 'title' => 'Property Sales',             'desc' => 'Residential and commercial lots, house & lot, condominium, condotel — vetted listings across Bohol and Cebu.'],
            ['num' => '02', 'title' => 'Brokerage Services',         'desc' => 'Full-cycle representation for sellers and buyers, with PRC-licensed brokers and a clear chain of accountability.'],
            ['num' => '03', 'title' => 'Marketing & Listings',       'desc' => 'Photography, copy, online distribution, and qualified-buyer matching for property owners.'],
            ['num' => '04', 'title' => 'Investment Consultation',    'desc' => 'Yield modeling, financing structure, and long-horizon planning for first-time and returning investors.'],
            ['num' => '05', 'title' => 'Documentation Assistance',   'desc' => 'BIR, Registry of Deeds, LGU, HOA — every filing handled in-house. You sign; we walk.'],
            ['num' => '06', 'title' => 'Property Tripping & Site Tours', 'desc' => 'Patient, jargon-free site visits across Bohol and Cebu — including remote video tours for buyers abroad.'],
        ];
    }

    public static function values(): array
    {
        return [
            ['n' => '01', 't' => 'Integrity',          'd' => 'Honest, transparent, in writing. Every figure we quote is one we will honor at closing.'],
            ['n' => '02', 't' => 'Client Commitment',  'd' => 'Your goals are the brief. Not the listing, not the commission — the life you\'re trying to build.'],
            ['n' => '03', 't' => 'Excellence',         'd' => 'Professional standards in every document, every site visit, every conversation.'],
            ['n' => '04', 't' => 'Trust',              'd' => 'Long-term relationships, not single transactions. Most of our clients are referrals from clients.'],
            ['n' => '05', 't' => 'Innovation',         'd' => 'Modern marketing, remote tours, digital documentation — adapted for clients across the world.'],
        ];
    }
}
