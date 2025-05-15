<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Voting System - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body, html {
            height: 100%;
            background-color: #f8f9fa;
        }
        .container {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        h1 {
            margin-bottom: 40px;
            color: #343a40;
        }
        .btn-login {
            width: 200px;
            margin: 10px;
            font-size: 18px;
            padding: 12px;
        }
    </style>
</head>
<body>

<div class="container text-center">
    <h1>Welcome to Voting System</h1>
    <a href="admin_login.php" class="btn btn-primary btn-login">Admin Login</a>
    <a href="voter_login.php" class="btn btn-success btn-login">Voter Login</a>
</div>

</body>
</html>
