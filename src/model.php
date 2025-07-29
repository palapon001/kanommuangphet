<?php
$modelIndex = [
    'th' => [
        'navbar_brand_text' => 'ขนมเมืองเพชร',
        'description' => '',
        'search_placeholder_text' => 'ค้นหาสินค้า...',
        'search_text' => 'ค้นหา',
        'login_button' => 'เข้าสู่ระบบ',
        'product_hit_text' => 'สินค้าขายดี',
        'product_sale_text' => 'สินค้าลดราคา',
        'blog_text' => 'บทความและข่าวสาร',
        'copyright' => '© 2025 Kanom Muang Phet - เว็บไซต์เนื้อหาเกี่ยวกับขนมไทยโดยเฉพาะ'
    ],
    'en' => [
        'navbar_brand_text' => 'Kanom Muang Phet',
        'description' => '',
        'search_placeholder_text' => 'Search for products...',
        'search_text' => 'Search',
        'login_button' => 'Login',
        'product_hit_text' => 'Best Sellers',
        'product_sale_text' => 'On Sale',
        'blog_text' => 'Articles & News',
        'copyright' => '© 2025 Kanom Muang Phet - A website dedicated to authentic Thai desserts.'
    ]
];


$menuBottom = [
    'th' => [
        "ขนมไทยโบราณ" => ["ฝอยทอง", "ทองหยิบ", "ขนมชั้น", "ขนมตาล"],
        "ขนมไทยหวาน" => ["ข้าวเหนียวมูน", "กล้วยบวชชี", "บัวลอย"],
        "ขนมไทยทอด" => ["ทองม้วน", "ช่อม่วง", "ขนมไข่ปลา"],
        "ขนมไทยนึ่ง" => ["ขนมถ้วย", "ขนมฟักทอง", "ขนมต้ม"],
        "ขนมไทยอบ" => ["ขนมหม้อแกง", "ขนมเปียกปูน"],
        "ขนมไทยแบบใหม่" => ["ขนมปังสังขยา", "ขนมเค้กไทย"],
        "เครื่องดื่มไทย" => ["น้ำกระเจี๊ยบ", "น้ำเก๊กฮวย"],
        "วัตถุดิบขนมไทย" => ["แป้ง", "กะทิ", "น้ำตาลปี๊บ"],
        "โปรโมชั่น" => []
    ],
    'en' => [
        "Ancient Thai Desserts" => ["Foi Thong", "Thong Yip", "Khanom Chan", "Khanom Tan"],
        "Sweet Thai Desserts" => ["Sticky Rice with Coconut Milk", "Banana in Coconut Milk", "Bua Loi"],
        "Fried Thai Desserts" => ["Thong Muan", "Chor Muang", "Khanom Khai Pla"],
        "Steamed Thai Desserts" => ["Khanom Thuai", "Pumpkin Dessert", "Khanom Tom"],
        "Baked Thai Desserts" => ["Khanom Mo Kaeng", "Piek Poon"],
        "Modern Thai Desserts" => ["Custard Bread", "Thai Cake"],
        "Thai Drinks" => ["Roselle Juice", "Chrysanthemum Tea"],
        "Thai Dessert Ingredients" => ["Flour", "Coconut Milk", "Palm Sugar"],
        "Promotions" => []
    ]
];

// ข้อมูลสินค้า
$productsHit = [
    ["title" => "ข้าวเหนียวมะม่วง", "price" => "ลดเหลือ 99 บาท", "img" => ""],
    ["title" => "ทองหยิบ", "price" => "ลดเหลือ 150 บาท", "img" => ""],
    ["title" => "ฝอยทอง", "price" => "ลดเหลือ 120 บาท", "img" => ""],
    ["title" => "วุ้นกะทิ", "price" => "ลดเหลือ 200 บาท", "img" => ""],
    ["title" => "ขนมตาล", "price" => "ลดเหลือ 250 บาท", "img" => ""],
    ["title" => "ขนมถ้วย", "price" => "ลดเหลือ 300 บาท", "img" => ""],
    ["title" => "ขนมใส่ไส้", "price" => "ลดเหลือ 180 บาท", "img" => ""],
    ["title" => "ขนมชั้น", "price" => "ลดเหลือ 220 บาท", "img" => ""],
    ["title" => "ลอดช่อง", "price" => "ลดเหลือ 140 บาท", "img" => ""],
    ["title" => "ขนมหม้อแกง", "price" => "ลดเหลือ 160 บาท", "img" => ""],
];

