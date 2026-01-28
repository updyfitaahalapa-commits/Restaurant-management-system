-- Add missing columns to orders table
ALTER TABLE `orders` ADD COLUMN `payment_method` varchar(50) DEFAULT NULL;
ALTER TABLE `orders` ADD COLUMN `payment_status` varchar(20) DEFAULT 'Pending';
ALTER TABLE `orders` ADD COLUMN `payment_phone` varchar(20) DEFAULT NULL;
ALTER TABLE `orders` ADD COLUMN `user_id` int(11) DEFAULT NULL;
-- Add FK for user_id if needed, but for now just column is enough to prevent error
