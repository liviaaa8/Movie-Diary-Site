<?php
require_once 'includes/functions.php';

$popularMovies = getPopularMovies();
$pageTitle = 'Home - Popular Movies This Week';
include 'includes/header.php';
?>

    <main class="main-content">
        <div class="container">
            <section class="hero">
                <h1>Popular This Week</h1>
                <p>Discover the most talked-about movies released between April 1 - May 23, 2024</p>
            </section>

            <section class="popular-movies">
                <div class="movies-carousel">
                    <?php if (!empty($popularMovies)): ?>
                        <?php foreach ($popularMovies as $movie): ?>
                            <div class="movie-card">
                                <div class="movie-poster">
                                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>"
                                         alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                         onerror="this.src='images/no-poster.jpg'">
                                    <?php if (isLoggedIn()): ?>
                                        <div class="movie-overlay">
                                            <a href="movie.php?id=<?php echo $movie['movie_id']; ?>" class="view-movie-btn">
                                                <i class="fas fa-play"></i> View Details
                                            </a>
                                        </div>
                                    <?php else: ?>
                                        <div class="movie-overlay">
                                            <a href="login.php" class="view-movie-btn">
                                                <i class="fas fa-sign-in-alt"></i> Login to Review
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="movie-info">
                                    <h3><?php echo htmlspecialchars($movie['title']); ?></h3>
                                    <p class="movie-genre"><?php echo htmlspecialchars($movie['genre']); ?></p>
                                    <p class="movie-date">Released: <?php echo date('M j, Y', strtotime($movie['release_date'])); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="no-movies">
                            <p>No popular movies found for this week.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>

            <?php if (!isLoggedIn()): ?>
                <section class="cta-section">
                    <div class="cta-content">
                        <h2>Join Our Community</h2>
                        <p>Create an account to rate and review your favorite movies!</p>
                        <a href="register.php" class="btn btn-primary">Get Started</a>
                    </div>
                </section>
            <?php endif; ?>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>