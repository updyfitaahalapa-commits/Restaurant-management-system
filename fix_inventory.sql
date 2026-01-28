-- Fix Inventory Schema
DROP TABLE IF EXISTS `inventory`;

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_name` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `unit` varchar(20) NOT NULL DEFAULT 'pcs', 
  `min_threshold` int(11) NOT NULL DEFAULT 5,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `inventory` (`item_name`, `quantity`, `unit`, `min_threshold`) VALUES
('Burger Patty', 50, 'pcs', 10),
('Pizza Dough', 30, 'pcs', 5),
('Pasta Pack', 20, 'pcs', 5),
('Salmon Fillet', 15, 'pcs', 3),
('Lettuce Head', 40, 'pcs', 5);
