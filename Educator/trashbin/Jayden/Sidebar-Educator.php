<!DOCTYPE html>
<html lang="en" dir="US">

<head>
    <meta charset="UTF-8">
    <title>Responsive SideBar Menu</title>
    <!-- Fluent UI CDN Link -->
    <link rel="stylesheet"
        href="https://static2.sharepointonline.com/files/fabric/office-ui-fabric-core/11.0.0/css/fabric.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        #btn {
            margin: 0;
            cursor: pointer;
        }

        #log_out {
            margin: 0;
            cursor: pointer;
        }

        body {
            position: relative;
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
            background: #444;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 7rem;
            background: #2d0d3f;
            padding: 6px 14px;
            transition: all 0.5s ease;
        }

        .sidebar.active {
            width: 25rem;
        }

        .sidebar .logo_content .logo {
            color: #FFF;
            display: flex;
            height: 50px;
            width: 100%;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            transition: all 0.5s ease;
        }

        .sidebar.active .logo_content .logo {
            opacity: 1;
            pointer-events: none;
        }

        .logo_content .logo img {
            margin-top: 30px;
            width: 190px;
            height: 60px;
        }

        .logo_content .logo .logo_name {
            font-size: 20px;
            font-weight: 400;
        }

        .sidebar #btn {
            position: absolute;
            color: #FFF;
            top: 15px;
            left: 50px;
            font-size: 30px;
            height: 50px;
            width: 50px;
            text-align: center;
            line-height: 50px;
            transform: translateX(-30%);
        }

        .sidebar.active #btn {
            left: 90%;
        }

        .sidebar ul {
            margin-top: 35px;
        }

        .sidebar ul li {
            position: relative;
            height: 100px;
            width: 100%;
            margin: 0 5px;
            list-style: none;
            line-height: 50px;
        }

        .sidebar ul li .tooltip {
            position: absolute;
            left: 122px;
            top: 0;
            transform: translateX(-50%);
            border-radius: 6px;
            height: 35px;
            width: 122px;
            background: #FFF;
            line-height: 35px;
            text-align: center;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
            transition: 0s;
            opacity: 0;
            pointer-events: none;
            display: block;
        }

        .sidebar.active ul li .tooltip {
            display: none;
        }

        .sidebar ul li:hover .tooltip {
            transition: all 0.5s ease;
            opacity: 1;
            top: 10%;
        }

        .sidebar ul li input {
            position: absolute;
            height: 100%;
            width: 100%;
            left: 0;
            top: 0;
            border-radius: 12px;
            outline: none;
            border: none;
            background: #1D1B31;
            padding-left: 50px;
            font-size: 16px;
            color: #FFF;
        }

        .sidebar ul li span {
            font-size: large;
        }

        .sidebar ul li a {
            color: #FFF;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: all 0.5s ease;
            border-radius: 12px;
            white-space: nowrap;
        }

        .sidebar ul li a:hover {
            color: #11101D;
            background: #FFF;
        }

        .sidebar ul li i {
            height: 80px;
            min-width: 85px;
            border-radius: 12px;
            line-height: 80px;
            text-align: center;
            font-size: 1.6rem;
        }

        .sidebar .links_name {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar.active .links_name {
            opacity: 1;
            pointer-events: auto;
            transition: all 0.5s ease;
        }

        .sidebar .profile_content {
            position: absolute;
            color: #FFF;
            bottom: 0;
            left: 0;
            width: 100%;
        }

        .sidebar .profile_content .profile {
            position: relative;
            padding: 10px 6px;
            height: 7rem;
            background: none;
            transition: all 0.5s ease;
        }

        .sidebar.active .profile_content .profile {
            background: #1D1B31;
        }

        .profile_content .profile .profile_details {
            display: flex;
            align-items: center;
            opacity: 0;
            pointer-events: none;
            white-space: nowrap;
        }

        .sidebar.active .profile .profile_details {
            opacity: 1;
            pointer-events: auto;
        }

        .profile .profile_details img {
            height: 90px;
            width: 90px;
            object-fit: cover;
            border-radius: 6px;
        }

        .profile .profile_details .name_job {
            margin-left: 10px;
        }

        .profile .profile_details .name {
            font-size: 15px;
            font-weight: 400;
        }

        .profile .profile_details .job {
            font-size: 12px;
        }

        .profile #log_out {
            position: absolute;
            bottom: 29px;
            left: 50%;
            transform: translateX(-50%);
            min-width: 50px;
            line-height: 50px;
            font-size: 35px;
            border-radius: 12px;
            text-align: center;
            transition: all 0.5s ease;
            background: #1D1B31;
        }

        .sidebar.active .profile #log_out {
            left: 88%;
            background: none;
        }

        .home_content {
            position: absolute;
            height: 100%;
            width: calc(100% - 78px);
            left: 78px;
            transition: all 0.5s ease;
        }

        /* This section adjusts the home content when the sidebar is active */
        .sidebar.active~.home_content {
            width: calc(100% - 25rem);
            left: 25rem;
        }

        /* Responsive adjustments */
        @media (max-width: 932px) {
            /* Collapse the sidebar by default */
            .sidebar {
                width: 70px;
                transition: all 0.5s ease;
            }

            /* Hide the navigation list and profile content by default */
            .sidebar .nav_list,
            .sidebar .profile_content {
                display: none;
            }

            /* Expand the sidebar and show contents when active */
            .sidebar.active {
                width: 250px;
            }

            .sidebar.active .nav_list,
            .sidebar.active .profile_content {
                display: block;
            }

            /* Adjust the home content width when sidebar is active */
            .home_content {
                width: calc(100% - 70px);
                left: 70px;
                transition: all 0.5s ease;
            }

            .sidebar.active~.home_content {
                width: calc(100% - 250px);
                left: 250px;
            }

            /* Center the menu button */
            .sidebar #btn {
                left: 50%;
                transform: translateX(-50%);
            }
        }
    </style>
