<?php include 'includes/nav.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WiseSprout Landing Page</title>
</head>
<body>
    <div class="landing-page">
        <div class="overlay">
            <div class="container text-center">
                <h1>Welcome to Wise <span style="color: yellow;">Sprout</span>!</h1>
                <p class="lead">Your smart solution for efficient irrigation management.</p>
                <p>Discover how WiseSprout revolutionizes the way you manage your irrigation system.</p>
                <a href="how-it-works.php" class="btn btn-outline-primary" data-scroll>How It Works</a>
            </div>
            </div>
        </div>
    </div>

    <main role="main" class="container p-5" id="About-Us">
        <div class="row my-5 ">
            <div class="col-md-6">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <ol class="carousel-indicators">
                        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="assets/pictures/sample1.jpg" class="d-block w-100 h-100" alt="First slide">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/pictures/sample2.jpg" class="d-block w-100" alt="Second slide">
                        </div>
                        <div class="carousel-item">
                            <img src="assets/pictures/sample3.jpg" class="d-block w-100" alt="Third slide">
                        </div>
                    </div>
                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="sr-only">Previous</span>
                    </a>
                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="sr-only">Next</span>
                    </a>
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <div>
                    <h1>Our Technology</h1>
                    <p>WiseSprout harnesses state-of-the-art technology to redefine water sprinkler management. Our web application serves as the central hub for controlling and monitoring your irrigation system with unparalleled ease and efficiency.</p>
                    <p>Through seamless integration with your existing setup, our platform automates watering schedules, adapts to weather fluctuations in real-time, and optimizes water usage based on precise data insights. Our intelligent algorithms take into account soil moisture levels, plant types, and environmental conditions to deliver tailored irrigation solutions.</p>
                </div>
            </div>
        </div>
        <div class="mt-5">
            <h1 class="mb-4 text-center">Meet our team!</h1>
                <div class="row">
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="team-member text-center">
                    <img src="assets/pictures/ronwel.jpg" class="team-member-img mb-3" alt="Ronwel John M. Catre">
                    <h5>Ronwel John Catre</h5>
                    <p>Project Manager / Developer</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="team-member text-center">
                    <img src="assets/pictures/lyka.jpg" class="team-member-img mb-3" alt="Angelyka Abiada">
                    <h5>Angelyka Abiada</h5>
                    <p>QA Tester / UI/UX Designer</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="team-member text-center">
                    <img src="assets/pictures/mark.jpg" class="team-member-img mb-3" alt="Mark Delmo">
                    <h5>Mark Delmo</h5>
                    <p>QA Tester / UI/UX Designer</p>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="team-member text-center">
                    <img src="assets/pictures/arnel.jpg" class="team-member-img mb-3" alt="Arnel Arandia">
                    <h5>Arnel Arandia</h5>
                    <p>Content Manager</p>
                </div>
            </div>
        </div>
        <section class=" py-5" id="Benefits">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center">
                        <h2 class="section-heading text-uppercase">Benefits of WiseSprout</h2>
                        <p class="text-muted">Discover why WiseSprout is the smart choice for your irrigation needs.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="benefit-item text-center">
                            <i class="fas fa-check-circle fa-3x mb-4"></i>
                            <h3>Water Conservation</h3>
                            <p class="text-muted">Efficient water management strategies reduce waste and conserve resources.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-item text-center">
                            <i class="fas fa-chart-line fa-3x mb-4"></i>
                            <h3>Improved Efficiency</h3>
                            <p class="text-muted">Optimized scheduling and real-time monitoring enhance system efficiency.</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="benefit-item text-center">
                            <i class="fas fa-user-shield fa-3x mb-4"></i>
                            <h3>User-Friendly Interface</h3>
                            <p class="text-muted">Intuitive dashboard and user-friendly controls for easy management.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="Exclusive-Content"  class="py-5">
            <div class="container text-center">
                <h2>Exclusive Content</h2>
                <p>Unlock premium tips and guides for maximizing your irrigation system's efficiency. Register now to access.</p>
                <a href="register.php" class="btn btn-primary">Register</a>
            </div>
            <div id="Features" ></div>
        </section>
    </main>
    <section class="py-5 text-white" style="background-color: #012602;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center mb-4">
                <h2 class="section-heading text-uppercase">Features</h2>
                <p>Discover the powerful features that make WiseSprout exceptional.</p>
            </div>
        </div>
        <div class="container">
        <div class="row">
            <div class="col-md-6">
                <div class="feature-item p-4">
                    <h3>Redefining Water Sprinkler Management</h3>
                    <p>WiseSprout brings advanced technology to revolutionize irrigation management, ensuring efficient and precise watering solutions tailored to your needs.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-item p-4">
                    <h3>Control from Anywhere</h3>
                    <p>Monitor and manage your irrigation system seamlessly from anywhere using our intuitive mobile app and web dashboard.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="feature-item p-4">
                    <h3>Smart Irrigation</h3>
                    <p>WiseSprout automatically adjusts watering schedules based on real-time weather data and soil moisture levels, optimizing water usage and plant health.</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="feature-item p-4">
                    <h3>Easy Setup and Control</h3>
                    <p>Set up and control watering schedules effortlessly through our user-friendly interface.</p>
                </div>
            </div>
        </div>
    </div>
    </div>
