<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code Combat Header</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
        }

        /* Header */
        header {
            background-color: #221c36;
            color: white;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-container {
            max-width: 1200px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px;
        }

        .logo {
            max-width: 6rem;
            max-height: 4rem;
            margin: 0 auto;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 1.5em;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
        }

        .search-signup {
            display: flex;
            align-items: center;
            gap: 0.5em;
        }

        .search-signup input {
            padding: 0.5em;
            border-radius: 4px;
            border: none;
        }

        .sign-in,
        .sign-up {
            padding: 0.5em 1em;
            border: none;
            border-radius: 4px;
        }

        .sign-in {
            background-color: #dadada;
            color: #333;
        }

        .sign-up {
            background-color: #6e30a8;
            color: white;
        }
        
        .sign-in:hover {
            background-color: #ffffff;
            color: #333;
        }

        .sign-up:hover {
            background-color: #a94dff;
            color: white;
        }

        img {
            width: 9rem;
            height: 3.0rem;
        }

        /* Responsive Design */
        @media (max-width: 100px) {
            .navbar {
                flex-direction: column;
                align-items: flex-start;
            }

            nav ul {
                flex-direction: column;
                gap: 0.5em;
                margin-top: 0.5em;
            }

            .search-signup {
                flex-direction: column;
                width: 100%;
                margin-top: 1em;
            }

            .search-signup input {
                width: 100%;
            }

            footer .footer-container {
                flex-direction: column;
                gap: 1em;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 0.5em;
            }

            .footer-container {
                padding: 1em;
                font-size: 0.9em;
            }

            .search-signup input {
                width: 100%;
                margin-bottom: 0.5em;
            }
        }
    </style>
</head>

<body>
    <header>
        <div class="navbar">
            <div class="logo">
                <img src="/img/Code-Combat (trans) logo.png" alt="Logo" />
            </div>
            <div class="search-signup">
                <!-- <button class="sign-in">Sign in</button> -->
                <!-- <button class="sign-up">Sign up</button> -->
            </div>
        </div>
    </header>
</body>

</html>