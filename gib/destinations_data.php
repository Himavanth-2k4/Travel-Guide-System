<?php
require_once 'db_connect.php';

function isInWishlist($place_id, $user_id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND place_id = ?");
        $stmt->execute([$user_id, $place_id]);
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    } catch(Exception $e) {
        return false;
    }
}  
// Array of destinations with detailed information
$destinations = [
    // Heritage Sites
    [
        'id' => 1,
        'name' => 'Taj Mahal, Agra',
        'description' => 'Symbol of eternal love and one of the New Seven Wonders of the World',
        'image' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523',
        'category' => 'Heritage',
        'map_link' => 'https://www.google.com/maps/place/Taj+Mahal/@27.174245,78.0414359,17z/data=!4m6!3m5!1s0x39747121d702ff6d:0xdd2ae4803f767dde!8m2!3d27.1751448!4d78.0421422!16zL20vMGw4Y2I?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 2,
        'name' => 'Hawa Mahal, Jaipur',
        'description' => 'Palace of Winds with its unique honeycomb facade',
        'image' => 'https://images.unsplash.com/photo-1477587458883-47145ed94245',
        'category' => 'Heritage',
        'map_link' => 'https://www.google.com/maps/place/Hawa+Mahal/@26.9240458,75.8241395,17z/data=!3m1!4b1!4m6!3m5!1s0x396db14b1bd30ba5:0x860e5d531eccb20c!8m2!3d26.9240458!4d75.8267144!16zL20vMDZrN3Fj?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
    [
        'id' => 3,
        'name' => 'Hampi, Karnataka',
        'description' => 'Ancient ruins of the Vijayanagara Empire',
        'image' => 'https://karnatakatourism.org/wp-content/uploads/2020/05/Hampi.jpg',
        'category' => 'Heritage',
        'map_link' => 'https://satellites.pro/Google_plan/Hampi_map'
    ],
    [
        'id' => 4,
        'name' => 'Khajuraho Temples',
        'description' => 'Famous for their nagara-style architectural symbolism',
        'image' => 'https://s1.1zoom.me/b5453/970/India_Temples_Flowering_trees_Khajuraho_542965_1920x1080.jpg',
        'category' => 'Heritage',
        'map_link' => 'https://www.google.com/maps/place/Khajuraho+Western+Group+of+Temples/@24.8530929,79.9215707,15z/data=!4m2!3m1!1s0x3982e6018c847285:0x6a22e9146869df56'
    ],
   

    // Nature
    [
        'id' => 7,
        'name' => 'Valley of Flowers',
        'description' => 'Vibrant meadows with endemic alpine flowers',
        'image' => 'https://trekthehimalayas.com/images/ValleyofFlowersTrek/Slider/b3d630fb-3f9a-4cc6-9fef-1be72e135695_VOF.jpg',
        'category' => 'Nature',
        'map_link' => 'https://www.google.co.in/maps/place/Valley+of+Flowers+National+Park/@30.7274861,79.6047642,17z/data=!3m1!4b1!4m6!3m5!1s0x39a791153bd771ef:0x1f42050f9b6c125f!8m2!3d30.7274861!4d79.6073391!16zL20vMDNkMHg4?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
    [
        'id' => 8,
        'name' => 'Kaziranga National Park',
        'description' => 'Home to two-thirds of the world\'s one-horned rhinoceros',
        'image' => 'https://t4.ftcdn.net/jpg/03/15/84/67/360_F_315846750_IxIiSYob3gkC7BheqjhX1ZDarbvQ9uFH.jpg',
        'category' => 'Nature',
        'map_link' => 'https://kaziranga.nptr.in/'
    ],
    [
        'id' => 9,
        'name' => 'Sundarbans',
        'description' => 'Largest mangrove forest and home to Bengal tigers',
        'image' => 'https://asiainsurancepost.com/wp-content/uploads/2023/10/sunderbans.jpg',
        'category' => 'Nature',
        'map_link' => 'https://www.google.co.in/maps/place/Sundarbans/@22.0177662,88.6275125,9z/data=!3m1!4b1!4m6!3m5!1s0x3a004caac2c7b315:0x4716abcfbb16c93c!8m2!3d21.9497274!4d89.1833304!16zL20vMDU1Zzhq?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
    [
        'id' => 10,
        'name' => 'Thar Desert',
        'description' => 'Golden sand dunes and rich desert culture',
        'image' => 'https://img.veenaworld.com/wp-content/uploads/2018/06/1-cover-shutterstock_782705764-Camel-ride-on-the-sand-dunes-of-Thar-desert-Jaisalmer.jpg?imwidth=1300',
        'category' => 'Nature',
        'map_link' => 'https://www.google.co.in/maps/place/Thar+Desert/@26.8850367,68.6054176,7z/data=!3m1!4b1!4m6!3m5!1s0x39470bd5c24347d1:0x54f658627a3fb418!8m2!3d27.4694892!4d70.6216794!16zL20vMDduc3M?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
   

    // Spiritual
    [
        'id' => 13,
        'name' => 'Varanasi Ghats',
        'description' => 'Ancient spiritual city on the banks of Ganges',
        'image' => 'https://www.savaari.com/blog/wp-content/uploads/2023/09/Varanasi_ghats1.webp',
        'category' => 'Spiritual',
        'map_link' => 'https://www.google.co.in/maps/search/Varanasi+Ghats/@25.306846,83.0001155,14z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
    [
        'id' => 14,
        'name' => 'Golden Temple',
        'description' => 'Most important pilgrimage site of Sikhism',
        'image' => 'https://imgs.search.brave.com/5WzKa2pGLYDUqcMXld8VbaHYfo51bYGY7wPMqTZxtbo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzAwLzc4LzMyLzQx/LzM2MF9GXzc4MzI0/MTQ2X01RY0RIZHBF/bnlkU2Y3U2NyNVp5/VGJJdVU5Sjc3S0NK/LmpwZw',
        'category' => 'Spiritual',
        'map_link' => 'https://www.google.com/maps?ll=31.61998,74.876485&z=15&t=m&hl=en&gl=IN&mapclient=embed&cid=6936533577008293006'
    ],
    [
        'id' => 15,
        'name' => 'Bodh Gaya',
        'description' => 'Site where Buddha attained enlightenment',
        'image' => 'https://imgs.search.brave.com/vEN68ehXgGVHcD3xm3KpZ7G9SeiLGk5txntoO6gGdPk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zbWFy/dGhpc3Rvcnkub3Jn/L3dwLWNvbnRlbnQv/dXBsb2Fkcy8yMDIw/LzEwL0ZpZ182X3Rl/bXBsZS04NzB4NjUz/LmpwZw',
        'category' => 'Spiritual',
        'map_link' => 'https://www.google.co.in/maps/place/Bodh+Gaya,+Bihar/@24.6991562,84.9645964,14z/data=!3m1!4b1!4m6!3m5!1s0x39f32c5fbc12ed3d:0x9bbc5dccc57d96e!8m2!3d24.6961343!4d84.9869547!16zL20vMDFneXkw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
    [
        'id' => 16,
        'name' => 'Tirupati Temple',
        'description' => 'One of the most visited religious sites in the world',
        'image' => 'https://imgs.search.brave.com/R_6uEcVA8atos8-ihPK_TAZMXFlVQz2hOrGx3tUxNaQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/dGVtcGxlcHVyb2hp/dC5jb20vd3AtY29u/dGVudC91cGxvYWRz/LzIwMTUvMTIvNi5q/cGc',
        'category' => 'Spiritual',
        'map_link' => 'https://www.google.co.in/maps/search/Tirupati+Temple/@15.497524,76.355888,7z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
    ],
  

    // Adventure
    [
        'id' => 19,
        'name' => 'Chadar Trek',
        'description' => 'Trek on the frozen Zanskar River',
        'image' => 'https://imgs.search.brave.com/-te8M1nV2mxWAvNpxmywL_TWewuS2PtR_Ys8KDxdAfA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mYXJt/Ni5zdGF0aWMuZmxp/Y2tyLmNvbS81MDkz/LzU0MjczNDI3MDNf/ZmUxZDhlN2MwMy5q/cGc',
        'category' => 'Adventure',
        'map_link' => 'https://www.google.co.in/maps/place/Chadar+Trek+-+The+Frozen+Zanskar+River+Adventure/@34.1623771,77.5790746,17z/data=!3m1!4b1!4m6!3m5!1s0x38fdeb3011e21813:0x532c1458f6469c60!8m2!3d34.1623728!4d77.5839455!16s%2Fg%2F11h5p7s9tw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 20,
        'name' => 'Rishikesh',
        'description' => 'White water rafting and bungee jumping',
        'image' => 'https://imgs.search.brave.com/_EKq5sBTDL2TDlmfeFe9309cEAt4TwOviA61uk1MJpc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1waG90/by9yaXNoaWtlc2gt/aW5kaWFfNzgzNjEt/MjYwNC5qcGc_c2Vt/dD1haXNfaHlicmlk',
        'category' => 'Adventure',
        'map_link' => 'https://www.google.co.in/maps/place/Rishikesh,+Uttarakhand/@30.0877466,78.2294089,13z/data=!4m6!3m5!1s0x39093e67cf93f111:0xcc78804a6f941bfe!8m2!3d30.1157619!4d78.2853017!16zL20vMGNjdHZz?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 21,
        'name' => 'Bir Billing',
        'description' => 'Paragliding paradise in Himachal Pradesh',
        'image' => 'https://imgs.search.brave.com/icqQ7l6pIE6mObA525Zo_sNelOuuoHgHlTI-SvyaBIY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YTEudGhyaWxsb3Bo/aWxpYS5jb20vZmls/ZXN0b3JlL3B5NWJq/a2VlZHBqczJiZGo2/MDM0YnRjMjQ1dmhf/MTU3MTQ4NTI0M18x/MzYyMDczOF8xMDc2/NzM4NDQ1NzI2MDY0/Xzg1OTMxMzIzOTIz/NjY5NzQ3NjVfbi5q/cGc_dz03NTMmaD00/NTAmZHBy',
        'category' => 'Adventure',
        'map_link' => 'https://www.google.co.in/maps/place/Bir,+Himachal+Pradesh/@32.0441502,76.7184198,16z/data=!4m6!3m5!1s0x3904b8cf2c5ca823:0x13fc1b0578356ada!8m2!3d32.0456253!4d76.7235513!16s%2Fm%2F065yy53?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 22,
        'name' => 'Andaman Islands',
        'description' => 'Scuba diving and snorkeling',
        'image' => 'https://imgs.search.brave.com/25iXP_WdrqELWFU3qBIfvdAw-rbzfGduxda7fXINApQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzL2E0LzRl/LzMxL2E0NGUzMWZk/NjVmM2IwOWJmYzMw/YzI3ZTVmMTgxYWQ3/LmpwZw',
        'category' => 'Adventure',
        'map_link' => 'https://www.google.co.in/maps/place/Andaman+and+Nicobar+Islands/@10.2097024,90.5889873,7z/data=!3m1!4b1!4m6!3m5!1s0x3064a00f2b650ff3:0xce80055648fccb2c!8m2!3d10.7448873!4d92.4999918!16zL20vMGN2dmM?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
  

    // Beach
    [
        'id' => 25,
        'name' => 'Radhanagar Beach',
        'description' => 'One of Asia\'s best beaches in Andaman',
        'image' => 'https://imgs.search.brave.com/bvg6_izDQUU5heD21wETAH8p3PYzGAHYWq5T1WEwj1I/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/cHJvZC53ZWJzaXRl/LWZpbGVzLmNvbS81/YjU2MzE5OTcxYWM4/Yzc0NzVhOWQ4Nzcv/NWVlNDgxMDhmM2Vm/YzEwZmUxZTYzMjdl/XzIwMTkwNjEwXzEy/MTcwOC0uanBn',
        'category' => 'Beach',
        'map_link' => 'https://www.google.com/maps/place/Radhanagar+Beach/@11.9830887,92.9381495,15z/data=!3m1!4b1!4m6!3m5!1s0x3088d212164bb773:0x9715637d9a7265b3!8m2!3d11.9844552!4d92.9508454!16s%2Fg%2F1hc18h9zw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 26,
        'name' => 'Palolem Beach',
        'description' => 'Pristine crescent beach in South Goa',
        'image' => 'https://imgs.search.brave.com/tBZd7fjkPxetXY661ajoh2gA4G7tXSgMJGEy_bFSiQo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pMC53/cC5jb20vdHJvdC53/b3JsZC93cC1jb250/ZW50L3VwbG9hZHMv/MjAyMC8wNy9EU0Nf/MDY4M1VzZS10aGlz/LmpwZz9yZXNpemU9/Njk3LDQ2NSZzc2w9/MQ',
        'category' => 'Beach',
        'map_link' => 'https://www.google.com/maps/place/Palolem+Beach/@15.0093045,74.0162473,16z/data=!3m1!4b1!4m6!3m5!1s0x3bbe4551d05b02bb:0x1e1bc67d4b0fbbf5!8m2!3d15.0099648!4d74.0232186!16zL20vMDgxZ3B3?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 27,
        'name' => 'Varkala Beach',
        'description' => 'Dramatic cliffs and golden sands in Kerala',
        'image' => 'https://imgs.search.brave.com/qxk5YzdwZClhHaA_l6aBjFxjAYQYWtkjblSh0iLCous/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pcmlz/aG9saWRheXMuY29t/L2JhY2tlbmQvd2Vi/L2Rlc3RpbmF0aW9u/LWRldGFpbHMvdmFy/a2FsYS1iZWFjaC1j/bGlmZjA3LTE1Njc0/MzE5OTQuanBn',
        'category' => 'Beach',
        'map_link' => 'https://www.google.com/maps/place/Varkala+Beach/@8.7347976,76.7008245,17z/data=!3m1!4b1!4m6!3m5!1s0x3b05ef1c356e35d9:0x529a20f7e0453699!8m2!3d8.7355515!4d76.7031667!16s%2Fm%2F0bb_279?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
    [
        'id' => 28,
        'name' => 'Om Beach',
        'description' => 'Natural Om-shaped beach in Karnataka',
        'image' => 'https://imgs.search.brave.com/7KfqP7iHOjc5WWqUGc-eQIimfK1eRm6pN024bfiQ_qw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvNDY1/NjQ0MTkzL3Bob3Rv/L29tLWJlYWNoLmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1u/ZmQ5cjEzWkNQdFd6/NW1Ha3NEdUc1TlNy/cVpmdE0tOVhDaUo4/aHVzQUpRPQ',
        'category' => 'Beach',
        'map_link' => 'https://www.google.com/maps/place/Om+Beach/@14.5188323,74.3203131,17z/data=!3m1!4b1!4m6!3m5!1s0x3bbe8218126fad05:0x294f4f7ab4235873!8m2!3d14.5192405!4d74.3230039!16s%2Fg%2F1vlqnnmz?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
    ],
  
];


?>