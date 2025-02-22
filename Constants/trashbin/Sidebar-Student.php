
<meta charset="UTF-8">
<title>Responsive SideBar Menu</title>
<!-- Fluent UI CDN Link -->
<link rel="stylesheet" href="https://static2.sharepointonline.com/files/fabric/office-ui-fabric-core/11.0.0/css/fabric.min.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- Font Awesome CSS -->
<link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.6.0/css/all.css">
<style>
    /* Reset and Base Styles */
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

    /* Sidebar Styles */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100%;
        width: 80px; /* Converted from 5rem */
        background: #2d0d3f;
        padding: 12px 10px;
        transition: all 0.5s ease;
        display: flex;
        flex-direction: column;
        z-index: 10000;
        /* z-index to ensure it will always be above other */
    }

    .sidebar.active {
        width: 320px; /* Converted from 20rem */
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.3);
    }

    .sidebar .logo_content .logo {
        color: #FFF;
        display: flex;
        height: 50px;
        width: 90px;
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
        width: 150px;
        height: 45px;
    }

    .logo_content .logo .logo_name {
        font-size: 20px;
        font-weight: 400;
        transition: opacity 0.5s ease;
    }

    /* collapse button */
    .sidebar #btn {
        position: absolute;
        color: #FFF;
        top: 15px;
        left: 35%;
        font-size: 30px;
        height: 50px;
        width: 50px;
        text-align: center;
        line-height: 50px;
        transform: translateX(-30%);
        transition: transform 0.5s ease-in-out, color 0.5s ease-in-out;
    }

    .sidebar.active #btn {
        left: 80%;
        transform: rotate(180deg);
    }

    .sidebar ul {
        margin-top: 35px;
        transition: opacity 0.5s ease-in-out;
    }

    .sidebar.active ul {
        opacity: 1;
    }

    .sidebar ul li {
        position: relative;
        height: 60px;
        /* Reduced height */
        width: 100%;
        margin: 10px 0;
        /* Adjusted margin for better spacing */
        list-style: none;
        line-height: 60px;
        /* Adjusted line-height to match new height */
    }

    .sidebar ul li .tooltip {
        position: absolute;
        left: 110px;
        /* Adjusted positioning */
        top: 50%;
        transform: translateY(-50%);
        border-radius: 6px;
        height: 30px;
        /* Reduced height */
        width: 100px;
        /* Reduced width */
        background: #FFF;
        line-height: 30px;
        /* Match height */
        text-align: center;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
        opacity: 0;
        pointer-events: none;
        display: block;
    }

    .sidebar.active ul li .tooltip {
        display: none;
    }

    .sidebar ul li:hover .tooltip {
        opacity: 1;
        top: 60%;
        /* Adjusted for smoother appearance */
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
        font-size: 14.4px; /* Converted from 0.9rem */
    }

    .sidebar ul li a {
        color: #FFF;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 12px;
        white-space: nowrap;
    }

    .sidebar ul li a:hover {
        color: #11101D;
        background: #FFF;
    }

    .sidebar ul li i {
        height: 60px;
        /* Reduced height */
        min-width: 60px;
        /* Reduced min-width */
        border-radius: 12px;
        line-height: 60px;
        /* Match height */
        text-align: center;
        font-size: 19.2px; /* Converted from 1.2rem */
    }

    /* Specific Adjustments for Font Awesome Icons */
    .sidebar ul li a .fa-gamepad-modern,
    .sidebar ul li a .fa-pen-field,
    .sidebar ul li a .fa-trophy,
    .sidebar ul li a .fa-clock-rotate-left {
        font-size: 19.2px; /* Converted from 1.2rem */
    }

    .sidebar .links_name {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.5s ease;
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
        padding: 8px 0px;
        height: 80px; /* Converted from 5rem */
        background: none;
        transition: all 0.5s ease-in-out;
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
        transition: opacity 0.5s ease;
    }

    .sidebar.active .profile .profile_details {
        opacity: 1;
        pointer-events: auto;
    }

    .profile .profile_details img {
        height: 70px;
        /* Reduced size */
        width: 70px;
        /* Reduced size */
        object-fit: cover;
        border-radius: 6px;
        margin-left: 16px; /* Converted from 1rem */
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
        bottom: 20px;
        /* Adjusted positioning */
        left: 50%;
        transform: translateX(-50%);
        min-width: 40px;
        /* Reduced width */
        line-height: 40px;
        /* Match min-width */
        font-size: 25px;
        /* Reduced font size */
        border-radius: 12px;
        text-align: center;
        transition: transform 0.5s ease-in-out;
        background: #1D1B31;
    }

    .sidebar.active .profile #log_out {
        left: 88%;
        background: none;
        rotate: (180deg);
    }

    /* Home Content */
    .home_content {
        position: absolute;
        height: 100%;
        width: calc(100% - 80px); /* Converted from 5rem */
        left: 80px; /* Converted from 5rem */
        transition: margin-left 0.5s ease;
    }

    /* When Sidebar is Active */
    .sidebar.active~.home_content {
        width: calc(100% - 320px); /* Converted from 20rem */
        left: 320px; /* Converted from 20rem */
    }

    /* Responsive Adjustments */
    @media (max-width: 932px) {

        /* Collapse the sidebar by default */
        .sidebar {
            width: 50px;
            /* Reduced from 70px */
            transition: all 0.5s ease;
        }

        /* Hide the navigation list and profile content by default */
        .sidebar .nav_list,
        .sidebar .profile_content {
            display: none;
        }

        /* Expand the sidebar and show contents when active */
        .sidebar.active {
            width: 200px;
            /* Reduced from 250px */
        }

        .sidebar.active .nav_list,
        .sidebar.active .profile_content {
            display: block;
        }

        /* Adjust the home content width when sidebar is active */
        .home_content {
            width: calc(100% - 50px); /* Converted from 50px */
            left: 50px; /* Converted from 50px */
            transition: margin-left 0.5s ease;
        }

        .sidebar.active~.home_content {
            width: calc(100% - 200px); /* Converted from 200px */
            left: 200px; /* Converted from 200px */
        }

        /* Center the menu button */
        .sidebar #btn {
            left: 50%;
            transform: translateX(-50%);
        }

        /* Further reduce icon sizes in responsive mode */
        .sidebar ul li i {
            font-size: 16px; /* Converted from 1rem */
        }

        /* Further reduce tooltip sizes in responsive mode */
        .sidebar ul li .tooltip {
            width: 80px;
            /* Further reduced width */
            height: 25px;
            /* Further reduced height */
            line-height: 25px;
            /* Match height */
            font-size: 12.8px; /* Converted from 0.8rem */
        }

        /* Adjust Font Awesome icons in responsive mode */
        .sidebar ul li a .fa-gamepad-modern,
        .sidebar ul li a .fa-pen-field,
        .sidebar ul li a .fa-trophy,
        .sidebar ul li a .fa-clock-rotate-left {
            font-size: 16px; /* Converted from 1rem */
        }

        /* Adjust logout icon size in responsive mode */
        .profile #log_out {
            font-size: 20px;
            /* Further reduced font size */
            min-width: 35px;
            /* Further reduced width */
            line-height: 35px;
            /* Match min-width */
        }
    }
