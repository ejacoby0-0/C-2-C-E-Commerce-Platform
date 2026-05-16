<?php
require_once "../config/session.php"; //This session is only used for users that are not longin/register but want to look around 
require_once "../config/db.php";

$pageTitle = "About";

include "../includes/header.php";
include "../includes/navbar.php";

?>

<main>

    <!--Hero banner-->
    <section class="hero-image">
    <div class="hero-text">
        <h1>Welcome to E-marketplace</h1>
        <p>Our platfrom provides local South Africans to buy and sell online.</p>
    </div>
    </section>


    <div class="about-section">
        <h1>About Us</h1>
        <p>We are a C-2-C e-commerce platfrom. We aim to provide local South African a convinent means to buying what they want or selling want they don't need anymore.
            To make sure that our customer are getting what they payed for, we make sure before the owner get the tranaction of the item that the customer order, will only clear once the customer get their order.
            To make sure that seller aare getting their transcation, we have proper secure payment method in place.</p>
    </div>

    <!--Service Section-->
    <section class="services-section">
        <div class="services-header">

            <h2>Our Services</h2>

            <p>
                We provide a trusted and secure marketplace experience
                for buyers and sellers across South Africa.
            </p>

        </div>

        <div class="services-grid">

            <!-- Service 1 -->
            <div class="service-card active-service">

                <div class="service-icon">
                    🚚
                </div>

                <h3>Delivery Services</h3>

                <p>
                    Reliable delivery options to get products safely
                    to your door.
                </p>

            </div>

            <!-- Service 2 -->
            <div class="service-card">

                <div class="service-icon">
                    🔄
                </div>

                <h3>Shipping & Returns</h3>

                <p>
                    Easy returns and smooth shipping processes for
                    secure transactions.
                </p>

            </div>

            <!-- Service 3 -->
            <div class="service-card">

                <div class="service-icon">
                    %
                </div>

                <h3>Promotions</h3>

                <p>
                    Enjoy discounts, promotions, and featured deals
                    across listings.
                </p>

            </div>

            <!-- Service 4 -->
            <div class="service-card">

                <div class="service-icon">
                    👤
                </div>

                <h3>24 Hour Support</h3>

                <p>
                    Our platform is available anytime to help buyers
                    and sellers connect.
                </p>

            </div>
        </div>
    </section>

    <!--Why choose us Section-->
    <section class="why-us-section">

        <div class="why-us-header">

            <h2>Why Choose E-Marketplace?</h2>

            <p>
                We focus on trust, security, and convenience for
                South African buyers and sellers.
            </p>

        </div>

        <div class="why-us-grid">

            <div class="why-card">

                <h3>🔒 Secure Payments</h3>

                <p>
                    Transactions are protected to ensure safe buying
                    and selling experiences.
                </p>

            </div>

            <div class="why-card">

                <h3>🛍 Local Marketplace</h3>

                <p>
                    Buy and sell products locally across South Africa.
                </p>

            </div>

            <div class="why-card">

                <h3>⭐ Trusted Sellers</h3>

                <p>
                    Seller profiles and completed sales help build trust
                    within the community.
                </p>

            </div>

            <div class="why-card">

                <h3>⚡ Easy To Use</h3>

                <p>
                    A clean and simple marketplace experience designed
                    for everyone.
                </p>

            </div>

        </div>

    </section>

    <!--Stats Section-->
    <section class="stats-section">

        <div class="stats-grid">

            <div class="stat-card">
                <h2>1000+</h2>
                <p>Products Listed</p>
            </div>

            <div class="stat-card">
                <h2>500+</h2>
                <p>Happy Users</p>
            </div>

            <div class="stat-card">
                <h2>24/7</h2>
                <p>Marketplace Access</p>
            </div>

            <div class="stat-card">
                <h2>Secure</h2>
                <p>Payments & Transactions</p>
            </div>

        </div>

    </section>

    <!--Ready to start-->
    <section class="about-cta">

        <h2>Ready To Start Buying & Selling?</h2>

        <p>
            Join our growing South African marketplace community today.
        </p>

        <a href="../pages/shop.php" class="about-cta-btn">
            Explore Marketplace
        </a>

    </section>



</main>

<?php
include "../includes/footer.php";
include "../includes/scripts.php";
?>