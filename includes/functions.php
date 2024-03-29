<?php

/**
 * Define amazon RegionName and host and also RegionCode
 *
 * @return string[][]
 */
function ams_get_amazon_regions() {
    $regions = array(
        'com.au' => array( 'RegionName' => 'Australia', 'Host' => 'webservices.amazon.com.au', 'RegionCode' => 'us-west-2' ),
        'com.br' => array( 'RegionName' => 'Brazil', 'Host' => 'webservices.amazon.com.br', 'RegionCode' => 'us-east-1' ),
        'ca'     => array( 'RegionName' => 'Canada', 'Host' => 'webservices.amazon.ca', 'RegionCode' => 'us-east-1' ),
        'cn'     => array( 'RegionName' => 'China', 'Host' => 'webservices.amazon.cn', 'RegionCode' => 'us-west-2' ),
        'fr'     => array( 'RegionName' => 'France', 'Host' => 'webservices.amazon.fr', 'RegionCode' => 'eu-west-1' ),
        'de'     => array( 'RegionName' => 'Germany', 'Host' => 'webservices.amazon.de', 'RegionCode' => 'eu-west-1' ),
        'in'     => array( 'RegionName' => 'India', 'Host' => 'webservices.amazon.in', 'RegionCode' => 'eu-west-1' ),
        'it'     => array( 'RegionName' => 'Italy', 'Host' => 'webservices.amazon.it', 'RegionCode' => 'eu-west-1' ),
        'jp'     => array( 'RegionName' => 'Japan', 'Host' => 'webservices.amazon.co.jp', 'RegionCode' => 'us-west-2' ),
        'mx'     => array( 'RegionName' => 'Mexico', 'Host' => 'webservices.amazon.com.mx', 'RegionCode' => 'us-east-1' ),
        'nl'     => array( 'RegionName' => 'Netherlands', 'Host' => 'webservices.amazon.nl', 'RegionCode' => 'eu-west-1' ),
        'sa'     => array( 'RegionName' => 'Saudi Arabia', 'Host' => 'webservices.amazon.sa', 'RegionCode' => 'eu-west-1' ),
        'sg'     => array( 'RegionName' => 'Singapore', 'Host' => 'webservices.amazon.sg', 'RegionCode' => 'us-west-2' ),
        'es'     => array( 'RegionName' => 'Spain', 'Host' => 'webservices.amazon.es', 'RegionCode' => 'eu-west-1' ),
        'com.tr' => array( 'RegionName' => 'Turkey', 'Host' => 'webservices.amazon.com.tr', 'RegionCode' => 'eu-west-1' ),
        'ae'     => array( 'RegionName' => 'United Arab Emirates', 'Host' => 'webservices.amazon.ae', 'RegionCode' => 'eu-west-1' ),
        'co.uk'  => array( 'RegionName' => 'United Kingdom', 'Host' => 'webservices.amazon.co.uk', 'RegionCode' => 'eu-west-1' ),
        'com'    => array( 'RegionName' => 'United States', 'Host' => 'webservices.amazon.com', 'RegionCode' => 'us-east-1' )
    );

    return  $regions;
}

/**
 * Total products information is brought through this function
 *
 * @return array
 */
function ams_get_all_products_info() {
    global $wpdb;

    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT DISTINCT  $wpdb->posts.ID, $wpdb->postmeta.meta_value
             FROM $wpdb->posts, $wpdb->postmeta
             WHERE $wpdb->posts.ID  = $wpdb->postmeta.post_id 
             AND  $wpdb->posts.post_type  = %s
             AND $wpdb->postmeta.meta_key = %s 
             ",
            'product', '_wca_amazon_affiliate_asin'
        )
    );

    $products_search_count = get_option( 'wca_products_search_count' );
    $data = array();
    $products_count = 0;
    $total_view_count = 0;
    $total_product_direct_redirected = 0;
    $total_product_added_to_cart = 0;

    foreach ( $results as $row ) {

        $data['asin'][] = $row->meta_value;
        $data['id'][] = $row->ID;

        $view = get_post_meta( $row->ID, 'ams_product_views_count', true );
        $total_view_count = $total_view_count + ( int )$view;

        $product_direct_redirected = get_post_meta( $row->ID, 'ams_product_direct_redirected', true );
        $total_product_direct_redirected = $total_product_direct_redirected + ( int )$product_direct_redirected;

        $product_added_to_cart = get_post_meta( $row->ID, 'ams_product_added_to_cart', true );
        $total_product_added_to_cart = $total_product_added_to_cart + ( int )$product_added_to_cart;

        $products_count++;
    }

    $data['total_view_count'] = $total_view_count;
    $data['total_product_direct_redirected'] = $total_product_direct_redirected;
    $data['total_product_added_to_cart'] = $total_product_added_to_cart;
    $data['products_count'] = $products_count;
    $data['products_search_count'] = $products_search_count;
    return $data;
}

