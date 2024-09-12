<!DOCTYPE html>
<html>
<head>
    <title>Teacher Login</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: whitesmoke;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: center;
        }
        .menubar {
            background-color:  #1F3F49;
            width: 100%;
            position: sticky;
            top: 0;
            left: 0;
            display: flex;
            justify-content: flex-end;
            padding: 10px;
            box-sizing: border-box;
        }
        .menubar ul {
            display: flex;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .menubar ul li {
            margin: 0 10px;
        }
        .menubar ul li a {
            display: block;
            padding: 15px 20px;
            text-decoration: none;
            color: #fff;
            text-transform: uppercase;
            transition: background-color 0.3s, color 0.3s;
        }
        .menubar ul li a:hover {
            background-color: cyan;
            color: black;
            border-radius: 2px;
        }
        .slideleft {
            animation: slideleft 1s linear forwards;
        }
        @keyframes slideleft {
            0% {
                transform: translateX(100%);
                opacity: 0;
            }
            100% {
                transform: translateX(0%);
                opacity: 1;
            }
        }
        h1.slideleft, p.slideleft {
            animation-delay: 1s;
        }
        .content {
            text-align: center;
            color: black;
            margin-top: 30px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 25%;
            border: none;
            margin-top: 15px;
            margin-bottom: 10px;
        }
        h2 {
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 80%;
            padding: 10px;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        button[type="submit"] {
            width: 50%;
            padding: 10px;
            background-color:  #1F3F49;
            color: #fff;
            border: none;
            margin-left: 25%;
            border-radius: 3px;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        button[type="submit"]:hover {
            background-color:green;
            color: white;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="menubar">
        <ul>
            <li><a href="pupil.php">Pupil Login</a></li>
        </ul>
    </div>
     
    <div class="content">
        <h1 class="slideleft">WELCOME TO LILAYI PRIMARY SCHOOL</h1> 
        <p class="slideleft">Digitizing The Education System.</p>
    </div>
   
    <form method="POST" action="login.php">
        <h2>STAFF LOGIN</h2>
        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<p class="error">' . $_SESSION['error'] . '</p>';
            unset($_SESSION['error']);
        }
        ?>
        <label for="username">Username:</label>
        <input type="text" id="username" placeholder="Enter UserName" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" placeholder="Enter password" name="password" required>
        <br>
        <p>Don't have an account?<a href="register_teacher.php">Register</a></p>
        <button type="submit">Login</button>
    </form>
</body>
</html>
