<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require("config.php");

if (file_exists("img/default_logo.png")){
	if(!file_exists("img/logo.png")){
		@rename("img/default_logo.png","img/logo.png");
	}
}

try {
     $db = new PDO("mysql:dbname=".USER_DB_NAME.";host=".USER_DB_HOST, USER_DB_USER, USER_DB_PASSWORD );
     $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );//Error Handling
     $sql ="CREATE TABLE IF NOT EXISTS `categories` (
 `cat_id` int(11) NOT NULL AUTO_INCREMENT,
 `cat_userid` int(11) NOT NULL,
 `cat_name` text NOT NULL,
 `cat_created` datetime NOT NULL,
 PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS `projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `project_name` text NOT NULL,
  `date_created` datetime NOT NULL,
  `last_edited` datetime NOT NULL,
  `category` int(11) NOT NULL DEFAULT 0,
  `promptstrict` int(11) DEFAULT 7,
  `tone` int(11) NOT NULL,
  `language` int(11) NOT NULL,
  `audience` text DEFAULT NULL,
  `p1_input` longtext NOT NULL,
  `s1` longtext DEFAULT NULL,
  `s2` longtext DEFAULT NULL,
  `s3` longtext DEFAULT NULL,
  `s4` longtext DEFAULT NULL,
  `s5` longtext DEFAULT NULL,
  `s6` longtext DEFAULT NULL,
  `s7` longtext DEFAULT NULL,
  `s8` longtext DEFAULT NULL,
  `s9` longtext DEFAULT NULL,
  `bonusnum1` int(11) NOT NULL DEFAULT 0,
  `bonusnum2` int(11) NOT NULL DEFAULT 0,
  `bonusnum3` int(11) NOT NULL DEFAULT 0,
  `bonusnum4` int(11) NOT NULL DEFAULT 0,
  `bonusnum5` int(11) NOT NULL DEFAULT 0,
  `bonusname1` text DEFAULT NULL,
  `bonusname2` text DEFAULT NULL,
  `bonusname3` text DEFAULT NULL,
  `bonusname4` text DEFAULT NULL,
  `bonusname5` text DEFAULT NULL,
  `bonusdesc1` text DEFAULT NULL,
  `bonusdesc2` text DEFAULT NULL,
  `bonusdesc3` text DEFAULT NULL,
  `bonusdesc4` text DEFAULT NULL,
  `bonusdesc5` text DEFAULT NULL,
  `bonusurl1` text DEFAULT NULL,
  `bonusurl2` text DEFAULT NULL,
  `bonusurl3` text DEFAULT NULL,
  `bonusurl4` text DEFAULT NULL,
  `bonusurl5` text DEFAULT NULL,
  `bonusthumb1` text DEFAULT NULL,
  `bonusthumb2` text DEFAULT NULL,
  `bonusthumb3` text DEFAULT NULL,
  `bonusthumb4` text DEFAULT NULL,
  `bonusthumb5` text DEFAULT NULL,
  `numdays` int(11) NOT NULL DEFAULT 1,
  `product_name` text DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE IF NOT EXISTS `users` (
 `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
 `user_name` varchar(255) CHARACTER SET utf8 NOT NULL,
 `user_email` varchar(255) CHARACTER SET utf8 NOT NULL,
 `user_password` varchar(255) CHARACTER SET utf8 NOT NULL,
 `user_logins` bigint(20) NOT NULL,
 `user_apikey` varchar(255) CHARACTER SET utf8 NOT NULL,
 `user_status` int(11) NOT NULL,
 `user_creation` datetime NOT NULL,
 `user_lastlogin` datetime NOT NULL,
 `user_credits` int(11) NOT NULL DEFAULT 0,
 `adnumber` int(11) NOT NULL DEFAULT 1,
 `user_txid_wplus` text,
 `user_txid_jvzoo` text,
 PRIMARY KEY (`user_id`),
 UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `bonuses` (
  `bonus_id` int(11) NOT NULL,
  `bonus_name` text DEFAULT NULL,
  `bonus_description` text DEFAULT NULL,
  `bonus_delivery` text DEFAULT NULL,
  `bonus_thumbnail` text DEFAULT NULL,
  `bonus_tags` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

INSERT INTO `bonuses` (`bonus_id`, `bonus_name`, `bonus_description`, `bonus_delivery`, `bonus_thumbnail`, `bonus_tags`) VALUES
(1, 'Webinar Conversions Blueprint', 'Announcing the brand new 9 part step by step video course, finally discover how to build high converting sales webinars from start to finish.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Webinar-Conversions-Blueprint-1.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/1a1jDAnHVlrdKkWv.png', 'webinar, video, training'),
(2, 'Video Product Blueprint', 'Discover how to launch your product through a series of videos that\'ll generate the buzz you need, starting today.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Video-Product-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/89atdY8Jqjjr6e09.png', 'video, product, training'),
(3, 'Entrepreneurial Action', 'This is an audio course containing 50+ mins of inspiring lessons that are going to teach you all about entrepreneurial action.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Entrepreneurial-Action.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/EKlAhpmUBo2swldB.png', 'entrepreneurial, audio, course'),
(4, 'Dominic Anderton Interviewing Sean Mize', 'This is an audio interview with Sean Mize containing 60+ mins with an interview with Dominic Anderton', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Dominic-Anderton-Interviewing-Sean-Mize.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/4ObZYKUxBUCrZFUQ.png', 'interview, audio, training'),
(5, 'Create Giveaway And Squeeze Page', 'This is an audio course containing 50+ mins of inspiring lessons that are going to teach you how to create giveaway and squeeze pages.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Create-Giveaway-and-Squeeze-Page+(1).zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/GmRoVIVr7baLcelz.png', 'audio, training, pages'),
(6, 'Attracting Clients Who Are Willing To Pay A Higher Rate', 'This is an audio course containing 60+ mins of inspiring lessons that are going to teach you to attract clients who are willing to pay a higher rate.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Attracting-Clients-Who-Are-Willing-to-Pay-a-Higher-Rate+(1).zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/4TSCH3LqhXgPq5iZ.png', 'audio, lessons, clients'),
(7, 'Advanced Sales Funnel Blueprint', 'This is an audio course containing 70+ mins of inspiring lessons that are going to teach you advanced sales funnel blueprint.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/Advanced-Sales-Funnel-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/gd0KA1CCWlPoGWiG.png', 'audio, course, funnel'),
(8, '10 Day Coaching Program', 'This is an audio course containing 80+ mins of inspiring lessons that are going to teach you marketing lessons.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Free+Downloads/new1/10-Day-Coaching-Program+(1).zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/VzmMryLal3u3YtQM.png', 'audio, course, marketing'),
(9, 'Weight Loss Tips For Life', 'Are you ready to learn valuable weight loss information, tips and strategies that will help you reach your goals.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Weight-Loss-Tips-For-Life.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/V74hGUSNP93iCKlE.png', 'weightloss, strategies, goals'),
(10, 'The Dieting Weight Loss Correction', 'Start dropping that weight you have been finding hard too by dieting the correct way.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/The-Dieting-Weight-Loss-Correction.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/i1BWsRNTe5h1jETg.png', 'dieting, goals, help'),
(11, 'The Basic Survival Guide', 'Are you prepared in the event of an emergency.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/The-Basic-Survival-Guide.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/d4cLJJ2UQG0P7xmx.png', 'survival, emergency, guide'),
(12, 'Survival Basics', 'Basic Survival Guide, can we do something to lessen the odds of ever becoming a victim.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Survival-Basics.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/uK6GkeGVYSEB6sVY.png', 'survival, guide, basics'),
(13, 'Popular Diets Ecourse', 'Everything you need to know about popular diets.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Popular-Diets-Ecourse.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/d93URWWMAVMbvKnZ.png', 'diet, ecourse, popular'),
(14, 'Healthy Juicing', 'Learn the importance of living a healthy lifestyle through the power of juicing.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Healthy-Juicing.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/KUn8ddunL6JZ0oQw.png', 'health, juice, important'),
(15, 'Forex Trading PLR Articles', 'To trade successfully in forex you must be able to understand the trading signals that can contribute greatly to your profits.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Forex-Trading-PLR-Articles.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/bsL4moUupfr5fIeK.png', 'plr, articles, trading'),
(16, 'Forex Trading For Newbies', 'How you can get on the forex trading action even if you have never made a trade in your life.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Forex-Trading-For-Newbies.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/T69Jxg7rNcVnJHSi.png', 'trading, newbies, forex'),
(17, 'Dating Ecourse', 'Online dating tips for women.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Dating-Ecourse.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/aJphfgKrs4nbnHeR.png', 'dating, online, tips'),
(18, '5 Steps To Online Dating Success', 'Everything you need to know about online dating.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/5-Steps-To-Online-Dating-Success.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/wLSos01UmuTXFfvN.png', 'dating, steps, online'),
(19, 'Content Publishing Profits', 'The content publishing profits course was designed to show even absolute beginners how publishing content online can be used to increase profits for business plus more.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Content/Content-Publishing-Profits.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/D3OzGO3qkvrw7Chv.png', 'content, publishing, beginners'),
(20, 'Content Marketing Novice', 'Have you been looking for a way to quickly increase awareness, traffic, and profits for your website?', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Content/Content-Marketing-Novice.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YAsS4K21vf1dilpy.png', 'content, marketing, traffic'),
(21, 'Content Marketing Interpreted', 'Pump up your profits by giving your best stuff away.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Content/Content-Marketing-Interpreted.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/EeGjfLDO5u7tRwhe.png', 'content, marketing, profits'),
(22, 'Content Creation Blueprint', 'Generate content the easy way.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Content/Content-Creation-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/07NboA1tAT1aSIRz.png', 'content, creation, blueprint'),
(23, 'Commission Strength', 'Maximize your commissions, this complete video training series will show you how to increase your earnings and boost profits with affiliate marketing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Commission-Strength.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/g2YZ8JmyK3wlOGdD.png', 'commission, video, training'),
(24, 'Bonus URL', 'Get your affiliate instantly up and running by giving them the easiest way ever to offer bonuses for your products today.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Bonus-URL.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/tx01g2YYdp0rKUaI.png', 'affiliate, bonus, products'),
(25, 'Amazon Associate Influence', 'A guide to mastering amazon associate by becoming an influential affiliate marketer.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Amazon-Associate-Influence.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/zwC7DYuGnm855rKB.png', 'amazon, affiliate, marketing'),
(26, 'Affiliate Rockstar Domination', 'The inside strategies of top online marketers.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Rockstar-Domination.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/k7CQMpBDiRMkCKj0.png', 'affiliate, online, marketers'),
(27, 'Affiliate Marketing Know All', 'A know all short report on the foundations of affiliate marketing help you succeed.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Marketing-Know-All.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/BppVuin9slkUMG1r.png', 'affiliate, marketing, report'),
(28, 'Affiliate Marketing A To Z', 'Easy steps to maximize your potential.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Marketing-A-To-Z.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/cLvkIEGHqHrlzeP7.png', 'affiliate, marketing, maximize'),
(29, 'Affiliate Link Cloaker', 'Increase clickthru rates and skyrocket your affiliate commissions by at least 300%.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Link-Cloaker.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/F4ZRHnyRbmUS3V4U.png', 'affiliate, link, commissions'),
(30, 'Affiliate Launch Profits', 'There are launches that are going on all the time and now you can be part of the action.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Launch-Profits.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/oj76wES4azTz6Lfk.png', 'affiliate, profits, launch'),
(31, 'Affiliate Funnel System', 'Learn everything there is to know about affiliate funnel system.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Funnel-System.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/lBfbzXemxxpZ31uL.png', 'affiliate, funnel, learning'),
(32, 'Affiliate Fire Extinguisher', 'A simple tool that creates special link hiding pages, which can significantly boost profits.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Fire-Extinguisher.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/bd01uL4ja0PhPfJA.png', 'affiliate, profits, boost'),
(33, 'Affiliate Cash Monster', 'Discover how to earn monster profits and launch automated marketing campaigns.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/Affiliate-Cash-Monster.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/cVGhd7twlaeghB93.png', 'affiliate, marketing, campaigns'),
(34, 'A Is For Affiliate', 'Learn the ABCs of affiliate marketing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Affiliate/A-Is-For-Affiliate.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/gXgEhzvVhCi0TXx6.png', 'affiliate, marketing, training'),
(35, 'eCommerce Shortcut', 'This 6 part video course is going to help you know the ropes to get your foot in the door.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/eCom/eCommerce-Shortcut.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/OXkpapK9oxTzHhvq.png', 'ecommerce, video, course'),
(36, 'eCom Mastery', 'New tools have made it easier than ever to set up their own store, and less expensive than ever, to get started in e-commerce.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/eCom/Ecom-Mastery.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/DXUeAA7DTU6LgFu0.png', 'ecommerce, mastery, training'),
(37, 'Ecom Traffic Generator', 'Get traffic to your e-store with your own affiliate referral program.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Traffic/eCom-Traffic-Generator.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/PiybZ2jCyVCisFOz.png', 'ecommerce, traffic, affiliate'),
(38, 'Hot Selling eCom Products', 'Learn the tricks and the trades of some of the hottest selling e-commerce products to date.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Online/Hot-Selling-eCom-Products.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/j37pt1CYa1KwKEP2.png', 'ecommerce, products, training'),
(39, 'Ecommerce Treasures', 'A 10 part video course for beginners all about eCommerce profits.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Online/Ecommerce-Treasures.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/PjoMMwhoCaNo8QGb.png', 'ecommerce, video, profits'),
(40, 'Viral Marketing Blueprint', 'A comprehensive series of 20 in depth videos teaching you all the important elements of creating and launching profitable viral marketing campaigns without an expensive cost.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Marketing/Viral-Marketing-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/dGPVpXYIttqOwhON.png', 'marketing, video, campaigns'),
(41, 'Rapid Offline Profits', 'Your rapid guide to making money offline.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Marketing/Rapid-Offline-Profits.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/zzr0tbCHmBWHKMZi.png', 'offline, profits, guide'),
(42, 'Mobile Marketing Made Easy', 'Discover the proven formula used by the most successful internet marketers to build their mobile list, boost sales and strengthen customer relations.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Marketing/Mobile-Marketing-Made-Easy.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Ud2jQCCaUf7x5UdB.png', 'marketing, mobile, formula'),
(43, 'Mobile 2 Step Opt In Generator', 'Create mobile two step opt-in pages in just minutes.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Marketing/Mobile2StepOptInGen_mrr.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/PEpUtvEUFJxja0b8.png', 'mobile, generator, pages'),
(44, 'Ezine Marketing A To Z', 'Get paid for sharing what you know best.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Marketing/EZine-Marketing-A-To-Z.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/TvZd2I692msJZAuI.png', 'marketing, sharing, training'),
(45, 'Modern Email Marketing', 'Build profitable list with these modern email marketing and segmentation techniques.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Modern-Email-Marketing-And-Segmentation.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/JNYB8ypSC9duPx2K.png', 'email, marketing, profitable'),
(46, 'Getting Started With Email Marketing', 'If you love copy and paste, then you\'ll love how easy it is to put this course to work for your business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Getting-Started-With-Email-Marketing.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Flnebfejo6bHEQ18.png', 'email, marketing, course'),
(47, 'Email Marketing Profits', 'How to make a full time living with email marketing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Email-Marketing-Profits.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/36GfYcQ9kdyqApaF.png', 'email, marketing, profitable'),
(49, 'Email Domination Bonus', 'A 20 part video course for beginners all about email marketing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Email-Domination.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/xDfcGzbdQK18bVLB.png', 'email, video, beginners'),
(50, 'Building A Relationship With Your List', 'How to build stronger bonds, instill stronger credibility and increase your opt-in list response with relative ease.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Building-A-Relationship-With-Your-List.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/muv2ULJ7UE1dCo4j.png', 'list, opt-in, relationship'),
(51, 'Build Your List', 'The very best, latest and fastest list building methods and techniques.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Build-Your-List.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/ZdpBHmzc1oW4EJZe.png', 'list, methods, techniques'),
(52, 'Build You Audience', 'What you are doing wrong and why your site or business has no traffic.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Build-Your-Audience.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/IF90WCE1N8SYs0qi.png', 'audience, traffic, business'),
(53, '37 List Building Quick Tips', 'The most profitable opt-in list building secrets uncovered and revealed.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/37-List-Building-Quick-Tips.rar', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/782jBxLc6EfDu4LO.png', 'list, secrets, profitable'),
(54, 'Viral Master List Builder', 'Build a highly profitable optin mailing list automatically.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Viral-Master-List-Builder.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/I8Ey6efhYyY2x5Rg.png', 'list, mailing, profitable'),
(55, 'Article Site Builder', 'Discover the ultimate lazy 3-step formula for building your own profitable mailing list on autopilot.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Email+List+Building/Article-Site-Builder.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/KRSPDx8Isuu20k0S.png', 'article, mailing, profitable'),
(56, 'New Funnel Hacking', 'Learn how to filter your target prospects and convert them into loyal customers.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Funnels/FunnelHacking_plr.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/o5VvjOqb5BYI7La0.png', 'funnel, customer, target'),
(57, 'Internet Marketing Funnels', 'If you can copy and paste, then you\'ll love how easy it is to put this course to work for your business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Funnels/Internet-Marketing-Funnels.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/E6fukwfrAfJKmq26.png', 'marketing, funnel, business'),
(58, 'Funnel Hacking', 'Create a high converting sales funnel that gets sales.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Funnels/Funnel-Hacking.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/NouF4OoqnPt62rx5.png', 'funnel, sales, converting'),
(59, 'Build Passive Cash Funnels', 'Building passive cash funnels from scratch.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Funnels/Build-Passive-Cash-Funnels.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YOmfGEMNaPTwN60C.png', 'funnels, profit, building'),
(60, 'Clickbank Membership Site', 'Everything you need to know to be successful and achieve your goal.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Marketing/Clickbank-Membership-Sites.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Cg94kpirLd9a8z8C.png', 'membership, sites, goal'),
(61, 'Monthly Membership Profits', 'Set up and use membership sites to increase profits for any business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Membership/Monthly-Membership-Profits.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Q4HL5BWj5vwfCdHJ.png', 'membership, profits, business'),
(62, 'Membership Site Continuity Quick Start Guide', 'The easy way to build a profitable membership website.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Membership/Membership-Site-Continuity.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/q97Cb8FLSzen8YwS.png', 'membership, guide, profitable'),
(63, 'Membership Sales Blueprint', 'Discover the actionable, step by step system to fully master the membership site sales process.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Membership/Membership-Sales-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/iuFWyVxHfIo3i2IO.png', 'membership, sales, training'),
(64, 'Membership Mogul', 'The easy way to build a profitable membership website.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Membership/Membership-Mogul.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/MeveonCmqZfhRGzo.png', 'membership, profitable, website'),
(65, 'Building Influence With Free Membership Sites', 'Quickly and easily build your online influence with membership sites.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Membership/Influence-With-Free-Membership-Sites.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/qWodoIbRh5geqnCz.png', 'membership, online, build'),
(66, '100 Membership Site Marketing Tricks', 'Within this ebook you\'ll get 100 membership marketing tricks.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Membership/100-Membership-Site-Marketing-Tricks.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/K7t8SffwQiiD4FII.png', 'membership, ebook, marketing'),
(67, 'Whitelabel Profit Plan', 'Proven strategies to making money with white label licensing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Online/White-Label-Profit-Plan.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Z5sFzHX2kbQ3SheY.png', 'whitelabel, profit, strategies'),
(68, 'Networking Revolution', 'The home business marketing expansion series.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Online/Networking-Revolution-The-Home-Business-Marketing-Expansion-Series.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/CkkaBTnHeXmUyZWD.png', 'networking, marketing, business'),
(69, '7 Infamous Resell Rights Questions', '7 popular questions on resell rights answered.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Online/7-Infamous-Resell-Rights-Questions.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/9t95KBwUWIZYeKiW.png', 'resell, popular, answered'),
(70, 'Software Profit Mastery', 'Complete step-by-step formula for creating high value software products without any coding or programming experience.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Online/Software-Profit-Mastery.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YwqQ4PqQjwseutKT.png', 'software, formula,products'),
(71, 'Rebranding PLR Videos', 'This step-by-step, 9 part video series takes you by the hand and shows you how to take your private label rights videos to the next profit level.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/PLR/Rebranding-PLR-Videos.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/8SpiYnJeB4oEKCEu.png', 'plr, videos, training'),
(72, 'Private Label Masters Course', 'Become a private label master.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/PLR/PLR-Masters-Course.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Ec5xvPSVscwd74rh.png', 'masters, private, course'),
(73, 'PLR Power System', 'Complete A-Z system for launching a powerful private label rights business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/PLR/PLR-Power-System.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/dEPPthRUK2L1xYHJ.png', 'plr, business, launch'),
(74, 'Product Creation', '10 keys to creating internet marketing products for maximum traffic and profits.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Products/Product-Creation-Success.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/9t6Yb4vYgnkdcOtZ.png', 'product, marketing, traffic'),
(75, 'Dropshipping Speed Bumps', 'How to overcome common dropshipping hurdles so you can build a profitable e-commerce business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Products/Dropshipping-Speed-Bumps.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/1jvHyO0x49cQI5QQ.png', 'dropshipping, ecommerce, profit'),
(76, 'Dropshipping Simplified', 'Making money with dropshipping services has never been easier.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Products/Dropshipping-Simplified.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/ukGoMOvWoTEUmEJl.png', 'dropshipping, profit, training'),
(77, 'Dropshipping Secrets', 'The secrets of selling physical products without any stock.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Products/Dropshipping-Secrets.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/aZvt5CrUmadc72C6.png', 'dropshipping, products, secret'),
(78, 'Social Media Intelligence', 'A comprehensive guide all about gaining traffic from social media.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Social+Media/Social-Media-Intelligence.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/HCXSiwhTiGD9EStP.png', 'socialmedia, traffic, guide'),
(79, 'Google Plus Money Making Tactics', 'The most complete and easy to follow google cash blueprint ever.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Social+Media/Google-Plus-Money-Making-Tactics.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/NDom6Rgkne1wp44a.png', 'tactics, money, google'),
(80, 'Facebook Quiz Creator', 'Discover how you can easily create fun, entertaining Facebook social quizzes and get free viral traffic in just a few clicks.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Social+Media/Facebook-Quiz-Creator.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/OnwbzuyE31APWsLb.png', 'facebook, free, traffic'),
(81, 'Facebook Business Basics', 'An introduction to the power of Facebook and what it can do for your business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Social+Media/Facebook-Business-Basics.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/UlSb99P5putJC4Ey.png', 'facebook, business, power'),
(82, '120 Social Media Profit Tips', 'This video tutorial course will teach you everything you\'re needing to know about social media marketing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Social+Media/120-Social-Media-Profile-Tips.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/pWqZVxbAFdeET4Wk.png', 'socialmedia, video, tutorial'),
(83, 'WP Video Focus', 'WP Video Focus is a plugin that allows you to clip your video and serves as a widget to any corner on your page.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/WordPress/WP+Video+Focus.12046.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/LptX4odqeNdhsXhN.png', 'video, wordpres, plugin'),
(84, 'WP Sales Graphics', 'Quickly create professional graphics to use inside your blog posts.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/WordPress/WP-Sales-Graphics.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/97TPN1hB2KTZk6Tv.png', 'wordpress, sales, blog'),
(85, 'WP Sales Funnel', 'Discover how to use WordPress to launch automated list building funnels and turn them into sales getting machines.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/WordPress/WP-Sales-Funnel.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/5LdnMQva3NWNcujB.png', 'wordpress, sales, funnel'),
(86, 'WP Offline Pricing Pro', 'Impress your clients with professional pricing pages in WordPress.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/WordPress/WP-Offline-Pricing-Pro-Plugin.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/BHy6O8YTdTPwAofO.png', 'wordpress, pages, clients'),
(87, 'WP List Formula', 'Ultimate collection of twenty-four WordPress list building How To Training Videos.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/WordPress/WP-List-Formula.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/tXPv1LXej8isudwe.png', 'wordpress, training, videos'),
(88, 'WP In-Content Popup Pro', 'Create attention grabbing popups within your content.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/WordPress/WP+In-Content+Popup+Pro.12038.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/RALImKMvfbIQNcmL.png', 'wordpress, popup, content'),
(89, 'WP FaceBook Quiz Creator', 'Easily create fun, entertaining Facebook quizzes with just a few clicks of your mouse.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/WordPress/WP+FaceBook+Quiz+Creator.12037.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/rgYfTmZxWonAS9SN.png', 'wordpress, quiz, traffic'),
(90, 'Making Money With WordPress', 'A guide to learning how to make money from your WordPress blog.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/WordPress/Making-Money-With-WordPress.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/kg3NwpWQgoFjfBNg.png', 'wordpress, guide, blog'),
(91, 'Install SEO WordPress', 'Get ranked in search engines such as google or bing.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/WordPress/Install-SEO-Wordpress.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/MP9EnKJFV3DFY5Ji.png', 'seo, wordpress, ranked'),
(92, 'Audio Collection Volume 2', 'We have 100s of audio tracks that can be used in all your videos or videos for your clients.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Stock+Graphics+Sounds+Video/Audio+Collection+Volume+2.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/F5YmqefFKVBQbAd7.png', 'audio, videos, clients'),
(93, 'Audio Collection Volume 1', 'We have 100s of audio tracks that can be used in all your videos or videos for your clients.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/Stock+Graphics+Sounds+Video/Audio+Collection+Volume+1.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YoJt3NDnVJ6OYJ9j.png', 'audio, videos, clients'),
(94, 'The Melody Music Volume 4 Cinematic', 'A collection of melody music that you and your inner self will enjoy.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/The-Melody-Music-Volume-4-Cinematic.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/61lLqR7zdvTUvigN.png', 'music, melody, audio'),
(95, 'Stock Audio Sound Clips', 'This is a collection of 32 audio sounds that can be used for anything you are wanting.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/Stock-Audio-Sound-Clips-Volume-10-Emergency.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/6E3lo241gZSvMnLu.png', 'audio, stock, sound'),
(96, 'Music Audio Loops Edition 8', 'Ideal for anyone who is needing background music.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/Music-Audio-Loops-Edition-8.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/t8UIUUzQOBJlpZqe.png', 'audio, music, background'),
(97, 'Music Audio Loops Edition 3', 'Studio quality music audio loops used for background music.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/Music-Audio-Loops-Edition-3.zip', 'https://s3.console.aws.amazon.com/s3/object/inspiredsoft?region=us-east-1&prefix=thumbsai/8Yoi0B1e8JrV6kDY.png', 'audio, music, background'),
(98, 'Music Audio Loops Edition 2', 'Studio quality music audio loops used for background music.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/Music-Audio-Loops-Edition-2.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/8dxGtpN8wRvfpAg6.png', 'audio, music, background'),
(99, 'Music Audio Loops Edition 1', 'Studio quality music audio loops used for background music.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/Music-Audio-Loops-Edition-1.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/rGQRmtCpPqDOuxoz.png', 'audio, music, background'),
(100, 'HD Time Lapse Stock Footage Collection', 'A collection of 8 high definition time lapse stock footage that are all high quality.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/HD-Time-Lapse-High-Quality-Stock-Footage-Collection.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/83jssE6HbvpGyx4s.png', 'footage, stock, timelapse'),
(101, '500 Sound Effects Collection', 'This is a collection of 8 cinematic music melodies from the \'The Melody Music\' collection.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Stock+Graphics+Sounds+Video/500-Sound-Effects-Collection.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/yS28NND83Ul375ep.png', 'cinematic, music, melodies'),
(102, 'Small Business Content Toolkit', 'Tools, instruction and content for marketing a small business.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Small-Business-Content-Toolkit.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/UBp8pHwFd65cKTLq.png', 'business, toolkit, marketing'),
(103, 'Money Making Online', 'Is it really possible to earn money on the internet?', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Making-Money-Online.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/NVMjBfug3yLA5SET.png', 'internet, money, online'),
(104, 'Get Paid To Write A Book', 'Write a non-fiction book proposal and then sell it.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Get-Paid-To-Write-A-Book.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/HNrPnHr1SW6jQqrD.png', 'book, paid, write'),
(105, 'Easy Sales Blueprint', 'Learn the closely guarded, jaw-dropping system all successful internet marketers use to pack crazy cash into their bank accounts.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Easy-Sales-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/68M1G2BlCjEfmWkw.png', 'sales, blueprint, marketers'),
(106, 'Copywriting Formula', 'A know-all short report on the foundations of copywriting formula.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Copywriting-Formula.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/MIvaKMtc1XzwJBSx.png', 'copywriting, report, formula'),
(107, 'Clipboard Spyware Defender', 'Protect yourself, even from threats that many AVs/ASs can\'t detect.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Clipboard-Spy-Defend.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/5GEzsGEzj95QC6iF.png', 'spyware, protect, defender'),
(108, 'Character Building', 'How to build stronger character, tougher mind, and create breakthroughs.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Character-Building.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/tt8XZXlqq3IwXyIK.png', 'building, breakthroughs, character'),
(109, 'Business Patron', 'Learn how simple tweaks can bring significant change to the future of your organization.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Business-Patron.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/VSNzF7PLSLh9NQc5.png', 'business, training, organization'),
(110, 'Becoming Stronger', 'A ten minute full body workout that anyone can use.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Becoming-Stronger.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/w7TEGT7UE9DXqoRo.png', 'workout, fullbody, stronger'),
(111, 'Amazon S3 Supremacy', 'A beginners 9 part video course all about amazons3.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Amazon-S3-Supremacy.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/hLZ0e7qv4Qamka9R.png', 'amazons3, video, course'),
(112, '10k Blueprint', 'A step-by-step guide to earning $10,000+ a month.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/10K-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/kNAkKNsdAKT6GBT2.png', 'guide, blueprint, 10k'),
(113, '10k Blueprint Upgrade Package', 'A step-by-step guide to earning $10,000+ a month, upgraded.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/10K-Blueprint-Upgrade-Package.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YiDW84PdtDyomhPX.png', 'guide, blueprint, 10k'),
(114, 'Manifest Your Dreams', 'Discover the power of positive thinking and how you can make your dreams really come true.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Manifest-Your-Dreams.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/8GLFUuQUXYpAuOsZ.png', 'manifest, positive, dreams'),
(115, 'Audio Niche', 'You can make interactive multi-media courses that your users love without even having to speak. Audio niche automator is going to add a new level of personality to your products.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Productivity/Audio-Niche-Automator.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/RrcnjXCCWcov9wIY.png', 'audio, course, products'),
(116, 'Video Teaser Blueprint', 'Learn how to build video teasers that sell your video products. These methods have been working for the lase decade.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/Video-Teaser-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/VegVrH7eEMkErEpF.png', 'video, blueprint, products'),
(117, 'YouTube Video Mastery', 'This is a 3 part video course that will help you within creating videos using the free YouTube creation tool, using keywords and monetizing videos.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/YouTube-Video-Mastery.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/6JrIboHJBi2egzmX.png', 'youtube, video, course'),
(118, 'YouTube Outro Clips', '25 unique outro clips made for youtube videos.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/YouTube-Outro-Clips.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/p6V8bmomf9pV1FZL.png', 'youtube, video, clips'),
(119, 'YouTube Money Machine', 'Inside this easy to follow 5 day crash course you will be introduced to the basics of using YouTube.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/YouTube-Money-Machine.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/wJE6pLCx8n0zUs9O.png', 'youtube, basics, course'),
(120, 'Traffic Strategies For Your YouTube Channel', 'Learn how to drive traffic to your YouTube channel with proven methods that work.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/Traffic-Strategies-For-Your-YouTube-Channel.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/uCQ4Jj9J3FxX5P8q.png', 'youtube, traffic, strategies'),
(121, 'Understanding YouTube For Beginners', 'Inside this easy to follow 5 day crash course you will be introduced to the basics of using YouTube.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/Understand-YouTube-For-Beginners.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/W7V9kyPUcxH9HUE1.png', 'youtube, beginners, course'),
(122, 'The YouTube Affiliate', 'This video course will teach you all the ropes on how to be successful in becoming a YouTube affiliate marketer.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/The-YouTube-Affiliate.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/ak7ofhDAm2QEnnGl.png', 'youtube, affiliate, marketer'),
(123, 'Set Up A Video Sales Funnel', 'How to set up a video sales funnel, inside this package is a series of video tutorials that will guide you to setup video sales funnel.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/VAULT_SetUpAVideoSalesFunnel_PLR_RR.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/xErxEqiJ9w9hNRdi.png', 'video, funnel, tutorial'),
(124, 'Expert Graphic Video Pro', 'Discover the lazy mans way to learning graphic design and get paid top dollar for you work the pro way.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/Expert-Graphics-Videos-Pro.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/CgD5poiwnoQ3yVn9.png', 'video, graphic, tutorial'),
(125, 'Expert Graphic Video', 'Discover the lazy mans way to learning graphic design and get paid top dollar for your work.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/Expert-Graphics-Videos.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/yZHnoPtCQKtnl7ps.png', 'video, graphic, tutorial'),
(126, 'Youtube Channel SEO', 'Imagine if you could build sustainable recurring traffic from your YouTube channel to your products and services...', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/YouTube-Channel-SEO.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/WAMeeybOM2XON096.png', 'youtube, seo, traffic'),
(127, 'YouTube Black Hat', 'This 5 part video training course will teach you everything you need to know about marketing on youtube for hordes of traffic.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Video/YouTube-Black-Hat.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/OZZ77MjDntshBAOv.png', 'youtube, video, marketing'),
(128, 'Traffic Machine', 'If you\'re ready to learn the secrets to turning your website into a traffic machine. This is a detailed guide outlining 5 powerful strategies that will help you increase website traffic.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Traffic/Traffic-Machine.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/FPUthbORaWeArkP4.png', 'traffic,website, strategies'),
(129, 'Secret Instant Traffic Source', 'You are going to learn how to tap into some unknown sources of free traffic within this 5 part video tutorial to gain you more traffic.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Traffic/Secret-Instant-Traffic-Sources.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/GrDAU5KdgAqhJqFP.png', 'traffic, video, tutorial'),
(130, 'Hot Paid Traffic Source', 'Getting traffic whether its free or paid is not so easy these days. This new 3 part over the shoulder video course which is going to teach you how to get traffic.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Traffic/Hot-Paid-Traffic-Sources.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/FljQu7rZItTMqF8m.png', 'traffic, video, course'),
(131, 'Easy Traffic Videos', 'Inside you will find a video course packed with material on various FREE traffic sources.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Traffic/Easy-Traffic-Video.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/cR8oieT1oqAHYll3.png', 'traffic, video, sources'),
(132, 'Web Traffic Spreader', 'Advertising online can be an expensive business, with quality solo ads often costing up to a hundred dollers or more. And quite often those ads just don\'t perform as well as you might have hoped.', 'https://s3.amazonaws.com/inspiredsoft/BONUSES/Traffic/Web-Traffic-Spreader.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/cs1gkfxADMGEKiCU.png', 'traffic, advertising, business'),
(133, 'Resume Rockstar', 'Insider tips and tricks to making your resume stand out', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Resume-Rockstar.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/d0PFANX8AwhWMZb3.png', 'resume, tips, standout'),
(134, 'Powerful Mind', 'Discover the importance of changing the state of mind for powerful growth and development.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Powerful-Mind.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/bOAziNhvP7VBvLtW.png', 'mind, growth, development'),
(135, 'Top 6 Paid Underground Traffic Sources', 'Discover the top six paid underground traffic sources for your website.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Top-6-Paid-Underground-Traffic-Sources.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/C0chay8m5DRKBDfJ.png', 'traffic, website, sources'),
(136, 'Mastering Facebook', 'Learn the secrets of mastering facebook', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Mastering-Facebook.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/hME2gzsWpCitJWSA.png', 'facebook, secrets, master'),
(137, 'Internet Marketing Methods Case Study', '$2k and 400 subscribers case study', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Internet-Marketing-Methods-Case-Study.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/VdsVOWCfdufJWZ8d.png', 'internet, marketing, casestudy'),
(138, 'Build A Funnel On Clickfunnels', 'Quickly create beautiful sales funnels that convert your visitors into leads and then customers without having to hire or rely on a tech team', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Build-a-Funnel-on-ClickFunnels.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/FFTwp12X0FDKfDOA.png', 'funnel, clickfunnel, convert'),
(139, '21 Instagram Marketing Tricks', 'This is not your typical marketing tricks you have seen on the blog or via article. Instead, this is the system we have been using to boost traffic in a matter of minutes.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/21-Instagram-Marketing-Tricks.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Lk6fILlpD9A4e5wW.png', 'instagram, marketing, traffic'),
(140, 'Instagram For Entrepreneurs', 'Instagram for entrepreneurs is the nononsense, straight to the point methods anyone can use to attract followers, generate free traffic and increase their brands visibility through Instagram\'s photos and video sharing platforms.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Instagram-For-Entrepreneurs.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/6hkaaAAzUzmMhMtu.png', 'instagram, traffic, entrepreneurs'),
(141, 'Instagram Mastery', 'Instagram is the fastest growing social media platform for driving targeted buyer traffic for your offers without investing a fortune.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Instagram-Mastery.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/TFSKa5iJEF8Udiba.png', 'instagram, socialmedia, traffic'),
(142, 'Instagram Profit Map', 'Want to learn how to turn Instagram into a long-term profit pulling machine, the right way.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Instagram-Profit-Map.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/CZPy0Y2wtSDXD9jb.png', 'instagram, profit, training'),
(143, 'Maggazzine Instagram', 'As visual content gets more sought after on Social Media, so does the need for brands to create as well as leverage.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Maggazzine-Instagram.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/jHw2zzkGKJP8OH1x.png', 'socialmedia, magazine, instagram'),
(144, 'Rapid Instagram Traffic', 'Its no secret that Instagram has grown to be an incredible powerful social media platform.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Rapid-Instagram-Traffic.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/0ZqURRrLnXvejtyO.png', 'traffic, socialmedia, instagram'),
(145, 'Trending Keywords WordPress Plugin', 'Find the most popular keywords that people are actually searching for from all six of the worlds biggest search engines.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Trending-Keywords-WordPress-Plugin.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/JK7Cf8EQb7JSpxUX.png', 'wordpress, plugin, keywords'),
(146, 'Proven Funnel Formula', 'Discover how to generate micro-targeted leads that are ready to buy your products and services, starting today.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Proven-Funnel-Formula.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/kCeyr0PM9KB6WH2z.png', 'leads, funnel, products'),
(147, 'Profit Builders', 'Find out how you can build a mailing list of hungry buyers in a matter of a few days - all without breaking the bank.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Profit-Builders.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/awgALLUm1CxjY4ce.png', 'mail, buyers, profit'),
(148, 'YouTube Sponsorship Income', 'You don\'t need a large YouTube channel to earn 6 figures per year from sponsorship and brand deals. Here\'s the lowdown on how to do it...', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/YouTube-Sponsorship-Income.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/GZwMQvaGAChZY5Ap.png', 'youtube, sponsorship, income'),
(149, 'Video Launch Method', 'Discover how to launch your product through a series of videos that\'ll generate the buzz you need, starting today.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Video-Launch-Method-1.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/sY1W0JZnIippl4HJ.png', 'video, product, launch'),
(150, 'Start Making Money Online', 'Earning significant income using the Internet doesn\'t require any specific skill or a long history of becoming familiar with computers or the internet itself.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Start-Making-Money-Online.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/A9gtYeZP5mIxoAAV.png', 'online, internet, money'),
(151, 'SEO Predictions', 'Discover the secret predictions of search engine optimisation experts.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/SEO-Predictions.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YxdPpt9W6DtuGrdL.png', 'seo, secret, online'),
(152, 'Secret Sauce Strategies', 'The target market is a no brainer, anyone who wishes to be better their life strategies to be the best they can be.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Secret-Sauce-Strategies.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/YO7wDmajFyfRTd6F.png', 'market, strategies, secret'),
(153, 'PLR Article Mega Pack', 'Some of the categories in the pack include home and family, travel and tourism, health and fitness and many more.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/PLR-Article-Mega-Pack.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/qBINBhUJ24pl8U0c.png', 'plr, article, pack'),
(154, 'Commission Strength', 'This complete video training series will show you how to increase your earnings & boost profits with affiliate marketing, launch profitable campaigns and get free traffic to all your offers', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Commission-Strength.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/zOZbs23ssnW0L01P.png', 'commission, video, training'),
(155, 'Buyers Traffic Funnel', 'How to build your funnel for attracting highly targeted buyer traffic on a daily basis.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Buyers-Traffic-Funnel.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/C5i0VlSM2pP647Mt.png', 'traffic, funnel, buyers'),
(156, 'Blogging Tactics PLR Articles', 'If you\'re a blogger or are hoping to become a blogger down the road, the content in these articles are definitely an eye-opener.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Blogging-Tactics-PLR-Articles.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/W5a0P8N9Nw36MfIX.png', 'blog, plr, articles'),
(157, 'Online Businesses For Moms', 'Do you want to start your own online business as a stay at home mom?', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Online-Businesses-For-Moms.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/etmawC5od9VQ6y5F.png', 'online, business, own');
INSERT INTO `bonuses` (`bonus_id`, `bonus_name`, `bonus_description`, `bonus_delivery`, `bonus_thumbnail`, `bonus_tags`) VALUES
(158, 'Mastering The Plan Mechanics', 'Ready to maximise your efforts with proper strategies. Simple, yet advanced analysis on network marketing plans to fire up your network and triple your income.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Mastering-The-Plan-Mechanics.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/xbS4K0Cw7roQZP2P.png', 'strategies, marketing, network'),
(159, 'Making Money From Your Hobby', 'What if you could find a way to enjoy your hobby and make a little money?', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Making-Money-From-Hobbies.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/PgSGvSHYWMXR0o97.png', 'hobby, money, earning'),
(160, 'Leadership Influence', 'Do you want to become more knowledgeable learning about becoming an influential leader.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Leadership-Influence.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/1oMK4aHufeOiFToH.png', 'leader, knowledge, learning'),
(161, 'GDPR Cookie Express Plugin', 'Brand new WordPress plugin for the GDPR cookie compliance policy.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/WP-GDPR-Cookie-Express-Plugin.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/rBpTGCDkU6nroKSc.png', 'wordpress, plugin, gdpr'),
(162, 'eCover Black Pack', 'Would you like to be finally free of these high prices once and for all and be able to create your own graphics with a simple to use system.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/eCover-Black-Pack.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Hv60LBMPvnsTvF1o.png', 'graphics, system, training'),
(163, 'Customer Tested Buying Triggers', 'This is an audio eBook training teaching you how to test customers buying triggers.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Customer-Tested-Buying-Triggers.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/X25QWLoHyrbRmcDz.png', 'audio, ebook, training'),
(164, 'All In One Free Stock Photos', 'The all in one free stock photos plugin will allow you to find and insert free stock photos from the top 3 free stock photo sites quickly and easily.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/WP-Free-Stock-Photo-Plugin.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/H9glc7rlnq7IAmMO.png', 'photos, stock, plugin'),
(165, '177 Audio Sound Clips', 'This is a collection of 177 Audio Sounds that can used for anything you are wanting.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Stock-Audio-Sound-Clips-Volume-30-Vehicle.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/R0u1ZTPEwN0yxGU8.png', 'audio, clips, sounds'),
(166, 'WP Bulk Article Importer Plugin', 'Here is the plugin you can use to import many articles at one go.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/WP-Bulk-Article-Importer-Plugin.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/3189lM2gkG8bNSEO.png', 'plugin, wordpress, articles'),
(167, 'WP Blog Roll Link Exchange Plugin', 'Automatically exchange links with your visitors and build quality backlinks the easy and passive way.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/WP-Blog-Roll-Link-Exchange-Plugin.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/IlmlMYCNOHezfgtp.png', 'wordpress, blog, visitors'),
(168, 'Understanding Private Label Content', 'Brand new private label course lets you easily teach customers & subscribers how to use private label content to build their business, get more traffic and make more money.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/Understanding-Private-Label-Content.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/TXh6WgR59fUOTTQ5.png', 'traffic, customers, subscribers'),
(169, 'Road To PLR Riches', 'Your path to private label rights riches in the internet marketing niche.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/Road-To-PLR-Riches.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/TtCDVOkZwS7shuHO.png', 'plr, internet, marketing'),
(170, 'Recurring Profits Avalanche', 'How to create the ultimate residual goldmine that blows out paychecks on complete autopilot.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/Recurring-Profits-Avalanche.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Ntvsks0y1UIiCFVg.png', 'autopilot, recurring, business'),
(171, 'PLR Influence', 'When it comes to making money online, there are many individuals who move on to the next opportunity when they do not fully understand how one works.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/PLR-Influence.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/CKa9QFEX0tfn3cm3.png', 'plr, online, money'),
(172, 'Large Solo Ad Vendors And List Brokers', 'Large Solo Ad Vendors And List Brokers - if you need to build a list fast, you can buy these big boys to send you lots of traffic fast.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/Large-Solo-Ad-Vendors-And-List-Brokers.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/zLdY1GlK1qF0WAps.png', 'ad, list, traffic'),
(173, 'IM Collection Series Vol 3', 'This collection of eBooks, you can resell each one of them keeping 100% of the profits.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/IM-Collection-Series-Vol-3.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/NCwMkEr3EYMEJWM1.png', 'ebook, profits, resell'),
(174, 'IM Collection Series Vol 2', 'This collection of eBooks, you can resell each one of them keeping 100% of the profits.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/IM-Collection-Series-Vol-2.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/UzdVHxypU8pnSQDW.png', 'ebook, profits, resell'),
(175, 'IM Collection Series Vol 1', 'This collection of eBooks, you can resell each one of them keeping 100% of the profits.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/october+bonuses/IM-Collection-Series-Vol-1.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/ebdekgfxsm8Z0j7Q.png', 'ebook, profits, resell'),
(176, 'YouTube Case Studies', 'Video training lessons that features case studies of five successful YouTubers.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/YouTube-Case-Studies.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/WdcpOmPfatPb4e6b.png', 'youtube, video, training'),
(177, 'WP Squeeze Question Plugin', 'How are you going to sort out those visits to your website? The quick answer to that question are surveys. And that\'s what WP Squeeze Question Plugins works.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/WP-Squeeze-Question-Plugin.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/Jf9QWKQBR4cvmqso.png', 'wordpress, plugins, squeeze'),
(178, 'How To Use WordPress Plugins', 'Find the right programmer who can create your hot-selling WordPress Plugins.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/How-To-WordPress-Plugins.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/c2FNPFMZ69wQaDM0.png', 'wordpress, plugins, online'),
(179, 'Google Optimisation Blue Print', 'Google and other search engines have become much smarter than ever. What it means is that they are constantly trying to figure out what your website is all about.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Google-Optimization-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/bvToqZbeHQxnv141.png', 'google, blueprint, business'),
(180, 'Facebook Ad Tracking', 'The brand new 9 part, step-by-step video course. Discover what you need to do before setting up your Facebook Ads to get results.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Facebook-Ad-Tracking.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/aQQOVoWeaChqe8Oy.png', 'facebook, ad, video'),
(181, 'Customer Intelliegence Sniper', 'Discover how to attract rapid buyers to your products and services. This 8-part video course is designed to show you how you can attract the perfect buyer who wants to buy all your products and services.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Customer-Intelligence-Sniper.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/F72ZPgyNZbD75Jtu.png', 'customer, video, course'),
(182, 'Covert Profit Selling Principles', 'Multiply your marketing and advertising efforts on the internet.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Covert-Product-Selling-Principles.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/8CqfJs3MldpvfMpt.png', 'marketing, advertising, profit'),
(183, 'Attracting Clients ', 'These articles are all about attracting clients. This is a collection of premium PLR articles all about attracting clients.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/Attracting-Clients-PLR-Articles.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/gkmkKNW6oh93S0kQ.png', 'plr, articles, clients'),
(184, 'Ads Shake', 'Ads and offers on your website can be worth their weight in gold.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/new23/AD-Shake.zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/hak1yQz8RDnY9h4m.png', 'ads, website, business'),
(185, 'Product Launch Ignition', 'Discover how to launch your product, without your server crashing, losing lots of time and money along the way. You get access to step-by-step videos that show how to do it the right way. Using real experience, not theory.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Product-Launch-Ignition+(1).zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/bAI05uQxbZmTxosg.png', 'product, launch, tutorial'),
(186, 'Pinterest Anatomy', 'Its a fact that Pinterest is huge and has tons of prospects waiting to find you.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Pinterest-Anatomy.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/ovUp7nHHDCSpPKNv.png', 'pinterest, business, online'),
(187, 'Email Marketing Fundamentals', 'Discover how to set up your email autoresponder with GetResponse so that you can grow an email list that gets clicks and converts sales, starting today.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Email-Marketing-Fundamentals+(1).zip\n', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/wOecJtYZ9woHn3Oy.png', 'marketing, email, sales'),
(188, 'Blogbook', 'Turn any WordPress into a beautiful re-sellable eBook.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/BlogBook-Plugin.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/XFNma6Hi0WYVyeo2.png', 'wordpress, ebook, blog'),
(189, 'Blog And Ping Automator', 'Automated blog and ping solutions for super fast search engine saturation.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Blog-and-Ping-Automator.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/PqWJ3SxDS6QJcCnI.png', 'blog, ping, seo'),
(190, 'Article Submitter', 'Article Submitter is the Fastest and Easiest way to get your articles posted on hundreds of article directories without spending hours and hours on the manual labor of doing so.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Article-Submitter.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/FK0eXXTucVPvnVDn.png', 'article, online, business'),
(191, 'Advanced Traffic Blueprint', 'You will learn such things as organic search, YouTube organics traffic, solo ads, product creation, forum signature marketing, Amazon Kindle, Udemy and much more.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Advanced-Traffic-Blueprint.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/nXWtIMNVzhUdhQ00.png', 'traffic, youtube, training'),
(192, 'Ad Tracking Pro', 'Ad Tracking Pro is a new unparalleled web statistics/ad-management/scientific testing system designed specifically to continually boost and downright explode your business.', 'https://inspiredsoft.s3.amazonaws.com/BONUSES/New+Bonuses/Ad-Tracking-Pro.zip', 'https://inspiredsoft.s3.amazonaws.com/thumbsai/JlyAeNxmMOtCrsSM.png', 'ad, tracking, business');

ALTER TABLE `bonuses`
  ADD PRIMARY KEY (`bonus_id`);

ALTER TABLE `bonuses`
  MODIFY `bonus_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

COMMIT;

			";
	$db->exec("SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';");
			
     $db->exec($sql);
	 
    $sql = "
    CREATE TABLE IF NOT EXISTS `sales` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `platform` int(11) NOT NULL COMMENT '0-Internal, 1-warriorplus',
        `internal_product_id` int(11) NOT NULL COMMENT '0=test, 1=Credit',
        `pack_name` text NOT NULL,
        `name` text NOT NULL,
        `email` text NOT NULL,
        `buyer_ip` text NOT NULL,
        `purchase_date` datetime NOT NULL,
        `cancel_date` datetime NOT NULL,
        `txid` text NOT NULL,
        `item_number` text NOT NULL,
        `item_name` text NOT NULL,
        `credits` int(11) NOT NULL,
        `user_id` int(11) NOT NULL,
        `sale_amount` text NOT NULL,
        `sale_currency` text NOT NULL,
		`type` int(11) NOT NULL COMMENT '0=one time sale, 1=recurring sale',
        PRIMARY KEY (`id`)
    ) DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;
    ";

    $db->exec($sql);
	 
	 $db=null;
} catch(PDOException $e) {
    echo "DATABASE ERROR : ".$e->getMessage();
	die();
}
	

$configpacks=<<<EOT
<?php
\$wplus_pack_description_1='';
\$wplus_pack_credits_1='';
\$wplus_pack_cart_url_1='';
\$wplus_pack_product_id_1='';

\$wplus_pack_description_2='';
\$wplus_pack_credits_2='';
\$wplus_pack_cart_url_2='';
\$wplus_pack_product_id_2='';

\$wplus_pack_description_3='';
\$wplus_pack_credits_3='';
\$wplus_pack_cart_url_3='';
\$wplus_pack_product_id_3='';

\$wplus_pack_description_4='';
\$wplus_pack_credits_4='';
\$wplus_pack_cart_url_4='';
\$wplus_pack_product_id_4='';

\$wplus_pack_description_5='';
\$wplus_pack_credits_5='';
\$wplus_pack_cart_url_5='';
\$wplus_pack_product_id_5='';

\$wplus_pack_description_6='';
\$wplus_pack_credits_6='';
\$wplus_pack_cart_url_6='';
\$wplus_pack_product_id_6='';

\$wplus_pack_description_7='';
\$wplus_pack_credits_7='';
\$wplus_pack_cart_url_7='';
\$wplus_pack_product_id_7='';

\$wplus_pack_description_8='';
\$wplus_pack_credits_8='';
\$wplus_pack_cart_url_8='';
\$wplus_pack_product_id_8='';

\$wplus_pack_description_9='';
\$wplus_pack_credits_9='';
\$wplus_pack_cart_url_9='';
\$wplus_pack_product_id_9='';

\$wplus_pack_description_10='';
\$wplus_pack_credits_10='';
\$wplus_pack_cart_url_10='';
\$wplus_pack_product_id_10='';

?>

EOT;



file_put_contents("configpacks.php",$configpacks);		
		

@unlink("setup.php");
exit();



?>