</style>



<div class="sidebar">
    <div class="logo_content">
        <div class="logo">
            <!-- Logo can be placed here if needed -->
        </div>
        <i class="ms-Icon ms-Icon--CollapseMenu" id="btn"></i>
    </div>
    <ul class="nav_list">
        <li>
            <a href="dashboard_nav">
                <i class="ms-Icon ms-Icon--WaffleOffice365"></i>
                <span class="links_name">Dashboard</span>
            </a>
            <span class="tooltip">Dashboard</span>
        </li>
        <li>
            <a href="play_nav">
                <i class="fas fa-gamepad"></i>
                <span class="links_name">Play</span>
            </a>
            <span class="tooltip">Play</span>
        </li>
        <li>
            <a href="custom_quiz_nav">
                <i class="fa-light fa-pen-field"></i>
                <span class="links_name">Custom Quiz</span>
            </a>
            <span class="tooltip">Custom Quiz</span>
        </li>
        <li>
            <a href="user_nav">
                <i class="ms-Icon ms-Icon--Contact"></i>
                <span class="links_name">User</span>
            </a>
            <span class="tooltip">User</span>
        </li>
        <li>
            <a href="leaderboard_nav">
                <i class="fa-solid fa-trophy"></i>
                <span class="links_name">Leaderboard</span>
            </a>
            <span class="tooltip">Leaderboard</span>
        </li>
        <li>
            <a href="history_nav">
                <i class="fa-solid fa-clock-rotate-left"></i>
                <span class="links_name">History</span>
            </a>
            <span class="tooltip">History</span>
        </li>
        <li>
            <a href="setting_nav">
                <i class="ms-Icon ms-Icon--Settings"></i>
                <span class="links_name">Settings</span>
            </a>
            <span class="tooltip">Settings</span>
        </li>
    </ul>
    <div class="profile_content">
        <div class="profile">
            <div class="profile_details">
                <img src="/img/Profile Pic.png" alt="Profile Picture" style="cursor: pointer;">
                <div class="name_job">
                    <div class="name">USER</div>
                    <div class="job">Student</div>
                </div>
            </div>
            <i class="ms-Icon ms-Icon--SignOut" id="log_out"></i>
        </div>
    </div>
</div>

<script>
    const btn = document.querySelector("#btn");
    const sidebar = document.querySelector(".sidebar");

    // Toggle sidebar on button click
    btn.addEventListener('click', function(event) {
        sidebar.classList.toggle("active");
        // Prevent the click from propagating to the document
        event.stopPropagation();
    });

    // Prevent clicks inside the sidebar from closing it
    sidebar.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    // Close sidebar when clicking outside of it
    document.addEventListener('click', function(event) {
        // If the sidebar is active, remove the "active" class to hide it
        if (sidebar.classList.contains("active")) {
            sidebar.classList.remove("active");
        }
    });
</script>

