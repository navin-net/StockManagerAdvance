<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Index - Kevin Bootstrap Template</title>
    <meta name="description" content="">
    <meta name="keywords" content="">

    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->

    <link href="{{ asset('assets/bootstrap.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('portfolio/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('portfolio/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">
    <link href="{{ asset('portfolio/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="{{ asset('portfolio/assets/css/main.css') }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Kevin
  * Template URL: https://bootstrapmade.com/Kevin-bootstrap-portfolio-website-template/
  * Updated: Aug 23 2025 with Bootstrap v5.3.7
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body class="index-page">

    {{-- <header id="header" class="header d-flex align-items-center light-background sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between"> --}}

            <!-- <a href="index.html" class="logo d-flex align-items-center me-auto me-xl-0">
      <img src="assets/img/logo.webp" alt="">
      <h1 class="sitename">Kevin</h1>
    </a> -->

            {{-- <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="index.html" class="active">Home</a></li>
                    <li><a href="about.html">About</a></li>
                    <li><a href="resume.html">Resume</a></li>
                    <li><a href="services.html">Services</a></li>
                    <li><a href="portfolio.html">Portfolio</a></li>
                    <li class="dropdown"><a href="#"><span>Dropdown</span> <i
                                class="bi bi-chevron-down toggle-dropdown"></i></a>
                        <ul>
                            <li><a href="#">Dropdown 1</a></li>
                            <li class="dropdown"><a href="#"><span>Deep Dropdown</span> <i
                                        class="bi bi-chevron-down toggle-dropdown"></i></a>
                                <ul>
                                    <li><a href="#">Deep Dropdown 1</a></li>
                                    <li><a href="#">Deep Dropdown 2</a></li>
                                    <li><a href="#">Deep Dropdown 3</a></li>
                                    <li><a href="#">Deep Dropdown 4</a></li>
                                    <li><a href="#">Deep Dropdown 5</a></li>
                                </ul>
                            </li>
                            <li><a href="#">Dropdown 2</a></li>
                            <li><a href="#">Dropdown 3</a></li>
                            <li><a href="#">Dropdown 4</a></li>
                        </ul>
                    </li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <div class="header-social-links">
                <a href="#" class="twitter"><i class="bi bi-twitter-x"></i></a>
                <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
            </div>

        </div>
    </header> --}}

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-4 align-items-center">
                    <div class="col-lg-6 order-2 order-lg-1">
                        <div class="hero-content">
                            <h1 data-aos="fade-up" data-aos-delay="200">Hello, I'm <span
                                    class="highlight">{{ Auth::user()->first_name }}</span></h1>
                            <h2 data-aos="fade-up" data-aos-delay="300">Creative <span class="typed"
                                    data-typed-items="UI/UX Designer, Web Developer, Digital Artist, Brand Strategist"></span>
                            </h2>
                            <p data-aos="fade-up" data-aos-delay="400">I was born on January 1, 1995, in Kandal and now live in Phnom Penh. I graduated in Information Technology from
                                [Build Bright University]. I did an internship at [IGrow-Tech] working with PHP and
                                CodeIgniter, and I was later offered a full-time role, where I’ve spent the past 2 years
                                developing projects using PHP, CodeIgniter, and Laravel.
                                I’m really motivated to join your company because I admire your innovative projects. I’m
                                excited to bring my skills to contribute to your team, solve challenging problems, and
                                keep growing as a developer."*
                            </p>
                            <div class="hero-actions" data-aos="fade-up" data-aos-delay="500">
                                <a href="portfolio.html" class="btn btn-primary">View My Work</a>
                                {{-- <a href="contact.html" class="btn btn-outline">Get In Touch</a> --}}
                            </div>
                            <div class="social-links" data-aos="fade-up" data-aos-delay="600">
                                <a href="#"><i class="bi bi-telegram"></i></a>
                                <a href="#"><i class="bi bi-linkedin"></i></a>
                                <a href="#"><i class="bi bi-github"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 order-1 order-lg-2">
                        <div class="hero-image" data-aos="zoom-in" data-aos-delay="300">
                            <div class="image-wrapper">
                                <img src="{{ asset('portfolio/assets/img/profile/profile-square-11.jpg')}}"
                                    alt="Sarah Mitchell" class="img-fluid">
                            {{-- @auth
                                <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                                    alt="{{ auth()->user()->name }}"
                                    class="img-fluid">
                            @endauth --}}
                                <div class="floating-elements">
                                    <div class="floating-card design" data-aos="fade-left" data-aos-delay="700">
                                        <i class="bi bi-palette"></i>
                                        <span>Design</span>
                                    </div>
                                    <div class="floating-card code" data-aos="fade-right" data-aos-delay="800">
                                        <i class="bi bi-code-slash"></i>
                                        <span>Code</span>
                                    </div>
                                    <div class="floating-card creativity" data-aos="fade-up" data-aos-delay="900">
                                        <i class="bi bi-lightbulb"></i>
                                        <span>Ideas</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Skills Section -->
        <section id="skills" class="skills section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Skills</h2>
                <p></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 skills-animation">

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="100">
                        <div class="skill-box">
                            <h3>HTML</h3>
                            <p>Sed ut perspiciatis unde omnis iste natus error sit voluptatem.</p>
                            <span class="text-end d-block">90%</span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="90" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="200">
                        <div class="skill-box">
                            <h3>CSS</h3>
                            <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur.</p>
                            <span class="text-end d-block">90%</span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="95" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="300">
                        <div class="skill-box">
                            <h3>JavaScript</h3>
                            <p>Neque porro quisquam est qui dolorem ipsum quia dolor.</p>
                            <span class="text-end d-block">80%</span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="80" aria-valuemin="0"
                                    aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3" data-aos="fade-up" data-aos-delay="400">
                        <div class="skill-box">
                            <h3>Photoshop</h3>
                            <p>Quis autem vel eum iure reprehenderit qui in ea voluptate.</p>
                            <span class="text-end d-block">55%</span>
                            <div class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="55" aria-valuemin="0"
                                    aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Skills Section -->


        <!-- Resume Section -->
        <section id="resume" class="resume section">
            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Resume</h2>
                {{-- <p>simple</p> --}}
            </div><!-- End Section Title -->
            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row">
                    <div class="col-lg-6">

                        <!-- Education Section -->
                        <div class="resume-item" data-aos="fade-up">
                            <h3 class="resume-title">Education</h3>

                            <div class="resume-content">
                                {{-- <article class="education-item">
                                    <h4>Master of Computer Science</h4>
                                    <h5>2019 - 2021</h5>
                                    <p class="institution"><em>Stanford University, California</em></p>
                                    <p>Qui deserunt veniam. Et sed aliquam labore tempore sed quisquam iusto autem sit.
                                        Ea vero voluptatum qui ut dignissimos deleniti nerada porti sand markend</p>
                                </article> --}}

                                <article class="education-item">
                                    <h4>Bachelor of Information Technology</h4>
                                    <h5>2021 - 2024</h5>
                                    <p class="institution"><em>Build Bright University</em></p>
                                </article>
                                <article class="education-item">
                                    <h4>Hun Sen Saang High School</h4>
                                    <h5>2017 — 2021</h5>
                                    <p class="institution">High School Diploma</p>
                                </article>

                            </div>
                        </div><!-- End Education Section -->

                    </div>
                    <div class="col-lg-6">
                        <!-- Experience Section -->
                        <div class="resume-item" data-aos="fade-up" data-aos-delay="100">
                            <h3 class="resume-title">Professional Experience</h3>

                            <div class="resume-content">


                                <article class="experience-item">
                                    <h4>Junior Software Developer</h4>
                                    <h5>2017 - 2019</h5>
                                    <p class="company"><em>IGrowTech</em></p>
                                    <ul>
                                        <li>Implemented responsive websites and web applications using modern JavaScript
                                            frameworks</li>
                                        <li>Collaborated with senior developers to maintain and optimize existing
                                            applications</li>
                                        <li>Participated in code reviews and contributed to team documentation efforts
                                        </li>
                                        <li>Assisted in the development of RESTful APIs and microservices</li>
                                    </ul>
                                </article>
                            </div>
                        </div><!-- End Experience Section -->
                    </div>
                </div>

            </div>
        </section>
        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p></p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-4 g-lg-5">
                    <div class="col-lg-5">
                        <div class="info-box" data-aos="fade-up" data-aos-delay="200">
                            <h3>Contact Info</h3>
                            <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante
                                ipsum primis.</p>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="300">
                                <div class="icon-box">
                                    <i class="bi bi-geo-alt"></i>
                                </div>
                                <div class="content">
                                    <h4>Our Location</h4>
                                    <p>A108 Adam Street</p>
                                    <p>New York, NY 535022</p>
                                </div>
                            </div>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="400">
                                <div class="icon-box">
                                    <i class="bi bi-telephone"></i>
                                </div>
                                <div class="content">
                                    <h4>Phone Number</h4>
                                    <p>+1 5589 55488 55</p>
                                    <p>+1 6678 254445 41</p>
                                </div>
                            </div>

                            <div class="info-item" data-aos="fade-up" data-aos-delay="500">
                                <div class="icon-box">
                                    <i class="bi bi-envelope"></i>
                                </div>
                                <div class="content">
                                    <h4>Email Address</h4>
                                    <p>info@example.com</p>
                                    <p>contact@example.com</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="contact-form" data-aos="fade-up" data-aos-delay="300">
                            <h3>Get In Touch</h3>
                            <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Vestibulum ante
                                ipsum primis.</p>

                            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up"
                                data-aos-delay="200">
                                <div class="row gy-4">

                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" placeholder="Your Name"
                                            required="">
                                    </div>

                                    <div class="col-md-6 ">
                                        <input type="email" class="form-control" name="email" placeholder="Your Email"
                                            required="">
                                    </div>

                                    <div class="col-12">
                                        <input type="text" class="form-control" name="subject" placeholder="Subject"
                                            required="">
                                    </div>

                                    <div class="col-12">
                                        <textarea class="form-control" name="message" rows="6" placeholder="Message"
                                            required=""></textarea>
                                    </div>

                                    <div class="col-12 text-center">
                                        <div class="loading">Loading</div>
                                        <div class="error-message"></div>
                                        <div class="sent-message">Your message has been sent. Thank you!</div>

                                        <button type="submit" class="btn btn-primary" disabled>Send Message</button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Contact Section -->


    </main>

    <footer id="footer" class="footer">

        <div class="container">
            <div class="copyright text-center ">
                <p>© <span>Copyright</span> <strong class="px-1 sitename">Kevin</strong> <span>All Rights
                        Reserved<br></span></p>
            </div>
            <div class="social-links d-flex justify-content-center">
                <a href=""><i class="bi bi-facebook"></i></a>
                <a href=""><i class="bi bi-instagram"></i></a>
                <a href=""><i class="bi bi-linkedin"></i></a>
            </div>
            <div class="credits">
                Designed by <a href="https://bootstrapmade.com/">BootstrapMade</a>
            </div>
        </div>

    </footer>

    <!-- Scroll Top -->
    <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Preloader -->
    <div id="preloader"></div>

    <!-- Vendor JS Files -->
    <script src="{{ asset('portfolio/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/php-email-form/validate.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/typed.js/typed.umd.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/waypoints/noframework.waypoints.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('portfolio/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('portfolio/assets/js/main.js') }}"></script>

</body>

</html>
