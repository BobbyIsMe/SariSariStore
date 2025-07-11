CREATE TABLE `Customers` (
  `user_id` bigint(11) NOT NULL PRIMARY KEY,
  FOREIGN KEY (`user_id`) REFERENCES Users(`user_id`)
)