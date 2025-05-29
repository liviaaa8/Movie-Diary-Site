<?php
$pageTitle = 'Admin Panel';
require_once 'includes/admin_header.php';

$movies = getAllMovies();
?>

<main class="main-content">
    <div class="container">
        <div class="admin-header">
            <h1>Admin Panel</h1>
            <a href="addmovie.php" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Movie
            </a>
        </div>

        <?php if (isset($_SESSION['admin_message'])): ?>
            <div class="alert alert-success">
                <?php
                echo $_SESSION['admin_message'];
                unset($_SESSION['admin_message']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['admin_error'])): ?>
            <div class="alert alert-error">
                <?php
                echo $_SESSION['admin_error'];
                unset($_SESSION['admin_error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="admin-section">
            <h2>Manage Movies</h2>
            <?php if (!empty($movies)): ?>
                <div class="admin-table-container">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Poster</th>
                            <th>Title</th>
                            <th>Genre</th>
                            <th>Release Date</th>
                            <th>Popular This Week</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($movies as $movie): ?>
                            <tr>
                                <td>
                                    <img src="../<?php echo htmlspecialchars($movie['poster_url']); ?>"
                                         alt="<?php echo htmlspecialchars($movie['title']); ?>"
                                         class="admin-poster-thumb"
                                         onerror="this.src='../images/no-poster.jpg'">
                                </td>
                                <td><?php echo htmlspecialchars($movie['title']); ?></td>
                                <td><?php echo htmlspecialchars($movie['genre']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($movie['release_date'])); ?></td>
                                <td>
                                    <span class="status-badge <?php echo $movie['is_popular_this_week'] ? 'active' : 'inactive'; ?>">
                                        <?php echo $movie['is_popular_this_week'] ? 'Yes' : 'No'; ?>
                                    </span>
                                </td>
                                <td class="actions">
                                    <a href="editmovie.php?id=<?php echo $movie['movie_id']; ?>" class="btn btn-small btn-secondary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="deletemovie.php?id=<?php echo $movie['movie_id']; ?>"
                                       class="btn btn-small btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="no-data">
                    <p>No movies found. <a href="addmovie.php">Add the first movie</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include 'includes/admin_footer.php'; ?>
