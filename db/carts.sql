CREATE TABLE `Carts` (
  `cart_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `user_id` bigint(11) NOT NULL,
  `status` enum('pending', 'approved', 'rejected', 'closed') NOT NULL DEFAULT 'pending',
  `date_time_deadline` datetime,
  `date_time_received` datetime,
  `date_time_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `type` enum('cart', 'order') NOT NULL DEFAULT 'cart',
  FOREIGN KEY (`user_id`) REFERENCES Users(`user_id`)
)

CREATE TABLE `Cart_Items` (
  `cart_id` bigint(11) NOT NULL,
  `product_id` bigint(11) NOT NULL,
  `variation_id` bigint(11) NOT NULL,
  `item_qty` int NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`cart_id`, `product_id`, `variation_id`),
  FOREIGN KEY (`cart_id`) REFERENCES Carts(`cart_id`),
  FOREIGN KEY (`product_id`) REFERENCES Products(`product_id`),
  FOREIGN KEY (`variation_id`) REFERENCES Variations(`variation_id`)
)