--
-- structure of table `cus_settings`
--

CREATE TABLE IF NOT EXISTS `cus_settings` (
  `name_site` varchar(255) NOT NULL,
  `support_email` varchar(255) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_picture` text NOT NULL,
  `display_notification_module` tinyint(1) NOT NULL,
  `display_email_support` tinyint(1) NOT NULL,
  `display_number_updates` int(11) DEFAULT NULL,
  `user_email` varchar(255) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- data in table `cus_settings`
--

INSERT INTO `cus_settings` (`name_site`, `support_email`, `product_name`, `product_picture`, `display_notification_module`, `display_email_support`, `display_number_updates`, `user_email`, `user_password`) VALUES
('Demo Script', 'demo@gmail.com', 'Check Update Script', 'https://mtfo.fr/wp-content/uploads/1781098_718494971602545_903878449278406760_o.jpg', 1, 1, 10, 'admin@gmail.com', '$2y$10$eKoAZEmycVYCMnvHBqj2/eHr1vvJrXL6IjIR0PlzIyXRgD9U4TsPO');


--
-- structure of table `cus_updates`
--

CREATE TABLE IF NOT EXISTS `cus_updates` (
  `id` int(11) NOT NULL,
  `version` text NOT NULL,
  `beta` tinyint(1) NOT NULL,
  `name` varchar(255) NOT NULL,
  `download` text NOT NULL,
  `datetime` datetime NOT NULL,
  `purchase` tinyint(1) NOT NULL,
  `changelog` text NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

ALTER TABLE `cus_updates`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `cus_updates`
--

ALTER TABLE `cus_updates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
  
--
-- structure of table `cus_mails`
--

CREATE TABLE IF NOT EXISTS `cus_mails` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- data in table `cus_mails`
--

INSERT INTO `cus_mails` (`id`, `email`) VALUES
(1, 'default@mail.com');

ALTER TABLE `cus_mails`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT for table `cus_mails`
--
ALTER TABLE `cus_mails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
