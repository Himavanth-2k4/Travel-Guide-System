<?php
require_once 'db_connect.php';

function sInWishlist($place_id, $user_id) {
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
   // Heritage Sites
   [
    'id' => 1,
    'name' => 'Taj Mahal, Agra',
    'description' => 'Symbol of eternal love and one of the New Seven Wonders of the World',
    'image' => 'https://images.unsplash.com/photo-1564507592333-c60657eea523',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/maps/about/behind-the-scenes/streetview/treks/taj-mahal/'
],
[
    'id' => 2,
    'name' => 'Hawa Mahal, Jaipur',
    'description' => 'Palace of Winds with its unique honeycomb facade',
    'image' => 'https://images.unsplash.com/photo-1477587458883-47145ed94245',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/url?q=https://www.hawa-mahal.com/&opi=69817538&sa=U&ved=0ahUKEwjGn_78q9eMAxWcyDgGHT2VKqUQ61gIFCgQ&usg=AOvVaw0d3UU-_0W9D5VMT_AdnuKO'
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
[
    'id' => 5,
    'name' => 'Konark Sun Temple',
    'description' => 'Magnificent 13th-century temple dedicated to the Sun God',
    'image' => 'https://indiaholidaymall.com/images/blog/Sun-Temple-Konark.jpg',
    'category' => 'Heritage',
    'map_link' => 'https://virtualglobetrotting.com/map/konark-sun-temple/view/google/'
],
[
    'id' => 6,
    'name' => 'Ajanta Caves',
    'description' => 'Ancient Buddhist monasteries with remarkable paintings',
    'image' => 'https://www.steppestravel.com/app/uploads/2019/12/Local-Women-Ajanta-Caves-India.jpg',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/maps/place/Ajanta+Caves/@20.5513286,75.7069356,15z/data=!4m2!3m1!1s0x3bd97f7014a75e43:0x7ca8d7c57639691f'
],
 // Additional Heritage Sites
 [
    'id' => 7,
    'name' => 'Ellora Caves',
    'description' => 'Ancient cave temples showcasing Buddhist, Hindu and Jain monuments',
    'image' => 'https://s7ap1.scene7.com/is/image/incredibleindia/ellora-caves-chhatrapati-sambhaji-nagar-maharashtra-attr-hero-5?qlt=82&ts=1727010646173',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/maps/place/Ellora+Caves/@20.0267844,75.1770869,15z/data=!4m2!3m1!1s0x3bdb93bd138ae4bd:0x574c6482cf0b89cf'
],
[
    'id' => 8,
    'name' => 'Mysore Palace',
    'description' => 'Historical royal residence known for its Indo-Saracenic architecture',
    'image' => 'https://dwq3yv87q1b43.cloudfront.net/public/blogs/fit-in/1200x675/Blog_20241130-237756404-1732944745.jpg',
    'category' => 'Heritage',
    'map_link' => 'https://virtualglobetrotting.com/map/mysore-palace/view/google/'
],
[
    'id' => 9,
    'name' => 'Red Fort, Delhi',
    'description' => 'UNESCO World Heritage Site and symbol of India\'s sovereignty',
    'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ee/Redfort.JPG/1200px-Redfort.JPG',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/maps/place/Red+Fort/@28.6561592,77.2410203,15z/data=!4m2!3m1!1s0x390cfce26ec085ef:0x441e32f4fa5002fb'
],
[
    'id' => 10,
    'name' => 'Qutub Minar',
    'description' => 'Tallest brick minaret in the world and an UNESCO World Heritage Site',
    'image' => 'https://www.thedelhitours.com/blog/wp-content/uploads/2024/09/Qutub-Minar-750x564.jpg',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/url?q=http://www.delhitourism.gov.in/delhitourism/tourist_place/qutab_minar.jsp&opi=69817538&sa=U&ved=0ahUKEwiX9N-4rdeMAxXQzDgGHTU2Hn0Q61gIEigO&usg=AOvVaw2McuVKMvntuVRQgyHMe3wH'
],
[
    'id' => 11,
    'name' => 'Chittorgarh Fort',
    'description' => 'Largest fort in India representing the Rajput pride and valor',
    'image' => 'https://static.toiimg.com/thumb/50900339.cms?resizemode=75&width=1200&height=900',
    'category' => 'Heritage',
    'map_link' => 'https://virtualglobetrotting.com/map/chittor-fort/view/google/'
],
[
    'id' => 12,
    'name' => 'Meenakshi Temple',
    'description' => 'Historic Hindu temple with 14 gateway towers covered in colorful sculptures',
    'image' => 'https://www.tamilnadutourism.tn.gov.in/img/pages/large-desktop/meenakshi-amman-temple-1656170467_cfebe78d69f069f881aa.webp',
    'category' => 'Heritage',
    'map_link' => 'https://www.google.com/maps/place/Meenakshi+Amman+Temple/@9.9195045,78.1193418,15z/data=!4m2!3m1!1s0x3b00c58461e46987:0xf134621ce5286703'
],

// Nature
[
    'id' => 13,
    'name' => 'Valley of Flowers',
    'description' => 'Vibrant meadows with endemic alpine flowers',
    'image' => 'https://trekthehimalayas.com/images/ValleyofFlowersTrek/Slider/b3d630fb-3f9a-4cc6-9fef-1be72e135695_VOF.jpg',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Valley+of+Flowers+National+Park/@30.7274861,79.6047642,17z/data=!3m1!4b1!4m6!3m5!1s0x39a791153bd771ef:0x1f42050f9b6c125f!8m2!3d30.7274861!4d79.6073391!16zL20vMDNkMHg4?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 14,
    'name' => 'Kaziranga National Park',
    'description' => 'Home to two-thirds of the world\'s one-horned rhinoceros',
    'image' => 'https://t4.ftcdn.net/jpg/03/15/84/67/360_F_315846750_IxIiSYob3gkC7BheqjhX1ZDarbvQ9uFH.jpg',
    'category' => 'Nature',
    'map_link' => 'https://kaziranga.nptr.in/'
],
[
    'id' => 15,
    'name' => 'Sundarbans',
    'description' => 'Largest mangrove forest and home to Bengal tigers',
    'image' => 'https://asiainsurancepost.com/wp-content/uploads/2023/10/sunderbans.jpg',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Sundarbans/@22.0177662,88.6275125,9z/data=!3m1!4b1!4m6!3m5!1s0x3a004caac2c7b315:0x4716abcfbb16c93c!8m2!3d21.9497274!4d89.1833304!16zL20vMDU1Zzhq?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 16,
    'name' => 'Thar Desert',
    'description' => 'Golden sand dunes and rich desert culture',
    'image' => 'https://img.veenaworld.com/wp-content/uploads/2018/06/1-cover-shutterstock_782705764-Camel-ride-on-the-sand-dunes-of-Thar-desert-Jaisalmer.jpg?imwidth=1300',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Thar+Desert/@26.8850367,68.6054176,7z/data=!3m1!4b1!4m6!3m5!1s0x39470bd5c24347d1:0x54f658627a3fb418!8m2!3d27.4694892!4d70.6216794!16zL20vMDduc3M?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 17,
    'name' => 'Silent Valley',
    'description' => 'Pristine rainforest ecosystem in Kerala',
    'image' => 'https://www.tamilnadutourism.tn.gov.in/img/pages/large-desktop/silent-valley-view-1678282213_989bd8b52f9f65fb8c4b.webp',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Silent+Valley+National+Park/@11.0694222,76.3517847,13z/data=!4m10!1m2!2m1!1sSilent+Valley!3m6!1s0x3ba62af225691cfd:0xf9e2a321ac787017!8m2!3d11.0694222!4d76.4280024!15sCg1TaWxlbnQgVmFsbGV5Wg8iDXNpbGVudCB2YWxsZXmSAQ1uYXRpb25hbF9wYXJr4AEA!16zL20vMDYycjM3?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 18,
    'name' => 'Nohkalikai Falls',
    'description' => 'India\'s tallest plunge waterfall',
    'image' => 'https://www.oyorooms.com/travel-guide/wp-content/uploads/2019/06/Nohsngithiang-Falls.webp',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/NohKaLikai+Falls/@25.2754719,91.6835928,17z/data=!3m1!4b1!4m6!3m5!1s0x37508d4d52a66213:0x1e4a36d9696f9c41!8m2!3d25.2754203!4d91.6859562!16s%2Fm%2F03gwzx9?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
 // Additional Nature Sites
 [
    'id' => 19,
    'name' => 'Coorg, Karnataka',
    'description' => 'Lush hill station known for coffee plantations and misty landscapes',
    'image' => 'https://coorgtourism.co.in/images/coorg-places/places-to-visit-coorg-tour-local-sightseeing-tour-package-karada-village-coorg.jpg',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Madikeri,+Karnataka+571201/@12.4314273,75.7142703,14z/data=!3m1!4b1!4m6!3m5!1s0x3ba50075627a7fff:0xaf8a66ea4651c1a6!8m2!3d12.4244205!4d75.7381856!16zL20vMDF4OWJ4?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 20,
    'name' => 'Dzukou Valley',
    'description' => 'Known as the "Valley of Flowers of the North East" with rare lilies',
    'image' => 'https://static.toiimg.com/photo/97834460.cms',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Dzukou+Valley/@25.5553627,94.0627038,17z/data=!3m1!4b1!4m6!3m5!1s0x37489f5930712773:0xcff1a7f40e50782c!8m2!3d25.5553627!4d94.0652787!16s%2Fg%2F11fkf4w59s?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 21,
    'name' => 'Dudhsagar Falls',
    'description' => 'Four-tiered waterfall among the tallest in India',
    'image' => 'https://media1.thrillophilia.com/filestore/a7d6z5abld6isf67zp6s3l4jonvh_1575223660_39273478275_c9009ee6bd_h.jpg',
    'category' => 'Nature',
    'map_link' => 'https://www.goa.gov.in/places/dudhsagar-waterfalls/'
],
[
    'id' => 22,
    'name' => 'Hemis National Park',
    'description' => 'Home to snow leopards and diverse high-altitude wildlife',
    'image' => 'https://s7ap1.scene7.com/is/image/incredibleindia/hemis-national-park-leh-ladakh-1-attr-hero?qlt=82&ts=1726667915652',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Hemis+National+Park/@33.7187211,77.3856475,17z/data=!3m1!4b1!4m6!3m5!1s0x3902707b177afb8b:0xf74ea9833e5618e2!8m2!3d33.7187211!4d77.3882224!16zL20vMDhfdndi?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 23,
    'name' => 'Majuli Island',
    'description' => 'World\'s largest river island in the Brahmaputra',
    'image' => 'https://static.toiimg.com/photo/64012913.cms',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Majuli/@27.0073917,94.029863,11z/data=!3m1!4b1!4m6!3m5!1s0x3746c41068c5707f:0x3dd7532bf70e8c60!8m2!3d27.0016172!4d94.2242981!16zL20vMDNsY3B4?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 24,
    'name' => 'Pangong Lake',
    'description' => 'Stunning high-altitude lake that changes colors throughout the day',
    'image' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86',
    'category' => 'Nature',
    'map_link' => 'https://www.google.co.in/maps/place/Pangong+Tso/@33.8219595,78.5587784,11z/data=!3m1!4b1!4m6!3m5!1s0x39002d69b6082a97:0xb7ba17e3c8c016a9!8m2!3d33.7595131!4d78.6674404!16zL20vMDY0eG5m?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],

// Spiritual
[
    'id' => 25,
    'name' => 'Varanasi Ghats',
    'description' => 'Ancient spiritual city on the banks of Ganges',
    'image' => 'https://www.savaari.com/blog/wp-content/uploads/2023/09/Varanasi_ghats1.webp',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/search/Varanasi+Ghats/@25.306846,83.0001155,14z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 26,
    'name' => 'Golden Temple',
    'description' => 'Most important pilgrimage site of Sikhism',
    'image' => 'https://imgs.search.brave.com/5WzKa2pGLYDUqcMXld8VbaHYfo51bYGY7wPMqTZxtbo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90NC5m/dGNkbi5uZXQvanBn/LzAwLzc4LzMyLzQx/LzM2MF9GXzc4MzI0/MTQ2X01RY0RIZHBF/bnlkU2Y3U2NyNVp5/VGJJdVU5Sjc3S0NK/LmpwZw',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.com/maps?ll=31.61998,74.876485&z=15&t=m&hl=en&gl=IN&mapclient=embed&cid=6936533577008293006'
],
[
    'id' => 27,
    'name' => 'Bodh Gaya',
    'description' => 'Site where Buddha attained enlightenment',
    'image' => 'https://imgs.search.brave.com/vEN68ehXgGVHcD3xm3KpZ7G9SeiLGk5txntoO6gGdPk/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9zbWFy/dGhpc3Rvcnkub3Jn/L3dwLWNvbnRlbnQv/dXBsb2Fkcy8yMDIw/LzEwL0ZpZ182X3Rl/bXBsZS04NzB4NjUz/LmpwZw',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/place/Bodh+Gaya,+Bihar/@24.6991562,84.9645964,14z/data=!3m1!4b1!4m6!3m5!1s0x39f32c5fbc12ed3d:0x9bbc5dccc57d96e!8m2!3d24.6961343!4d84.9869547!16zL20vMDFneXkw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 28,
    'name' => 'Tirupati Temple',
    'description' => 'One of the most visited religious sites in the world',
    'image' => 'https://imgs.search.brave.com/R_6uEcVA8atos8-ihPK_TAZMXFlVQz2hOrGx3tUxNaQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/dGVtcGxlcHVyb2hp/dC5jb20vd3AtY29u/dGVudC91cGxvYWRz/LzIwMTUvMTIvNi5q/cGc',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/search/Tirupati+Temple/@15.497524,76.355888,7z/data=!3m1!4b1?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 29,
    'name' => 'Ajmer Sharif',
    'description' => 'Revered Sufi shrine',
    'image' => 'https://imgs.search.brave.com/Dn1LPGsGEkJmRMjEdTtvbMTNOxul308uixDSYikVGMw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93YWxs/cGFwZXJjYXZlLmNv/bS93cC93cDQ1ODQy/NDEuanBn',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/place/Ajmer+Sharif/@26.4571069,74.6296695,17z/data=!4m10!1m2!2m1!1sAjmer+Sharif!3m6!1s0x396be7c96c9a3e6d:0xb262ec215faff2a9!8m2!3d26.458047!4d74.6363026!15sCgxBam1lciBTaGFyaWaSAQZzaHJpbmXgAQA!16s%2Fg%2F11q43z2v78?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNjQwSAFQAw%3D%3D'
],
[
    'id' => 30,
    'name' => 'Kedarnath Temple',
    'description' => 'Ancient temple in the Himalayas',
    'image' => 'https://imgs.search.brave.com/g1ygcPBx5J6CyVFgeM8buEkoDQAAbS4gGyqVqO5cVz0/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1waG90/by90cmFkaXRpb25h/bC1idWlsZGluZy1h/Z2FpbnN0LXNreV8x/MDQ4OTQ0LTE4NDg0/NDkwLmpwZz9zZW10/PWFpc19oeWJyaWQ',
    'category' => 'Spiritual',
    'map_link' => 'https://badrinath-kedarnath.gov.in/'
],

// Adventure
[
    'id' => 31,
    'name' => 'Chadar Trek',
    'description' => 'Trek on the frozen Zanskar River',
    'image' => 'https://imgs.search.brave.com/-te8M1nV2mxWAvNpxmywL_TWewuS2PtR_Ys8KDxdAfA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9mYXJt/Ni5zdGF0aWMuZmxp/Y2tyLmNvbS81MDkz/LzU0MjczNDI3MDNf/ZmUxZDhlN2MwMy5q/cGc',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.co.in/maps/place/Chadar+Trek+-+The+Frozen+Zanskar+River+Adventure/@34.1623771,77.5790746,17z/data=!3m1!4b1!4m6!3m5!1s0x38fdeb3011e21813:0x532c1458f6469c60!8m2!3d34.1623728!4d77.5839455!16s%2Fg%2F11h5p7s9tw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 32,
    'name' => 'Rishikesh',
    'description' => 'White water rafting and bungee jumping',
    'image' => 'https://imgs.search.brave.com/_EKq5sBTDL2TDlmfeFe9309cEAt4TwOviA61uk1MJpc/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pbWcu/ZnJlZXBpay5jb20v/cHJlbWl1bS1waG90/by9yaXNoaWtlc2gt/aW5kaWFfNzgzNjEt/MjYwNC5qcGc_c2Vt/dD1haXNfaHlicmlk',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.co.in/maps/place/Rishikesh,+Uttarakhand/@30.0877466,78.2294089,13z/data=!4m6!3m5!1s0x39093e67cf93f111:0xcc78804a6f941bfe!8m2!3d30.1157619!4d78.2853017!16zL20vMGNjdHZz?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 33,
    'name' => 'Bir Billing',
    'description' => 'Paragliding paradise in Himachal Pradesh',
    'image' => 'https://imgs.search.brave.com/icqQ7l6pIE6mObA525Zo_sNelOuuoHgHlTI-SvyaBIY/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YTEudGhyaWxsb3Bo/aWxpYS5jb20vZmls/ZXN0b3JlL3B5NWJq/a2VlZHBqczJiZGo2/MDM0YnRjMjQ1dmhf/MTU3MTQ4NTI0M18x/MzYyMDczOF8xMDc2/NzM4NDQ1NzI2MDY0/Xzg1OTMxMzIzOTIz/NjY5NzQ3NjVfbi5q/cGc_dz03NTMmaD00/NTAmZHBy',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.co.in/maps/place/Bir,+Himachal+Pradesh/@32.0441502,76.7184198,16z/data=!4m6!3m5!1s0x3904b8cf2c5ca823:0x13fc1b0578356ada!8m2!3d32.0456253!4d76.7235513!16s%2Fm%2F065yy53?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 34,
    'name' => 'Andaman Islands',
    'description' => 'Scuba diving and snorkeling',
    'image' => 'https://imgs.search.brave.com/25iXP_WdrqELWFU3qBIfvdAw-rbzfGduxda7fXINApQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pLnBp/bmltZy5jb20vb3Jp/Z2luYWxzL2E0LzRl/LzMxL2E0NGUzMWZk/NjVmM2IwOWJmYzMw/YzI3ZTVmMTgxYWQ3/LmpwZw',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.co.in/maps/place/Andaman+and+Nicobar+Islands/@10.2097024,90.5889873,7z/data=!3m1!4b1!4m6!3m5!1s0x3064a00f2b650ff3:0xce80055648fccb2c!8m2!3d10.7448873!4d92.4999918!16zL20vMGN2dmM?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 35,
    'name' => 'Spiti Valley',
    'description' => 'High-altitude desert adventure',
    'image' => 'https://imgs.search.brave.com/zkHI4BuoX2f1Wr1mrTgrioZbobqFxzoA3cl4WKdKXtA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93YWxs/cGFwZXJjYXZlLmNv/bS93cC93cDc3NjMz/MzYuanBn',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.co.in/maps/place/Spiti+Valley,+Marango+Rangarik,+Himachal+Pradesh+172114/@32.2461542,78.0246162,15z/data=!3m1!4b1!4m6!3m5!1s0x3906a40ef42dc09b:0x52b583a91132a239!8m2!3d32.246137!4d78.034916!16s%2Fm%2F0273pk9?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 36,
    'name' => 'Gulmarg',
    'description' => 'Skiing and snowboarding destination',
    'image' => 'https://imgs.search.brave.com/tL1G66gHTWUrnXOOyNeASGTGdnpb6RXILuV3yUr4AgQ/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly90My5m/dGNkbi5uZXQvanBn/LzA0LzM4LzA0LzAw/LzM2MF9GXzQzODA0/MDAzOV9IMU1lQ1BK/ak5mV2daUTdYQmRU/SGdtMjRoVVg3YzlI/ay5qcGc',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.co.in/maps/place/Gulmarg+193403/@34.0506524,74.3627414,15z/data=!3m1!4b1!4m6!3m5!1s0x38e1af91308dd977:0x7a5cc65c8fb01df7!8m2!3d34.0483704!4d74.3804791!16zL20vMDU0eXMw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
 // Additional Spiritual Sites
 [
    'id' => 37,
    'name' => 'Jagannath Temple',
    'description' => 'Famous temple dedicated to Lord Jagannath in Puri',
    'image' => 'https://images.unsplash.com/photo-1472396961693-142e6e269027',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/place/Shree+Jagannatha+Temple+Puri/@19.8049429,85.8153637,17z/data=!3m1!4b1!4m6!3m5!1s0x3a19c6b8bfe386af:0x8f052c84639c7d48!8m2!3d19.8049379!4d85.8179386!16zL20vMGN2eDd4?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 38,
    'name' => 'Mahabodhi Temple',
    'description' => 'UNESCO site marking the location where Buddha attained enlightenment',
    'image' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/place/Mahabodhi+Temple/@24.6959271,84.9888444,17z/data=!3m1!4b1!4m6!3m5!1s0x39f32c5b4bd80877:0xf8dd2e286fa80c97!8m2!3d24.6959222!4d84.9914193!16zL20vMDFneXl2?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 39,
    'name' => 'Kailash Temple',
    'description' => 'Carved from a single rock with intricate Hindu architecture',
    'image' => 'https://images.unsplash.com/photo-1465146344425-f00d5f5c8f07',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.co.in/maps/place/Shri+Kailasa+Temple,+Ellora/@20.0237541,75.1766519,17z/data=!4m10!1m2!2m1!1sKailash+Temple!3m6!1s0x3bdb925d7576f3e7:0xc53f42c5f6812405!8m2!3d20.0237503!4d75.1791233!15sCg5LYWlsYXNoIFRlbXBsZVoQIg5rYWlsYXNoIHRlbXBsZZIBDGhpbmR1X3RlbXBsZeABAA!16zL20vMDlrcndu?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 40,
    'name' => 'Jama Masjid',
    'description' => 'One of India\'s largest mosques built by Shah Jahan',
    'image' => 'https://images.unsplash.com/photo-1482938289607-e9573fc25ebb',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.com/maps/place/Jama+Masjid/@28.6506839,77.2308672,17z/data=!3m1!4b1!4m6!3m5!1s0x390cfd18df89b215:0xdd57369e29bf9d96!8m2!3d28.6506792!4d77.2334421!16zL20vMDR5emo1?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 41,
    'name' => 'Velankanni Church',
    'description' => 'Basilica of Our Lady of Good Health, a major pilgrimage site',
    'image' => 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.com/maps/place/Basilica+of+Our+Lady+of+Good+Health/@11.8346848,78.7308852,8z/data=!4m10!1m2!2m1!1sVelankanni+Church!3m6!1s0x3a556edf9c4aeff9:0xd104df2c09d5ece3!8m2!3d10.6803068!4d79.8496123!15sChFWZWxhbmthbm5pIENodXJjaFoTIhF2ZWxhbmthbm5pIGNodXJjaJIBCGJhc2lsaWNh4AEA!16zL20vMDR2cDIz?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 42,
    'name' => 'Jwala Ji Temple',
    'description' => 'Temple with eternal flame emerging from natural gas reserves',
    'image' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86',
    'category' => 'Spiritual',
    'map_link' => 'https://www.google.com/maps/place/Shri+Jwalamukhi+Mata+Shaktipeeth+ji+Temple/@31.8765206,76.3173852,17z/data=!4m10!1m2!2m1!1sJwala+Ji+Temple!3m6!1s0x391b312f2171643f:0xa49c70e95f333bc1!8m2!3d31.8753944!4d76.3240824!15sCg9Kd2FsYSBKaSBUZW1wbGVaESIPandhbGEgamkgdGVtcGxlkgEMaGluZHVfdGVtcGxl4AEA!16s%2Fg%2F11b5wm_1m3?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
 // Additional Adventure Sites
 [
    'id' => 43,
    'name' => 'Zanskar Valley',
    'description' => 'Remote adventure destination for trekking and river rafting',
    'image' => 'https://images.unsplash.com/photo-1472396961693-142e6e269027',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.com/maps/place/Zanskar+194302/@33.562579,76.9774553,15z/data=!3m1!4b1!4m6!3m5!1s0x390291f2ae1b57bb:0x736eba6f94fd593a!8m2!3d33.562562!4d76.987755!16zL20vMDNjazV5?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 44,
    'name' => 'Dzongri Trek',
    'description' => 'High altitude trek with views of Kanchenjunga',
    'image' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.com/maps/place/Dzongri+Trek+Sikkim/@27.359232,88.2199051,17z/data=!3m1!4b1!4m6!3m5!1s0x39e689f167339597:0xb4a3f3f5252317a1!8m2!3d27.3592273!4d88.22248!16s%2Fg%2F11ns0rdxtw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 45,
    'name' => 'Leh Ladakh',
    'description' => 'Adventure motorcycle paradise with high mountain passes',
    'image' => 'https://images.unsplash.com/photo-1465146344425-f00d5f5c8f07',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.com/maps/place/Leh/@34.1663762,77.4843365,12z/data=!3m1!4b1!4m6!3m5!1s0x38fdeb21445fed85:0xd1bb09975086f710!8m2!3d34.1525864!4d77.5770535!16zL20vMDJibnBr?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 46,
    'name' => 'Roopkund Trek',
    'description' => 'Mysterious trek to a high-altitude lake with ancient skeletons',
    'image' => 'https://images.unsplash.com/photo-1482938289607-e9573fc25ebb',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.com/maps/place/Roopkund+Trek/@30.0571126,79.2770404,11z/data=!4m10!1m2!2m1!1sRoopkund+Trek!3m6!1s0x39a743b2de24c0bd:0x9ef0a8dd5ff61424!8m2!3d30.0571126!4d79.581911!15sCg1Sb29wa3VuZCBUcmVrWg8iDXJvb3BrdW5kIHRyZWuSAQt0b3VyX2FnZW5jeZoBJENoZERTVWhOTUc5blMwVkpRMEZuU1VOU2EzWXRXamhCUlJBQuABAPoBBAgAEDE!16s%2Fg%2F11ghs7_hqr?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 47,
    'name' => 'Auli',
    'description' => 'Premier skiing destination with panoramic Himalayan views',
    'image' => 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.com/maps/place/Auli+Hill+Station/@30.5175869,79.5244082,13z/data=!4m10!1m2!2m1!1sAuli!3m6!1s0x39a79d899f1c548d:0x6d60dbed3c3ef9e7!8m2!3d30.5273376!4d79.5632716!15sCgRBdWxpWgYiBGF1bGmSARJ0b3VyaXN0X2F0dHJhY3Rpb27gAQA!16s%2Fg%2F11p59f9tl7?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 48,
    'name' => 'Havelock Island',
    'description' => 'World-class scuba diving among pristine coral reefs',
    'image' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86',
    'category' => 'Adventure',
    'map_link' => 'https://www.google.com/maps/place/Swaraj+Dweep/@11.965773,92.9070623,12z/data=!3m1!4b1!4m6!3m5!1s0x3088d3d85e0fe039:0x25c8aaaa513ef4bf!8m2!3d11.9760503!4d92.9875565!16zL20vMDU5a3dq?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],

// Beach
[
    'id' => 49,
    'name' => 'Radhanagar Beach',
    'description' => 'One of Asia\'s best beaches in Andaman',
    'image' => 'https://imgs.search.brave.com/bvg6_izDQUU5heD21wETAH8p3PYzGAHYWq5T1WEwj1I/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9jZG4u/cHJvZC53ZWJzaXRl/LWZpbGVzLmNvbS81/YjU2MzE5OTcxYWM4/Yzc0NzVhOWQ4Nzcv/NWVlNDgxMDhmM2Vm/YzEwZmUxZTYzMjdl/XzIwMTkwNjEwXzEy/MTcwOC0uanBn',
    'category' => 'Beach',
    'map_link' => 'https://www.google.com/maps/place/Radhanagar+Beach/@11.9830887,92.9381495,15z/data=!3m1!4b1!4m6!3m5!1s0x3088d212164bb773:0x9715637d9a7265b3!8m2!3d11.9844552!4d92.9508454!16s%2Fg%2F1hc18h9zw?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 50,
    'name' => 'Palolem Beach',
    'description' => 'Pristine crescent beach in South Goa',
    'image' => 'https://imgs.search.brave.com/tBZd7fjkPxetXY661ajoh2gA4G7tXSgMJGEy_bFSiQo/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pMC53/cC5jb20vdHJvdC53/b3JsZC93cC1jb250/ZW50L3VwbG9hZHMv/MjAyMC8wNy9EU0Nf/MDY4M1VzZS10aGlz/LmpwZz9yZXNpemU9/Njk3LDQ2NSZzc2w9/MQ',
    'category' => 'Beach',
    'map_link' => 'https://www.google.com/maps/place/Palolem+Beach/@15.0093045,74.0162473,16z/data=!3m1!4b1!4m6!3m5!1s0x3bbe4551d05b02bb:0x1e1bc67d4b0fbbf5!8m2!3d15.0099648!4d74.0232186!16zL20vMDgxZ3B3?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 51,
    'name' => 'Varkala Beach',
    'description' => 'Dramatic cliffs and golden sands in Kerala',
    'image' => 'https://imgs.search.brave.com/qxk5YzdwZClhHaA_l6aBjFxjAYQYWtkjblSh0iLCous/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pcmlz/aG9saWRheXMuY29t/L2JhY2tlbmQvd2Vi/L2Rlc3RpbmF0aW9u/LWRldGFpbHMvdmFy/a2FsYS1iZWFjaC1j/bGlmZjA3LTE1Njc0/MzE5OTQuanBn',
    'category' => 'Beach',
    'map_link' => 'https://www.google.com/maps/place/Varkala+Beach/@8.7347976,76.7008245,17z/data=!3m1!4b1!4m6!3m5!1s0x3b05ef1c356e35d9:0x529a20f7e0453699!8m2!3d8.7355515!4d76.7031667!16s%2Fm%2F0bb_279?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 52,
    'name' => 'Om Beach',
    'description' => 'Natural Om-shaped beach in Karnataka',
    'image' => 'https://imgs.search.brave.com/7KfqP7iHOjc5WWqUGc-eQIimfK1eRm6pN024bfiQ_qw/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5pc3RvY2twaG90/by5jb20vaWQvNDY1/NjQ0MTkzL3Bob3Rv/L29tLWJlYWNoLmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1u/ZmQ5cjEzWkNQdFd6/NW1Ha3NEdUc1TlNy/cVpmdE0tOVhDaUo4/aHVzQUpRPQ',
    'category' => 'Beach',
    'map_link' => 'https://www.google.com/maps/place/Om+Beach/@14.5188323,74.3203131,17z/data=!3m1!4b1!4m6!3m5!1s0x3bbe8218126fad05:0x294f4f7ab4235873!8m2!3d14.5192405!4d74.3230039!16s%2Fg%2F1vlqnnmz?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 53,
    'name' => 'Marari Beach',
    'description' => 'Quiet fishing village beach in Kerala',
    'image' => 'https://imgs.search.brave.com/UacIcS__LczBg3-KY0hoFHy1hDQ7XPPbdFjjO6mA19k/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly93d3cu/a2VyYWxhdG91cmlz/bS5vcmcvX25leHQv/aW1hZ2UvP3VybD1o/dHRwOi8vMTI3LjAu/MC4xL2t0YWRtaW4v/aW1nL3BhZ2VzL2xh/cmdlLWRlc2t0b3Av/bWFyYXJpLWJlYWNo/LTE3MjI5NTU0NjNf/ZDcxZWUwZmFlNzll/ZDdlZDRmMTUud2Vi/cCZ3PTM4NDAmcT03/NQ',
    'category' => 'Beach',
    'map_link' => 'https://www.google.com/maps/place/Marari+beach/@9.6007714,76.2879587,15z/data=!3m1!4b1!4m6!3m5!1s0x3b0887be9871a19b:0x7f54df9416daa008!8m2!3d9.6007506!4d76.2982585!16zL20vMDg1MGd3?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
    'id' => 54,
    'name' => 'Kovalam Beach',
    'description' => 'Lighthouse beach with vibrant nightlife',
    'image' => 'https://imgs.search.brave.com/YUZrc3u3JNmdTbrGrRJkc7BgJrqBmLoxJJIPqlEA2cA/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9tZWRp/YS5nZXR0eWltYWdl/cy5jb20vaWQvNTQ5/Mzk3MzI3L3Bob3Rv/L2tvdmFsYW0tYmVh/Y2gtdGhpcnV2YW5h/bnRoYXB1cmFtLmpw/Zz9zPTYxMng2MTIm/dz0wJms9MjAmYz1k/MXl2OWFrMW5yeVdU/WHNuQlE3UXU5eFRJ/ZXVndXlGdHkxM1Zh/TmlhX2N3PQ',
    'category' => 'Beach',
    'map_link' => 'https://www.google.com/maps/place/Kovalam+Beach,+Kerala+695521/@8.3837657,76.9778515,17z/data=!3m1!4b1!4m6!3m5!1s0x3b05a5bb99dbbfd7:0xca415947dc10a9b5!8m2!3d8.3837841!4d76.9804176!16s%2Fg%2F11g1pn3b7y?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
// Additional Beach Sites
[
'id' => 55,
'name' => 'Agonda Beach',
'description' => 'Quiet beach known for turtle nesting and serene atmosphere',
'image' => 'https://images.unsplash.com/photo-1472396961693-142e6e269027',
'category' => 'Beach',
'map_link' => 'https://www.google.com/maps/place/Agonda,+Goa/@15.0466972,73.9777919,14z/data=!3m1!4b1!4m6!3m5!1s0x3bbe4fbb85faee3d:0xef5d75dacb8f9d45!8m2!3d15.0455814!4d73.9888797!16s%2Fg%2F11ck8blfx2?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
'id' => 56,
'name' => 'Mandarmani Beach',
'description' => 'Longest motorable beach in India with red crabs',
'image' => 'https://images.unsplash.com/photo-1433086966358-54859d0ed716',
'category' => 'Beach',
'map_link' => 'https://www.google.com/maps/place/Mandarmani+Beach/@21.6538382,87.6515541,14z/data=!3m1!4b1!4m6!3m5!1s0x3a032fd93a4bf789:0xa8e634453578d6a3!8m2!3d21.6479709!4d87.6445473!16s%2Fg%2F1tgyv3fq?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
'id' => 57,
'name' => 'Tarkarli Beach',
'description' => 'Crystal clear waters perfect for snorkeling in Maharashtra',
'image' => 'https://images.unsplash.com/photo-1465146344425-f00d5f5c8f07',
'category' => 'Beach',
'map_link' => 'https://www.google.com/maps/place/Tarkarli+Beach/@16.015648,73.4657046,14z/data=!3m1!4b1!4m6!3m5!1s0x3bc002678e3801d1:0xa2cb4081dd152476!8m2!3d16.0136864!4d73.4898334!16s%2Fg%2F11b77hg5d_?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[   
'id' => 58,
'name' => 'Kaup Beach',
'description' => 'Picturesque beach with a historic lighthouse in Karnataka',
'image' => 'https://images.unsplash.com/photo-1482938289607-e9573fc25ebb',
'category' => 'Beach',
'map_link' => 'https://www.google.com/maps/place/Kapu+Beach/@13.2222827,74.7355016,17z/data=!3m1!4b1!4m6!3m5!1s0x3bbcb11621151e45:0xf5e5f8e8e04f1ec8!8m2!3d13.2222775!4d74.7380765!16s%2Fg%2F11j8vq3gqk?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
'id' => 59,
'name' => 'Auroville Beach',
'description' => 'Serene beach near the experimental township of Auroville',
'image' => 'https://images.unsplash.com/photo-1509316975850-ff9c5deb0cd9',
'category' => 'Beach',
'map_link' => 'https://www.google.com/maps/place/Auroville+Beach/@11.9865047,79.84469,16z/data=!3m1!4b1!4m6!3m5!1s0x3a536412b097606f:0x9de480df736f6b1!8m2!3d11.9865408!4d79.8496571!16s%2Fg%2F1hjgx06qf?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
],
[
'id' => 60,
'name' => 'Bangaram Island',
'description' => 'Remote coral paradise in Lakshadweep with pristine beaches',
'image' => 'https://images.unsplash.com/photo-1513836279014-a89f7a76ae86',
'category' => 'Beach',
'map_link' => 'https://www.google.com/maps/place/Bangaram+Atoll/@10.9406018,72.2828079,16z/data=!3m1!4b1!4m6!3m5!1s0x3b9e8cf90a6bd555:0xe47d88c9eb469dfc!8m2!3d10.9364592!4d72.2881907!16s%2Fm%2F05zpppk?entry=ttu&g_ep=EgoyMDI1MDQwOS4wIKXMDSoJLDEwMjExNDU1SAFQAw%3D%3D'
]

];


?>