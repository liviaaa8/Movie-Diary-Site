<?php
$pageTitle = 'Edit Movie - Admin';
require_once 'includes/admin_header.php';

$movieId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$movie = getMovieById($movieId);

if (!$movie) {
    adminRedirectTo('index.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = sanitizeInput($_POST['title']);
    $description = sanitizeInput($_POST['description']);
    $releaseDate = sanitizeInput($_POST['release_date']);
    $posterUrl = sanitizeInput($_POST['poster_url']);
    $genre = sanitizeInput($_POST['genre']);
    $isPopular = isset($_POST['is_popular_this_week']) ? 1 : 0;

    if (empty($title) || empty($releaseDate) || empty($genre)) {
        $error = 'Title, release date, and genre are required.';
    } else {
        $result = updateMovie($movieId, $title, $description, $releaseDate, $posterUrl, $genre, $isPopular);

        if ($result) {
            $success = 'Movie updated successfully!';
            // Refresh movie data
            $movie = getMovieById($movieId);
        } else {
            $error = 'Failed to update movie. Please try again.';
        }
    }
}
?>

    <main class="main-content">
        <div class="container">
            <div class="admin-form-container">
                <div class="admin-header">
                    <h1>Edit Movie</h1>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Admin Panel
                    </a>
                </div>

                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>

                <form method="POST" action="" class="admin-form">
                    <div class="form-group">
                        <label for="title">Movie Title *:</label>
                        <input type="text" id="title" name="title" required
                               value="<?php echo htmlspecialchars($movie['title']); ?>">
                    </div>

                    <div class="form-group">
                        <label for="genre">Genre *:</label>
                        <input type="text" id="genre" name="genre" required
                               value="<?php echo htmlspecialchars($movie['genre']); ?>"
                               placeholder="e.g., Action/Adventure, Drama/Romance">
                    </div>

                    <div class="form-group">
                        <label for="release_date">Release Date *:</label>
                        <input type="date" id="release_date" name="release_date" required
                               value="<?php echo date('Y-m-d', strtotime($movie['release_date'])); ?>">
                    </div>

                    <div class="form-group">
                        <label for="poster_url">Poster Path:</label>
                        <input type="text" id="poster_url" name="poster_url"
                               value="<?php echo htmlspecialchars($movie['poster_url']); ?>"
                               placeholder="images/movie_name.jpg">
                        <small class="form-text">Add the relative path to your image file (e.g., "images/fall_guy.jpg"). Make sure to manually upload the file to this location first.</small>
                    </div>


                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea id="description" name="description" rows="4"
                                  placeholder="Brief description of the movie..."><?php echo htmlspecialchars($movie['description']); ?></textarea>
                    </div>

                    <div class="form-group checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_popular_this_week" value="1"
                                <?php echo ($movie['is_popular_this_week'] ? 'checked' : ''); ?>>
                            <span class="checkmark"></span>
                            Show in "Popular This Week"
                        </label>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">Update Movie</button>
                        <a href="index.php" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </main>

<?php include 'includes/admin_footer.php'; ?>