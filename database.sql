-- Database: restaurant_system

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','staff','customer') NOT NULL DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`fullname`, `username`, `password`, `role`) VALUES
('Administrator', 'admin', '$2y$10$TDYwePCq4Jc3.Zr4iyKzoe3Eu0xDKgLZ0ob70D.S4884UWlBqdb1K', 'admin'),
('Staff Member', 'staff', '$2y$10$TDYwePCq4Jc3.Zr4iyKzoe3Eu0xDKgLZ0ob70D.S4884UWlBqdb1K', 'staff'),
('Test Customer', 'customer', '$2y$10$TDYwePCq4Jc3.Zr4iyKzoe3Eu0xDKgLZ0ob70D.S4884UWlBqdb1K', 'customer');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`name`, `price`, `image`) VALUES
('Burger', 12.50, 'assets/images/burger.png'),
('Pizza', 15.00, 'assets/images/pizza.png'),
('Pasta', 10.99, 'assets/images/pasta.png'),
('Grilled Salmon', 18.50, 'assets/images/salmon.png'),
('Caesar Salad', 9.50, 'assets/images/salad.png');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer` varchar(50) NOT NULL,
  `item` varchar(100) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('Pending','Completed') NOT NULL DEFAULT 'Pending',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`customer`, `item`, `quantity`, `total`, `status`, `order_date`) VALUES
('customer', 'Burger', 2, 25.00, 'Completed', NOW()),
('customer', 'Pizza', 1, 15.00, 'Pending', NOW());

COMMIT;
