# C-2-C-E-Commerce-Platform

## Project Overview
This project is a C2C e-commerce platform

## Tech Stack
1. Frontend/Languages: HTML, CSS, JavaScript
2. Backend: PHP
3. Framework: Bootstrap
4. Database: MySQL

## Features
1.  Responsive Design
2.  Secure Payment Gateways
3. Detailed Product Information
4. Streamlined Checkout
5. Customer Reviews & Ratings
6. Faceted Search & Navigation
7. Automated Email Flows
8. Product Management System


## Prerequisite

This is the schema I used for my website

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `user_type` enum('user','admin') DEFAULT 'user',
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','suspended') DEFAULT 'active',
  `bio` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL
)