$productsSale = [
    ["title" => "ขนมเปียกปูน", "price" => "ลดเหลือ 199 บาท", "img" => ""],
    ["title" => "ขนมเบื้อง", "price" => "ลดเหลือ 250 บาท", "img" => ""],
    ["title" => "ขนมถังแตก", "price" => "ลดเหลือ 220 บาท", "img" => ""],
    ["title" => "กล้วยบวชชี", "price" => "ลดเหลือ 300 บาท", "img" => ""],
    ["title" => "มันเชื่อม", "price" => "ลดเหลือ 350 บาท", "img" => ""],
    ["title" => "กล้วยแขก", "price" => "ลดเหลือ 180 บาท", "img" => ""],
    ["title" => "บัวลอย", "price" => "ลดเหลือ 220 บาท", "img" => ""],
    ["title" => "ขนมปังสังขยา", "price" => "ลดเหลือ 260 บาท", "img" => ""],
    ["title" => "ลูกชุบ", "price" => "ลดเหลือ 240 บาท", "img" => ""],
    ["title" => "แตงไทยน้ำกะทิ", "price" => "ลดเหลือ 280 บาท", "img" => ""],
];

$blogs = [
    [
        "title" => "เคล็ดลับการเลือกวัตถุดิบสดใหม่",
        "desc" => "เรียนรู้วิธีเลือกวัตถุดิบสดใหม่สำหรับทำขนมไทยอย่างมือโปร พร้อมเทคนิคเก็บรักษาคุณภาพให้คงทน",
        "img" => "https://placehold.co/400x250?text=Blog+Image+1",
        "link" => "#"
    ],
    [
        "title" => "สูตรขนมไทยยอดนิยมประจำปี 2025",
        "desc" => "รวมสูตรขนมไทยยอดนิยมที่ขายดีในตลาด พร้อมขั้นตอนทำง่ายๆ สำหรับผู้เริ่มต้น",
        "img" => "https://placehold.co/400x250?text=Blog+Image+2",
        "link" => "#"
    ],
    [
        "title" => "วิธีเก็บรักษาวัตถุดิบให้สดได้นานขึ้น",
        "desc" => "เทคนิคและวิธีการเก็บรักษาวัตถุดิบสำหรับร้านขนม เพื่อความสดใหม่และประหยัดต้นทุน",
        "img" => "https://placehold.co/400x250?text=Blog+Image+3",
        "link" => "#"
    ],
    [
        "title" => "เทรนด์ขนมไทยปี 2025",
        "desc" => "ติดตามเทรนด์และไอเดียใหม่ๆ ในวงการขนมไทย ที่กำลังมาแรงและสร้างรายได้ดี",
        "img" => "https://placehold.co/400x250?text=Blog+Image+4",
        "link" => "#"
    ]
];

// โฆษณา (ads) mockup
$ads = [
    // [
    //     "img" => "https://placehold.co/1200x200?text=Ad+Banner+1",
    //     "link" => "#"
    // ],
    // [
    //     "img" => "https://placehold.co/1200x200?text=Ad+Banner+2",
    //     "link" => "#"
    // ],
];

