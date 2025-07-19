CREATE TABLE `Users` (
  `user_id` bigint(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  `phone_number` varchar(20) NOT NULL,
  `name_id` bigint(11) NOT NULL,
  FOREIGN KEY (`name_id`) REFERENCES Names(`name_id`),
  `password` varchar(255) NOT NULL,
  `date_created` date NOT NULL DEFAULT CURRENT_DATE,
  `staff_type` enum('customer', 'inventory', 'staff') NOT NULL,
  `verified` boolean NOT NULL DEFAULT false,
  `resend_date` datetime,
  `expiration_date` datetime,
  `code` varchar(6)
)

