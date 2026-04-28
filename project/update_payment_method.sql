-- Add payment_method column to orders table
-- Run this if you already have the database created

USE coffee_shop;

-- Add payment_method column if it doesn't exist
ALTER TABLE orders 
ADD COLUMN IF NOT EXISTS payment_method ENUM('cash', 'bkash', 'card') DEFAULT 'cash' 
AFTER status;
