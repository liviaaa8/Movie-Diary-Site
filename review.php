<?php
require_once 'includes/functions.php';

if (!isLoggedIn()) {
    redirectTo('login.php');
}

$movieId = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;
$movie = getMovieById($movieId);

if (!$movie) {
    redirectTo('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rating = intval($_POST['rating']);
    $reviewText = sanitizeInput($_POST['review_text']);

    if ($rating < 1 || $rating > 5) {
        $error = 'Please select a rating between 1 and 5 stars.';
    } else {
        $database = new Database();
        $conn = $database->getConnection();

        // Check if user already reviewed this movie
        $checkQuery = "SELECT review_id FROM reviews WHERE user_id = ? AND movie_id = ?";
        $checkParams = array($_SESSION['user_id'], $movieId);
        $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);

        if ($checkStmt && sqlsrv_has_rows($checkStmt)) {
            $error = 'You have already reviewed this movie.';
        } else {
            // Insert new review
            $insertQuery = "INSERT INTO reviews (user_id, movie_id, rating, review_text) VALUES (?, ?, ?, ?)";
            $insertParams = array($_SESSION['user_id'], $movieId, $rating, $reviewText);
            $insertStmt = sqlsrv_query($conn, $insertQuery, $insertParams);

            if ($insertStmt) {
                $success = 'Review submitted successfully!';
            } else {
                $error = 'Failed to submit review. Please try again.';
            }
        }

        sqlsrv_close($conn);
    }
}

$pageTitle = 'Review - ' . htmlspecialchars($movie['title']);
include 'includes/header.php';
?>

    <main class="main-content">
        <div class="container">
            <div class="review-form-container">
                <div class="movie-info-header">
                    <img src="<?php echo htmlspecialchars($movie['poster_url']); ?>"
                         alt="<?php echo htmlspecialchars($movie['title']); ?>"
                         class="review-movie-poster"
                         onerror="this.src='images/no-poster.jpg'">
                    <div>
                        <h1>Review: <?php echo htmlspecialchars($movie['title']); ?></h1>
                        <p><?php echo htmlspecialchars($movie['genre']); ?> • <?php echo date('Y', strtotime($movie['release_date'])); ?></p>
                    </div>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                        <br><a href="movie.php?id=<?php echo $movieId; ?>">View all reviews</a>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" class="review-form">
                    <div class="form-group">
                        <label>Your Rating:</label>
                        <div class="star-rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" name="rating" value="<?php echo $i; ?>" id="star<?php echo $i; ?>"
                                    <?php echo (isset($_POST['rating']) && $_POST['rating'] == $i) ? 'checked' : ''; ?>>
                                <label for="star<?php echo $i; ?>"><i class="fas fa-star"></i></label>
                            <?php endfor; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="review_text">Your Review (Optional):</label>
                        <textarea id="review_text" name="review_text" rows="6"
                                  placeholder="Share your thoughts about this movie..."><?php echo isset($_POST['review_text']) ? htmlspecialchars($_POST['review_text']) : ''; ?></textarea>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                        <a href="movie.php?id=<?php echo $movieId; ?>" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>