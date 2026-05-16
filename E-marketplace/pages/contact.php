<?php
require_once "../config/session.php"; //This session is only used for users that are not longin/register but want to look around 
require_once "../config/db.php";

$pageTitle = "Contact";

include "../includes/header.php";
include "../includes/navbar.php";
?>


<main class="contact-page">

    <!-- HERO -->
    <section class="contact-hero">

        <h1>Contact Us</h1>

        <p>
            Need help with an order, seller, listing, or account?
            Our support team is here to help.
        </p>

    </section>

    <!-- CONTACT LAYOUT -->
    <section class="contact-container">

        <!-- LEFT -->
        <div class="contact-form-box">

            <h2>Send a Message</h2>

            <form action="#" method="POST" class="contact-form">

                <div class="form-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="name"
                        placeholder="Enter your name"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Email Address</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Subject</label>

                    <input
                        type="text"
                        name="subject"
                        placeholder="What is this about?"
                        required
                    >

                </div>

                <div class="form-group">

                    <label>Message</label>

                    <textarea
                        name="message"
                        placeholder="Write your message..."
                        required
                    ></textarea>

                </div>

                <button type="submit" class="contact-btn">
                    Send Message
                </button>

            </form>

        </div>

        <!-- RIGHT -->
        <aside class="contact-info">

            <h2>Support Information</h2>

            <div class="info-card">

                <h3>Email</h3>
                <p>support@yourmarketplace.com</p>

            </div>

            <div class="info-card">

                <h3>Phone</h3>
                <p>+27 12 345 6789</p>

            </div>

            <div class="info-card">

                <h3>Location</h3>
                <p>Cape Town, South Africa</p>

            </div>

            <div class="info-card">

                <h3>Support Hours</h3>
                <p>Monday - Friday</p>
                <p>08:00 - 17:00</p>

            </div>

        </aside>

    </section>

    <!-- FAQ -->
    <section class="contact-faq">

        <h2>Frequently Asked Questions</h2>

        <div class="faq-box">

            <h3>How do I buy a product?</h3>

            <p>
                Browse products in the shop and click Buy Now
                or add items to your cart.
            </p>

        </div>

        <div class="faq-box">

            <h3>How do I become a seller?</h3>

            <p>
                Register an account and create your listings
                from your dashboard.
            </p>

        </div>

        <div class="faq-box">

            <h3>How do I contact a seller?</h3>

            <p>
                Visit the seller profile page from any product listing.
            </p>

        </div>

    </section>

</main>


<?php
include "../includes/footer.php";
include "../includes/scripts.php";
?>