/**
 * Amazon affiliate departments country base
 *
 * @return string[][]
 */
function ams_amazon_departments() {
    $cat = array(
        'com.au' => array(
            'All'                     => 'All Departments',
            'Automotive'              => 'Automotive',
            'Baby'                    => 'Baby',
            'Beauty'                  => 'Beauty',
            'Books'                   => 'Books',
            'Computers'               => 'Computers',
            'Electronics'             => 'Electronics',
            'EverythingElse'          => 'Everything Else',
            'Fashion'                 => 'Clothing & Shoes',
            'GiftCards'               => 'Gift Cards',
            'HealthPersonalCare'      => 'Health, Household & Personal Care',
            'HomeAndKitchen'          => 'Home & Kitchen',
            'KindleStore'             => 'Kindle Store',
            'Lighting'                => 'Lighting',
            'Luggage'                 => 'Luggage & Travel Gear',
            'MobileApps'              => 'Apps & Games',
            'MoviesAndTV'             => 'Movies & TV',
            'Music'                   => 'CDs & Vinyl',
            'OfficeProducts'          => 'Stationery & Office Products',
            'PetSupplies'             => 'Pet Supplies',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports, Fitness & Outdoors',
            'ToolsAndHomeImprovement' => 'Home Improvement',
            'ToysAndGames'            => 'Toys & Games',
            'VideoGames'              => 'Video Games',
        ),
        'com.br' => array(
            'All'                     => 'Todos os departamentos',
            'Books'                   => 'Livros',
            'Computers'               => 'Computadores e Informática',
            'Electronics'             => 'Eletrônicos',
            'HomeAndKitchen'          => 'Casa e Cozinha',
            'KindleStore'             => 'Loja Kindle',
            'MobileApps'              => 'Apps e Jogos',
            'OfficeProducts'          => 'Material para Escritório e Papelaria',
            'ToolsAndHomeImprovement' => 'Ferramentas e Materiais de Construção',
            'VideoGames'              => 'Games',
        ),
        'ca'     => array(
            'All'                     => 'All Department',
            'Apparel'                 => 'Clothing & Accessories',
            'Automotive'              => 'Automotive',
            'Baby'                    => 'Baby',
            'Beauty'                  => 'Beauty',
            'Books'                   => 'Books',
            'Classical'               => 'Classical Music',
            'Electronics'             => 'Electronics',
            'EverythingElse'          => 'Everything Else',
            'ForeignBooks'            => 'English Books',
            'GardenAndOutdoor'        => 'Patio, Lawn & Garden',
            'GiftCards'               => 'GiftCards',
            'GroceryAndGourmetFood'   => 'Grocery & Gourmet Food',
            'Handmade'                => 'Handmade',
            'HealthPersonalCare'      => 'Health & Personal Care',
            'HomeAndKitchen'          => 'Home & Kitchen',
            'Industrial'              => 'Industrial & Scientific',
            'Jewelry'                 => 'Jewelry',
            'KindleStore'             => 'Kindle Store',
            'Luggage'                 => 'Luggage & Bags',
            'LuxuryBeauty'            => 'Luxury Beauty',
            'MobileApps'              => 'Apps & Games',
            'MoviesAndTV'             => 'Movies & TV',
            'Music'                   => 'Music',
            'MusicalInstruments'      => 'Musical Instruments, Stage & Studio',
            'OfficeProducts'          => 'Office Products',
            'PetSupplies'             => 'Pet Supplies',
            'Shoes'                   => 'Shoes & Handbags',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports & Outdoors',
            'ToolsAndHomeImprovement' => 'Tools & Home Improvement',
            'ToysAndGames'            => 'Toys & Games',
            'VHS'                     => 'VHS',
            'VideoGames'              => 'Video Games',
            'Watches'                 => 'Watches',
        ),
        'cn'     => array(
            'All' => 'All Departments',
        ),
        'fr'     => array(
            'All'                     => 'Toutes nos catégories',
            'Apparel'                 => 'Vêtements et accessoires',
            'Appliances'              => 'Gros électroménager',
            'Automotive'              => 'Auto et Moto',
            'Baby'                    => '	Bébés & Puériculture',
            'Beauty'                  => 'Beauté et Parfum',
            'Books'                   => 'Livres en français',
            'Computers'               => 'Informatique',
            'DigitalMusic'            => 'Téléchargement de musique',
            'Electronics'             => 'High-Tech',
            'EverythingElse'          => 'Autres',
            'Fashion'                 => 'Mode',
            'ForeignBooks'            => 'Livres anglais et étrangers',
            'GardenAndOutdoor'        => 'Jardin',
            'GiftCards'               => 'Boutique chèques-cadeaux',
            'GroceryAndGourmetFood'   => 'Epicerie',
            'Handmade'                => 'Handmade',
            'HealthPersonalCare'      => 'Hygiène et Santé',
            'HomeAndKitchen'          => 'Cuisine & Maison',
            'Industrial'              => 'Secteur industriel & scientifique',
            'Jewelry'                 => 'Bijoux',
            'KindleStore'             => 'Boutique Kindle',
            'Lighting'                => 'Luminaires et Eclairage',
            'Luggage'                 => 'Bagages',
            'LuxuryBeauty'            => 'Beauté Prestige',
            'MobileApps'              => 'Applis & Jeux',
            'MoviesAndTV'             => 'DVD & Blu-ray',
            'Music'                   => 'Musique : CD & Vinyles',
            'MusicalInstruments'      => 'Instruments de musique & Sono',
            'OfficeProducts'          => 'Fournitures de bureau',
            'PetSupplies'             => 'Animalerie',
            'Shoes'                   => 'Chaussures et Sacs',
            'Software'                => 'Logiciels',
            'SportsAndOutdoors'       => 'Sports et Loisirs',
            'ToolsAndHomeImprovement' => 'Bricolage',
            'ToysAndGames'            => 'Jeux et Jouets',
            'VHS'                     => 'VHS',
            'VideoGames'              => 'Jeux vidéo',
            'Watches'                 => 'Montres',
        ),
        'de'     => array(
            'All'                     => 'Alle Kategorien',
            'AmazonVideo'             => 'Prime Video',
            'Apparel'                 => 'Bekleidung',
            'Appliances'              => 'Elektro-Großgeräte',
            'Automotive'              => 'Auto & Motorrad',
            'Baby'                    => 'Baby',
            'Beauty'                  => 'Beauty',
            'Books'                   => 'Bücher',
            'Classical'               => 'Klassik',
            'Computers'               => 'Computer & Zubehör',
            'DigitalMusic'            => 'Musik-Downloads',
            'Electronics'             => 'Elektronik & Foto',
            'EverythingElse'          => 'Sonstiges',
            'Fashion'                 => 'Fashion',
            'ForeignBooks'            => 'Bücher (Fremdsprachig)',
            'GardenAndOutdoor'        => 'Garten',
            'GiftCards'               => 'Geschenkgutscheine',
            'GroceryAndGourmetFood'   => 'Lebensmittel & Getränke',
            'Handmade'                => 'Handmade',
            'HealthPersonalCare'      => 'Drogerie & Körperpflege',
            'HomeAndKitchen'          => 'Küche, Haushalt & Wohnen',
            'Industrial'              => 'Gewerbe, Industrie & Wissenschaft',
            'Jewelry'                 => 'Schmuck',
            'KindleStore'             => 'Kindle-Shop',
            'Lighting'                => 'Beleuchtung',
            'Luggage'                 => 'Koffer, Rucksäcke & Taschen',
            'LuxuryBeauty'            => 'Luxury Beauty',
            'Magazines'               => 'Zeitschriften',
            'MobileApps'              => 'Apps & Spiele',
            'MoviesAndTV'             => 'DVD & Blu-ray',
            'Music'                   => 'Musik-CDs & Vinyl',
            'MusicalInstruments'      => 'Musikinstrumente & DJ-Equipment',
            'OfficeProducts'          => 'Bürobedarf & Schreibwaren',
            'PetSupplies'             => 'Haustier',
            'Photo'                   => 'Kamera & Foto',
            'Shoes'                   => 'Schuhe & Handtaschen',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sport & Freizeit',
            'ToolsAndHomeImprovement' => 'Baumarkt',
            'ToysAndGames'            => 'Spielzeug',
            'VHS'                     => 'VHS',
            'VideoGames'              => 'Games',
            'Watches'                 => 'Uhren',
        ),
        'in'     => array(
            'All'                     => 'All Categories',
            'Apparel'                 => 'Clothing & Accessories',
            'Appliances'              => 'Appliances',
            'Automotive'              => 'Car & Motorbike',
            'Beauty'                  => 'Beauty',
            'Books'                   => 'Books',
            'Collectibles'            => 'Collectibles',
            'Computers'               => 'Computers & Accessories',
            'Electronics'             => 'Electronics',
            'EverythingElse'          => 'Everything Else',
            'Fashion'                 => 'Amazon Fashion',
            'Furniture'               => 'Furniture',
            'GardenAndOutdoor'        => 'Garden & Outdoors',
            'GiftCards'               => 'Gift Cards',
            'GroceryAndGourmetFood'   => 'Grocery & Gourmet Foods',
            'HealthPersonalCare'      => 'Health & Personal Care',
            'HomeAndKitchen'          => 'Home & Kitchen',
            'Industrial'              => 'Industrial & Scientific',
            'Jewelry'                 => 'Jewellery',
            'KindleStore'             => 'Kindle Store',
            'Luggage'                 => 'Luggage & Bags',
            'LuxuryBeauty'            => 'Luxury Beauty',
            'MobileApps'              => 'Apps & Games',
            'MoviesAndTV'             => 'Movies & TV Shows',
            'Music'                   => 'Music',
            'MusicalInstruments'      => 'Musical Instruments',
            'OfficeProducts'          => 'Office Products',
            'PetSupplies'             => 'Pet Supplies',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports, Fitness & Outdoors',
            'ToolsAndHomeImprovement' => 'Tools & Home Improvement',
            'ToysAndGames'            => 'Toys & Games',
            'VideoGames'              => 'Video Games',
            'Watches'                 => 'Watches',
        ),
        'it'     => array(
            'All'                     => 'Tutte le categorie',
            'Apparel'                 => 'Abbigliamento',
            'Appliances'              => 'Grandi elettrodomestici',
            'Automotive'              => 'Auto e Moto',
            'Baby'                    => 'Prima infanzia',
            'Beauty'                  => 'Bellezza',
            'Books'                   => 'Libri',
            'Computers'               => 'Informatica',
            'DigitalMusic'            => 'Musica Digitale',
            'Electronics'             => 'Elettronica',
            'EverythingElse'          => 'Altro',
            'Fashion'                 => 'Moda',
            'ForeignBooks'            => 'Libri in altre lingue',
            'GardenAndOutdoor'        => 'Giardino e giardinaggio',
            'GiftCards'               => 'Buoni Regalo',
            'GroceryAndGourmetFood'   => 'Alimentari e cura della casa',
            'Handmade'                => 'Handmade',
            'HealthPersonalCare'      => 'Salute e cura della persona',
            'HomeAndKitchen'          => 'Casa e cucina',
            'Industrial'              => 'Industria e Scienza',
            'Jewelry'                 => 'Gioielli',
            'KindleStore'             => 'Kindle Store',
            'Lighting'                => 'Illuminazione',
            'Luggage'                 => 'Valigeria',
            'MobileApps'              => 'App e Giochi',
            'MoviesAndTV'             => 'Film e TV',
            'Music'                   => 'CD e Vinili',
            'MusicalInstruments'      => 'Strumenti musicali e DJ',
            'OfficeProducts'          => 'Cancelleria e prodotti per ufficio',
            'PetSupplies'             => 'Prodotti per animali domestici',
            'Shoes'                   => 'Scarpe e borse',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sport e tempo libero',
            'ToolsAndHomeImprovement' => 'Fai da te',
            'ToysAndGames'            => 'Giochi e giocattoli',
            'VideoGames'              => 'Videogiochi',
            'Watches'                 => 'Orologi',
        ),
        'jp'     => array(
            'All'                     => 'All Departments',
            'AmazonVideo'             => 'Prime Video',
            'Apparel'                 => 'Clothing & Accessories',
            'Appliances'              => 'Large Appliances',
            'Automotive'              => 'Car & Bike Products',
            'Baby'                    => 'Baby & Maternity',
            'Beauty'                  => 'Beauty',
            'Books'                   => 'Japanese Books',
            'Computers'               => 'Computers & Accessories',
            'CreditCards'             => 'Credit Cards',
            'DigitalMusic'            => 'Digital Music',
            'Electronics'             => 'Electronics & Cameras',
            'EverythingElse'          => 'Everything Else',
            'Fashion'                 => 'Fashion',
            'FashionBaby'             => 'Kids & Baby',
            'FashionMen'              => 'Men',
            'FashionWomen'            => 'Women',
            'ForeignBooks'            => 'English Books',
            'GiftCards'               => 'Gift Cards',
            'GroceryAndGourmetFood'   => 'Food & Beverage',
            'HealthPersonalCare'      => 'Health & Personal Care',
            'Hobbies'                 => 'Hobby',
            'HomeAndKitchen'          => 'Kitchen & Housewares',
            'Industrial'              => 'Industrial & Scientific',
            'Jewelry'                 => 'Jewelry',
            'KindleStore'             => 'Kindle Store',
            'MobileApps'              => 'Apps & Games',
            'MoviesAndTV'             => 'Movies & TV',
            'Music'                   => 'Music',
            'MusicalInstruments'      => 'Musical Instruments',
            'OfficeProducts'          => 'Stationery and Office Products',
            'PetSupplies'             => 'Pet Supplies',
            'Shoes'                   => 'Shoes & Bags',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports',
            'ToolsAndHomeImprovement' => 'DIY, Tools & Garden',
            'Toys'                    => 'Toys',
            'VideoGames'              => 'Computer & Video Games',
            'Watches'                 => 'Watches',
        ),
        'mx'     => array(
            'All'                     => 'Todos los departamentos',
            'Automotive'              => 'Auto',
            'Baby'                    => 'Bebé',
            'Books'                   => 'Libros',
            'Electronics'             => 'Electrónicos',
            'Fashion'                 => 'Ropa, Zapatos y Accesorios',
            'FashionBaby'             => 'Ropa, Zapatos y Accesorios Bebé',
            'FashionBoys'             => 'Ropa, Zapatos y Accesorios Niños',
            'FashionGirls'            => 'Ropa, Zapatos y Accesorios Niñas',
            'FashionMen'              => 'Ropa, Zapatos y Accesorios Niñas',
            'FashionWomen'            => 'Ropa, Zapatos y Accesorios Mujeres',
            'GroceryAndGourmetFood'   => 'Alimentos y Bebidas',
            'Handmade'                => 'Productos Handmade',
            'HealthPersonalCare'      => 'Salud, Belleza y Cuidado Personal',
            'HomeAndKitchen'          => 'Hogar y Cocina',
            'IndustrialAndScientific' => 'Industria y ciencia',
            'KindleStore'             => 'Tienda Kindle',
            'MoviesAndTV'             => 'Películas y Series de TV',
            'Music'                   => 'Música',
            'MusicalInstruments'      => 'Instrumentos musicales',
            'OfficeProducts'          => 'Oficina y Papelería',
            'PetSupplies'             => 'Mascotas',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Deportes y Aire Libre',
            'ToolsAndHomeImprovement' => 'Herramientas y Mejoras del Hogar',
            'ToysAndGames'            => 'Juegos y juguetes',
            'VideoGames'              => 'Videojuegos',
            'Watches'                 => 'Relojes',
        ),
        'nl'     => array(
            'All'                     => 'Alle afdelingen',
            'Automotive'              => 'Auto en motor',
            'Baby'                    => 'Babyproducten',
            'Beauty'                  => 'Beauty en persoonlijke verzorging',
            'Books'                   => 'Boeken',
            'Electronics'             => 'Elektronica',
            'EverythingElse'          => 'Overig',
            'Fashion'                 => 'Kleding, schoenen en sieraden',
            'GardenAndOutdoor'        => 'Tuin, terras en gazon',
            'GiftCards'               => 'Cadeaubonnen',
            'GroceryAndGourmetFood'   => 'Levensmiddelen',
            'HealthPersonalCare'      => 'Gezondheid en persoonlijke verzorging',
            'HomeAndKitchen'          => 'Wonen en keuken',
            'Industrial'              => 'Zakelijk, industrie en wetenschap',
            'KindleStore'             => 'Kindle Store',
            'MoviesAndTV'             => 'Films en tv',
            'Music'                   => 'Cd\'s en lp\'s',
            'MusicalInstruments'      => 'Muziekinstrumenten',
            'OfficeProducts'          => 'Kantoorproducten',
            'PetSupplies'             => 'Huisdierbenodigdheden',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sport en outdoor',
            'ToolsAndHomeImprovement' => 'Klussen en gereedschap',
            'ToysAndGames'            => 'Speelgoed en spellen',
            'VideoGames'              => 'Videogames',
        ),
        'sa'     => array(
            'All'                     => 'All Categories',
            'ArtsAndCrafts'           => 'Arts, Crafts & Sewing',
            'Automotive'              => 'Automotive Parts & Accessories',
            'Baby'                    => 'Baby',
            'Beauty'                  => 'Beauty & Personal Care',
            'Books'                   => 'Books',
            'Computers'               => 'Computer & Accessories',
            'Electronics'             => 'Electronics',
            'Fashion'                 => 'Clothing, Shoes & Jewelry',
            'GardenAndOutdoor'        => 'Home & Garden',
            'GiftCards'               => 'Gift Cards',
            'GroceryAndGourmetFood'   => 'Grocery & Gourmet Food',
            'HealthPersonalCare'      => 'Health, Household & Baby Care',
            'HomeAndKitchen'          => 'Kitchen & Dining',
            'Industrial'              => 'Industrial & Scientific',
            'KindleStore'             => 'Kindle Store',
            'Miscellaneous'           => 'Everything Else',
            'MoviesAndTV'             => 'MoviesAndTV',
            'Music'                   => 'CDs & Vinyl',
            'MusicalInstruments	'     => 'Musical Instruments',
            'OfficeProducts'          => 'Office Productsd',
            'PetSupplies'             => 'Pet Supplies',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports',
            'ToolsAndHomeImprovement' => 'Tools & Home Improvement',
            'ToysAndGames'            => 'Toys & Games',
            'VideoGames'              => 'Video Games',
        ),
        'sg'     => array(
            'All'                     => 'All Departments',
            'Automotive'              => 'Automotive',
            'Baby'                    => 'Baby',
            'Beauty'                  => 'Beauty & Personal Care',
            'Computers'               => 'Computers',
            'Electronics'             => 'Electronics',
            'GroceryAndGourmetFood'   => 'Grocery',
            'HealthPersonalCare'      => 'HealthPersonalCare',
            'HomeAndKitchen'          => 'Home, Kitchen & Dining',
            'OfficeProducts'          => 'Office Products',
            'PetSupplies'             => 'Pet Supplies',
            'SportsAndOutdoors'       => 'Sports & Outdoors',
            'ToolsAndHomeImprovement' => 'Tools & Home Improvement',
            'ToysAndGames'            => 'Toys & Games',
            'VideoGames'              => 'Video Games',
        ),
        'es'     => array(
            'All'                     => 'Todos los departamentos',
            'Apparel'                 => 'Ropa y accesorios',
            'Appliances'              => 'Appliances',
            'Automotive'              => 'Coche y moto',
            'Baby'                    => 'Bebé',
            'Beauty'                  => 'Belleza',
            'Books'                   => 'Libros',
            'Computers'               => 'Informática',
            'DigitalMusic'            => 'Música Digital',
            'Electronics'             => 'Electrónica',
            'EverythingElse'          => 'Otros Productos',
            'Fashion'                 => 'Moda',
            'ForeignBooks'            => 'Libros en idiomas extranjeros',
            'GardenAndOutdoor'        => 'Jardín',
            'GiftCards'               => 'Cheques regalo',
            'GroceryAndGourmetFood'   => 'Alimentación y bebidas',
            'Handmade'                => 'Handmade',
            'HealthPersonalCare'      => 'Salud y cuidado personal',
            'HomeAndKitchen'          => 'Hogar y cocina',
            'Industrial'              => 'Industria y ciencia',
            'Jewelry'                 => 'Joyería',
            'KindleStore'             => 'Tienda Kindle',
            'Lighting'                => 'Iluminación',
            'Luggage'                 => 'Equipaje',
            'MobileApps'              => 'Appstore para Android',
            'MoviesAndTV'             => 'Películas y TV',
            'Music'                   => 'Música: CDs y vinilos',
            'MusicalInstruments	'     => 'Instrumentos musicales',
            'OfficeProducts'          => 'Oficina y papelería',
            'PetSupplies'             => 'Productos para mascotas',
            'Shoes'                   => 'Zapatos y complementos',
            'Software'                => 'Softwares',
            'SportsAndOutdoors'       => 'Deportes y aire libre',
            'ToolsAndHomeImprovement' => 'Bricolaje y herramientas',
            'ToysAndGames'            => 'Juguetes y juegos',
            'Vehicles'                => 'Coche - renting',
            'VideoGames'              => 'Videojuegos',
            'Watches'                 => 'Relojes',
        ),
        'com.tr' => array(
            'All'                     => 'Tüm Kategoriler',
            'Baby'                    => 'Bebek',
            'Books'                   => 'Kitaplar',
            'Computers'               => 'Bilgisayarlar',
            'Electronics'             => 'Elektronik',
            'EverythingElse'          => 'Diğer Her Şey',
            'Fashion'                 => 'Moda',
            'HomeAndKitchen'          => 'Ev ve Mutfak',
            'OfficeProducts'          => 'Ofis Ürünleri',
            'SportsAndOutdoors'       => 'Spor',
            'ToolsAndHomeImprovement' => 'Yapı Market',
            'ToysAndGames'            => 'Oyuncaklar ve Oyunlar',
            'VideoGames'              => 'PC ve Video Oyunları',
        ),
        'ae'     => array(
            'All'                     => 'All Departments',
            'Appliances'              => 'Appliances',
            'ArtsAndCrafts'           => 'Arts, Crafts & Sewing',
            'Automotive'              => 'Automotive Parts & Accessories',
            'Baby'                    => 'Baby',
            'Beauty	'                 => 'Beauty & Personal Care',
            'Books'                   => 'Books',
            'Computers'               => 'Computers',
            'Electronics'             => 'Electronics',
            'EverythingElse'          => 'Everything Else',
            'Fashion'                 => 'Clothing, Shoes & Jewelry',
            'GardenAndOutdoor'        => 'Home & Garden',
            'GroceryAndGourmetFood'   => 'Grocery & Gourmet Food',
            'HealthPersonalCare'      => 'Health, Household & Baby Care',
            'HomeAndKitchen'          => 'Home & Kitchen',
            'Industrial'              => 'Industrial & Scientific',
            'Lighting'                => 'Lighting',
            'MusicalInstruments'      => 'Musical Instruments',
            'OfficeProducts'          => 'Office Products',
            'PetSupplies'             => 'Pet Supplies',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports',
            'ToolsAndHomeImprovement' => 'Tools & Home Improvement',
            'ToysAndGames'            => 'Toys & Games',
            'VideoGames'              => 'Video Games',
        ),
        'co.uk'  => array(
            'All'                     => 'All Departments',
            'AmazonVideo'             => 'Amazon Video',
            'Apparel'                 => 'Clothing',
            'Appliances'              => 'Large Appliances',
            'Automotive'              => 'Car & Motorbike',
            'Baby'                    => 'Baby',
            'Beauty'                  => 'Beauty',
            'Books'                   => 'Books',
            'Classical'               => 'Classical Music',
            'Computers'               => 'Computers & Accessories',
            'DigitalMusic'            => 'Digital Music',
            'Electronics'             => 'Electronics & Photo',
            'EverythingElse'          => 'Everything Else',
            'Fashion'                 => 'Fashion',
            'GardenAndOutdoor'        => 'Garden & Outdoors',
            'GiftCards'               => 'Gift Cards',
            'GroceryAndGourmetFood'   => 'Grocery',
            'Handmade'                => 'Handmade',
            'HealthPersonalCare'      => 'Health & Personal Care',
            'HomeAndKitchen'          => 'Home & Kitchen',
            'Industrial'              => 'Industrial & Scientific',
            'Jewelry'                 => 'Industrial & Scientific',
            'KindleStore'             => 'Kindle Store',
            'Luggage'                 => 'Luggage',
            'LuxuryBeauty'            => 'Luxury Beauty',
            'MobileApps'              => 'Apps & Games',
            'MoviesAndTV'             => 'DVD & Blu-ray',
            'Music'                   => 'CDs & Vinyl',
            'MusicalInstruments'      => 'Musical Instruments & DJ',
            'OfficeProducts'          => 'Stationery & Office SuppliesJ',
            'PetSupplies'             => 'Pet Supplies',
            'Shoes'                   => 'Shoes & Bags',
            'Software'                => 'Software',
            'SportsAndOutdoors'       => 'Sports & Outdoors',
            'ToolsAndHomeImprovement' => 'DIY & Tools',
            'ToysAndGames'            => 'Toys & Games',
            'VHS'                     => 'VHS',
            'VideoGames'              => 'PC & Video Games',
            'Watches'                 => 'Watches',
        ),
        'com'    => array(
            'All'                         => 'All Departments',
            'AmazonVideo'                 => 'Prime Video',
            'Apparel'                     => 'Clothing & Accessories',
            'Appliances'                  => 'Appliances',
            'ArtsAndCrafts'               => 'Arts, Crafts & Sewing',
            'Automotive'                  => 'Automotive Parts & Accessories',
            'Baby'                        => 'Baby',
            'Beauty'                      => 'Beauty & Personal Care',
            'Books'                       => 'Books',
            'Classical'                   => 'Classical',
            'Collectibles'                => 'Collectibles & Fine Art',
            'Computers'                   => 'Computers',
            'DigitalMusic'                => 'Digital Music',
            'DigitalEducationalResources' => 'Digital Educational Resources',
            'Electronics'                 => 'Electronics',
            'EverythingElse'              => 'Everything Else',
            'Fashion'                     => 'Clothing, Shoes & Jewelry',
            'FashionBaby'                 => 'Clothing, Shoes & Jewelry Baby',
            'FashionBoys'                 => 'Clothing, Shoes & Jewelry Boys',
            'FashionGirls'                => 'Clothing, Shoes & Jewelry Girls',
            'FashionMen'                  => 'Clothing, Shoes & Jewelry Men',
            'FashionWomen'                => 'Clothing, Shoes & Jewelry Women',
            'GardenAndOutdoor'            => 'Garden & Outdoor',
            'GiftCards'                   => 'Gift Cards',
            'GroceryAndGourmetFood'       => 'Grocery & Gourmet Food',
            'Handmade'                    => 'Handmade',
            'HealthPersonalCare'          => 'Health, Household & Baby Care',
            'HomeAndKitchen'              => 'Home & Kitchen',
            'Industrial'                  => 'Industrial & Scientific',
            'Jewelry'                     => 'Jewelry',
            'KindleStore'                 => 'Kindle Store',
            'LocalServices'               => 'Home & Business Services',
            'Luggage'                     => 'Luggage & Travel Gear',
            'LuxuryBeauty'                => 'Luxury Beauty',
            'Magazines	'                 => 'Magazine Subscriptions',
            'MobileAndAccessories'        => 'Cell Phones & Accessories',
            'MobileApps'                  => 'Apps & Gamess',
            'MoviesAndTV'                 => 'Movies & TV',
            'Music'                       => 'CDs & Vinyl',
            'MusicalInstruments'          => 'Musical Instruments',
            'OfficeProducts'              => 'Office Products',
            'PetSupplies'                 => 'Pet Supplies',
            'Photo'                       => 'Camera & Photo',
            'Shoes'                       => 'Shoes',
            'Software'                    => 'Software',
            'SportsAndOutdoors'           => 'Sports & Outdoors',
            'ToolsAndHomeImprovement'     => 'Tools & Home Improvement',
            'ToysAndGames'                => 'Toys & Games',
            'VHS'                         => 'VHS',
            'VideoGames'                  => 'Video Games',
            'Watches'                     => 'Watches',
        ),
    );

    return $cat;
}

