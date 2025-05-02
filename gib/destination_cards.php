<?php
function renderDestinationCard($destination) {
    $isWishlisted = isset($_SESSION['user']) ? sInWishlist($destination['id'], $_SESSION['user']) : false;
    $wishlistClass = $isWishlisted ? 'wishlisted' : '';
    ?>
    <div class="destination-card" data-category="<?php echo strtolower($destination['category']); ?>">
        <div class="card-zoom">
            <img src="<?php echo $destination['image']; ?>" alt="<?php echo $destination['name']; ?>" class="card-image">
            <?php if(isset($_SESSION['user'])): ?>
            <button class="wishlist-btn <?php echo $wishlistClass; ?>" data-id="<?php echo $destination['id']; ?>">
                <i class="fas fa-star"></i>
            </button>
            <?php endif; ?>
        </div>
        <div class="card-content">
            <span class="destination-category"><?php echo $destination['category']; ?></span>
            <h3 class="destination-title"><?php echo $destination['name']; ?></h3>
            <p class="destination-desc"><?php echo $destination['description']; ?></p>
            <a href="<?php echo $destination['map_link']; ?>" target="_blank" class="destination-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>View on map</span>
            </a>
        </div>
    </div>
    <?php
}


?>