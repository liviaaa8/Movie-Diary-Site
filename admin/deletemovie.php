<?php
$pageTitle = 'Delete Movie - Admin';
require_once 'includes/admin_header.php';

$movieId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$movie = getMovieById($movieId);

if (!$movie) {
    adminRedirectTo('index.php');
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    $result = deleteMovie($movieId);

    if ($result) {
        $_SESSION['admin_message'] = 'Movie deleted successfully!';
    } else {
        $_SESSION['admin_error'] = 'Failed to delete movie.';
    }

    adminRedirectTo('index.php');
}
?>

    <main class="main-content">
        <div class="container">
            <div class="admin-form-container">
                <div class="admin-header">
                    <h1>Delete Movie</h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Admin Panel
                    </a>
                </div>

                <div class="delete-confirmation">
                    <div class="movie-preview">
                        <img src="../<?php echo htmlspecialchars($movie['poster_url']); ?>"
                             alt="<?php echo htmlspecialchars($movie['title']); ?>"
                             class="delete-movie-poster"
                             onerror="this.src='../images/no-poster.jpg'">
                        <div class="movie-details">
                            <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                            <p><strong>Genre:</strong> <?php echo htmlspecialchars($movie['genre']); ?></p>
                            <p><strong>Release Date:</strong> <?php echo date('F j, Y', strtotime($movie['release_date'])); ?></p>
                            <p><strong>Popular This Week:</strong> <?php echo $movie['is_popular_this_week'] ? 'Yes' : 'No'; ?></p>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> This action cannot be undone. Deleting this movie will also remove all associated reviews.
                    </div>

                    <form method="POST" action="" class="delete-form">
                        <p class="confirmation-text">Are you sure you want to delete "<strong><?php echo htmlspecialchars($movie['title']); ?></strong>"?</p>

                        <div class="form-actions">
                            <button type="submit" name="confirm_delete" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Yes, Delete Movie
                            </button>
                            <a href="index.php" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

<?php include 'includes/admin_footer.php'; ?>