/**
 * Add products search
 */
function wca_add_products_search_count() {
    $count = get_option( 'wca_products_search_count' );
    $count = $count + 1;
    update_option( 'wca_products_search_count', $count );
}

/**
 * Woocommerce plugin missing notices.
 */
function ams_woocommerce_missing() {
    $massing_text     = esc_html__('Affiliate Management System - WooCommerce Amazon requires WooCommerce to be installed and active. You can download', 'ams-wc-amazon' );
    $translators_text = sprintf( '<div class="error"><p><strong>%s <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">%s</a> here.</strong></p></div>', $massing_text, esc_html__( 'WooCommerce', 'ams-wc-amazon' ) );
    echo $translators_text ;
}

/**
 * License not activation notices
 */
function ams_plugin_license_active_massage() {
    $text = esc_html__( 'Affiliate Management System - WooCommerce Amazon plugin license not activated Please activate the plugin\'s license', 'ams-wc-amazon' );
    $contain = sprintf( '<div class="error"><p><strong>%s</strong></p></div>', $text );
    echo $contain;
}

/**
 * License status check
 */
function ams_plugin_license_status() {
    $status = get_option( 'ams_activated_status' );

    if ( strtolower( $status ) === strtolower( 'success' ) ) {
        return true;
    } else {
        return false;
    }
}

