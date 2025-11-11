<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Poppins', sans-serif;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }
        .container {
            max-width: 600px;
        }
        h1 {
            font-size: 100px;
            margin: 0;
            color: #3f51b5;
        }
        p {
            font-size: 18px;
        }
        a {
            color: #3f51b5;
            text-decoration: none;
            border: 1px solid #3f51b5;
            padding: 8px 16px;
            border-radius: 8px;
            transition: 0.3s;
        }
        a:hover {
            background-color: #3f51b5;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <p>Oops! The page you’re looking for doesn’t exist.</p>
        <a href="{{ url('/') }}">Go Home</a>
    </div>
</body>
</html>
