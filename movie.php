<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirectTo('login.php');
}

$movieId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$movie = getMovieById($movieId);

if (!$movie) {
    redirectTo('index.php');
}

$reviews = getMovieReviews($movieId);
$pageTitle = htmlspecialchars($movie['title']);
include 'includes/header.php';
?>

    <main class="main-content">
        <div class="container">
            <div class="movie-details">
                <div class="movie-header">
                    <div class="movie-poster-large">
                        <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>"
                             alt="<?php echo htmlspecialchars($movie['title']); ?>"
                             onerror="this.src='images/no-poster.jpg'">
                    </div>
                    <div class="movie-info-large">
                        <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                        <p class="movie-genre"><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
                        <p class="movie-date"><strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($movie['release_date'])); ?></p>
                        <div class="movie-description">
                            <h3>Description</h3>
                            <p><?php echo nl2br(htmlspecialchars($movie['description'])); ?></p>
                        </div>
                        <a href="review.php?movie_id=<?php echo $movie['movie_id']; ?>" class="btn btn-primary">
                            <i class="fas fa-star"></i> Write a Review
                        </a>
                    </div>
                </div>
            </div>

            <section class="reviews-section">
                <h2>Reviews</h2>
                <?php if (!empty($reviews)): ?>
                    <div class="reviews-list">
                        <?php foreach ($reviews as $review): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <h4><?php echo htmlspecialchars($review['full_name']); ?></h4>
                                    <div class="rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'filled' : 'empty'; ?>"></i>
                                        <?php endfor; ?>
                                        <span class="rating-text">(<?php echo $review['rating']; ?>/5)</span>
                                    </div>
                                    <span class="review-date"><?php echo date('M j, Y', strtotime($review['created_at'])); ?></span>
                                </div>
                                <?php if (!empty($review['review_text'])): ?>
                                    <div class="review-text">
                                        <p><?php echo nl2br(htmlspecialchars($review['review_text'])); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <p>No reviews yet. Be the first to review this movie!</p>
                        <a href="review.php?movie_id=<?php echo $movie['movie_id']; ?>" class="btn btn-primary">Write First Review</a>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>