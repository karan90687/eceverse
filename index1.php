<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ECEverse - Electronics Learning Platform</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6C63FF;
            --secondary: #4D44DB;
            --accent: #FF6584;
            --light: #F8F9FF;
            --dark: #2E2E48;
            --gray: #8C8CA1;
            --success: #4CC9F0;
            --card-bg: #FFFFFF;
            --section-bg: #F9FAFF;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        
        body {
            background-color: #ffffff;
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }
        
        /* Header */
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.2rem 5%;
            background-color: rgba(255, 255, 255, 0.98);
            box-shadow: 0 2px 30px rgba(108, 99, 255, 0.08);
            z-index: 1000;
            backdrop-filter: blur(8px);
            transition: all 0.3s ease;
        }
        
        header.scrolled {
            padding: 0.8rem 5%;
            box-shadow: 0 4px 20px rgba(108, 99, 255, 0.1);
        }
        
        .logo {
            font-size: 1.6rem;
            font-weight: 700;
            color: var(--primary);
            display: flex;
            align-items: center;
            transition: all 0.3s;
        }
        
        .logo i {
            margin-right: 0.5rem;
            color: var(--primary);
        }
        
        nav {
            display: flex;
            gap: 2rem;
            color: transparent;
        }
        
        nav a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 500;
            font-size: 0.95rem;
            position: relative;
            transition: color 0.3s;
        }
        
        nav a:hover {
            color: var(--primary);
        }
        
        nav a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background-color: var(--accent);
            transition: width 0.3s;
        }
        
        nav a:hover::after {
            width: 100%;
        }
        
        .auth-buttons {
            display: flex;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        
        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
        }
        
        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.3);
        }
        
        .btn-solid {
            background: var(--primary);
            color: white;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }
        
        .btn-solid:hover {
            background: var(--secondary);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.4);
        }
        
        .btn i {
            margin-left: 0.5rem;
            font-size: 0.9rem;
            transition: transform 0.3s;
        }
        
        .btn:hover i {
            transform: translateX(3px);
        }
        
        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 5%;
            background: linear-gradient(135deg, #f8f9ff 0%, #f0f2ff 100%);
            position: relative;
            overflow: hidden;
        }
        
        .hero::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 200%;
            background: radial-gradient(circle, rgba(108, 99, 255, 0.08) 0%, rgba(108, 99, 255, 0) 70%);
            z-index: 0;
        }
        
        .hero-content {
            max-width: 600px;
            z-index: 1;
            position: relative;
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .hero h1 {
            font-size: 3.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            line-height: 1.2;
            color: var(--dark);
        }
        
        .hero h1 span {
            color: var(--primary);
            position: relative;
            display: inline-block;
        }
        
        .hero h1 span::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 0;
            width: 100%;
            height: 8px;
            background-color: rgba(108, 99, 255, 0.2);
            z-index: -1;
            border-radius: 4px;
        }
        
        .hero p {
            font-size: 1.15rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            max-width: 500px;
            line-height: 1.7;
        }
        
        .hero-buttons {
            display: flex;
            gap: 1.5rem;
            margin-top: 2rem;
        }
        
        .hero-image {
            position: absolute;
            right: 5%;
            width: 45%;
            max-width: 700px;
            z-index: 0;
            margin-top: 100px;
            animation: float 6s ease-in-out infinite;
        }
        
        .hero-image img {
            width: 90%;
            margin-trim: 30%;
            height: 50%;
            margin-top: 0px;
            filter: drop-shadow(0 20px 30px rgba(0, 0, 0, 0.1));
        }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Features Section */
        .features {
            padding: 8rem 5%;
            background-color: var(--section-bg);
            position: relative;
        }
        
        .features::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgZmlsbD0icmdiYSgxMDgsOTksMjU1LDAuMDMpIi8+PC9wYXR0ZXJuPjwvZGVmcz48cmVjdCBmaWxsPSJ1cmwoI3BhdHRlcm4pIiB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIi8+PC9zdmc+');
            opacity: 0.5;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 5rem;
            position: relative;
        }
        
        .section-header h2 {
            font-size: 2.3rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }
        
        .section-header h2::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(to right, var(--primary), var(--accent));
            border-radius: 3px;
        }
        
        .section-header p {
            font-size: 1.1rem;
            color: var(--gray);
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.7;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
        }
        
        .feature-card {
            display: flex;
            align-items: center;
            background: var(--card-bg);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.1);
            border: 1px solid rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.03) 0%, rgba(255, 101, 132, 0.03) 100%);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.08);
        }
        
        .feature-card:hover::before {
            opacity: 1;
        }
        
        .feature-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 1.5rem;
            color: white;
            font-size: 1.6rem;
            box-shadow: 0 8px 20px rgba(108, 99, 255, 0.3);
            flex-shrink: 0;
        }
        
        .feature-content {
            flex: 1;
        }
        
        .feature-card h3 {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }
        
        .feature-card p {
            color: var(--gray);
            margin-bottom: 1rem;
            line-height: 1.7;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.95rem;
        }
        
        .feature-link {
            display: inline-flex;
            align-items: center;
            color: var(--primary);
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .feature-link:hover {
            color: var(--secondary);
        }
        
        .feature-link i {
            margin-left: 0.5rem;
            transition: transform 0.3s;
            font-size: 0.9rem;
        }
        
        .feature-link:hover i {
            transform: translateX(5px);
        }
        
        .feature-image {
            width: 100px;
            height: 100px;
            margin-left: 1.5rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }
        
        .feature-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .typewriter p::after {
            content: '|';
            display: inline-block;
            animation: blink 0.75s step-end infinite;
            margin-left: 2px;
        }
        
        @keyframes blink {
            50% { opacity: 0; }
        }
        
        /* Mission Sections */
        .mission {
            padding: 6rem 5%;
            background-color: white;
            position: relative;
            overflow: hidden;
        }
        
        .mission:nth-child(even) {
            background-color: var(--section-bg);
        }
        
        .mission-container {
            display: flex;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
            gap: 5rem;
            position: relative;
            border: black;
        }
        
        .mission-image {
            flex: 1;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
            transition: transform 0.5s;
            position: relative;
            background: transparent;

        }
        
        .mission-image::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(108, 99, 255, 0.1) 0%, rgba(255, 101, 132, 0.1) 100%);
            opacity: 0;
            transition: opacity 0.3s;
            background: transparent;
            z-index: 1;
        }
        
        .mission-image:hover::before {
            opacity: 1;
            background: transparent;
        }
        
        .mission-image:hover {
            transform: scale(1.02);
            background: transparent;
        }
        
        .mission-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
            background: transparent;
        }
        
        .mission-image:hover img {
            transform: scale(1.05);
            background: transparent;
        }
        
        .mission-content {
            flex: 1;
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .mission-number {
            font-size: 1rem;
            font-weight: 700;
            color: var(--accent);
            margin-bottom: 1rem;
            display: inline-block;
            background: rgba(255, 101, 132, 0.1);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
        }
        
        .mission-content h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--dark);
            line-height: 1.3;
        }
        
        .mission-content p {
            font-size: 1.1rem;
            color: var(--gray);
            margin-bottom: 2rem;
            line-height: 1.7;
        }
        
        .mission-stats {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }
        
        .stat-item {
            text-align: center;
            background: white;
            padding: 1.5rem;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s;
            min-width: 100px;
        }
        
        .stat-item:hover {
            transform: translateY(-5px);
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--gray);
            font-weight: 500;
        }
        
        /* Alternate layout for even missions */
        .mission:nth-child(even) .mission-container {
            flex-direction: row-reverse;
        }
        
        /* CTA Section */
        .cta {
            padding: 8rem 5%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        
        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg4NSkiPjxyZWN0IHdpZHRoPSIyMCIgaGVpZ2h0PSI2MCIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3QgZmlsbD0idXJsKCNwYXR0ZXJuKSIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIvPjwvc3ZnPg==');
            opacity: 0.3;
        }
        
        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .cta h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }
        
        .cta p {
            font-size: 1.15rem;
            margin-bottom: 3rem;
            opacity: 0.9;
            line-height: 1.7;
        }
        
        .cta .btn {
            background: white;
            color: var(--primary);
            font-size: 1.1rem;
            padding: 1rem 2.5rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            font-weight: 600;
        }
        
        .cta .btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }
        
        /* Floating elements animation */
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }
        
        .floating-element {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            animation: floatElement 15s infinite linear;
        }
        
        @keyframes floatElement {
            0% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-100px) rotate(180deg); }
            100% { transform: translateY(0) rotate(360deg); }
        }
        
        /* Footer */
        footer {
            background-color: var(--dark);
            color: white;
            padding: 5rem 5% 2rem;
            position: relative;
        }
        
        .footer-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        
        .footer-logo {
            font-size: 1.6rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: inline-block;
            color: white;
        }

        .logo{

            background: transparent;
        }
        
        .footer-about p {
            opacity: 0.7;
            margin-bottom: 1.5rem;
            line-height: 1.7;
        }
        
        .social-links {
            display: flex;
            gap: 1rem;
        }
        
        .social-links a {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: all 0.3s;
        }
        
        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        .footer-links h3 {
            font-size: 1.2rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 0.5rem;
            color: white;
        }
        
        .footer-links h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background: var(--accent);
        }
        
        .footer-links ul {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s;
            font-size: 0.95rem;
        }
        
        .footer-links a:hover {
            color: white;
        }
        
        .footer-bottom {
            text-align: center;
            padding-top: 3rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            opacity: 0.7;
            font-size: 0.9rem;
            position: relative;
        }
        
        .footer-bottom p {
            margin-bottom: 1rem;
        }
        
        .footer-bottom-links {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        
        .footer-bottom-links a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }
        
        .footer-bottom-links a:hover {
            color: white;
        }
        
        /* Responsive Design */
        @media (max-width: 768px) {
            .hero {
                flex-direction: column;
                text-align: center;
                padding-top: 100px;
            }
            
            .hero-content {
                max-width: 100%;
            }
            
            .hero-image {
                position: relative;
                width: 100%;
                max-width: 500px;
                margin-top: 2rem;
            }
            
            .hero-buttons {
                justify-content: center;
            }
            
            .mission-container {
                flex-direction: column;
            }
            
            .mission:nth-child(even) .mission-container {
                flex-direction: column;
            }
            
            .mission-image {
                width: 100%;
                max-width: 500px;
            }
            
            .feature-card {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .feature-image {
                margin-left: 0;
                margin-top: 1rem;
            }
            
            .footer-container {
                grid-template-columns: 1fr;
                text-align: center;
            }
            
            .social-links {
                justify-content: center;
            }
            
            .footer-links h3::after {
                left: 50%;
                transform: translateX(-50%);
            }
        }
        
        @media (max-width: 480px) {
            .hero h1 {
                font-size: 2.5rem;
            }
            
            .cta h2 {
                font-size: 2rem;
            }
            
            .btn {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }
        }

        a.btn {
    text-decoration: none;
}

    </style>
</head>
<body>
    <header>
        <div class="logo"><i class="fas fa-bolt"></i> ECEverse</div>
        <nav>
            <a href="#home">Home</a>
            <a href="#features">Features</a>
            <a href="#">Community</a>
            <a href="#">Network</a>
            <a href="#contact">Contact</a>
        </nav>
        <div class="auth-buttons">
           <a href="login.php" class="btn btn-outline">Login</a>
           <a href="create_account.php" class="btn btn-solid">Sign Up <i class="fas fa-arrow-right"></i></a>

        </div>
    </header>
    
    <section class="hero" id="home">
        <div class="hero-content">
            <h1>Master <span>Electronics</span> with ECEverse</h1>
            <p>Explore interactive courses, hands-on projects, and real-world applications to become an electronics expert.</p>
            <div class="hero-buttons">
                <a href="login.php" class="btn btn-solid">Get Started <i class="fas fa-arrow-right"></i></a>
                <a href ="create_account.php" class="btn btn-outline">Create account</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="https://illustrations.popsy.co/amber/digital-nomad.svg" alt="Electronics Illustration">
        </div>
        <div class="floating-elements">
            <div class="floating-element" style="width: 50px; height: 50px; top: 20%; left: 10%; animation-duration: 12s;"></div>
            <div class="floating-element" style="width: 80px; height: 80px; top: 60%; left: 80%; animation-duration: 18s;"></div>
            <div class="floating-element" style="width: 60px; height: 60px; top: 80%; left: 30%; animation-duration: 15s;"></div>
        </div>
    </section>
    
    <section class="features" id= "features">
        <div class="section-header">
            <h2>Why Choose ECEverse?</h2>
            <p>Discover the tools and resources that make learning electronics engaging and effective.</p>
        </div>
         

    
<section class="mission" id="mission">
        <div class="mission-container">
            <div class="mission-image">
                <img src="hand-coding-animate.svg" alt="Mission Image">
            </div>
            <div class="mission-content">
                <span class="mission-number">01</span>
                <h2> Practice Problems</h2>
                <p>Sharpen your skills by solving a curated set of practice problems tailored for electronics learners and enthusiasts.</p>
                <div class="mission-stats">
                    <div class="stat-item">
                        <div class="stat-number" id="active-learners">10K+</div>
                        <div class="stat-label">Active Learners</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="projects-completed">500+</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
   <section class="mission" id="mission">
        <div class="mission-container">
            <div class="mission-image">
                <img src="group-discussion-animate.svg"alt="Mission Image">
            </div>
            <div class="mission-content">
                <span class="mission-number">02</span>
                <h2>Community Forum</h2>
                <p>Join interactive discussions with peers and experts in the electronics field — ask questions, share insights, and grow together.</p>
                <div class="mission-stats">
                    <div class="stat-item">
                        <div class="stat-number" id="active-learners">10K+</div>
                        <div class="stat-label">Active Learners</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="projects-completed">500+</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="mission" id="mission">
        <div class="mission-container">
            <div class="mission-image">
                  <img src="profile-interface-animate.svg"alt="Mission Image">
                
            </div>
            <div class="mission-content">
                <span class="mission-number">03</span>
                <h2>Profile Builder</h2>
                <p>Create a professional electronics profile to showcase your skills, completed projects, and achievements.</p>
                <div class="mission-stats">
                    <div class="stat-item">
                        <div class="stat-number" id="active-learners">10K+</div>
                        <div class="stat-label">Active Learners</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number" id="projects-completed">500+</div>
                        <div class="stat-label">Projects Completed</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section class="mission">
        <div class="mission-container">
            <div class="mission-image">
                <img src="printed-circuit-board-animate.svg"alt="Mission Image">
            </div>
            <div class="mission-content">
                <span class="mission-number">04</span>
                <h2>Project Explorer</h2>
                <p>Browse and explore a wide range of exciting electronics projects to learn, replicate, or get inspired for your own creations.</p>
                <div class="mission-stats">
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Expert Instructors</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="mission">
        <div class="mission-container">
            <div class="mission-image">
                <img src="job-offers-animate.svg"alt="Mission Image">
            </div>
            <div class="mission-content">
                <span class="mission-number">05</span>
                <h2>ElectroJobs</h2>
                <p>Discover and apply for job opportunities specifically in the electronics industry, tailored to your skills and interests.<p>
                <div class="mission-stats">
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Expert Instructors</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">95%</div>
                        <div class="stat-label">Satisfaction Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    
    
    <section class="cta">
        <div class="cta-content">
            <h2>Join the Electronics Revolution</h2>
            <p>Start your journey with ECEverse today and unlock your potential in electronics engineering.</p>
            <button class="btn">Get Started Now <i class="fas fa-arrow-right"></i></button>
        </div>
        <div class="floating-elements">
            <div class="floating-element" style="width: 60px; height: 60px; top: 30%; left: 15%; animation-duration: 14s;"></div>
            <div class="floating-element" style="width: 90px; height: 90px; top: 70%; left: 85%; animation-duration: 16s;"></div>
        </div>
    </section>
    
    <footer id="contact">
        <div class="footer-container">
            <div class="footer-about">
                <div class="footer-logo"><i class="fas fa-bolt"></i> ECEverse</div>
                <p>ECEverse is your go-to platform for mastering electronics through interactive learning and hands-on experience.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="footer-links">
                <h3>Explore</h3>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#mission">Mission</a></li>
                    <li><a href="#about">About</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h3>Resources</h3>
                <ul>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Tutorials</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">Community</a></li>
                </ul>
            </div>
            <div class="footer-links">
                <h3>Support</h3>
                <ul>
                    <li><a href="#">Help Center</a></li>
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>© 2025 ECEverse. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Use</a>
                <a href="#">Cookie Policy</a>
                <a href="#">Accessibility</a>
            </div>
        </div>
    </footer>
    
    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (header) {
                header.classList.toggle('scrolled', window.scrollY > 0);
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Random number generator for mission stats
        function updateStats() {
            const activeLearners = document.getElementById('active-learners');
            const projectsCompleted = document.getElementById('projects-completed');
            
            if (activeLearners && projectsCompleted) {
                // Generate random numbers
                const learners = Math.floor(Math.random() * (15000 - 8000) + 8000);
                const projects = Math.floor(Math.random() * (1000 - 400) + 400);
                
                activeLearners.textContent = `${Math.round(learners / 1000)}K+`;
                projectsCompleted.textContent = `${projects}+`;
            }
        }

        // Update stats initially and every 5 seconds
        updateStats();
        setInterval(updateStats, 5000);

        // Typewriter effect
        const typewriterElements = document.querySelectorAll('.typewriter');
        typewriterElements.forEach((element, index) => {
            const text = element.textContent;
            element.textContent = '';
            let i = 0;
            
            function type() {
                if (i < text.length) {
                    element.textContent = text.slice(0, i + 1);
                    i++;
                    setTimeout(type, 100 + Math.random() * 50);
                }
            }
            
            // Start typewriter effect with a slight delay for each element
            setTimeout(type, index * 500);
        });
    </script>
</body>
</html>