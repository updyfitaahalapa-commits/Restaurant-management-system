-- Database Updates for Restaurant System Thesis

-- 1. Create Inventory Table
CREATE TABLE IF NOT EXISTS `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(20) NOT NULL DEFAULT 'pcs', -- e.g., kg, pcs, liters
  `min_threshold` int(11) NOT NULL DEFAULT 5, -- for low stock alerts
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Create Payments Table
CREATE TABLE IF NOT EXISTS `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('Cash', 'Card', 'Other') NOT NULL DEFAULT 'Cash',
  `status` enum('Pending', 'Paid') NOT NULL DEFAULT 'Paid',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Update Menu Table to link with Inventory
-- Adding inventory_id to link menu item to stock
-- Adding stock_quantity_required to know how much to deduct per order (default 1)
ALTER TABLE `menu` ADD COLUMN IF NOT EXISTS `inventory_id` int(11) NULL DEFAULT NULL;
ALTER TABLE `menu` ADD COLUMN IF NOT EXISTS `quantity_required` int(11) DEFAULT 1;

-- 4. Update Orders Table for better tracking
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `user_id` int(11) NULL DEFAULT NULL; -- Link to users table
ALTER TABLE `orders` ADD COLUMN IF NOT EXISTS `payment_status` enum('Unpaid', 'Paid') NOT NULL DEFAULT 'Unpaid';

-- 5. Seed Initial Inventory (Example)
INSERT INTO `inventory` (`item_name`, `quantity`, `unit`, `min_threshold`) VALUES
('Burger Patty', 50, 'pcs', 10),
('Pizza Dough', 30, 'pcs', 5),
('Pasta Pack', 20, 'pcs', 5),
('Salmon Fillet', 15, 'pcs', 3),
('Lettuce Head', 40, 'pcs', 5);