$footerSections = [
    'th' => [
        [
            "title" => "ขนมเมืองเพชร",
            "content" => [
                "text" => "เว็บไซต์ขนมไทยแท้ๆ สั่งง่าย แค่ปลายนิ้ว",
                "contacts" => [
                    "โทร" => "02 023 9903 บริการ 24 ชั่วโมง",
                    "Line Official" => "@kanommuangphet",
                    "เวลาทำการ" => "07:00 น. - 16:00 น"
                ]
            ]
        ],
        [
            "title" => "นโยบายเว็บไซต์",
            "links" => [
                ["text" => "เกี่ยวกับเรา", "url" => "#"],
                ["text" => "เงื่อนไขการให้บริการ", "url" => "#", "target" => "_blank"],
                ["text" => "นโยบายความเป็นส่วนตัว", "url" => "#", "target" => "_blank"],
                ["text" => "คำถามที่พบบ่อย", "url" => "#", "target" => "_blank"]
            ]
        ],
        [
            "title" => "ร่วมเป็นส่วนหนึ่งกับเรา",
            "links" => [
                ["text" => "วิธีสมัครสมาชิก", "url" => "#"],
                ["text" => "วิธีการสร้างสินค้า", "url" => "#", "target" => "_blank"],
                ["text" => "วิธีการใช้ระบบ", "url" => "#", "target" => "_blank"]
            ]
        ],
        // [
        //     "title" => "ดาวน์โหลดแอป Kanom Muang Phet",
        //     "images" => [
        //         ["src" => "/images/appstore.png", "alt" => "App Store"],
        //         ["src" => "/images/playstore.png", "alt" => "Google Play"]
        //     ]
        // ],
        [
            "title" => "ติดตามเรา",
            "socials" => [
                ["href" => "#", "icon" => "fab fa-facebook-f", "title" => "Facebook"],
                ["href" => "#", "icon" => "fab fa-instagram", "title" => "Instagram"],
                ["href" => "#", "icon" => "fab fa-youtube", "title" => "YouTube"],
                ["href" => "#", "icon" => "fab fa-tiktok", "title" => "TikTok"],
                ["href" => "#", "icon" => "fab fa-line", "title" => "Line"]
            ]
        ]
    ],
    'en' => [
        [
            "title" => "Kanom Muang Phet",
            "content" => [
                "text" => "Authentic Thai desserts, just a click away",
                "contacts" => [
                    "Phone" => "02 023 9903 (24-hour service)",
                    "Line Official" => "@kanommuangphet",
                    "Business Hours" => "07:00 AM - 04:00 PM"
                ]
            ]
        ],
        [
            "title" => "Website Policies",
            "links" => [
                ["text" => "About Us", "url" => "#"],
                ["text" => "Terms of Service", "url" => "#", "target" => "_blank"],
                ["text" => "Privacy Policy", "url" => "#", "target" => "_blank"],
                ["text" => "FAQs", "url" => "#", "target" => "_blank"]
            ]
        ],
        [
            "title" => "Join Us",
            "links" => [
                ["text" => "How to Register", "url" => "#"],
                ["text" => "How to Add Products", "url" => "#", "target" => "_blank"],
                ["text" => "How to Use the System", "url" => "#", "target" => "_blank"]
            ]
        ],
        // [
        //     "title" => "Download Kanom Muang Phet App",
        //     "images" => [
        //         ["src" => "/images/appstore.png", "alt" => "App Store"],
        //         ["src" => "/images/playstore.png", "alt" => "Google Play"]
        //     ]
        // ],
        [
            "title" => "Follow Us",
            "socials" => [
                ["href" => "#", "icon" => "fab fa-facebook-f", "title" => "Facebook"],
                ["href" => "#", "icon" => "fab fa-instagram", "title" => "Instagram"],
                ["href" => "#", "icon" => "fab fa-youtube", "title" => "YouTube"],
                ["href" => "#", "icon" => "fab fa-tiktok", "title" => "TikTok"],
                ["href" => "#", "icon" => "fab fa-line", "title" => "Line"]
            ]
        ]
    ]
];
