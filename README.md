# C-2-C-E-Commerce-Platform

## Project Overview
This project is a C2C e-commerce platform. 

The purpose of this project is to provide a digital environment where customers can sell and purchase products online without worrying about fraudulent activities, promoting a secure and safe marketplace.

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


## Getting Started
To use this repo, you will need to get a local copy up and running. Follow these steps.

### Prerequisites

  ```sh
  -VS code
  -Xammp
    |-Apache(To run the website locally)
    |-MySQL(To store and manage the database)
  -No npm (Node Package Manager) used for this project
  ```

### Installation

1. Clone the repo:
   ```sh
   git clone https://github.com/ejacoby0-0/C-2-C-E-Commerce-Platform
   cd C-2-C-E-Commercere-Platform
   ```

This is the schema I used for my database:

```

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

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','sold','pending','removed') DEFAULT 'pending',
  `location` varchar(255) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `product_condition` enum('new','like new','good','fair') DEFAULT 'good'
)

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0
)

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `buyer_id` int(11) NOT NULL,
  `seller_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `status` enum('pending','paid','shipped','delivered','cancelled','disputed') DEFAULT 'pending',
  `payment_reference` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL
)

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL
)

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
)

```
