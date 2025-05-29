<?php
session_start();
require_once __DIR__ . '/../config/database.php';;

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function redirectTo($url) {
    header("Location: $url");
    exit();
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function getPopularMovies() {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM movies WHERE is_popular_this_week = 1 AND release_date BETWEEN '2024-04-01' AND '2025-08-01' ORDER BY release_date DESC";
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

function getMovieById($movieId) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT * FROM movies WHERE movie_id = ?";
    $params = array($movieId);
    $stmt = sqlsrv_query($conn, $query, $params);

    $movie = null;
    if ($stmt && sqlsrv_has_rows($stmt)) {
        $movie = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    sqlsrv_close($conn);
    return $movie;
}

function getMovieReviews($movieId) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT r.*, u.full_name FROM reviews r 
              JOIN users u ON r.user_id = u.user_id 
              WHERE r.movie_id = ? 
              ORDER BY r.created_at DESC";
    $params = array($movieId);

    $stmt = sqlsrv_query($conn, $query, $params);

    $reviews = array();
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $reviews[] = $row;
        }
    }

    sqlsrv_close($conn);
    return $reviews;
}

function getUserMovieActions($userId, $movieId = null) {
    $database = new Database();
    $conn = $database->getConnection();

    if ($movieId) {
        // Get actions for a specific movie
        $query = "SELECT * FROM user_movie_actions WHERE user_id = ? AND movie_id = ?";
        $params = array($userId, $movieId);
        $stmt = sqlsrv_query($conn, $query, $params);

        $result = null;
        if ($stmt && sqlsrv_has_rows($stmt)) {
            $result = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
        }
    } else {
        // Get actions for all movies
        $query = "SELECT uma.*, m.title, m.poster_url FROM user_movie_actions uma 
                  JOIN movies m ON uma.movie_id = m.movie_id 
                  WHERE user_id = ? ORDER BY uma.updated_at DESC";
        $params = array($userId);
        $stmt = sqlsrv_query($conn, $query, $params);

        $result = array();
        if ($stmt) {
            while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                $result[] = $row;
            }
        }
    }

    sqlsrv_close($conn);
    return $result;
}

function updateMovieAction($userId, $movieId, $action, $value) {
    $database = new Database();
    $conn = $database->getConnection();

    // Check if record exists
    $checkQuery = "SELECT id FROM user_movie_actions WHERE user_id = ? AND movie_id = ?";
    $checkParams = array($userId, $movieId);
    $checkStmt = sqlsrv_query($conn, $checkQuery, $checkParams);

    if ($checkStmt && sqlsrv_has_rows($checkStmt)) {
        // Update existing record
        $updateQuery = "UPDATE user_movie_actions SET $action = ?, updated_at = GETDATE() WHERE user_id = ? AND movie_id = ?";
        $updateParams = array($value, $userId, $movieId);
        $result = sqlsrv_query($conn, $updateQuery, $updateParams);
    } else {
        // Insert new record
        $insertQuery = "INSERT INTO user_movie_actions (user_id, movie_id, $action, updated_at) VALUES (?, ?, ?, GETDATE())";
        $insertParams = array($userId, $movieId, $value);
        $result = sqlsrv_query($conn, $insertQuery, $insertParams);
    }

    $success = ($result !== false);
    sqlsrv_close($conn);
    return $success;
}

function getWatchedMovies($userId) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT m.* FROM user_movie_actions uma 
              JOIN movies m ON uma.movie_id = m.movie_id 
              WHERE uma.user_id = ? AND uma.watched = 1
              ORDER BY uma.updated_at DESC";
    $params = array($userId);
    $stmt = sqlsrv_query($conn, $query, $params);

    $movies = array();
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $movies[] = $row;
        }
    }

    sqlsrv_close($conn);
    return $movies;
}

function getLikedMovies($userId) {
    $database = new Database();
    $conn = $database->getConnection();

    $query = "SELECT m.* FROM user_movie_actions uma 
              JOIN movies m ON uma.movie_id = m.movie_id 
              WHERE uma.user_id = ? AND uma.liked = 1
              ORDER BY uma.updated_at DESC";
    $params = array($userId);
    $stmt = sqlsrv_query($conn, $query, $params);

    $movies = array();
    if ($stmt) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $movies[] = $row;
        }
    }

    sqlsrv_close($conn);
    return $movies;
}

?>