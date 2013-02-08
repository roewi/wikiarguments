SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `groupId` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(250) NOT NULL,
  `ownerId` int(11) NOT NULL,
  `dateAdded` bigint(20) NOT NULL,
  `visibility` tinyint(4) NOT NULL,
  `url` varchar(250) NOT NULL,
  PRIMARY KEY (`groupId`),
  UNIQUE KEY `url` (`url`),
  KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `group_permissions`
--

CREATE TABLE IF NOT EXISTS `group_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `permission` int(11) NOT NULL,
  `dateAdded` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `groupId` (`groupId`,`userId`),
  KEY `userId` (`userId`,`permission`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_groups`
--

CREATE TABLE IF NOT EXISTS `user_groups` (
  `userId` int(11) NOT NULL,
  `groupId` int(11) NOT NULL,
  `dateAdded` bigint(20) NOT NULL,
  UNIQUE KEY `userId` (`userId`,`groupId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

ALTER TABLE `questions` ADD `groupId` INT NOT NULL ,
ADD `type` TINYINT NOT NULL ,
ADD `flags` TINYINT NOT NULL;
ALTER TABLE `questions` ADD INDEX ( `type` , `groupId` , `questionId` );

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

ALTER TABLE `tags` ADD `groupId` INT NOT NULL;

-- --------------------------------------------------------

--
-- Table structure for table `localization`
--

ALTER TABLE `localization` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `loc_language` , `loc_key` ( 255 ) );

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

INSERT INTO `pages` (`pageId`, `pageTitle`, `url`, `className`, `templateFile`) VALUES
(14, 'new_group', 'new_group', 'PageNewGroup', 'newGroup.php'),
(15, 'manage_group', 'manage_group', 'PageManageGroup', 'manageGroup.php'),
(17, 'edit_argument', 'edit_argument', 'PageEditArgument', 'editArgument.php'),
(18, 'edit_counter_argument', 'edit_counter_argument', 'PageEditCounterArgument', 'editCounterArgument.php'),
(19, 'edit_question', 'edit_question', 'PageEditQuestion', 'editQuestion.php'),
(22, 'faq', 'faq', 'PageFaq', 'faq.php');
