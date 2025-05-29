# MovieDiary
A PHP web application for movie enthusiasts to track, review, and share movies they've watched and liked.

## Overview
MovieDiary is a simple web application that allows users to:
- Browse a collection of movies 'popular this week'
- Create an account and log in
- Write reviews for movies

## Features
- **User Authentication**: Register, login, and logout functionality
- **Admin Panel**: A dedicated panel for a better administration
- **Movie Details**: View comprehensive information about each movie
- **Reviews**: Read and write reviews for movies
- **Responsive Design**: Works on desktop and mobile devices

## Database-Website Integration

MovieDiary features a seamless integration between its Microsoft SQL Server database, PHP back-end, and HTML/CSS front-end:

- **User-Centric Data Flow**: The application captures user activities and stores them in relational tables.

- **Multi-Table Relational Structure**: The database design (see diagram below) supports complex queries that power features like personalized recommendations and activity tracking.

- **Authentication Layer**: User credentials and session data are securely managed through database persistence, with PHP middleware functions (`isLoggedIn()`, `isAdmin()`) acting as gatekeepers.

- **Content Management System**: Movie metadata is stored in structured tables, allowing for efficient querying and display through PHP functions like `getMovieById()`.

- **Social Interaction Database Design**: The schema supports social features through interconnected tables tracking user reviews and preferences, which are surfaced through functions like `getMovieReviews()`.

- **Action Tracking System**: User interactions are captured in the database and exposed through specialized functions (`getUserMovieActions()`), enabling personalized content presentation.



## Database Structure
The application uses Microsoft SQL Server Manager with the following tables:

[image](https://github.com/user-attachments/assets/02577c10-aa42-46bb-929b-e206cb5769c0)


## Core Functions
### Authentication Functions
- `isLoggedIn()`: Checks if a user is currently logged in
- `isAdmin()`: Checks if the logged-in user has admin privileges
- `redirectTo($url)`: Redirects to a specified URL
- `sanitizeInput($input)`: Sanitizes user input to prevent security issues

### Movie Management Functions
- `getMovieById($movieId)`: Retrieves a movie by its ID
- `getMovieReviews($movieId)`: Gets all reviews for a specific movie