</head>

<body class="ms-Fabric">

    <div class="sidebar">
        <div class="logo_content">
            <div class="logo">
                <img src="/img/Code-Combat (trans) logo.png" alt="Logo">
            </div>
            <i class="ms-Icon ms-Icon--CollapseMenu" id="btn"></i>
        </div>
        <ul class="nav_list">
            <li>
                <a href="#">
                    <i class="ms-Icon ms-Icon--WaffleOffice365"></i>
                    <span class="links_name">Dashboard</span>
                </a>
                <span class="tooltip">Dashboard</span>
            </li>
            <li>
                <a href="#">
                    <i class="ms-Icon ms-Icon--Contact"></i>
                    <span class="links_name">User Board</span>
                </a>
                <span class="tooltip">User Board</span>
            </li>
            <li>
                <a href="#">
                    <i class="ms-Icon ms-Icon--Chat"></i>
                    <span class="links_name">Chat</span>
                </a>
                <span class="tooltip">Chat</span>
            </li>
            <li>
                <a href="#">
                    <i class="ms-Icon ms-Icon--PieDouble"></i>
                    <span class="links_name">Analytics</span>
                </a>
                <span class="tooltip">Analytics</span>
            </li>
            <li>
                <a href="#">
                    <i class="ms-Icon ms-Icon--FabricFolder"></i>
                    <span class="links_name">Files</span>
                </a>
                <span class="tooltip">Files</span>
            </li>
            <li>
                <a href="#">
                    <i class="fa-solid fa-circle-plus"></i>
                    <span class="links_name">Add Quiz</span>
                </a>
                <span class="tooltip">Add Quiz</span>
            </li>
            <li>
                <a href="#">
                    <i class="ms-Icon ms-Icon--Settings"></i>
                    <span class="links_name">Settings</span>
                </a>
                <span class="tooltip">Settings</span>
            </li>
        </ul>
        <div class="profile_content">
            <div class="profile">
                <div class="profile_details">
                    <img src="/img/Profile Pic.png" alt="Profile Picture">
                    <div class="name_job">
                        <div class="name">USER</div>
                        <div class="job">Admin</div>
                    </div>
                </div>
                <i class="ms-Icon ms-Icon--SignOut" id="log_out"></i>
            </div>
        </div>
    </div>
    <div class="home_content">
        <!-- Main content goes here -->
    </div>

    <script>
        let btn = document.querySelector("#btn");
        let sidebar = document.querySelector(".sidebar");

        btn.onclick = function() {
            sidebar.classList.toggle("active");
        };
    </script>
</body>

</html>