</section>

<section id="ProofInPlants" class="py-5">
    <div class="container">
        <div class="row text-center">
            <div class="col-lg-12">
                <h2 class="section-heading text-uppercase">The Proof is in the Plants</h2>
                <p class="text-muted">See the impact of optimal watering with WiseSprout</p>
            </div>
            <div class="col-lg-12 mb-4">
                <a href="resources.php" class="btn btn-primary">LEARN MORE</a>
            </div>
        </div>
        <div class="row text-white">
            <div class="col-md-12 position-relative">
                <div id="plantCarousel" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner" style="height: 400px;"> <!-- Set fixed height for the carousel -->
                        <div class="carousel-item active">
                            <div class="row h-100"> <!-- Ensure full height for each carousel item -->
                                <div class="col-md-6">
                                    <img src="assets/pictures/plant1.jpg" class="d-block w-100 h-100" alt="Plant 1" style="object-fit: cover;">
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-center rounded" style="background-color: #012602;">
                                    <div class="text-center p-3">
                                        <h5>Healthy Growth</h5>
                                        <p>Witness lush, vibrant growth with WiseSprout’s precise watering technology.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row h-100">
                                <div class="col-md-6">
                                    <img src="assets/pictures/plant2.jpg" class="d-block w-100 h-100" alt="Plant 2" style="object-fit: cover;">
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-center rounded" style="background-color: #012602;">
                                    <div class="text-center p-3">
                                        <h5>Water Efficiency</h5>
                                        <p>Save water without sacrificing the health of your plants.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row h-100">
                                <div class="col-md-6">
                                    <img src="assets/pictures/plant3.jpg" class="d-block w-100 h-100" alt="Plant 3" style="object-fit: cover;">
                                </div>
                                <div class="col-md-6 d-flex align-items-center justify-content-center rounded" style="background-color: #012602;">
                                    <div class="text-center p-3">
                                        <h5>Environmental Impact</h5>
                                        <p>Conserve our environment by optimizing water usage with WiseSprout.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row text-center mt-3">
            <div class="col-md-4 mb-2">
                <button class="btn btn-success btn-block" onclick="$('#plantCarousel').carousel(0);">Healthy Growth</button>
            </div>
            <div class="col-md-4 mb-2">
                <button class="btn btn-success btn-block" onclick="$('#plantCarousel').carousel(1);">Water Efficiency</button>
            </div>
            <div class="col-md-4 mb-2">
                <button class="btn btn-success btn-block" onclick="$('#plantCarousel').carousel(2);">Environmental Impact</button>
            </div>
        </div>
    </div>
</section>
    <?php include 'includes/footer.php' ?>
</body>
</html>
