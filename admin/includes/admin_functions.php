<?php
require_once __DIR__ . '/../../includes/functions.php';

function adminRedirectTo($url) {
    header("Location: $url");
    exit();
}

function checkAdminAccess() {
    if (!isLoggedIn() || !isAdmin()) {
        adminRedirectTo('../../index.php');
    }
}

function getAllMovies() {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM movies ORDER BY created_at DESC";
    $stmt = sqlsrv_query($conn, $query);

    $movies = array();
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $movies[] = $row;
        }
    }

    sqlsrv_close($conn);
    return $movies;
}

function deleteMovie($movieId) {
    $database = new Database();
    $conn = $database->getConnection();

    // Delete reviews first (foreign key constraint)
    $deleteReviewsQuery = "DELETE FROM reviews WHERE movie_id = ?";
    $deleteReviewsParams = array($movieId);
    sqlsrv_query($conn, $deleteReviewsQuery, $deleteReviewsParams);

    // Delete movie
    $deleteMovieQuery = "DELETE FROM movies WHERE movie_id = ?";
    $deleteMovieParams = array($movieId);
    $stmt = sqlsrv_query($conn, $deleteMovieQuery, $deleteMovieParams);

    $success = ($stmt) ? true : false;
    sqlsrv_close($conn);

    return $success;
}

function addMovie($title, $description, $releaseDate, $posterUrl, $genre, $isPopular) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "INSERT INTO movies (title, description, release_date, poster_url, genre, is_popular_this_week) VALUES (?, ?, ?, ?, ?, ?)";
    $params = array($title, $description, $releaseDate, $posterUrl, $genre, $isPopular);
    $stmt = sqlsrv_query($conn, $query, $params);

    $success = ($stmt) ? true : false;
    sqlsrv_close($conn);

    return $success;
}

function updateMovie($movieId, $title, $description, $releaseDate, $posterUrl, $genre, $isPopular) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "UPDATE movies SET title = ?, description = ?, release_date = ?, poster_url = ?, genre = ?, is_popular_this_week = ? WHERE movie_id = ?";
    $params = array($title, $description, $releaseDate, $posterUrl, $genre, $isPopular, $movieId);
    $stmt = sqlsrv_query($conn, $query, $params);

    $success = ($stmt) ? true : false;
    sqlsrv_close($conn);

    return $success;
}
?>